@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 bg-indigo-600">
            <h5 class="text-lg font-semibold text-white">Create New Ceremony</h5>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('admin.ceramony.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Dropdowns Row (Host, Category, Venue) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Select Host -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Host</label>
                        <select name="host_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">-- Choose Host --</option>
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}">{{ $host->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ceremony Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ceremony Category</label>
                        <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Venue -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Venue</label>
                        <select name="venue_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">-- Choose Venue (Optional) --</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->venue_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Info Row (Name, Date, Time) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Ceremony Name -->
                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ceremony Name</label>
                        <input type="text" name="ceramony_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. Sangeet Celebration">
                    </div>
                    
                    <!-- Date -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="ceramony_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    
                    <!-- Time -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                        <input type="time" name="ceramony_time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>

                <!-- Banner Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ceremony Banner/Image</label>
                    <input type="file" name="ceramony_image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <!-- Form Actions -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Ceremony
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection