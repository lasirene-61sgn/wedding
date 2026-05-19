<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                return redirect()->back()->withInput($request->only('email'))->with('error', 'Your Accoutn is Suspended');
            }
            $request->session()->regenerate();
            return redirect()->route('host.dashboard')->with('Success', 'Login Success');
        }
        return redirect()->route('host.login')->with('Errror', 'Invalid Crdentials');
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
            'email' => 'required|email|unique:host,email', // Added unique check
            'password' => 'required|string|min:8',
            'mobile' => 'required|numeric|unique:host,mobile',
        ]);

        $defaultModules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories'];

        $defaultPermissionSlugs = array_map(fn($module) => Str::slug($module), $defaultModules);

        $host = Host::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => 'active',
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'is_password_set' => true, // Manual users already set their password
        ]);

        auth()->guard('host')->login($host);
        return redirect()->route('host.packages.index')->with('success', 'Registration successful!');
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
                // Update Google ID if they already existed
                $host->update(['google_id' => $googleUser->getId()]);
            }

            Auth::guard('host')->login($host);

            // Redirect logic
            if (!$host->is_password_set) {
                return redirect()->route('host.set-password.view');
            }

            // If they already have a package, go to dashboard, else go to packages
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
