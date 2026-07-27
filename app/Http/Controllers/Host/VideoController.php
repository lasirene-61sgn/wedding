<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(){
        return view('host.picture.index');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'videos' => 'required|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        $host_id = Auth::guard('host')->id();

        if($request->hasFile('videos')){
            $newFileSize = $request->file('videos')->getSize();
            if (!\App\Services\StorageService::hasSufficientStorage($host_id, $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more videos.');
            }
            $validated['videos'] = $request->file('videos')->store('videos', 'public');
        }
        $validated['host_id'] = $host_id;
        Videos::create($validated);
        return redirect()->route('host.picture.index')->with('success', 'videos added Successfully');
    }

    public function destroy($id){
        $video = Videos::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        if($video->video && Storage::disk('public')->exists($video->video)){
            Storage::disk('public')->delete($video->video);
        }
        $video->delete();
        return redirect()->route('host.picture.index')->with('Success', 'videos Deleted');
    }
}
