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

        // Pre-defined IDs for our texts
        let titleObj, dateObj, timeObj, venueObj;

        function addOrUpdateText(id, text, top, fontSize, color, fontFamily) {
            let existingObj = canvas.getObjects().find(o => o.id === id);
            if (existingObj) {
                existingObj.set({ text: text, fill: color });
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

        // Bind form inputs
        if(brideInput) brideInput.addEventListener('input', updatePreview);
        if(groomInput) groomInput.addEventListener('input', updatePreview);
        if(dateInput) dateInput.addEventListener('change', updatePreview);
        if(timeInput) timeInput.addEventListener('input', updatePreview);
        if(venueSelect) venueSelect.addEventListener('change', updatePreview);
        if(textColorInput) textColorInput.addEventListener('input', updatePreview);
        if(detailsColorInput) detailsColorInput.addEventListener('input', updatePreview);

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

        // Initial setup
        updatePreview();

        // If a background is already checked, trigger it
        const checkedBg = document.querySelector('input[name="selected_background_id"]:checked');
        if (checkedBg) checkedBg.dispatchEvent(new Event('change'));

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
                
                // Position toolbar above the canvas
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
    document.getElementById('quickVenueForm').addEventListener('submit', function(e) {
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
                var modal = bootstrap.Modal.getInstance(document.getElementById('addVenueModal'));
                modal.hide();
            })
            .catch(err => alert("Error saving venue."));
    });
