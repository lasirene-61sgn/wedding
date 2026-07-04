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
                        
                        <input type="hidden" name="text_positions" id="text_positions" value="{{ is_string($ceramony->text_positions) ? $ceramony->text_positions : json_encode($ceramony->text_positions ?? []) }}">
                        <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{{ is_string($ceramony->custom_canvas_texts) ? $ceramony->custom_canvas_texts : json_encode($ceramony->custom_canvas_texts ?? []) }}">

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
                    <div class="ceremony-card-preview" id="preview_card" style="{{ $ceramony->background ? 'background-image: url(' . asset('storage/' . $ceramony->background->image_path) . ');' : '' }}">
                        <canvas id="designCanvas" width="450" height="600"></canvas>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nameInput = document.getElementById('ceramony_name');
        const dateInput = document.getElementById('ceramony_date');
        const timeInput = document.getElementById('ceramony_time');
        const venueSelect = document.getElementById('venue_select');
        const textColorInput = document.getElementById('text_color');
        const detailsColorInput = document.getElementById('details_color');
        const bgRadios = document.querySelectorAll('.bg-radio');
        const textPositionsInput = document.getElementById('text_positions');
        const customTextsInput = document.getElementById('custom_canvas_texts');
        const toolbar = document.getElementById('canvas_toolbar');

        // Init Fabric Canvas
        const canvas = new fabric.Canvas('designCanvas', { preserveObjectStacking: true });

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
                    id: id, left: 225, top: top, originX: 'center', originY: 'center',
                    fontSize: fontSize, fill: color, fontFamily: fontFamily, textAlign: 'center',
                    width: 400, animType: 'none', animDuration: '0.8',
                    transparentCorners: false, cornerColor: '#007bff', cornerSize: 10, borderColor: '#007bff'
                });
                canvas.add(obj);
                return obj;
            }
            return existingObj;
        }

        function updatePreview() {
            let titleText = nameInput && nameInput.value ? nameInput.value : 'Ceremony Name';
            
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

            addOrUpdateText('preview_title', titleText, 100, 32, tColor, 'Georgia');
            addOrUpdateText('preview_date_row', dateText, 250, 18, dColor, 'Arial');
            addOrUpdateText('preview_time_row', timeText, 320, 18, dColor, 'Arial');
            addOrUpdateText('preview_venue_row', venueText, 390, 18, dColor, 'Arial');

            canvas.renderAll();
            saveCanvasState();
        }

        function saveCanvasState() {
            textPositionsInput.value = JSON.stringify(canvas.toJSON());
            
            let texts = {};
            canvas.getObjects().forEach(obj => {
                if(obj.id) texts[obj.id] = obj.text;
            });
            customTextsInput.value = JSON.stringify(texts);
        }

        canvas.on('object:modified', saveCanvasState);
        canvas.on('text:changed', saveCanvasState);

        function bindEvents() {
            if(nameInput) nameInput.addEventListener('input', updatePreview);
            if(dateInput) dateInput.addEventListener('change', updatePreview);
            if(timeInput) timeInput.addEventListener('input', updatePreview);
            if(venueSelect) venueSelect.addEventListener('change', updatePreview);
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

        bgRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const src = this.getAttribute('data-url');
                    if(src) {
                        fabric.Image.fromURL(src, function(img) {
                            let scaleX = canvas.width / img.width;
                            let scaleY = canvas.height / img.height;
                            let scale = Math.max(scaleX, scaleY);
                            img.set({
                                scaleX: scale, scaleY: scale,
                                originX: 'center', originY: 'center',
                                top: canvas.height / 2, left: canvas.width / 2
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
        function hideToolbar() { toolbar.style.display = 'none'; }

        document.getElementById('tool_bold').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('fontWeight', obj.fontWeight === 'bold' ? 'normal' : 'bold'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_italic').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('fontStyle', obj.fontStyle === 'italic' ? 'normal' : 'italic'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_left').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('textAlign', 'left'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_center').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('textAlign', 'center'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_right').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('textAlign', 'right'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_color').addEventListener('input', function(e) { const obj = canvas.getActiveObject(); if(obj) { obj.set('fill', this.value); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_font_family').addEventListener('change', function(e) { const obj = canvas.getActiveObject(); if(obj) { obj.set('fontFamily', this.value); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_size_up').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('fontSize', (obj.fontSize || 16) + 2); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_size_down').addEventListener('click', function(e) { e.preventDefault(); const obj = canvas.getActiveObject(); if(obj) { obj.set('fontSize', Math.max(10, (obj.fontSize || 16) - 2)); canvas.renderAll(); saveCanvasState(); } });
        
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

        document.getElementById('tool_animation_duration').addEventListener('input', function(e) { const obj = canvas.getActiveObject(); if(obj) { obj.animDuration = this.value; saveCanvasState(); } });
    });

    document.getElementById('saveVenueBtn').addEventListener('click', function(e) {
        e.preventDefault();
        let formData = new FormData(document.getElementById('quickVenueForm'));
        fetch("{{ route('host.venue.store') }}", {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                let select = document.getElementById('venue_select');
                let option = new Option(data.venue_name, data.id, true, true);
                option.setAttribute('data-name', data.venue_name);
                select.add(option);
                select.dispatchEvent(new Event('change'));
                var modal = bootstrap.Modal.getInstance(document.getElementById('addVenueModal'));
                modal.hide();
            })
            .catch(err => alert("Error saving venue."));
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
</script>
@endpush
@endsection