@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Service Packages</h2>
        <a href="{{ route('admin.package.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md text-sm font-semibold text-white shadow-sm transition-colors">
            Create New Package
        </a>
    </div>

    <!-- Success Flash Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Card Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-gray-700 uppercase tracking-wider text-xs">Package Name</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-gray-700 uppercase tracking-wider text-xs">Price</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-gray-700 uppercase tracking-wider text-xs">Guest Limit</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-gray-700 uppercase tracking-wider text-xs">Invite Limit</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-gray-700 uppercase tracking-wider text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($packages as $package)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <!-- Package Name -->
                        <td class="whitespace-nowrap px-6 py-4 font-bold text-gray-900">
                            {{ $package->package_name }}
                        </td>
                        
                        <!-- Price -->
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600 font-medium">
                            ${{ number_format($package->price, 2) }}
                        </td>
                        
                        <!-- Guest Limit -->
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                            {{ $package->guest_limit }}
                        </td>
                        
                        <!-- Invite Limit -->
                        <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                            {{ $package->invite_limit }}
                        </td>
                        
                        <!-- Actions -->
                        <td class="whitespace-nowrap px-6 py-4 text-sm space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.package.edit', $package->id) }}" 
                               class="inline-flex items-center px-2.5 py-1.5 border border-indigo-300 text-xs font-semibold rounded text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Edit
                            </a>
                            
                            <!-- Delete Form & Button -->
                            <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" class="inline-block">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-red-300 text-xs font-semibold rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" 
                                        onclick="return confirm('Delete this package?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection