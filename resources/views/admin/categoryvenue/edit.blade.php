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

                <!-- Ceremonies Input Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-gray-700">Default Ceremonies</label>
                        <button type="button" onclick="addCeremonyField()" class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                            + Add Ceremony
                        </button>
                    </div>
                    <div id="ceremonies-container" class="space-y-3">
                        @php
                            $ceremonies = old('ceremonies', is_array($category->ceremonies) ? $category->ceremonies : []);
                        @endphp
                        @forelse($ceremonies as $index => $ceremony)
                            <div class="flex items-center gap-2">
                                <input type="text" name="ceremonies[]" value="{{ $ceremony }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="e.g. Reception">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
                            </div>
                        @empty
                            <div class="flex items-center gap-2">
                                <input type="text" name="ceremonies[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="e.g. Reception">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
                            </div>
                        @endforelse
                    </div>
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
<script>
    function addCeremonyField() {
        const container = document.getElementById('ceremonies-container');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" name="ceremonies[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="e.g. Sangeet">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
        `;
        container.appendChild(div);
    }
</script>
@endsection