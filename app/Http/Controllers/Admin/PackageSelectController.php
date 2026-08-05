<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageSelectController extends Controller
{
    public function index(){
        $packages = Package::with('customFeatures')->get();
        return view('admin.package.index', compact('packages'));
    }

    public function create(){
        return view('admin.package.create');
    }

    public function store(Request $request){
        $request->validate([
            'package_name' => 'required',
            'price' => 'required|string',
            'guest_limit' => 'required|integer',
            'validity' => 'required',
            'invitaion' => 'required',
            'rsvp' => 'required',
            'ceramonies' => 'required',
            'reports' => 'required',
            'gallery' => 'required',
            'package_description' => 'required',
            'wishboard' => 'nullable',
            'dcgqrcode' => 'nullable',
            'vaf' => 'nullable',
            'sms_limit' =>' nullable|integer',
            'email_limit' =>' nullable|integer',
            'whatsapp_limit' =>' nullable|integer',
            'storage_limit_mb' => 'nullable|integer',
            'custom_fields' => 'nullable|array',
            
        ]);

        $package = Package::create($request->all());

        //condition for adding custom fields
        if($request->has('custom_fields')){
            foreach($request->custom_fields as $field)
            {
                if(!empty($field['label']) && !empty($field['value'])){
                    $package->customFeatures()->create([
                        'field_label' => $field['label'],
                        'field_type'  => $field['type'],
                        'field_value'  => $field['value'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.package.index')->with('Success', "Package Created Successfully");
    }

    public function edit($id){
        $package = Package::with('customFeatures')->findOrFail($id);
        return view('admin.package.edit', compact('package'));
    }

    public function update(Request $request, $id){
        $package = Package::findOrFail($id);
        $request->validate([
            'package_name' => 'required',
            'price' => 'required|string',
            'guest_limit' => 'required|integer',
            'validity' => 'required',
            'invitaion' => 'required',
            'rsvp' => 'required',
            'ceramonies' => 'required',
            'reports' => 'required',
            'gallery' => 'required',
            'package_description' => 'required',
            'wishboard' => 'nullable',
            'dcgqrcode' => 'nullable',
            'vaf' => 'nullable',
            'sms_limit' =>' nullable|integer',
            'email_limit' => 'nullable|integer',
            'whatsapp_limit' => 'nullable|integer',
            'storage_limit_mb' => 'nullable|integer',
            'custom_fields' => 'nullable|array',
        ]);

        $package->update($request->all());

        $package->customFeatures()->delete();

        if($request->has('custom_fields')){
            foreach($request->custom_fields as $field){
                if(!empty($field['label']) && !empty($field['value'])){
                    $package->customFeatures()->create([
                        'field_label' => $field['label'],
                        'field_type'  => $field['type'],
                        'field_value'  => $field['value'],
                    ]);
                }
            }
        }
        return redirect()->route('admin.package.index')->with('Success', 'Package Updated Successfully');
    }

    public function destroy($id){
        $package = Package::findOrFail($id);
        $package->delete();
        return redirect()->route('admin.package.index')->with('Success', 'Package Deleted Successfully');
    }
}
