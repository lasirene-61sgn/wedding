@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Ceremony Categories & Templates</h2>
            <p class="text-xs text-gray-500 mt-1">Manage event categories, nested subcategories, ceremonies, and responsive HTML templates.</p>
        </div>
        <a href="{{ route('admin.categoryvenue.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Category
        </a>
    </div>

    <!-- Success Flash Notification -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md shadow-sm flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">ID</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/4">Category</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subcategories, Ceremonies & Templates</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/70 transition-colors align-top">
                            <!-- ID -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                #{{ $category->id }}
                            </td>

                            <!-- Category Name -->
                            <td class="px-6 py-4 text-sm text-gray-900 font-semibold">
                                {{ $category->category_name }}
                            </td>

                            <!-- Subcategories & Nested Details -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if(is_array($category->sub_categories) && count($category->sub_categories) > 0)
                                    <div class="space-y-3">
                                        @foreach($category->sub_categories as $sub)
                                            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-lg text-xs space-y-2">
                                                <!-- Subcategory Header -->
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-slate-800 text-sm">{{ $sub['name'] ?? 'Unnamed Subcategory' }}</span>
                                                </div>

                                                <!-- Ceremonies -->
                                                @if(isset($sub['ceremonies']) && is_array($sub['ceremonies']) && count($sub['ceremonies']) > 0)
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        <span class="text-gray-500 font-medium">Ceremonies:</span>
                                                        @foreach($sub['ceremonies'] as $ceremony)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                                {{ $ceremony }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Templates for this Subcategory -->
                                                @if(isset($sub['html_files']) && is_array($sub['html_files']) && count($sub['html_files']) > 0)
                                                    <div class="flex items-center gap-2 flex-wrap pt-1 border-t border-slate-200/60">
                                                        <span class="text-gray-500 font-medium">Templates:</span>
                                                        @foreach($sub['html_files'] as $file)
                                                            <button type="button" 
                                                                    onclick="openResponsivePreview('{{ asset($file) }}', '{{ basename($file) }}')" 
                                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition-colors">
                                                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                <span class="truncate max-w-[150px]" title="{{ basename($file) }}">{{ basename($file) }}</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-xs">No subcategories defined</span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-right space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.categoryvenue.edit', $category->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 border border-amber-200 rounded-md text-xs font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                                    Edit
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('admin.categoryvenue.destroy', $category->id) }}" 
                                      method="POST" 
                                      class="inline-block" 
                                      onsubmit="return confirm('Are you sure you want to delete this category and all its templates?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2.5 py-1.5 bg-red-50 border border-red-200 rounded-md text-xs font-semibold text-red-700 hover:bg-red-100 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400 italic">
                                No ceremony categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= RESPONSIVE PREVIEW MODAL ================= -->
<div id="previewModal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-xs flex items-center justify-center p-2 sm:p-4 hidden">
    <div class="bg-white rounded-xl shadow-2xl flex flex-col w-full h-[95vh] max-w-7xl overflow-hidden border border-slate-700">
        
        <!-- Modal Header -->
        <div class="px-4 py-3 bg-slate-900 text-white flex flex-wrap items-center justify-between gap-3 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                <h6 id="previewFileName" class="text-xs sm:text-sm font-semibold truncate max-w-xs sm:max-w-md">Template Preview</h6>
            </div>

            <!-- Viewport Switcher -->
            <div class="flex items-center bg-slate-800 p-1 rounded-lg border border-slate-700 text-xs">
                <!-- Desktop -->
                <button type="button" onclick="setDeviceView('desktop')" id="btn-desktop" 
                        class="device-tab-btn px-3 py-1.5 rounded-md font-medium text-slate-300 hover:text-white flex items-center gap-1.5 transition-all bg-indigo-600 text-white shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Desktop</span>
                </button>

                <!-- Tablet -->
                <button type="button" onclick="setDeviceView('tablet')" id="btn-tablet" 
                        class="device-tab-btn px-3 py-1.5 rounded-md font-medium text-slate-300 hover:text-white flex items-center gap-1.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Tablet</span>
                </button>

                <!-- Mobile -->
                <button type="button" onclick="setDeviceView('mobile')" id="btn-mobile" 
                        class="device-tab-btn px-3 py-1.5 rounded-md font-medium text-slate-300 hover:text-white flex items-center gap-1.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Mobile</span>
                </button>
            </div>

            <!-- Close Modal -->
            <button type="button" onclick="closeResponsivePreview()" class="text-slate-400 hover:text-white text-xl font-bold p-1 leading-none">&times;</button>
        </div>

        <!-- Preview Viewport -->
        <div class="flex-1 bg-slate-950/20 p-2 sm:p-4 flex items-center justify-center overflow-auto">
            <div id="previewFrameContainer" class="w-full h-full transition-all duration-300 flex items-center justify-center">
                <iframe id="previewIframe" src="" class="w-full h-full bg-white rounded-lg shadow-lg border border-slate-300"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function openResponsivePreview(url, fileName) {
        document.getElementById('previewFileName').textContent = fileName;
        document.getElementById('previewIframe').src = url;
        document.getElementById('previewModal').classList.remove('hidden');
        setDeviceView('desktop');
    }

    function closeResponsivePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.getElementById('previewIframe').src = '';
    }

    function setDeviceView(device) {
        const container = document.getElementById('previewFrameContainer');
        const buttons = document.querySelectorAll('.device-tab-btn');
        
        buttons.forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-xs');
            btn.classList.add('text-slate-300');
        });

        const activeBtn = document.getElementById('btn-' + device);
        activeBtn.classList.add('bg-indigo-600', 'text-white', 'shadow-xs');
        activeBtn.classList.remove('text-slate-300');

        if (device === 'desktop') {
            container.style.maxWidth = '100%';
            container.style.height = '100%';
        } else if (device === 'tablet') {
            container.style.maxWidth = '768px';
            container.style.height = '100%';
        } else if (device === 'mobile') {
            container.style.maxWidth = '375px';
            container.style.height = '667px';
        }
    }
</script>
@endsection