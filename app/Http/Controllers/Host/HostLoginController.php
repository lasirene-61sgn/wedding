<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class HostLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('host.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('host')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('host')->user();
            if ($user->status !== 'active') {
                Auth::guard('host')->logout();
                return redirect()->back()->withInput($request->only('email'))->with('error', 'Your Account is Suspended');
            }
            $request->session()->regenerate();
            return redirect()->route('host.dashboard')->with('Success', 'Login Success');
        }
        return redirect()->route('host.login')->with('Errror', 'Invalid Credentials');
    }

    public function logout(Request $request)
    {
        Auth::guard('host')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('host.login')->with('Success', 'Logout Success');
    }

    public function showRegistrationForm()
    {
        return view('host.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:host,email',
            'password' => 'required|string|min:8',
            'mobile' => 'required|numeric|unique:host,mobile',
        ]);

        $otp = rand(100000, 999999);

        $request->session()->put('register_data', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'otp' => $otp,
        ]);

        // FIX: Match case structure precisely (sendWhatsAppOtp)
        $smsSent = $this->sendWhatsAppOtp($request->mobile, $otp);

        if (!$smsSent) {
            return redirect()->back()->withInput()->with('error', 'Failed to send otp to your mobile number');
        }

        return redirect()->route('host.verify.form')->with('success', 'Code Sent successful!');
    }

    public function showVerifyForm()
    {
        if (!session()->has('register_data')) {
            return redirect()->route('host.register');
        }
        return view('host.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
        
        $sessionData = $request->session()->get('register_data');

        if (!$sessionData) {
            return redirect()->route('host.register')->with('error', 'Session expired, Register Again');
        }

        if ($request->otp != $sessionData['otp']) {
            return redirect()->back()->with('error', 'invalid verification code');
        }

        $defaultModules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories'];
        // $defaultPermissionSlugs = array_map(fn($module) => Str::slug($module), $defaultModules);
        $defaultPermissions = ['ceremonies', 'gallery', 'invitation', 'save-the-date', 'guest-list', 'reports', 'categories'];

        // FIX: Read registration properties from $sessionData instead of raw $request input
        $host = Host::create([
            
            'name' => $sessionData['name'],
            'email' => $sessionData['email'],
            'status' => 'active',
            'password' => $sessionData['password'], // Already Hashed in step 1
            'mobile' => $sessionData['mobile'],
            'is_password_set' => true,
            'permissions' => $defaultPermissions,
        ]);

        session()->forget('register_data');
        auth()->guard('host')->login($host);
        return redirect()->route('host.packages.index')->with('success', 'Registered Successfully');
    }

    protected function sendWhatsAppOtp($mobileNumber, $otp)
    {
        try {
            $cleanNumber = preg_replace('/[^0-9]/', '', $mobileNumber);
            if (strlen($cleanNumber) === 10) {
                $cleanNumber = '91' . $cleanNumber;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authkey' => config('services.msg91.auth_key'),
            ])->post('https://control.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', [
                'integrated_number' => config('services.msg91.integrated_number'),
                'content_type' => 'template',
                'payload' => [
                    'messaging_product' => 'whatsapp',
                    'type' => 'template',
                    'template' => [
                        'name' => 'logintest',
                        'language' => [
                            'code' => 'en',
                            'policy' => 'deterministic'
                        ],
                        'namespace' => 'bc3735fb_a2e9_4e83_8b62_377bca25c09f',
                        'to_and_components' => [
                            [
                                'to' => [
                                    $cleanNumber
                                ],
                                'components' => [
                                    'body_1' => [
                                        'type' => 'text',
                                        'value' => (string)$otp
                                    ],
                                    'button_1' => [
                                        'subtype' => 'url',
                                        'type' => 'text',
                                        'value' => (string)$otp
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('MSG91 API Error Response: ' . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('MSG91 Exception Error: ' . $e->getMessage());
            return false;
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $host = Host::where('email', $googleUser->getEmail())->first();

            if (!$host) {
                $defaultPermissions = ['ceremonies', 'gallery', 'invitation', 'save-the-date', 'guest-list', 'reports', 'categories'];
                $host = Host::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'status' => 'active',
                    'password' => bcrypt(Str::random(16)),
                    'is_password_set' => false,
                    'permissions' => $defaultPermissions,
                ]);
            } else {
                $host->update(['google_id' => $googleUser->getId()]);
            }

            Auth::guard('host')->login($host);

            if (!$host->is_password_set) {
                return redirect()->route('host.set-password.view');
            }

            return $host->package_id
                ? redirect()->route('host.dashboard')
                : redirect()->route('host.packages.index');
        } catch (\Exception $e) {
            return redirect()->route('host.login')->with('error', 'Google authentication failed' . $e->getMessage());
        }
    }

    public function showSetPasswordForm()
    {
        return view('host.set-password');
    }

    public function storeSetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $host = Auth::guard('host')->user();
        $host->update([
            'password' => bcrypt($request->password),
            'is_password_set' => true
        ]);

        return redirect()->route('host.packages.index')->with('success', 'Password set! Now choose your package.');
    }

    public function dashboard()
    {
        $user = Auth::guard('host')->user();
        if ($user->status !== 'active') {
            Auth::guard('host')->logout();
            return redirect()->route('host.login')->with('error', 'Your account is suspended. Please contact Admin.');
        }

        $host_id = $user->id;

        $stats = [
            'total_guests' => \App\Models\GuestList::where('host_id', $host_id)->count(),
            'ceremonies_count' => \App\Models\Ceramonies::where('host_id', $host_id)->count(),
            'invitations_sent' => \App\Models\GuestList::where('host_id', $host_id)->where('invitation_sent', 1)->count(),
            'pending' => \App\Models\GuestList::where('host_id', $host_id)->where('status', 'pending')->count(),
            'accepted' => \App\Models\GuestList::where('host_id', $host_id)->where('status', 'accepted')->count(),
            'rejected' => \App\Models\GuestList::where('host_id', $host_id)->where('status', 'rejected')->count(),
        ];

        $ceremonies = \App\Models\Ceramonies::where('host_id', $host_id)->get(['ceramony_name', 'ceramony_date', 'ceramony_time']);

        return view('host.dashboard', compact('stats', 'ceremonies'));
    }
}