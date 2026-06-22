@extends('layouts.host')

@section('content')
<style>
    /* Hides the ugly default radio button dot */
    .cursor-pointer input[type="radio"] {
        opacity: 0;
        width: 0;
        height: 0;
    }
    /* Changes the card border smoothly when selected */
    .cursor-pointer:has(input[type="radio"]:checked) {
        border: 2px solid var(--pink-primary, #d63384) !important;
        background-color: #fff5f8;
        box-shadow: 0 4px 12px rgba(214, 51, 132, 0.15);
    }
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

                        <div class="mb-3">
                            <label class="form-label d-block fw-bold">Select Guest Panel Background Theme</label>
                            <div class="row g-3">
                                @foreach($backgrounds as $bg)
                                <div class="col-6 col-md-3">
                                    <label class="card h-100 text-center border p-2 position-relative cursor-pointer">
                                        <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="position-absolute top-0 start-0 m-2 bg-radio" data-url="{{ asset('storage/' . $bg->image_path) }}"
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

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Title Text Color</label>
                                <input type="color" name="text_color" id="text_color" class="form-control form-control-color w-100" value="{{ $ceramony->text_color ?? '#b02663' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Details Text Color</label>
                                <input type="color" name="details_color" id="details_color" class="form-control form-control-color w-100" value="{{ $ceramony->details_color ?? '#2b4c5e' }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Banner Image</label>
                            @if($ceramony->ceramony_image)
                            <div class="mb-2"><img src="{{ asset('storage/'.$ceramony->ceramony_image) }}" width="100" class="rounded border"></div>
                            @endif
                            <input type="file" name="ceramony_image" class="form-control">
                        </div>
                        
                        <input type="hidden" name="text_positions" id="text_positions" value="{{ json_encode($ceramony->text_positions ?? []) }}">
                        <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{{ json_encode($ceramony->custom_canvas_texts ?? []) }}">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-success px-5 fw-bold">Save Changes</button>
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
                    <div class="ceremony-card-preview" id="preview_card" style="{{ $ceramony->background ? 'background-image: url(' . asset('storage/' . $ceramony->background->image_path) . ');' : '' }}">
                        <div class="card-content" id="preview_card_inner">
                            @php
                                $pos = $ceramony->text_positions ?? [];
                                $customTexts = $ceramony->custom_canvas_texts ?? [];

                                $defaults = [
                                    'preview_title' => ['top' => '10%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '2.2rem'],
                                    'preview_date_row' => ['top' => '30%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.1rem'],
                                    'preview_time_row' => ['top' => '45%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.1rem'],
                                    'preview_venue_row' => ['top' => '60%', 'left' => '50%', 'transform' => 'translateX(-50%)', 'textAlign' => 'center', 'fontSize' => '1.1rem']
                                ];

                                $title_pos = array_merge($defaults['preview_title'], $pos['preview_title'] ?? []);
                                $date_pos = array_merge($defaults['preview_date_row'], $pos['preview_date_row'] ?? []);
                                $time_pos = array_merge($defaults['preview_time_row'], $pos['preview_time_row'] ?? []);
                                $venue_pos = array_merge($defaults['preview_venue_row'], $pos['preview_venue_row'] ?? []);
                            @endphp

                            <h4 class="ceremony-title draggable-text" id="preview_title" data-anim-type="{{ $title_pos['animationType'] ?? 'none' }}" data-anim-duration="{{ $title_pos['animationDuration'] ?? '0.8' }}" style="color: {{ $title_pos['color'] ?? ($ceramony->text_color ?? '#b02663') }}; top: {{ $title_pos['top'] }}; left: {{ $title_pos['left'] }}; transform: {{ $title_pos['transform'] ?? 'none' }}; text-align: {{ $title_pos['textAlign'] ?? 'center' }}; font-size: {{ $title_pos['fontSize'] ?? '2.2rem' }}; font-family: {{ $title_pos['fontFamily'] ?? "'Georgia', cursive, serif" }};">
                                {!! $customTexts['preview_title'] ?? $ceramony->ceramony_name !!}
                            </h4>
                            
                            <div class="details-row date-row draggable-text" id="preview_date_row" data-anim-type="{{ $date_pos['animationType'] ?? 'none' }}" data-anim-duration="{{ $date_pos['animationDuration'] ?? '0.8' }}" style="color: {{ $date_pos['color'] ?? ($ceramony->details_color ?? '#2b4c5e') }}; top: {{ $date_pos['top'] }}; left: {{ $date_pos['left'] }}; transform: {{ $date_pos['transform'] ?? 'none' }}; text-align: {{ $date_pos['textAlign'] ?? 'center' }}; font-size: {{ $date_pos['fontSize'] ?? '1.1rem' }}; font-family: {{ $date_pos['fontFamily'] ?? "'Arial', sans-serif" }};">
                                {!! $customTexts['preview_date_row'] ?? '<span>📅</span> <span id="preview_date">' . ($ceramony->ceramony_date ? \Carbon\Carbon::parse($ceramony->ceramony_date)->format('l, d F Y') : 'Select a Date') . '</span>' !!}
                            </div>
                            
                            <div class="details-row time-row draggable-text" id="preview_time_row" data-anim-type="{{ $time_pos['animationType'] ?? 'none' }}" data-anim-duration="{{ $time_pos['animationDuration'] ?? '0.8' }}" style="color: {{ $time_pos['color'] ?? ($ceramony->details_color ?? '#2b4c5e') }}; top: {{ $time_pos['top'] }}; left: {{ $time_pos['left'] }}; transform: {{ $time_pos['transform'] ?? 'none' }}; text-align: {{ $time_pos['textAlign'] ?? 'center' }}; font-size: {{ $time_pos['fontSize'] ?? '1.1rem' }}; font-family: {{ $time_pos['fontFamily'] ?? "'Arial', sans-serif" }};">
                                {!! $customTexts['preview_time_row'] ?? '<span>⏰</span> <span id="preview_time">' . ($ceramony->ceramony_time ? \Carbon\Carbon::parse($ceramony->ceramony_time)->format('h:i A') : 'Select a Time') . '</span>' !!}
                            </div>
                            
                            <div class="details-row venue-row draggable-text" id="preview_venue_row" data-anim-type="{{ $venue_pos['animationType'] ?? 'none' }}" data-anim-duration="{{ $venue_pos['animationDuration'] ?? '0.8' }}" style="color: {{ $venue_pos['color'] ?? ($ceramony->details_color ?? '#2b4c5e') }}; top: {{ $venue_pos['top'] }}; left: {{ $venue_pos['left'] }}; transform: {{ $venue_pos['transform'] ?? 'none' }}; text-align: {{ $venue_pos['textAlign'] ?? 'center' }}; font-size: {{ $venue_pos['fontSize'] ?? '1.1rem' }}; font-family: {{ $venue_pos['fontFamily'] ?? "'Arial', sans-serif" }};">
                                {!! $customTexts['preview_venue_row'] ?? '<span>📍</span> <span id="preview_venue">' . ($ceramony->venue ? $ceramony->venue->venue_name : 'Venue to be announced') . '</span>' !!}
                            </div>
                        </div>
                    </div>
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

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Live Preview Logic ---
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
            cancel: '.editing-mode', // Do not drag when in editing mode
            start: function(event, ui) {
                // Let draggable do its thing natively
            },
            stop: function(event, ui) {
                const innerContainer = $('#preview_card_inner');
                const element = $(this);
                
                const parentWidth = innerContainer.width();
                const parentHeight = innerContainer.height();
                
                const leftPercent = ((ui.position.left / parentWidth) * 100).toFixed(2) + '%';
                const topPercent = ((ui.position.top / parentHeight) * 100).toFixed(2) + '%';
                
                element.css({
                    'left': leftPercent,
                    'top': topPercent,
                    'transform': 'none'
                });

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

            // Convert transform to pure position on hover to avoid drag jump
            el.addEventListener('mouseenter', function() {
                if (this.style.transform && this.style.transform.includes('translateX')) {
                    const jqEl = $(this);
                    const offset = jqEl.position();
                    const parentWidth = $('#preview_card_inner').width();
                    const parentHeight = $('#preview_card_inner').height();
                    
                    const leftPercent = ((offset.left / parentWidth) * 100).toFixed(2) + '%';
                    const topPercent = ((offset.top / parentHeight) * 100).toFixed(2) + '%';

                    jqEl.css({
                        'transform': 'none',
                        'left': leftPercent,
                        'top': topPercent
                    });
                }
            });

            // Single click to show toolbar and select (but retain dragging capability)
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

            // Double click to actually edit text
            el.addEventListener('dblclick', function(e) {
                $(this).addClass('editing-mode');
                $(this).attr('contenteditable', 'true');
                $(this).focus();
            });

            el.addEventListener('input', function() {
                const id = this.getAttribute('id');
                customTexts[id] = this.innerHTML; // Save exact HTML including spans
                customTextsInput.value = JSON.stringify(customTexts);
                
                // If they edit the title, sync it to the form input on the left
                if (id === 'preview_title') {
                    document.getElementById('ceramony_name').value = this.innerText;
                }
                savePositions($(this));
            });

            el.addEventListener('blur', function() {
                // We don't remove editing-mode immediately on blur because clicking toolbar would blur the text
                // We handle removing editing-mode in the mousedown outside listener below
            });
        });

        // Hide toolbar and restore dragging when clicking outside canvas
        document.addEventListener('mousedown', function(e) {
            if (!$(e.target).closest('#preview_card').length && !$(e.target).closest('.canvas-toolbar').length) {
                toolbar.style.display = 'none';
                if (activeElement) {
                    activeElement.removeClass('editing-mode'); // Re-enable drag
                    activeElement.removeClass('selected');
                    activeElement.removeAttr('contenteditable');
                }
                activeElement = null;
            }
        });

        // Toolbar Button Actions
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
                // Using left 100% and translateX(-100%) cleanly aligns it to the right boundary
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
    });
