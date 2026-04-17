<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestLoginController extends Controller
{
    public function showLoginForm(){
        return view('guest.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' =>'required|email', 
            'password' => 'required',
        ]);

        if(auth()->guard('guest')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('guest.dashboard')->with('Success', 'Login Success');
        }
        return redirect()->back()->with('Error', 'Invalid Credentials');
    }

    public function logout(Request $request){
        Auth::guard('guest')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('guest.login')->with('success', 'Logout Success');
    }

    public function dashboard(){
        return view('guest.dashboard');
    }
}
