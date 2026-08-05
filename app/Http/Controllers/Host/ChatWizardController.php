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
        // Fetch Admin added venues (we assume admin venues have null host_id or all venues)
        // Let's pass all VenueNames for now, since Admin Venue Controller creates VenueName
        $venues = \App\Models\VenueName::all();
        $categories = \App\Models\CategoryVenue::all();
        
        // Replace 'host.wizard' with the exact folder path to your blade file
        return view('host.wizard.chat', compact('venues', 'categories')); 
    }
    // 1. Store Venue
    // 1. Store Venue
    public function storeVenue(Request $request)
    {
        $validated = $request->validate([
            'venue_name'    => 'nullable|string|max:255',
            'pincode'       => 'nullable|digits:6',
            'area_name'     => 'nullable|string',
            'district'      => 'nullable|string',
            'state'         => 'nullable|string',
            'circle'        => 'nullable|string',
            'country'       => 'nullable|string',
            'venue_address' => 'nullable|string',
            'wedding_location' => 'nullable|string',
            'location_map' => 'nullable|string',
        ]);

        if (empty($validated['venue_name'])) {
            return response()->json([
                'success' => true,
                'message' => 'Venue creation skipped!',
                'venue_id' => null
            ]);
        }

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
            'venue_id' => 'nullable|exists:venue_names,id',
            'invite' => 'nullable|in:brideparents,groomparents,bride,groom,weddingcouple',
            'bride_name' => 'nullable|string',
            'bride_number' => 'nullable|string',
            'bride_email' => 'nullable|string',
            'bride_father_name' => 'nullable|string',
            'bride_mother_name' => 'nullable|string',
            'groom_name' => 'nullable|string',
            'groom_number' => 'nullable|string',
            'groom_email' => 'nullable|string',
            'groom_father_name' => 'nullable|string',
            'groom_mother_name' => 'nullable|string',
            'wedding_date' => 'nullable|date',
            'wedding_time' => 'nullable',
            'wedding_image' => 'nullable|image|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
        ]);

        // If core invitation details are missing, skip creating invitation
        if (empty($validated['bride_name']) && empty($validated['groom_name'])) {
            return response()->json([
                'success' => true,
                'message' => 'Invitation creation skipped!',
                'invitation_id' => null
            ]);
        }

        $validated['host_id'] = Auth::id();
        $validated['is_main'] = false;

        if ($request->hasFile('wedding_image')) {
            $newFileSize = $request->file('wedding_image')->getSize();
            if (!\App\Services\StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
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

        SaveDate::create([
            'host_id' => Auth::id(),
            'invitation_id' => $invitation->id,
            'image' => $invitation->wedding_image,
            'message' => 'Save the date! We are getting married.',
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

        $saveDate = SaveDate::updateOrCreate(
            ['invitation_id' => $request->invitation_id],
            [
                'host_id' => Auth::id(),
                'image' => $request->file('image')->store('savedates', 'public'),
                'message' => $request->message,
            ]
        );

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
            'venue_id'       => 'nullable|exists:venue_names,id',
            'ceramony_name'  => 'required|string|max:255',
            'ceramony_date'  => 'nullable|date',
            'ceramony_time'  => 'nullable',
            'ceramony_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $validated['host_id'] = Auth::id();

        if ($request->hasFile('ceramony_image')) {
            $newFileSize = $request->file('ceramony_image')->getSize();
            if (!\App\Services\StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }

        Ceramonies::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Additional ceremony added successfully!'
        ]);
    }
}
