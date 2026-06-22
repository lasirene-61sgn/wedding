@extends('layouts.host')

@section('content')
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
        background-repeat: no-repeat !important;
        background-position: center center !important;
        background-size: cover !important;
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
        border: 2px solid rgba(0, 123, 255, 0.5); /* Blue border for selected */
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

<div class="container mt-4">
    <div class="row">
        <!-- Form Side -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">Create New Ceremony</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('host.ceramony.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block"><strong>Select Guest Panel Background Theme</strong></label>
                            <div class="row g-3">
                                @foreach($backgrounds as $bg)
                                <div class="col-6 col-md-3">
                                    <label class="card h-100 text-center border p-2 position-relative cursor-pointer">
                                        <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="position-absolute top-0 start-0 m-2 bg-radio" data-url="{{ asset('storage/' . $bg->image_path) }}"
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

                        <div class="mb-3">
                            <label class="form-label">Ceremony Name</label>
                            <input type="text" name="ceramony_name" id="ceramony_name" class="form-control" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="ceramony_date" id="ceramony_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Time</label>
                                <input type="time" name="ceramony_time" id="ceramony_time" class="form-control">
                            </div>
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

                        <div class="mb-4">
                            <label class="form-label fw-bold">Banner Image</label>
                            <input type="file" name="ceramony_image" class="form-control">
                        </div>
                        
                        <input type="hidden" name="text_positions" id="text_positions" value="{}">
                        <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{}">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Create Ceremony</button>
                        </div>
                    </form>
                </div>
            </div>
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
                        <button type="button" id="tool_bold" title="Bold">B</button>
                        <button type="button" id="tool_italic" title="Italic"><i>I</i></button>
                        <div class="vr mx-1"></div>
                        <button type="button" id="tool_size_down" title="Decrease Font Size">A-</button>
                        <button type="button" id="tool_size_up" title="Increase Font Size">A+</button>
                        <div class="vr mx-1"></div>
                        <button type="button" id="tool_align_left" title="Align Left">⬅</button>
                        <button type="button" id="tool_align_center" title="Center">↔</button>
                        <button type="button" id="tool_align_right" title="Align Right">➡</button>
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
                        <div class="card-content" id="preview_card_inner">
                            <h4 class="ceremony-title draggable-text" id="preview_title" data-anim-type="none" data-anim-duration="0.8" style="color: #b02663; top: 10%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 2.2rem; font-family: 'Georgia', cursive, serif;">
                                Ceremony Name
                            </h4>
                            
                            <div class="details-row date-row draggable-text" id="preview_date_row" data-anim-type="none" data-anim-duration="0.8" style="color: #2b4c5e; top: 30%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 1.1rem; font-family: 'Arial', sans-serif;">
                                <span>📅</span> <span id="preview_date">Select a Date</span>
                            </div>
                            
                            <div class="details-row time-row draggable-text" id="preview_time_row" data-anim-type="none" data-anim-duration="0.8" style="color: #2b4c5e; top: 45%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 1.1rem; font-family: 'Arial', sans-serif;">
                                <span>⏰</span> <span id="preview_time">Select a Time</span>
                            </div>
                            
                            <div class="details-row venue-row draggable-text" id="preview_venue_row" data-anim-type="none" data-anim-duration="0.8" style="color: #2b4c5e; top: 60%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 1.1rem; font-family: 'Arial', sans-serif;">
                                <span>📍</span> <span id="preview_venue">Venue to be announced</span>
                            </div>
                        </div>
                    </div>
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

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nameInput = document.getElementById('ceramony_name');
        const dateInput = document.getElementById('ceramony_date');
        const timeInput = document.getElementById('ceramony_time');
        const venueSelect = document.getElementById('venue_select');
        const textColorInput = document.getElementById('text_color');
        const detailsColorInput = document.getElementById('details_color');
        const bgRadios = document.querySelectorAll('.bg-radio');

        const prevTitle = document.getElementById('preview_title');
        const prevDate = document.getElementById('preview_date');
        const prevTime = document.getElementById('preview_time');
        const prevVenue = document.getElementById('preview_venue');
        const prevDateRow = document.getElementById('preview_date_row');
        const prevTimeRow = document.getElementById('preview_time_row');
        const prevVenueRow = document.getElementById('preview_venue_row');
        const prevCard = document.getElementById('preview_card');

        function updatePreview() {
            prevTitle.textContent = nameInput.value || 'Ceremony Name';
            
            if(dateInput.value) {
                const dateObj = new Date(dateInput.value);
                prevDate.textContent = dateObj.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            } else {
                prevDate.textContent = 'Select a Date';
            }

            if(timeInput.value) {
                // simple 12h formatting
                let [h, m] = timeInput.value.split(':');
                let ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                prevTime.textContent = `${h}:${m} ${ampm}`;
            } else {
                prevTime.textContent = 'Select a Time';
            }

            if(venueSelect.selectedIndex >= 0) {
                const opt = venueSelect.options[venueSelect.selectedIndex];
                prevVenue.textContent = opt.getAttribute('data-name') || 'Venue to be announced';
            }

            // Colors
            prevTitle.style.color = textColorInput.value;
            prevDateRow.style.color = detailsColorInput.value;
            prevTimeRow.style.color = detailsColorInput.value;
            prevVenueRow.style.color = detailsColorInput.value;

            // Update custom texts so the backend receives the freshest preview content
            customTexts['preview_title'] = prevTitle.innerHTML;
            customTexts['preview_date_row'] = prevDateRow.innerHTML;
            customTexts['preview_time_row'] = prevTimeRow.innerHTML;
            customTexts['preview_venue_row'] = prevVenueRow.innerHTML;
            customTextsInput.value = JSON.stringify(customTexts);
        }

        nameInput.addEventListener('input', updatePreview);
        dateInput.addEventListener('change', updatePreview);
        timeInput.addEventListener('input', updatePreview);
        venueSelect.addEventListener('change', updatePreview);
        textColorInput.addEventListener('input', updatePreview);
        detailsColorInput.addEventListener('input', updatePreview);

        bgRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    prevCard.style.backgroundImage = `url('${this.getAttribute('data-url')}')`;
                }
            });
        });

        // --- Draggable Logic via jQuery UI ---
        const textPositionsInput = document.getElementById('text_positions');
        let parsedPositions = textPositionsInput.value ? JSON.parse(textPositionsInput.value) : {};
        let currentPositions = Array.isArray(parsedPositions) ? {} : parsedPositions;

        function savePositions(element) {
            const innerContainer = $('#preview_card_inner');
            const posLeft = element[0].style.left || '50%';
            const posTop = element[0].style.top || '0%';
            const transform = element[0].style.transform || 'none';
            const textAlign = element.css('text-align') || 'center';
            const fontSize = element.css('font-size') || '1rem';
            const color = element.css('color') || '#000';
            const fontFamily = element.css('font-family') || "'Arial', sans-serif";
            const animationType = element.attr('data-anim-type') || 'none';
            const animationDuration = element.attr('data-anim-duration') || '0.8';

            const id = element.attr('id');
            currentPositions[id] = {
                left: posLeft,
                top: posTop,
                transform: transform,
                textAlign: textAlign,
                fontSize: fontSize,
                color: color,
                fontFamily: fontFamily,
                animationType: animationType,
                animationDuration: animationDuration
            };
            
            textPositionsInput.value = JSON.stringify(currentPositions);
        }

        $('.draggable-text').draggable({
            containment: '#preview_card_inner',
            cancel: '.editing-mode',
            stop: function(event, ui) {
                const innerContainer = $('#preview_card_inner');
                const element = $(this);
                const parentWidth = innerContainer.width();
                const parentHeight = innerContainer.height();
                const leftPercent = ((ui.position.left / parentWidth) * 100).toFixed(2) + '%';
                const topPercent = ((ui.position.top / parentHeight) * 100).toFixed(2) + '%';
                element.css({ 'left': leftPercent, 'top': topPercent, 'transform': 'none' });
                savePositions(element);
            }
        });

        // --- Canva Content Editable & Toolbar Logic ---
        const customTextsInput = document.getElementById('custom_canvas_texts');
        let parsedTexts = customTextsInput.value ? JSON.parse(customTextsInput.value) : {};
        let customTexts = Array.isArray(parsedTexts) ? {} : parsedTexts;
        const toolbar = document.getElementById('canvas_toolbar');
        let activeElement = null;

        document.querySelectorAll('.draggable-text').forEach(el => {
            el.addEventListener('mouseenter', function() {
                if (this.style.transform && this.style.transform.includes('translateX')) {
                    const jqEl = $(this);
                    const offset = jqEl.position();
                    const parentWidth = $('#preview_card_inner').width();
                    const parentHeight = $('#preview_card_inner').height();
                    const leftPercent = ((offset.left / parentWidth) * 100).toFixed(2) + '%';
                    const topPercent = ((offset.top / parentHeight) * 100).toFixed(2) + '%';
                    jqEl.css({ 'transform': 'none', 'left': leftPercent, 'top': topPercent });
                }
            });

            el.addEventListener('click', function(e) {
                document.querySelectorAll('.draggable-text').forEach(t => t.classList.remove('selected'));
                $(this).addClass('selected');
                activeElement = $(this);
                toolbar.style.display = 'flex';
                
                function rgbToHex(rgb) {
                    if(!rgb) return '#000000';
                    let a = rgb.split("(")[1].split(")")[0].split(",");
                    return "#" + a.map(x => {
                        x = parseInt(x).toString(16);
                        return (x.length==1) ? "0"+x : x;
                    }).join("");
                }
                const color = activeElement.css('color');
                if (color && color.startsWith('rgb')) {
                    document.getElementById('tool_color').value = rgbToHex(color);
                }
                const fontVal = activeElement.css('font-family');
                if(fontVal) document.getElementById('tool_font_family').value = fontVal.replace(/"/g, "'");
                const animType = activeElement.attr('data-anim-type') || 'none';
                document.getElementById('tool_animation_type').value = animType;
                const animDur = activeElement.attr('data-anim-duration') || '0.8';
                document.getElementById('tool_animation_duration').value = animDur;
            });

            el.addEventListener('dblclick', function(e) {
                $(this).addClass('editing-mode');
                $(this).attr('contenteditable', 'true');
                $(this).focus();
            });

            el.addEventListener('input', function() {
                const id = this.getAttribute('id');
                customTexts[id] = this.innerHTML;
                customTextsInput.value = JSON.stringify(customTexts);
                if (id === 'preview_title') {
                    document.getElementById('ceramony_name').value = this.innerText;
                }
                savePositions($(this));
            });
        });

        document.addEventListener('mousedown', function(e) {
            if (!$(e.target).closest('#preview_card').length && !$(e.target).closest('.canvas-toolbar').length) {
                toolbar.style.display = 'none';
                if (activeElement) {
                    activeElement.removeClass('editing-mode').removeClass('selected').removeAttr('contenteditable');
                }
                activeElement = null;
            }
        });

        document.getElementById('tool_bold').addEventListener('mousedown', (e) => { e.preventDefault(); document.execCommand('bold', false, null); });
        document.getElementById('tool_italic').addEventListener('mousedown', (e) => { e.preventDefault(); document.execCommand('italic', false, null); });

        document.getElementById('tool_align_left').addEventListener('click', (e) => { e.preventDefault(); if(activeElement) { activeElement.css({ 'left': '0%', 'transform': 'none', 'text-align': 'left' }); savePositions(activeElement); } });
        document.getElementById('tool_align_center').addEventListener('click', (e) => { e.preventDefault(); if(activeElement) { activeElement.css({ 'left': '50%', 'transform': 'translateX(-50%)', 'text-align': 'center' }); savePositions(activeElement); } });
        document.getElementById('tool_align_right').addEventListener('click', (e) => { e.preventDefault(); if(activeElement) { activeElement.css({ 'left': '100%', 'transform': 'translateX(-100%)', 'text-align': 'right' }); savePositions(activeElement); } });

        document.getElementById('tool_size_up').addEventListener('click', (e) => { e.preventDefault(); if(activeElement) { let size = parseFloat(activeElement.css('font-size')); activeElement.css('font-size', (size + 2) + 'px'); savePositions(activeElement); } });
        document.getElementById('tool_size_down').addEventListener('click', (e) => { e.preventDefault(); if(activeElement) { let size = parseFloat(activeElement.css('font-size')); activeElement.css('font-size', (size - 2) + 'px'); savePositions(activeElement); } });

        document.getElementById('tool_color').addEventListener('input', function() {
            if(activeElement) {
                activeElement.css('color', this.value);
                savePositions(activeElement);
            }
        });

        document.getElementById('tool_font_family').addEventListener('change', function() {
            if(activeElement) {
                activeElement.css('font-family', this.value);
                savePositions(activeElement);
            }
        });

        document.getElementById('tool_animation_type').addEventListener('change', function() {
            if(activeElement) {
                activeElement.attr('data-anim-type', this.value);
                savePositions(activeElement);
            }
        });

        document.getElementById('tool_animation_duration').addEventListener('input', function() {
            if(activeElement) {
                activeElement.attr('data-anim-duration', this.value);
                savePositions(activeElement);
            }
        });

        // --- Venue Modal Logic ---
        document.getElementById('v_pincode').addEventListener('keyup', function() {
            let pin = this.value;
            if (pin.length === 6) {
                document.getElementById('pin_load').style.display = 'block';
                fetch(`https://api.postalpincode.in/pincode/${pin}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('pin_load').style.display = 'none';
                        if (data[0].Status === "Success") {
                            let offices = data[0].PostOffice;
                            let area = document.getElementById('v_area');
                            area.innerHTML = '';
                            offices.forEach(o => {
                                area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                            });
                            document.getElementById('v_district').value = offices[0].District;
                            document.getElementById('v_state').value = offices[0].State;
                            document.getElementById('v_circle').value = offices[0].Circle;
                            document.getElementById('v_country').value = offices[0].Country;
                        }
                    });
            }
        });

        document.getElementById('saveVenueBtn').addEventListener('click', function() {
            let formData = new FormData(document.getElementById('quickVenueForm'));
            fetch("{{ route('host.venue.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('pin_load').style.display = 'none';
                    if (data[0].Status === "Success") {
                        let offices = data[0].PostOffice;
                        let area = document.getElementById('v_area');
                        area.innerHTML = '';
                        offices.forEach(o => {
                            area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                        });
                        document.getElementById('v_district').value = offices[0].District;
                        document.getElementById('v_state').value = offices[0].State;
                        document.getElementById('v_circle').value = offices[0].Circle;
                        document.getElementById('v_country').value = offices[0].Country;
                    }
                });
        }
    });

    // AJAX TO SAVE VENUE AND AUTO-SELECT IT
    document.getElementById('saveVenueBtn').addEventListener('click', function() {
        let formData = new FormData(document.getElementById('quickVenueForm'));

        fetch("{{ route('host.venue.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.json())
            .then(data => {
                // Add the new venue to the dropdown and select it
                let select = document.getElementById('venue_select');
                let option = new Option(data.venue_name, data.id, true, true);
                option.setAttribute('data-name', data.venue_name);
                select.add(option);
                select.dispatchEvent(new Event('change'));

                // Close modal
                var myModalEl = document.getElementById('addVenueModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            })
            .catch(err => alert("Error saving venue. Make sure all fields are filled."));
    });
});
</script>
@endpush
@endsection