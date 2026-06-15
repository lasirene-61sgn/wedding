<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\CeramonyBackground;
use App\Models\Host;
use App\Models\VenueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CeramonyController extends Controller
{
    public function index(){
        $ceramonies = Ceramonies::with(['host', 'category', 'venue'])->get();
        return view('admin.ceramony.index', compact('ceramonies'));
    }


    public function create(){
        $hosts = Host::all();
        $categories = CategoryVenue::all();
        $venues = VenueName::all();
        $backgrounds = CeramonyBackground::all();
        return view('admin.ceramony.create', compact('hosts', 'categories', 'venues', 'backgrounds'));
    }

    public function store(Request $request){
       $validated = $request->validate([
            'host_id' => 'required|exists:host,id',
            'category_id' => 'required|exists:category_venues,id',
            'venue_id' => 'nullable|exists:veneu_names,id',
            'ceramony_name' => 'nullable',
            'ceramony_date' => 'nullable|date',
            'ceramony_image' => 'nullable|mimes:jpg,jpeg,gif,svg,webp,png|max:3048',
            'selected_background_id' => 'nullable|mimes:jpg,jpeg,png,svg,webp,gif|max:5048',
        ]);

        if($request->hasFile('ceramony_image')){
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }

        Ceramonies::create($validated);
        return redirect()->route('admin.ceramony.index')->with('Success', 'Ceramony Created Successfuly');
    }

    public function edit(Ceramonies $ceramony){
        $hosts = Host::all();
        $categories = CategoryVenue::all();
        $venues = VenueName::where('host_id', $ceramony->host_id)->get();
        $backgrounds = CeramonyBackground::all();
        return view('admin.ceramony.edit', compact('categories','hosts', 'venues', 'ceramony', 'backgrounds' ));
    }

    public function update(Request $request, Ceramonies $ceramony){
        $validated = $request->validate([
            'host_id' => 'required|exists:host,id',
            'category_id' => 'required|exists:category_venues,id',
            'venue_id' => 'nullable|exists:veneu_names,id',
            'ceramony_name' => 'nullable',
            'ceramony_date' => 'nullable|date',
            'ceramony_image' => 'nullable|mimes:jpg,jpeg,gif,svg,webp,png|max:3048',
            'selected_background_id' => 'nullable|mimes:jpg,jpeg,png,svg,webp,gif|max:5048',
        ]);

        if($request->hasFile('ceramony_image')){
            if($ceramony->ceramony_image){
                Storage::disk('public')->delete($ceramony->ceramony_image);
            }
            $validated['ceramony_image'] = $request->file('ceramony_image')->store('ceramonies', 'public');
        }
        $ceramony->update($validated);
        return redirect()->route('admin.ceramony.index')->with('success', 'ceramony updated successfully');
    }

    public function manageBackgrounds()
    {
        $backgrounds = CeramonyBackground::all();
        return view('admin.ceramony.backgrounds', compact('backgrounds'));
    }

    public function storeBackgrounds(Request $request)
    {
        $request->validate(['background_image'=> 'nullable|mimes:jpg,svg,png,jpeg,gif,webp|max:5048']);
        if($request->hasFile('background_image')){
            $path = $request->file('background_image')->store('global_backgrounds', 'public');
            CeramonyBackground::create(['image_path' => $path]);
        }
        return redirect()->back()->with('success', 'Global Background Images Added');
    }

    public function destroyBackground($id)
    {
        $background = CeramonyBackground::findOrFail($id);
        Storage::disk('public')->delete($background->image_path);
        $background->delete();
        return redirect()->back()->with('success', 'Global Background Image Deleted');
    }

    public function destroy(Ceramonies $ceramony){
        if($ceramony->ceramony_image){
            Storage::disk('public')->delete($ceramony->ceramony_image);
        }
        $ceramony->delete();
        return redirect()->route('admin.ceramony.index')->with('success', 'ceramony deleted successfully');
    }
}
