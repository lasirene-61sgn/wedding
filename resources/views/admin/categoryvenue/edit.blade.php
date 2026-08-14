@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 mt-8">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 flex items-center justify-between">
            <h5 class="text-lg font-semibold text-white tracking-tight">Edit Category</h5>
            <span class="text-xs px-2.5 py-1 bg-white/20 text-white rounded-full font-medium">Category #{{ $category->id }}</span>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            {{-- 1. Session Error Alert --}}
            @if(session('error'))
                <div class="mb-5 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-lg flex items-start gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- 2. Validation Errors List --}}
            @if($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-lg shadow-sm" role="alert">
                    <div class="flex items-center gap-2 mb-2 font-semibold text-sm">
                        <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span>Please correct the errors below:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1 font-medium pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 3. Success Alert --}}
            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg flex items-start gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form id="editCategoryForm" action="{{ route('admin.categoryvenue.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Category Input Section -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="category_name" 
                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder-slate-400 text-sm py-2.5 px-3.5 transition-all @error('category_name') border-rose-500 bg-rose-50/20 focus:border-rose-500 focus:ring-rose-500/20 @enderror" 
                           placeholder="e.g. Wedding, Birthday, Corporate" 
                           value="{{ old('category_name', $category->category_name) }}"
                           required>
                    
                    @error('category_name') 
                        <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                <!-- Ceremonies Input Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-slate-700">Default Ceremonies / Events</label>
                        <button type="button" onclick="addCeremonyField()" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                            <span class="text-sm font-bold">+</span> Add Ceremony
                        </button>
                    </div>
                    <div id="ceremonies-container" class="space-y-2.5">
                        @php
                            $ceremonies = old('ceremonies', is_array($category->ceremonies) ? $category->ceremonies : []);
                        @endphp
                        @forelse($ceremonies as $index => $ceremony)
                            <div class="flex items-center gap-2">
                                <input type="text" name="ceremonies[]" value="{{ $ceremony }}" class="flex-1 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder-slate-400 text-sm py-2 px-3 transition-all" placeholder="e.g. Reception">
                                <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Remove">&times;</button>
                            </div>
                        @empty
                            <div class="flex items-center gap-2">
                                <input type="text" name="ceremonies[]" class="flex-1 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder-slate-400 text-sm py-2 px-3 transition-all" placeholder="e.g. Reception">
                                <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Remove">&times;</button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.categoryvenue.index') }}" 
                       class="px-4 py-2.5 bg-white text-slate-600 font-semibold rounded-lg border border-slate-300 hover:bg-slate-50 text-sm transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            id="submitEditCategoryBtn"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-sm text-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        
                        <!-- Spinner Icon (hidden by default) -->
                        <svg id="editCategorySpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>

                        <span id="editCategoryBtnText">Update Category</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Dynamic Ceremony Field Addition
    function addCeremonyField() {
        const container = document.getElementById('ceremonies-container');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" name="ceremonies[]" class="flex-1 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder-slate-400 text-sm py-2 px-3 transition-all" placeholder="e.g. Sangeet">
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Remove">&times;</button>
        `;
        container.appendChild(div);
    }

    // 2. Submit Button Loading State
    document.getElementById('editCategoryForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitEditCategoryBtn');
        const btnText = document.getElementById('editCategoryBtnText');
        const spinner = document.getElementById('editCategorySpinner');

        submitBtn.disabled = true;
        btnText.textContent = 'Updating Category...';
        spinner.classList.remove('hidden');
    });
</script>
@endsection