<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\VenueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CeramonyController extends Controller
{
    public function index()
    {
        $ceramonies = Ceramonies::with(['category', 'venue'])->where('host_id', Auth::id())->get();
        return view('host.ceramony.index', compact('ceramonies'));
    }

    public function create()
    {
        $categories = CategoryVenue::all();
        $venues = VenueName::where('host_id', Auth::id())->get();
        return view('host.ceramony.create', compact('categories', 'venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:category_venues,id',
            'venue_id'       => 'nullable|exists:venue_names,id',
            'ceramony_name'  => 'required|string|max:255',
            'ceramony_date'  => 'nullable|date',
            'ceramony_time'  => 'nullable',
            'ceramony_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);
        if ($request->venue_id) {
            $checkVenue = VenueName::where('id', $request->venue_id)->where('host_id', Auth::id())->first();
            if (!$checkVenue) {
                return redirect()->route('host.ceramony.create')->with('error', 'venue nto found');
            }
        }
        $validated['host_id'] = Auth::id();
        if ($request->ceramony_image) {
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }
        Ceramonies::create($validated);
        return redirect()->route('host.ceramony.index')->with('success', 'ceramony created successfully');
    }

    public function edit(Ceramonies $ceramony)
    {
        if ($ceramony->host_id != Auth::id()) {
            abort(403);
        }
        if ($ceramony->is_main) {
            return redirect()->back()->with('error', 'YOu cannot edit');
        }
        $categories = CategoryVenue::all();
        $venues = VenueName::where('host_id', Auth::id())->get();
        return view('host.ceramony.edit', compact('categories', 'ceramony', 'venues'));
    }

    public function update(Request $request, Ceramonies $ceramony)
    {
        if ($ceramony->host_id != Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'category_id'    => 'required|exists:category_venues,id',
            'venue_id'       => 'nullable|exists:venue_names,id',
            'ceramony_name'  => 'required|string|max:255',
            'ceramony_date'  => 'nullable|date',
            'ceramony_time'  => 'nullable',
            'ceramony_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        if ($request->hasFile('ceramony_image')) {
            if ($ceramony->ceramony_image) {
                Storage::disk('public')->delete($ceramony->ceramony_image);
            }
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }
        $ceramony->update($validated);
        return redirect()->route('host.ceramony.index', $ceramony->id)->with('success', 'Ceramony Updated');
    }
    public function destroy(Ceramonies $ceramony)
    {
        if ($ceramony->host_id != Auth::id()) abort(403);

        // Prevent deletion of the Main Wedding
        if ($ceramony->is_main) {
            return redirect()->back()->with('error', 'You cannot delete the Main Wedding ceremony. Delete the Invitation instead.');
        }

        if ($ceramony->ceramony_image) {
            Storage::disk('public')->delete($ceramony->ceramony_image);
        }

        $ceramony->delete();
        return redirect()->route('host.ceramony.index')->with('success', 'Ceremony deleted successfully');
    }
}
