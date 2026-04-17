<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\Invitation;
use App\Models\VenueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvitationController extends Controller
{
    public function index(){
        $invitations = Invitation::where('host_id', Auth::id())->get();
        
        return view('host.invitation.index', compact('invitations'));
    }

    public function create(){
        $venues = VenueName::where('host_id', Auth::id())->get();
        return view('host.invitation.create', compact('venues'));
    }
    public function store(Request $request){
        $validated = $request->validate([
            'venue_id' => 'required|exists:venue_names,id',
            'invite' => 'required|in:brideparents,groomparents,bride,groom,weddingcouple',
            'bride_name' => 'required',
            'bride_number' => 'required',
            'bride_email' => 'required',
            'bride_father_name' => 'required',
            'bride_mother_name' => 'required',
            'groom_name' => 'required',
            'groom_number' => 'required',
            'groom_email' => 'required',
            'groom_father_name' => 'required',
            'groom_mother_name' => 'required',
            'wedding_date' => 'required',
            'wedding_time' => 'required',
            // 'wedding_location' => 'nullable',
            // 'pincode' => 'nullable',
            // 'area_name' =>  'nullable',
            // 'district' => 'nullable',
            // 'state' => 'nullable',
            // 'circle' => 'nullable',
            // 'country' => 'nullable',
            'wedding_image' => 'required|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
        ]);
        $validated['host_id'] = Auth::id();
        $validated['is_main'] = false;

        if($request->hasFile('wedding_image')){
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }

        $invitation = Invitation::create($validated);
        
        $category = CategoryVenue::firstOrCreate(['category_name' => 'Wedding']);
        Ceramonies::create([
            'host_id' => Auth::id(),
            'category_id' => $category->id,
            'venue_id' => $invitation->venue_id,
            'ceramony_name' => 'Wedding: ' .$invitation->bride_name . '&' .$invitation->groom_name,
            'ceramony_date' => $invitation->wedding_date,
            'ceramony_time' =>  $invitation->wedding_time,
            'ceramony_image' => $invitation->wedding_image,
            'is_main' => true, 
        ]);
        return redirect()->route('host.invitation.index')->with('Message', 'Invitations Created');
    }

    public function edit($id){
        $invitation = Invitation::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $venues = VenueName::where('host_id', Auth::id())->get();
        return view('host.invitation.edit', compact('invitation', 'venues'));
    }

    public function update(Request $request, $id){
        $invitation = Invitation::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'venue_id' => 'required|exists:venue_names,id',
            'invite' => 'required|in:brideparents,groomparents,bride,groom,weddingcouple',
            'theme' => 'nullable|in:classic,royal,modern',
            'bride_name' => 'required',
            'bride_number' => 'required',
            'bride_email' => 'required',
            'bride_father_name' => 'required',
            'bride_mother_name' => 'required',
            'groom_name' => 'required',
            'groom_number' => 'required',
            'groom_email' => 'required',
            'groom_father_name' => 'required',
            'groom_mother_name' => 'required',
            'wedding_date' => 'required',
            'wedding_time' => 'required',
            // 'wedding_location' => 'nullable',
            // 'pincode' => 'nullable',
            // 'area_name' =>  'nullable',
            // 'distric' => 'nullable',
            // 'state' => 'nullable',
            // 'circle' => 'nullable',
            // 'country' => 'nullable',
            'wedding_image' => 'nullable|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
        ]);
        $validated['host_id'] = Auth::id();
        if($request->hasFile('wedding_image')){
            if($invitation->wedding_image){
                Storage::disk('public')->delete($invitation->wedding_image);
            }
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }
        $invitation->update($validated);
        return redirect()->route('host.invitation.index')->with('Success', 'Invitation Updated Successfully');
    }
}
