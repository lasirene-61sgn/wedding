const ceremonyCategories = [];
let state = {
    setup_role: '',
    creator_relationship: '',
    bride_name: '',
    bride_email: '',
    groom_name: '',
    groom_email: '',
    wedding_category_id: null,
    custom_wedding_category: '',
    current_city: '',
    wedding_city: '',
    wedding_state: ''
};

function scrollToBottom() {}
function appendBotMessage(messageHtml) {}
function appendUserMessage(text) {}
function startFlow() {}

// STEP 1: Role
function step1Role() {
    appendBotMessage(`
        <p class='font-serif text-base text-wedding-dark mb-2'>Step 1: Who is filling this setup?</p>
        <div class="flex gap-2">
            <button onclick="handleRole('Bride')" class="bg-wedding-dark text-white px-4 py-2 rounded-lg text-sm hover:bg-wedding-primary">Bride</button>
            <button onclick="handleRole('Groom')" class="bg-wedding-dark text-white px-4 py-2 rounded-lg text-sm hover:bg-wedding-primary">Groom</button>
            <button onclick="handleRole('Other')" class="border border-stone-300 text-stone-700 px-4 py-2 rounded-lg text-sm hover:bg-stone-100">Other</button>
        </div>
    `);
}

window.handleRole = function(role) {
    state.setup_role = role;
    appendUserMessage(role);
    
    if (role === 'Other') {
        setTimeout(step1Relationship, 500);
    } else {
        state.creator_relationship = role;
        setTimeout(step2Details, 500);
    }
}

function step1Relationship() {
    appendBotMessage(`
        <p class='text-sm mb-2'>What is your relationship to the couple?</p>
        <div class="flex flex-wrap gap-2 mb-3">
            <button type="button" onclick="setRel('Father')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Father</button>
            <button type="button" onclick="setRel('Mother')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Mother</button>
            <button type="button" onclick="setRel('Uncle')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Uncle</button>
            <button type="button" onclick="setRel('Friend')" class="border border-stone-300 px-3 py-1.5 rounded-lg text-sm hover:bg-stone-100">Friend</button>
        </div>
        <div class="flex gap-2">
            <input type="text" id="custom_rel" placeholder="Or type other relationship" class="p-2 border rounded-lg text-sm flex-1 focus:outline-none">
            <button onclick="handleRel()" class="bg-wedding-dark text-white px-4 py-2 rounded-lg text-sm">Next</button>
        </div>
    `);
    window.setRel = function(val) {}
    window.handleRel = function() {}
}

// STEP 2: Details
function step2Details() {
    appendBotMessage(`
        <p class='font-serif text-base text-wedding-dark mb-2'>Step 2: Couple Details (Required)</p>
        <form id="step2-form" class="space-y-3">
            <input type="text" id="b_name" required placeholder="Bride's Name *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <input type="email" id="b_email" required placeholder="Bride's Email *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <input type="text" id="g_name" required placeholder="Groom's Name *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <input type="email" id="g_email" required placeholder="Groom's Email *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <button type="submit" class="w-full bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Save Details →</button>
        </form>
    `);
}

// STEP 3: Type of Wedding
function step3Category() {
    let catOptions = '<option value="">-- Select Event Category --</option>';
    ceremonyCategories.forEach(cat => {
        catOptions += `<option value="${cat.id}">${cat.category_name}</option>`;
    });

    appendBotMessage(`
        <p class='font-serif text-base text-wedding-dark mb-2'>Step 3: Type of Wedding (Optional)</p>
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
}

function step4Location() {
    appendBotMessage(`
        <p class='font-serif text-base text-wedding-dark mb-2'>Step 4: Location Details</p>
        <form id="step4-form" class="space-y-3">
            <input type="text" id="l_current" required placeholder="Current City (Required) *" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <input type="text" id="l_wedding_city" placeholder="Wedding City (Optional)" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <input type="text" id="l_wedding_state" placeholder="Wedding State (Optional)" class="w-full p-2 border rounded-lg text-sm focus:outline-none">
            <div class="flex gap-2">
                <button type="submit" class="w-full bg-wedding-dark text-white p-2 rounded-lg text-sm font-semibold">Next →</button>
            </div>
        </form>
    `);
}
