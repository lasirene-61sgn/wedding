<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PackageController extends Controller
{
    public function index(){
        $packages = Package::all();
        return view('host.select-package', compact('packages'));
    }

    public function select(Request $request){
        try{
            $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);
        $host = Auth::guard('host')->user();

        if(!$host){
            return redirect()->route('host.login')->with('Error', 'Login Agin');
        }
        $host->update([
            'package_id' => $request->package_id,
            'package_status' => 'active',
        ]);
        return redirect()->route('host.dashboard')->with("success", 'Package Activated');
        }catch(\Exception $e){
            dd($e->getMessage());
        }
        
    }
}
