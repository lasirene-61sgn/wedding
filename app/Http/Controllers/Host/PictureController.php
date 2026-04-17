<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Pictures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function index()
{
    // FIX: Use the 'host' guard to get the correct logged-in ID
    $host_id = Auth::guard('host')->id();

    // 1. Get Pictures
    $pictures = Pictures::where('host_id', $host_id)->get();

    // 2. Get Albums 
    $albums = \App\Models\Albums::where('host_id', $host_id)->get();

    // 3. Get Videos 
    $videos = \App\Models\Videos::where('host_id', $host_id)->get();

    // 4. Send ALL THREE to the view
    // This ensures $pictures, $albums, and $videos are NEVER undefined
    return view('host.picture.index', compact('pictures', 'albums', 'videos'));
}

    public function create(){
        return view('host.picture.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'picture' => 'required|mimes:jpeg,jpg,png,svg,gif,webp,avif|max:3048',
        ]);

        if($request->hasFile('picture')){
            $validated['picture'] = $request->file('picture')->store('pictures', 'public');
        }
        $validated['host_id'] = Auth::guard('host')->id();
        Pictures::create($validated);
        return redirect()->route('host.picture.index')->with('success', 'Picture added Successfully');
    }

    public function destroy($id){
        $picture = Pictures::where('id', $id)->where('host_id', Auth::guard('host')->id())->firstOrFail();
        if($picture->picture && Storage::disk('public')->exists($picture->picture)){
            Storage::disk('public')->delete($picture->picture);
        }
        $picture->delete();
        return redirect()->route('host.picture.index')->with('Success', 'Picture Deleted');
    }
}
