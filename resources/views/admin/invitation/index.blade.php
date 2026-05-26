@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">All Host Invitations</h2>
        <a href="{{ route('admin.invitation.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Invitation
        </a>
    </div>

    <!-- Success Flash Notification -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md shadow-sm flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Host Name</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wedding Couple</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Venue</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($invitations as $inv)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <!-- Host Name -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="font-bold text-indigo-600">
                                    {{ $inv->host->name ?? 'Unknown Host' }}
                                </span>
                            </td>
                            
                            <!-- Wedding Couple -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $inv->bride_name }} & {{ $inv->groom_name }}
                            </td>
                            
                            <!-- Date & Time -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                <div class="font-medium text-gray-800">{{ $inv->wedding_date }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $inv->wedding_time }}</div>
                            </td>
                            
                            <!-- Venue -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $inv->venue->venue_name ?? 'N/A' }}
                            </td>
                            
                            <!-- Actions Button Layout -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm space-x-1">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.invitation.edit', $inv->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-md text-xs font-semibold text-amber-700 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 transition-colors">
                                    Edit
                                </a>
                                
                                <!-- Delete Button Block -->
                                <form action="{{ route('admin.invitation.destroy', $inv->id) }}" 
                                      method="POST" 
                                      class="inline-block" 
                                      onsubmit="return confirm('Delete this invitation?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-200 rounded-md text-xs font-semibold text-red-700 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 italic">
                                No invitations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Links Wrapper -->
    <div class="mt-6">
        {{ $invitations->links() }}
    </div>
</div>
@endsection