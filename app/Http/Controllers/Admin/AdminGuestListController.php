<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ceramonies;
use App\Models\GuestFamilyMember;
use App\Models\GuestList;
use App\Models\Host;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGuestListController extends Controller
{
    public function index(Request $request)
    {
        $hosts = Host::withTrashed()->get();

        $query = GuestList::withTrashed()->with(['ceramony' => function ($q) {
            $q->withTrashed();
        }]);

        if ($request->filled('host_id')) {
            $query->where('host_id', $request->host_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('guest_name', 'like', '%' . $request->search . '%')
                    ->orWhere('guest_number', 'like', '%' . $request->search . '%');
            });
        }
        $guestlists = $query->latest()->paginate(15)->withQueryString();
        return view('admin.guestlist.index', compact('guestlists', 'hosts'));
    }

    public function show($id)
    {
        $guest = GuestList::withTrashed()->with([
            'familyMembers',
            'ceramony' => function ($q) {
                $q->withTrashed();
            }
        ])->findOrFail($id);
        return view('admin.guestlist.show', compact('guest'));
    }

    public function edit($id)
    {
        $guest = GuestList::withTrashed()->with('familyMembers')->findOrFail($id);
        $ceramonies = Ceramonies::where('host_id', $guest->host_id)->withTrashed()->get();
        return view('admin.guestlist.edit', compact('guest', 'ceramonies'));
    }

    public function update(Request $request, $id)
    {
        $guest = GuestList::withTrashed()->findOrFail($id);
        $validated = $request->validate([
            'ceramony_id' => 'nullable',
            'guest_name' => 'required|string|max:255',
            'guest_number' => [
                'required',
                Rule::unique('guest_lists')->where(function ($query) use ($guest) { // <-- Pass $guest here
                    return $query->where('host_id', $guest->host_id);
                })->ignore($id)
            ],
            'guest_email' => 'nullable|email',
            'relation' => 'nullable|in:bride,groom',
            'gender' => 'nullable|in:male,female,other',
            'whatsapp_number' => 'nullable',
            'alternate_number' => 'nullable',
            'pincode' => 'nullable',
            'state' => 'nullable',
            'district' => 'nullable',
            'area_name' => 'nullable',
            'street_name' => 'nullable',
            'door_no' => 'nullable',
            'family' => 'nullable|array',
            'family.*.name' => 'required_with:family|string',
            'family.*.mobile' => 'nullable',
            'family.*.relation' => 'nullable',
        ]);

        if ($request->has('family')) {
            GuestFamilyMember::where('guest_list_id', $guest->id)->delete();
            foreach ($request->family as $member) {
                $guest->familyMembers()->create([
                    'name' => $member['name'],
                    'mobile' => $member['mobile'] ?? null,
                    'relation' => $member['relation'] ?? null,
                ]);
            }
        }
        $guest->update($validated);
        return redirect()->route('admin.guestlist.index')->with('Success', 'Guest List Updated');
    }

    public function destroy($id)
    {
        $guest = GuestList::findOrFail($id);
        $guest->delete();
        return redirect()->route('admin.guestlist.index')->with('success', 'Guest Trashed Successfully');
    }

    public function forceDelete($id)
    {
        $guest = GuestList::withTrashed()->findOrFail($id);
        GuestFamilyMember::where('guest_list_id', $guest->id)->delete();
        $guest->forceDelete();
        return redirect()->route('admin.guestlist.index')->with('Success', 'Guest Delered');
    }
}
