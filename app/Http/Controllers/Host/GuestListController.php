<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Imports\GuestListimport;
use App\Models\Ceramonies;
use App\Models\GuestCategory;
use App\Models\GuestList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class GuestListController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestList::with('ceramony')->where('host_id', Auth::id());
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('guest_name', 'like', '%' . $request->search . '%')
                    ->orWhere('guest_number', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filter by Ceremony
        if ($request->filled('ceramony_id')) {
            $query->where('ceramony_id', $request->ceramony_id);
        }

        // 3. Filter by Status (Example: Not Invited yet)
        if ($request->filled('status')) {
            $query->where('invitation_sent', $request->status == 'sent' ? 1 : 0);
        }
        $categories = GuestCategory::where('host_id', Auth::id())->get();
        $guestlists = $query->latest()->paginate(10)->withQueryString();
        $ceramonies = Ceramonies::where('host_id', Auth::id())->get();
        return view('host.guestlist.index', compact('guestlists', 'ceramonies', 'categories'));
    }
    public function show($id)
    {
        $guestlist = GuestList::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        return view('host.guestlist.show', compact('guestlist'));
    }

    public function create()
    {
        $ceramonies = Ceramonies::where('host_id', Auth::id())->get();
        return view('host.guestlist.create', compact('ceramonies'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ceramony_id' => 'nullable|exists:ceramonies,id',
            'guest_name' => 'required',
            'guest_number' => [
                'required',
                Rule::unique('guest_lists')->where(function ($query) {
                    return $query->where('host_id', Auth::id());
                }),
            ],
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
        $validated['host_id'] = Auth::id();
        GuestList::create($validated);
        return redirect()->route('host.guestlist.index')->with('success', 'Guest Added Successfully');
    }

    public function edit($id)
    {
        $guestlist = GuestList::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $ceramonies = Ceramonies::where('host_id', Auth::id())->get();
        $categories = GuestCategory::where('host_id', Auth::id())->get();
        return view('host.guestlist.edit', compact('guestlist', 'ceramonies', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $guestlist = GuestList::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'ceramony_id' => 'nullable|exists:ceramonies,id',
            'guest_name' => 'required',
            'guest_number' => [
                'required',
                Rule::unique('guest_lists')->where(function ($query) {
                    return $query->where('host_id', Auth::id());
                })->ignore($id), // Ignore the current guest's ID so you can save edits
            ],
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
            'ceremony_ids' => 'nullable|array',
        ]);

        if ($request->has('ceremony_ids')) {
            $allCeremonyNames = Ceramonies::whereIn('id', $request->ceremony_ids)->pluck('ceramony_name')->implode(', ');

            $guestlist->assigned_ceremonies = $allCeremonyNames;
            $guestlist->ceramony_id = $request->ceremony_ids[0] ?? null;
        } else {
            $guestlist->assigned_ceremonies = '';
            $guestlist->ceramony_id = null;
        }
        $guestlist->update($validated);
        return redirect()->route('host.guestlist.index')->with('Success', 'Guest List Updated');
    }

    public function destroy($id)
    {
        $guest = GuestList::where('host_id', Auth::id())->firstOrFail();
        $guest->delete();
        return redirect()->route('host.guestlist.index')->with('Suceess', 'Guest Deleted');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:5048',
        ]);
        Excel::import(new GuestListimport(Auth::id()), $request->file('file'));
        return redirect()->route('host.guestlist.index')->with('Success', 'Guest List Imported');
    }

    public function bulkSend(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'category_id' => 'nullable|exists:guest_categories,id',
            'channels' => 'nullable|array',
        ]);

        $category = GuestCategory::find($request->category_id);

        // FIX: Remove json_decode. Laravel's $casts already made this an array.
        $ceremonyIds = $category->ceremony_ids;

        $allCeremonyNames = Ceramonies::whereIn('id', $ceremonyIds)
            ->pluck('ceramony_name')
            ->implode(', ');

        $channels = implode(', ', $request->channels ?? []);
        $guests = GuestList::whereIn('id', $request->ids)
            ->where('host_id', Auth::id())
            ->get();

        foreach ($guests as $guest) {
            $guest->update([
                'category_id' => $request->category_id,
                'assigned_ceremonies' => $allCeremonyNames,
                'send_via' => $channels,
                'invitation_sent' => $request->has('channels') ? true : $guest->invitation_sent,
                // Optional: Store the first ceremony ID for relation consistency
                'ceramony_id' => $ceremonyIds[0] ?? $guest->ceramony_id,
            ]);
        }

        return back()->with('success', 'Category assigned and guests updated!');
    }
}
