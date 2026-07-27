@extends('layouts.host')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hostceramonycreate.css') }}">

<div class="container mt-4">
    <div class="row">
        <!-- Form Side -->
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">Create New Ceremony</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('host.ceramony.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" id="category_select" class="form-select" required onchange="handleCategoryChange()">
                                    <option value="">-- Select Type --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-ceremonies="{{ json_encode(is_array($cat->ceremonies) ? $cat->ceremonies : []) }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Dynamic Ceremonies Box -->
                        <div class="mb-4" id="ceremonies_box_container" style="display: none;">
                            <label class="form-label fw-bold text-primary">Select Ceremony</label>
                            <div id="ceremonies_badges" class="d-flex flex-wrap gap-2">
                                <!-- Badges injected via JS -->
                            </div>
                        </div>

                        <div id="ceremony_details_container" style="display: none;">
                            <div class="mb-3" id="ceramony_name_container">
                                <label class="form-label">Ceremony Name</label>
                                <input type="text" name="ceramony_name" id="ceramony_name" class="form-control" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Select Venue</label>
                                    <div class="input-group">
                                        <select name="venue_id" id="venue_select" class="form-select">
                                            <option value="" data-name="Venue to be announced">-- Choose My Venue --</option>
                                            @foreach($venues as $v)
                                            <option value="{{ $v->id }}" data-name="{{ $v->venue_name }}">{{ $v->venue_name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">
                                            + New
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="ceramony_date" id="ceramony_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Time</label>
                                    <input type="time" name="ceramony_time" id="ceramony_time" class="form-control">
                                </div>
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold">Banner Image</label>
                            <input type="file" name="ceramony_image" class="form-control">
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

                        <input type="hidden" name="text_positions" id="text_positions" value="{}">
                        <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{}">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Background</label>
                            <div class="row">
                                @foreach($backgrounds as $bg)
                                <div class="col-md-3 col-6 mb-3 text-center">
                                    <label class="d-block cursor-pointer">
                                        <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="d-none bg-selector" data-image="{{ asset('storage/'.$bg->image_path) }}">
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
                            
                            <input type="text" name="canva_template_id" id="canva_template_id" class="form-control mb-2" placeholder="Enter Canva Template ID (e.g. DACxxxx)">
                            <small class="text-muted d-block mb-3">Enter a template ID if you want Canva to auto-generate an invitation based on the details above.</small>
                            
                            <label class="form-label fw-bold">Canva Public View Link (For Guests)</label>
                            <input type="url" name="canva_public_link" id="canva_public_link" class="form-control" placeholder="https://www.canva.com/design/.../view">
                            <small class="text-muted">To show your design to guests, edit your design in Canva, click "Share" -> "Public View Link", and paste it here.</small>

                            <input type="hidden" name="canva_design_url" id="canva_design_url_input">
                            <div id="canva_preview_container" class="mt-3 text-center" style="display: none;">
                                <label class="fw-bold text-success d-block text-start mb-2">Your Canva Design Preview:</label>
                                <img id="canva_preview_image" src="" alt="Canva Design" class="img-fluid rounded shadow" style="max-height: 250px;">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Create Ceremony</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="modal fade" id="addVenueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Add Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quickVenueForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Venue Name</label>
                            <input type="text" id="v_name" name="venue_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Pincode</label>
                            <input type="text" id="v_pincode" name="pincode" class="form-control" maxlength="6">
                            <small id="pin_load" class="text-primary" style="display:none;">Fetching...</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Area</label>
                            <select id="v_area" name="area_name" class="form-select"></select>
                        </div>
                        <div class="col-md-4">
                            <label>District</label>
                            <input type="text" id="v_district" name="district" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>State</label>
                            <input type="text" id="v_state" name="state" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Landmark</label>
                            <input type="text" id="v_wedding_location" name="wedding_location" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Map</label>
                            <input type="text" id="v_location_map" name="location_map" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Full Address</label>
                        <textarea id="v_address" name="venue_address" class="form-control"></textarea>
                    </div>
                    <input type="hidden" id="v_circle" name="circle">
                    <input type="hidden" id="v_country" name="country">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveVenueBtn" class="btn btn-primary">Save & Select Venue</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/hostceramonycreate.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    function handleCategoryChange() {
        const select = document.getElementById('category_select');
        const option = select.options[select.selectedIndex];
        const ceremoniesBox = document.getElementById('ceremonies_box_container');
        const ceremoniesBadges = document.getElementById('ceremonies_badges');
        const detailsContainer = document.getElementById('ceremony_details_container');
        
        // Reset and hide
        ceremoniesBadges.innerHTML = '';
        detailsContainer.style.display = 'none';
        document.getElementById('ceramony_name').value = '';

        if (!option || !option.value) {
            ceremoniesBox.style.display = 'none';
            return;
        }

        const ceremonies = JSON.parse(option.getAttribute('data-ceremonies') || '[]');
        
        ceremonies.forEach(ceremony => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-primary ceremony-badge';
            btn.innerText = ceremony;
            btn.onclick = function() {
                selectCeremonyBadge(this, ceremony);
            };
            ceremoniesBadges.appendChild(btn);
        });

        // Add "Others" button
        const othersBtn = document.createElement('button');
        othersBtn.type = 'button';
        othersBtn.className = 'btn btn-outline-secondary ceremony-badge';
        othersBtn.innerText = 'Others';
        othersBtn.onclick = function() {
            selectCeremonyBadge(this, '');
        };
        ceremoniesBadges.appendChild(othersBtn);

        ceremoniesBox.style.display = 'block';
    }

    function selectCeremonyBadge(clickedBtn, ceremonyName) {
        // Highlight active button
        document.querySelectorAll('.ceremony-badge').forEach(btn => {
            btn.classList.remove('btn-primary', 'text-white');
            if (btn.classList.contains('btn-outline-secondary')) {
                // Others button
                btn.classList.remove('btn-secondary', 'text-white');
            }
        });

        if (clickedBtn.innerText === 'Others') {
            clickedBtn.classList.add('btn-secondary', 'text-white');
            clickedBtn.classList.remove('btn-outline-secondary');
        } else {
            clickedBtn.classList.add('btn-primary', 'text-white');
            clickedBtn.classList.remove('btn-outline-primary');
        }

        // Show details container
        document.getElementById('ceremony_details_container').style.display = 'block';
        
        // Set name
        const nameInput = document.getElementById('ceramony_name');
        nameInput.value = ceremonyName;
        
        if (ceremonyName === '') {
            nameInput.focus();
        }
    }
</script>

@endsection