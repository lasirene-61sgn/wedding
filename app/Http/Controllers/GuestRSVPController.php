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
        $guest = GuestList::with('ceramony')->findOrFail($id);

        // Fix: Point directly to guest/selection.blade.php
        return view('guest.selection', compact('guest'));
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