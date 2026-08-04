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
        // 1. Validate the incoming data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt to sign the host in
        if (Auth::guard('host')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('host')->user();

            // Check if the host account is suspended
            if ($user->status !== 'active') {
                Auth::guard('host')->logout();

                return redirect()
                    ->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Your Account is Suspended'); // Fixed casing
            }

            // Session security check passed
            $request->session()->regenerate();

            return redirect()
                ->route('host.dashboard')
                ->with('success', 'Login Success'); // Fixed casing to lowercase 'success'
        }

        // 3. Failed authentication attempt
        return redirect()
            ->back() // Sends them back to the login page form
            ->withInput($request->only('email')) // Keeps the email filled in for them
            ->with('error', 'Invalid Credentials'); // Fixed typo from 'Errror' to 'error'
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
        // First validate basic formatting without unique constraint for CRM tracking
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
        ]);

        // CRM Logic: Track registration attempts
        $crm = \App\Models\Crm::where('mobile', $request->mobile)->first();
        $welcomeBack = false;

        if ($crm) {
            $crm->increment('attempts_count');
            $welcomeBack = true;
        } else {
            \App\Models\Crm::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'attempts_count' => 1
            ]);
        }

        // Now validate uniqueness for actual host creation
        // If they are already a fully registered host, this will throw an error and redirect back
        $request->validate([
            'email' => 'unique:host,email',
            'mobile' => 'unique:host,mobile',
        ]);

        $otp = rand(100000, 999999);

        $request->session()->put('register_data', [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'otp' => $otp,
            'otp_verified' => false,
        ]);

        $smsSent = $this->sendWhatsAppOtp($request->mobile, $otp);

        if (!$smsSent) {
            return redirect()->back()->withInput()->with('error', 'Failed to send otp to your mobile number');
        }

        if ($welcomeBack) {
            return redirect()->route('host.verify.form')->with('success', 'Welcome back! You are logging in again for the packages. Code Sent successful!');
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

        $sessionData['otp_verified'] = true;
        $request->session()->put('register_data', $sessionData);

        return redirect()->route('host.register.packages')->with('success', 'OTP Verified! Please select a package.');
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


    public function showRegisterPackagesForm()
    {
        $sessionData = session('register_data');
        if (!$sessionData || empty($sessionData['otp_verified'])) {
            return redirect()->route('host.register')->with('error', 'Please verify OTP first.');
        }

        $packages = \App\Models\Package::all();
        return view('host.register-packages', compact('packages'));
    }

    public function initRegisterPayment(Request $request)
    {
        $sessionData = session('register_data');
        if (!$sessionData || empty($sessionData['otp_verified'])) {
            return response()->json(['error' => 'Not authenticated for registration'], 401);
        }

        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);

        $package = \App\Models\Package::find($request->package_id);
        
        $priceParts = explode(' ', trim($package->price));
        $activePriceStr = count($priceParts) > 1 && is_numeric($priceParts[0]) ? implode(' ', array_slice($priceParts, 1)) : $package->price;
        $numericPrice = (int) preg_replace('/[^0-9]/', '', $activePriceStr);

        if ($numericPrice <= 0) {
            return response()->json(['error' => 'Invalid package price for payment'], 400);
        }

        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $order = $api->order->create([
                'receipt'         => 'order_rcptid_' . uniqid(),
                'amount'          => $numericPrice * 100, // amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ]);

            return response()->json([
                'order_id' => $order['id'],
                'amount'   => $numericPrice * 100,
                'key'      => env('RAZORPAY_KEY'),
                'package_name' => $package->package_name,
                'host_name' => $sessionData['name'] ?? '',
                'host_email' => $sessionData['email'] ?? ''
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay Order Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyRegisterPayment(Request $request)
    {
        $sessionData = session('register_data');
        if (!$sessionData || empty($sessionData['otp_verified'])) {
            return redirect()->route('host.register')->with('error', 'Please restart registration.');
        }

        try {
            $request->validate([
                'package_id' => 'required|exists:packages,id',
                'razorpay_payment_id' => 'nullable|string',
                'razorpay_order_id' => 'nullable|string',
                'razorpay_signature' => 'nullable|string',
            ]);

            $package = \App\Models\Package::find($request->package_id);
            $priceParts = explode(' ', trim($package->price));
            $activePriceStr = count($priceParts) > 1 && is_numeric($priceParts[0]) ? implode(' ', array_slice($priceParts, 1)) : $package->price;
            $numericPrice = (int) preg_replace('/[^0-9]/', '', $activePriceStr);

            if ($numericPrice > 0) {
                if (!$request->razorpay_payment_id || !$request->razorpay_signature) {
                    return redirect()->back()->with('error', 'Payment details are missing.');
                }

                $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                try {
                    $attributes = array(
                        'razorpay_order_id' => $request->razorpay_order_id,
                        'razorpay_payment_id' => $request->razorpay_payment_id,
                        'razorpay_signature' => $request->razorpay_signature
                    );
                    $api->utility->verifyPaymentSignature($attributes);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Payment verification failed: ' . $e->getMessage());
                }
            }

            // Payment successful, store package ID in session
            $sessionData['package_id'] = $request->package_id;
            session(['register_data' => $sessionData]);

            return redirect()->route('host.register.set-password.view')->with('success', 'Payment successful! Please set your password.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function showRegisterSetPasswordForm()
    {
        $sessionData = session('register_data');
        if (!$sessionData || empty($sessionData['package_id'])) {
            return redirect()->route('host.register')->with('error', 'Please complete payment first.');
        }
        return view('host.register-set-password', compact('sessionData'));
    }

    public function submitRegisterSetPassword(Request $request)
    {
        $sessionData = session('register_data');
        if (!$sessionData || empty($sessionData['package_id'])) {
            return redirect()->route('host.register')->with('error', 'Session expired. Please restart registration.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $defaultPermissions = ['ceremonies', 'gallery', 'invitation', 'save-the-date', 'guest-list', 'reports', 'categories'];

        $package = \App\Models\Package::find($sessionData['package_id']);
        $expiresAt = null;
        if ($package && $package->validity) {
            $validityLower = strtolower(trim($package->validity));
            if (str_contains($validityLower, 'year')) {
                $num = (int) filter_var($validityLower, FILTER_SANITIZE_NUMBER_INT) ?: 1;
                $expiresAt = now()->addYears($num);
            } elseif (str_contains($validityLower, 'month')) {
                $num = (int) filter_var($validityLower, FILTER_SANITIZE_NUMBER_INT) ?: 1;
                $expiresAt = now()->addMonths($num);
            } elseif (str_contains($validityLower, 'day')) {
                $num = (int) filter_var($validityLower, FILTER_SANITIZE_NUMBER_INT) ?: 1;
                $expiresAt = now()->addDays($num);
            }
        }

        $host = Host::create([
            'name' => $sessionData['name'],
            'email' => $sessionData['email'],
            'mobile' => $sessionData['mobile'],
            'password' => Hash::make($request->password),
            'status' => 'active',
            'is_password_set' => true,
            'permissions' => $defaultPermissions,
            'package_id' => $sessionData['package_id'],
            'package_status' => 'active',
            'package_expires_at' => $expiresAt,
        ]);

        session()->forget('register_data');
        Auth::guard('host')->login($host);

        return redirect()->route('host.wizard.index')->with('success', "Account Created Successfully! Let's set up your wedding info.");
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
