@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 mt-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 flex items-center justify-between">
            <h5 class="text-lg font-semibold text-white tracking-tight">Edit Category</h5>
            <span class="text-xs px-2.5 py-1 bg-white/20 text-white rounded-full font-medium">Category #{{ $category->id }}</span>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            @if(session('error'))
                <div class="mb-5 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-lg">
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-lg">
                    <ul class="list-disc list-inside text-sm space-y-1 font-medium pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="editCategoryForm" action="{{ route('admin.categoryvenue.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Category Name -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="category_name" 
                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 text-sm py-2.5 px-3.5" 
                           value="{{ old('category_name', $category->category_name) }}" required>
                </div>

                <!-- Sub Categories, Ceremonies & Templates -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-semibold text-slate-700">Sub Categories, Ceremonies & Templates</label>
                        <button type="button" onclick="addSubCategoryBlock()" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:underline">
                            <span class="text-sm font-bold">+</span> Add Sub Category
                        </button>
                    </div>
                    
                    <datalist id="existingSubCats">
                        @foreach($allSubCategories as $subCat)
                            <option value="{{ $subCat }}">
                        @endforeach
                    </datalist>

                    <div id="sub-categories-wrapper" class="space-y-5">
                        <!-- Dynamic Subcategory Blocks Injected via JS -->
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.categoryvenue.index') }}" class="px-4 py-2.5 bg-white text-slate-600 font-semibold rounded-lg border border-slate-300 hover:bg-slate-50 text-sm">
                        Cancel
                    </a>
                    <button type="submit" id="submitEditCategoryBtn" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-sm">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= RESPONSIVE PREVIEW MODAL ================= -->
<div id="previewModal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-xs flex items-center justify-center p-2 sm:p-4 hidden">
    <div class="bg-white rounded-xl shadow-2xl flex flex-col w-full h-[95vh] max-w-7xl overflow-hidden border border-slate-700">
        <div class="px-4 py-3 bg-slate-900 text-white flex items-center justify-between gap-3 border-b border-slate-800">
            <h6 id="previewFileName" class="text-sm font-semibold truncate max-w-md">Template Preview</h6>

            <div class="flex items-center bg-slate-800 p-1 rounded-lg border border-slate-700 text-xs">
                <button type="button" onclick="setDeviceView('desktop')" id="btn-desktop" class="device-tab-btn px-3 py-1.5 rounded-md font-medium bg-indigo-600 text-white">Desktop</button>
                <button type="button" onclick="setDeviceView('tablet')" id="btn-tablet" class="device-tab-btn px-3 py-1.5 rounded-md font-medium text-slate-300">Tablet</button>
                <button type="button" onclick="setDeviceView('mobile')" id="btn-mobile" class="device-tab-btn px-3 py-1.5 rounded-md font-medium text-slate-300">Mobile</button>
            </div>

            <button type="button" onclick="closeResponsivePreview()" class="text-slate-400 hover:text-white text-xl font-bold">&times;</button>
        </div>
        <div class="flex-1 bg-slate-950/20 p-4 flex items-center justify-center overflow-auto">
            <div id="previewFrameContainer" class="w-full h-full transition-all flex items-center justify-center">
                <iframe id="previewIframe" src="" class="w-full h-full bg-white rounded-lg shadow-lg border border-slate-300"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    let subCategoryIndex = 0;

    function addSubCategoryBlock(name = '', ceremonies = [], htmlFiles = []) {
        const wrapper = document.getElementById('sub-categories-wrapper');
        const block = document.createElement('div');
        block.className = 'subcategory-block p-5 border border-slate-200 rounded-xl bg-slate-50 relative space-y-4';
        
        // 1. Render Ceremonies
        let ceremoniesHtml = '';
        if (ceremonies.length > 0) {
            ceremonies.forEach(c => {
                ceremoniesHtml += `
                    <div class="ceremony-item flex items-center gap-2 mt-2">
                        <input type="text" name="sub_categories[${subCategoryIndex}][ceremonies][]" value="${c}" class="flex-1 rounded-lg border-slate-300 shadow-sm text-sm py-1.5 px-3" placeholder="Ceremony (e.g. Sangeet)">
                        <button type="button" onclick="this.closest('.ceremony-item').remove()" class="text-rose-500 hover:text-rose-700 font-bold px-1 text-lg" title="Delete Ceremony">&times;</button>
                    </div>`;
            });
        }

        // 2. Render Existing HTML Files
        let existingFilesHtml = '';
        if (htmlFiles.length > 0) {
            existingFilesHtml += `<div class="uploaded-templates-list mt-2 space-y-2">`;
            htmlFiles.forEach(file => {
                const fileName = file.split('/').pop();
                const fileUrl = "{{ asset('') }}" + file;
                existingFilesHtml += `
                    <div class="template-item flex items-center justify-between bg-white px-3 py-2 border border-slate-200 rounded-lg text-xs">
                        <input type="hidden" name="sub_categories[${subCategoryIndex}][existing_files][]" value="${file}">
                        <span class="font-medium text-slate-700 truncate max-w-xs">${fileName}</span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openResponsivePreview('${fileUrl}', '${fileName}')" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded border border-indigo-200 hover:bg-indigo-100">Preview</button>
                            <button type="button" onclick="this.closest('.template-item').remove()" class="px-2 py-1 bg-rose-50 text-rose-600 rounded border border-rose-200 hover:bg-rose-100 font-semibold" title="Delete Template">Delete</button>
                        </div>
                    </div>`;
            });
            existingFilesHtml += `</div>`;
        }

        block.innerHTML = `
            <!-- Delete Entire Subcategory Button -->
            <button type="button" onclick="this.closest('.subcategory-block').remove()" class="absolute top-3 right-3 text-slate-400 hover:text-rose-600 text-xl font-bold" title="Delete Subcategory">&times;</button>
            
            <!-- Subcategory Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Subcategory Name</label>
                <input type="text" list="existingSubCats" name="sub_categories[${subCategoryIndex}][name]" value="${name}" class="w-full rounded-lg border-slate-300 text-sm py-2 px-3" placeholder="e.g. Telugu Hindu, Tamil" required>
            </div>

            <!-- Ceremonies List -->
            <div class="pt-2 border-t border-slate-200/60">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-xs font-semibold text-slate-700">Ceremonies</label>
                    <button type="button" onclick="addNestedCeremony(this, ${subCategoryIndex})" class="text-xs font-semibold text-indigo-600 hover:underline">+ Add Ceremony</button>
                </div>
                <div class="ceremonies-list space-y-1">${ceremoniesHtml}</div>
            </div>

            <!-- HTML Templates List -->
            <div class="pt-2 border-t border-slate-200/60">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Subcategory HTML Templates</label>
                ${existingFilesHtml}
                <div class="mt-2">
                    <input type="file" name="sub_categories[${subCategoryIndex}][html_files][]" accept=".html" multiple class="w-full rounded-lg border-slate-300 text-xs py-1.5 px-2.5 bg-white">
                </div>
            </div>
        `;

        wrapper.appendChild(block);
        subCategoryIndex++;
    }

    function addNestedCeremony(btn, idx) {
        const list = btn.closest('.pt-2').querySelector('.ceremonies-list');
        const div = document.createElement('div');
        div.className = 'ceremony-item flex items-center gap-2 mt-2';
        div.innerHTML = `
            <input type="text" name="sub_categories[${idx}][ceremonies][]" class="flex-1 rounded-lg border-slate-300 text-sm py-1.5 px-3" placeholder="Ceremony Name">
            <button type="button" onclick="this.closest('.ceremony-item').remove()" class="text-rose-500 hover:text-rose-700 font-bold px-1 text-lg" title="Delete Ceremony">&times;</button>
        `;
        list.appendChild(div);
    }

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
        buttons.forEach(b => { b.classList.remove('bg-indigo-600', 'text-white'); b.classList.add('text-slate-300'); });
        const activeBtn = document.getElementById('btn-' + device);
        activeBtn.classList.add('bg-indigo-600', 'text-white');
        activeBtn.classList.remove('text-slate-300');

        if (device === 'desktop') { container.style.maxWidth = '100%'; container.style.height = '100%'; }
        else if (device === 'tablet') { container.style.maxWidth = '768px'; container.style.height = '100%'; }
        else if (device === 'mobile') { container.style.maxWidth = '375px'; container.style.height = '667px'; }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const existingData = @json(old('sub_categories', is_array($category->sub_categories) ? $category->sub_categories : []));
        if (existingData && existingData.length > 0) {
            existingData.forEach(sub => {
                addSubCategoryBlock(sub.name || '', sub.ceremonies || [], sub.html_files || []);
            });
        } else {
            addSubCategoryBlock();
        }
    });
</script>
@endsection