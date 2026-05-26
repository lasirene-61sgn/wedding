@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb Header -->
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route('admin.venue.index') }}" class="hover:text-blue-600 font-medium transition-colors">Venues</a>
        <i class="bi bi-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Create Global Venue</span>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Create Global Venue</h2>
            <p class="text-xs text-gray-500 mt-0.5">Venues created here have no host assignment and will be accessible by all application hosts.</p>
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

            <form action="{{ route('admin.venue.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Venue Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Venue Name <span class="text-red-500">*</span></label>
                    <input type="text" name="venue_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('venue_name') }}" required placeholder="Enter primary name of the venue">
                </div>

                <!-- Pincode & Area -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" name="pincode" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('pincode') }}" required maxlength="6" placeholder="6-digit pincode">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Area Name <span class="text-red-500">*</span></label>
                        <input type="text" name="area_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('area_name') }}" required placeholder="Locality or sector name">
                    </div>
                </div>

                <!-- District, State & Circle -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">District <span class="text-red-500">*</span></label>
                        <input type="text" name="district" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('district') }}" required placeholder="District">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">State <span class="text-red-500">*</span></label>
                        <input type="text" name="state" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('state') }}" required placeholder="State">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Circle <span class="text-red-500">*</span></label>
                        <input type="text" name="circle" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('circle') }}" required placeholder="Region/Circle">
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Country <span class="text-red-500">*</span></label>
                    <input type="text" name="country" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-600 focus:outline-none" value="{{ old('country', 'India') }}" required readonly>
                </div>

                <!-- Venue Address -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Full Venue Address <span class="text-red-500">*</span></label>
                    <textarea name="venue_address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" required placeholder="Street numbers, building details, landmarks...">{{ old('venue_address') }}</textarea>
                </div>

                <!-- Wedding Location Details -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Wedding Location Details <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                    <input type="text" name="wedding_location" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('wedding_location') }}" placeholder="e.g., Crystal Ball Room, Poolside Lawn">
                </div>

                <!-- Map Embed URL -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Location Map Link / Embed URL <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                    <input type="text" name="location_map" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" value="{{ old('location_map') }}" placeholder="Google Maps configuration source link string">
                </div>

                <!-- Form Controls Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.venue.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm cursor-pointer shadow-blue-100">
                        Save Global Venue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection