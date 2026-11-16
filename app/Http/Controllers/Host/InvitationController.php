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

        $host = Auth::user();
        if ($host && $host->package) {
            $limit = (int) $host->package->invitaion;
            if ($limit > 0) {
                $count = Invitation::where('host_id', $host->id)->count();
                if ($count >= $limit) {
                    return redirect()->back()->withInput()->with('error', 'Invitation limit reached. Your current plan allows up to ' . $limit . ' invitations. Please upgrade your package to add more.');
                }
            }
        }

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

        if (!empty($invitation->wedding_date) && !empty($invitation->wedding_time) && !empty($invitation->venue_id)) {
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
        }

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
            $rawCss = $validated['customized_css'] ?? '';
            $rawHtml = $validated['customized_html'];

            $css = str_starts_with($rawCss, 'B64:') ? base64_decode(substr($rawCss, 4)) : $rawCss;
            $html = str_starts_with($rawHtml, 'B64:') ? base64_decode(substr($rawHtml, 4)) : $rawHtml;

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
        if (!empty($invitation->wedding_date) && !empty($invitation->wedding_time) && !empty($invitation->venue_id)) {
            $category = CategoryVenue::firstOrCreate(['category_name' => 'Wedding']);
            Ceramonies::updateOrCreate(
                ['host_id' => Auth::id(), 'is_main' => true],
                [
                    'category_id' => $category->id,
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
                ]
            );
        } else {
            // Delete main ceremony if details are removed
            Ceramonies::where('host_id', Auth::id())->where('is_main', true)->delete();
        }

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
            base_path($cleanTemplate),
            base_path('invitation/' . basename($cleanTemplate)),
            public_path('invitation/' . basename($cleanTemplate)),
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

        // Ensure base tag is present pointing to root URL for asset resolution (images/...)
        $baseUrl = url('/') . '/';
        if (preg_match('/<base\s+[^>]*href=["\'][^"\']*["\'][^>]*>/i', $html)) {
            $html = preg_replace('/<base\s+[^>]*href=["\'][^"\']*["\'][^>]*>/i', '<base href="' . $baseUrl . '">', $html);
        } elseif (str_contains($html, '<head>')) {
            $html = str_replace('<head>', "<head>\n  <base href=\"{$baseUrl}\">", $html);
        }

        // Text & Name variables
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

        // Dynamic Ceremonies Resolution
        $ceremonies = Ceramonies::where('host_id', $hostId)->get();

        // 1. Template block repetition support: <!-- CEREMONY_ITEM --> ... <!-- /CEREMONY_ITEM -->
        if (preg_match('/<!--\s*CEREMONY_ITEM\s*-->(.*?)<!--\s*\/CEREMONY_ITEM\s*-->/s', $html, $matches)) {
            $itemTemplate = $matches[1];
            $renderedItems = '';

            foreach ($ceremonies as $ceremony) {
                $cDate = $ceremony->ceramony_date ? \Carbon\Carbon::parse($ceremony->ceramony_date)->format('d M Y') : '';
                $cTime = $ceremony->ceramony_time ? \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') : '';
                $cImage = !empty($ceremony->ceramony_image) ? asset('storage/' . $ceremony->ceramony_image) : '';

                $cReplacements = [
                    '[CERAMONY_NAME]'  => htmlspecialchars($ceremony->ceramony_name),
                    '[CERAMONY_DATE]'  => $cDate,
                    '[CERAMONY_TIME]'  => $cTime,
                    '[CERAMONY_IMAGE]' => $cImage,
                ];

                $renderedItems .= str_replace(array_keys($cReplacements), array_values($cReplacements), $itemTemplate);
            }

            $html = preg_replace('/<!--\s*CEREMONY_ITEM\s*-->.*?<!--\s*\/CEREMONY_ITEM\s*-->/s', $renderedItems, $html);
            $ceremoniesHtml = ''; // Already handled inside custom template layout
        } else {
            // Fallback for simple [CEREMONIES] placeholder with un-styled semantic markup
            $ceremoniesHtml = '';
            if ($ceremonies->count() > 0) {
                $ceremoniesHtml .= '<div class="ceremonies-container">';
                foreach ($ceremonies as $ceremony) {
                    $cDate = $ceremony->ceramony_date ? \Carbon\Carbon::parse($ceremony->ceramony_date)->format('d M Y') : '';
                    $cTime = $ceremony->ceramony_time ? \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') : '';
                    $cImage = !empty($ceremony->ceramony_image) ? asset('storage/' . $ceremony->ceramony_image) : '';

                    $ceremoniesHtml .= '<div class="ceremony-item">';
                    if ($cImage) {
                        $ceremoniesHtml .= '<img src="' . $cImage . '" alt="' . htmlspecialchars($ceremony->ceramony_name) . '" class="ceremony-image">';
                    }
                    $ceremoniesHtml .= '<h4 class="ceremony-title">' . htmlspecialchars($ceremony->ceramony_name) . '</h4>';
                    $ceremoniesHtml .= '<p class="ceremony-datetime">' . $cDate . ($cTime ? ' at ' . $cTime : '') . '</p>';
                    $ceremoniesHtml .= '</div>';
                }
                $ceremoniesHtml .= '</div>';
            }
        }

        // 2. Template block repetition support for Gallery
        $pictures = Pictures::where('host_id', $hostId)->latest()->take(8)->get();
        if (preg_match('/<!--\s*GALLERY_ITEM\s*-->(.*?)<!--\s*\/GALLERY_ITEM\s*-->/s', $html, $matches)) {
            $itemTemplate = $matches[1];
            $renderedItems = '';
            foreach ($pictures as $pic) {
                $pReplacements = [
                    '[GALLERY_IMAGE]' => asset('storage/' . $pic->picture),
                ];
                $renderedItems .= str_replace(array_keys($pReplacements), array_values($pReplacements), $itemTemplate);
            }
            $html = preg_replace('/<!--\s*GALLERY_ITEM\s*-->.*?<!--\s*\/GALLERY_ITEM\s*-->/s', $renderedItems, $html);
            $galleryHtml = '';
        } else {
            $galleryHtml = '';
            if ($pictures->count() > 0) {
                $galleryHtml .= '<div class="gallery-container">';
                foreach ($pictures as $pic) {
                    $galleryHtml .= '<img class="gallery-image" src="' . asset('storage/' . $pic->picture) . '">';
                }
                $galleryHtml .= '</div>';
            }
        }

        // 3. Template block repetition support for Albums
        $albums = Albums::where('host_id', $hostId)->latest()->take(5)->get();
        if (preg_match('/<!--\s*ALBUM_ITEM\s*-->(.*?)<!--\s*\/ALBUM_ITEM\s*-->/s', $html, $matches)) {
            $itemTemplate = $matches[1];
            $renderedItems = '';
            foreach ($albums as $album) {
                $cover = !empty($album->album_images) && is_array($album->album_images) ? asset('storage/' . $album->album_images[0]) : '';
                $aReplacements = [
                    '[ALBUM_IMAGE]' => $cover,
                    '[ALBUM_NAME]'  => htmlspecialchars($album->album_name),
                ];
                $renderedItems .= str_replace(array_keys($aReplacements), array_values($aReplacements), $itemTemplate);
            }
            $html = preg_replace('/<!--\s*ALBUM_ITEM\s*-->.*?<!--\s*\/ALBUM_ITEM\s*-->/s', $renderedItems, $html);
            $albumsHtml = '';
        } else {
            $albumsHtml = '';
            if ($albums->count() > 0) {
                $albumsHtml .= '<div class="albums-container">';
                foreach ($albums as $album) {
                    $albumsHtml .= '<div class="album-item">';
                    if (!empty($album->album_images) && is_array($album->album_images)) {
                        $albumsHtml .= '<img class="album-cover" src="' . asset('storage/' . $album->album_images[0]) . '">';
                    }
                    $albumsHtml .= '<strong class="album-title">' . htmlspecialchars($album->album_name) . '</strong>';
                    $albumsHtml .= '</div>';
                }
                $albumsHtml .= '</div>';
            }
        }

        // 4. Template block repetition support for Videos
        $videos = Videos::where('host_id', $hostId)->latest()->take(5)->get();
        if (preg_match('/<!--\s*VIDEO_ITEM\s*-->(.*?)<!--\s*\/VIDEO_ITEM\s*-->/s', $html, $matches)) {
            $itemTemplate = $matches[1];
            $renderedItems = '';
            foreach ($videos as $vid) {
                $vReplacements = [
                    '[VIDEO_URL]' => asset('storage/' . $vid->videos),
                ];
                $renderedItems .= str_replace(array_keys($vReplacements), array_values($vReplacements), $itemTemplate);
            }
            $html = preg_replace('/<!--\s*VIDEO_ITEM\s*-->.*?<!--\s*\/VIDEO_ITEM\s*-->/s', $renderedItems, $html);
            $videosHtml = '';
        } else {
            $videosHtml = '';
            if ($videos->count() > 0) {
                $videosHtml .= '<div class="videos-container">';
                foreach ($videos as $vid) {
                    $videosHtml .= '<video controls class="video-item" src="' . asset('storage/' . $vid->videos) . '"></video>';
                }
                $videosHtml .= '</div>';
            }
        }

        // Dynamic Save the Date message & picture block
        $saveDate = SaveDate::where('host_id', $hostId)->latest()->first();
        $saveDateHtml = '';
        if ($saveDate) {
            $saveDateHtml = '<div class="save-the-date-container">';
            if (!empty($saveDate->image)) {
                $saveDateHtml .= '<img class="save-the-date-image" src="' . asset('storage/' . $saveDate->image) . '">';
            }
            $saveDateHtml .= '<p class="save-the-date-text">' . htmlspecialchars($saveDate->message) . '</p>';
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

        // Replace all remaining placeholders inside the loaded HTML
        $replacements = [
            '[BRIDE_NAME]'          => htmlspecialchars($bride),
            '[GROOM_NAME]'          => htmlspecialchars($groom),
            '[BRIDE_INITIAL]'       => htmlspecialchars(strtoupper($brideInitial)),
            '[GROOM_INITIAL]'       => htmlspecialchars(strtoupper($groomInitial)),
            '[BRIDE_FATHER_NAME]'   => htmlspecialchars($brideFather),
            '[BRIDE_MOTHER_NAME]'   => htmlspecialchars($brideMother),
            '[GROOM_FATHER_NAME]'   => htmlspecialchars($groomFather),
            '[GROOM_MOTHER_NAME]'   => htmlspecialchars($groomMother),
            '[WEDDING_DATE]'        => $date,
            '[WEDDING_TIME]'        => htmlspecialchars($time),
            '[VENUE_NAME]'          => htmlspecialchars($venueName),
            '[VENUE_ADDRESS]'       => htmlspecialchars($venueAddress),
            '[VENUE_MAP_URL]'       => $venueMap,
            '[CEREMONIES]'          => $ceremoniesHtml,
            '[GALLERY]'             => $galleryHtml,
            '[ALBUMS]'              => $albumsHtml,
            '[VIDEOS]'              => $videosHtml,
            '[SAVE_THE_DATE]'       => $saveDateHtml,
            '[BACKGROUND_IMAGE]'    => $bgUrl,
            '[TITLE_COLOR]'         => $request->input('text_color', $invitation->text_color ?? '#b02663'),
            '[DETAILS_COLOR]'       => $request->input('details_color', $invitation->details_color ?? '#2b4c5e'),
            // Add Guest defaults for live preview
            '[GUEST_NAME]'          => 'Guest Name',
            '[GUEST_SIDE]'          => 'Guest Side',
            '[INVITATION_TYPE]'     => 'Invitation Type',
        ];

        $originalHtml = $html;
        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // Replace common template hardcoded demo couple names with host couple names
        $coupleAmp = htmlspecialchars($bride) . ' &amp; ' . htmlspecialchars($groom);
        $couplePlain = $bride . ' & ' . $groom;
        $legacyReplacements = [
            'Mohit &amp; Preksha' => $coupleAmp,
            'Mohit & Preksha'     => $couplePlain,
            'Mohit'               => htmlspecialchars($groom),
            'Preksha'             => htmlspecialchars($bride),
            'Karan <span class="amp">&amp;</span> Simran' => $coupleAmp,
            'Karan &amp; Simran' => $coupleAmp,
            'Karan & Simran'     => $couplePlain,
            'Aarav &amp; Ananya' => $coupleAmp,
            'Aarav & Ananya'     => $couplePlain,
            'Parminder Kaur &amp; Raj Kumar' => $coupleAmp,
            'Parminder Kaur & Raj Kumar'     => $couplePlain,
            'Parminder &amp; Raj'            => $coupleAmp,
            'Parminder & Raj'                => $couplePlain,
            'Parminder'                      => htmlspecialchars($bride),
            'Raj Kumar'                      => htmlspecialchars($groom),
            'Raj'                            => htmlspecialchars($groom),
            'Arjun &amp; Diya'               => $coupleAmp,
            'Arjun & Diya'                   => $couplePlain,
            'Arjun'                          => htmlspecialchars($groom),
            'Diya'                           => htmlspecialchars($bride),
            'The Royal Palace &amp; Orchards, Jaipur' => htmlspecialchars($venueName . ($venueAddress ? ', ' . $venueAddress : '')),
            'The Royal Palace &amp; Orchards'        => htmlspecialchars($venueName),
            'The Royal Palace & Orchards'            => htmlspecialchars($venueName),
            'November 14 – 16 • Royal Palace Grounds' => $date . ' • ' . htmlspecialchars($venueName),
        ];
        $html = str_replace(array_keys($legacyReplacements), array_values($legacyReplacements), $html);

        // Format host parent strings or hide demo parent text if not configured
        $groomParentsText = trim(($groomMother ? 'Smt. ' . $groomMother : '') . ($groomMother && $groomFather ? ' & ' : '') . ($groomFather ? 'Shri ' . $groomFather : ''));
        $groomParentsHtml = $groomParentsText ? '<p>(S/o ' . htmlspecialchars($groomParentsText) . ')</p>' : '';
        $brideParentsText = trim(($brideMother ? 'Smt. ' . $brideMother : '') . ($brideMother && $brideFather ? ' & ' : '') . ($brideFather ? 'Shri ' . $brideFather : ''));
        $brideParentsHtml = $brideParentsText ? '<p>(D/o ' . htmlspecialchars($brideParentsText) . ')</p>' : '';

        // Replace demo parent sections in templates (e.g. Dudheria family lines)
        $parentDemoPatterns = [
            '/<p>\s*\(G\s*S\/o\s*Lt\.\s*Smt\.\s*Gattu\s*Devi.*?\)<\/p>\s*<p>\s*\(S\/o\s*Smt\.\s*Saroj\s*Devi.*?\)<\/p>/si' => $groomParentsHtml,
            '/\(G\s*S\/o\s*Lt\.\s*Smt\.\s*Gattu\s*Devi.*?\)<br>\s*\(S\/o\s*Smt\.\s*Saroj\s*Devi.*?\)/si' => $groomParentsText ? htmlspecialchars($groomParentsText) : '',
        ];
        foreach ($parentDemoPatterns as $pat => $rep) {
            $html = preg_replace($pat, $rep, $html, 1);
        }
        // Second block is Bride parents
        if ($brideParentsHtml) {
            $html = preg_replace('/<p>\s*\(G\s*S\/o\s*Lt\.\s*Smt\.\s*Gattu\s*Devi.*?\)<\/p>\s*<p>\s*\(S\/o\s*Smt\.\s*Saroj\s*Devi.*?\)<\/p>/si', $brideParentsHtml, $html, 1);
            $html = preg_replace('/\(G\s*S\/o\s*Lt\.\s*Smt\.\s*Gattu\s*Devi.*?\)<br>\s*\(S\/o\s*Smt\.\s*Saroj\s*Devi.*?\)/si', htmlspecialchars($brideParentsText), $html, 1);
        } else {
            $html = preg_replace('/<p>\s*\(G\s*S\/o\s*Lt\.\s*Smt\.\s*Gattu\s*Devi.*?\)<\/p>\s*<p>\s*\(S\/o\s*Smt\.\s*Saroj\s*Devi.*?\)<\/p>/si', '', $html, 1);
            $html = preg_replace('/\(G\s*S\/o\s*Lt\.\s*Smt\.\s*Gattu\s*Devi.*?\)<br>\s*\(S\/o\s*Smt\.\s*Saroj\s*Devi.*?\)/si', '', $html, 1);
        }

        // Clean demo family names and RSVP phone numbers
        $hostPhone = htmlspecialchars($invitation->groom_number ?? $invitation->bride_number ?? (Auth::user()->mobile_number ?? ''));
        $familyTitle = htmlspecialchars($bride) . ' &amp; ' . htmlspecialchars($groom) . ' Families';
        $familyReplacements = [
            'Gaget singh dudheria &amp; Family' => $familyTitle,
            'Gaget singh dudheria & Family'     => htmlspecialchars($bride) . ' & ' . htmlspecialchars($groom) . ' Families',
            'Jagat Singh Dudheria &amp; Family' => $familyTitle,
            'Jagat Singh Dudheria & Family'     => htmlspecialchars($bride) . ' & ' . htmlspecialchars($groom) . ' Families',
            'Jagat Dudheria'                    => htmlspecialchars($groom),
            'Chatur Dudheria'                   => htmlspecialchars($bride),
            'Dudheria &amp; Kothari Families, Relatives &amp; Near and Dear Friends' => $familyTitle . ', Relatives &amp; Near and Dear Friends',
            'Dudheria'                          => htmlspecialchars($groom) . ' & ' . htmlspecialchars($bride),
            'dudheria'                          => strtolower($groom),
            '+91 52125 55456'                   => $hostPhone ?: '+91 98765 43210',
            '+91 92723 93496'                   => $hostPhone ?: '+91 98765 43210',
            '+91 92723 93497'                   => $hostPhone ?: '+91 98765 43210',
        ];
        $html = str_replace(array_keys($familyReplacements), array_values($familyReplacements), $html);

        // Replace generic dummy Lorem Ipsum paragraphs with wedding invitation blessing
        $warmInvite = "We cordially invite you and your family to join us in celebrating our wedding festivities and shower your gracious blessings upon us.";
        $html = str_replace([
            "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,",
            "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
        ], $warmInvite, $html);

        // Dynamic Ceremonies injection into template interactive events / carousels
        $ceremoniesHandledInTemplate = false;
        if ($ceremonies->count() > 0) {
            // 1. Check for happywed.html carousel (const events = [...])
            $eventsOne = [];
            foreach ($ceremonies as $c) {
                $cDate = $c->ceramony_date ? \Carbon\Carbon::parse($c->ceramony_date)->format('F d, Y') : $date;
                $cTime = $c->ceramony_time ? \Carbon\Carbon::parse($c->ceramony_time)->format('h:i A') : $time;
                $eventsOne[] = [
                    'title'     => $c->ceramony_name,
                    'date'      => $cDate . ' | ' . $cTime,
                    'venue'     => $venueName . ($venueAddress ? ',<br/>' . $venueAddress : ''),
                    'dressCode' => 'Traditional Festive Formal. We warmly look forward to celebrating with you!'
                ];
            }
            $jsonOne = json_encode($eventsOne, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
            if (preg_match('/const\s+events\s*=\s*\[\s*\{.*?\}\s*\];/s', $html)) {
                $html = preg_replace('/const\s+events\s*=\s*\[\s*\{.*?\}\s*\];/s', 'const events = ' . $jsonOne . ';', $html);
                if (!empty($eventsOne[0])) {
                    $first = $eventsOne[0];
                    $html = preg_replace('/(<h3\s+id="eventTitle"[^>]*>).*?(<\/h3>)/s', '$1' . htmlspecialchars($first['title']) . '$2', $html);
                    $html = preg_replace('/(<span\s+id="eventDate"[^>]*>).*?(<\/span>)/s', '$1' . htmlspecialchars($first['date']) . '$2', $html);
                    $html = preg_replace('/(<span\s+id="eventVenue"[^>]*>).*?(<\/span>)/s', '$1' . $first['venue'] . '$2', $html);
                }
                $ceremoniesHandledInTemplate = true;
            }

            // 2. Check for happywedtwo.html slider (const WEDDING_EVENTS = [...])
            $eventsTwo = [];
            foreach ($ceremonies as $idx => $c) {
                $cDate = $c->ceramony_date ? \Carbon\Carbon::parse($c->ceramony_date)->format('F d, Y') : $date;
                $cTime = $c->ceramony_time ? \Carbon\Carbon::parse($c->ceramony_time)->format('h:i A') : $time;
                $eventsTwo[] = [
                    'title'      => $c->ceramony_name,
                    'datetime'   => $cDate . ' | ' . $cTime,
                    'venue'      => $venueName . ($venueAddress ? ',<br>' . $venueAddress : ''),
                    'inviteCode' => 'CEREMONY-' . ($idx + 1)
                ];
            }
            $jsonTwo = json_encode($eventsTwo, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
            if (preg_match('/const\s+WEDDING_EVENTS\s*=\s*\[\s*\{.*?\}\s*\];/s', $html)) {
                $html = preg_replace('/const\s+WEDDING_EVENTS\s*=\s*\[\s*\{.*?\}\s*\];/s', 'const WEDDING_EVENTS = ' . $jsonTwo . ';', $html);
                if (!empty($eventsTwo[0])) {
                    $firstTwo = $eventsTwo[0];
                    $html = preg_replace('/(<h3\s+id="event-title"[^>]*>).*?(<\/h3>)/s', '$1' . htmlspecialchars($firstTwo['title']) . '$2', $html);
                    $html = preg_replace('/(<p\s+id="event-datetime"[^>]*>).*?(<\/p>)/s', '$1' . htmlspecialchars($firstTwo['datetime']) . '$2', $html);
                    $html = preg_replace('/(<p\s+id="event-venue"[^>]*>).*?(<\/p>)/s', '$1' . $firstTwo['venue'] . '$2', $html);
                }
                $ceremoniesHandledInTemplate = true;
            }

            // 3. Check for happywedseven.html timeline (#view-events)
            if (str_contains($html, 'id="view-events"')) {
                $timelineHtml = "\n      <div class=\"relative max-w-4xl mx-auto\">\n";
                $timelineHtml .= "        <div aria-hidden=\"true\" class=\"absolute top-3 bottom-3 left-[19px] md:left-1/2 md:-translate-x-1/2 w-[1px] bg-goldLine\"></div>\n";

                $defaultImgs = [
                    asset('images/happywed/Rectangle 3717.png'),
                    asset('images/happywed/Rectangle 3721.png'),
                    asset('images/happywed/Rectangle 3721 (3).png'),
                    asset('images/happywedtwo/Rectangle 3721 (4).png'),
                    asset('images/happywedtwo/Rectangle 3721 (5).png'),
                ];

                $calendarItemsHtml = '';

                foreach ($ceremonies as $idx => $c) {
                    $cDateRaw = $c->ceramony_date ? \Carbon\Carbon::parse($c->ceramony_date) : null;
                    $cDateLabel = $cDateRaw ? strtoupper($cDateRaw->format('l, M d')) : strtoupper($date);
                    $cDateCal = $cDateRaw ? $cDateRaw->format('D, M d') : $date;
                    $cTimeFormatted = $c->ceramony_time ? \Carbon\Carbon::parse($c->ceramony_time)->format('h:i A') : $time;
                    $cName = $c->ceramony_name;
                    $cNameLower = strtolower($cName);

                    // Image selection based on host upload or matching ceremony theme
                    if (!empty($c->ceramony_image)) {
                        $cImg = asset('storage/' . $c->ceramony_image);
                    } else {
                        if (str_contains($cNameLower, 'mehendi') || str_contains($cNameLower, 'mehndi')) {
                            $cImg = asset('images/happywed/Rectangle 3721.png');
                        } elseif (str_contains($cNameLower, 'haldi')) {
                            $cImg = asset('images/happywed/Rectangle 3718.png');
                        } elseif (str_contains($cNameLower, 'sangeet') || str_contains($cNameLower, 'cocktail') || str_contains($cNameLower, 'dance') || str_contains($cNameLower, 'music')) {
                            $cImg = asset('images/happywed/Rectangle 3721 (3).png');
                        } elseif (str_contains($cNameLower, 'wedding') || str_contains($cNameLower, 'phera') || str_contains($cNameLower, 'vivah') || str_contains($cNameLower, 'shaadi') || str_contains($cNameLower, 'marriage') || str_contains($cNameLower, 'mandap') || str_contains($cNameLower, 'baraat')) {
                            $cImg = asset('images/happywedtwo/Rectangle 3721 (4).png');
                        } elseif (str_contains($cNameLower, 'engagement') || str_contains($cNameLower, 'ring') || str_contains($cNameLower, 'roka') || str_contains($cNameLower, 'shagun') || str_contains($cNameLower, 'save the date')) {
                            $cImg = asset('images/happywed/Rectangle 3717.png');
                        } elseif (str_contains($cNameLower, 'reception')) {
                            $cImg = asset('images/happywed/Rectangle 3717.png');
                        } elseif (str_contains($cNameLower, 'brunch') || str_contains($cNameLower, 'lunch') || str_contains($cNameLower, 'dinner') || str_contains($cNameLower, 'farewell') || str_contains($cNameLower, 'breakfast')) {
                            $cImg = asset('images/happywedtwo/Rectangle 3721 (5).png');
                        } elseif (str_contains($cNameLower, 'pooja') || str_contains($cNameLower, 'puja') || str_contains($cNameLower, 'vidhi') || str_contains($cNameLower, 'hastamelap')) {
                            $cImg = asset('images/happywed/Rectangle 3719.png');
                        } else {
                            $cImg = $defaultImgs[$idx % count($defaultImgs)];
                        }
                    }

                    // Icon & Category Name
                    if (str_contains($cNameLower, 'mehendi') || str_contains($cNameLower, 'mehndi')) {
                        $cIcon = 'spa';
                        $cCategory = 'WELCOME &amp; MEHENDI';
                    } elseif (str_contains($cNameLower, 'haldi')) {
                        $cIcon = 'spa';
                        $cCategory = 'AUSPICIOUS HALDI';
                    } elseif (str_contains($cNameLower, 'sangeet') || str_contains($cNameLower, 'cocktail') || str_contains($cNameLower, 'dance')) {
                        $cIcon = 'celebration';
                        $cCategory = 'SANGEET &amp; CELEBRATION';
                    } elseif (str_contains($cNameLower, 'wedding') || str_contains($cNameLower, 'phera') || str_contains($cNameLower, 'vivah') || str_contains($cNameLower, 'mandap') || str_contains($cNameLower, 'baraat')) {
                        $cIcon = 'temple_hindu';
                        $cCategory = 'THE WEDDING CEREMONY';
                    } elseif (str_contains($cNameLower, 'engagement') || str_contains($cNameLower, 'ring') || str_contains($cNameLower, 'save the date')) {
                        $cIcon = 'favorite';
                        $cCategory = 'THE ENGAGEMENT';
                    } elseif (str_contains($cNameLower, 'reception')) {
                        $cIcon = 'celebration';
                        $cCategory = 'GRAND RECEPTION';
                    } elseif (str_contains($cNameLower, 'brunch') || str_contains($cNameLower, 'lunch') || str_contains($cNameLower, 'dinner') || str_contains($cNameLower, 'farewell')) {
                        $cIcon = 'brunch_dining';
                        $cCategory = 'FEAST &amp; FAREWELL';
                    } else {
                        $cIcon = 'celebration';
                        $cCategory = 'CEREMONY CELEBRATION';
                    }

                    $isEven = ($idx % 2 === 0);
                    $mbClass = ($idx === $ceremonies->count() - 1) ? '' : 'mb-14 md:mb-20';

                    $cardHtml = '
            <article class="bg-white/95 border border-goldBorder rounded-xl overflow-hidden shadow-card hover:shadow-cardHover transition-all duration-300 max-w-md group">
              <div class="relative w-full h-44 sm:h-48 overflow-hidden bg-amber-50">
                <img
                  src="' . htmlspecialchars($cImg) . '"
                  alt="' . htmlspecialchars($cName) . '"
                  class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                  onerror="this.style.display=\'none\'"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent pointer-events-none"></div>
                <span class="absolute bottom-3 left-3 text-[10px] font-bold uppercase tracking-[0.15em] text-white bg-forestDark/85 backdrop-blur-sm px-2.5 py-1 rounded shadow-sm">
                  ' . $cCategory . '
                </span>
                ' . ($cTimeFormatted ? '<span class="absolute bottom-3 right-3 text-[11px] font-semibold text-white bg-black/60 backdrop-blur-sm px-2.5 py-0.5 rounded">' . htmlspecialchars($cTimeFormatted) . '</span>' : '') . '
              </div>
              <div class="p-5 sm:p-6">
                <h2 class="font-serif font-bold text-xl sm:text-[22px] text-charcoal mb-2 leading-snug">
                  ' . htmlspecialchars($cName) . '
                </h2>
                <p class="text-xs sm:text-[13px] leading-relaxed text-bodyMuted mb-4">
                  Join us to celebrate the auspicious ' . htmlspecialchars($cName) . ' of ' . $coupleAmp . ' and bless the couple with your gracious presence.
                </p>
                <div class="flex items-center gap-1.5 text-[11px] sm:text-xs font-medium text-forestText">
                  <span class="material-symbols-outlined text-[16px] text-forestText">location_on</span>
                  <span>' . htmlspecialchars($venueName) . '</span>
                </div>
              </div>
            </article>';

                    if ($isEven) {
                        // Left Label, Right Card
                        $timelineHtml .= '
        <!-- MILESTONE ' . ($idx + 1) . ' -->
        <div class="timeline-reveal relative grid grid-cols-[40px_1fr] md:grid-cols-[1fr_56px_1fr] items-center gap-x-4 md:gap-x-6 ' . $mbClass . '">
          <!-- Left Column (Desktop Date Label) -->
          <div class="hidden md:flex justify-end pr-4">
            <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-forestText">
              ' . $cDateLabel . '
            </span>
          </div>

          <!-- Center Milestone Badge -->
          <div class="relative z-10 flex justify-center">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-forestDark text-ivory flex items-center justify-center shadow-sm ring-4 ring-ivory">
              <span class="material-symbols-outlined text-[18px] sm:text-[20px]">' . $cIcon . '</span>
            </div>
          </div>

          <!-- Right Column (Card) -->
          <div class="md:pl-4">
            <span class="block md:hidden text-[10px] font-semibold uppercase tracking-[0.16em] text-forestText mb-2">
              ' . $cDateLabel . '
            </span>
            ' . $cardHtml . '
          </div>
        </div>' . "\n";
                    } else {
                        // Left Card, Right Label
                        $timelineHtml .= '
        <!-- MILESTONE ' . ($idx + 1) . ' -->
        <div class="timeline-reveal relative grid grid-cols-[40px_1fr] md:grid-cols-[1fr_56px_1fr] items-center gap-x-4 md:gap-x-6 ' . $mbClass . '">
          <!-- Left Column (Desktop Card) -->
          <div class="order-2 md:order-1 md:pr-4 flex md:justify-end">
            <div class="w-full max-w-md">
              <span class="block md:hidden text-[10px] font-semibold uppercase tracking-[0.16em] text-forestText mb-2">
                ' . $cDateLabel . '
              </span>
              ' . $cardHtml . '
            </div>
          </div>

          <!-- Center Milestone Badge -->
          <div class="order-1 md:order-2 relative z-10 flex justify-center">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-forestDark text-ivory flex items-center justify-center shadow-sm ring-4 ring-ivory">
              <span class="material-symbols-outlined text-[18px] sm:text-[20px]">' . $cIcon . '</span>
            </div>
          </div>

          <!-- Right Column (Desktop Date Label) -->
          <div class="hidden md:flex md:order-3 justify-start pl-4">
            <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-forestText">
              ' . $cDateLabel . '
            </span>
          </div>
        </div>' . "\n";
                    }

                    // Calendar items
                    $calendarItemsHtml .= '
          <div class="p-3.5 rounded-lg bg-ivory border border-goldLine flex justify-between items-center">
            <div>
              <p class="font-semibold text-charcoal">' . $cDateCal . ' • ' . htmlspecialchars($cName) . '</p>
              <p class="text-xs text-bodyMuted">' . htmlspecialchars($cTimeFormatted) . ' • ' . htmlspecialchars($venueName) . '</p>
            </div>
            <span class="text-xs font-semibold text-forestText">Event ' . ($idx + 1) . '</span>
          </div>';
                }

                $timelineHtml .= "      </div>\n    ";

                // Replace vertical timeline inside #view-events
                $html = preg_replace('/(<div\s+id="view-events"[^>]*>).*?(<\/div>\s*<!--\s*VIEW B:)/s', '$1' . $timelineHtml . '$2', $html);

                // Also replace Calendar view entries if present
                if (!empty($calendarItemsHtml)) {
                    $html = preg_replace('/(<div\s+id="view-calendar"[^>]*>.*?<div\s+class="space-y-3\s+text-sm">).*?(<\/div>\s*<\/div>\s*<\/div>\s*<!--\s*VIEW D:)/s', '$1' . $calendarItemsHtml . "\n        $2", $html);
                }

                $ceremoniesHandledInTemplate = true;
            }
        }

        // Auto-inject missing dynamic content if placeholders weren't present in the template ONLY for Ceremonies
        $autoInject = '';
        if ($ceremoniesHtml && !$ceremoniesHandledInTemplate && !str_contains($originalHtml, '[CEREMONIES]')) {
            $autoInject .= '<div class="injected-section injected-ceremonies"><h3 style="color:' . $replacements['[TITLE_COLOR]'] . '">Ceremonies</h3>' . $ceremoniesHtml . '</div>';
        }

        if ($autoInject !== '') {
            $wrapper = '<div class="auto-injected-content">' . $autoInject . '</div>';
            if (str_contains($html, '</body>')) {
                $html = str_replace('</body>', $wrapper . '</body>', $html);
            } else {
                $html .= $wrapper;
            }
        }

        return response($html)->header('Content-Type', 'text/html');
    }
}
