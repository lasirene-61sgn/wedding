<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VenueName;

class AdminVenueController extends Controller
{
    public function index()
    {
        $venues = VenueName::latest()->paginate(10);
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
            'venue_name'      => 'required|string|max:255',
            'pincode'         => 'required|digits:6',
            'area_name'       => 'required|string',
            'district'        => 'required|string',
            'state'           => 'required|string',
            'circle'          => 'required|string',
            'country'         => 'required|string',
            'venue_address'   => 'required|string',
            'wedding_location'=> 'nullable|string',
            'location_map'    => 'nullable|string',
        ]);

        $venue = VenueName::create($validated);

        if($request->ajax()){
            return response()->json($venue);
        }
        return redirect()->route('admin.venues.index')->with('success', 'venue created successfully');
    }

    public function edit(VenueName $venue)
    {
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, VenueName $venue)
    {
        $validated = $request->validate([
            'venue_name'      => 'required|string|max:255',
            'pincode'         => 'required|digits:6',
            'area_name'       => 'required|string',
            'district'        => 'required|string',
            'state'           => 'required|string',
            'circle'          => 'required|string',
            'country'         => 'required|string',
            'venue_address'   => 'required|string',
            'wedding_location'=> 'nullable|string',
            'location_map'    => 'nullable|string',
        ]);
        $venue->update($validated);

        if($request->ajax()){
            return response()->json($venue);
        }
        return redirect()->route('admin.venues.index')->with('success', 'venue updated successfully');
    }

    public function destroy(VenueName $venue)
    {
        $venue->delete();
        return redirect()->route('admin.venues.index')->with('success', 'venue Deleted success');
    }
}
