<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueLoginController extends Controller
{
    public function showLoginForm(){
        return view('venue.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->guard('venue')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('venue.dashboard')->with('success', 'Login Success');
        }

        return redirect()->back()->with('Error', 'Invalid Credentials');
    }

    public function logout(Request $request){
        Auth::guard('venue')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('venue.login')->with('Success', 'logout Success');
    }

    public function dashboard(){
        return view('venue.dashboard');
    }
}
