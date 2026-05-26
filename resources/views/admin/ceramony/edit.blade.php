@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 bg-sky-600">
            <h5 class="text-lg font-semibold text-white">Edit Ceremony: {{ $ceramony->ceramony_name }}</h5>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('admin.ceramony.update', $ceramony->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Relationship Dropdowns -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Host Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <select name="host_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm" required>
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}" {{ $ceramony->host_id == $host->id ? 'selected' : '' }}>
                                    {{ $host->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $ceramony->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Venue Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                        <select name="venue_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm">
                            <option value="">-- No Venue --</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ $ceramony->venue_id == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->venue_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Text Info & Datetime Row -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Ceremony Name -->
                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ceremony Name</label>
                        <input type="text" name="ceramony_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm" value="{{ $ceramony->ceramony_name }}">
                    </div>
                    
                    <!-- Date -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="ceramony_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm" value="{{ $ceramony->ceramony_date }}">
                    </div>
                    
                    <!-- Time -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                        <input type="time" name="ceramony_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm" value="{{ $ceramony->ceramony_time }}">
                    </div>
                </div>

                <!-- Media Handling Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Ceremony Image</label>
                        @if($ceramony->ceramony_image)
                            <img src="{{ asset('storage/' . $ceramony->ceramony_image) }}" alt="Current ceremony image" class="h-36 w-auto object-cover rounded-md border border-gray-300 shadow-sm bg-white p-1">
                        @else
                            <p class="text-xs text-gray-400 italic">No image uploaded.</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload New Image <span class="text-xs font-normal text-gray-400">(Leave blank to keep current)</span></label>
                        <input type="file" name="ceramony_image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 bg-white">
                    </div>
                </div>

                <!-- Form Footer Controls -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('admin.ceramony.index') }}" 
                       class="px-5 py-2 bg-white text-gray-700 font-medium rounded-md border border-gray-300 hover:bg-gray-50 text-sm transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1">
                        Back to List
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-sky-600 text-white font-semibold rounded-md shadow-sm hover:bg-sky-700 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        Update Ceremony
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection