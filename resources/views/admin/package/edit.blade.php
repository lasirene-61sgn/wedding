@extends('layouts.admin')

@section('content')
<div class="w-full">

    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Package: {{ $package->package_name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Modify hosting or subscription package details.</p>
        </div>
        <a href="{{ route('admin.package.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-xl transition-colors shadow-xs">
            <i class="bi bi-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl w-full">
            <div class="flex items-center text-red-700 font-semibold mb-2">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i> Please fix the errors below:
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Package Edit Form Card (Full Width Span) -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-xs p-6 md:p-8 w-full">
        <form action="{{ route('admin.package.update', $package->id) }}" method="POST" class="space-y-6 w-full">
            @csrf
            @method('PUT')

            <!-- Main Form Grid (2 Columns on Medium+ screens, 1 Column on Mobile) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                <!-- Plan Name -->
                <div>
                    <label for="package_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Plan Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="package_name" id="package_name" value="{{ old('package_name', $package->package_name) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('package_name') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="price" id="price" value="{{ old('price', $package->price) }}" placeholder="e.g. 1500+GST"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('price') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Guest Count -->
                <div>
                    <label for="guest_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        Guest Count <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="guest_limit" id="guest_limit" value="{{ old('guest_limit', $package->guest_limit) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('guest_limit') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Validity -->
                <div>
                    <label for="validity" class="block text-sm font-semibold text-gray-700 mb-2">
                        Validity <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="validity" id="validity" value="{{ old('validity', $package->validity) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('validity') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Invitation -->
                <div>
                    <label for="invitaion" class="block text-sm font-semibold text-gray-700 mb-2">
                        Invitation <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="invitaion" id="invitaion" value="{{ old('invitaion', $package->invitaion) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('invitaion') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- RSVP -->
                <div>
                    <label for="rsvp" class="block text-sm font-semibold text-gray-700 mb-2">
                        RSVP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="rsvp" id="rsvp" value="{{ old('rsvp', $package->rsvp) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('rsvp') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Ceremonies -->
                <div>
                    <label for="ceramonies" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ceremonies <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ceramonies" id="ceramonies" value="{{ old('ceramonies', $package->ceramonies) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('ceramonies') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Reports -->
                <div>
                    <label for="reports" class="block text-sm font-semibold text-gray-700 mb-2">
                        Reports <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="reports" id="reports" value="{{ old('reports', $package->reports) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('reports') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Gallery Text Display -->
                <div>
                    <label for="gallery" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gallery (Text Display) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="gallery" id="gallery" value="{{ old('gallery', $package->gallery) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    @error('gallery') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Gallery Limit -->
                <div>
                    <label for="storage_limit_mb" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gallery Limit (in MB)
                    </label>
                    <input type="number" name="storage_limit_mb" id="storage_limit_mb" value="{{ old('storage_limit_mb', $package->storage_limit_mb) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                    @error('storage_limit_mb') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Message Service Text Display -->
                <div>
                    <label for="package_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Message Service (Text Display) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="package_description" id="package_description" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>{{ old('package_description', $package->package_description) }}</textarea>
                    @error('package_description') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- SMS Limit -->
                <div>
                    <label for="sms_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        SMS Limit (Number of messages)
                    </label>
                    <input type="number" name="sms_limit" id="sms_limit" value="{{ old('sms_limit', $package->sms_limit) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                    @error('sms_limit') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Email Limit -->
                <div>
                    <label for="email_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        EMAIL Limit (Number of messages)
                    </label>
                    <input type="number" name="email_limit" id="email_limit" value="{{ old('email_limit', $package->email_limit) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                    @error('email_limit') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- WhatsApp Limit -->
                <div>
                    <label for="whatsapp_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        WHATSAPP Limit (Number of messages)
                    </label>
                    <input type="number" name="whatsapp_limit" id="whatsapp_limit" value="{{ old('whatsapp_limit', $package->whatsapp_limit) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                    @error('whatsapp_limit') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Wishboard (Optional) -->
                <div>
                    <label for="wishboard" class="block text-sm font-semibold text-gray-700 mb-2">
                        Wishboard <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" name="wishboard" id="wishboard" value="{{ old('wishboard', $package->wishboard) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                    @error('wishboard') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- DCG QR Code (Optional) -->
                <div>
                    <label for="dcgqrcode" class="block text-sm font-semibold text-gray-700 mb-2">
                        DCG QR Code <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" name="dcgqrcode" id="dcgqrcode" value="{{ old('dcgqrcode', $package->dcgqrcode) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                    @error('dcgqrcode') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- VAF Field (Full Width Row) -->
                <div class="md:col-span-2">
                    <label for="vaf" class="block text-sm font-semibold text-gray-700 mb-2">
                        VAF <span class="text-red-500">*</span>
                    </label>
                    <textarea name="vaf" id="vaf" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>{{ old('vaf', $package->vaf) }}</textarea>
                    @error('vaf') <span class="block mt-1 text-xs text-red-600 font-medium">{{ $message }}</span> @enderror
                </div>

            </div>

            <!-- Dynamic Custom Fields Subsystem -->
            <hr class="border-gray-200 my-6">
            <div class="space-y-4 w-full">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-base font-bold text-gray-800">Dynamic Custom Fields</h5>
                        <p class="text-xs text-gray-500">Add custom key-value features for this package.</p>
                    </div>
                    <button type="button" id="add-custom-field" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                        <i class="bi bi-plus-lg mr-1.5"></i> Add Field
                    </button>
                </div>

                <div id="custom-fields-container" class="space-y-3 w-full">
                    @if($package->customFeatures && $package->customFeatures->count() > 0)
                        @foreach($package->customFeatures as $index => $feature)
                        <div class="custom-field-row flex flex-col md:flex-row gap-3 items-stretch md:items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="w-full md:w-1/3">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 md:hidden">Field Label</label>
                                <input type="text" name="custom_fields[{{ $index }}][label]" value="{{ $feature->field_label }}" placeholder="Field Label" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div class="w-full md:w-1/4">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 md:hidden">Field Type</label>
                                <select name="custom_fields[{{ $index }}][type]" class="field-type-selector w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="text" {{ $feature->field_type == 'text' ? 'selected' : '' }}>Text / String</option>
                                    <option value="number" {{ $feature->field_type == 'number' ? 'selected' : '' }}>Number</option>
                                    <option value="date" {{ $feature->field_type == 'date' ? 'selected' : '' }}>Date</option>
                                    <option value="price" {{ $feature->field_type == 'price' ? 'selected' : '' }}>Price (₹)</option>
                                </select>
                            </div>
                            <div class="w-full flex-1 value-container">
                                <label class="block text-xs font-semibold text-gray-500 mb-1 md:hidden">Value</label>
                                @if($feature->field_type == 'date')
                                    <input type="date" name="custom_fields[{{ $index }}][value]" value="{{ $feature->field_value }}" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                                @elseif($feature->field_type == 'price')
                                    <input type="number" step="0.01" name="custom_fields[{{ $index }}][value]" value="{{ $feature->field_value }}" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                                @elseif($feature->field_type == 'number')
                                    <input type="number" name="custom_fields[{{ $index }}][value]" value="{{ $feature->field_value }}" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                                @else
                                    <input type="text" name="custom_fields[{{ $index }}][value]" value="{{ $feature->field_value }}" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                                @endif
                            </div>
                            <button type="button" class="remove-field self-end md:self-auto px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-semibold transition-colors cursor-pointer border border-red-200">
                                <i class="bi bi-trash mr-1"></i> Remove
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3 w-full">
                <a href="{{ route('admin.package.index') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-xs focus:ring-4 focus:ring-blue-200 cursor-pointer">
                    <i class="bi bi-check-lg mr-1.5"></i> Update Package
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript Subsystem logic -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Helper function to update input fields when select changes
        function updateValueInput(row, index, type) {
            const valueContainer = row.querySelector('.value-container');
            if (type === 'text') {
                valueContainer.innerHTML = `<input type="text" name="custom_fields[${index}][value]" placeholder="Enter text" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>`;
            } else if (type === 'number') {
                valueContainer.innerHTML = `<input type="number" name="custom_fields[${index}][value]" placeholder="0" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>`;
            } else if (type === 'date') {
                valueContainer.innerHTML = `<input type="date" name="custom_fields[${index}][value]" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>`;
            } else if (type === 'price') {
                valueContainer.innerHTML = `<input type="number" step="0.01" name="custom_fields[${index}][value]" placeholder="0.00" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>`;
            }
        }

        // Bind events to existing loaded custom fields
        document.querySelectorAll('.custom-field-row').forEach((row, index) => {
            const select = row.querySelector('.field-type-selector');
            if (select) {
                select.addEventListener('change', function (e) {
                    updateValueInput(row, index, e.target.value);
                });
            }
        });

        // Dynamic Add Field click handler
        document.getElementById('add-custom-field').addEventListener('click', function() {
            const container = document.getElementById('custom-fields-container');
            const index = container.children.length;
            const row = document.createElement('div');
            row.className = "custom-field-row flex flex-col md:flex-row gap-3 items-stretch md:items-center bg-gray-50 p-4 rounded-xl border border-gray-200";
            
            row.innerHTML = `
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-semibold text-gray-500 mb-1 md:hidden">Field Label</label>
                    <input type="text" name="custom_fields[${index}][label]" placeholder="Field Label" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="w-full md:w-1/4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1 md:hidden">Field Type</label>
                    <select name="custom_fields[${index}][type]" class="field-type-selector w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500">
                        <option value="text">Text / String</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="price">Price (₹)</option>
                    </select>
                </div>
                <div class="w-full flex-1 value-container">
                    <label class="block text-xs font-semibold text-gray-500 mb-1 md:hidden">Value</label>
                    <input type="text" name="custom_fields[${index}][value]" placeholder="Enter Value" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <button type="button" class="remove-field self-end md:self-auto px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-semibold transition-colors cursor-pointer border border-red-200">
                    <i class="bi bi-trash mr-1"></i> Remove
                </button>
            `;
            container.appendChild(row);

            // Add change listener to newly appended select dropdown
            row.querySelector('.field-type-selector').addEventListener('change', function(e) {
                updateValueInput(row, index, e.target.value);
            });
        });

        // Event delegation for removal of fields
        document.getElementById('custom-fields-container').addEventListener('click', function(e) {
            if (e.target && e.target.closest('.remove-field')) {
                e.target.closest('.custom-field-row').remove();
            }
        });
    });
</script>
@endsection