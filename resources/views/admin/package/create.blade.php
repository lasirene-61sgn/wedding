@extends('layouts.admin')

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create Package</h1>
            <p class="text-sm text-gray-500 mt-1">Add a new hosting or subscription package to the system.</p>
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

    <!-- Package Form Card (Full Width Span) -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-xs p-6 md:p-8 w-full">
        <form action="{{ route('admin.package.store') }}" method="POST" class="space-y-6 w-full">
            @csrf

            <!-- Form Grid (2 Columns across full width) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                <!-- Plan Name -->
                <div>
                    <label for="plan_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Plan Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="plan_name" id="plan_name" value="{{ old('plan_name') }}"
                        placeholder="e.g. Gold Plan"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="price" id="price" value="{{ old('price') }}"
                        placeholder="e.g. 1500+GST"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Guest Count -->
                <div>
                    <label for="guest_count" class="block text-sm font-semibold text-gray-700 mb-2">
                        Guest Count <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="guest_count" id="guest_count" value="{{ old('guest_count') }}"
                        placeholder="e.g. 100"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Validity -->
                <div>
                    <label for="validity" class="block text-sm font-semibold text-gray-700 mb-2">
                        Validity <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="validity" id="validity" value="{{ old('validity') }}"
                        placeholder="e.g. 1 Year"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Invitation -->
                <div>
                    <label for="invitation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Invitation <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="invitation" id="invitation" value="{{ old('invitation') }}"
                        placeholder="Enter invitation info"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- RSVP -->
                <div>
                    <label for="rsvp" class="block text-sm font-semibold text-gray-700 mb-2">
                        RSVP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="rsvp" id="rsvp" value="{{ old('rsvp') }}"
                        placeholder="Enter RSVP info"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Ceremonies -->
                <div>
                    <label for="ceremonies" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ceremonies <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ceremonies" id="ceremonies" value="{{ old('ceremonies') }}"
                        placeholder="Enter ceremonies info"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Reports -->
                <div>
                    <label for="reports" class="block text-sm font-semibold text-gray-700 mb-2">
                        Reports <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="reports" id="reports" value="{{ old('reports') }}"
                        placeholder="Enter reports info"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Gallery Text Display -->
                <div>
                    <label for="gallery_display" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gallery (Text Display) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="gallery_display" id="gallery_display" value="{{ old('gallery_display') }}"
                        placeholder="Enter gallery text for display (e.g. 1.5 MB)"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

                <!-- Gallery Limit (in MB) -->
                <div>
                    <label for="gallery_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gallery Limit (in MB)
                    </label>
                    <input type="number" name="gallery_limit" id="gallery_limit" value="{{ old('gallery_limit') }}"
                        placeholder="e.g. 500"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                </div>

                <!-- Message Service (Text Display) -->
                <div>
                    <label for="message_service_display" class="block text-sm font-semibold text-gray-700 mb-2">
                        Message Service (Text Display) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message_service_display" id="message_service_display" rows="3"
                        placeholder="Enter package details for display..."
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>{{ old('message_service_display') }}</textarea>
                </div>

                <!-- SMS Limit -->
                <div>
                    <label for="sms_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        SMS Limit
                    </label>
                    <input type="number" name="sms_limit" id="sms_limit" value="{{ old('sms_limit') }}"
                        placeholder="e.g. 100"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                </div>

                <!-- Email Limit -->
                <div>
                    <label for="email_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Limit
                    </label>
                    <input type="number" name="email_limit" id="email_limit" value="{{ old('email_limit') }}"
                        placeholder="e.g. 100"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                </div>

                <!-- WhatsApp Limit -->
                <div>
                    <label for="whatsapp_limit" class="block text-sm font-semibold text-gray-700 mb-2">
                        WhatsApp Limit
                    </label>
                    <input type="number" name="whatsapp_limit" id="whatsapp_limit" value="{{ old('whatsapp_limit') }}"
                        placeholder="e.g. 100"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                </div>

                <!-- Wishboard (Optional) -->
                <div>
                    <label for="wishboard" class="block text-sm font-semibold text-gray-700 mb-2">
                        Wishboard <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" name="wishboard" id="wishboard" value="{{ old('wishboard') }}"
                        placeholder="Enter wishboard info"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                </div>

                <!-- DCG QR Code (Optional) -->
                <div>
                    <label for="dcg_qr_code" class="block text-sm font-semibold text-gray-700 mb-2">
                        DCG QR Code <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" name="dcg_qr_code" id="dcg_qr_code" value="{{ old('dcg_qr_code') }}"
                        placeholder="Enter QR code info"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all">
                </div>

                <!-- VAF (Full Row Span) -->
                <div class="md:col-span-2">
                    <label for="vaf" class="block text-sm font-semibold text-gray-700 mb-2">
                        VAF <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="vaf" id="vaf" value="{{ old('vaf') }}"
                        placeholder="Enter VAF information..."
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                </div>

            </div>

            <!-- Form Action Buttons -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3 w-full">
                <a href="{{ route('admin.package.index') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm focus:ring-4 focus:ring-blue-200">
                    <i class="bi bi-check-lg mr-1.5"></i> Save Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection