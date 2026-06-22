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
                            <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" id="wedding_date" name="wedding_date" class="form-control watch-input" required></div>
                            <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" id="wedding_time" name="wedding_time" class="form-control watch-input" required></div>
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
                            <input type="file" name="wedding_image" class="form-control" required>
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
                            <h4 class="ceremony-title draggable-text" id="preview_title" data-anim-type="none" data-anim-duration="0.8" style="color: #b02663; top: 10%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 2.2rem; font-family: 'Georgia', cursive, serif;">Wedding: Bride & Groom</h4>
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

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
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

        const prevTitle = document.getElementById('preview_title');
        const prevDate = document.getElementById('preview_date');
        const prevTime = document.getElementById('preview_time');
        const prevVenue = document.getElementById('preview_venue');
        const prevDateRow = document.getElementById('preview_date_row');
        const prevTimeRow = document.getElementById('preview_time_row');
        const prevVenueRow = document.getElementById('preview_venue_row');
        const prevCard = document.getElementById('preview_card');

        function updatePreview() {
            let bride = brideInput.value || 'Bride';
            let groom = groomInput.value || 'Groom';
            prevTitle.textContent = `Wedding: ${bride} & ${groom}`;
            
            if(dateInput && dateInput.value) {
                const dateObj = new Date(dateInput.value);
                prevDate.textContent = dateObj.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            } else {
                prevDate.textContent = 'Select a Date';
            }

            if(timeInput && timeInput.value) {
                let [h, m] = timeInput.value.split(':');
                let ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                prevTime.textContent = `${h}:${m} ${ampm}`;
            } else {
                prevTime.textContent = 'Select a Time';
            }

            if(venueSelect && venueSelect.selectedIndex > 0) {
                const opt = venueSelect.options[venueSelect.selectedIndex];
                prevVenue.textContent = opt.getAttribute('data-name') || 'Venue to be announced';
            }

            // Colors
            prevTitle.style.color = textColorInput.value;
            prevDateRow.style.color = detailsColorInput.value;
            prevTimeRow.style.color = detailsColorInput.value;
            prevVenueRow.style.color = detailsColorInput.value;

            customTexts['preview_title'] = prevTitle.innerHTML;
            customTexts['preview_date_row'] = prevDateRow.innerHTML;
            customTexts['preview_time_row'] = prevTimeRow.innerHTML;
            customTexts['preview_venue_row'] = prevVenueRow.innerHTML;
            customTextsInput.value = JSON.stringify(customTexts);
        }

        if(brideInput) brideInput.addEventListener('input', updatePreview);
        if(groomInput) groomInput.addEventListener('input', updatePreview);
        if(dateInput) dateInput.addEventListener('change', updatePreview);
        if(timeInput) timeInput.addEventListener('input', updatePreview);
        if(venueSelect) venueSelect.addEventListener('change', updatePreview);
        if(textColorInput) textColorInput.addEventListener('input', updatePreview);
        if(detailsColorInput) detailsColorInput.addEventListener('input', updatePreview);

        bgRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const img = this.closest('label').querySelector('img');
                    if(img) {
                        prevCard.style.backgroundImage = `url('${img.src}')`;
                    }
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
            start: function(event, ui) {},
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
                savePositions($(this));
            });
        });

        document.addEventListener('mousedown', function(e) {
            if (!$(e.target).closest('#preview_card').length && !$(e.target).closest('.canvas-toolbar').length) {
                toolbar.style.display = 'none';
                if (activeElement) {
                    activeElement.removeClass('editing-mode');
                    activeElement.removeClass('selected');
                    activeElement.removeAttr('contenteditable');
                }
                activeElement = null;
            }
        });

        // Toolbar Buttons
        document.getElementById('tool_bold').addEventListener('mousedown', function(e) {
            e.preventDefault();
            document.execCommand('bold', false, null);
            if(activeElement) {
                customTexts[activeElement.attr('id')] = activeElement.html();
                customTextsInput.value = JSON.stringify(customTexts);
            }
        });
        
        document.getElementById('tool_italic').addEventListener('mousedown', function(e) {
            e.preventDefault();
            document.execCommand('italic', false, null);
            if(activeElement) {
                customTexts[activeElement.attr('id')] = activeElement.html();
                customTextsInput.value = JSON.stringify(customTexts);
            }
        });

        document.getElementById('tool_align_left').addEventListener('click', function(e) {
            e.preventDefault();
            if(activeElement) { 
                activeElement.css({ 'left': '0%', 'transform': 'none', 'text-align': 'left' }); 
                savePositions(activeElement); 
            }
        });
        document.getElementById('tool_align_center').addEventListener('click', function(e) {
            e.preventDefault();
            if(activeElement) { 
                activeElement.css({ 'left': '50%', 'transform': 'translateX(-50%)', 'text-align': 'center' }); 
                savePositions(activeElement); 
            }
        });
        document.getElementById('tool_align_right').addEventListener('click', function(e) {
            e.preventDefault();
            if(activeElement) { 
                activeElement.css({ 'left': '100%', 'transform': 'translateX(-100%)', 'text-align': 'right' }); 
                savePositions(activeElement); 
            }
        });

        document.getElementById('tool_size_up').addEventListener('click', function(e) {
            e.preventDefault();
            if(activeElement) {
                let size = parseFloat(activeElement.css('font-size'));
                activeElement.css('font-size', (size + 2) + 'px');
                savePositions(activeElement);
            }
        });
        document.getElementById('tool_size_down').addEventListener('click', function(e) {
            e.preventDefault();
            if(activeElement) {
                let size = parseFloat(activeElement.css('font-size'));
                activeElement.css('font-size', (size - 2) + 'px');
                savePositions(activeElement);
            }
        });

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
        const pinLoad = document.getElementById('pin_load') || document.createElement('small');
        document.getElementById('q_v_pin').addEventListener('keyup', function() {
            let pin = this.value;
            if (pin.length === 6) {
                pinLoad.style.display = 'block';
                fetch(`https://api.postalpincode.in/pincode/${pin}`)
                    .then(res => res.json())
                    .then(data => {
                        pinLoad.style.display = 'none';
                        if (data[0].Status === "Success") {
                            let offices = data[0].PostOffice;
                            let area = document.getElementById('q_v_area');
                            area.innerHTML = '';
                            offices.forEach(o => {
                                area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                            });
                            document.getElementById('q_v_district').value = offices[0].District;
                            document.getElementById('q_v_state').value = offices[0].State;
                            document.getElementById('q_v_circle').value = offices[0].Circle;
                            document.getElementById('q_v_country').value = offices[0].Country;
                        }
                    });
            }
        });

        document.getElementById('quickVenueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            // map form names appropriately if backend expects different ones
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
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addVenueModal'));
                    modal.hide();
                })
                .catch(err => alert("Error saving venue."));
        });
    });
</script>
@endpush
@endsection