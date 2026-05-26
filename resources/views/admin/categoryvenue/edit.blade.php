@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 mt-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 bg-amber-500">
            <h5 class="text-lg font-semibold text-gray-900">Edit Category</h5>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('admin.categoryvenue.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Category Input Section -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Category Name</label>
                    <input type="text" name="category_name" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('category_name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                           value="{{ old('category_name', $category->category_name) }}">
                    
                    @error('category_name') 
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Form Controls -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <a href="{{ route('admin.categoryvenue.index') }}" 
                       class="px-5 py-2 bg-white text-gray-700 font-medium rounded-md border border-gray-300 hover:bg-gray-50 text-sm transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-amber-500 text-gray-900 font-semibold rounded-md shadow-sm hover:bg-amber-600 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection