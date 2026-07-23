@extends('layouts.host')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hostceramonyedit.css') }}">
<div class="container mt-4">
    <div class="row">
        <!-- Form Side -->
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Edit Ceremony</h5>
                    <a href="{{ route('host.ceramony.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('host.ceramony.update', $ceramony->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $ceramony->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Venue Location</label>
                                <div class="input-group">
                                    <select name="venue_id" id="venue_select" class="form-select">
                                        <option value="" data-name="Venue to be announced">-- Select Venue --</option>
                                        @foreach($venues as $v)
                                        <option value="{{ $v->id }}"
                                            data-name="{{ $v->venue_name }}"
                                            data-address="{{ $v->venue_address }}"
                                            data-pin="{{ $v->pincode }}"
                                            data-area="{{ $v->area_name }}"
                                            data-district="{{ $v->district }}"
                                            data-state="{{ $v->state }}"
                                            data-country="{{ $v->country }}"
                                            data-circle="{{ $v->circle }}"
                                            data-landmark="{{ $v->wedding_location }}"
                                            data-map="{{ $v->location_map }}"
                                            {{ $ceramony->venue_id == $v->id ? 'selected' : '' }}>
                                            {{ $v->venue_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-warning text-white" id="edit_venue_btn">Edit</button>
                                    <button type="button" class="btn btn-primary" id="new_venue_btn">+ New</button>
                                </div>
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold">Ceremony Name</label>
                            <input type="text" name="ceramony_name" id="ceramony_name" class="form-control" value="{{ $ceramony->ceramony_name }}" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date</label>
                                <input type="date" name="ceramony_date" id="ceramony_date" class="form-control" value="{{ $ceramony->ceramony_date }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Time</label>
                                <input type="time" name="ceramony_time" id="ceramony_time" class="form-control" value="{{ $ceramony->ceramony_time }}">
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold">Banner Image</label>
                            @if($ceramony->ceramony_image)
                            <div class="mb-2"><img src="{{ asset('storage/'.$ceramony->ceramony_image) }}" width="100" class="rounded border"></div>
                            @endif
                            <input type="file" name="ceramony_image" class="form-control">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Title Text Color</label>
                                <input type="color" name="text_color" id="text_color" class="form-control form-control-color w-100" value="{{ $ceramony->text_color ?? '#b02663' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Details Text Color</label>
                                <input type="color" name="details_color" id="details_color" class="form-control form-control-color w-100" value="{{ $ceramony->details_color ?? '#2b4c5e' }}">
                            </div>
                        </div>

                        <input type="hidden" name="text_positions" id="text_positions" value="{{ is_array($ceramony->text_positions) ? json_encode($ceramony->text_positions) : ($ceramony->text_positions ?? '{}') }}">
                        <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{{ is_array($ceramony->custom_canvas_texts) ? json_encode($ceramony->custom_canvas_texts) : ($ceramony->custom_canvas_texts ?? '{}') }}">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Background</label>
                            <div class="row">
                                @foreach($backgrounds as $bg)
                                <div class="col-md-3 col-6 mb-3 text-center">
                                    <label class="d-block cursor-pointer">
                                        <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="d-none bg-selector" data-image="{{ asset('storage/'.$bg->image_path) }}" {{ $ceramony->selected_background_id == $bg->id ? 'checked' : '' }}>
                                        <img src="{{ asset('storage/'.$bg->image_path) }}" class="img-fluid rounded border bg-img-preview" alt="Background" style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;">
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4 position-relative" id="live-preview-container" style="display: none;">
                            <label class="form-label fw-bold">Live Preview</label>
                            <p class="text-muted small mb-2">You can click to edit the text directly on the canvas, and drag it anywhere!</p>
                            
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

                            <div class="ceremony-card-preview border" id="preview_card">
                                <canvas id="designCanvas" width="450" height="600"></canvas>
                            </div>
                        </div>

                        <div class="mb-4 d-none">
                            <label class="form-label fw-bold">Canva Integration</label>
                            
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" id="btn-design-on-canva" class="btn btn-outline-info rounded-pill fw-bold">
                                    <i class="bi bi-magic me-2"></i> Design on Canva
                                </button>
                                <span class="text-muted small">or enter a Template ID below to auto-generate:</span>
                            </div>

                            <input type="text" name="canva_template_id" id="canva_template_id" class="form-control mb-2" value="{{ $ceramony->canva_template_id }}" placeholder="Enter Canva Template ID (e.g. DACxxxx)">
                            <small class="text-muted d-block mb-3">Enter a template ID if you want Canva to auto-generate an invitation based on the details above.</small>
                            
                            <label class="form-label fw-bold">Canva Public View Link (For Guests)</label>
                            <input type="url" name="canva_public_link" id="canva_public_link" class="form-control" value="{{ $ceramony->canva_public_link }}" placeholder="https://www.canva.com/design/.../view">
                            <small class="text-muted">To show your design to guests, edit your design in Canva, click "Share" -> "Public View Link", and paste it here.</small>

                            <input type="hidden" name="canva_design_url" id="canva_design_url_input" value="{{ $ceramony->canva_design_url }}">
                            <!-- <div id="canva_preview_container" class="mt-3 text-center" style="{{ $ceramony->canva_design_url ? 'display: block;' : 'display: none;' }}">
                                <label class="fw-bold text-success d-block text-start mb-2">Your Canva Design Preview:</label>
                                <img id="canva_preview_image" src="{{ $ceramony->canva_design_url }}" alt="Canva Design" class="img-fluid rounded shadow" style="max-height: 250px;">
                                @if($ceramony->canva_design_url)
                                <div class="mt-2">
                                    <a href="{{ $ceramony->canva_design_url }}" target="_blank" class="btn btn-sm btn-info text-white"><i class="bi bi-box-arrow-up-right me-1"></i> Open Full Image</a>
                                </div>
                                @endif
                            </div> -->
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border fw-bold">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Update Ceremony</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitle">Add Venue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="venueForm">
                    @csrf
                    <input type="hidden" name="id" id="v_id">
                    <div class="row g-3">
                        <div class="col-md-6"><label>Venue Name</label><input type="text" name="venue_name" id="v_name" class="form-control" required></div>
                        <div class="col-md-6"><label>Pincode</label><input type="text" name="pincode" id="v_pincode" class="form-control" maxlength="6"></div>
                        <div class="col-md-4"><label>Area</label><select name="area_name" id="v_area" class="form-select"></select></div>
                        <div class="col-md-4"><label>District</label><input type="text" name="district" id="v_district" class="form-control" readonly></div>
                        <div class="col-md-4"><label>State</label><input type="text" name="state" id="v_state" class="form-control" readonly></div>
                        <div class="col-md-4"><label>Country</label><input type="text" name="country" id="v_country" class="form-control" readonly></div>
                        <div class="col-md-4"><label>Circle</label><input type="text" name="circle" id="v_circle" class="form-control" readonly></div>
                        <div class="col-md-4"><label>Landmark</label><input type="text" name="wedding_location" id="v_wedding_location" class="form-control"></div>
                        <div class="col-md-12"><label>Map URL</label><input type="text" name="location_map" id="v_location_map" class="form-control"></div>
                        <div class="col-12"><label>Full Address</label><textarea name="venue_address" id="v_address" class="form-control" rows="2"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveVenueBtn" class="btn btn-primary px-4">Save Venue</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="{{ asset('js/hostceramonyedit.js') }}"></script>

@endsection