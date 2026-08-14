@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">

    <!-- Breadcrumb Navigation -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('admin.ceramony.index') }}" class="hover:text-indigo-600 font-medium transition-colors">Ceremonies</a>
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-800 font-semibold">Create Ceremony</span>
    </div>

    <!-- Alert Notifications -->
    @if(session('error'))
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl shadow-sm" role="alert">
            <div class="flex items-center gap-2 mb-2 font-semibold text-sm">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>Please fix the following issues:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 font-medium pl-1 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Card Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-800 flex items-center justify-between text-white">
            <div>
                <h2 class="text-xl font-bold tracking-tight">Create New Ceremony</h2>
                <p class="text-xs text-indigo-100 mt-0.5">Configure ceremony schedules, venues, and hosts.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-white backdrop-blur-sm border border-white/20">
                New Event
            </span>
        </div>

        <!-- Form Body -->
        <div class="p-6 sm:p-8">
            <form id="createCeremonyForm" action="{{ route('admin.ceramony.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Host & Association -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">1. Event Association</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        
                        <!-- Select Host -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Select Host <span class="text-rose-500">*</span></label>
                            <select name="host_id" 
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3 transition @error('host_id') border-rose-400 bg-rose-50/20 @enderror" required>
                                <option value="">-- Choose Host --</option>
                                @foreach($hosts as $host)
                                    <option value="{{ $host->id }}" {{ old('host_id') == $host->id ? 'selected' : '' }}>
                                        {{ $host->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('host_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ceremony Category -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ceremony Category <span class="text-rose-500">*</span></label>
                            <select name="category_id" 
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3 transition @error('category_id') border-rose-400 bg-rose-50/20 @enderror" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Select Venue -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Select Venue <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <select name="venue_id" 
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3 transition @error('venue_id') border-rose-400 bg-rose-50/20 @enderror">
                                <option value="">-- Choose Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->venue_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Section 2: Details & Schedule -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">2. Ceremony Details & Timing</h3>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        
                        <!-- Ceremony Name -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ceremony Name <span class="text-rose-500">*</span></label>
                            <input type="text" 
                                   name="ceramony_name" 
                                   value="{{ old('ceramony_name') }}"
                                   placeholder="e.g. Sangeet Night, Haldi Ceremony"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3.5 transition @error('ceramony_name') border-rose-400 bg-rose-50/20 @enderror" 
                                   required>
                            @error('ceramony_name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Date -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ceremony Date <span class="text-rose-500">*</span></label>
                            <input type="date" 
                                   name="ceramony_date" 
                                   value="{{ old('ceramony_date') }}"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3.5 transition @error('ceramony_date') border-rose-400 bg-rose-50/20 @enderror"
                                   required>
                            @error('ceramony_date') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Time -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ceremony Time <span class="text-rose-500">*</span></label>
                            <input type="time" 
                                   name="ceramony_time" 
                                   value="{{ old('ceramony_time') }}"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3.5 transition @error('ceramony_time') border-rose-400 bg-rose-50/20 @enderror"
                                   required>
                            @error('ceramony_time') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Section 3: Media Upload -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">3. Ceremony Banner / Cover Photo</h3>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-indigo-400 transition-colors bg-slate-50/50">
                        <div class="space-y-2 text-center flex flex-col items-center">
                            <!-- Preview Image Element (Hidden Until Selected) -->
                            <img id="imagePreview" src="#" alt="Preview" class="hidden w-32 h-24 object-cover rounded-xl border border-slate-200 shadow-sm mb-2">

                            <svg id="uploadIcon" class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <div class="flex text-sm text-slate-600">
                                <label for="ceramony_image" class="relative cursor-pointer bg-white rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Upload a banner</span>
                                    <input id="ceramony_image" name="ceramony_image" type="file" accept="image/*" class="sr-only" onchange="previewCeremonyImage(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-slate-400">PNG, JPG, JPEG up to 5MB</p>
                        </div>
                    </div>
                    @error('ceramony_image') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Form Controls & Actions -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.ceramony.index') }}" 
                       class="px-5 py-2.5 bg-white text-slate-700 font-semibold rounded-xl border border-slate-300 hover:bg-slate-50 text-sm transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                        Cancel
                    </a>
                    
                    <button type="submit" 
                            id="submitCeremonyBtn"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed text-white font-semibold rounded-xl shadow-sm text-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        
                        <!-- Spinner Icon (hidden by default) -->
                        <svg id="ceremonySpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>

                        <span id="ceremonyBtnText">Create Ceremony</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Live Preview for Uploaded Image
    function previewCeremonyImage(input) {
        const preview = document.getElementById('imagePreview');
        const icon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. Submit Button Loading State
    document.getElementById('createCeremonyForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitCeremonyBtn');
        const btnText = document.getElementById('ceremonyBtnText');
        const spinner = document.getElementById('ceremonySpinner');

        submitBtn.disabled = true;
        btnText.textContent = 'Creating Ceremony...';
        spinner.classList.remove('hidden');
    });
</script>
@endsection