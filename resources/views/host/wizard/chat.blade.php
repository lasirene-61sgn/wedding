<!DOCTYPE html>
<html lang="en" class="h-full bg-stone-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Setup Your Wedding Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wedding: {
                            dark: '#1e3a2f',
                            primary: '#2c5f41',
                            gold: '#c4a373',
                            cream: '#f9f7f4'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif']
                    }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e7e5e4;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-stone-50 font-sans h-screen flex flex-col justify-between">

    <header class="bg-white border-b border-stone-100 px-6 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-wedding-dark rounded-full flex items-center justify-center text-white font-serif text-xl shadow-md">
                W
            </div>
            <div>
                <h1 class="text-md font-bold text-wedding-dark tracking-wide">Wedding Planning Assistant</h1>
                <p class="text-xs text-stone-500 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full inline-block animate-pulse"></span> Virtual Concierge
                </p>
            </div>
        </div>
        <a href="{{ route('host.invitation.index') }}" class="text-xs font-semibold text-stone-400 hover:text-wedding-dark uppercase tracking-wider transition">
            Skip Setup
        </a>
    </header>

    <main id="chat-container" class="flex-1 overflow-y-auto px-4 md:px-8 py-6 space-y-6 custom-scrollbar max-w-4xl mx-auto w-full">
        <div class="flex gap-3 max-w-[85%]">
            <div class="w-8 h-8 rounded-full bg-wedding-cream flex-shrink-0 flex items-center justify-center text-xs font-semibold text-wedding-gold border border-stone-100">AI</div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-stone-100 text-stone-700 space-y-2 text-sm leading-relaxed">
                <p>Thank you for choosing your planning suite! 🌸</p>
                <p>I'm here to help you configure your venue, create your core digital invitation registry, manage your scheduled ceremonies, and set up your "Save the Date" layout right now so your dashboard is ready to go.</p>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-stone-100 p-4 shadow-[0_-4px_20px_rgba(0,0,0,0.02)]">
        <div class="max-w-4xl mx-auto w-full" id="input-zone">
        </div>
    </footer>

    <script>
        const chatContainer = document.getElementById('chat-container');
        const inputZone = document.getElementById('input-zone');

        let state = {
            venue_id: null,
            invitation_id: null,
            skipped_invitation: false,
            bride_name: '',
            groom_name: ''
        };

        const ceremonyCategories = @json($categories ?? []);

        let temporaryCeremonies = [];

        function scrollToBottom() {
            setTimeout(() => {
                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }, 50);
        }

        function appendBotMessage(messageHtml) {
            const html = `
                <div class="flex gap-3 max-w-[85%] animate-fade-in mt-4">
                    <div class="w-8 h-8 rounded-full bg-wedding-cream flex-shrink-0 flex items-center justify-center text-xs font-semibold text-wedding-gold border border-stone-100">AI</div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-stone-100 text-stone-700 text-sm leading-relaxed">
                        ${messageHtml}
                    </div>
                </div>
            `;
            chatContainer.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function appendUserMessage(text) {
            const html = `
                <div class="flex gap-3 max-w-[85%] ml-auto justify-end mt-4">
                    <div class="bg-wedding-primary text-white p-4 rounded-2xl rounded-tr-none shadow-md text-sm leading-relaxed font-medium">
                        ${text}
                    </div>
                </div>
            `;
            chatContainer.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
            inputZone.innerHTML = ''; // clear input zone
        }

        function askQuestion(botMessageHtml, buttons) {
            appendBotMessage(botMessageHtml);
            
            let buttonsHtml = '<div class="flex gap-3 justify-end">';
            buttons.forEach(btn => {
                const btnClass = btn.secondary 
                    ? 'border border-stone-300 text-stone-600 hover:bg-stone-100' 
                    : 'bg-wedding-dark text-white hover:bg-wedding-primary';
                buttonsHtml += `<button onclick="${btn.action}" class="${btnClass} px-5 py-2.5 rounded-xl text-sm font-semibold transition">${btn.text}</button>`;
            });
            buttonsHtml += '</div>';
            inputZone.innerHTML = buttonsHtml;
            scrollToBottom();
        }

        // --- STEP 1: INVITATION DETAILS (INCLUDING VENUE) ---
        function promptInvitationStep() {
            askQuestion(
                "<p class='font-serif text-base text-wedding-dark mb-1'>Step 1: Invitation & Venue Details</p><p>Would you like to set up your Wedding Invitation and Venue?</p>",
                [
                    { text: 'Skip Invitation & Venue', action: 'skipInvitation()', secondary: true },
                    { text: 'Create Invitation', action: 'showInvitationForm()' }
                ]
            );
        }

        function skipInvitation() {
            appendUserMessage("Skip Invitation");
            state.skipped_invitation = true;
            promptSaveDateStep();
        }

        function showInvitationForm() {
            appendUserMessage("I want to create an Invitation.");
            appendBotMessage("Let's compile the registry layout details for the wedding invitation card, along with your venue information.");

            inputZone.innerHTML = `
                <form id="invitation-form" class="space-y-4 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1">
                    
                    <div class="bg-white p-3 border border-stone-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-wedding-dark mb-3">Venue Details</h3>
                        <div class="mb-4">
                            <label class="text-xs text-stone-500 font-medium block mb-1">Select Venue (Optional)</label>
                            <select name="existing_venue_id" id="existing_venue_id" onchange="toggleNewVenueForm()" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                                <option value="">-- Add New Venue --</option>
                                @foreach($venues as $v)
                                    <option value="{{ $v->id }}">{{ $v->venue_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="new_venue_fields" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <input type="text" name="venue_name" placeholder="Venue Name (e.g., The Taj Palace)" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="pincode" id="v_pincode" placeholder="Pincode (6 digits)" maxlength="6" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            
                            <select name="area_name" id="v_area_name" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                                <option value="">Select Area</option>
                            </select>
                            
                            <input type="text" name="district" id="v_district" placeholder="District" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="state" id="v_state" placeholder="State" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="circle" id="v_circle" placeholder="Circle" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="country" id="v_country" placeholder="Country" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            
                            <input type="text" name="wedding_location" placeholder="Wedding Location (City/Town)" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="location_map" placeholder="Google Maps Link" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50 md:col-span-2">
                            
                            <input type="text" name="venue_address" placeholder="Full Postal Address Details" class="p-3 border border-stone-200 rounded-xl text-sm md:col-span-3 focus:outline-none focus:border-wedding-gold bg-stone-50">
                        </div>
                    </div>

                    <div class="bg-white p-3 border border-stone-200 rounded-xl">
                        <h3 class="text-sm font-semibold text-wedding-dark mb-3">Invitation Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="md:col-span-2">
                                <label class="text-xs text-stone-500 font-medium block mb-1">Who is sending out the invitation?</label>
                                <select name="invite" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                                    <option value="weddingcouple">The Wedding Couple</option>
                                    <option value="bride">The Bride</option>
                                    <option value="groom">The Groom</option>
                                    <option value="brideparents">Bride's Parents</option>
                                    <option value="groomparents">Groom's Parents</option>
                                </select>
                            </div>
                            <input type="text" name="bride_name" placeholder="Bride's Name" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="groom_name" placeholder="Groom's Name" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="bride_number" placeholder="Bride's Contact Number" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="email" name="bride_email" placeholder="Bride's Email Address" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="groom_number" placeholder="Groom's Contact Number" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="email" name="groom_email" placeholder="Groom's Email Address" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="bride_father_name" placeholder="Bride's Father" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="bride_mother_name" placeholder="Bride's Mother" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="groom_father_name" placeholder="Groom's Father" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <input type="text" name="groom_mother_name" placeholder="Groom's Mother" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            <div>
                                <label class="text-xs text-stone-500 font-medium block mb-1">Wedding Date</label>
                                <input type="date" name="wedding_date" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            </div>
                            <div>
                                <label class="text-xs text-stone-500 font-medium block mb-1">Wedding Muhurtham / Time</label>
                                <input type="time" name="wedding_time" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-stone-400 font-medium block mb-1">Main Wedding Banner Image Cover</label>
                                <input type="file" name="wedding_image" accept="image/*" class="w-full p-2 border border-dashed border-stone-300 rounded-xl text-sm bg-stone-50 text-stone-500">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" id="inv-submit-btn" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white p-3.5 rounded-xl font-semibold text-sm transition tracking-wider uppercase">Save Details →</button>
                </form>
            `;
            scrollToBottom();
            
            window.toggleNewVenueForm = function() {
                const select = document.getElementById('existing_venue_id');
                const newForm = document.getElementById('new_venue_fields');
                if (select && select.value) {
                    newForm.style.display = 'none';
                } else {
                    newForm.style.display = 'grid';
                }
            };


            // Handle Pincode Auto-fetch
            const pincodeInput = document.getElementById('v_pincode');
            pincodeInput.addEventListener('input', async function() {
                if(this.value.length === 6) {
                    try {
                        const response = await fetch(`https://api.postalpincode.in/pincode/${this.value}`);
                        const data = await response.json();
                        
                        if(data && data[0].Status === 'Success') {
                            const postOffices = data[0].PostOffice;
                            
                            // Fill fields
                            document.getElementById('v_district').value = postOffices[0].District;
                            document.getElementById('v_state').value = postOffices[0].State;
                            document.getElementById('v_circle').value = postOffices[0].Circle;
                            document.getElementById('v_country').value = postOffices[0].Country;
                            
                            // Populate area dropdown
                            const areaSelect = document.getElementById('v_area_name');
                            areaSelect.innerHTML = '<option value="">Select Area</option>';
                            postOffices.forEach(po => {
                                areaSelect.innerHTML += `<option value="${po.Name}">${po.Name}</option>`;
                            });
                            // If only 1 area, select it automatically
                            if(postOffices.length === 1) {
                                areaSelect.value = postOffices[0].Name;
                            }
                        }
                    } catch (err) {
                        console.error('Failed to fetch pincode details', err);
                    }
                }
            });

            document.getElementById('invitation-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = document.getElementById('inv-submit-btn');
                submitBtn.innerText = 'Saving...';
                submitBtn.disabled = true;

                try {
                    let existingVenueId = document.getElementById('existing_venue_id') ? document.getElementById('existing_venue_id').value : null;

                    if (!existingVenueId) {
                        // 1. Create Venue
                        let venueResponse = await fetch("{{ route('host.wizard.storeVenue') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        let venueData = await venueResponse.json();
                        if (!venueResponse.ok || !venueData.success) {
                            alert("Venue Error: " + (venueData.message || "Please check inputs."));
                            submitBtn.innerText = 'Save Details →';
                            submitBtn.disabled = false;
                            return;
                        }
                        
                        state.venue_id = venueData.venue_id;
                    } else {
                        state.venue_id = existingVenueId;
                    }

                    if (state.venue_id) {
                        formData.append('venue_id', state.venue_id);
                    }

                    // 2. Create Invitation
                    let invResponse = await fetch("{{ route('host.wizard.storeInvitation') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    let invData = await invResponse.json();
                    if (!invResponse.ok || !invData.success) {
                        alert("Invitation Error encountered saving your invitation details.");
                        submitBtn.innerText = 'Save Details →';
                        submitBtn.disabled = false;
                        return;
                    }

                    // Success
                    state.invitation_id = invData.invitation_id;
                    state.skipped_invitation = false;
                    state.bride_name = formData.get('bride_name');
                    state.groom_name = formData.get('groom_name');
                    appendUserMessage(`💍 Invitation & Venue Configured for ${formData.get('bride_name')} & ${formData.get('groom_name')}`);
                    promptSaveDateStep();

                } catch (err) {
                    console.error("Error processing request:", err);
                    alert("A backend configuration error occurred. Please check your developer console.");
                    submitBtn.innerText = 'Save Details →';
                    submitBtn.disabled = false;
                }
            });
        }

        // --- STEP 2: SAVE THE DATE ---
        function promptSaveDateStep() {
            if (state.skipped_invitation) {
                // If they skipped invitation, automatically skip save the date because it requires an invitation ID
                appendBotMessage("<p class='font-serif text-base text-wedding-dark mb-1'>Step 2: Save The Date Card</p><p>Since you skipped the Invitation step, we will also skip the Save The Date card for now.</p>");
                setTimeout(() => {
                    promptCeremonyStep();
                }, 1500);
                return;
            }

            askQuestion(
                "<p class='font-serif text-base text-wedding-dark mb-1'>Step 2: Save The Date Card</p><p>Would you like to create your Save the Date card?</p>",
                [
                    { text: 'Skip Save the Date', action: 'skipSaveDate()', secondary: true },
                    { text: 'Create Save the Date', action: 'showSaveDateForm()' }
                ]
            );
        }

        function skipSaveDate() {
            appendUserMessage("Skip Save the Date");
            promptCeremonyStep();
        }

        function showSaveDateForm() {
            appendUserMessage("Let's create the Save the Date card.");
            appendBotMessage("Upload a design flyer image and a welcome message to broadcast to early guest RSVPs:");

            const defaultMessage = (state.bride_name && state.groom_name) ? `Save the date for ${state.bride_name} & ${state.groom_name}!` : '';

            inputZone.innerHTML = `
                <form id="savedate-form" class="space-y-4">
                    <input type="text" name="message" value="${defaultMessage}" placeholder="Short Sweet Message (e.g., Save Our Date! Max 100 characters)" maxlength="100" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                    <div>
                        <label class="text-xs text-stone-400 font-medium block mb-1">Upload Save the Date Poster Image Card</label>
                        <input type="file" name="image" accept="image/*" required class="w-full p-2 border border-dashed border-stone-300 rounded-xl text-sm bg-stone-50 text-stone-500">
                    </div>
                    <button type="submit" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white p-3.5 rounded-xl font-semibold text-sm transition tracking-wider uppercase">Save Date & Continue →</button>
                </form>
            `;
            scrollToBottom();

            document.getElementById('savedate-form').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!state.invitation_id) {
                    alert("Missing Invitation ID connection! Please ensure Step 2 completed successfully.");
                    return;
                }

                let formData = new FormData(this);
                formData.append('invitation_id', state.invitation_id);

                fetch("{{ route('host.wizard.storeSaveDate') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async res => {
                        if (!res.ok) {
                            const errData = await res.json();
                            alert("Validation Failed: " + JSON.stringify(errData.errors));
                            throw errData;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            appendUserMessage("📷 Save the Date card saved successfully.");
                            promptCeremonyStep();
                        }
                    })
                    .catch(err => {
                        console.error("Error processing request:", err);
                    });
            });
        }


        // --- STEP 3: CEREMONY DETAILS ---
        function promptCeremonyStep() {
            askQuestion(
                "<p class='font-serif text-base text-wedding-dark mb-1'>Step 3: Wedding Ceremonies & Events</p><p>Would you like to add Ceremonies?</p>",
                [
                    { text: 'Skip Ceremonies', action: 'skipCeremonies()', secondary: true },
                    { text: 'Add Ceremonies', action: 'showCeremonyForm()' }
                ]
            );
        }

        function skipCeremonies() {
            appendUserMessage("Skip Ceremonies");
            promptGalleryStep();
        }

        function showCeremonyForm() {
            appendUserMessage("I want to add some Ceremonies.");
            appendBotMessage("Add the structural ceremonies for this venue partition layout (e.g., Sangeet, Muhurtham, Reception):");
            temporaryCeremonies = [];
            renderCeremonyZone();
        }

        function renderCeremonyZone() {
            let listHtml = '';
            if (temporaryCeremonies.length > 0) {
                listHtml = `<div class="bg-stone-100 p-3 rounded-xl space-y-2 mb-3 text-xs text-stone-700">
                    <p class="font-semibold text-wedding-dark">Ceremonies Added to Queue:</p>`;
                temporaryCeremonies.forEach((c, idx) => {
                    listHtml += `<div class="flex justify-between items-center bg-white p-2 rounded-lg shadow-sm border border-stone-200">
                        <div><strong>${c.name}</strong> - ${c.date || 'No Date'} @ ${c.time || 'No Time'}</div>
                        <button type="button" onclick="removeCeremonyFromList(${idx})" class="text-rose-600 font-medium hover:underline">Remove</button>
                    </div>`;
                });
                listHtml += `</div>`;
            }

            let catOptions = '<option value="">-- Select Category --</option>';
            ceremonyCategories.forEach(cat => {
                catOptions += `<option value="${cat.id}" data-ceremonies='${JSON.stringify(cat.ceremonies || [])}'>${cat.category_name}</option>`;
            });

            let venueOptions = '<option value="">-- Select Venue (Optional) --</option><option value="new">-- Add New Venue --</option>';
            @foreach($venues as $v)
                venueOptions += `<option value="{{ $v->id }}">{{ $v->venue_name }}</option>`;
            @endforeach

            inputZone.innerHTML = `
                ${listHtml}
                <form id="ceremony-form" class="space-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1">
                    <div class="bg-white p-3 border border-stone-200 rounded-xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="md:col-span-2">
                                <label class="text-xs text-stone-500 font-medium block mb-1">Ceremony Category Group</label>
                                <select id="c_category_id" onchange="window.handleCeremonyCategoryChange()" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                                    ${catOptions}
                                </select>
                            </div>
                            <div id="c_badges_container" class="md:col-span-2 flex-wrap gap-2 hidden" style="display: none;"></div>
                            
                            <input type="text" id="c_name" placeholder="Ceremony Name (e.g., Haldi, Sangeet)" class="p-3 border border-stone-200 rounded-xl text-sm md:col-span-2 focus:outline-none focus:border-wedding-gold bg-stone-50">
                            
                            <div class="md:col-span-2">
                                <label class="text-xs text-stone-500 font-medium block mb-1">Select Venue (Optional)</label>
                                <select id="c_existing_venue_id" onchange="window.toggleCeremonyNewVenueForm()" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                                    ${venueOptions}
                                </select>
                            </div>
                            
                            <div id="c_new_venue_fields" class="md:col-span-2 grid-cols-1 md:grid-cols-2 gap-3 p-3 bg-stone-50 rounded-xl border border-stone-200 hidden" style="display: none;">
                                <input type="text" id="c_v_name" placeholder="Venue Name" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white md:col-span-2">
                                <input type="text" id="c_v_pincode" placeholder="Pincode (6 digits)" maxlength="6" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white">
                                <select id="c_v_area_name" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white"><option value="">Area</option></select>
                                <input type="text" id="c_v_district" placeholder="District" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white">
                                <input type="text" id="c_v_state" placeholder="State" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white">
                                <input type="text" id="c_v_circle" placeholder="Circle" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white">
                                <input type="text" id="c_v_country" placeholder="Country" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white">
                                <input type="text" id="c_v_wedding_location" placeholder="Wedding Location (City/Town)" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white">
                                <input type="text" id="c_v_location_map" placeholder="Google Maps Link" class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-white md:col-span-2">
                                <input type="text" id="c_v_venue_address" placeholder="Full Postal Address Details" class="p-3 border border-stone-200 rounded-xl text-sm md:col-span-2 focus:outline-none focus:border-wedding-gold bg-white">
                            </div>

                            <div>
                                <label class="text-xs text-stone-500 font-medium block mb-1">Ceremony Date</label>
                                <input type="date" id="c_date" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            </div>
                            <div>
                                <label class="text-xs text-stone-500 font-medium block mb-1">Ceremony Time</label>
                                <input type="time" id="c_time" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2 pt-2">
                        <button type="button" onclick="addCeremonyToList()" class="w-full md:w-1/3 border border-wedding-dark text-wedding-dark hover:bg-stone-100 p-3 rounded-xl font-semibold text-sm transition">
                            + Add Ceremony
                        </button>
                        <button type="button" onclick="submitAllCeremonies()" class="w-full md:w-2/3 bg-wedding-dark hover:bg-wedding-primary text-white p-3 rounded-xl font-semibold text-sm transition tracking-wider uppercase">
                            Save Ceremonies Timeline →
                        </button>
                    </div>
                </form>
            `;
            scrollToBottom();

            window.toggleCeremonyNewVenueForm = function() {
                const select = document.getElementById('c_existing_venue_id');
                const newForm = document.getElementById('c_new_venue_fields');
                if (select && select.value === 'new') {
                    newForm.style.display = 'grid';
                    newForm.classList.remove('hidden');
                } else {
                    newForm.style.display = 'none';
                    newForm.classList.add('hidden');
                }
            };

            window.handleCeremonyCategoryChange = function() {
                const select = document.getElementById('c_category_id');
                const option = select.options[select.selectedIndex];
                const badgesContainer = document.getElementById('c_badges_container');
                const nameInput = document.getElementById('c_name');

                badgesContainer.innerHTML = '';
                badgesContainer.style.display = 'none';
                badgesContainer.classList.add('hidden');
                nameInput.value = '';

                if (!option || !option.value) return;

                let ceremoniesRaw = option.getAttribute('data-ceremonies');
                let ceremonies = ceremoniesRaw ? JSON.parse(ceremoniesRaw) : [];

                if (ceremonies.length > 0) {
                    badgesContainer.style.display = 'flex';
                    badgesContainer.classList.remove('hidden');
                    
                    ceremonies.forEach(ceremony => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'px-3 py-1 text-xs border border-wedding-primary text-wedding-primary rounded-full hover:bg-wedding-primary hover:text-white transition c-badge';
                        btn.innerText = ceremony;
                        btn.onclick = function() {
                            document.querySelectorAll('.c-badge').forEach(b => {
                                b.classList.remove('bg-wedding-primary', 'text-white');
                                b.classList.add('text-wedding-primary');
                            });
                            this.classList.add('bg-wedding-primary', 'text-white');
                            this.classList.remove('text-wedding-primary');
                            nameInput.value = ceremony;
                        };
                        badgesContainer.appendChild(btn);
                    });
                }
            };

            // Pincode fetch for new venue in ceremony
            const pincodeInput = document.getElementById('c_v_pincode');
            if (pincodeInput) {
                pincodeInput.addEventListener('input', async function() {
                    if(this.value.length === 6) {
                        try {
                            const response = await fetch(`https://api.postalpincode.in/pincode/${this.value}`);
                            const data = await response.json();
                            
                            if(data && data[0].Status === 'Success') {
                                const postOffices = data[0].PostOffice;
                                document.getElementById('c_v_district').value = postOffices[0].District;
                                document.getElementById('c_v_state').value = postOffices[0].State;
                                document.getElementById('c_v_circle').value = postOffices[0].Circle;
                                document.getElementById('c_v_country').value = postOffices[0].Country;
                                
                                const areaSelect = document.getElementById('c_v_area_name');
                                areaSelect.innerHTML = '<option value="">Select Area</option>';
                                postOffices.forEach(po => {
                                    areaSelect.innerHTML += `<option value="${po.Name}">${po.Name}</option>`;
                                });
                                if(postOffices.length === 1) {
                                    areaSelect.value = postOffices[0].Name;
                                }
                            }
                        } catch (err) { console.error('Failed to fetch pincode', err); }
                    }
                });
            }
        }

        function addCeremonyToList() {
            const catId = document.getElementById('c_category_id').value;
            const name = document.getElementById('c_name').value.trim();
            const date = document.getElementById('c_date').value;
            const time = document.getElementById('c_time').value;

            const existingVenueId = document.getElementById('c_existing_venue_id').value;
            let newVenueData = null;
            
            if (existingVenueId === 'new') {
                newVenueData = {
                    venue_name: document.getElementById('c_v_name').value,
                    pincode: document.getElementById('c_v_pincode').value,
                    area_name: document.getElementById('c_v_area_name').value,
                    district: document.getElementById('c_v_district').value,
                    state: document.getElementById('c_v_state').value,
                    circle: document.getElementById('c_v_circle').value,
                    country: document.getElementById('c_v_country').value,
                    wedding_location: document.getElementById('c_v_wedding_location').value,
                    location_map: document.getElementById('c_v_location_map').value,
                    venue_address: document.getElementById('c_v_venue_address').value,
                };
            }

            if (!name) {
                alert("Please write a ceremony name before attempting to append it.");
                return;
            }

            temporaryCeremonies.push({
                category_id: catId,
                ceramony_name: name,
                ceramony_date: date ? date : null,
                ceramony_time: time ? time : null,
                name: name,
                date: date,
                time: time,
                existing_venue_id: existingVenueId,
                new_venue_data: newVenueData
            });

            renderCeremonyZone();
        }

        function removeCeremonyFromList(index) {
            temporaryCeremonies.splice(index, 1);
            renderCeremonyZone();
        }

        async function submitAllCeremonies() {
            if (temporaryCeremonies.length === 0) {
                alert("Please add at least one event ceremony to your schedule.");
                return;
            }

            inputZone.innerHTML = `<div class="text-center py-4 text-stone-500 text-sm animate-pulse">Syncing events with active backend data blocks...</div>`;

            try {
                for (let i = 0; i < temporaryCeremonies.length; i++) {
                    let item = temporaryCeremonies[i];
                    
                    let finalVenueId = null;
                    if (item.existing_venue_id === 'new' && item.new_venue_data && item.new_venue_data.venue_name) {
                        let vFormData = new FormData();
                        for (const key in item.new_venue_data) {
                            vFormData.append(key, item.new_venue_data[key]);
                        }
                        let vRes = await fetch("{{ route('host.wizard.storeVenue') }}", {
                            method: "POST",
                            body: vFormData,
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        let vData = await vRes.json();
                        if (vRes.ok && vData.success) {
                            finalVenueId = vData.venue_id;
                        }
                    } else if (item.existing_venue_id && item.existing_venue_id !== 'new') {
                        finalVenueId = item.existing_venue_id;
                    } else if (state.venue_id) {
                        finalVenueId = state.venue_id;
                    }
                    
                    let formData = new FormData();
                    formData.append('category_id', item.category_id);
                    if(finalVenueId) formData.append('venue_id', finalVenueId);
                    formData.append('ceramony_name', item.ceramony_name);
                    if(item.ceramony_date) formData.append('ceramony_date', item.ceramony_date);
                    if(item.ceramony_time) formData.append('ceramony_time', item.ceramony_time);

                    let response = await fetch("{{ route('host.wizard.storeCeremony') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        let errData = await response.json();
                        throw new Error(JSON.stringify(errData.errors || "Validation error at sub-node"));
                    }
                }

                appendUserMessage(`✨ Successfully registered ${temporaryCeremonies.length} structural event timeline modules.`);
                promptGalleryStep();

            } catch (error) {
                console.error("Pipeline failure storing your nested records:", error);
                alert("An error occurred while saving your ceremonies list: " + error.message);
                renderCeremonyZone(); 
            }

        }


        // --- STEP 4: GALLERY ---
        function promptGalleryStep() {
            askQuestion(
                "<p class='font-serif text-base text-wedding-dark mb-1'>Step 4: Wedding Gallery</p><p>Would you like to set up your Gallery?</p>",
                [
                    { text: 'Skip to Dashboard', action: 'wrapUpWizard()', secondary: true },
                    { text: 'Setup Gallery', action: 'wrapUpWizard("gallery")' }
                ]
            );
        }


        function wrapUpWizard(action = 'dashboard') {
            if(action === 'gallery') {
                appendUserMessage("I want to set up the Gallery.");
            } else {
                appendUserMessage("Skip to Dashboard.");
            }

            appendBotMessage("<p class='font-serif text-lg text-wedding-dark'>✨ All Set!</p><p>Your custom wedding workspace parameters have been completely initialized. Redirecting you now...</p>");

            inputZone.innerHTML = `
                <div class="text-center py-4 text-wedding-primary font-semibold text-sm animate-pulse">
                    Redirecting to your Planning Workspace...
                </div>
            `;

            setTimeout(() => {
                // If there's a specific gallery setup route, you could redirect there. 
                // For now, it goes to the host dashboard / invitation index.
                window.location.href = "{{ route('host.invitation.index') }}";
            }, 2500);
        }

        window.onload = () => {
            setTimeout(() => {
                promptInvitationStep();
            }, 500);
        };
    </script>
</body>

</html>