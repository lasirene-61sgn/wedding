@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Admin: Edit Invitation</h2>
        <a href="{{ route('admin.invitation.index') }}" 
           class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 border border-transparent rounded-md text-xs font-semibold text-white shadow-sm transition-colors">
            Back
        </a>
    </div>

    <form action="{{ route('admin.invitation.update', $invitation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Assignment & Config Panel -->
        <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Assigned Host -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Assigned Host</label>
                    <select name="host_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white py-2 px-3" required>
                        @foreach($host as $host)
                            <option value="{{ $host->id }}" {{ $invitation->host_id == $host->id ? 'selected' : '' }}>{{ $host->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Who is Inviting -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Who is Inviting?</label>
                    <select name="invite" id="invite_dropdown" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white py-2 px-3" required>
                        @foreach(['brideparents'=>"Bride's Parents",'groomparents'=>"Groom's Parents",'bride'=>'Bride','groom'=>'Groom','weddingcouple'=>'Wedding Couple'] as $k => $v)
                            <option value="{{ $k }}" {{ $invitation->invite == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Venue Picker Input Group -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Select Venue</label>
                    <div class="flex rounded-md shadow-sm">
                        <select name="venue_id" id="venue_dropdown" class="block w-full rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white py-2 px-3 z-10" required>
                            @foreach($venues as $v)
                                <option value="{{ $v->id }}" 
                                    data-name="{{ $v->venue_name }}" data-pin="{{ $v->pincode }}" 
                                    data-area="{{ $v->area_name }}" data-district="{{ $v->district }}" 
                                    data-state="{{ $v->state }}" data-addr="{{ $v->venue_address }}" 
                                    data-landmark="{{ $v->wedding_location }}" data-map="{{ $v->location_map }}"
                                    {{ $invitation->venue_id == $v->id ? 'selected' : '' }}>
                                    {{ $v->venue_name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="btn_add_venue" class="inline-flex items-center px-3 rounded-none border-t border-b border-r border-gray-300 bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-colors">+</button>
                        <button type="button" id="btn_edit_venue" class="inline-flex items-center px-3 rounded-r-md border-t border-b border-r border-gray-300 bg-amber-500 text-white text-sm hover:bg-amber-600 transition-colors">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>

                <!-- Invitation Theme -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Theme</label>
                    <select name="theme" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white py-2 px-3">
                        <option value="classic" {{ $invitation->theme == 'classic' ? 'selected' : '' }}>Classic</option>
                        <option value="royal" {{ $invitation->theme == 'royal' ? 'selected' : '' }}>Royal</option>
                        <option value="floral" {{ $invitation->theme == 'floral' ? 'selected' : '' }}>Floral</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Party Information Block -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="party_details_row">
            <!-- Bride Container -->
            <div id="bride_card_container" class="bg-white rounded-lg shadow-sm border border-cyan-200 overflow-hidden">
                <div class="bg-cyan-600 px-4 py-3 text-white font-bold text-sm tracking-wide">
                    Bride's Information
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Bride Name</label>
                        <input type="text" name="bride_name" value="{{ $invitation->bride_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Mobile</label>
                        <input type="text" name="bride_number" value="{{ $invitation->bride_number }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                        <input type="email" name="bride_email" value="{{ $invitation->bride_email }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Father</label>
                        <input type="text" name="bride_father_name" value="{{ $invitation->bride_father_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Mother</label>
                        <input type="text" name="bride_mother_name" value="{{ $invitation->bride_mother_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 text-sm py-2 px-3" required>
                    </div>
                </div>
            </div>

            <!-- Groom Container -->
            <div id="groom_card_container" class="bg-white rounded-lg shadow-sm border border-gray-300 overflow-hidden">
                <div class="bg-gray-600 px-4 py-3 text-white font-bold text-sm tracking-wide">
                    Groom's Information
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Groom Name</label>
                        <input type="text" name="groom_name" value="{{ $invitation->groom_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Mobile</label>
                        <input type="text" name="groom_number" value="{{ $invitation->groom_number }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                        <input type="email" name="groom_email" value="{{ $invitation->groom_email }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Father</label>
                        <input type="text" name="groom_father_name" value="{{ $invitation->groom_father_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Mother</label>
                        <input type="text" name="groom_mother_name" value="{{ $invitation->groom_mother_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 text-sm py-2 px-3" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Management Panel -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 flex flex-col items-center justify-center space-y-4">
                @if($invitation->wedding_image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $invitation->wedding_image) }}" 
                             alt="Current Invitation" 
                             class="max-h-48 object-contain rounded-lg border border-gray-200 shadow-sm p-1 bg-white">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center transition-opacity duration-200">
                            <span class="text-white text-xs font-semibold tracking-wider bg-black/60 px-2.5 py-1 rounded">Current Image</span>
                        </div>
                    </div>
                @endif
                
                <div class="w-full max-w-md">
                    <label class="block text-center text-sm font-medium text-gray-600 mb-2">Replace Invitation Image (Optional)</label>
                    <input type="file" name="wedding_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-md cursor-pointer focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2 pb-12">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg font-bold text-base text-white shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                Update Invitation
            </button>
        </div>
    </form>
</div>

@include('admin.invitation.venue_modal')
@endsection