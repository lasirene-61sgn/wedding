<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        // It's safer to always pull the fresh user from the guard
        $host = Auth::guard('host')->user();
        return view('host.profile.edit', compact('host'));
    }

    public function update(Request $request)
    {
        $host = Auth::guard('host')->user();

        // 1. Assign the validation results to $validated
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:host,email,' . $host->id,
            'alternate_number' => 'nullable',
            'whatsapp_number' => 'nullable',
            'complex_name' => 'nullable',
            'floor' => 'nullable',
            'door_no' => 'nullable',
            'street_name' => 'nullable',
            'area' => 'nullable',
            'district' => 'nullable',
            'pincode' => 'nullable|digits:6',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'location_map' => 'nullable',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // 2. Handle the password hashing
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            // Remove password from the array if it's not being updated
            unset($validated['password']);
        }

        // 3. Security: Ensure package_id and created_by are NOT in the update array
        // Even if they are in the request, $validated only contains what we defined above.

        $host->update($validated);

        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }
}