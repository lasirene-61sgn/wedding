<?php

namespace App\Services;

use App\Models\Host;
use App\Models\Pictures;
use App\Models\Videos;
use App\Models\Albums;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function hasSufficientStorage($hostId, $newFileSizeInBytes = 0)
    {
        $host = Host::with('package')->find($hostId);
        if (!$host || !$host->package) {
            return true; // No package limit if no package
        }

        $limitMb = $host->package->storage_limit_mb;
        if (!$limitMb) {
            return true; // No limit set on package
        }

        $limitBytes = $limitMb * 1024 * 1024;
        
        $totalBytesUsed = 0;

        // Calculate Pictures size
        $pictures = Pictures::where('host_id', $hostId)->get();
        foreach ($pictures as $pic) {
            if ($pic->picture && Storage::disk('public')->exists($pic->picture)) {
                $totalBytesUsed += Storage::disk('public')->size($pic->picture);
            }
        }

        // Calculate Videos size
        $videos = Videos::where('host_id', $hostId)->get();
        foreach ($videos as $vid) {
            if ($vid->video && Storage::disk('public')->exists($vid->video)) {
                $totalBytesUsed += Storage::disk('public')->size($vid->video);
            }
        }

        // Calculate Albums size (album_images is an array of paths)
        $albums = Albums::where('host_id', $hostId)->get();
        foreach ($albums as $album) {
            if (is_array($album->album_images)) {
                foreach ($album->album_images as $imagePath) {
                    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                        $totalBytesUsed += Storage::disk('public')->size($imagePath);
                    }
                }
            }
        }

        return ($totalBytesUsed + $newFileSizeInBytes) <= $limitBytes;
    }
}
