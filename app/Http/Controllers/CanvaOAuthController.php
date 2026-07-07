<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CanvaOAuthController extends Controller
{
    private function getClientId()
    {
        return config('services.canva.client_id', env('CANVA_CLIENT_ID'));
    }

    private function getClientSecret()
    {
        return config('services.canva.client_secret', env('CANVA_CLIENT_SECRET'));
    }

    private function getRedirectUri()
    {
        return url('/oauth/canva/callback');
    }

    public function redirect(Request $request)
    {
        $codeVerifier = Str::random(128);
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        $request->session()->put('canva_code_verifier', $codeVerifier);
        $request->session()->put('canva_template_id', $request->input('template_id'));
        $request->session()->put('canva_ceramony_id', $request->input('ceramony_id'));

        $query = http_build_query([
            'client_id' => $this->getClientId(),
            'response_type' => 'code',
            'redirect_uri' => $this->getRedirectUri(),
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
            'scope' => 'design:content:read design:content:write',
        ]);

        return redirect('https://www.canva.com/api/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $error = $request->input('error');
        
        if ($error || !$code) {
            return redirect()->route('host.ceramony.index')->with('error', 'Canva authorization failed: ' . $error);
        }

        $codeVerifier = $request->session()->pull('canva_code_verifier');
        $templateId = $request->session()->pull('canva_template_id');
        $ceramonyId = $request->session()->pull('canva_ceramony_id');

        $clientId = $this->getClientId();
        $clientSecret = $this->getClientSecret();
        
        $authHeader = base64_encode("{$clientId}:{$clientSecret}");

        $response = Http::withHeaders([
            'Authorization' => "Basic {$authHeader}",
        ])->asForm()->post('https://api.canva.com/rest/v1/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'code_verifier' => $codeVerifier,
            'redirect_uri' => $this->getRedirectUri(),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $host = Auth::guard('host')->user();
            if ($host) {
                $host->update([
                    'canva_access_token' => $data['access_token'],
                    'canva_refresh_token' => $data['refresh_token'],
                    'canva_token_expires_at' => now()->addSeconds($data['expires_in']),
                ]);
            }

            // Create design
            $createData = [
                'design_type' => [
                    'type' => 'custom',
                    'width' => 1080,
                    'height' => 1920
                ]
            ];
            
            if ($templateId) {
                $createData = [
                    'asset_id' => $templateId
                ];
            }

            $designResponse = Http::withToken($data['access_token'])
                ->post('https://api.canva.com/rest/v1/designs', $createData);

            if ($designResponse->successful()) {
                $designData = $designResponse->json();
                $designId = $designData['design']['id'] ?? '';
                $designUrl = $designId ? "https://www.canva.com/design/{$designId}/edit" : '';
                
                return redirect()->route('host.ceramony.index')->with('success', 'Design created! Please open this URL to edit it: ' . $designUrl);
            } else {
                return redirect()->route('host.ceramony.index')->with('error', 'Failed to create Canva design: ' . $designResponse->body());
            }
        }

        return redirect()->route('host.ceramony.index')->with('error', 'Failed to get Canva token: ' . $response->body());
    }
}
