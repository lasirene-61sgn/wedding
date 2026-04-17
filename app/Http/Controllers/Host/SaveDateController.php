<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\SaveDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaveDateController extends Controller
{
    public function index(){
        $savedates = SaveDate::where('host_id', Auth::id())->get();
        return view('host.savedate.index', compact('savedates'));
    }

    public function create(){
        $invitations = Invitation::where('host_id', Auth::id())->get();
        return view('host.savedate.create', compact('invitations'));
    }

    public function store(Request $request){
        $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'image' => 'required|mimes:jpg,jpeg,webp,gis,avif,svg|max:4048',
            'message' => 'nullable|string|max:100',
        ]);
        $data = [
            'host_id' => Auth::id(),
            'invitation_id' => $request->invitation_id,
            'image' => $request->file('image')->store('savedates', 'public'),
            'message' => $request->message,
        ];
        SaveDate::create($data);
        return redirect()->route('host.savedate.index')->with('Success', 'Save the date added');
    }

    public function edit($id){
        $savedate = SaveDate::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        return view('host.savedate.edit', compact('savedate'));
    }

    public function update(Request $request, $id){
        $savedate = SaveDate::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'image' => 'nullable|mimes:jpg,jpeg,webp,gif,avif,svg|max:4048',
            'message' => 'nullable|string|max:100',
        ]);
        if($request->hasFile('image')){
            if($savedate->image && Storage::disk('public')->exists($savedate->image)){
                Storage::disk('public')->delete($savedate->image);
            }
            $validated['image'] = $request->file('image')->store('savedates', 'public');
        }else {
            unset($validated['image']);
        }
        $savedate->update($validated);
        return redirect()->route('host.savedate.index')->with('Success', 'Updated Successfully');
    }
}
