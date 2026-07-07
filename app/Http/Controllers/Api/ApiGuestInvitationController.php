<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Albums;
use App\Models\Ceramonies;
use App\Models\GuestList;
use App\Models\HostFamilyDetails;
use App\Models\Pictures;
use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiGuestInvitationController extends Controller
{
    /**
     * Guest Login API
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $input = $request->phone;

        $guest = GuestList::where(function ($query) use ($input) {
            $query->where('guest_number', $input)
                ->orWhere('guest_email', $input);
        })->first();

        if (!$guest || !Hash::check($request->password, $guest->password)) {
            return response()->json(['success' => false, 'message' => 'Check your credentials.'], 401);
        }

        // Return the guest phone as a token for simplicity in API stateless authentication
        return response()->json([
            'success' => true, 
            'message' => 'Login successful', 
            'token' => $guest->guest_number,
            'guest' => $guest
        ], 200);
    }

    /**
     * Guest Logout API
     */
    public function logout(Request $request)
    {
        // In a stateless API (using phone number as token), logout is handled client-side
        // by deleting the token/header. We provide this endpoint to confirm logout actions.
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out. Please remove the guest phone token from your client storage.'
        ], 200);
    }

    /**
     * Select Wedding API
     */
    public function selectWedding(Request $request)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();

        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Guest phone required.'], 401);
        }

        $tab = strtolower($request->query('tab', 'all'));

        // Fetch ALL invitations for this guest to compute tab counts accurately
        $allInvitations = GuestList::where('guest_number', $phone)->with('host')->get();

        $counts = [
            'all' => $allInvitations->count(),
            'new' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        $filteredInvitations = collect();

        // Compute counts and filter based on the requested tab
        foreach ($allInvitations as $invite) {
            $status = strtolower($invite->status ?? 'pending');
            
            // Treat empty/null as pending/new
            if ($status === '') $status = 'pending';

            if ($status === 'accepted') {
                $counts['accepted']++;
                if ($tab === 'accepted' || $tab === 'all') $filteredInvitations->push($invite);
            } elseif ($status === 'rejected' || $status === 'declined') {
                $counts['rejected']++;
                if ($tab === 'rejected' || $tab === 'all') $filteredInvitations->push($invite);
            } else {
                $counts['new']++;
                if ($tab === 'new' || $tab === 'pending' || $tab === 'all') $filteredInvitations->push($invite);
            }
        }

        $calendarEvents = [];
        foreach ($filteredInvitations as $invite) {
            $assignedNames = explode(', ', $invite->assigned_ceremonies);
            $ceremonies = \App\Models\Ceramonies::where('host_id', $invite->host_id)
                ->whereIn('ceramony_name', $assignedNames)
                ->get();
            
            foreach($ceremonies as $ceremony) {
                $color = '#d63384'; // Default Pink
                if ($invite->status === 'accepted') $color = '#4CAF50';
                if ($invite->status === 'declined' || $invite->status === 'rejected') $color = '#F44336';

                $calendarEvents[] = [
                    'title' => $ceremony->ceramony_name . ' (' . ($invite->host->bride_name ?? '') . ' & ' . ($invite->host->groom_name ?? '') . ')',
                    'start' => $ceremony->ceramony_date . 'T' . $ceremony->ceramony_time,
                    'invite_id' => $invite->id,
                    'color' => $color
                ];
            }
        }

        return response()->json([
            'success' => true,
            'tab_requested' => $tab,
            'counts' => $counts,
            'invitations' => $filteredInvitations,
            'calendarEvents' => $calendarEvents
        ], 200);
    }

    /**
     * Update Overall Status API
     */
    public function updateStatus(Request $request, $id)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->first();
        
        if (!$invite) {
            return response()->json(['success' => false, 'message' => 'Invitation not found or unauthorized.'], 404);
        }

        $request->validate([
            'status' => 'required|in:accepted,declined,rejected,pending'
        ]);
        
        $assignedNames = explode(', ', $invite->assigned_ceremonies);
        $statuses = [];
        foreach ($assignedNames as $name) {
            $statuses[$name] = $request->status; 
        }

        $invite->update([
            'status' => $request->status,
            'ceremony_status' => $statuses
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully.',
            'invite' => $invite
        ], 200);
    }

    /**
     * Update Specific Ceremony Status API
     */
    public function updateCeremonyStatus(Request $request, $id)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->first();
        
        if (!$invite) {
            return response()->json(['success' => false, 'message' => 'Invitation not found or unauthorized.'], 404);
        }

        $request->validate([
            'ceremony_name' => 'required|string',
            'status' => 'required|in:accepted,declined,rejected,pending'
        ]);
        
        $statuses = $invite->ceremony_status ?? [];
        $statuses[$request->ceremony_name] = $request->status;
        
        $invite->ceremony_status = $statuses;
        
        // Determine global status
        $allAccepted = true;
        $allDeclined = true;
        $assignedNames = explode(', ', $invite->assigned_ceremonies);
        foreach ($assignedNames as $name) {
            $status = $statuses[$name] ?? 'pending';
            if ($status !== 'accepted') $allAccepted = false;
            if ($status !== 'rejected' && $status !== 'declined') $allDeclined = false;
        }

        if ($allAccepted) {
            $invite->status = 'accepted';
        } elseif ($allDeclined) {
            $invite->status = 'declined';
        } else {
            $invite->status = 'partially_accepted'; 
        }

        $invite->save();

        return response()->json([
            'success' => true, 
            'message' => 'RSVP updated for ' . $request->ceremony_name,
            'invite' => $invite
        ], 200);
    }

    /**
     * Save The Date details API
     */
    public function saveTheDate(Request $request, $id)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->first();
        
        if (!$invite) {
            return response()->json(['success' => false, 'message' => 'Invitation not found or unauthorized.'], 404);
        }

        $invitation = \App\Models\Invitation::where('host_id', $invite->host_id)->first();
        $saveDate = \App\Models\SaveDate::where('host_id', $invite->host_id)->first();

        $imageUrl = null;
        if ($saveDate && $saveDate->image) {
            $imageUrl = str_starts_with($saveDate->image, 'http') 
                ? $saveDate->image 
                : (str_starts_with($saveDate->image, 'storage/') ? asset($saveDate->image) : asset('storage/' . $saveDate->image));
        }

        return response()->json([
            'success' => true,
            'save_the_date' => [
                'bride_name' => $invitation->bride_name ?? '',
                'groom_name' => $invitation->groom_name ?? '',
                'wedding_date' => $invitation->wedding_date ?? '',
                'message' => $saveDate->message ?? '',
                'image' => $imageUrl
            ]
        ], 200);
    }

    /**
     * Show Ceremonies / Dashboard API
     */
    public function showCeremonies(Request $request, $id)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->with('host')->first();
        
        if (!$invite) {
            return response()->json(['success' => false, 'message' => 'Invitation not found or unauthorized.'], 404);
        }

        $assignedNames = explode(', ', $invite->assigned_ceremonies);

        $detailedCeremonies = Ceramonies::with('venue', 'background')
            ->where('host_id', $invite->host_id)
            ->whereIn('ceramony_name', $assignedNames)
            ->orderBy('ceramony_date', 'asc')
            ->orderBy('ceramony_time', 'asc')
            ->get();

        $detailedCeremonies->map(function ($ceremony) use ($invite) {
            if ($ceremony->ceramony_image && !str_starts_with($ceremony->ceramony_image, 'http')) {
                $ceremony->ceramony_image = str_starts_with($ceremony->ceramony_image, 'storage/') 
                    ? asset($ceremony->ceramony_image) 
                    : asset('storage/' . $ceremony->ceramony_image);
            }
            if ($ceremony->background && $ceremony->background->image_path && !str_starts_with($ceremony->background->image_path, 'http')) {
                $ceremony->background->image_path = str_starts_with($ceremony->background->image_path, 'storage/') 
                    ? asset($ceremony->background->image_path) 
                    : asset('storage/' . $ceremony->background->image_path);
            }

            // Attach the guest's RSVP status for this specific ceremony
            $ceremonyStatuses = $invite->ceremony_status ?? [];
            $ceremony->status = $ceremonyStatuses[$ceremony->ceramony_name] ?? 'pending';

            return $ceremony;
        });

        $hfamily = HostFamilyDetails::where('host_id', $invite->host_id)->with('background')->first();

        if ($hfamily && $hfamily->background && $hfamily->background->image_path && !str_starts_with($hfamily->background->image_path, 'http')) {
            $hfamily->background->image_path = str_starts_with($hfamily->background->image_path, 'storage/') 
                ? asset($hfamily->background->image_path) 
                : asset('storage/' . $hfamily->background->image_path);
        }

        return response()->json([
            'success' => true,
            'detailedCeremonies' => $detailedCeremonies,
            'hfamily' => $hfamily
        ], 200);
    }

    /**
     * Show Gallery API
     */
    public function showGallery(Request $request, $id)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->with('host')->first();
        
        if (!$invite) {
            return response()->json(['success' => false, 'message' => 'Invitation not found or unauthorized.'], 404);
        }

        $host_id = $invite->host_id;
        $pictures = Pictures::where('host_id', $host_id)->get();
        $pictures->map(function ($pic) {
            if ($pic->picture && !str_starts_with($pic->picture, 'http')) {
                // Determine if it needs 'storage/' prefix based on your storage setup. 
                // Usually it's asset('storage/' . $pic->picture) or asset($pic->picture).
                $pic->picture = str_starts_with($pic->picture, 'storage/') ? asset($pic->picture) : asset('storage/' . $pic->picture);
            }
            return $pic;
        });

        $albums = Albums::where('host_id', $host_id)->get();
        $albums->map(function ($album) {
            if (is_string($album->album_images)) {
                $images = json_decode($album->album_images, true) ?? [];
                $album->album_images = array_map(function ($img) {
                    return str_starts_with($img, 'http') ? $img : (str_starts_with($img, 'storage/') ? asset($img) : asset('storage/' . $img));
                }, $images);
            } elseif (is_array($album->album_images)) {
                $album->album_images = array_map(function ($img) {
                    return str_starts_with($img, 'http') ? $img : (str_starts_with($img, 'storage/') ? asset($img) : asset('storage/' . $img));
                }, $album->album_images);
            }
            return $album;
        });

        $videos = Videos::where('host_id', $host_id)->get();
        $videos->map(function ($video) {
            if ($video->videos && !str_starts_with($video->videos, 'http')) {
                $video->videos = str_starts_with($video->videos, 'storage/') ? asset($video->videos) : asset('storage/' . $video->videos);
            }
            return $video;
        });
        
        return response()->json([
            'success' => true,
            'pictures' => $pictures,
            'albums' => $albums,
            'videos' => $videos
        ], 200);
    }

    /**
     * Show Host Family Details API
     */
    public function showHostFamilyDetails(Request $request, $id)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $invite = GuestList::where('id', $id)->where('guest_number', $phone)->with('host')->first();
        
        if (!$invite) {
            return response()->json(['success' => false, 'message' => 'Invitation not found or unauthorized.'], 404);
        }

        $host_id = $invite->host_id;
        $hfamily = HostFamilyDetails::where('host_id', $host_id)->with('background')->first();
        
        if ($hfamily && $hfamily->background && $hfamily->background->image_path && !str_starts_with($hfamily->background->image_path, 'http')) {
            $hfamily->background->image_path = str_starts_with($hfamily->background->image_path, 'storage/') 
                ? asset($hfamily->background->image_path) 
                : asset('storage/' . $hfamily->background->image_path);
        }

        return response()->json([
            'success' => true,
            'invite' => $invite,
            'hfamily' => $hfamily
        ], 200);
    }

    /**
     * Get Guest Profile API
     */
    public function getProfile(Request $request)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $guest = GuestList::where('guest_number', $phone)
            ->latest()
            ->with('familyMembers') // Removed host as requested
            ->first();
        
        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Profile not found or unauthorized.'], 404);
        }

        return response()->json([
            'success' => true,
            'guest' => $guest
        ], 200);
    }

    /**
     * Update Guest Profile API
     */
    public function updateProfile(Request $request)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        $guest = GuestList::where('guest_number', $phone)->latest()->first();
        
        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Profile not found or unauthorized.'], 404);
        }

        $validated = $request->validate([
            'guest_email' => 'nullable|email',
            'relation' => 'nullable|in:bride,groom',
            'gender' => 'nullable|in:male,female,other',
            'alternate_number' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'age' => 'nullable|integer',
            'complex' => 'nullable|string',
            'floor' => 'nullable|string',
            'door_no' => 'nullable|string',
            'street_name' => 'nullable|string',
            'pincode' => 'nullable|string',
            'area_name' => 'nullable|string',
            'district' => 'nullable|string',
            'state' => 'nullable|string',
            'circle' => 'nullable|string',
            'country' => 'nullable|string',
            'location_map' => 'nullable|string',
            'family_members' => 'nullable|array',
            'family_members.*.name' => 'required|string',
            'family_members.*.mobile' => 'nullable|string',
            'family_members.*.whatsapp_number' => 'nullable|string',
            'family_members.*.email' => 'nullable|email',
            'family_members.*.relation' => 'nullable|string',
            'family_members.*.gender' => 'nullable|in:male,female,other',
            'family_members.*.age' => 'nullable|integer',
        ]);
        
        // Remove family_members from validated so we can update the main guest safely
        $guestData = \Illuminate\Support\Arr::except($validated, ['family_members']);
        $guest->update($guestData);
        
        if ($request->has('family_members')) {
            // Remove existing family members
            \App\Models\GuestFamilyMember::where('guest_list_id', $guest->id)->delete();
            
            // Add new ones
            foreach ($request->family_members as $member) {
                \App\Models\GuestFamilyMember::create(array_merge($member, ['guest_list_id' => $guest->id]));
            }
        }
        
        // Refresh guest with relationships to return updated data
        $guest = GuestList::where('id', $guest->id)->with('familyMembers')->first();

        return response()->json([
            'success' => true, 
            'message' => 'Profile updated successfully.',
            'guest' => $guest
        ], 200);
    }

    /**
     * Get Previous Details API
     */
    public function getPreviousDetails(Request $request)
    {
        $phone = $request->header('X-Guest-Phone') ?? $request->bearerToken();
        
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Guest phone required.'], 401);
        }

        $previous = GuestList::where('guest_number', $phone)
            ->whereNotNull('pincode')
            ->latest('updated_at')
            ->first();

        if ($previous) {
            return response()->json([
                'success' => true,
                'data' => $previous
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No previous details found.'
        ], 404);
    }
}
