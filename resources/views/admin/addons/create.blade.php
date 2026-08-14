@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 max-w-xl antialiased">
    <div class="mb-6">
        <a href="{{ route('admin.addons.index') }}" class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
            <i class="bi bi-arrow-left"></i> Back to Add-ons
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Create Channel Add-on</h2>
        <p class="text-xs text-gray-500 mt-0.5">Define a credit pack for hosts to purchase.</p>
    </div>

    {{-- Error Banner --}}
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
            <ul class="list-disc pl-4 space-y-1 font-medium">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Banner --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form id="addonForm" action="{{ route('admin.addons.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Add-on Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. 50 WhatsApp Credits"
                    class="w-full px-4 py-2.5 border @error('name') border-red-400 bg-red-50/20 @else border-gray-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" required>
                @error('name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Type -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Channel Type</label>
                <select name="type" class="w-full px-4 py-2.5 border @error('type') border-red-400 bg-red-50/20 @else border-gray-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" required>
                    <option value="">-- Select Channel --</option>
                    <option value="whatsapp" {{ old('type') == 'whatsapp' ? 'selected' : '' }}>📲 WhatsApp</option>
                    <option value="sms"      {{ old('type') == 'sms'      ? 'selected' : '' }}>💬 SMS</option>
                    <option value="email"    {{ old('type') == 'email'    ? 'selected' : '' }}>✉️ Email</option>
                </select>
                @error('type') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Count -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Credits (Count)</label>
                <input type="number" name="count" value="{{ old('count') }}" min="1" placeholder="e.g. 50"
                    class="w-full px-4 py-2.5 border @error('count') border-red-400 bg-red-50/20 @else border-gray-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" required>
                <p class="text-xs text-gray-400 mt-1">Number of messages the host can send after purchasing.</p>
                @error('count') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Price (₹ INR)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="1" placeholder="e.g. 99"
                    class="w-full px-4 py-2.5 border @error('price') border-red-400 bg-red-50/20 @else border-gray-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" required>
                @error('price') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Active -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', '1') ? 'checked' : '' }}
                    class="w-4 h-4 accent-indigo-600 cursor-pointer">
                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Active (visible to hosts)</label>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    id="submitAddonBtn"
                    class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed text-white font-semibold rounded-lg text-sm transition-colors flex items-center justify-center gap-2">
                    
                    {{-- Spinner (hidden by default) --}}
                    <svg id="loadingSpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>

                    <span id="btnText">Create Add-on</span>
                </button>

                <a href="{{ route('admin.addons.index') }}"
                    class="flex-1 py-2.5 border border-gray-300 text-gray-600 font-semibold rounded-lg text-sm text-center hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle loading state on form submit
    document.getElementById('addonForm').addEventListener('submit', function () {
        const submitBtn = document.getElementById('submitAddonBtn');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('loadingSpinner');

        submitBtn.disabled = true;
        btnText.textContent = 'Creating Add-on...';
        spinner.classList.remove('hidden');
    });
</script>
@endsection