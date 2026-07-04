@extends('layouts.host')
<style>
    .ceremony-card-preview {
        background-color: #fff9e6; 
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 35px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        position: relative;
        box-sizing: border-box;
        width: 100%;
        max-width: 450px;
        margin-left: auto;
        margin-right: auto;
        aspect-ratio: 3 / 4; 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }
    .ceremony-card-preview .card-content {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: auto !important;
        width: 100%;
        height: 100%;
        text-align: center;
        z-index: 2;
    }
    .ceremony-card-preview .ceremony-title {
        font-family: 'Georgia', cursive, serif !important;
        font-size: 2.2rem !important;
        line-height: 1.2;
        word-wrap: break-word;
        font-weight: 600;
        margin: 0 !important;
        text-shadow: 2px 2px 4px rgba(255, 255, 255, 1), -2px -2px 4px rgba(255, 255, 255, 1);
        cursor: grab;
        position: absolute;
    }
    .ceremony-card-preview .ceremony-title:active {
        cursor: grabbing;
    }
    .ceremony-card-preview .details-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 1.1rem !important;
        margin: 0 !important;
        font-weight: 600;
        text-shadow: 1.5px 1.5px 3px rgba(255, 255, 255, 1), -1.5px -1.5px 3px rgba(255, 255, 255, 1);
        cursor: grab;
        position: absolute;
    }
    .ceremony-card-preview .details-row:active {
        cursor: grabbing;
    }
    #preview_card_inner {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .draggable-text {
        width: max-content;
        min-width: 50px;
        padding: 5px;
        margin: 0;
        line-height: 1.2;
        cursor: grab;
        position: absolute;
        border: 2px solid transparent;
        border-radius: 4px;
        transition: border 0.2s;
    }
    .draggable-text.selected {
        border: 2px solid rgba(0, 123, 255, 0.5);
    }
    .draggable-text.editing-mode {
        outline: none;
        border: 2px dashed rgba(255, 255, 255, 0.9);
        background: rgba(255, 255, 255, 0.1);
        cursor: text;
    }
    .draggable-text:active {
        cursor: grabbing;
    }
    /* Toolbar Styles */
    .canvas-toolbar {
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        background: #343a40;
        border-radius: 8px;
        padding: 6px 12px;
        display: none;
        gap: 8px;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1000;
        width: max-content;
    }
    .canvas-toolbar button, .canvas-toolbar select, .canvas-toolbar input {
        background: none;
        border: none;
        color: white;
        font-size: 14px;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
    }
    .canvas-toolbar select {
        background: #495057;
        color: white;
        padding: 4px 8px;
    }
    .canvas-toolbar input[type="number"] {
        background: #495057;
        color: white;
        padding: 4px 8px;
        width: 60px;
    }
    .canvas-toolbar button:hover {
        background: rgba(255,255,255,0.2);
    }
