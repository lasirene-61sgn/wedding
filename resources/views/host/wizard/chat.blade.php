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
                <p>I'm here to help you configure your venue, create your core digital invitation registry, and set up your "Save the Date" layout right now so your dashboard is ready to go.</p>
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
            invitation_id: null
        };

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
                <div class="flex gap-3 max-w-[85%] animate-fade-in">
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
                <div class="flex gap-3 max-w-[85%] ml-auto justify-end">
                    <div class="bg-wedding-primary text-white p-4 rounded-2xl rounded-tr-none shadow-md text-sm leading-relaxed font-medium">
                        ${text}
                    </div>
                </div>
            `;
            chatContainer.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        // --- STEP 1: VENUE DETAILS ---
        function initVenueStep() {
            appendBotMessage("<p class='font-serif text-base text-wedding-dark mb-1'>Step 1: Your Wedding Venue Location</p><p>Where will the celebratory ceremonies take place? Please provide your basic venue coordinates below:</p>");

            inputZone.innerHTML = `
                <form id="venue-form" class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" name="venue_name" placeholder="Venue Name (e.g., The Taj Palace)" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="pincode" placeholder="Pincode (6 digits)" maxlength="6" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="area_name" placeholder="Area Name" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="district" placeholder="District" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="state" placeholder="State" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="circle" placeholder="Circle" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="country" placeholder="Country" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="venue_address" placeholder="Full Postal Address Details" required class="p-3 border border-stone-200 rounded-xl text-sm md:col-span-2 focus:outline-none focus:border-wedding-gold bg-stone-50">
                    </div>
                    <button type="submit" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white p-3.5 rounded-xl font-semibold text-sm transition tracking-wider uppercase">Save Venue Details & Next Step →</button>
                </form>
            `;

            document.getElementById('venue-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch("{{ route('host.wizard.storeVenue') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw res;
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            state.venue_id = data.venue_id;
                            appendUserMessage(`📍 Venue Registered: ${formData.get('venue_name')}`);
                            initInvitationStep();
                        } else {
                            alert("Please recheck inputs: " + (data.message || ""));
                        }
                    })
                    .catch(err => {
                        console.error("Error processing request:", err);
                        alert("A backend configuration error occurred. Please check your developer console.");
                    });
            });
        }

        // --- STEP 2: INVITATION DETAILS ---
        function initInvitationStep() {
            appendBotMessage("<p class='font-serif text-base text-wedding-dark mb-1'>Step 2: Couple & Invitation Details</p><p>Let's compile the registry layout details for the wedding invitation card.</p>");

            inputZone.innerHTML = `
                <form id="invitation-form" class="space-y-3 max-h-[45vh] overflow-y-auto custom-scrollbar pr-1">
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
                        <input type="text" name="bride_name" placeholder="Bride's Name" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="groom_name" placeholder="Groom's Name" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="bride_number" placeholder="Bride's Contact Number" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="email" name="bride_email" placeholder="Bride's Email Address" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="groom_number" placeholder="Groom's Contact Number" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="email" name="groom_email" placeholder="Groom's Email Address" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="bride_father_name" placeholder="Bride's Father" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="bride_mother_name" placeholder="Bride's Mother" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="groom_father_name" placeholder="Groom's Father" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <input type="text" name="groom_mother_name" placeholder="Groom's Mother" required class="p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        <div>
                            <label class="text-xs text-stone-500 font-medium block mb-1">Wedding Date</label>
                            <input type="date" name="wedding_date" required class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        </div>
                        <div>
                            <label class="text-xs text-stone-500 font-medium block mb-1">Wedding Muhurtham / Time</label>
                            <input type="time" name="wedding_time" required class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs text-stone-400 font-medium block mb-1">Main Wedding Banner Image Cover</label>
                            <input type="file" name="wedding_image" accept="image/*" required class="w-full p-2 border border-dashed border-stone-300 rounded-xl text-sm bg-stone-50 text-stone-500">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white p-3.5 rounded-xl font-semibold text-sm transition tracking-wider uppercase">Save Invitation Card Details →</button>
                </form>
            `;
            scrollToBottom();

            document.getElementById('invitation-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('venue_id', state.venue_id);

                fetch("{{ route('host.wizard.storeInvitation') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw res;
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            state.invitation_id = data.invitation_id;
                            appendUserMessage(`💍 Invitation Configured for ${formData.get('bride_name')} & ${formData.get('groom_name')}`);
                            initSaveDateStep();
                        } else {
                            alert("Validation error encountered saving your invitation details.");
                        }
                    })
                    .catch(err => {
                        console.error("Error processing request:", err);
                        alert("Failed to save invitation data. Check that the image format is valid and fields match model limits.");
                    });
            });
        }

        // --- STEP 3: SAVE THE DATE ---
        function initSaveDateStep() {
            appendBotMessage("<p class='font-serif text-base text-wedding-dark mb-1'>Step 3: Save The Date Card</p><p>Finally, upload the design flyer image and a welcome message to broadcast to early guest RSVPs:</p>");

            inputZone.innerHTML = `
                <form id="savedate-form" class="space-y-4">
                    <input type="text" name="message" placeholder="Short Sweet Message (e.g., Save Our Date! Max 100 characters)" maxlength="100" class="w-full p-3 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-wedding-gold bg-stone-50">
                    <div>
                        <label class="text-xs text-stone-400 font-medium block mb-1">Upload Save the Date Poster Image Card</label>
                        <input type="file" name="image" accept="image/*" required class="w-full p-2 border border-dashed border-stone-300 rounded-xl text-sm bg-stone-50 text-stone-500">
                    </div>
                    <button type="submit" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white p-3.5 rounded-xl font-semibold text-sm transition tracking-wider uppercase">Complete Setup & Enter Dashboard ✨</button>
                </form>
            `;
            scrollToBottom();

            document.getElementById('savedate-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // SAFETY CHECK: If step 2 failed to capture the ID, alert immediately
    if (!state.invitation_id) {
        console.error("Current Wizard State:", state);
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
            'Accept': 'application/json' // Forces Laravel to send clear validation strings
        }
    })
    .then(async res => {
        if (!res.ok) {
            // Read validation failure messages explicitly
            const errData = await res.json();
            console.log("❌ VAL_ERRORS:", errData.errors);
            alert("Validation Failed: " + JSON.stringify(errData.errors));
            throw errData;
        }
        return res.json();
    })
    .then(data => {
        if(data.success) {
            appendUserMessage("📷 Save the Date card saved successfully.");
            wrapUpWizard();
        }
    })
    .catch(err => {
        console.error("Error processing request:", err);
    });
});
        }

        function wrapUpWizard() {
            appendBotMessage("<p class='font-serif text-lg text-wedding-dark'>✨ All Set!</p><p>Your custom wedding workspace parameters have been completely initialized. Redirecting you to your admin dashboard management layout now...</p>");

            inputZone.innerHTML = `
                <div class="text-center py-4 text-wedding-primary font-semibold text-sm animate-pulse">
                    Redirecting to your Planning Workspace...
                </div>
            `;

            setTimeout(() => {
                window.location.href = "{{ route('host.invitation.index') }}";
            }, 2500);
        }

        window.onload = () => {
            initVenueStep();
        };
    </script>
</body>

</html>