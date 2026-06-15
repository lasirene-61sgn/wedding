@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">All Ceremonies</h2>
        <a href="{{ route('admin.ceramony.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Ceremony
        </a>
        <a href="{{ route('admin.ceramony.backgrounds.index') }}" class="btn btn-info text-black">
            <i class="fa fa-images"></i> Manage Background Images
        </a>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Image</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ceremony Name</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Host</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Venue</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-44">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($ceramonies as $ceramony)
                        <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-indigo-50/30 transition-colors">
                            <!-- Image Column -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <img src="{{ asset('storage/' . $ceramony->ceramony_image) }}" 
                                     alt="{{ $ceramony->ceramony_name }}" 
                                     class="w-14 h-14 object-cover rounded-md border border-gray-100 shadow-sm">
                            </td>
                            
                            <!-- Ceremony Name -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ $ceramony->ceramony_name }}
                            </td>
                            
                            <!-- Host -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $ceramony->host->name ?? 'N/A' }}
                            </td>
                            
                            <!-- Category -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $ceramony->category->category_name ?? 'N/A' }}
                                </span>
                            </td>
                            
                            <!-- Venue -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $ceramony->venue->venue_name ?? 'N/A' }}
                            </td>
                            
                            <!-- Date / Time -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 font-medium">
                                <div class="text-gray-700">{{ $ceramony->ceramony_date }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $ceramony->ceramony_time }}</div>
                            </td>
                            
                            <!-- Action Elements -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm space-x-1">
                                <!-- Edit Link -->
                                <a href="{{ route('admin.ceramony.edit', $ceramony->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-sky-50 border border-sky-200 rounded-md text-xs font-semibold text-sky-700 hover:bg-sky-100 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 transition-colors">
                                    Edit
                                </a>
                                
                                <!-- Delete Inline Form -->
                                <form action="{{ route('admin.ceramony.destroy', $ceramony->id) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this ceremony?')">
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
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400 italic">
                                No ceremonies found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection