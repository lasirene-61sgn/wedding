@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Host Details: {{ $host->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Review the host's profile and quick setup responses.</p>
        </div>
        <div>
            <a href="{{ route('admin.host.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 border border-gray-300 font-semibold text-sm rounded-xl shadow-xs hover:bg-gray-50 transition-colors cursor-pointer">
                <i class="bi bi-arrow-left mr-2"></i> Back to Hosts
            </a>
        </div>
    </div>

    @php
        $invitation = $host->invitations->first();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Host Profile Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Host Profile</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Name</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $host->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Email</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $host->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Mobile</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $host->mobile }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Status</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $host->status == 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ ucfirst($host->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Quick Setup</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $host->quick_setup_status == 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($host->quick_setup_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Setup CRM Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Quick Setup CRM Details</h3>
            </div>
            
            <div class="p-6">
                @if($invitation)
                    <div class="space-y-6">
                        <!-- Setup Info -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Setup Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Filled By (Role)</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $invitation->setup_role ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Relationship</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $invitation->creator_relationship ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Couple Details -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Couple Details</h4>
                            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-lg">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Bride's Name</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $invitation->bride_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1"><i class="bi bi-telephone mr-1"></i>{{ $invitation->bride_number }}</p>
                                    @if($invitation->bride_email) <p class="text-xs text-gray-500 mt-1"><i class="bi bi-envelope mr-1"></i>{{ $invitation->bride_email }}</p> @endif
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Groom's Name</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $invitation->groom_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1"><i class="bi bi-telephone mr-1"></i>{{ $invitation->groom_number }}</p>
                                    @if($invitation->groom_email) <p class="text-xs text-gray-500 mt-1"><i class="bi bi-envelope mr-1"></i>{{ $invitation->groom_email }}</p> @endif
                                </div>
                            </div>
                        </div>

                        <!-- Wedding Logistics -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Wedding Logistics</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Event Category</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        @if($invitation->wedding_category_id)
                                            {{ \App\Models\CategoryVenue::find($invitation->wedding_category_id)->category_name ?? 'N/A' }}
                                        @elseif($invitation->custom_wedding_category)
                                            {{ $invitation->custom_wedding_category }} (Custom)
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Engagement Over?</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        {{ $invitation->is_engagement_completed === 1 ? 'Yes' : ($invitation->is_engagement_completed === 0 ? 'No' : 'N/A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Wedding Date</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        @if($invitation->is_date_finalized)
                                            {{ \Carbon\Carbon::parse($invitation->wedding_date)->format('M d, Y') }}
                                        @else
                                            Not Finalized
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Venue</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        @if($invitation->is_venue_finalized)
                                            {{ $invitation->venue_name ?? 'Finalized (Name pending)' }}
                                        @else
                                            Not Finalized
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Locations -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Locations</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Current Location</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        {{ $invitation->current_city ?? 'Not specified' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Wedding Location</p>
                                    <p class="text-sm font-medium text-gray-800 mt-1">
                                        {{ collect([$invitation->wedding_city, $invitation->wedding_state])->filter()->join(', ') ?: 'Not specified' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <i class="bi bi-clipboard-x text-xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No quick setup details found.</p>
                        <p class="text-sm text-gray-400 mt-1">The host has not completed the onboarding wizard yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
