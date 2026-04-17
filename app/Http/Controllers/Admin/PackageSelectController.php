<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageSelectController extends Controller
{
    public function index(){
        $packages = Package::all();
        return view('admin.package.index', compact('packages'));
    }

    public function create(){
        return view('admin.package.create');
    }

    public function store(Request $request){
        $request->validate([
            'package_name' => 'required',
            'package_description' => 'required',
            'price' => 'required|numeric',
            'invite_limit' =>' required|integer',
            'guest_limit' => 'required|integer',
        ]);

        Package::create($request->all());

        return redirect()->route('admin.package.index')->with('Success', "Package Created Successfully");
    }

    public function edit($id){
        $package = Package::findOrFail($id);
        return view('admin.package.edit', compact('package'));
    }

    public function update(Request $request, $id){
        $package = Package::findOrFail($id);
        $request->validate([
            'package_name' => 'nullable',
            'package_description' => 'nullable',
            'price' => 'nullable',
            'invite_limit' => 'nullable|integer',
            'guest_limit' => 'nullable|integer',
        ]);

        $package->update($request->all());
        return redirect()->route('admin.package.index')->with('Success', 'Package Updated Successfully');
    }

    public function destroy($id){
        $package = Package::findOrFail($id);
        $package->delete();
        return redirect()->route('admin.package.index')->with('Success', 'Package Deleted Successfully');
    }
}
