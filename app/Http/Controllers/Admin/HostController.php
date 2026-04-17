<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Host;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HostController extends Controller
{
    public function index()
    {
        $hosts = Host::with(['package', 'creator'])->get();
        return view('admin.host.index', compact('hosts'));
    }


    public function create()
    {
        $packages = Package::all();
        return view('admin.host.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable',
            'email' => 'required',
            'password' => 'required|min:6',
            'mobile' => 'required|unique:host,mobile',
            'package_id' => 'required|exists:packages,id',
            'created_by' => 'nullable',
            'alternate_number' => 'nullable',
            'whatsapp_number' => 'nullable',
            'complex_name' => 'nullable',
            'floor' => 'nullable',
            'door_no' => 'nullable',
            'street_name' => 'nullable',
            'area' => 'nullable',
            'district' => 'nullable',
            'pincode' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'location_map' => 'nullable',
            'permissions' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        Host::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password,
            'package_id' => $request->package_id,
            'created_by' => Auth::id(),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.host.index')->with('success', 'Host Created Successfully');
    }

    public function edit($id)
    {
        $host = Host::findOrFail($id);
        $packages = Package::all();
        return view('admin.host.edit', compact('host', 'packages'));
    }

    public function update(Request $request, $id)
    {
       $validated = $request->validate([
            'name' => 'nullable',
            'email' => 'nullable',
            'mobile' => 'nullable|unique:host,mobile,' . $id,
            'password' => 'nullable|min:6',
            'package_id' => 'nullable|exists:packages,id',
            'created_by' => 'nullable',
            'alternate_number' => 'nullable',
            'whatsapp_number' => 'nullable',
            'complex_name' => 'nullable',
            'floor' => 'nullable',
            'door_no' => 'nullable',
            'street_name' => 'nullable',
            'area' => 'nullable',
            'district' => 'nullable',
            'pincode' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'location_map' => 'nullable',
            'permissions' => 'nullable|array',
            'status' => 'nullable|in:active,inactive',
        ]);

        $host = Host::findOrFail($id);
       
        if ($request->filled('password')) {
            $validated['password'] =Hash::make($request->password) ;
        }else{
            unset($validated['password']);
        }
        $host->update($validated);
        return redirect()->route('admin.host.index')->with('Success', 'Host Updated Successfully');
    }

    public function destroy($id)
    {
        $host = Host::findOrFail($id);
        $host->delete();
        return redirect()->route('admin.host.index')->with('success', 'Host Deleted Successfully');
    }
}
