<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use App\Models\Ceramonies;
use App\Models\Invitation;
use App\Models\SaveDate;
use App\Models\VenueName;
use App\Services\SaveDateTemplateService;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaveDateController extends Controller
{
    public function index()
    {
        $hostId = Auth::id();
        $invitation = Invitation::where('host_id', $hostId)->latest()->first();
        $saveDate = SaveDate::where('host_id', $hostId)->latest()->first();

        $categories = CategoryVenue::all();
        $ceremonies = Ceramonies::with(['category', 'venue'])->where('host_id', $hostId)->get();
        $venues = VenueName::where(function ($q) use ($hostId) {
            $q->where('host_id', $hostId)->orWhereNull('host_id');
        })->get();

        // Auto-initialize SaveDate record if not present
        if (!$saveDate) {
            $defaultCeremony = $ceremonies->firstWhere('is_main', true) ?? $ceremonies->first();
            $saveDate = SaveDate::create([
                'host_id'        => $hostId,
                'invitation_id'  => $invitation ? $invitation->id : null,
                'category_id'    => $defaultCeremony ? $defaultCeremony->category_id : null,
                'ceremony_id'    => $defaultCeremony ? $defaultCeremony->id : null,
                'venue_id'       => $defaultCeremony ? $defaultCeremony->venue_id : ($invitation ? $invitation->venue_id : null),
                'template'       => 'savethedate/invitation.html',
                'name_order'     => 'bride_groom',
                'use_first_name' => true,
                'message'        => 'Save the date! We are getting married.',
                'image'          => $invitation->wedding_image ?? null,
            ]);
        } elseif (empty($saveDate->template)) {
            $saveDate->template = 'savethedate/invitation.html';
            $saveDate->save();
        }

        // Determine active selected category
        $selectedCategoryId = $saveDate->category_id;
        if (!$selectedCategoryId && $saveDate->ceremony_id) {
            $selCeremony = $ceremonies->firstWhere('id', $saveDate->ceremony_id);
            if ($selCeremony) {
                $selectedCategoryId = $selCeremony->category_id;
            }
        }
        if (!$selectedCategoryId && $ceremonies->isNotEmpty()) {
            $mainCeremony = $ceremonies->firstWhere('is_main', true) ?? $ceremonies->first();
            $selectedCategoryId = $mainCeremony->category_id;
        }

        $templates = SaveDateTemplateService::getTemplates();
        $savedates = SaveDate::where('host_id', $hostId)->get();

        return view('host.savedate.index', compact(
            'saveDate',
            'savedates',
            'invitation',
            'categories',
            'selectedCategoryId',
            'ceremonies',
            'venues',
            'templates'
        ));
    }

    public function selectTemplate(Request $request)
    {
        $request->validate([
            'template' => 'required|string',
        ]);

        $hostId = Auth::id();
        $invitation = Invitation::where('host_id', $hostId)->latest()->first();
        $saveDate = SaveDate::where('host_id', $hostId)->latest()->first();

        if (!$saveDate) {
            $saveDate = SaveDate::create([
                'host_id'       => $hostId,
                'invitation_id' => $invitation ? $invitation->id : null,
                'template'      => $request->template,
                'name_order'    => 'bride_groom',
                'use_first_name'=> true,
                'message'       => 'Save the date! We are getting married.',
            ]);
        } else {
            $saveDate->template = $request->template;
            $saveDate->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Save The Date template updated successfully!',
                'template' => $saveDate->template,
            ]);
        }

        return redirect()->route('host.savedate.index')->with('Success', 'Save The Date template selected successfully!');
    }

    public function preview(Request $request)
    {
        $hostId = Auth::id();
        $invitation = Invitation::where('host_id', $hostId)->latest()->first();
        $saveDate = SaveDate::where('host_id', $hostId)->latest()->first();

        $template = $request->input('template') ?? $request->query('template') ?? ($saveDate->template ?? 'savethedate/invitation.html');

        // Allow previewing with query overrides (e.g. testing name order, ceremony, venue, category)
        if ($saveDate && ($request->filled('ceremony_id') || $request->filled('venue_id') || $request->filled('name_order') || $request->filled('category_id'))) {
            $saveDate = clone $saveDate;
            if ($request->filled('category_id')) $saveDate->category_id = $request->input('category_id');
            if ($request->filled('ceremony_id')) $saveDate->ceremony_id = $request->input('ceremony_id');
            if ($request->filled('venue_id')) $saveDate->venue_id = $request->input('venue_id');
            if ($request->filled('name_order')) $saveDate->name_order = $request->input('name_order');
            if ($request->has('use_first_name')) $saveDate->use_first_name = (bool)$request->input('use_first_name');
        }

        $html = SaveDateTemplateService::render($template, $invitation, $saveDate);

        if (!$html) {
            return response("<div style='padding:40px; text-align:center; font-family:sans-serif; color:#dc3545;'><strong>Template Not Found:</strong> Could not resolve template file.</div>", 200);
        }

        return response($html, 200)->header('Content-Type', 'text/html');
    }

    public function create()
    {
        $invitations = Invitation::where('host_id', Auth::id())->get();
        return view('host.savedate.create', compact('invitations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invitation_id'  => 'required|exists:invitations,id',
            'category_id'    => 'nullable|exists:category_venues,id',
            'ceremony_id'    => 'nullable|exists:ceramonies,id',
            'venue_id'       => 'nullable|exists:venue_names,id',
            'name_order'     => 'nullable|in:bride_groom,groom_bride',
            'use_first_name' => 'nullable|boolean',
            'image'          => 'nullable|mimes:jpg,jpeg,webp,gif,avif,svg|max:4048',
            'message'        => 'nullable|string|max:250',
            'template'       => 'nullable|string',
        ]);

        $categoryId = $request->category_id;
        if (empty($categoryId) && !empty($request->ceremony_id)) {
            $ceremonyObj = Ceramonies::find($request->ceremony_id);
            if ($ceremonyObj) {
                $categoryId = $ceremonyObj->category_id;
            }
        }

        $data = [
            'host_id'        => Auth::id(),
            'invitation_id'  => $request->invitation_id,
            'category_id'    => $categoryId,
            'ceremony_id'    => $request->ceremony_id,
            'venue_id'       => $request->venue_id,
            'name_order'     => $request->name_order ?? 'bride_groom',
            'use_first_name' => $request->has('use_first_name') ? (bool)$request->use_first_name : true,
            'template'       => $request->template ?? 'savethedate/invitation.html',
            'message'        => $request->message ?? 'Save the date! We are getting married.',
        ];

        if ($request->hasFile('image')) {
            $newFileSize = $request->file('image')->getSize();
            if (!StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $data['image'] = $request->file('image')->store('savedates', 'public');
        }

        SaveDate::create($data);
        return redirect()->route('host.savedate.index')->with('Success', 'Save the date announcement added');
    }

    public function edit($id)
    {
        $hostId = Auth::id();
        $savedate = SaveDate::where('id', $id)->where('host_id', $hostId)->firstOrFail();
        $categories = CategoryVenue::all();
        $ceremonies = Ceramonies::with(['category', 'venue'])->where('host_id', $hostId)->get();
        $venues = VenueName::where(function ($q) use ($hostId) {
            $q->where('host_id', $hostId)->orWhereNull('host_id');
        })->get();
        $templates = SaveDateTemplateService::getTemplates();

        $selectedCategoryId = $savedate->category_id;
        if (!$selectedCategoryId && $savedate->ceremony_id) {
            $selCeremony = $ceremonies->firstWhere('id', $savedate->ceremony_id);
            if ($selCeremony) {
                $selectedCategoryId = $selCeremony->category_id;
            }
        }

        return view('host.savedate.edit', compact('savedate', 'categories', 'selectedCategoryId', 'ceremonies', 'venues', 'templates'));
    }

    public function update(Request $request, $id)
    {
        $savedate = SaveDate::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'invitation_id'  => 'nullable|exists:invitations,id',
            'category_id'    => 'nullable|exists:category_venues,id',
            'ceremony_id'    => 'nullable|exists:ceramonies,id',
            'venue_id'       => 'nullable|exists:venue_names,id',
            'name_order'     => 'nullable|in:bride_groom,groom_bride',
            'use_first_name' => 'nullable|boolean',
            'image'          => 'nullable|mimes:jpg,jpeg,webp,gif,avif,svg|max:4048',
            'message'        => 'nullable|string|max:250',
            'template'       => 'nullable|string',
        ]);

        if (empty($validated['category_id']) && !empty($validated['ceremony_id'])) {
            $ceremonyObj = Ceramonies::find($validated['ceremony_id']);
            if ($ceremonyObj) {
                $validated['category_id'] = $ceremonyObj->category_id;
            }
        }

        $validated['use_first_name'] = $request->has('use_first_name');

        if ($request->hasFile('image')) {
            if ($savedate->image && Storage::disk('public')->exists($savedate->image)) {
                Storage::disk('public')->delete($savedate->image);
            }
            $newFileSize = $request->file('image')->getSize();
            if (!StorageService::hasSufficientStorage(Auth::id(), $newFileSize)) {
                return redirect()->back()->with('error', 'Storage limit reached. Please upgrade your package to upload more files.');
            }
            $validated['image'] = $request->file('image')->store('savedates', 'public');
        } else {
            unset($validated['image']);
        }

        $savedate->update($validated);
        return redirect()->route('host.savedate.index')->with('Success', 'Save The Date details updated successfully!');
    }

    public function destroy($id)
    {
        $savedate = SaveDate::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        if ($savedate->image && Storage::disk('public')->exists($savedate->image)) {
            Storage::disk('public')->delete($savedate->image);
        }
        $savedate->delete();
        return redirect()->route('host.savedate.index')->with('Success', 'Announcement deleted successfully');
    }
}
