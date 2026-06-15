<?php

namespace App\Http\Controllers;

use App\Models\GuestList;
use Illuminate\Http\Request;

class GuestRSVPController extends Controller
{
    /**
     * Show the unique wedding invitation card portal to the guest
     */
    public function showPortal($id)
    {
        $guest = GuestList::findOrFail($id);

        // Auto-login the guest by saving their phone number in the session
        session(['guest_phone' => $guest->guest_number]);

        // Redirect to their invitation dashboard
        return redirect()->route('guest.wedding.details', $guest->id);
    }

    /**
     * Handle the Accept or Reject submission choices
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'rsvp_status' => 'required|in:accepted,declined,pending'
        ]);

        $guest = GuestList::findOrFail($id);

        $guest->update([
            'rsvp_status' => $request->rsvp_status
        ]);

        return back()->with('success', 'Thank you! Your RSVP status has been updated.');
    }
}
