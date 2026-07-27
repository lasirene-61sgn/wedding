@extends('layouts.host')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hostinvitationcreation.css') }}">
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
                                <select name="invite" id="invite_dropdown" class="form-select">
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
                                    <select name="venue_id" id="venue_dropdown" class="form-select">
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
                                <div class="mb-2"><label>Email</label><input type="email" name="bride_email" class="form-control"></div>
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
                                <div class="mb-2"><label>Email</label><input type="email" name="groom_email" class="form-control"></div>
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
                            <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" id="wedding_date" name="wedding_date" class="form-control watch-input"></div>
                            <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" id="wedding_time" name="wedding_time" class="form-control watch-input"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Title Text Color</label>
                                <input type="color" name="text_color" id="text_color" class="form-control form-control-color w-100" value="#b02663">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Details Text Color</label>
                                <input type="color" name="details_color" id="details_color" class="form-control form-control-color w-100" value="#2b4c5e">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="fw-bold">Upload Invitation Image</label>
                            <input type="file" name="wedding_image" class="form-control">
                        </div>
                        
                        <input type="hidden" name="text_positions" id="text_positions" value="{}">
                        <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{}">
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 mb-5 shadow-sm">Save & Generate Invitation</button>
            </form>
        </div>

        <!-- Live Preview Side -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark text-center">Live Preview</h5>
                </div>
                <div class="card-body bg-light position-relative">
                    
                    <!-- Floating Toolbar -->
                    <div class="canvas-toolbar" id="canvas_toolbar">
                        <select id="tool_font_family" title="Font Family">
                            <option value="Georgia, cursive, serif">Georgia</option>
                            <option value="'Playfair Display', serif">Playfair Display</option>
                            <option value="'Great Vibes', cursive">Great Vibes</option>
                            <option value="'Montserrat', sans-serif">Montserrat</option>
                            <option value="'Lora', serif">Lora</option>
                            <option value="'Poppins', sans-serif">Poppins</option>
                            <option value="Arial, sans-serif">Arial</option>
                        </select>
                        <div class="vr mx-1"></div>
                        <button type="button" id="tool_bold" title="Bold" class="btn btn-sm btn-outline-secondary px-2 fw-bold">B</button>
                        <button type="button" id="tool_italic" title="Italic" class="btn btn-sm btn-outline-secondary px-2 fst-italic">I</button>
                        <div class="vr mx-1"></div>
                        <button type="button" id="tool_size_down" title="Decrease Font Size" class="btn btn-sm btn-outline-secondary px-2">A-</button>
                        <button type="button" id="tool_size_up" title="Increase Font Size" class="btn btn-sm btn-outline-secondary px-2">A+</button>
                        <div class="vr mx-1"></div>
                        <button type="button" id="tool_align_left" title="Align Left" class="btn btn-sm btn-outline-secondary px-2">⬅</button>
                        <button type="button" id="tool_align_center" title="Center" class="btn btn-sm btn-outline-secondary px-2">↔</button>
                        <button type="button" id="tool_align_right" title="Align Right" class="btn btn-sm btn-outline-secondary px-2">➡</button>
                        <div class="vr mx-1"></div>
                        <input type="color" id="tool_color" title="Text Color" value="#000000">
                        <div class="vr mx-1"></div>
                        <select id="tool_animation_type" title="Animation Type">
                            <option value="none">No Anim</option>
                            <option value="fade-in">Fade In</option>
                            <option value="slide-up">Slide Up</option>
                            <option value="slide-down">Slide Down</option>
                            <option value="zoom-in">Zoom In</option>
                            <option value="bounce">Bounce</option>
                        </select>
                        <input type="number" id="tool_animation_duration" title="Anim Duration (s)" step="0.1" min="0.1" max="5" value="0.8">
                    </div>

                    <p class="text-muted text-center small mb-3">You can click to edit the text directly on the canvas, and drag it anywhere!</p>
                    <div class="ceremony-card-preview" id="preview_card">
                        <canvas id="designCanvas" width="450" height="600"></canvas>
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
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" id="q_v_name" name="venue_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" id="q_v_pin" name="pincode" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><select id="q_v_area" name="area_name" class="form-select"></select></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" id="q_v_district" name="district" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" id="q_v_state" name="state" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>Landmark</label><input type="text" id="q_v_wedding_location" name="wedding_location" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Map Link (URL)</label><input type="text" id="q_v_location_map" name="location_map" class="form-control" placeholder="https://maps.google.com/..."></div>
                    </div>
                    <div class="mb-3"><label>Detailed Address</label><textarea id="q_v_addr" name="venue_address" class="form-control" rows="2" required></textarea></div>
                    <input type="hidden" id="q_v_country" name="country" value="India"><input type="hidden" id="q_v_circle" name="circle">
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary w-100 shadow">Save Venue</button></div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="{{ asset('js/hostinvitationcreation.js') }}"></script>
@endpush
@endsection