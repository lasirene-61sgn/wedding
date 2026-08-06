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
    </header>

    <main id="chat-container" class="flex-1 overflow-y-auto px-4 md:px-8 py-6 space-y-6 custom-scrollbar max-w-4xl mx-auto w-full">
        <div class="flex gap-3 max-w-[85%]">
            <div class="w-8 h-8 rounded-full bg-wedding-cream flex-shrink-0 flex items-center justify-center text-xs font-semibold text-wedding-gold border border-stone-100">AI</div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-stone-100 text-stone-700 space-y-2 text-sm leading-relaxed">
                <p>Welcome to Quick Setup! 🌸</p>
                <p>I'm here to gather some details about your wedding.</p>
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
        
        const ceremonyCategories = {!! json_encode($categories ?? []) !!};

        let state = {
            setup_role: '',
            creator_relationship: '',
            bride_name: '',
            bride_number: '',
            bride_email: null,
            bride_display_name: null,
            groom_name: '',
            groom_number: '',
            groom_email: null,
            groom_display_name: null,
            wedding_category_id: null,
            custom_wedding_category: null,
            is_engagement_completed: null,
            is_date_finalized: null,
            wedding_date: null,
            is_venue_finalized: null,
            venue_name: null,
            current_city: null,
            wedding_city: null,
            wedding_state: null
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
                <div class="flex gap-3 max-w-[85%] animate-fade-in mt-4">
                    <div class="w-8 h-8 rounded-full bg-wedding-cream flex-shrink-0 flex items-center justify-center text-xs font-semibold text-wedding-gold border border-stone-100">AI</div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-stone-100 text-stone-700 text-sm leading-relaxed w-full">
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
            inputZone.innerHTML = '';
        }

        function startFlow() {
            step1Role();
        }

        // STEP 1: Role
        function step1Role() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 1/8: Who is filling this setup?</p>
                <div class="flex gap-2">
                    <button type="button" onclick="handleRole('Bride')" class="bg-wedding-dark text-white px-4 py-2 rounded-lg text-sm hover:bg-wedding-primary">Bride</button>
                    <button type="button" onclick="handleRole('Groom')" class="bg-wedding-dark text-white px-4 py-2 rounded-lg text-sm hover:bg-wedding-primary">Groom</button>
                    <button type="button" onclick="handleRole('Other')" class="border border-stone-300 text-stone-700 px-4 py-2 rounded-lg text-sm hover:bg-stone-100">Other</button>
                </div>
            `);
        }

        window.handleRole = function(role) {
            state.setup_role = role;
            appendUserMessage(role);
            setTimeout(() => step1Relationship(role), 500);
        }

        function step1Relationship(role) {
            let relText = role === 'Other' ? 'the couple' : `the ${role}`;
            appendBotMessage(`
                <p class='text-sm mb-2'>What is your relationship to ${relText}?</p>
                <div class="flex flex-wrap gap-2 mb-3">
                    <button type="button" onclick="handleRel('Self')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">I am the ${role}</button>
                    <button type="button" onclick="setRel('Father')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Father</button>
                    <button type="button" onclick="setRel('Mother')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Mother</button>
                    <button type="button" onclick="setRel('Uncle')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Uncle</button>
                    <button type="button" onclick="setRel('Friend')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Friend</button>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="custom_rel" placeholder="Or type other relationship" class="p-2 border rounded-lg text-sm flex-1 focus:outline-none">
                    <button type="button" onclick="handleRel()" class="bg-wedding-dark text-white px-4 py-2 rounded-lg text-sm">Next</button>
                </div>
            `);
            window.setRel = function(val) {
                document.getElementById('custom_rel').value = val;
            }
            window.handleRel = function(directVal = null) {
                const val = directVal || document.getElementById('custom_rel').value.trim();
                if(!val) { alert("Please enter relationship"); return; }
                state.creator_relationship = (val === 'Self') ? state.setup_role : val;
                appendUserMessage(val === 'Self' ? `I am the ${state.setup_role}` : val);
                setTimeout(step2Details, 500);
            }
        }

        // STEP 2: Details
        function step2Details() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 2/8: Couple Details</p>
                <form id="step2-form" class="space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="b_name" required placeholder="Bride's Name *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                        <input type="text" id="b_mobile" required placeholder="Bride's Mobile *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="email" id="b_email" placeholder="Bride's Email (Optional)" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                        <input type="text" id="b_display" placeholder="Bride Display Name (Optional)" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                    </div>
                    <hr class="border-stone-100 my-2">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="g_name" required placeholder="Groom's Name *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                        <input type="text" id="g_mobile" required placeholder="Groom's Mobile *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="email" id="g_email" placeholder="Groom's Email (Optional)" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                        <input type="text" id="g_display" placeholder="Groom Display Name (Optional)" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Save Details →</button>
                </form>
            `);
            
            document.getElementById('step2-form').addEventListener('submit', function(e) {
                e.preventDefault();
                state.bride_name = document.getElementById('b_name').value;
                state.bride_number = document.getElementById('b_mobile').value;
                state.bride_email = document.getElementById('b_email').value || null;
                state.bride_display_name = document.getElementById('b_display').value || null;
                
                state.groom_name = document.getElementById('g_name').value;
                state.groom_number = document.getElementById('g_mobile').value;
                state.groom_email = document.getElementById('g_email').value || null;
                state.groom_display_name = document.getElementById('g_display').value || null;
                
                appendUserMessage(`Details saved for ${state.bride_name} & ${state.groom_name}`);
                setTimeout(step3Category, 500);
            });
        }

        // STEP 3: Type of Wedding
        function step3Category() {
            let catOptions = '<option value="">-- Select Event Category --</option>';
            ceremonyCategories.forEach(cat => {
                catOptions += `<option value="${cat.id}">${cat.category_name}</option>`;
            });

            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 3/8: Type of Wedding (Optional)</p>
                <form id="step3-form" class="space-y-3">
                    <select id="w_category" class="w-full p-2 border rounded-lg text-sm focus:outline-none" onchange="toggleCustomCat()">
                        ${catOptions}
                        <option value="other">Other (Specify)</option>
                    </select>
                    <input type="text" id="w_custom_cat" placeholder="Custom Wedding Type" class="w-full p-2 border rounded-lg text-sm focus:outline-none hidden">
                    <div class="flex gap-2">
                        <button type="button" onclick="skipStep3()" class="flex-1 border border-stone-300 text-stone-600 p-2 rounded-lg text-sm">Skip</button>
                        <button type="submit" class="flex-1 bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Next →</button>
                    </div>
                </form>
            `);

            window.toggleCustomCat = function() {
                const val = document.getElementById('w_category').value;
                const cust = document.getElementById('w_custom_cat');
                if(val === 'other') cust.classList.remove('hidden');
                else cust.classList.add('hidden');
            }

            window.skipStep3 = function() {
                appendUserMessage("Skipped");
                setTimeout(step4Engagement, 500);
            }

            document.getElementById('step3-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const cat = document.getElementById('w_category').value;
                const cust = document.getElementById('w_custom_cat').value;
                
                if (cat === 'other' && cust) {
                    state.custom_wedding_category = cust;
                    appendUserMessage(`Type: ${cust}`);
                } else if (cat && cat !== 'other') {
                    state.wedding_category_id = cat;
                    const text = document.getElementById('w_category').options[document.getElementById('w_category').selectedIndex].text;
                    appendUserMessage(`Type: ${text}`);
                } else {
                    appendUserMessage("Skipped");
                }
                setTimeout(step4Engagement, 500);
            });
        }

        // STEP 4: Engagement
        function step4Engagement() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 4/8: Is your engagement completed? (Optional)</p>
                <div class="flex gap-2">
                    <button type="button" onclick="handleEngage(1)" class="border border-stone-300 px-4 py-2 rounded-lg text-sm hover:bg-stone-100">Yes</button>
                    <button type="button" onclick="handleEngage(0)" class="border border-stone-300 px-4 py-2 rounded-lg text-sm hover:bg-stone-100">No</button>
                    <button type="button" onclick="handleEngage(null)" class="border border-stone-300 px-4 py-2 rounded-lg text-sm hover:bg-stone-100 text-stone-500">Skip</button>
                </div>
            `);
            window.handleEngage = function(val) {
                if(val !== null) state.is_engagement_completed = val;
                appendUserMessage(val === 1 ? 'Yes' : (val === 0 ? 'No' : 'Skipped'));
                setTimeout(step5WeddingDate, 500);
            }
        }

        // STEP 5: Wedding Date
        function step5WeddingDate() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 5/8: Is the wedding date finalized? (Optional)</p>
                <form id="step5-form" class="space-y-3">
                    <div class="flex gap-2 mb-2">
                        <label class="flex items-center gap-1"><input type="radio" name="d_final" value="1" onchange="toggleDate(1)"> Yes</label>
                        <label class="flex items-center gap-1"><input type="radio" name="d_final" value="0" onchange="toggleDate(0)"> No</label>
                    </div>
                    <input type="date" id="w_date" class="w-full p-2 border rounded-lg text-sm focus:outline-none hidden">
                    <div class="flex gap-2 mt-2">
                        <button type="button" onclick="skipStep5()" class="flex-1 border border-stone-300 text-stone-600 p-2 rounded-lg text-sm">Skip</button>
                        <button type="submit" class="flex-1 bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Next →</button>
                    </div>
                </form>
            `);

            window.toggleDate = function(val) {
                const dt = document.getElementById('w_date');
                if(val == 1) { dt.classList.remove('hidden'); dt.required = true; }
                else { dt.classList.add('hidden'); dt.required = false; }
            }

            window.skipStep5 = function() {
                appendUserMessage("Skipped");
                setTimeout(step6Venue, 500);
            }

            document.getElementById('step5-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const isFinal = document.querySelector('input[name="d_final"]:checked');
                if(isFinal && isFinal.value == "1") {
                    state.is_date_finalized = 1;
                    state.wedding_date = document.getElementById('w_date').value;
                    appendUserMessage(`Date: ${state.wedding_date}`);
                } else if(isFinal && isFinal.value == "0") {
                    state.is_date_finalized = 0;
                    appendUserMessage("Not finalized");
                } else {
                    appendUserMessage("Skipped");
                }
                setTimeout(step6Venue, 500);
            });
        }

        // STEP 6: Venue
        function step6Venue() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 6/8: Is the venue finalized? (Optional)</p>
                <form id="step6-form" class="space-y-3">
                    <div class="flex gap-2 mb-2">
                        <button type="button" onclick="handleVenue(1)" class="border border-stone-300 px-4 py-2 rounded-lg text-sm hover:bg-stone-100">Yes</button>
                        <button type="button" onclick="handleVenue(0)" class="border border-stone-300 px-4 py-2 rounded-lg text-sm hover:bg-stone-100">No</button>
                        <button type="button" onclick="handleVenue(null)" class="border border-stone-300 px-4 py-2 rounded-lg text-sm hover:bg-stone-100 text-stone-500">Skip</button>
                    </div>
                </form>
            `);

            window.handleVenue = function(val) {
                if(val !== null) state.is_venue_finalized = val;
                
                // We no longer ask for venue name, just record Yes/No and auto-advance.
                state.venue_name = null;

                appendUserMessage(val === 1 ? 'Yes' : (val === 0 ? 'No' : 'Skipped'));
                setTimeout(step7CurrentLocation, 500);
            }
        }


        // Location API Helpers
        let apiStates = [];
        async function fetchStates(country = 'India') {
            try {
                let res = await fetch('https://countriesnow.space/api/v0.1/countries/states', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({country})
                });
                let data = await res.json();
                return data.data.states || [];
            } catch(e) { return []; }
        }
        async function fetchCities(country = 'India', state) {
            try {
                let res = await fetch('https://countriesnow.space/api/v0.1/countries/state/cities', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({country, state})
                });
                let data = await res.json();
                return data.data || [];
            } catch(e) { return []; }
        }

        function populateSelect(selectId, items, placeholder, valueKey, textKey) {
            let el = document.getElementById(selectId);
            el.innerHTML = `<option value="">-- ${placeholder} --</option>`;
            items.forEach(item => {
                let val = valueKey ? item[valueKey] : item;
                let txt = textKey ? item[textKey] : item;
                el.innerHTML += `<option value="${val}">${txt}</option>`;
            });
            el.innerHTML += `<option value="other">Other (Type manually)</option>`;
        }

        // STEP 7: Current Location
        async function step7CurrentLocation() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 7/8: Current Location (Optional)</p>
                <form id="step7-form" class="space-y-3">
                    <select id="curr_state" class="w-full p-2 border rounded-lg text-sm focus:outline-none" onchange="handleCurrState()">
                        <option value="">Loading States...</option>
                    </select>
                    
                    <select id="curr_city" class="w-full p-2 border rounded-lg text-sm focus:outline-none hidden" onchange="handleCurrCity()">
                    </select>
                    
                    <input type="text" id="curr_manual" placeholder="Type your city/location" class="w-full p-2 border rounded-lg text-sm focus:outline-none hidden">
                    
                    <div class="flex gap-2 mt-2">
                        <button type="button" onclick="skipStep7()" class="flex-1 border border-stone-300 text-stone-600 p-2 rounded-lg text-sm">Skip</button>
                        <button type="submit" class="flex-1 bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Next →</button>
                    </div>
                </form>
            `);

            if(apiStates.length === 0) apiStates = await fetchStates();
            populateSelect('curr_state', apiStates, 'Select State', 'name', 'name');

            window.handleCurrState = async function() {
                const st = document.getElementById('curr_state').value;
                const ct = document.getElementById('curr_city');
                const mn = document.getElementById('curr_manual');
                
                if(st === 'other') {
                    ct.classList.add('hidden');
                    mn.classList.remove('hidden');
                } else if(st) {
                    ct.innerHTML = '<option value="">Loading Cities...</option>';
                    ct.classList.remove('hidden');
                    mn.classList.add('hidden');
                    let cities = await fetchCities('India', st);
                    populateSelect('curr_city', cities, 'Select City');
                } else {
                    ct.classList.add('hidden');
                    mn.classList.add('hidden');
                }
            }

            window.handleCurrCity = function() {
                const ct = document.getElementById('curr_city').value;
                const mn = document.getElementById('curr_manual');
                if(ct === 'other') mn.classList.remove('hidden');
                else mn.classList.add('hidden');
            }

            window.skipStep7 = function() {
                appendUserMessage("Skipped");
                setTimeout(step8WeddingLocation, 500);
            }

            document.getElementById('step7-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let st = document.getElementById('curr_state').value;
                let ct = document.getElementById('curr_city').value;
                let mn = document.getElementById('curr_manual').value;
                
                if (st === 'other' || ct === 'other') {
                    state.current_city = mn;
                } else {
                    state.current_city = ct || st || null; 
                }
                
                if(state.current_city) appendUserMessage(`Current: ${state.current_city}`);
                else appendUserMessage("Skipped");
                
                setTimeout(step8WeddingLocation, 500);
            });
        }

        // STEP 8: Wedding Location
        async function step8WeddingLocation() {
            appendBotMessage(`
                <p class='font-serif text-base text-wedding-dark mb-2'>Step 8/8: Wedding Location (Optional)</p>
                <form id="step8-form" class="space-y-3">
                    <select id="wed_state" class="w-full p-2 border rounded-lg text-sm focus:outline-none" onchange="handleWedState()">
                        <option value="">Loading States...</option>
                    </select>
                    
                    <select id="wed_city" class="w-full p-2 border rounded-lg text-sm focus:outline-none hidden" onchange="handleWedCity()">
                    </select>
                    
                    <input type="text" id="wed_manual" placeholder="Type your wedding city/location" class="w-full p-2 border rounded-lg text-sm focus:outline-none hidden">
                    
                    <div class="flex gap-2 mt-2">
                        <button type="button" onclick="skipStep8()" class="flex-1 border border-stone-300 text-stone-600 p-2 rounded-lg text-sm">Skip</button>
                        <button type="submit" class="flex-1 bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Finish →</button>
                    </div>
                </form>
            `);

            if(apiStates.length === 0) apiStates = await fetchStates();
            populateSelect('wed_state', apiStates, 'Select State', 'name', 'name');

            window.handleWedState = async function() {
                const st = document.getElementById('wed_state').value;
                const ct = document.getElementById('wed_city');
                const mn = document.getElementById('wed_manual');
                
                if(st === 'other') {
                    ct.classList.add('hidden');
                    mn.classList.remove('hidden');
                } else if(st) {
                    ct.innerHTML = '<option value="">Loading Cities...</option>';
                    ct.classList.remove('hidden');
                    mn.classList.add('hidden');
                    let cities = await fetchCities('India', st);
                    populateSelect('wed_city', cities, 'Select City');
                } else {
                    ct.classList.add('hidden');
                    mn.classList.add('hidden');
                }
            }

            window.handleWedCity = function() {
                const ct = document.getElementById('wed_city').value;
                const mn = document.getElementById('wed_manual');
                if(ct === 'other') mn.classList.remove('hidden');
                else mn.classList.add('hidden');
            }

            window.skipStep8 = function() {
                appendUserMessage("Skipped");
                setTimeout(step9Finish, 500);
            }

            document.getElementById('step8-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let st = document.getElementById('wed_state').value;
                let ct = document.getElementById('wed_city').value;
                let mn = document.getElementById('wed_manual').value;
                
                if (st === 'other' || ct === 'other') {
                    state.wedding_city = mn;
                    state.wedding_state = (st !== 'other') ? st : null;
                } else {
                    state.wedding_city = ct || null;
                    state.wedding_state = st || null;
                }
                
                let locMsg = [];
                if(state.wedding_city) locMsg.push(state.wedding_city);
                if(state.wedding_state) locMsg.push(state.wedding_state);
                
                if(locMsg.length > 0) appendUserMessage(`Wedding Loc: ${locMsg.join(', ')}`);
                else appendUserMessage("Skipped");
                
                setTimeout(step9Finish, 500);
            });
        }

        // STEP 9: Finish
        function step9Finish() {
            inputZone.innerHTML = `
                <button type="button" onclick="submitAll()" id="submit-btn" class="w-full bg-wedding-dark hover:bg-wedding-primary text-white p-3.5 rounded-xl font-semibold text-sm transition tracking-wider uppercase shadow-lg">
                    Edit & Save to Dashboard →
                </button>
            `;
            appendBotMessage(`
                <p class='font-serif text-lg text-wedding-dark mb-1'>All Set!</p>
                <p>Click the button below to save your details and proceed to your planning dashboard.</p>
            `);
            
            window.submitAll = async function() {
                const btn = document.getElementById('submit-btn');
                btn.disabled = true;
                btn.innerText = 'Saving...';
                
                try {
                    let response = await fetch("{{ route('host.wizard.storeQuickSetup') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(state)
                    });
                    
                    let data = await response.json();
                    if(response.ok && data.success) {
                        window.location.href = "{{ route('host.dashboard') }}";
                    } else {
                        alert("Error saving setup: " + (data.message || 'Validation failed.'));
                        btn.disabled = false;
                        btn.innerText = 'Edit & Save to Dashboard →';
                    }
                } catch(e) {
                    alert("A network error occurred.");
                    btn.disabled = false;
                    btn.innerText = 'Edit & Save to Dashboard →';
                }
            }
        }

        // Initialize the flow
        startFlow();

    </script>
</body>
</html>