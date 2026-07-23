document.addEventListener("DOMContentLoaded", function () {
        const btnDesignOnCanva = document.getElementById('btn-design-on-canva');
        if (btnDesignOnCanva) {
            btnDesignOnCanva.addEventListener('click', function () {
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

        fabric.Object.prototype.toObject = (function (toObject) {
            return function (propertiesToInclude) {
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
            radio.addEventListener('change', function () {
                document.querySelectorAll('.bg-img-preview').forEach(img => {
                    img.style.border = '1px solid #dee2e6';
                    img.style.boxShadow = 'none';
                });
                if (this.checked) {
                    this.nextElementSibling.style.border = '3px solid #0d6efd';
                    this.nextElementSibling.style.boxShadow = '0 0 10px rgba(13,110,253,0.5)';

                    const imgSrc = this.dataset.image;
                    fabric.Image.fromURL(imgSrc, function (img) {
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
            if (radio.checked) {
                radio.dispatchEvent(new Event('change'));
            }
        });

        nameInput.addEventListener('input', updatePreview);
        dateInput.addEventListener('change', updatePreview);
        timeInput.addEventListener('input', updatePreview);
        venueSelect.addEventListener('change', updatePreview);
        if (textColorInput) textColorInput.addEventListener('input', updatePreview);
        if (detailsColorInput) detailsColorInput.addEventListener('input', updatePreview);

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

        document.getElementById('tool_bold').addEventListener('click', function (e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if (activeObj) { activeObj.set('fontWeight', activeObj.fontWeight === 'bold' ? 'normal' : 'bold'); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_italic').addEventListener('click', function (e) {
            e.preventDefault();
            const activeObj = canvas.getActiveObject();
            if (activeObj) { activeObj.set('fontStyle', activeObj.fontStyle === 'italic' ? 'normal' : 'italic'); canvas.renderAll(); saveCanvasState(); }
        });

        document.getElementById('tool_align_left').addEventListener('click', function (e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('textAlign', 'left'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_center').addEventListener('click', function (e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('textAlign', 'center'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_align_right').addEventListener('click', function (e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('textAlign', 'right'); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_color').addEventListener('input', function (e) { const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('fill', this.value); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_font_family').addEventListener('change', function (e) { const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('fontFamily', this.value); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_size_up').addEventListener('click', function (e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('fontSize', (activeObj.fontSize || 16) + 2); canvas.renderAll(); saveCanvasState(); } });
        document.getElementById('tool_size_down').addEventListener('click', function (e) { e.preventDefault(); const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.set('fontSize', Math.max(10, (activeObj.fontSize || 16) - 2)); canvas.renderAll(); saveCanvasState(); } });

        document.getElementById('tool_animation_type').addEventListener('change', function (e) { const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.animType = this.value; saveCanvasState(); } });
        document.getElementById('tool_animation_duration').addEventListener('input', function (e) { const activeObj = canvas.getActiveObject(); if (activeObj) { activeObj.animDuration = this.value; saveCanvasState(); } });

        const saveVenueBtn = document.getElementById('saveVenueBtn');
        if (saveVenueBtn) {
            saveVenueBtn.addEventListener('click', function (e) {
                e.preventDefault();
                let form = document.getElementById('quickVenueForm');
                if (!form) return;
                let formData = new FormData(form);
                const csrfToken = form.querySelector('input[name="_token"]')?.value || '';
                fetch("/host/venue", {
                    method: "POST",
                    body: formData,
                    headers: { 
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                    .then(async res => {
                        if (!res.ok) {
                            const errData = await res.json();
                            throw errData;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.id) {
                            let select = document.getElementById('venue_select');
                            if (select) {
                                let option = new Option(data.venue_name, data.id, true, true);
                                option.setAttribute('data-name', data.venue_name);
                                select.add(option);
                                select.dispatchEvent(new Event('change'));
                            }
                            var modalEl = document.getElementById('addVenueModal');
                            if (modalEl) {
                                var modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();
                            }
                        } else {
                            alert("Error: Venue ID not returned.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        if (err && err.errors) {
                            alert("Validation Error:\n" + Object.values(err.errors).flat().join('\n'));
                        } else {
                            alert("Error saving venue. Make sure all required fields (Pincode, Full Address, etc.) are filled.");
                        }
                    });
            });
        }

        const pincodeInput = document.getElementById('v_pincode');
        if (pincodeInput) {
            pincodeInput.addEventListener('keyup', function () {
                let pinLoad = document.getElementById('pin_load');
                if (this.value.length === 6) {
                    if (pinLoad) pinLoad.style.display = 'inline';
                    fetch(`https://api.postalpincode.in/pincode/${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            if (pinLoad) pinLoad.style.display = 'none';
                            if (data[0].Status === "Success") {
                                let offices = data[0].PostOffice;
                                let area = document.getElementById('v_area');
                                if (area) {
                                    area.innerHTML = '';
                                    offices.forEach(o => {
                                        area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                                    });
                                }
                                let district = document.getElementById('v_district');
                                if (district) district.value = offices[0].District;
                                let state = document.getElementById('v_state');
                                if (state) state.value = offices[0].State;
                                let country = document.getElementById('v_country');
                                if (country) country.value = offices[0].Country;
                                let circle = document.getElementById('v_circle');
                                if (circle) circle.value = offices[0].Circle;
                            }
                        })
                        .catch(err => {
                            if (pinLoad) pinLoad.style.display = 'none';
                            console.error("Error fetching pincode", err);
                        });
                }
            });
        }
    });
