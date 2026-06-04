<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('host.select-package', compact('packages'));
    }

    public function select(Request $request)
    {
        // 1. Check authentication FIRST before validating input data
        $host = Auth::guard('host')->user();

        if (!$host) {
            return redirect()->route('host.login')->with('error', 'Your session expired. Please login again.');
        }

        try {
            // 2. Validate incoming request parameters
            $request->validate([
                'package_id' => 'required|exists:packages,id'
            ]);

            // 3. Update host credentials
            $host->update([
                'package_id'     => $request->package_id,
                'package_status' => 'active',
            ]);

            // 4. Redirect onward to the wizard workflow
            return redirect()->route('host.wizard.index')->with('success', "Package Activated! Let's set up your wedding info.");

        } catch (ValidationException $e) {
            // Let Laravel handle standard validation rules naturally
            throw $e;
        } catch (\Exception $e) {
            // Catch actual code, database, or connection breakages
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}