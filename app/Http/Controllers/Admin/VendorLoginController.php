<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorLoginController extends Controller
{
    public function showLoginForm(){
        return view('vendor.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->guard('vendor')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('vendor.dashboard')->with('Success', 'Login Success');
        }

        return redirect()->back()->withErrors('Error', 'Check Your Credentials');
    }
    
    public function logout(Request $request){
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('vendor.login')->with('Success', 'logout Success');
    }

    public function dashboard(){
        return view('vendor.dashboard');
    }
}
