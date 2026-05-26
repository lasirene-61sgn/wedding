@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <a href="{{ route('admin.guestlist.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">← Back to Master List</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Guest Profile Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Demographics Sheet -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2 space-y-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Personal Metrics</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-400 block">Full Legal Name</span> <strong class="text-gray-800 text-base">{{ $guest->guest_name }}</strong></div>
                    <div><span class="text-gray-400 block">Primary Contact Number</span> <strong class="text-gray-800">{{ $guest->guest_number }}</strong></div>
                    <div><span class="text-gray-400 block">WhatsApp Line</span> <span class="text-gray-700">{{ $guest->whatsapp_number ?: 'Not Configured' }}</span></div>
                    <div><span class="text-gray-400 block">Email Address</span> <span class="text-gray-700">{{ $guest->guest_email ?: 'N/A' }}</span></div>
                    <div><span class="text-gray-400 block">Side Association</span> <span class="text-gray-700 capitalize">{{ $guest->relation ?: 'Unassigned' }}</span></div>
                    <div><span class="text-gray-400 block">Gender Structure</span> <span class="text-gray-700 capitalize">{{ $guest->gender ?: 'Unspecified' }}</span></div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Location & Delivery Address</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-400 block">Door / Flat Details</span> <span class="text-gray-700">{{ $guest->door_no }} {{ $guest->floor ? '(Floor '.$guest->floor.')' : '' }}</span></div>
                    <div><span class="text-gray-400 block">Street / Complex Location</span> <span class="text-gray-700">{{ $guest->street_name ?: $guest->complex }}</span></div>
                    <div><span class="text-gray-400 block">Area Boundary / Postal Code</span> <span class="text-gray-700">{{ $guest->area_name }} - {{ $guest->pincode }}</span></div>
                    <div><span class="text-gray-400 block">District & State Realm</span> <span class="text-gray-700">{{ $guest->district }}, {{ $guest->state }}</span></div>
                </div>
            </div>
        </div>

        <!-- Meta System Info Sidebar -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 mb-4">System Context</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <span class="text-gray-400 block">Assigned Event Room</span>
                        <span class="font-medium text-gray-900">{{ $guest->ceramony ? $guest->ceramony->ceramony_name : 'No Active Ceremony' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Database Storage Status</span>
                        @if($guest->trashed())
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">Soft-Deleted (Archived from Host)</span>
                        @else
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Live Active Stream</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Family Network List -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 mb-3">Linked Family Group Members ({{ $guest->familyMembers->count() }})</h3>
                <div class="divide-y divide-gray-100 max-h-60 overflow-y-auto">
                    @forelse($guest->familyMembers as $member)
                        <div class="py-2.5 text-sm">
                            <div class="font-medium text-gray-800">{{ $member->name }}</div>
                            <div class="text-xs text-gray-400">{{ $member->relation ?: 'Family Member' }} • {{ $member->mobile ?: 'No Phone Number' }}</div>
                        </div>
                    @empty
                        <div class="text-gray-400 italic text-xs py-4 text-center">No linked sub-family groupings found for this guest head.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection