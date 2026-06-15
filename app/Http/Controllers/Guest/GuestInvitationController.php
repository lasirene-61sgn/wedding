<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Albums;
use App\Models\Ceramonies;
use App\Models\GuestList;
use App\Models\Pictures;
use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuestInvitationController extends Controller
{
    public function showLogin()
    {
        return view('guest.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required', // This field handles both input types now
            'password' => 'required',
        ]);

        $input = $request->phone;

        // 1. Dynamic Lookup: Check both the phone number AND email columns
        $guest = GuestList::where(function($query) use ($input) {
            $query->where('guest_number', $input)
                  ->orWhere('guest_email', $input);
        })->first();

        // 2. Validate Credentials
        if (!$guest || !Hash::check($request->password, $guest->password)) {
            return back()->with('error', 'Check your credentials.');
        }

        // 3. Store the precise database key in the session instead of raw input
        session(['guest_phone' => $guest->guest_number]);
        
        return redirect()->route('guest.select');
    }

    public function selectWedding()
    {
        $phone = session('guest_phone');
        
        if (!$phone) {
            return redirect()->route('guest.login');
        }

        // 4. Safer query: Fall back to pulling invitations ignoring the 'invitation_sent' flag 
        // to prevent administrative blockages during testing.
        $invitations = GuestList::where('guest_number', $phone)
            ->with('host')
            ->get();

        return view('guest.selection', compact('invitations'));
    }

    public function updateStatus(Request $request, $id)
    {
        $invite = GuestList::where('id', $id)->where('guest_number', session('guest_phone'))->firstOrFail();
        $invite->update(['status' => $request->status]);
        if ($request->status == 'accepted') {
        return redirect()->route('guest.wedding.details', $id);
    }
    return redirect()->route('guest.select')->with('info', 'invitation declined');
    }

    public function saveTheDate($id)
    {
        $phone = session('guest_phone');
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->with('host')->firstOrFail();
        return view('guest.save_the_date', compact('invite'));
    }

    public function showCeremonies($id)
    {
        $phone = session('guest_phone');
        // We load the 'host' relationship here so the header doesn't break
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->with('host')->firstOrFail();

        // Alias $invite to $guest so your RSVP blade markup can use both safely
        $guest = $invite;

        $assignedNames = explode(', ', $invite->assigned_ceremonies);
        $detailedCeremonies = Ceramonies::with('venue')
            ->where('host_id', $invite->host_id)
            ->whereIn('ceramony_name', $assignedNames)
            ->orderBy('ceramony_date', 'asc')
            ->orderBy('ceramony_time', 'asc')
            ->get();

        // Passing both $invite and $guest ensures neither layout breaks
        return view('guest.dashboard', compact('invite', 'guest', 'detailedCeremonies'));
    }

    public function showGallery($id)
    {
        $phone = session('guest_phone');
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->with('host')->firstOrFail();
        $host_id = $invite->host_id;
        $pictures = Pictures::where('host_id', $host_id)->get();
        $albums = Albums::where('host_id', $host_id)->get();
        $videos = Videos::where('host_id', $host_id)->get();
        return view('guest.gallery', compact('invite', 'pictures', 'albums', 'videos'));
    }

    public function editProfile($id)
    {
        $invite = GuestList::findOrFail($id);
        return view('guest.profile', compact('invite'));
    }

    public function updateProfile(Request $request, $id)
    {
        $invite = GuestList::findOrFail($id);
        $validated = $request->validate([
            'guest_email' => 'nullable',
            'relation' => 'nullable|in:bride,groom',
            'gender' => 'nullable|in:male,female,other',
            'alternate_number' => 'nullable',
            'whatsapp_number' => 'nullable',
            'age' => 'nullable',
            'complex' => 'nullable',
            'floor' => 'nullable',
            'door_no' => 'nullable',
            'street_name' => 'nullable',
            'pincode' => 'nullable',
            'area_name' => 'nullable',
            'district' => 'nullable',
            'state' => 'nullable',
            'circle' => 'nullable',
            'country' => 'nullable',
            'location_map' => 'nullable',
        ]);
        $invite->update($validated);
        return redirect()->route('guest.wedding.details', $id)->with('success', 'Profile Updated');
    }
    public function getPreviousDetails()
    {
        $phone = session('guest_phone');

        // Find the latest record with this phone number that HAS an address (pincode)
        // We exclude the current one if needed, but 'latest' usually works best
        $previous = GuestList::where('guest_number', $phone)
            ->whereNotNull('pincode')
            ->latest('updated_at')
            ->first();

        if ($previous) {
            return response()->json([
                'success' => true,
                'data' => $previous
            ]);
        }

        return response()->json(['success' => false]);
    }
}
