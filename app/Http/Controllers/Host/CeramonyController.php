<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\CeramonyBackground;
use App\Models\VenueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class CeramonyController extends Controller
{
    /**
     * Helper method to securely get the active Canva Access Token.
     */
    private function getCanvaToken()
    {
        // For development, you can return a hardcoded token, 
        // or fetch it from your authenticated user/host model if stored there.
        return Auth::user()->canva_token ?? env('CANVA_TEMPORARY_TOKEN', 'YOUR_ACCESS_TOKEN_HERE');
    }

    /**
     * Helper method to dispatch a template compilation job to Canva's design servers.
     */
    private function generateCanvaDesign($templateId, $name, $date, $time)
    {
        $token = $this->getCanvaToken();

        // 1. Dispatch the asynchronous autofill job to Canva
        $response = Http::withToken($token)
            ->post('https://api.canva.com/rest/v1/autofills', [
                'brand_template_id' => $templateId,
                'data' => [
                    'Title' => [
                        'type' => 'text',
                        'text' => $name
                    ],
                    'Date' => [
                        'type' => 'text',
                        'text' => $date ?? 'To Be Announced'
                    ],
                    'Time' => [
                        'type' => 'text',
                        'text' => $time ?? ''
                    ]
                ]
            ]);

        if (!$response->successful()) {
            return null;
        }

        $jobData = $response->json();
        $jobId = $jobData['job']['id'] ?? null;

        if (!$jobId) {
            return null;
        }

        // 2. Poll the job status endpoint until Canva finishes generating the design structure
        $maxAttempts = 5;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $statusResponse = Http::withToken($token)
                ->get("https://api.canva.com/rest/v1/autofills/{$jobId}");

            if ($statusResponse->successful()) {
                $statusData = $statusResponse->json();
                $status = $statusData['job']['status'] ?? 'failed';

                if ($status === 'success') {
                    // Extract the final user-editable Canva workspace URL
                    return $statusData['job']['result']['design']['url'] ?? null;
                }

                if ($status === 'failed') {
                    break;
                }
            }

            $attempt++;
            sleep(1); // Wait 1 second before polling again
        }

        return null;
    }

    /**
     * Helper to resolve Canva shortlinks and force /view for embeds
     */
    private function resolveCanvaPublicLink($url) {
        if (!$url) return null;
        
        if (str_contains($url, 'canva.link')) {
            $headers = @get_headers($url, 1);
            if ($headers && isset($headers['Location'])) {
                $url = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
            }
        }
        
        $parsed = parse_url($url);
        if (isset($parsed['path'])) {
            if (preg_match('/^\/design\/([A-Za-z0-9_-]+)/', $parsed['path'], $matches)) {
                return "https://www.canva.com/design/" . $matches[1] . "/view";
            }
        }
        
        return $url;
    }

    public function index()
    {
        $ceramonies = Ceramonies::with(['category', 'venue'])->where('host_id', Auth::id())->get();
        return view('host.ceramony.index', compact('ceramonies'));
    }

    public function create()
    {
        $categories = CategoryVenue::all();
        $venues = VenueName::where('host_id', Auth::id())->get();
        $backgrounds = CeramonyBackground::all();
        return view('host.ceramony.create', compact('categories', 'venues', 'backgrounds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'            => 'required|exists:category_venues,id',
            'venue_id'               => 'nullable|exists:venue_names,id',
            'ceramony_name'          => 'required|string|max:255',
            'ceramony_date'          => 'nullable|date',
            'ceramony_time'          => 'nullable',
            'ceramony_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'selected_background_id' => 'nullable|exists:ceramony_backgrounds,id',
            'text_color'             => 'nullable|string|max:7',
            'details_color'          => 'nullable|string|max:7',
            'text_positions'         => 'nullable|string',
            'custom_canvas_texts'    => 'nullable|string',
            'canva_template_id'      => 'nullable|string',
            'canva_design_url'       => 'nullable|string',
            'canva_public_link'      => 'nullable|url|max:1000'
        ]);

        if ($request->venue_id) {
            $checkVenue = VenueName::where('id', $request->venue_id)->where('host_id', Auth::id())->first();
            if (!$checkVenue) {
                return redirect()->route('host.ceramony.create')->with('error', 'venue nto found');
            }
        }
        
        $validated['host_id'] = Auth::id();
        
        if (isset($validated['text_positions'])) {
            $validated['text_positions'] = json_decode($validated['text_positions'], true);
        }

        if (isset($validated['custom_canvas_texts'])) {
            $validated['custom_canvas_texts'] = json_decode($validated['custom_canvas_texts'], true);
        }

        if($request->hasFile('ceramony_image')){
            $newFileSize = $request->file('ceramony_image')->getSize();
            if (!\App\Services\StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }

        if (!empty($validated['canva_public_link'])) {
            $validated['canva_public_link'] = $this->resolveCanvaPublicLink($validated['canva_public_link']);
        }

        // --- CANVA COMPILATION API PATH ---
        if (!empty($validated['canva_template_id']) && empty($validated['canva_design_url'])) {
            $designUrl = $this->generateCanvaDesign(
                $validated['canva_template_id'],
                $validated['ceramony_name'],
                $validated['ceramony_date'],
                $validated['ceramony_time']
            );

            if ($designUrl) {
                $validated['canva_design_url'] = $designUrl;
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to generate invitation preview inside Canva. Please verify configuration parameters.');
            }
        }
        // ----------------------------------

        Ceramonies::create($validated);
        return redirect()->route('host.ceramony.index')->with('success', 'ceramony created successfully');
    }

    public function edit(Ceramonies $ceramony)
    {
        if ($ceramony->host_id != Auth::id()) {
            abort(403);
        }
        if ($ceramony->is_main) {
            return redirect()->back()->with('error', 'YOu cannot edit');
        }
        $categories = CategoryVenue::all();
        $venues = VenueName::where('host_id', Auth::id())->get();
        $backgrounds = CeramonyBackground::all();
        return view('host.ceramony.edit', compact('categories', 'ceramony', 'venues', 'backgrounds'));
    }

    public function update(Request $request, Ceramonies $ceramony)
    {
        if ($ceramony->host_id != Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'category_id'            => 'required|exists:category_venues,id',
            'venue_id'               => 'nullable|exists:venue_names,id',
            'ceramony_name'          => 'required|string|max:255',
            'ceramony_date'          => 'nullable|date',
            'ceramony_time'          => 'nullable',
            'ceramony_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'selected_background_id' => 'nullable|exists:ceramony_backgrounds,id',
            'text_color'             => 'nullable|string|max:7',
            'details_color'          => 'nullable|string|max:7',
            'text_positions'         => 'nullable|string',
            'custom_canvas_texts'    => 'nullable|string',
            'canva_template_id'      => 'nullable|string',
            'canva_design_url'       => 'nullable|string',
            'canva_public_link'      => 'nullable|url|max:1000'
        ]);

        if (isset($validated['text_positions'])) {
            $validated['text_positions'] = json_decode($validated['text_positions'], true);
        }

        if (isset($validated['custom_canvas_texts'])) {
            $validated['custom_canvas_texts'] = json_decode($validated['custom_canvas_texts'], true);
        }

        if ($request->hasFile('ceramony_image')) {
            if($ceramony->ceramony_image){
                Storage::disk('public')->delete($ceramony->ceramony_image);
            }
            $newFileSize = $request->file('ceramony_image')->getSize();
            if (!\App\Services\StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }

        // --- UPDATE DYNAMIC LAYOUT IN CANVA ---
        $templateToUse = $validated['canva_template_id'] ?? $ceramony->canva_template_id;
        
        if (!empty($templateToUse) && empty($validated['canva_design_url'])) {
            $designUrl = $this->generateCanvaDesign(
                $templateToUse,
                $validated['ceramony_name'],
                $validated['ceramony_date'],
                $validated['ceramony_time']
            );

            if ($designUrl) {
                $validated['canva_design_url'] = $designUrl;
            }
        }
        // --------------------------------------

        if (!empty($validated['canva_public_link'])) {
            $validated['canva_public_link'] = $this->resolveCanvaPublicLink($validated['canva_public_link']);
        }

        $ceramony->update($validated);
        return redirect()->route('host.ceramony.index', $ceramony->id)->with('success', 'Ceramony Updated');
    }

    public function destroy(Ceramonies $ceramony)
    {
        if ($ceramony->host_id != Auth::id()) abort(403);

        // Prevent deletion of the Main Wedding
        if ($ceramony->is_main) {
            return redirect()->back()->with('error', 'You cannot delete the Main Wedding ceremony. Delete the Invitation instead.');
        }

        if ($ceramony->ceramony_image) {
            Storage::disk('public')->delete($ceramony->ceramony_image);
        }

        $ceramony->delete();
        return redirect()->route('host.ceramony.index')->with('success', 'Ceremony deleted successfully');
    }
}