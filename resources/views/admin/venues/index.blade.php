@extends('layouts.admin') {{-- Change this to match your actual admin layout path if different --}}

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Manage Venues</h1>
            <p class="text-sm text-gray-500 mt-1">Manage global venues accessible by all hosts, or view host-specific entries.</p>
        </div>
        <a href="{{ route('admin.venue.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm shadow-blue-100">
            <i class="bi bi-plus-lg mr-2"></i> Add New Global Venue
        </a>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center text-green-700">
            <i class="bi bi-check-circle-fill mr-3 text-lg"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Venue Name</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Pincode</th>
                        <th class="px-6 py-4">Scope Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($venues as $venue)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-400">#{{ $venue->id }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 block">{{ $venue->venue_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $venue->area_name }}, {{ $venue->state }}</td>
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ $venue->pincode }}</td>
                            <td class="px-6 py-4">
                                @if(is_null($venue->host_id))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="bi bi-globe mr-1.5"></i> Global (All Hosts)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        <i class="bi bi-person-badge mr-1.5"></i> Private (Host ID: {{ $venue->host_id }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <a href="{{ route('admin.venue.edit', $venue->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl transition-colors">
                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                </a>
                                
                                <form action="{{ route('admin.venue.destroy', $venue->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you completely sure you want to delete this venue?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-100 hover:bg-red-100 text-red-600 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                                        <i class="bi bi-trash3 mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="bi bi-geo-alt text-4xl text-gray-300"></i>
                                    <p class="text-base font-semibold text-gray-500">No venues registered yet</p>
                                    <p class="text-xs text-gray-400">Click the button above to add a platform-wide global venue.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($venues->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $venues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection