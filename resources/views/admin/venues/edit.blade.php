@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb Header -->
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route('admin.venue.index') }}" class="hover:text-blue-600 font-medium transition-colors">Venues</a>
        <i class="bi bi-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Edit Venue</span>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Edit Venue Configuration</h2>
                <p class="text-xs text-gray-500 mt-0.5">Saving updates will apply changes globally to all accounts viewing this resource.</p>
            </div>
            <div>
                @if(is_null($venue->host_id))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <i class="bi bi-globe mr-1"></i> Global Scope
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                        <i class="bi bi-person-badge mr-1"></i> Host Assignment
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.venue.update', $venue->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Venue Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Venue Name <span class="text-red-500">*</span></label>
                    <input type="text" name="venue_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('venue_name', $venue->venue_name) }}" required>
                </div>

                <!-- Pincode & Area -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" name="pincode" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('pincode', $venue->pincode) }}" required maxlength="6">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Area Name <span class="text-red-500">*</span></label>
                        <input type="text" name="area_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('area_name', $venue->area_name) }}" required>
                    </div>
                </div>

                <!-- District, State & Circle -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">District <span class="text-red-500">*</span></label>
                        <input type="text" name="district" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('district', $venue->district) }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">State <span class="text-red-500">*</span></label>
                        <input type="text" name="state" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('state', $venue->state) }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Circle <span class="text-red-500">*</span></label>
                        <input type="text" name="circle" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('circle', $venue->circle) }}" required>
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Country <span class="text-red-500">*</span></label>
                    <input type="text" name="country" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-gray-50 text-gray-600 focus:outline-none" value="{{ old('country', $venue->country) }}" required>
                </div>

                <!-- Venue Address -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Full Venue Address <span class="text-red-500">*</span></label>
                    <textarea name="venue_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" required>{{ old('venue_address', $venue->venue_address) }}</textarea>
                </div>

                <!-- Wedding Location Details -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Wedding Location Details <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                    <input type="text" name="wedding_location" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('wedding_location', $venue->wedding_location) }}">
                </div>

                <!-- Map Embed URL -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Location Map Link / Embed URL <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                    <input type="text" name="location_map" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('location_map', $venue->location_map) }}">
                </div>

                <!-- Form Controls Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.venue.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                        Back to List
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm cursor-pointer shadow-blue-100">
                        Update Venue Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection