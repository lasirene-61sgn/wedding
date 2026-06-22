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
        text-shadow: 1.5px 1.5px 3px rgba(255, 255, 255, 1), -1.5px -1.5px 3px rgba(255, 255, 255, 1);
        font-weight: 600;
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
    .canvas-toolbar input[type="color"] {
        border: none;
        width: 30px;
        height: 30px;
        padding: 0;
        cursor: pointer;
        border-radius: 4px;
    }
</style>
@section('content')
<div class="container-fluid max-width-lg py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-success"></i>Create Host Family Details</h5>
        </div>
    </div>
    
    <div class="row">
        <!-- FORM SECTION -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 p-4">
                <form action="{{ route('host.hfamily.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4 bg-light p-3 border rounded shadow-sm">
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input cursor-pointer" type="checkbox" name="is_active" id="isActiveToggle" value="1" checked>
                            <label class="form-check-label fw-bold text-dark cursor-pointer" for="isActiveToggle">
                                Show on Guest Panel
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">If turned off, this entire family section will remain completely hidden from your guests' invitation dashboards.</small>
                    </div>
                    
                    <!-- Theme Selection Group Component -->
                    <div class="mb-4">
                        <label class="form-label d-block mb-3"><strong>Select Guest Panel Background Theme</strong></label>
                        <div class="row g-3">
                            @foreach($backgrounds as $bg)
                            <div class="col-6 col-md-3">
                                <label class="card h-100 text-center border p-2 position-relative cursor-pointer hover-shadow">
                                    <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="position-absolute top-0 start-0 m-2 bg-radio" data-url="{{ asset('storage/' . $bg->image_path) }}" {{ old('selected_background_id') == $bg->id ? 'checked' : '' }}>
                                    <img src="{{ asset('storage/' . $bg->image_path) }}" class="card-img-top img-fluid rounded" style="height: 120px; object-fit: cover;">
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('selected_background_id')
                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Title Text Color</label>
                            <input type="color" name="text_color" id="text_color" class="form-control form-control-color w-100" value="#b02663">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Details Text Color</label>
                            <input type="color" name="details_color" id="details_color" class="form-control form-control-color w-100" value="#2b4c5e">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Input Content Matrix -->
                    <h5 class="text-secondary fw-bold mb-4"><i class="bi bi-card-text me-2"></i>Invitation Topic Layout</h5>

                    @php
                    $fields = [
                        'one' => 'First Topic',
                        'two' => 'Second Topic',
                        'three' => 'Third Topic',
                        'four' => 'Fourth Topic',
                        'five' => 'Fifth Topic',
                        'six' => 'Sixth Topic'
                    ];
                    @endphp

                    @foreach($fields as $key => $label)
                    <div class="row g-3 mb-4 align-items-start border-bottom pb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">{{ $label }} Heading Title</label>
                            <input type="text" name="topic_title_{{ $key }}" id="input_title_{{ $key }}" class="form-control watch-input" value="{{ old('topic_title_'.$key) }}" placeholder="e.g., Groom's Parents">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-dark">{{ $label }} Content Body / Names</label>
                            <textarea name="text{{ $key }}" id="input_text_{{ $key }}" class="form-control watch-input" rows="2" placeholder="List family members or relevant notes..."></textarea>
                        </div>
                    </div>
                    @endforeach

                    <!-- Standalone Text Field Seven -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Additional Invitation Footer Notes (Text Seven)</label>
                        <textarea name="textseven" id="input_text_seven" class="form-control watch-input" rows="3" placeholder="Any final matching invitation details..."></textarea>
                    </div>
                    
                    <input type="hidden" name="text_positions" id="text_positions" value="{}">
                    <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{}">

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('host.hfamily.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-success px-4">Save Configuration</button>
                    </div>
                </form>
            </div>
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
                            @foreach(['one', 'two', 'three', 'four', 'five', 'six'] as $i => $key)
                            @php 
                                $topTitle = 10 + ($i * 12);
                                $topText = 15 + ($i * 12);
                            @endphp
                            <h4 class="ceremony-title draggable-text" id="prev_title_{{ $key }}" data-anim-type="none" data-anim-duration="0.8" style="color: #b02663; top: {{ $topTitle }}%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 1.8rem; display:none; font-family: 'Georgia', cursive, serif;">
                            </h4>
                            <div class="details-row draggable-text" id="prev_text_{{ $key }}" data-anim-type="none" data-anim-duration="0.8" style="color: #2b4c5e; top: {{ $topText }}%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 1rem; display:none; font-family: 'Arial', sans-serif;">
                            </div>
                            @endforeach
                            
                            <div class="details-row draggable-text" id="prev_text_seven" data-anim-type="none" data-anim-duration="0.8" style="color: #2b4c5e; top: 85%; left: 50%; transform: translateX(-50%); text-align: center; font-size: 1rem; display:none; max-width: 90%; font-family: 'Arial', sans-serif;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bgRadios = document.querySelectorAll('.bg-radio');
        const prevCard = document.getElementById('preview_card');
        const textColorInput = document.getElementById('text_color');
        const detailsColorInput = document.getElementById('details_color');

        const keys = ['one', 'two', 'three', 'four', 'five', 'six'];

        function updatePreview() {
            keys.forEach(key => {
                const titleInput = document.getElementById('input_title_' + key);
                const textInput = document.getElementById('input_text_' + key);
                const prevTitle = document.getElementById('prev_title_' + key);
                const prevText = document.getElementById('prev_text_' + key);

                if(!customTexts['prev_title_' + key]) {
                    if (titleInput.value) {
                        prevTitle.textContent = titleInput.value;
                        prevTitle.style.display = 'block';
                    } else {
                        prevTitle.style.display = 'none';
                    }
                    prevTitle.style.color = textColorInput.value;
                }
                
                if(!customTexts['prev_text_' + key]) {
                    if (textInput.value) {
                        prevText.innerHTML = textInput.value.replace(/\n/g, '<br>');
                        prevText.style.display = 'block';
                    } else {
                        prevText.style.display = 'none';
                    }
                    prevText.style.color = detailsColorInput.value;
                }
            });

            const textSeven = document.getElementById('input_text_seven');
            const prevSeven = document.getElementById('prev_text_seven');
            if(!customTexts['prev_text_seven']) {
                if (textSeven.value) {
                    prevSeven.innerHTML = textSeven.value.replace(/\n/g, '<br>');
                    prevSeven.style.display = 'block';
                } else {
                    prevSeven.style.display = 'none';
                }
                prevSeven.style.color = detailsColorInput.value;
            }
        }

        document.querySelectorAll('.watch-input').forEach(el => {
            el.addEventListener('input', updatePreview);
        });
        if(textColorInput) textColorInput.addEventListener('input', updatePreview);
        if(detailsColorInput) detailsColorInput.addEventListener('input', updatePreview);

        bgRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const imgUrl = this.getAttribute('data-url');
                    if(imgUrl) {
                        prevCard.style.backgroundImage = `url('${imgUrl}')`;
                    }
                }
            });
        });

        // Make sure preview updates on load
        updatePreview();

        // --- Draggable Logic via jQuery UI ---
        const textPositionsInput = document.getElementById('text_positions');
        let currentPositions = {};

        function savePositions(element) {
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
        let customTexts = {};
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
    });
</script>
@endpush
@endsection