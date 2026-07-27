<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Imports\GuestListimport;
use App\Models\Ceramonies;
use App\Models\GuestCategory;
use App\Models\GuestFamilyMember;
use App\Models\GuestList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\InvitationService;

class GuestListController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestList::with(['ceramony', 'category'])->where('host_id', Auth::id());
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
        $categories = GuestCategory::where('host_id', Auth::id())->get();
        return view('host.guestlist.create', compact('ceramonies', 'categories'));
    }
    public function store(Request $request)
    {
        $host = Auth::user();
        if ($host->package) {
            $currentGuestCount = GuestList::where('host_id', $host->id)->count();
            // Assuming guest_limit is a numeric string or integer. If it says "Unlimited", bypass.
            $guestLimitStr = strtolower(trim($host->package->guest_limit));
            if ($guestLimitStr !== 'unlimited' && is_numeric($guestLimitStr)) {
                if ($currentGuestCount >= (int) $guestLimitStr) {
                    return redirect()->back()->with('error', 'Guest limit reached for your current package. Please upgrade to add more guests.');
                }
            }
        }

        $validated = $request->validate([
            'category_id' => 'nullable|exists:guest_categories,id',
            'ceramony_id' => 'nullable|exists:ceramonies,id',
            'guest_name' => 'required',
            'guest_number' => [
                'required',
                Rule::unique('guest_lists')->where(function ($query) {
                    return $query->where('host_id', Auth::id());
                }),
            ],
            'guest_email' => 'nullable',
            'relation' => 'nullable|in:bride,groom,bride_parent,groom_parent',
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
            'family' => 'nullable|array',
            'family.*.name' => 'required_with:family|string',
            'family.*.mobile' => 'nullable',
            'family.*.whatsapp_number' => 'nullable',
            'family.*.realtion' => 'nullable',
            'family.*.age' => 'nullable',
            'family.*.email' => 'nullable',
            'family.*.gender' => 'nullable',
        ]);
        $validated['host_id'] = Auth::id();

        if (!empty($validated['category_id'])) {
            $category = GuestCategory::find($validated['category_id']);
            if ($category) {
                $ceremonyIds = collect($category->ceremony_ids ?? [])->map(function($item) {
                    return is_array($item) ? ($item['id'] ?? null) : $item;
                })->filter()->toArray();
                $allCeremonyNames = Ceramonies::whereIn('id', $ceremonyIds)->pluck('ceramony_name')->implode(', ');
                $validated['assigned_ceremonies'] = $allCeremonyNames;
                $validated['ceramony_id'] = $ceremonyIds[0] ?? null;
            }
        }

        $guest = GuestList::create($validated);
        if($request->has('family')){
            foreach($request->family as $member){
                $guest->familyMembers()->create($member);
            }
        }
        return redirect()->route('host.guestlist.index')->with('success', 'Guest Added Successfully');
    }

    public function edit($id)
    {
        $guestlist = GuestList::with('familyMembers')
        ->where('id', $id)
        ->where('host_id', Auth::id())->firstOrFail();
        $ceramonies = Ceramonies::where('host_id', Auth::id())->get();
        $categories = GuestCategory::where('host_id', Auth::id())->get();
        return view('host.guestlist.edit', compact('guestlist', 'ceramonies', 'categories', ));
    }

    public function update(Request $request, $id)
    {
        $guestlist = GuestList::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'category_id' => 'nullable|exists:guest_categories,id',
            'ceramony_id' => 'nullable|exists:ceramonies,id',
            'guest_name' => 'required',
            'guest_number' => [
                'required',
                Rule::unique('guest_lists')->where(function ($query) {
                    return $query->where('host_id', Auth::id());
                })->ignore($id), // Ignore the current guest's ID so you can save edits
            ],
            'guest_email' => 'nullable',
            'relation' => 'nullable|in:bride,groom,bride_parent,groom_parent',
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
            'family' => 'nullable|array',
            'family.*.name' => 'required_with:family|string',
            'family.*.mobile' => 'nullable',
            'family.*.whatsapp_number' => 'nullable',
            'family.*.realtion' => 'nullable',
            'family.*.age' => 'nullable',
            'family.*.email' => 'nullable',
            'family.*.gender' => 'nullable',
        ]);

        if (!empty($validated['category_id'])) {
            $category = GuestCategory::find($validated['category_id']);
            if ($category) {
                $ceremonyIds = collect($category->ceremony_ids ?? [])->map(function($item) {
                    return is_array($item) ? ($item['id'] ?? null) : $item;
                })->filter()->toArray();
                $allCeremonyNames = Ceramonies::whereIn('id', $ceremonyIds)->pluck('ceramony_name')->implode(', ');
                $guestlist->assigned_ceremonies = $allCeremonyNames;
                $guestlist->ceramony_id = $ceremonyIds[0] ?? null;
                // clear ceremony_ids so it doesn't overwrite below
                unset($request['ceremony_ids']); 
            }
        }

        if ($request->has('ceremony_ids')) {
            $allCeremonyNames = Ceramonies::whereIn('id', $request->ceremony_ids)->pluck('ceramony_name')->implode(', ');

            $guestlist->assigned_ceremonies = $allCeremonyNames;
            $guestlist->ceramony_id = $request->ceremony_ids[0] ?? null;
        } elseif (empty($validated['category_id'])) {
            $guestlist->assigned_ceremonies = '';
            $guestlist->ceramony_id = null;
        }

        if($request->has('family')){
            GuestFamilyMember::where('guest_list_id', $guestlist->id)->delete();
           
            foreach($request->family as $member){
                $newMember = new \App\Models\GuestFamilyMember();
            
            // FORCE the ID here
            $newMember->guest_list_id = $guestlist->id; 
            
            // Assign other fields
            $newMember->name = $member['name'];
            $newMember->mobile = $member['mobile'] ?? null;
            $newMember->whatsapp_number = $member['whatsapp_number'] ?? null;
            $newMember->relation = $member['relation'] ?? null;
            $newMember->gender = $member['gender'] ?? null;
            $newMember->age = $member['age'] ?? null;
            
            $newMember->save();
            }
        }
        $guestlist->update($validated);
        return redirect()->route('host.guestlist.index')->with('Success', 'Guest List Updated');
    }

    public function destroy($id)
    {
        $guest = GuestList::where('id', $id)->where('host_id', Auth::id())->firstOrFail();
        $guest->delete();
        return redirect()->route('host.guestlist.index')->with('Suceess', 'Guest Deleted');
    }

    public function downloadSample()
    {
        return Excel::download(new \App\Exports\GuestListSampleExport(), 'wedding_guests_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:5048',
        ]);
        
        // Note: For full accuracy, you should count the rows in the excel file first,
        // but for now, we enforce limits after or before based on basic checks.
        // A simple check if they are already at limit:
        $host = Auth::user();
        if ($host->package) {
            $currentGuestCount = GuestList::where('host_id', $host->id)->count();
            $guestLimitStr = strtolower(trim($host->package->guest_limit));
            if ($guestLimitStr !== 'unlimited' && is_numeric($guestLimitStr)) {
                if ($currentGuestCount >= (int) $guestLimitStr) {
                    return redirect()->back()->with('error', 'Guest limit reached for your current package. Cannot import more guests.');
                }
            }
        }

        Excel::import(new GuestListimport(Auth::id()), $request->file('file'));
        return redirect()->route('host.guestlist.index')->with('Success', 'Guest List Imported');
    }

    public function bulkSend(Request $request, InvitationService $invitationService)
    {
        $request->validate([
            'ids' => 'required|array',
            'category_id' => 'nullable|exists:guest_categories,id',
            'channels' => 'nullable|array',
        ]);

        $invitationExists = \App\Models\Invitation::where('host_id', Auth::id())->exists();
        if (!$invitationExists) {
            return back()->with('error', 'Please configure your Invitation Details first before sending messages.');
        }

        $category = GuestCategory::find($request->category_id);

        // FIX: Remove json_decode. Laravel's $casts already made this an array.
        $ceremonyIds = collect($category ? ($category->ceremony_ids ?? []) : [])->map(function($item) {
            return is_array($item) ? ($item['id'] ?? null) : $item;
        })->filter()->toArray();

        $allCeremonyNames = Ceramonies::whereIn('id', $ceremonyIds)
            ->pluck('ceramony_name')
            ->implode(', ');

        $selectedChannels = $request->channels ?? [];
        $channelsString = implode(', ', $selectedChannels);
        $guests = GuestList::whereIn('id', $request->ids)
            ->where('host_id', Auth::id())
            ->get();

        $host = Auth::user();
        $newSends = 0;
        
        if ($host->package && isset($host->package->invite_limit) && count($selectedChannels) > 0) {
            $inviteLimit = (int) $host->package->invite_limit;
            if ($inviteLimit > 0) {
                foreach ($guests as $g) {
                    if (!$g->invitation_sent) {
                        $newSends++;
                    }
                }
                $alreadySentCount = $host->messages_sent_count ?? 0;
                if (($alreadySentCount + $newSends) > $inviteLimit) {
                    return back()->with('error', 'Message limit reached! Your package allows sending messages to up to ' . $inviteLimit . ' guests. You have already sent ' . $alreadySentCount . ' messages.');
                }
            }
        }

        $skipped = 0;
        foreach ($guests as $guest) {

            // Enforce logic: Invitation can only be sent if Save the Date has been sent
            $canSendInvitation = $guest->save_date_sent == 1;
            
            $catId = $request->category_id ?? $guest->category_id;
            
            if (!$catId) {
                $skipped++;
                continue; // Skip guests with no category
            }
            
            if ($request->category_id) {
                $category = GuestCategory::find($request->category_id);
                $ceremonyIds = collect($category ? ($category->ceremony_ids ?? []) : [])->map(function($item) {
                    return is_array($item) ? ($item['id'] ?? null) : $item;
                })->filter()->toArray();
        
                $allCeremonyNames = Ceramonies::whereIn('id', $ceremonyIds)
                    ->pluck('ceramony_name')
                    ->implode(', ');
            } else {
                $allCeremonyNames = $guest->assigned_ceremonies;
                $ceremonyIds = [$guest->ceramony_id]; // Fallback
            }

            if ($canSendInvitation && count($selectedChannels) > 0) {
                $invitationService->sendBulkInvitations($guest, $selectedChannels, $allCeremonyNames);
            }

            $updateData = [
                'send_via' => $channelsString,
                'invitation_sent' => ($canSendInvitation && $request->has('channels')) ? true : $guest->invitation_sent,
            ];
            
            if ($request->category_id) {
                 $updateData['category_id'] = $request->category_id;
                 $updateData['assigned_ceremonies'] = $allCeremonyNames;
                 $updateData['ceramony_id'] = $ceremonyIds[0] ?? $guest->ceramony_id;
            }

            $guest->update($updateData);
        }

        if ($newSends > 0) {
            $host->messages_sent_count = ($host->messages_sent_count ?? 0) + $newSends;
            $host->save();
        }

        if ($skipped > 0) {
            return back()->with('success', 'Operation completed. Note: ' . $skipped . ' guest(s) were skipped because they have no category assigned.');
        }

        return back()->with('success', 'Category assigned and guests updated!');
    }
    public function bulkSaveDate(Request $request, InvitationService $invitationService)
    {
        $request->validate([
            'ids' => 'required|array',
            'channels' => 'nullable|array',
        ]);

        $invitationExists = \App\Models\Invitation::where('host_id', Auth::id())->exists();
        if (!$invitationExists) {
            return back()->with('error', 'Please configure your Invitation Details first before sending Save the Dates.');
        }

        $selectedChannels = $request->channels ?? [];
        $guests = GuestList::whereIn('id', $request->ids)
            ->where('host_id', Auth::id())
            ->get();

        $host = Auth::user();
        $newSends = 0;
        
        if ($host->package && isset($host->package->invite_limit) && count($selectedChannels) > 0) {
            $inviteLimit = (int) $host->package->invite_limit;
            if ($inviteLimit > 0) {
                foreach ($guests as $g) {
                    if (!$g->save_date_sent) {
                        $newSends++;
                    }
                }
                $alreadySentCount = $host->messages_sent_count ?? 0;
                if (($alreadySentCount + $newSends) > $inviteLimit) {
                    return back()->with('error', 'Message limit reached! Your package allows sending messages to up to ' . $inviteLimit . ' guests. You have already sent ' . $alreadySentCount . ' messages.');
                }
            }
        }

        $skipped = 0;
        foreach ($guests as $guest) {
            if (!$guest->category_id) {
                $skipped++;
                continue;
            }

            if (count($selectedChannels) > 0) {
                $invitationService->sendBulkSaveDate($guest, $selectedChannels);
            }
            
            $guest->update([
                'save_date_sent' => true
            ]);
        }

        if ($newSends > 0) {
            $host->messages_sent_count = ($host->messages_sent_count ?? 0) + $newSends;
            $host->save();
        }

        if ($skipped > 0) {
            return back()->with('success', 'Save the Date operation completed. ' . $skipped . ' guest(s) were skipped because they have no category assigned.');
        }

        return back()->with('success', 'Save the Date sent successfully!');
    }


    public function sendReminders(Request $request, InvitationService $invitationService)
    {
        $request->validate([
            'reminder_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $host = Auth::user();

        $invitationExists = \App\Models\Invitation::where('host_id', $host->id)->exists();
        if (!$invitationExists) {
            return back()->with('error', 'Please configure your Invitation Details first before sending Reminders.');
        }

        if ($request->hasFile('reminder_image')) {
            $imagePath = $request->file('reminder_image')->store('reminders', 'public');
            $host->reminder_image = $imagePath;
        }

        $host->reminders_active = true;
        $host->save();

        $guests = \App\Models\GuestList::where('host_id', $host->id)
            ->where('invitation_sent', true)
            ->where('reminder_sent', false)
            ->get();

        $newSends = 0;
        foreach ($guests as $g) {
            if (!$g->reminder_sent) {
                $newSends++;
            }
        }

        if ($host->package && isset($host->package->invite_limit)) {
            $inviteLimit = (int) $host->package->invite_limit;
            if ($inviteLimit > 0) {
                $alreadySentCount = $host->messages_sent_count ?? 0;
                if (($alreadySentCount + $newSends) > $inviteLimit) {
                    return back()->with('error', 'Message limit reached! Your package allows sending messages to up to ' . $inviteLimit . ' guests. You have already sent ' . $alreadySentCount . ' messages.');
                }
            }
        }

        $skipped = 0;
        foreach ($guests as $guest) {
            if (!$guest->category_id) {
                $skipped++;
                continue;
            }

            $invitationService->sendBulkReminders($guest, ['whatsapp']);
            
            $guest->update([
                'reminder_sent' => true,
                'reminder_scheduled' => true
            ]);
        }

        if ($newSends > 0) {
            $host->messages_sent_count = ($host->messages_sent_count ?? 0) + $newSends;
            $host->save();
        }

        if ($skipped > 0) {
            return back()->with('success', "Reminders operation completed. " . $skipped . " guest(s) were skipped because they have no category assigned.");
        }

        return back()->with('success', "Reminders sent successfully to all invited guests!");
    }
}
