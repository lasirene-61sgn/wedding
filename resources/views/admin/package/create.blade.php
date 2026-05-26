@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
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
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Description</label>
                    <textarea name="package_description" rows="3" 
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">{{ old('package_description') }}</textarea>
                </div>
                
                <!-- Row with Price and Limits -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Price Column -->
                    <div class="md:col-span-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                    </div>
                    
                    <!-- Guest Limit Column -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Guest Limit</label>
                        <input type="number" name="guest_limit" value="{{ old('guest_limit') }}" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                    </div>
                    
                    <!-- Invite Limit Column -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Invite Limit</label>
                        <input type="number" name="invite_limit" value="{{ old('invite_limit') }}" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                    </div>
                </div>
                
                <!-- Submit Action Panel -->
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