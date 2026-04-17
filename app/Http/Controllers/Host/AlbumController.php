<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index(){
        return view('host.picture.index');
    }
    public function store(Request $request){
        $validated = $request->validate([
            'album_name' => 'required',
            'album_images' => 'required',
            'album_images.*' => 'required|mimes:jpeg,jpg,png,svg,gif,webp,avif|max:3048' 
        ]);
        $imagePaths = [];
        if($request->hasFile('album_images')){
            foreach($request->file('album_images') as $file){
                $imagePaths[] = $file->store('albums', 'public');
            }
        }
        Albums::create([
            'host_id' => Auth::id(),
            'album_name' => $request->album_name,
            'album_images' => $imagePaths,
        ]);
        return redirect()->route('host.picture.index')->with('success', 'Album Created Success');
    }

    public function update(Request $request, $id){
        $album = Albums::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $request->validate([
            'album_name' => 'required',
            'album_images.*' => 'mimes:jpeg,jpg,png,svg,gif,webp,avif|max:3048',
        ]);
        $currentImages = $album->album_images ?? [];
        if($request->hasFile('album_images')){
            foreach($request->file('album_images') as $file){
                $currentImages[] = $file->store('albums', 'public');
            }
        }
        $album->update([
            'album_name' => $request->album_name,
            'album_images' => $currentImages,
        ]);
        return redirect()->route('host.picture.index')->with('success', 'Albums Updated');
    }

    public function destroy($id){
        $album = Albums::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        if($album->album_images){
            foreach($album->album_images as $path){
                Storage::disk('public')->delete($path);
            }
        }
        $album->delete();
        return redirect()->route('host.picture.index')->with('success', 'Albume Deleted');
    }
    public function deleteImage(Request $request, $id){
        $album = Albums::where('id', $id)->where('host_id',Auth::id())->firstOrFail();
        $images = $album->album_images;
        $index = $request->image_index;
        if(asset($images[$index])){
            Storage::disk('public')->delete($images[$index]);
            unset($images[$index]);
            $album->album_images = array_values($images);
            $album->save();
            return redirect()->back()->with('Success', 'Image Deleted');
        }
        return redirect()->back()->with('error', 'image not found');
    }
}
