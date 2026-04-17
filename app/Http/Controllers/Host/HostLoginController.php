<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostLoginController extends Controller
{
     public function showLoginForm(){
        return view('host.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->guard('host')->attempt($credentials, $request->filled('remember'))){
            $user = Auth::guard('host')->user();
            if($user->status !== 'active'){
                Auth::guard('host')->logout();
                return redirect()->back()->withInput($request->only('email'))->with('error', 'Your Accoutn is Suspended');
            }
            $request->session()->regenerate();
            return redirect()->route('host.dashboard')->with('Success', 'Login Success');
        }
        return redirect()->route('host.login')->with('Errror', 'Invalid Crdentials');
    }

    public function logout(Request $request){
        Auth::guard('host')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('host.login')->with('Success', 'Logout Success');
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
