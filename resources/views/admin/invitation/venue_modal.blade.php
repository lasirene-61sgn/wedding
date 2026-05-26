<!-- Main Modal Wrapper (Hidden by default using 'hidden'. Toggle this class with JavaScript) -->
<div id="venueModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
    <!-- Backdrop overlay -->
    <div class="fixed inset-0 bg-black/50 transition-opacity"></div>

    <!-- Modal Positioning Container -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-3xl border border-gray-100">
            
            <!-- Modal Header -->
            <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
                <h5 id="modalTitle" class="text-lg font-bold text-white tracking-wide">Add Venue</h5>
                <button type="button" class="text-gray-400 hover:text-white transition-colors text-2xl font-semibold leading-none focus:outline-none" onclick="document.getElementById('venueModal').classList.add('hidden')">
                    &times;
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <form id="venueForm">
                    @csrf
                    <input type="hidden" id="v_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Venue Name -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Venue Name</label>
                            <input type="text" id="v_name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" required>
                        </div>
                        
                        <!-- Pincode -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Pincode</label>
                            <input type="text" id="v_pin" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" maxlength="6" required>
                        </div>
                        
                        <!-- Area Dropdown -->
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Area</label>
                            <select id="v_area" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white py-2 px-3"></select>
                        </div>
                        
                        <!-- District (Readonly) -->
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">District</label>
                            <input type="text" id="v_district" class="block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 cursor-not-allowed" readonly>
                        </div>
                        
                        <!-- State (Readonly) -->
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">State</label>
                            <input type="text" id="v_state" class="block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 text-sm py-2 px-3 cursor-not-allowed" readonly>
                        </div>
                        
                        <!-- Landmark -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Landmark</label>
                            <input type="text" id="v_wedding_location" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
                        </div>
                        
                        <!-- Map Link -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Map Link</label>
                            <input type="text" id="v_location_map" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3">
                        </div>
                        
                        <!-- Address TextArea -->
                        <div class="md:col-span-12">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Address</label>
                            <textarea id="v_addr" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3" rows="2" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 pb-6 pt-2">
                <button type="button" id="saveVenueBtn" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    Save Venue
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    const vModal = new bootstrap.Modal(document.getElementById('venueModal'));

    // 1. SWAP LOGIC
    function applySwap(val) {
        let bSection = $('#bride_card_container');
        let gSection = $('#groom_card_container');
        if(val.includes('groom')) { gSection.insertBefore(bSection); } 
        else { bSection.insertBefore(gSection); }
    }
    $('#invite_dropdown').on('change', function() { applySwap($(this).val()); });
    applySwap($('#invite_dropdown').val());

    // 2. PINCODE LOGIC
    function fetchPin(pin, callback = null) {
        if(pin.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pin}`, function(data) {
                if(data[0].Status === "Success") {
                    let res = data[0].PostOffice;
                    $('#v_district').val(res[0].District);
                    $('#v_state').val(res[0].State);
                    $('#v_area').empty();
                    res.forEach(o => $('#v_area').append(`<option value="${o.Name}">${o.Name}</option>`));
                    if(callback) callback();
                }
            });
        }
    }
    $('#v_pin').on('keyup', function() { fetchPin($(this).val()); });

    // 3. MODAL TRIGGERS
    $('#btn_add_venue').click(function() {
        $('#venueForm')[0].reset();
        $('#v_id').val('');
        $('#modalTitle').text('Add New Venue');
        vModal.show();
    });

    $('#btn_edit_venue').click(function() {
        let sel = $('#venue_dropdown option:selected');
        if(!sel.val()) return alert('Select venue first');
        $('#v_id').val(sel.val());
        $('#v_name').val(sel.data('name'));
        $('#v_pin').val(sel.data('pin'));
        $('#v_addr').val(sel.data('addr'));
        $('#v_wedding_location').val(sel.data('landmark'));
        $('#v_location_map').val(sel.data('map'));
        $('#modalTitle').text('Edit Venue');
        fetchPin(sel.data('pin').toString(), function() { $('#v_area').val(sel.data('area')); });
        vModal.show();
    });

    $('#saveVenueBtn').click(function() {
    let id = $('#v_id').val();
    let selectedHostId = $('select[name="host_id"]').val(); // Get the host selected in the admin form

    if(!selectedHostId && !id) {
        alert("Please select a Host first!");
        return;
    }

    $.ajax({
        url: id ? `/host/venue/update/${id}` : "{{ route('host.venue.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            _method: id ? "PUT" : "POST",
            host_id: selectedHostId, // CRITICAL: Send the host_id
            venue_name: $('#v_name').val(),
            pincode: $('#v_pin').val(),
            area_name: $('#v_area').val(),
            district: $('#v_district').val(),
            state: $('#v_state').val(),
            circle: $('#v_circle').val() || 'N/A', // Default if hidden
            country: $('#v_country').val() || 'India',
            venue_address: $('#v_addr').val(),
            wedding_location: $('#v_wedding_location').val(),
            location_map: $('#v_location_map').val()
        },
        success: function(res) {
            alert("Venue successfully linked to host!");
            // Add the new venue to the dropdown and select it
            $('#venue_dropdown').append(`<option value="${res.id}" selected>${res.venue_name}</option>`);
            bootstrap.Modal.getInstance(document.getElementById('venueModal')).hide();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                console.log(errors);
                alert("Validation Failed: " + Object.values(errors).flat().join("\n"));
            }
        }
    });
});
});
</script>