</script>
<script>

        // --- Venue Modal Logic ---
        const modalElement = document.getElementById('venueModal');
        const venueModal = new bootstrap.Modal(modalElement);

        document.getElementById('new_venue_btn').addEventListener('click', () => {
            document.getElementById('venueForm').reset();
            document.getElementById('v_id').value = '';
            document.getElementById('v_area').innerHTML = '';
            document.getElementById('modalTitle').innerText = "Add New Venue";
            venueModal.show();
        });

        document.getElementById('edit_venue_btn').addEventListener('click', () => {
            const select = document.getElementById('venue_select');
            const opt = select.options[select.selectedIndex];

            if (!select.value) return alert("Please select a venue from the list first!");

            document.getElementById('v_id').value = select.value;
            document.getElementById('v_name').value = opt.dataset.name || '';
            document.getElementById('v_address').value = opt.dataset.address || '';
            document.getElementById('v_pincode').value = opt.dataset.pin || '';
            document.getElementById('v_district').value = opt.dataset.district || '';
            document.getElementById('v_state').value = opt.dataset.state || '';
            document.getElementById('v_country').value = opt.dataset.country || 'India';
            document.getElementById('v_circle').value = opt.dataset.circle || '';
            document.getElementById('v_wedding_location').value = opt.dataset.landmark || '';
            document.getElementById('v_location_map').value = opt.dataset.map || '';

            const areaVal = opt.dataset.area;
            const areaSelect = document.getElementById('v_area');
            areaSelect.innerHTML = areaVal ? `<option value="${areaVal}" selected>${areaVal}</option>` : '';

            document.getElementById('modalTitle').innerText = "Update Venue Details";
            venueModal.show();
        });

        document.getElementById('v_pincode').addEventListener('keyup', function() {
            if (this.value.length === 6) {
                fetch(`https://api.postalpincode.in/pincode/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data[0].Status === "Success") {
                            let offices = data[0].PostOffice;
                            let area = document.getElementById('v_area');
                            area.innerHTML = '';
                            offices.forEach(o => {
                                area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                            });
                            document.getElementById('v_district').value = offices[0].District;
                            document.getElementById('v_state').value = offices[0].State;
                            document.getElementById('v_country').value = offices[0].Country;
                            document.getElementById('v_circle').value = offices[0].Circle;
                        }
                    });
            }
        });

        document.getElementById('saveVenueBtn').addEventListener('click', function() {
            const form = document.getElementById('venueForm');
            const formData = new FormData(form);
            const id = document.getElementById('v_id').value;

            let url = id ? `/host/venue/update/${id}` : "{{ route('host.venue.store') }}";

            if (id) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.id) {
                        location.reload();
                    } else {
                        alert("Error saving venue. Check console.");
                        console.log(data);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Something went wrong!");
                });
        });
    });
</script>
@endpush
@endsection