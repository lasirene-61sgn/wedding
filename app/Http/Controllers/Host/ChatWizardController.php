<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VenueName;
use App\Models\Invitation;
use App\Models\SaveDate;
use App\Models\Ceramonies;
use App\Models\CategoryVenue;

class ChatWizardController extends Controller
{
    public function index()
    {
        // Replace 'host.wizard' with the exact folder path to your blade file
        return view('host.wizard.chat'); 
    }
    // 1. Store Venue
    // 1. Store Venue
    public function storeVenue(Request $request)
    {
        $validated = $request->validate([
            'venue_name'    => 'required|string|max:255',
            'pincode'       => 'required|digits:6',
            'area_name'     => 'required|string',
            'district'      => 'required|string',
            'state'         => 'required|string',
            'circle'        => 'required|string',
            'country'       => 'required|string',
            'venue_address' => 'required|string',
            'wedding_location' => 'nullable|string',
            'location_map' => 'nullable|string',
        ]);

        $validated['host_id'] = Auth::id();
        $venue = VenueName::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Venue details saved successfully!',
            'venue_id' => $venue->id
        ]);
    }

    // 2. Store Invitation
    public function storeInvitation(Request $request)
    {
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
            'wedding_image' => 'required|image|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
        ]);

        $validated['host_id'] = Auth::id();
        $validated['is_main'] = false;

        if ($request->hasFile('wedding_image')) {
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }

        $invitation = Invitation::create($validated);

        // Auto-create default Wedding Ceremony using your logic
        $category = CategoryVenue::firstOrCreate(['category_name' => 'Wedding']);
        Ceramonies::create([
            'host_id' => Auth::id(),
            'category_id' => $category->id,
            'venue_id' => $invitation->venue_id,
            'ceramony_name' => 'Wedding: ' . $invitation->bride_name . '&' . $invitation->groom_name,
            'ceramony_date' => $invitation->wedding_date,
            'ceramony_time' =>  $invitation->wedding_time,
            'ceramony_image' => $invitation->wedding_image,
            'is_main' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation created successfully!',
            'invitation_id' => $invitation->id
        ]);
    }

    // 3. Store Save The Date
    public function storeSaveDate(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'image' => 'required|image|max:4048',
            'message' => 'nullable|string|max:100',
        ]);

        $data = [
            'host_id' => Auth::id(),
            'invitation_id' => $request->invitation_id,
            'image' => $request->file('image')->store('savedates', 'public'),
            'message' => $request->message,
        ];

        $saveDate = SaveDate::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Save the Date card saved!'
        ]);
    }

    // 4. Store Optional Extra Ceremonies (e.g., Reception, Haldi, Sangeet)
    public function storeCeremony(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:category_venues,id',
            'venue_id'       => 'required|exists:venue_names,id',
            'ceramony_name'  => 'required|string|max:255',
            'ceramony_date'  => 'nullable|date',
            'ceramony_time'  => 'nullable',
            'ceramony_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $validated['host_id'] = Auth::id();

        if ($request->hasFile('ceramony_image')) {
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }

        Ceramonies::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Additional ceremony added successfully!'
        ]);
    }
}
