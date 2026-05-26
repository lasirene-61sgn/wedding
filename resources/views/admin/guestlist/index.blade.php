@extends('layouts.admin') 

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    
    <!-- Top Heading -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Guest List Directory</h1>
            <p class="text-sm text-gray-500">View and monitor all guest records across all event hosts, including completed/archived entries.</p>
        </div>
    </div>

    <!-- Filter Control Box -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.guestlist.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            
            <!-- Filter by Host -->
            <div>
                <label for="host_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Filter By Event Host</label>
                <select name="host_id" id="host_id" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2.5 px-3.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm transition-all">
                    <option value="">-- All Registered Hosts --</option>
                    @foreach($hosts as $host)
                        <option value="{{ $host->id }}" {{ request('host_id') == $host->id ? 'selected' : '' }}>
                            {{ $host->name }} ({{ $host->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Field -->
            <div>
                <label for="search" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Search Guests</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name or phone number..." class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2 px-3.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white text-sm transition-all">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2.5 px-4 rounded-lg transition-colors shadow-sm shadow-indigo-100">
                    Apply Filters
                </button>
                @if(request()->has('host_id') || request()->has('search'))
                    <a href="{{ route('admin.guestlist.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium text-sm py-2.5 px-4 rounded-lg transition-colors text-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Data Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <th class="py-4 px-6">Guest Info</th>
                        <th class="py-4 px-6">Assigned Ceremony</th>
                        <th class="py-4 px-6">Contact Channels</th>
                        <th class="py-4 px-6">Host Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($guestlists as $guest)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <!-- Guest Identity -->
                            <td class="py-4 px-6">
                                <div class="font-semibold text-gray-900">{{ $guest->guest_name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $guest->guest_number }}</div>
                            </td>
                            
                            <!-- Ceremony Information -->
                            <td class="py-4 px-6">
                                @if($guest->ceramony)
                                    <span class="text-gray-800 font-medium">
                                        {{ $guest->ceramony->ceramony_name }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $guest->ceramony->ceramony_date ?? 'No Date Configured' }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-xs">Direct/No Main Event</span>
                                @endif
                            </td>

                            <!-- Invitation Route Metadata -->
                            <td class="py-4 px-6">
                                <span class="text-xs font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                    {{ $guest->send_via ?: 'Not Specified' }}
                                </span>
                                <div class="text-xs mt-1 {{ $guest->invitation_sent ? 'text-emerald-600' : 'text-amber-600' }}">
                                    ● {{ $guest->invitation_sent ? 'Dispatched' : 'Pending Delivery' }}
                                </div>
                            </td>

                            <!-- Soft Delete Status Badge -->
                            <td class="py-4 px-6">
                                @if($guest->trashed())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Archived (Post-5 Days)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active on Host Panel
                                    </span>
                                @endif
                            </td>

                            <!-- Action Triggers (FIXED: Integrated directly into row row structure) -->
                            <td class="py-4 px-6 text-right whitespace-nowrap text-xs space-x-1">
                                <!-- View Details -->
                                <a href="{{ route('admin.guestlist.show', $guest->id) }}" class="inline-block bg-blue-50 text-blue-600 hover:bg-blue-100 font-medium px-2.5 py-1.5 rounded transition-all">
                                    View
                                </a>

                                <!-- Edit Target -->
                                <a href="{{ route('admin.guestlist.edit', $guest->id) }}" class="inline-block bg-amber-50 text-amber-600 hover:bg-amber-100 font-medium px-2.5 py-1.5 rounded transition-all">
                                    Edit
                                </a>

                                <!-- Hard Permanent Purge -->
                                <form action="{{ route('admin.guestlist.forceDelete', $guest->id) }}" method="POST" class="inline-block" onsubmit="return confirm('WARNING: This will permanently delete this guest and all associated family details across all system backends. This operation cannot be undone. Proceed?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-100 font-medium px-2.5 py-1.5 rounded transition-all">
                                        Delete Permanently
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="text-gray-400 mb-2">
                                    <svg class="mx-auto h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <span class="text-gray-500 font-medium">No records found matching your current selection criteria.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer links -->
        @if($guestlists->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $guestlists->links() }}
            </div>
        @endif
    </div>
</div>
@endsection