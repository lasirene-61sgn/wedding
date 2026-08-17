<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\CeramonyBackground;
use App\Models\Invitation;
use App\Models\Pictures;
use App\Models\Albums;
use App\Models\Videos;
use App\Models\SaveDate;
use App\Models\VenueName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::where('host_id', Auth::id())->get();
        return view('host.invitation.index', compact('invitations'));
    }

    public function create()
    {
        $venues = VenueName::where(function ($query) {
            $query->where('host_id', Auth::id())->orWhereNull('host_id');
        })->get();
        $backgrounds = CeramonyBackground::all();

        return view('host.invitation.create', compact('venues', 'backgrounds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'nullable|exists:venue_names,id',
            'invite' => 'nullable|in:brideparents,groomparents,bride,groom,weddingcouple',
            'bride_name' => 'required|string',
            'bride_number' => 'required|string',
            'bride_email' => 'nullable|email',
            'bride_father_name' => 'required|string',
            'bride_mother_name' => 'required|string',
            'groom_name' => 'required|string',
            'groom_number' => 'required|string',
            'groom_email' => 'nullable|email',
            'groom_father_name' => 'required|string',
            'groom_mother_name' => 'required|string',
            'wedding_date' => 'nullable|date',
            'wedding_time' => 'nullable',
            'wedding_image' => 'nullable|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
            'selected_background_id' => 'nullable|exists:ceramony_backgrounds,id',
            'selected_html_template' => 'nullable|string',
            'text_color' => 'nullable|string|max:7',
            'details_color' => 'nullable|string|max:7',
            'text_positions' => 'nullable|string',
            'custom_canvas_texts' => 'nullable|string'
        ]);

        $validated['host_id'] = Auth::id();
        $validated['is_main'] = false;

        if (isset($validated['text_positions'])) {
            $validated['text_positions'] = json_decode($validated['text_positions'], true);
        }

        if (isset($validated['custom_canvas_texts'])) {
            $validated['custom_canvas_texts'] = json_decode($validated['custom_canvas_texts'], true);
        }

        if ($request->hasFile('wedding_image')) {
            $newFileSize = $request->file('wedding_image')->getSize();
            if (class_exists('\App\Services\StorageService') && !\App\Services\StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }

        $invitation = Invitation::create($validated);

        $category = CategoryVenue::firstOrCreate(['category_name' => 'Wedding']);
        Ceramonies::create([
            'host_id' => Auth::id(),
            'category_id' => $category->id,
            'venue_id' => $invitation->venue_id,
            'ceramony_name' => 'Wedding: ' . $invitation->bride_name . ' & ' . $invitation->groom_name,
            'ceramony_date' => $invitation->wedding_date,
            'ceramony_time' => $invitation->wedding_time,
            'ceramony_image' => $invitation->wedding_image,
            'is_main' => true,
            'selected_background_id' => $invitation->selected_background_id,
            'selected_html_template' => $invitation->selected_html_template,
            'text_color' => $invitation->text_color,
            'details_color' => $invitation->details_color,
            'text_positions' => $invitation->text_positions,
            'custom_canvas_texts' => $invitation->custom_canvas_texts,
        ]);

        SaveDate::create([
            'host_id' => Auth::id(),
            'invitation_id' => $invitation->id,
            'image' => $invitation->wedding_image,
            'message' => 'Save the date! We are getting married.',
        ]);

        return redirect()->route('host.invitation.index')->with('Message', 'Invitation Created Successfully');
    }

    public function edit($id)
    {
        $invitation = Invitation::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $venues = VenueName::where(function ($query) {
            $query->where('host_id', Auth::id())->orWhereNull('host_id');
        })->get();
        $backgrounds = CeramonyBackground::all();

        $categories = CategoryVenue::all();
        $htmlTemplates = [];
        foreach ($categories as $category) {
            if (is_array($category->sub_categories)) {
                foreach ($category->sub_categories as $sub) {
                    if (isset($sub['html_files']) && is_array($sub['html_files'])) {
                        foreach ($sub['html_files'] as $file) {
                            $htmlTemplates[] = [
                                'category' => $category->category_name,
                                'subcategory' => $sub['name'] ?? 'General',
                                'file' => $file
                            ];
                        }
                    }
                }
            }
        }

        $hostId = Auth::id();
        $ceremonies = Ceramonies::where('host_id', $hostId)->get();
        $pictures = Pictures::where('host_id', $hostId)->latest()->get();
        $albums = Albums::where('host_id', $hostId)->latest()->get();
        $videos = Videos::where('host_id', $hostId)->latest()->get();
        $saveDate = SaveDate::where('host_id', $hostId)->latest()->first();

        // Add 'categories' inside compact()
        return view('host.invitation.edit', compact('invitation', 'venues', 'backgrounds', 'htmlTemplates', 'categories', 'ceremonies', 'pictures', 'albums', 'videos', 'saveDate'));
    }

    public function update(Request $request, $id)
    {
        $invitation = Invitation::where('id', $id)->where('host_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'venue_id' => 'nullable|exists:venue_names,id',
            'invite' => 'nullable|in:brideparents,groomparents,bride,groom,weddingcouple',
            'theme' => 'nullable|in:classic,royal,floral',
            'bride_name' => 'nullable|string',
            'bride_number' => 'nullable|string',
            'bride_email' => 'nullable|email',
            'bride_father_name' => 'nullable|string',
            'bride_mother_name' => 'nullable|string',
            'groom_name' => 'nullable|string',
            'groom_number' => 'nullable|string',
            'groom_email' => 'nullable|email',
            'groom_father_name' => 'nullable|string',
            'groom_mother_name' => 'nullable|string',
            'wedding_date' => 'nullable|date',
            'wedding_time' => 'nullable',
            'wedding_image' => 'nullable|mimes:jpeg,png,svg,gif,webp,avif|max:3048',
            'selected_background_id' => 'nullable|exists:ceramony_backgrounds,id',
            'selected_html_template' => 'nullable|string',
            'customized_html' => 'nullable|string',
            'customized_css' => 'nullable|string',
            'text_color' => 'nullable|string|max:7',
            'details_color' => 'nullable|string|max:7',
            'text_positions' => 'nullable|string',
            'custom_canvas_texts' => 'nullable|string'
        ]);

        if (isset($validated['text_positions']) && is_string($validated['text_positions'])) {
            $validated['text_positions'] = json_decode($validated['text_positions'], true);
        }

        if (isset($validated['custom_canvas_texts']) && is_string($validated['custom_canvas_texts'])) {
            $validated['custom_canvas_texts'] = json_decode($validated['custom_canvas_texts'], true);
        }

        // If the user selected a different template, delete their old custom HTML file
        $customFilename = "host_" . Auth::id() . "_inv_" . $invitation->id . ".html";
        $customFilepath = public_path('uploads/host_templates/' . $customFilename);
        
        if ($request->filled('selected_html_template') && $request->input('selected_html_template') !== $invitation->selected_html_template) {
            if (file_exists($customFilepath)) {
                unlink($customFilepath);
            }
        }

        // Save custom GrapesJS HTML output if they interacted with the Live Editor
        if (!empty($validated['customized_html'])) {
            $css = $validated['customized_css'] ?? '';
            $html = $validated['customized_html'];

            if (!empty($css)) {
                if (str_contains($html, '</head>')) {
                    $html = str_replace('</head>', "<style>$css</style>\n</head>", $html);
                } else {
                    $html = "<style>$css</style>\n" . $html;
                }
            }

            $directory = public_path('uploads/host_templates');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($customFilepath, $html);
            
            // We DO NOT overwrite $validated['selected_html_template'] anymore!
            // We keep the original template path in the database so the Category/Subcategory dropdowns remember it.
        }

        if ($request->hasFile('wedding_image')) {
            if ($invitation->wedding_image) {
                Storage::disk('public')->delete($invitation->wedding_image);
            }
            $newFileSize = $request->file('wedding_image')->getSize();
            if (class_exists('\App\Services\StorageService') && !\App\Services\StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $validated['wedding_image'] = $request->file('wedding_image')->store('wedding_images', 'public');
        }

        $invitation->update($validated);

        // Update Main Ceremony record
        $mainCeremonyName = 'Wedding: ' . ($invitation->bride_name ?? '') . ' & ' . ($invitation->groom_name ?? '');
        Ceramonies::where('host_id', Auth::id())
            ->where('is_main', true)
            ->update([
                'ceramony_name' => $mainCeremonyName,
                'ceramony_date' => $invitation->wedding_date,
                'ceramony_time' => $invitation->wedding_time,
                'venue_id' => $invitation->venue_id,
                'ceramony_image' => $invitation->wedding_image,
                'selected_background_id' => $invitation->selected_background_id,
                'selected_html_template' => $invitation->selected_html_template,
                'text_color' => $invitation->text_color,
                'details_color' => $invitation->details_color,
                'text_positions' => $invitation->text_positions,
                'custom_canvas_texts' => $invitation->custom_canvas_texts,
            ]);

        return redirect()->route('host.invitation.index')->with('Success', 'Invitation Updated Successfully');
    }

    public function livePreview(Request $request)
    {
        $hostId = Auth::guard('host')->id() ?? Auth::id();

        $template = $request->input('template') ?? $request->query('template');

        if (!$template || trim($template) === '') {
            return response('<div style="padding:40px; text-align:center; color:#888; font-family: sans-serif;">Please select a template from the dropdown above to preview.</div>', 200);
        }

        // Clean leading slashes and paths
        $cleanTemplate = ltrim($template, '/\\');

        // Fetch existing invitation record if available
        $invitation = Invitation::where('host_id', $hostId)->first();

        // Multi-path resolution across common public and storage structures
        $possiblePaths = [
            public_path($cleanTemplate),
            public_path('uploads/' . $cleanTemplate),
            public_path('uploads/host_templates/' . basename($cleanTemplate)),
            storage_path('app/public/' . $cleanTemplate),
            base_path($cleanTemplate)
        ];

        // Prioritize the user's custom template IF they are previewing their currently saved template
        $customFilename = "host_" . $hostId . "_inv_" . ($invitation ? $invitation->id : 0) . ".html";
        $customFilepath = public_path('uploads/host_templates/' . $customFilename);
        if ($invitation && $template === $invitation->selected_html_template && file_exists($customFilepath)) {
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
            return response("<div style='padding:30px; text-align:center; color:#dc3545; font-family: sans-serif;'><strong>Template Not Found:</strong> Could not resolve file path for <code>" . htmlspecialchars($cleanTemplate) . "</code></div>", 200);
        }

        $html = file_get_contents($path);

        // Text & Name variables: prioritizes active form input, falls back to saved model data
        $bride = $request->input('bride_name', $invitation->bride_name ?? 'Bride Name');
        $groom = $request->input('groom_name', $invitation->groom_name ?? 'Groom Name');
        $brideFather = $request->input('bride_father_name', $invitation->bride_father_name ?? '');
        $brideMother = $request->input('bride_mother_name', $invitation->bride_mother_name ?? '');
        $groomFather = $request->input('groom_father_name', $invitation->groom_father_name ?? '');
        $groomMother = $request->input('groom_mother_name', $invitation->groom_mother_name ?? '');

        $rawDate = $request->input('wedding_date', $invitation->wedding_date ?? null);
        $date = !empty($rawDate) ? \Carbon\Carbon::parse($rawDate)->format('d F Y') : 'Date to be announced';
        $time = $request->input('wedding_time', $invitation->wedding_time ?? 'Time to be announced');

        // Venue details resolution
        $venueId = $request->input('venue_id', $invitation->venue_id ?? null);
        $venueName = 'Venue to be announced';
        $venueAddress = '';
        $venueMap = '';

        if ($venueId) {
            $venue = VenueName::find($venueId);
            if ($venue) {
                $venueName = $venue->venue_name;
                $venueAddress = trim($venue->venue_address . ', ' . $venue->district . ', ' . $venue->state, ', ');
                $venueMap = $venue->location_map ?? '';
            }
        }

        // Dynamic Ceremonies HTML block
        $ceremonies = Ceramonies::where('host_id', $hostId)->get();
        $ceremoniesHtml = '';
        if ($ceremonies->count() > 0) {
            $ceremoniesHtml .= '<div class="ceremonies-container" style="display: grid; gap: 12px; margin: 15px 0;">';
            foreach ($ceremonies as $ceremony) {
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
        $saveDate = SaveDate::where('host_id', $hostId)->latest()->first();
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
        $bgId = $request->input('selected_background_id', $invitation->selected_background_id ?? null);
        $bgUrl = '';
        if ($bgId) {
            $bg = CeramonyBackground::find($bgId);
            if ($bg) {
                $bgUrl = asset('storage/' . $bg->image_path);
            }
        }

        // Extract initials safely
        $brideInitial = !empty(trim($bride)) ? mb_substr(trim($bride), 0, 1) : '';
        $groomInitial = !empty(trim($groom)) ? mb_substr(trim($groom), 0, 1) : '';

        // Replace all placeholders inside the loaded HTML
        $replacements = [
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
            '[TITLE_COLOR]' => $request->input('text_color', $invitation->text_color ?? '#b02663'),
            '[DETAILS_COLOR]' => $request->input('details_color', $invitation->details_color ?? '#2b4c5e'),
        ];

        $originalHtml = $html;
        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // Auto-inject missing dynamic content if placeholders weren't present in raw template
        $autoInject = '';
        if ($ceremoniesHtml && !str_contains($originalHtml, '[CEREMONIES]')) {
            $autoInject .= '<div style="margin-top:40px; text-align:center;"><h3 style="color:' . $replacements['[TITLE_COLOR]'] . '">Other Ceremonies</h3>' . $ceremoniesHtml . '</div>';
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

        if ($autoInject !== '') {
            $wrapper = '<div class="auto-injected-content" style="max-width:800px; margin: 40px auto; padding: 20px; font-family: sans-serif;">' . $autoInject . '</div>';
            if (str_contains($html, '</body>')) {
                $html = str_replace('</body>', $wrapper . '</body>', $html);
            } else {
                $html .= $wrapper;
            }
        }

        return response($html)->header('Content-Type', 'text/html');
    }
}
