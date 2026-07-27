<?php

namespace App\Http\Controllers;

use App\Models\GuestList;
use Illuminate\Http\Request;

class GuestRSVPController extends Controller
{
    /**
     * Show the unique wedding invitation card portal to the guest
     */
    public function showPortal($uuid)
    {
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $guest = GuestList::where($column, $uuid)->firstOrFail();

        // Auto-login the guest by saving their phone number in the session
        session(['guest_phone' => $guest->guest_number]);

        // Redirect to their invitation dashboard
        return redirect()->route('guest.wedding.details', $guest->uuid);
    }

    /**
     * Handle the Accept or Reject submission choices
     */
    public function updateStatus(Request $request, $uuid)
    {
        $request->validate([
            'rsvp_status' => 'required|in:accepted,declined,pending'
        ]);

        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $guest = GuestList::where($column, $uuid)->firstOrFail();

        $guest->update([
            'rsvp_status' => $request->rsvp_status
        ]);

        return back()->with('success', 'Thank you! Your RSVP status has been updated.');
    }
}
