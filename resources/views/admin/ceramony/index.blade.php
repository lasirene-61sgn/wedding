@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">

    <!-- Top Action & Notification Banners -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl shadow-sm flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Ceremonies & Events</h2>
            <p class="text-xs text-slate-500 mt-1">Manage global and host-assigned wedding ceremonies.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.ceramony.backgrounds.index') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-300 rounded-xl font-semibold text-xs text-slate-700 shadow-sm hover:bg-slate-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-slate-300 transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Manage Backgrounds
            </a>
            <a href="{{ route('admin.ceramony.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white shadow-sm hover:bg-indigo-700 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Ceremony
            </a>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Image</th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Ceremony Details</th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Host</th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Venue</th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Schedule</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($ceramonies as $ceramony)
                        <tr class="hover:bg-indigo-50/20 transition-colors">
                            <!-- Image Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!empty($ceramony->ceramony_image))
                                    <img src="{{ asset('storage/' . $ceramony->ceramony_image) }}" 
                                         alt="{{ $ceramony->ceramony_name }}" 
                                         class="w-14 h-14 object-cover rounded-xl border border-slate-200 shadow-sm">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Ceremony Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-900">{{ $ceramony->ceramony_name }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">ID: #{{ $ceramony->id }}</div>
                            </td>
                            
                            <!-- Host -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-sm text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $ceramony->host->name ?? 'Global (No Host)' }}
                                </div>
                            </td>
                            
                            <!-- Category Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $ceramony->category->category_name ?? 'Unassigned' }}
                                </span>
                            </td>
                            
                            <!-- Venue -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $ceramony->venue->venue_name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            
                            <!-- Schedule / Date / Time -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-800">{{ $ceramony->ceramony_date }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $ceramony->ceramony_time }}</div>
                            </td>
                            
                            <!-- Action Buttons -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.ceramony.edit', $ceramony->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors">
                                        Edit
                                    </a>
                                    
                                    <!-- Delete Form with Dynamic Spinner -->
                                    <form action="{{ route('admin.ceramony.destroy', $ceramony->id) }}" 
                                          method="POST" 
                                          class="delete-ceremony-form inline-block"
                                          onsubmit="return handleCeremonyDelete(this);">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="delete-btn inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 border border-rose-200 rounded-lg text-xs font-semibold text-rose-700 hover:bg-rose-100 disabled:opacity-75 disabled:cursor-not-allowed transition-colors">
                                            <svg class="delete-spinner animate-spin h-3.5 w-3.5 text-rose-700 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                            </svg>
                                            <span class="delete-text">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-semibold text-slate-800">No ceremonies found</h4>
                                    <p class="text-xs text-slate-500 mt-1 mb-4">Start by adding your first ceremony or event configuration.</p>
                                    <a href="{{ route('admin.ceramony.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-colors">
                                        + Add New Ceremony
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function handleCeremonyDelete(form) {
        if (!confirm('Are you sure you want to delete this ceremony? This action cannot be undone.')) {
            return false;
        }

        const button = form.querySelector('.delete-btn');
        const text = form.querySelector('.delete-text');
        const spinner = form.querySelector('.delete-spinner');

        button.disabled = true;
        text.textContent = 'Deleting...';
        spinner.classList.remove('hidden');

        return true;
    }
</script>
@endsection