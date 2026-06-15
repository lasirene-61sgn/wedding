@extends('layouts.host')
<link rel="stylesheet" href="{{ asset('css/hostinvitationcreate.css') }}">
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Create Wedding Invitation</h2>
        <a href="{{ route('host.invitation.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="row">
        <!-- FORM SECTION (UNCHANGED) -->
        <div class="col-lg-7">
            <form action="{{ route('host.invitation.store') }}" method="POST" enctype="multipart/form-data" id="invitationForm">
                @csrf
                
                <div class="card mb-4 shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Who is Inviting?</label>
                                <select name="invite" id="invite_dropdown" class="form-select" required>
                                    <option value="brideparents">Bride's Parents</option>
                                    <option value="groomparents">Groom's Parents</option>
                                    <option value="bride">Bride</option>
                                    <option value="groom">Groom</option>
                                    <option value="weddingcouple">Wedding Couple</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Select Venue</label>
                                <div class="input-group">
                                    <select name="venue_id" id="venue_dropdown" class="form-select" required>
                                        <option value="">-- Choose Venue --</option>
                                        @foreach($venues as $venue)
                                            <option value="{{ $venue->id }}" 
                                                data-name="{{ $venue->venue_name }}"
                                                data-pin="{{ $venue->pincode }}"
                                                data-area="{{ $venue->area_name }}"
                                                data-district="{{ $venue->district }}"
                                                data-state="{{ $venue->state }}"
                                                data-landmark="{{ $venue->wedding_location }}"
                                                data-address="{{ $venue->venue_address }}"
                                                data-map="{{ $venue->location_map }}">
                                                {{ $venue->venue_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">+ Add New</button>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Invitation Theme</label>
                                <select name="theme" id="theme_selector" class="form-select">
                                    <option value="classic">Classic Elegant</option>
                                    <option value="royal">Royal Luxury</option>
                                    <option value="floral">Modern Floral</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="party_details_row">
                    <div class="col-md-6" id="bride_card_container">
                        <div class="card mb-4 shadow-sm border-info">
                            <div class="card-header bg-info text-white fw-bold" id="bride_header_text">Bride's Information</div>
                            <div class="card-body">
                                <div class="mb-2"><label>Bride Name</label><input type="text" name="bride_name" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mobile</label><input type="text" name="bride_number" class="form-control" required></div>
                                <div class="mb-2"><label>Email</label><input type="email" name="bride_email" class="form-control" required></div>
                                <div class="mb-2"><label>Father's Name</label><input type="text" name="bride_father_name" class="form-control" required></div>
                                <div class="mb-2"><label>Mother's Name</label><input type="text" name="bride_mother_name" class="form-control" required></div>
                                <div class="mb-3">
                                <label class="form-label d-block"><strong>Select Guest Panel Background Theme</strong></label>
                                <div class="row g-3">
                                    @foreach($backgrounds as $bg)
                                    <div class="col-6 col-md-3">
                                        <label class="card h-100 text-center border p-2 position-relative cursor-pointer">
                                            <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="position-absolute top-0 start-0 m-2"
                                                {{ (isset($ceramony) && $ceramony->selected_background_id == $bg->id) ? 'checked' : '' }}>

                                            <img src="{{ asset('storage/' . $bg->image_path) }}" class="card-img-top img-fluid rounded" style="height: 120px; object-fit: cover;">
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('selected_background_id')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="groom_card_container">
                        <div class="card mb-4 shadow-sm border-secondary">
                            <div class="card-header bg-secondary text-white fw-bold" id="groom_header_text">Groom's Information</div>
                            <div class="card-body">
                                <div class="mb-2"><label>Groom Name</label><input type="text" name="groom_name" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mobile</label><input type="text" name="groom_number" class="form-control" required></div>
                                <div class="mb-2"><label>Email</label><input type="email" name="groom_email" class="form-control" required></div>
                                <div class="mb-2"><label>Father's Name</label><input type="text" name="groom_father_name" class="form-control" required></div>
                                <div class="mb-2"><label>Mother's Name</label><input type="text" name="groom_mother_name" class="form-control" required></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-dark text-white fw-bold">Event Schedule</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" name="wedding_date" class="form-control watch-input" required></div>
                            <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" name="wedding_time" class="form-control watch-input" required></div>
                        </div>
                        <div class="mt-3">
                            <label class="fw-bold">Upload Invitation Image</label>
                            <input type="file" name="wedding_image" class="form-control" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 mb-5 shadow-sm">Save & Generate Invitation</button>
            </form>
        </div>

        <!-- PREVIEW SECTION - ENHANCED DESIGNS -->
        <div class="col-lg-5">
            <div class="preview-sticky">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center fw-bold">✨ Live Invitation Preview</div>
                    <div class="card-body bg-secondary-subtle d-flex justify-content-center py-4">
                        <div id="invitation_card" class="invitation-card-preview theme-classic">
                            <div class="preview-content">
                                <span class="text-uppercase small mb-2" style="letter-spacing: 3px; font-weight: 600;">You're Invited</span>
                                
                                <div class="decorative-divider"></div>
                                
                                <h1 id="p_bride" class="h2 fw-bold mb-0">Bride</h1>
                                <p class="h4 my-1" style="font-family: 'Dancing Script', cursive;">&</p>
                                <h1 id="p_groom" class="h2 fw-bold mb-3">Groom</h1>
                                
                                <div class="decorative-divider"></div>
                                
                                <div class="mb-3">
                                    <p id="p_date" class="fw-bold mb-0" style="font-size: 1.05rem;">📅 Date: --/--/----</p>
                                    <p id="p_time" class="small" style="opacity: 0.9;">🕐 Time: --:--</p>
                                </div>

                                <div id="p_venue_box" class="venue-detail-box" style="display:none;">
                                    <p id="p_v_name" class="fw-bold mb-1" style="text-decoration: underline; font-size: 0.95rem;"></p>
                                    <p class="mb-0"><span id="p_v_area"></span><span id="p_v_landmark" class="fst-italic ms-1"></span></p>
                                    <p id="p_v_address" class="mb-1 small"></p>
                                    <p class="mb-2 small"><span id="p_v_district"></span>, <span id="p_v_state"></span> • <span id="p_v_pin"></span></p>
                                    <a id="p_v_map" href="#" target="_blank" class="btn btn-xs btn-outline-dark py-0 px-2" style="font-size: 0.7rem;">📍 View on Map</a>
                                </div>
                                <p id="p_venue_placeholder" class="small text-muted mt-3">✨ Select a venue to preview details</p>
                                
                                <div style="margin-top: 15px; font-size: 0.75rem; opacity: 0.7; font-style: italic;">
                                    Designed with ❤️ for your special day
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VENUE MODAL (UNCHANGED) -->
<div class="modal fade" id="addVenueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white"><h5>Add New Venue</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form id="quickVenueForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" id="q_v_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" id="q_v_pin" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><select id="q_v_area" class="form-select"></select></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" id="q_v_district" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" id="q_v_state" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>Landmark</label><input type="text" id="q_v_wedding_location" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Map Link (URL)</label><input type="text" id="q_v_location_map" class="form-control" placeholder="https://maps.google.com/..."></div>
                    </div>
                    <div class="mb-3"><label>Detailed Address</label><textarea id="q_v_addr" class="form-control" rows="2" required></textarea></div>
                    <input type="hidden" id="q_v_country" value="India"><input type="hidden" id="q_v_circle">
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary w-100 shadow">Save Venue</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Google Fonts for Premium Typography -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Dancing+Script:wght@400;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Global JavaScript Configurations -->
<script>
    window.LaravelRoutes = {
        hostVenueStore: "{{ route('host.venue.store', [], true) }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('js/hostinvitationcreate.js') }}?v={{ time() }}"></script>
@endsection