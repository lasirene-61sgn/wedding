<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\VenueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class HostInvitationController extends Controller
{
    public function index(){
        $invitations = Invitation::with(['venue', 'host' ])->latest()->paginate(10);
        return View('admin.invitation.index', compact('invitations'));
    }

    public function create(){
        $hosts = Host::all();
        $venues = VenueName::all();
        return view('admin.invitation.create', compact('hosts','venues'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'host_id' => 'required|exists:host,id',
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
            'wedding_image' => 'required|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
        ]);
        if($request->hasFile('wedding_image')){
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }

        $validated['is_main'] = false;
        $invitation = Invitation::create($validated);
        $category = CategoryVenue::firstOrCreate(['category_name' => 'Wedding']);
        Ceramonies::create([
            'host_id' => $request->host_id,
            'category_id' => $category->id,
            'venue_id' => $invitation->venue_id,
            'ceramony_name' => 'Wedding: ' .$invitation->bride_name . '&' .$invitation->groom_name,
            'ceramony_date' => $invitation->wedding_date,
            'ceramony_time' =>  $invitation->wedding_time,
            'ceramony_image' => $invitation->wedding_image,
            'is_main' => true, 
        ]);
        return redirect()->route('admin.invitation.index')->with('success', 'Invitation craeted');
    }

    public function edit($id){
        $invitation = Invitation::findorFail($id);
        $venues = VenueName::all();
        $host = Host::all();
        return view('admin.invitation.edit', compact('invitation', 'venues', 'host'));
    }

    public function update(Request $request, $id){
        $invitation = Invitation::findorFail($id);
        $validated = $request->validate([
            'host_id' => 'required|exists:host,id',
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
            'wedding_image' => 'nullable|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
        ]);
        if($request->hasFile('wedding_image')){
            if($invitation->wedding_image){
                Storage::disk('public')->delete($invitation->wedding_image);
            }
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }
        $invitation->update($validated);
        return redirect()->route('admin.invitation.index')->with('success', 'invitation updated');
    }
    public function destroy($id)
    {
        $invitation = Invitation::findOrFail($id);
        if($invitation->wedding_image){
            Storage::disk('public')->delete($invitation->wedding_image);
        }
        $invitation->delete();
        return redirect()->route('admin.invitation.index')->with('success', 'Invitation deleted');
    }
}
