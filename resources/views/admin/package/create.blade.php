@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <!-- Main Card Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h4 class="text-xl font-bold text-gray-800 tracking-tight">Create Package</h4>
            <a href="{{ route('admin.package.index') }}" 
               class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 border border-transparent rounded-md text-xs font-semibold text-white shadow-sm transition-colors">
                Back
            </a>
        </div>
        
        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.package.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Package Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Package Name</label>
                    <input type="text" name="package_name" value="{{ old('package_name') }}" placeholder="e.g. Gold Plan" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                    @error('package_name') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Package Description (Message Service)</label>
                    <textarea name="package_description" rows="3" placeholder="Enter package details..."
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>{{ old('package_description') }}</textarea>
                    @error('package_description') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- VAF -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">VAF</label>
                    <textarea name="vaf" rows="2" placeholder="Enter VAF information..."
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>{{ old('vaf') }}</textarea>
                    @error('vaf') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Metrics Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Price -->
                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('price') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Guest Limit -->
                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Guest Limit</label>
                        <input type="number" name="guest_limit" value="{{ old('guest_limit') }}" placeholder="e.g. 100"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('guest_limit') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Invite Limit -->
                    <div class="md:col-span-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Invite Limit</label>
                        <input type="number" name="invite_limit" value="{{ old('invite_limit') }}" placeholder="e.g. 5"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('invite_limit') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Validity Date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Validity</label>
                    <input type="date" name="validity" value="{{ old('validity') }}" 
                           class="block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                    @error('validity') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <hr class="border-gray-200 my-5">

                <!-- String Details Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Invitation -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Invitation</label>
                        <input type="text" name="invitaion" value="{{ old('invitaion') }}" placeholder="Enter invitation info"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('invitaion') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- RSVP -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">RSVP</label>
                        <input type="text" name="rsvp" value="{{ old('rsvp') }}" placeholder="Enter RSVP info"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('rsvp') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ceramonies -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Ceramonies</label>
                        <input type="text" name="ceramonies" value="{{ old('ceramonies') }}" placeholder="Enter ceremonies info"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('ceramonies') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Reports -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Reports</label>
                        <input type="text" name="reports" value="{{ old('reports') }}" placeholder="Enter reports info"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('reports') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Gallery -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Gallery</label>
                        <input type="text" name="gallery" value="{{ old('gallery') }}" placeholder="Enter gallery info"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        @error('gallery') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Wishboard (Nullable) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Wishboard <span class="text-xs text-gray-400 font-normal">(Optional)</span></label>
                        <input type="text" name="wishboard" value="{{ old('wishboard') }}" placeholder="Enter wishboard info"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
                        @error('wishboard') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- DCG QR Code (Nullable) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">DCG QR Code <span class="text-xs text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="dcgqrcode" value="{{ old('dcgqrcode') }}" placeholder="Enter QR code info"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
                    @error('dcgqrcode') <span class="text-xs text-red-600 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Submit Action -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        Save Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection