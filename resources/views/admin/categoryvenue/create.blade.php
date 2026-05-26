@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 mt-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 bg-emerald-600">
            <h5 class="text-lg font-semibold text-white">Add New Category</h5>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('admin.categoryvenue.store') }}" method="POST">
                @csrf
                
                <!-- Category Input Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                    <input type="text" name="category_name" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400 @error('category_name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                           placeholder="e.g. Wedding" 
                           value="{{ old('category_name') }}">
                    
                    @error('category_name') 
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Form Controls -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <a href="{{ route('admin.categoryvenue.index') }}" 
                       class="px-5 py-2 bg-white text-gray-700 font-medium rounded-md border border-gray-300 hover:bg-gray-50 text-sm transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1">
                        Back
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-md shadow-sm hover:bg-emerald-700 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection