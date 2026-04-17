<div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 id="modalTitle">Add Venue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="venueForm">
                    @csrf
                    <input type="hidden" id="v_id">
                    <div class="row g-3">
                        <div class="col-md-6"><label>Venue Name</label><input type="text" id="v_name" class="form-control" required></div>
                        <div class="col-md-6"><label>Pincode</label><input type="text" id="v_pin" class="form-control" maxlength="6" required></div>
                        <div class="col-md-4"><label>Area</label><select id="v_area" class="form-select"></select></div>
                        <div class="col-md-4"><label>District</label><input type="text" id="v_district" class="form-control" readonly></div>
                        <div class="col-md-4"><label>State</label><input type="text" id="v_state" class="form-control" readonly></div>
                        <div class="col-md-6"><label>Landmark</label><input type="text" id="v_wedding_location" class="form-control"></div>
                        <div class="col-md-6"><label>Map Link</label><input type="text" id="v_location_map" class="form-control"></div>
                        <div class="col-12"><label>Address</label><textarea id="v_addr" class="form-control" rows="2" required></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveVenueBtn" class="btn btn-primary w-100 shadow">Save Venue</button>
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