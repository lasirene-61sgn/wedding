@extends('layouts.host')
<link rel="stylesheet" href="{{ asset('css/hostinvitationedit.css') }}">
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit Wedding Invitation</h2>
        <a href="{{ route('host.invitation.index') }}" class="btn btn-secondary btn-sm shadow-sm">Back to Registry</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- FORM SECTION (Left Side) -->
        <div class="col-lg-7">
            <form action="{{ route('host.invitation.update', $invitation->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card mb-4 shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Invitation Sent By</label>
                                <select name="invite" id="invite_dropdown" class="form-select watch-input" required>
                                    @foreach(['brideparents' => "Bride's Parents", 'groomparents' => "Groom's Parents", 'bride' => 'Bride', 'groom' => 'Groom', 'weddingcouple' => 'Wedding Couple'] as $key => $label)
                                        <option value="{{ $key }}" {{ $invitation->invite == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label fw-bold">Select Venue</label>
                                <div class="input-group">
                                    <select name="venue_id" id="venue_dropdown" class="form-select watch-input" required>
                                        <option value="">-- Select Venue --</option>
                                        @foreach($venues as $venue)
                                            <option value="{{ $venue->id }}" 
                                                data-name="{{ $venue->venue_name }}"
                                                data-pin="{{ $venue->pincode }}"
                                                data-area="{{ $venue->area_name }}"
                                                data-district="{{ $venue->district }}"
                                                data-state="{{ $venue->state }}"
                                                data-addr="{{ $venue->venue_address }}"
                                                data-landmark="{{ $venue->wedding_location }}"
                                                data-map="{{ $venue->location_map }}"
                                                {{ $invitation->venue_id == $venue->id ? 'selected' : '' }}>
                                                {{ $venue->venue_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" id="btn_add_venue">+ Add</button>
                                    <button type="button" class="btn btn-warning" id="btn_edit_venue">Edit</button>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Theme Style</label>
                                <select name="theme" id="theme_selector" class="form-select watch-input">
                                    <option value="classic" {{ $invitation->theme == 'classic' ? 'selected' : '' }}>Classic Elegant</option>
                                    <option value="royal" {{ $invitation->theme == 'royal' ? 'selected' : '' }}>Royal Luxury</option>
                                    <option value="floral" {{ $invitation->theme == 'floral' ? 'selected' : '' }}>Modern Floral</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="party_details_row">
                    <div class="col-md-6" id="bride_card_container">
                        <div class="card mb-4 shadow-sm border-info">
                            <div class="card-header bg-info text-white fw-bold">Bride's Information</div>
                            <div class="card-body">
                                <div class="mb-2"><label>Full Name</label><input type="text" name="bride_name" value="{{ $invitation->bride_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mobile Number</label><input type="text" name="bride_number" value="{{ $invitation->bride_number }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Email Address</label><input type="email" name="bride_email" value="{{ $invitation->bride_email }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Father's Name</label><input type="text" name="bride_father_name" value="{{ $invitation->bride_father_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mother's Name</label><input type="text" name="bride_mother_name" value="{{ $invitation->bride_mother_name }}" class="form-control watch-input" required></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="groom_card_container">
                        <div class="card mb-4 shadow-sm border-secondary">
                            <div class="card-header bg-secondary text-white fw-bold">Groom's Information</div>
                            <div class="card-body">
                                <div class="mb-2"><label>Full Name</label><input type="text" name="groom_name" value="{{ $invitation->groom_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mobile Number</label><input type="text" name="groom_number" value="{{ $invitation->groom_number }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Email Address</label><input type="email" name="groom_email" value="{{ $invitation->groom_email }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Father's Name</label><input type="text" name="groom_father_name" value="{{ $invitation->groom_father_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mother's Name</label><input type="text" name="groom_mother_name" value="{{ $invitation->groom_mother_name }}" class="form-control watch-input" required></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">Event Timing & Location</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" name="wedding_date" value="{{ $invitation->wedding_date }}" class="form-control watch-input" required></div>
                            <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" name="wedding_time" value="{{ $invitation->wedding_time }}" class="form-control watch-input" required></div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body text-center">
                        <label class="fw-bold d-block mb-3">Current Invitation Card Image</label>
                        <img src="{{ asset('storage/' . $invitation->wedding_image) }}" class="img-thumbnail mb-4 shadow-sm" style="max-height: 300px;" alt="Invitation Image">
                        <div class="col-md-6 mx-auto">
                            <input type="file" name="wedding_image" class="form-control">
                            <small class="text-muted mt-2 d-block">Only upload if you wish to change the existing image.</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-5 shadow">✨ Update Wedding Invitation</button>
            </form>
        </div>

        <!-- LIVE PREVIEW SECTION (Right Side) -->
        <div class="col-lg-5">
            <div class="preview-sticky">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center fw-bold">✨ Live Invitation Preview</div>
                    <div class="card-body bg-secondary-subtle d-flex justify-content-center py-4">
                        <div id="invitation_card" class="invitation-card-preview theme-{{ $invitation->theme }}">
                            <div class="preview-content">
                                <span class="text-uppercase small mb-2" style="letter-spacing: 3px; font-weight: 600;">You're Invited</span>
                                
                                <div class="decorative-divider"></div>
                                
                                <h1 id="p_bride" class="h2 fw-bold mb-0">{{ $invitation->bride_name ?: 'Bride' }}</h1>
                                <p class="h4 my-1" style="font-family: 'Dancing Script', cursive;">&</p>
                                <h1 id="p_groom" class="h2 fw-bold mb-3">{{ $invitation->groom_name ?: 'Groom' }}</h1>
                                
                                <div class="decorative-divider"></div>
                                
                                <div class="mb-3">
                                    <p id="p_date" class="fw-bold mb-0" style="font-size: 1.05rem;">📅 Date: {{ $invitation->wedding_date ? date('d/m/Y', strtotime($invitation->wedding_date)) : '--/--/----' }}</p>
                                    <p id="p_time" class="small" style="opacity: 0.9;">🕐 Time: {{ $invitation->wedding_time ?: '--:--' }}</p>
                                </div>

                                <div id="p_venue_box" class="venue-detail-box" style="display:{{ $invitation->venue_id ? 'block' : 'none' }};">
                                    <p id="p_v_name" class="fw-bold mb-1" style="text-decoration: underline; font-size: 0.95rem;">{{ $invitation->venue->venue_name ?? '' }}</p>
                                    <p class="mb-0"><span id="p_v_area">{{ $invitation->venue->area_name ?? '' }}</span><span id="p_v_landmark" class="fst-italic ms-1">{{ $invitation->venue->wedding_location ? '(' . $invitation->venue->wedding_location . ')' : '' }}</span></p>
                                    <p id="p_v_address" class="mb-1 small">{{ $invitation->venue->venue_address ?? '' }}</p>
                                    <p class="mb-2 small"><span id="p_v_district">{{ $invitation->venue->district ?? '' }}</span>, <span id="p_v_state">{{ $invitation->venue->state ?? '' }}</span> • <span id="p_v_pin">{{ $invitation->venue->pincode ?? '' }}</span></p>
                                    @if($invitation->venue->location_map)
                                    <a id="p_v_map" href="{{ $invitation->venue->location_map }}" target="_blank" class="btn btn-xs btn-outline-dark py-0 px-2" style="font-size: 0.7rem;">📍 View on Map</a>
                                    @endif
                                </div>
                                <p id="p_venue_placeholder" class="small text-muted mt-3" style="{{ $invitation->venue_id ? 'display:none;' : '' }}">✨ Select a venue to preview details</p>
                                
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

<!-- VENUE MODAL (UNCHANGED FUNCTIONALITY) -->
<div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 id="modalTitle">Add New Venue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="venueForm">
                @csrf
                <input type="hidden" id="q_v_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" id="q_v_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" id="q_v_pin" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><select id="q_v_area" class="form-select"></select></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" id="q_v_district" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" id="q_v_state" class="form-control" readonly></div>
                        <div class="col-md-6 mb-3"><label>Landmark</label><input type="text" id="q_v_wedding_location" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Map URL</label><input type="text" id="q_v_location_map" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Full Address</label><textarea id="q_v_addr" class="form-control" rows="2" required></textarea></div>
                    <input type="hidden" id="q_v_country" value="India"><input type="hidden" id="q_v_circle">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 shadow" id="btnSaveVenue">Save Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Google Fonts for Premium Typography -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Dancing+Script:wght@400;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/hostinvitationedit.js') }}"></script>
@endsection