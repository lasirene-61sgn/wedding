@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 w-full antialiased">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Service Packages</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage base configurations, access metrics, and dynamic package details.</p>
        </div>
        <a href="{{ route('admin.package.create') }}" 
           class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md text-sm font-semibold text-white shadow-sm transition-colors duration-150">
            Create New Package
        </a>
    </div>

    <!-- Success Flash Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Mobile Responsive Card Grid Layout (Shows on small viewports only) -->
    <div class="grid grid-cols-1 gap-4 md:hidden mb-6">
        @forelse($packages as $package)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">{{ $package->package_name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Valid: {{ $package->validity ? \Carbon\Carbon::parse($package->validity)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <!-- Fixed Mobile Custom Price Representation String -->
                    <span class="text-lg font-bold text-indigo-600">
                        ₹{{ $package->price }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <div>
                        <span class="block text-gray-400 font-medium">Guest Limit</span>
                        <span class="text-gray-800 font-semibold">{{ $package->guest_limit }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 font-medium">Invite Limit</span>
                        <span class="text-gray-800 font-semibold">{{ $package->invite_limit }}</span>
                    </div>
                </div>

                @if($package->customFeatures && $package->customFeatures->count() > 0)
                    <div class="text-xs border-t border-dashed border-gray-100 pt-3">
                        <span class="block text-gray-400 font-medium mb-1.5">Dynamic Fields:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($package->customFeatures as $feature)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $feature->field_label }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.package.edit', $package->id) }}" 
                       class="flex-1 text-center py-2 border border-gray-300 text-xs font-semibold rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" class="flex-1">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full text-center py-2 border border-red-200 text-xs font-semibold rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors" 
                                onclick="return confirm('Delete this package?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-sm text-gray-500">
                No active service plans configured.
            </div>
        @endforelse
    </div>

    <!-- Desktop Data Table View Layout (Fixed sizing structure for PC view) -->
    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="w-[25%] px-6 py-4 font-semibold text-gray-700 uppercase tracking-wider text-xs">Package Name</th>
                        <th scope="col" class="w-[15%] px-6 py-4 font-semibold text-gray-700 uppercase tracking-wider text-xs">Price</th>
                        <th scope="col" class="w-[15%] px-6 py-4 font-semibold text-gray-700 uppercase tracking-wider text-xs">Guest Limit</th>
                        <th scope="col" class="w-[15%] px-6 py-4 font-semibold text-gray-700 uppercase tracking-wider text-xs">Invite Limit</th>
                        <th scope="col" class="w-[20%] px-6 py-4 font-semibold text-gray-700 uppercase tracking-wider text-xs">Dynamic Custom Fields</th>
                        <th scope="col" class="w-[10%] px-6 py-4 font-semibold text-gray-700 uppercase tracking-wider text-xs text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($packages as $package)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <!-- Package Name -->
                            <td class="px-6 py-4 truncate">
                                <span class="font-bold text-gray-900 block truncate">{{ $package->package_name }}</span>
                                <span class="text-[11px] text-gray-400 font-medium">Expires: {{ $package->validity ? \Carbon\Carbon::parse($package->validity)->format('Y-m-d') : 'N/A' }}</span>
                            </td>
                            
                            <!-- Fixed Desktop Custom Price Representation String -->
                            <td class="px-6 py-4 text-gray-900 font-bold truncate">
                                ₹{{ $package->price }}
                            </td>
                            
                            <!-- Guest Limit -->
                            <td class="px-6 py-4 text-gray-600 font-medium truncate">
                                {{ $package->guest_limit }} Count
                            </td>
                            
                            <!-- Invite Limit -->
                            <td class="px-6 py-4 text-gray-600 font-medium truncate">
                                {{ $package->invite_limit }} Limit
                            </td>

                            <!-- Dynamic Custom Fields Badges -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-full">
                                    @if($package->customFeatures && $package->customFeatures->count() > 0)
                                        @foreach($package->customFeatures as $feature)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200 truncate" title="{{ $feature->field_label }}: {{ $feature->field_value }}">
                                                {{ $feature->field_label }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-gray-400 italic">None</span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 text-sm text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.package.edit', $package->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-semibold rounded text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors duration-150">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" class="inline-block">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-red-200 text-xs font-semibold rounded text-red-700 bg-white hover:bg-red-50 shadow-sm transition-colors duration-150" 
                                            onclick="return confirm('Delete this package?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 italic">
                                No service packages found. Click "Create New Package" to construct one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection