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
        background-repeat: no-repeat !important;
        background-position: center center !important;
        background-size: cover !important;
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnDesignOnCanva = document.getElementById('btn-design-on-canva');
        if (btnDesignOnCanva) {
            btnDesignOnCanva.addEventListener('click', function() {
                const tplInput = document.getElementById('canva_template_id');
                const templateId = tplInput ? tplInput.value.trim() : '';
                let url = '{{ route("canva.redirect") }}';
                if (templateId) {
                    url += '?template_id=' + encodeURIComponent(templateId);
                }
                window.location.href = url;
            });
        }

        // Background Live Preview Logic
        const bgSelectors = document.querySelectorAll('.bg-selector');
        const previewContainer = document.getElementById('live-preview-container');
        
        const nameInput = document.getElementById('ceramony_name');
        const dateInput = document.getElementById('ceramony_date');
        const timeInput = document.getElementById('ceramony_time');
        const venueSelect = document.getElementById('venue_select');
        const textColorInput = document.getElementById('text_color');
        const detailsColorInput = document.getElementById('details_color');
        const textPositionsInput = document.getElementById('text_positions');
        const toolbar = document.getElementById('canvas_toolbar');

        // Init Fabric Canvas
        const canvas = new fabric.Canvas('designCanvas', {
            preserveObjectStacking: true
        });

        fabric.Object.prototype.toObject = (function(toObject) {
            return function(propertiesToInclude) {
                return toObject.call(this, ['id', 'animType', 'animDuration'].concat(propertiesToInclude || []));
            };
        })(fabric.Object.prototype.toObject);

        let titleObj, dateObj, timeObj, venueObj;

        function addOrUpdateText(id, text, top, fontSize, color, fontFamily) {
            let existingObj = canvas.getObjects().find(o => o.id === id);
            if (existingObj) {
                existingObj.set({ text: text, fill: color });
            } else {
                let obj = new fabric.Textbox(text, {
                    id: id,
                    left: 225,
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
            let selectedBg = document.querySelector('.bg-selector:checked');
            if (selectedBg) {
                previewContainer.style.display = 'block';
            }
            
            let titleText = nameInput.value || 'Ceremony Name';
            let dateText = dateInput.value ? '📅 ' + new Date(dateInput.value).toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' }) : '📅 Date to be announced';
            
            let timeText = '⏰ Time to be announced';
            if (timeInput.value) {
                let [h, m] = timeInput.value.split(':');
                let ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                timeText = `⏰ ${h}:${m} ${ampm}`;
            }

            let venueText = '📍 Venue to be announced';
            if (venueSelect.selectedIndex > 0) {
                const opt = venueSelect.options[venueSelect.selectedIndex];
                venueText = '📍 ' + (opt.getAttribute('data-name') || 'Venue');
            }

            let tColor = textColorInput ? textColorInput.value : '#b02663';
            let dColor = detailsColorInput ? detailsColorInput.value : '#2b4c5e';

            titleObj = addOrUpdateText('preview_title', titleText, 100, 32, tColor, 'Georgia');
            dateObj = addOrUpdateText('preview_date_row', dateText, 250, 18, dColor, 'Arial');
            timeObj = addOrUpdateText('preview_time_row', timeText, 320, 18, dColor, 'Arial');
            venueObj = addOrUpdateText('preview_venue_row', venueText, 390, 18, dColor, 'Arial');

            canvas.renderAll();
            saveCanvasState();
        }

        function saveCanvasState() {
            textPositionsInput.value = JSON.stringify(canvas.toJSON());
        }

        canvas.on('object:modified', saveCanvasState);
        canvas.on('text:changed', saveCanvasState);

        bgSelectors.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.bg-img-preview').forEach(img => {
                    img.style.border = '1px solid #dee2e6';
                    img.style.boxShadow = 'none';
                });
                if(this.checked) {
                    this.nextElementSibling.style.border = '3px solid #0d6efd';
                    this.nextElementSibling.style.boxShadow = '0 0 10px rgba(13,110,253,0.5)';
                    
                    const imgSrc = this.dataset.image;
                    fabric.Image.fromURL(imgSrc, function(img) {
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
                updatePreview();
            });
            if(radio.checked) {
                radio.dispatchEvent(new Event('change'));
            }
        });

        nameInput.addEventListener('input', updatePreview);
        dateInput.addEventListener('change', updatePreview);
        timeInput.addEventListener('input', updatePreview);
        venueSelect.addEventListener('change', updatePreview);
        if(textColorInput) textColorInput.addEventListener('input', updatePreview);
        if(detailsColorInput) detailsColorInput.addEventListener('input', updatePreview);

        // Toolbar Logic
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
            if(activeObj) { activeObj.set('fontWeight', activeObj.fontWeight === 'bold' ? 'normal' : 'bold'); canvas.renderAll(); saveCanvasState(); }
        });
        
        document.getElementById('tool_italic').addEventListener('click', function(e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if(activeObj) { activeObj.set('fontStyle', activeObj.fontStyle === 'italic' ? 'normal' : 'italic'); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_align_left').addEventListener('click', function(e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('textAlign', 'left'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_center').addEventListener('click', function(e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('textAlign', 'center'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_right').addEventListener('click', function(e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('textAlign', 'right'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_color').addEventListener('input', function(e) { const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('fill', this.value); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_font_family').addEventListener('change', function(e) { const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('fontFamily', this.value); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_size_up').addEventListener('click', function(e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('fontSize', (activeObj.fontSize || 16) + 2); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_size_down').addEventListener('click', function(e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.set('fontSize', Math.max(10, (activeObj.fontSize || 16) - 2)); canvas.renderAll(); saveCanvasState(); } });
        
        document.getElementById('tool_animation_type').addEventListener('change', function(e) { const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.animType = this.value; saveCanvasState(); } });
        document.getElementById('tool_animation_duration').addEventListener('input', function(e) { const activeObj = canvas.getActiveObject(); if(activeObj) { activeObj.animDuration = this.value; saveCanvasState(); } });

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
@endpush
@endsection