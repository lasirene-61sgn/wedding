<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlannerLoginController extends Controller
{
    public function showLoginForm(){
        return view('planner.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->guard('planner')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('planner.dashboard')->with('Success', 'Login Success');
        }

        return redirect()->back()->withErrors('Error', 'Check Your Credetials');
    }

    public function logout(Request $request){
        Auth::guard('planner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('planner.login')->with('Success', 'Logout Success');
    }

    public function dashboard(){
        return view('planner.dashboard');
    }
}
