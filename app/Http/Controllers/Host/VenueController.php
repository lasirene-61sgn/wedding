<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\VenueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    public function index(){
        $venues = VenueName::where('host_id',Auth::id())->get();
        return view('host.venue.index', compact('venues'));
    }

    public function create(){
        return view('host.venue.create');
    }

    public function store(Request $request){
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
        if(Auth::guard('admin')->check() || !isset($validated['host_id'])){
            $validated['host_id'] = $request->host_id ?? Auth::id();
        }
        $venue = VenueName::create($validated);
        if($request->ajax()){
            return response()->json($venue);
        }
        return redirect()->route('host.venue.index')->with('success', 'Venue Added Successfully');
    }

    public function edit(VenueName $venue){
        $venue = VenueName::where('id', $venue->id)
        ->where('host_id', Auth::id())->firstOrFail();
        return view('host.venue.edit', compact('venue'));
    }

    public function update(Request $request, VenueName $venue){
        if($venue->host_id != Auth::id()){
            abort(403);
        }
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
        $venue->update($validated);
        if($request->ajax()){
            return response()->json($venue);
        }
        return redirect()->route('host.venue.index')->with('Success', 'Venue Updated Successfully');
    }
}