</style>

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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#venueModal">+ Add</button>
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
                            <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" id="wedding_date" name="wedding_date" value="{{ $invitation->wedding_date }}" class="form-control watch-input" required></div>
                            <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" id="wedding_time" name="wedding_time" value="{{ $invitation->wedding_time }}" class="form-control watch-input" required></div>
                            <div class="mb-3">
                                <label class="form-label d-block"><strong>Select Guest Panel Background Theme</strong></label>
                                <div class="row g-3">
                                    @foreach($backgrounds as $bg)
                                    <div class="col-6 col-md-3">
                                        <label class="card h-100 text-center border p-2 position-relative cursor-pointer">
                                            <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="position-absolute top-0 start-0 m-2" data-url="{{ asset('storage/' . $bg->image_path) }}"
                                                {{ (isset($ceramony) && $ceramony->selected_background_id == $bg->id) || (isset($invitation) && $invitation->selected_background_id == $bg->id) ? 'checked' : '' }}>

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
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Title Text Color</label>
                                <input type="color" name="text_color" id="text_color" class="form-control form-control-color w-100" value="{{ $invitation->text_color ?? '#b02663' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Details Text Color</label>
                                <input type="color" name="details_color" id="details_color" class="form-control form-control-color w-100" value="{{ $invitation->details_color ?? '#2b4c5e' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body text-center">
                        <label class="fw-bold d-block mb-3">Current Invitation Card Image</label>
                        @if($invitation->wedding_image)
                        <img src="{{ asset('storage/' . $invitation->wedding_image) }}" class="img-thumbnail mb-4 shadow-sm" style="max-height: 300px;" alt="Invitation Image">
                        @endif
                        <div class="col-md-6 mx-auto">
                            <input type="file" name="wedding_image" class="form-control">
                            <small class="text-muted mt-2 d-block">Only upload if you wish to change the existing image.</small>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="text_positions" id="text_positions" value="{{ is_string($invitation->text_positions) ? $invitation->text_positions : json_encode($invitation->text_positions ?? []) }}">
                <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{{ is_string($invitation->custom_canvas_texts) ? $invitation->custom_canvas_texts : json_encode($invitation->custom_canvas_texts ?? []) }}">

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-5 shadow">✨ Update Wedding Invitation</button>
            </form>
        </div>

        <!-- LIVE PREVIEW SECTION (Right Side) -->
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
                    
                    @php
                        $bgImage = '';
                        if($invitation->selected_background_id){
                            foreach($backgrounds as $bg){
                                if($bg->id == $invitation->selected_background_id){
                                    $bgImage = asset('storage/' . $bg->image_path);
                                    break;
                                }
                            }
                        }
                    @endphp
                    
                    <div class="ceremony-card-preview" id="preview_card" style="{{ $bgImage ? 'background-image: url(' . $bgImage . ');' : '' }}">
                        <canvas id="designCanvas" width="450" height="600"></canvas>
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
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" id="q_v_name" name="venue_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" id="q_v_pin" name="pincode" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><select id="q_v_area" name="area_name" class="form-select"></select></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" id="q_v_district" name="district" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" id="q_v_state" name="state" class="form-control" readonly></div>
                        <div class="col-md-6 mb-3"><label>Landmark</label><input type="text" id="q_v_wedding_location" name="wedding_location" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Map URL</label><input type="text" id="q_v_location_map" name="location_map" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Full Address</label><textarea id="q_v_addr" name="venue_address" class="form-control" rows="2" required></textarea></div>
                    <input type="hidden" id="q_v_country" name="country" value="India"><input type="hidden" id="q_v_circle" name="circle">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 shadow" id="btnSaveVenue">Save Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const brideInput = document.querySelector('input[name="bride_name"]');
        const groomInput = document.querySelector('input[name="groom_name"]');
        const dateInput = document.getElementById('wedding_date');
        const timeInput = document.getElementById('wedding_time');
        const venueSelect = document.getElementById('venue_dropdown');
        const textColorInput = document.getElementById('text_color');
        const detailsColorInput = document.getElementById('details_color');
        const bgRadios = document.querySelectorAll('input[name="selected_background_id"]');
        const textPositionsInput = document.getElementById('text_positions');
        const toolbar = document.getElementById('canvas_toolbar');

        // Init Fabric Canvas
        const canvas = new fabric.Canvas('designCanvas', {
            preserveObjectStacking: true
        });

        // Set up custom properties to be exported in JSON
        fabric.Object.prototype.toObject = (function(toObject) {
            return function(propertiesToInclude) {
                return toObject.call(this, ['id', 'animType', 'animDuration'].concat(propertiesToInclude || []));
            };
        })(fabric.Object.prototype.toObject);

        function addOrUpdateText(id, text, top, fontSize, color, fontFamily) {
            let existingObj = canvas.getObjects().find(o => o.id === id);
            if (existingObj) {
                existingObj.set({ text: text }); // Only update text, preserve layout and colors in edit
            } else {
                let obj = new fabric.Textbox(text, {
                    id: id,
                    left: 225, // center of 450
                    top: top,
                    originX: 'center',
                    originY: 'center',
                    fontSize: fontSize,
                    fill: color,
                    fontFamily: fontFamily,
                    textAlign: 'center',
                    width: 400,
                    animType: 'none',
                    animDuration: '0.8',
                    transparentCorners: false,
                    cornerColor: '#007bff',
                    cornerSize: 10,
                    borderColor: '#007bff'
                });
                canvas.add(obj);
                return obj;
            }
            return existingObj;
        }

        function updatePreview() {
            let bride = brideInput && brideInput.value ? brideInput.value : 'Bride';
            let groom = groomInput && groomInput.value ? groomInput.value : 'Groom';
            let titleText = `Wedding: ${bride} & ${groom}`;
            
            let dateText = 'Select a Date';
            if(dateInput && dateInput.value) {
                const dateObj = new Date(dateInput.value);
                dateText = '📅 ' + dateObj.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            }

            let timeText = 'Select a Time';
            if(timeInput && timeInput.value) {
                let [h, m] = timeInput.value.split(':');
                let ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                timeText = `⏰ ${h}:${m} ${ampm}`;
            }

            let venueText = 'Venue to be announced';
            if(venueSelect && venueSelect.selectedIndex > 0) {
                const opt = venueSelect.options[venueSelect.selectedIndex];
                venueText = '📍 ' + (opt.getAttribute('data-name') || 'Venue to be announced');
            }

            let tColor = textColorInput ? textColorInput.value : '#b02663';
            let dColor = detailsColorInput ? detailsColorInput.value : '#2b4c5e';

            // Create or update existing
            addOrUpdateText('preview_title', titleText, 100, 32, tColor, 'Georgia');
            addOrUpdateText('preview_date_row', dateText, 250, 18, dColor, 'Arial');
            addOrUpdateText('preview_time_row', timeText, 320, 18, dColor, 'Arial');
            addOrUpdateText('preview_venue_row', venueText, 390, 18, dColor, 'Arial');

            canvas.renderAll();
            saveCanvasState();
        }

        function saveCanvasState() {
            textPositionsInput.value = JSON.stringify(canvas.toJSON());
        }

        canvas.on('object:modified', saveCanvasState);
        canvas.on('text:changed', saveCanvasState);

        // Bind form inputs
        function bindEvents() {
            if(brideInput) brideInput.addEventListener('input', updatePreview);
            if(groomInput) groomInput.addEventListener('input', updatePreview);
            if(dateInput) dateInput.addEventListener('change', updatePreview);
            if(timeInput) timeInput.addEventListener('input', updatePreview);
            if(venueSelect) venueSelect.addEventListener('change', updatePreview);
            
            // Only bind color inputs if they actually want to overwrite the whole canvas colors
            if(textColorInput) textColorInput.addEventListener('input', function() {
                let obj = canvas.getObjects().find(o => o.id === 'preview_title');
                if(obj) { obj.set('fill', this.value); canvas.renderAll(); saveCanvasState(); }
            });
            if(detailsColorInput) detailsColorInput.addEventListener('input', function() {
                let ids = ['preview_date_row', 'preview_time_row', 'preview_venue_row'];
                canvas.getObjects().forEach(obj => {
                    if(ids.includes(obj.id)) { obj.set('fill', this.value); }
                });
                canvas.renderAll();
                saveCanvasState();
            });
        }

        // Background Image Logic
        bgRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const imgEl = this.closest('label').querySelector('img');
                    if(imgEl) {
                        fabric.Image.fromURL(imgEl.src, function(img) {
                            // Scale image to fit canvas
                            let scaleX = canvas.width / img.width;
                            let scaleY = canvas.height / img.height;
                            let scale = Math.max(scaleX, scaleY);
                            img.set({
                                scaleX: scale,
                                scaleY: scale,
                                originX: 'center',
                                originY: 'center',
                                top: canvas.height / 2,
                                left: canvas.width / 2
                            });
                            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                            saveCanvasState();
                        }, { crossOrigin: 'anonymous' });
                    }
                }
            });
        });

        // Initial setup from database
        let initialJson = textPositionsInput.value;
        let isFabricJson = false;
        try {
            let parsed = JSON.parse(initialJson);
            if (parsed && typeof parsed === 'object' && !Array.isArray(parsed) && parsed.objects) {
                isFabricJson = true;
            }
        } catch (e) {
            isFabricJson = false;
        }

        if (initialJson && initialJson !== '{}' && initialJson !== '[]' && isFabricJson) {
            canvas.loadFromJSON(initialJson, function() {
                const checkedBg = document.querySelector('input[name="selected_background_id"]:checked');
                if (checkedBg) checkedBg.dispatchEvent(new Event('change'));
                
                canvas.renderAll();
                bindEvents();
            });
        } else {
            updatePreview();
            const checkedBg = document.querySelector('input[name="selected_background_id"]:checked');
            if (checkedBg) checkedBg.dispatchEvent(new Event('change'));
            bindEvents();
        }

        // --- Toolbar Logic ---
        canvas.on('selection:created', showToolbar);
        canvas.on('selection:updated', showToolbar);
        canvas.on('selection:cleared', hideToolbar);

        function showToolbar(e) {
            const activeObj = canvas.getActiveObject();
            if (activeObj && activeObj.type === 'textbox') {
                toolbar.style.display = 'flex';
                document.getElementById('tool_color').value = activeObj.fill || '#000000';
                document.getElementById('tool_font_family').value = activeObj.fontFamily || 'Arial';
                document.getElementById('tool_animation_type').value = activeObj.animType || 'none';
                document.getElementById('tool_animation_duration').value = activeObj.animDuration || '0.8';
                
                toolbar.style.top = '-60px';
                toolbar.style.left = '50%';
            }
        }

        function hideToolbar() {
            toolbar.style.display = 'none';
        }

        document.getElementById('tool_bold').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) {
                activeObj.set('fontWeight', activeObj.fontWeight === 'bold' ? 'normal' : 'bold');
                canvas.renderAll();
                saveCanvasState();
            }
        });
        
        document.getElementById('tool_italic').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) {
                activeObj.set('fontStyle', activeObj.fontStyle === 'italic' ? 'normal' : 'italic');
                canvas.renderAll();
                saveCanvasState();
            }
        });

        document.getElementById('tool_align_left').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('textAlign', 'left'); canvas.renderAll(); saveCanvasState(); }
        });
        document.getElementById('tool_align_center').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('textAlign', 'center'); canvas.renderAll(); saveCanvasState(); }
        });
        document.getElementById('tool_align_right').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('textAlign', 'right'); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_color').addEventListener('input', function(e) {
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('fill', this.value); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_font_family').addEventListener('change', function(e) {
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('fontFamily', this.value); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_size_up').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('fontSize', (activeObj.fontSize || 16) + 2); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_size_down').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('fontSize', Math.max(10, (activeObj.fontSize || 16) - 2)); canvas.renderAll(); saveCanvasState(); }
        });

        
        function playAnimationPreview(obj) {
            if (!obj) return;
            const origTop = obj.top;
            const dur = parseFloat(obj.animDuration || '0.8') * 1000;
            
            // Cancel existing animations if any
            
            if (obj.animType === 'fade-in') {
                obj.set({ opacity: 0 });
                obj.animate('opacity', 1, { onChange: canvas.renderAll.bind(canvas), duration: dur, easing: fabric.util.ease.easeOutQuad });
            } else if (obj.animType === 'slide-up') {
                obj.set({ top: origTop + 30, opacity: 0 });
                obj.animate('top', origTop, { onChange: canvas.renderAll.bind(canvas), duration: dur, easing: fabric.util.ease.easeOutQuad });
                obj.animate('opacity', 1, { duration: dur, easing: fabric.util.ease.easeOutQuad });
            } else if (obj.animType === 'slide-down') {
                obj.set({ top: origTop - 30, opacity: 0 });
                obj.animate('top', origTop, { onChange: canvas.renderAll.bind(canvas), duration: dur, easing: fabric.util.ease.easeOutQuad });
                obj.animate('opacity', 1, { duration: dur, easing: fabric.util.ease.easeOutQuad });
            } else if (obj.animType === 'zoom-in') {
                const origScale = obj.scaleX || 1;
                obj.set({ scaleX: origScale * 0.5, scaleY: origScale * 0.5, opacity: 0 });
                obj.animate('scaleX', origScale, { onChange: canvas.renderAll.bind(canvas), duration: dur, easing: fabric.util.ease.easeOutBack });
                obj.animate('scaleY', origScale, { duration: dur, easing: fabric.util.ease.easeOutBack });
                obj.animate('opacity', 1, { duration: dur, easing: fabric.util.ease.easeOutQuad });
            } else if (obj.animType === 'bounce') {
                obj.set({ top: origTop - 20 });
                obj.animate('top', origTop, { onChange: canvas.renderAll.bind(canvas), duration: dur, easing: fabric.util.ease.easeOutBounce });
            }
        }

        document.getElementById('tool_animation_type').addEventListener('change', function(e) {
            const activeObj = canvas.getActiveObject();
            if(activeObj) { 
                activeObj.animType = this.value; 
                saveCanvasState(); 
                playAnimationPreview(activeObj);
            }
        });

        
        document.getElementById('tool_animation_duration').addEventListener('input', function(e) {
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.animDuration = this.value; saveCanvasState(); }
        });
    });

    // Venue Modal Ajax logic
    document.getElementById('venueForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch("{{ route('host.venue.store') }}", {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                let select = document.getElementById('venue_dropdown');
                let option = new Option(data.venue_name, data.id, true, true);
                option.setAttribute('data-name', data.venue_name);
                select.add(option);
                select.dispatchEvent(new Event('change'));
                var modal = bootstrap.Modal.getInstance(document.getElementById('venueModal'));
                modal.hide();
            })
            .catch(err => alert("Error saving venue."));
    });
</script>
@endpush
@endsection