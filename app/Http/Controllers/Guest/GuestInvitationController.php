<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Albums;
use App\Models\Ceramonies;
use App\Models\GuestList;
use App\Models\HostFamilyDetails;
use App\Models\Pictures;
use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuestInvitationController extends Controller
{
    public function showLogin()
    {
        return view('guest.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required', // This field handles both input types now
            'password' => 'required',
        ]);

        $input = $request->phone;

        // 1. Dynamic Lookup: Check both the phone number AND email columns
        $guest = GuestList::where(function ($query) use ($input) {
            $query->where('guest_number', $input)
                ->orWhere('guest_email', $input);
        })->first();

        // 2. Validate Credentials
        if (!$guest || !Hash::check($request->password, $guest->password)) {
            return back()->with('error', 'Check your credentials.');
        }

        // 3. Store the precise database key in the session instead of raw input
        session(['guest_phone' => $guest->guest_number]);

        return redirect()->route('guest.select');
    }

    public function selectWedding()
    {
        $phone = session('guest_phone');

        if (!$phone) {
            return redirect()->route('guest.login');
        }

        // Check if any invitations are valid
        $invitations = GuestList::where('guest_number', $phone)
            ->with('host')
            ->get();
            
        // Filter out expired hosts
        $invitations = $invitations->filter(function($invite) {
            if ($invite->host && $invite->host->package_expires_at) {
                return \Carbon\Carbon::parse($invite->host->package_expires_at)->endOfDay()->isFuture();
            }
            return true;
        });

        $calendarEvents = [];
        foreach ($invitations as $invite) {
            $assignedNames = explode(', ', $invite->assigned_ceremonies);
            $ceremonies = \App\Models\Ceramonies::where('host_id', $invite->host_id)
                ->whereIn('ceramony_name', $assignedNames)
                ->get();
            
            foreach($ceremonies as $ceremony) {
                // Determine color based on invitation status
                $color = '#d63384'; // Default Pink (New/Pending)
                if ($invite->status === 'accepted') $color = '#4CAF50'; // Green
                if ($invite->status === 'declined' || $invite->status === 'rejected') $color = '#F44336'; // Red

                $calendarEvents[] = [
                    'title' => $ceremony->ceramony_name . ' (' . ($invite->host->bride_name ?? '') . ' & ' . ($invite->host->groom_name ?? '') . ')',
                    'start' => $ceremony->ceramony_date . 'T' . $ceremony->ceramony_time,
                    'url' => route('guest.wedding.details', $invite->uuid),
                    'color' => $color
                ];
            }
        }

        return view('guest.selection', compact('invitations', 'calendarEvents'));
    }

    public function updateStatus(Request $request, $uuid)
    {
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->where('guest_number', session('guest_phone'))->firstOrFail();
        
        $assignedNames = explode(', ', $invite->assigned_ceremonies);
        $statuses = [];
        foreach ($assignedNames as $name) {
            $statuses[$name] = $request->status; // 'accepted' or 'declined'
        }

        $invite->update([
            'status' => $request->status,
            'ceremony_status' => $statuses
        ]);

        if ($request->status == 'accepted') {
            return redirect()->route('guest.wedding.details', $uuid);
        }
        return redirect()->route('guest.select')->with('info', 'invitation declined');
    }

    public function updateCeremonyStatus(Request $request, $uuid)
    {
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->where('guest_number', session('guest_phone'))->firstOrFail();
        
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
            $invite->status = 'partially_accepted'; // At least one ceremony accepted/pending
        }

        $invite->save();

        return redirect()->back()->with('success', 'RSVP updated for ' . $request->ceremony_name);
    }

    public function saveTheDate($uuid)
    {
        // Allow access directly via link without needing to be logged in first
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->with('host')->firstOrFail();

        if ($invite->host && $invite->host->package_expires_at && \Carbon\Carbon::parse($invite->host->package_expires_at)->endOfDay()->isPast()) {
            return abort(403, 'This wedding invitation is no longer active.');
        }

        // Auto-login the guest for this session so they can navigate the dashboard later
        if (!session()->has('guest_phone')) {
            session(['guest_phone' => $invite->guest_number]);
        }
        
        $saveDateData = \App\Models\SaveDate::where('host_id', $invite->host_id)->latest()->first();
        $invitation = \App\Models\Invitation::where('host_id', $invite->host_id)->latest()->first();
        $ceremony = \App\Models\Ceramonies::where('host_id', $invite->host_id)->latest()->first();
        
        // Prioritize the actual Invitation's wedding date over an arbitrary ceremony date
        $weddingDate = $invitation->wedding_date ?? $ceremony->ceramony_date ?? null;

        return view('guest.save_the_date', compact('invite', 'saveDateData', 'weddingDate', 'invitation'));
    }

    public function showCeremonies($uuid)
    {
        $phone = session('guest_phone');
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->where('guest_number', $phone)->with('host')->firstOrFail();

        if ($invite->host && $invite->host->package_expires_at && \Carbon\Carbon::parse($invite->host->package_expires_at)->endOfDay()->isPast()) {
            return abort(403, 'This wedding invitation is no longer active.');
        }

        $guest = $invite;
        $hostId = $invite->host_id;

        // Determine the guest's addressed name
        $guestAddressing = htmlspecialchars($guest->guest_name);
        // Assuming GuestCategory or relation could denote Couple/Family, but we'll use a basic logic:
        // If guest has 'gender' or 'relation', we might add " & Family", but for now just use guest_name
        // A generic suffix could be added based on `group_type` if we fetch the category, but the DB schema for guest_lists doesn't have group_type directly.
        // Let's check GuestCategory if applicable.
        $guestCategory = \App\Models\GuestCategory::find($guest->caetgory_id ?? $guest->category_id);
        if ($guestCategory) {
            if ($guestCategory->group_type === 'couple') {
                $guestAddressing .= ' & Partner';
            } elseif ($guestCategory->group_type === 'family') {
                $guestAddressing .= ' & Family';
            }
        }

        // Fetch Invitation
        $invitation = \App\Models\Invitation::where('host_id', $hostId)->first();
        if (!$invitation || !$invitation->selected_html_template) {
            return response("<div style='padding:40px; text-align:center; color:#888; font-family: sans-serif;'>The host has not setup their invitation template yet.</div>", 200);
        }

        $template = $invitation->selected_html_template;
        $cleanTemplate = ltrim($template, '/\\');

        $possiblePaths = [
            public_path($cleanTemplate),
            public_path('uploads/' . $cleanTemplate),
            public_path('uploads/host_templates/' . basename($cleanTemplate)),
            storage_path('app/public/' . $cleanTemplate),
            base_path($cleanTemplate)
        ];

        // Prioritize the custom template
        $customFilename = "host_" . $hostId . "_inv_" . $invitation->id . ".html";
        $customFilepath = public_path('uploads/host_templates/' . $customFilename);
        if (file_exists($customFilepath)) {
            array_unshift($possiblePaths, $customFilepath);
        }

        $path = null;
        foreach ($possiblePaths as $p) {
            if (file_exists($p) && is_file($p)) {
                $path = $p;
                break;
            }
        }

        if (!$path) {
            return response("<div style='padding:30px; text-align:center; color:#dc3545; font-family: sans-serif;'><strong>Template Not Found</strong></div>", 200);
        }

        $html = file_get_contents($path);

        $bride = $invitation->bride_name ?? 'Bride Name';
        $groom = $invitation->groom_name ?? 'Groom Name';
        $brideFather = $invitation->bride_father_name ?? '';
        $brideMother = $invitation->bride_mother_name ?? '';
        $groomFather = $invitation->groom_father_name ?? '';
        $groomMother = $invitation->groom_mother_name ?? '';

        $rawDate = $invitation->wedding_date ?? null;
        $date = !empty($rawDate) ? \Carbon\Carbon::parse($rawDate)->format('d F Y') : 'Date to be announced';
        $time = $invitation->wedding_time ?? 'Time to be announced';

        $venueName = 'Venue to be announced';
        $venueAddress = '';
        $venueMap = '';
        if ($invitation->venue_id) {
            $venue = \App\Models\VenueName::find($invitation->venue_id);
            if ($venue) {
                $venueName = $venue->venue_name;
                $venueAddress = trim($venue->venue_address . ', ' . $venue->district . ', ' . $venue->state, ', ');
                $venueMap = $venue->location_map ?? '';
            }
        }

        // Dynamic Ceremonies HTML block - ONLY FOR ASSIGNED CEREMONIES
        $assignedNames = explode(', ', $invite->assigned_ceremonies);
        $detailedCeremonies = Ceramonies::where('host_id', $hostId)
            ->whereIn('ceramony_name', $assignedNames)
            ->orderBy('ceramony_date', 'asc')
            ->orderBy('ceramony_time', 'asc')
            ->get();

        $ceremoniesHtml = '';
        if ($detailedCeremonies->count() > 0) {
            $ceremoniesHtml .= '<div class="ceremonies-container" style="display: grid; gap: 12px; margin: 15px 0;">';
            foreach ($detailedCeremonies as $ceremony) {
                $cDate = $ceremony->ceramony_date ? \Carbon\Carbon::parse($ceremony->ceramony_date)->format('d M Y') : '';
                $cTime = $ceremony->ceramony_time ? \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') : '';
                $ceremoniesHtml .= '<div class="ceremony-item" style="padding: 12px; border-left: 4px solid #b02663; background: rgba(0,0,0,0.03); border-radius: 6px;">';
                $ceremoniesHtml .= '<strong style="display:block; font-size: 1.1em; color: inherit;">' . htmlspecialchars($ceremony->ceramony_name) . '</strong>';
                $ceremoniesHtml .= '<span style="font-size: 0.9em; opacity: 0.85;">' . $cDate . ($cTime ? ' at ' . $cTime : '') . '</span>';
                $ceremoniesHtml .= '</div>';
            }
            $ceremoniesHtml .= '</div>';
        }

        // Dynamic Gallery Photos HTML block
        $pictures = Pictures::where('host_id', $hostId)->latest()->take(8)->get();
        $galleryHtml = '';
        if ($pictures->count() > 0) {
            $galleryHtml .= '<div class="gallery-container" style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin: 15px 0;">';
            foreach ($pictures as $pic) {
                $galleryHtml .= '<img src="' . asset('storage/' . $pic->picture) . '" style="width: 110px; height: 110px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">';
            }
            $galleryHtml .= '</div>';
        }

        // Dynamic Albums HTML block
        $albums = Albums::where('host_id', $hostId)->latest()->take(5)->get();
        $albumsHtml = '';
        if ($albums->count() > 0) {
            $albumsHtml .= '<div class="albums-container" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; margin: 15px 0;">';
            foreach ($albums as $album) {
                $albumsHtml .= '<div style="text-align:center;">';
                if (!empty($album->album_images) && is_array($album->album_images)) {
                    $firstImg = $album->album_images[0];
                    $albumsHtml .= '<img src="' . asset('storage/' . $firstImg) . '" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">';
                }
                $albumsHtml .= '<strong style="display:block; margin-top:5px; font-size: 0.9em; color: inherit;">' . htmlspecialchars($album->album_name) . '</strong>';
                $albumsHtml .= '</div>';
            }
            $albumsHtml .= '</div>';
        }

        // Dynamic Videos HTML block
        $videos = Videos::where('host_id', $hostId)->latest()->take(5)->get();
        $videosHtml = '';
        if ($videos->count() > 0) {
            $videosHtml .= '<div class="videos-container" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; margin: 15px 0;">';
            foreach ($videos as $vid) {
                $videosHtml .= '<video controls src="' . asset('storage/' . $vid->videos) . '" style="width: 250px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"></video>';
            }
            $videosHtml .= '</div>';
        }

        // Dynamic Save the Date message & picture block
        $saveDate = \App\Models\SaveDate::where('host_id', $hostId)->latest()->first();
        $saveDateHtml = '';
        if ($saveDate) {
            $saveDateHtml = '<div class="save-the-date-container" style="text-align: center; margin: 20px 0;">';
            if (!empty($saveDate->image)) {
                $saveDateHtml .= '<img src="' . asset('storage/' . $saveDate->image) . '" style="max-width: 220px; border-radius: 8px; margin-bottom: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"><br>';
            }
            $saveDateHtml .= '<p style="font-style: italic; font-weight: 500; font-size: 1.05em;">' . htmlspecialchars($saveDate->message) . '</p>';
            $saveDateHtml .= '</div>';
        }

        // Selected Background Theme Image
        $bgId = $invitation->selected_background_id;
        $bgUrl = '';
        if ($bgId) {
            $bg = \App\Models\CeramonyBackground::find($bgId);
            if ($bg) {
                $bgUrl = asset('storage/' . $bg->image_path);
            }
        }

        $brideInitial = !empty(trim($bride)) ? mb_substr(trim($bride), 0, 1) : '';
        $groomInitial = !empty(trim($groom)) ? mb_substr(trim($groom), 0, 1) : '';

        // Replace placeholders
        $replacements = [
            '[GUEST_NAME]' => '<span class="guest-name-highlight" style="font-weight: bold; color: inherit;">' . $guestAddressing . '</span>',
            '[BRIDE_NAME]' => htmlspecialchars($bride),
            '[GROOM_NAME]' => htmlspecialchars($groom),
            '[BRIDE_INITIAL]' => htmlspecialchars(strtoupper($brideInitial)),
            '[GROOM_INITIAL]' => htmlspecialchars(strtoupper($groomInitial)),
            '[BRIDE_FATHER_NAME]' => htmlspecialchars($brideFather),
            '[BRIDE_MOTHER_NAME]' => htmlspecialchars($brideMother),
            '[GROOM_FATHER_NAME]' => htmlspecialchars($groomFather),
            '[GROOM_MOTHER_NAME]' => htmlspecialchars($groomMother),
            '[WEDDING_DATE]' => $date,
            '[WEDDING_TIME]' => htmlspecialchars($time),
            '[VENUE_NAME]' => htmlspecialchars($venueName),
            '[VENUE_ADDRESS]' => htmlspecialchars($venueAddress),
            '[VENUE_MAP_URL]' => $venueMap,
            '[CEREMONIES]' => $ceremoniesHtml,
            '[GALLERY]' => $galleryHtml,
            '[ALBUMS]' => $albumsHtml,
            '[VIDEOS]' => $videosHtml,
            '[SAVE_THE_DATE]' => $saveDateHtml,
            '[BACKGROUND_IMAGE]' => $bgUrl,
            '[TITLE_COLOR]' => $invitation->text_color ?? '#b02663',
            '[DETAILS_COLOR]' => $invitation->details_color ?? '#2b4c5e',
        ];

        $originalHtml = $html;
        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // Auto-inject missing dynamic content
        $autoInject = '';
        if ($ceremoniesHtml && !str_contains($originalHtml, '[CEREMONIES]')) {
            $autoInject .= '<div style="margin-top:40px; text-align:center;"><h3 style="color:' . $replacements['[TITLE_COLOR]'] . '">Your Ceremonies</h3>' . $ceremoniesHtml . '</div>';
        }
        if ($galleryHtml && !str_contains($originalHtml, '[GALLERY]')) {
            $autoInject .= '<div style="margin-top:40px; text-align:center;"><h3 style="color:' . $replacements['[TITLE_COLOR]'] . '">Our Memories</h3>' . $galleryHtml . '</div>';
        }
        if ($albumsHtml && !str_contains($originalHtml, '[ALBUMS]')) {
            $autoInject .= '<div style="margin-top:40px; text-align:center;"><h3 style="color:' . $replacements['[TITLE_COLOR]'] . '">Albums</h3>' . $albumsHtml . '</div>';
        }
        if ($videosHtml && !str_contains($originalHtml, '[VIDEOS]')) {
            $autoInject .= '<div style="margin-top:40px; text-align:center;"><h3 style="color:' . $replacements['[TITLE_COLOR]'] . '">Videos</h3>' . $videosHtml . '</div>';
        }

        if (!empty($autoInject)) {
            $injectionHtml = '<div style="max-width:800px; margin: 40px auto; padding: 20px; background: rgba(255,255,255,0.9); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); font-family: sans-serif;">' . $autoInject . '</div>';
            if (str_contains($html, '</body>')) {
                $html = str_replace('</body>', $injectionHtml . "\n</body>", $html);
            } else {
                $html .= $injectionHtml;
            }
        }

        // --- STICKY RSVP INJECTION ---
        $rsvpHtml = $this->generateStickyRsvpHtml($guest);
        
        // Inject FontAwesome for RSVP icons if not present
        if (!str_contains($html, 'font-awesome')) {
            $faLink = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">';
            if (str_contains($html, '</head>')) {
                $html = str_replace('</head>', $faLink . "\n</head>", $html);
            } else {
                $html = $faLink . "\n" . $html;
            }
        }

        // Add padding to body so sticky footer doesn\'t overlap content
        $paddingStyle = '<style>body { padding-bottom: 100px !important; }</style>';
        if (str_contains($html, '</head>')) {
            $html = str_replace('</head>', $paddingStyle . "\n</head>", $html);
        } else {
            $html = $paddingStyle . "\n" . $html;
        }

        if (str_contains($html, '</body>')) {
            $html = str_replace('</body>', $rsvpHtml . "\n</body>", $html);
        } else {
            $html .= $rsvpHtml;
        }

        return response($html, 200);
    }

    public function showGallery($uuid)
    {
        $phone = session('guest_phone');
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->where('guest_number', $phone)->with('host')->firstOrFail();

        if ($invite->host && $invite->host->package_expires_at && \Carbon\Carbon::parse($invite->host->package_expires_at)->endOfDay()->isPast()) {
            return abort(403, 'This wedding invitation is no longer active.');
        }

        $host_id = $invite->host_id;
        $pictures = Pictures::where('host_id', $host_id)->get();
        $albums = Albums::where('host_id', $host_id)->get();
        $videos = Videos::where('host_id', $host_id)->get();
        return view('guest.gallery', compact('invite', 'pictures', 'albums', 'videos'));
    }

    public function showHostFamilyDetails($uuid)
    {
        $phone = session('guest_phone');
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->where('guest_number', $phone)->with('host')->firstOrFail();

        if ($invite->host && $invite->host->package_expires_at && \Carbon\Carbon::parse($invite->host->package_expires_at)->endOfDay()->isPast()) {
            return abort(403, 'This wedding invitation is no longer active.');
        }

        $host_id = $invite->host_id;
        $hfamily = HostFamilyDetails::where('host_id', $host_id)->with('background')->first();
        return view('guest.hfamily', compact('invite', 'hfamily'));
    }

    public function editProfile($uuid)
    {
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->with('host')->firstOrFail();

        if ($invite->host && $invite->host->package_expires_at && \Carbon\Carbon::parse($invite->host->package_expires_at)->endOfDay()->isPast()) {
            return abort(403, 'This wedding invitation is no longer active.');
        }

        return view('guest.profile', compact('invite'));
    }

    public function updateProfile(Request $request, $uuid)
    {
        $column = \Illuminate\Support\Str::isUuid($uuid) ? 'uuid' : 'id';
        $invite = GuestList::where($column, $uuid)->firstOrFail();
        $validated = $request->validate([
            'guest_email' => 'nullable',
            'relation' => 'nullable|in:bride,groom',
            'gender' => 'nullable|in:male,female,other',
            'alternate_number' => 'nullable',
            'whatsapp_number' => 'nullable',
            'age' => 'nullable',
            'complex' => 'nullable',
            'floor' => 'nullable',
            'door_no' => 'nullable',
            'street_name' => 'nullable',
            'pincode' => 'nullable',
            'area_name' => 'nullable',
            'district' => 'nullable',
            'state' => 'nullable',
            'circle' => 'nullable',
            'country' => 'nullable',
            'location_map' => 'nullable',
        ]);
        $invite->update($validated);
        return redirect()->route('guest.wedding.details', $uuid)->with('success', 'Profile Updated');
    }
    public function getPreviousDetails()
    {
        $phone = session('guest_phone');

        // Find the latest record with this phone number that HAS an address (pincode)
        // We exclude the current one if needed, but 'latest' usually works best
        $previous = GuestList::where('guest_number', $phone)
            ->whereNotNull('pincode')
            ->latest('updated_at')
            ->first();

        if ($previous) {
            return response()->json([
                'success' => true,
                'data' => $previous
            ]);
        }

        return response()->json(['success' => false]);
    }
    private function generateStickyRsvpHtml($guest)
    {
        $updateRoute = route('guest.update_status', $guest->uuid);
        $csrfToken = csrf_token();

        // GuestList uses 'status' not 'rsvp_status' in this codebase flow
        $status = $guest->status ?? 'pending';
        $statusText = 'Pending';
        $statusColor = 'orange';
        $statusIcon = 'fa-hourglass-half';

        if ($status === 'accepted') {
            $statusText = 'Accepted';
            $statusColor = 'green';
            $statusIcon = 'fa-check';
        } elseif ($status === 'declined' || $status === 'rejected') {
            $statusText = 'Declined';
            $statusColor = 'red';
            $statusIcon = 'fa-times';
        }

        $html = '
        <div style="position: fixed; bottom: 0; left: 0; width: 100%; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1); padding: 15px 20px; display: flex; justify-content: center; align-items: center; gap: 15px; z-index: 1000; box-sizing: border-box; border-top-left-radius: 20px; border-top-right-radius: 20px; border-top: 1px solid #d4af37; font-family: sans-serif;">
            <div style="font-weight: 600; color: #333;">Your RSVP: <span style="color: ' . $statusColor . ';"><i class="fas ' . $statusIcon . '"></i> ' . $statusText . '</span></div>
        ';

        if ($status === 'pending') {
            $html .= '
            <form action="' . $updateRoute . '" method="POST" style="margin: 0; display: inline-block;">
                <input type="hidden" name="_token" value="' . $csrfToken . '">
                <input type="hidden" name="status" value="accepted">
                <button type="submit" style="padding: 10px 20px; border-radius: 50px; border: none; font-weight: bold; cursor: pointer; background: linear-gradient(135deg, #d63384, #b02663); color: white; transition: 0.2s ease; box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);">
                    <i class="fas fa-check"></i> Accept with Pleasure
                </button>
            </form>
            <form action="' . $updateRoute . '" method="POST" style="margin: 0; display: inline-block;">
                <input type="hidden" name="_token" value="' . $csrfToken . '">
                <input type="hidden" name="status" value="declined">
                <button type="submit" style="padding: 10px 20px; border-radius: 50px; border: 2px solid #888; font-weight: bold; cursor: pointer; background: transparent; color: #888; transition: 0.2s ease;">
                    <i class="fas fa-times"></i> Decline with Regret
                </button>
            </form>
            ';
        } else {
            $html .= '
            <form action="' . $updateRoute . '" method="POST" style="margin: 0; display: inline-block;">
                <input type="hidden" name="_token" value="' . $csrfToken . '">
                <input type="hidden" name="status" value="pending">
                <button type="submit" style="padding: 8px 16px; border-radius: 50px; border: 2px solid #d63384; font-weight: bold; cursor: pointer; background: transparent; color: #d63384; transition: 0.2s ease; font-size: 0.85rem;">
                    <i class="fas fa-undo"></i> Change RSVP
                </button>
            </form>
            ';
        }

        $html .= '</div>';

        return $html;
    }
}
