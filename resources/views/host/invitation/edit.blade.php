@extends('layouts.host')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit Wedding Invitation</h2>
        <a href="{{ route('host.invitation.index') }}" class="btn btn-secondary btn-sm shadow-sm">Back to Registry</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('host.invitation.update', $invitation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card mb-4 shadow-sm border-0 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Invitation Sent By</label>
                        <select name="invite" id="invite_dropdown" class="form-select" required>
                            @foreach(['brideparents' => "Bride's Parents", 'groomparents' => "Groom's Parents", 'bride' => 'Bride', 'groom' => 'Groom', 'weddingcouple' => 'Wedding Couple'] as $key => $label)
                                <option value="{{ $key }}" {{ $invitation->invite == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label fw-bold">Select Venue</label>
                        <div class="input-group">
                            <select name="venue_id" id="venue_dropdown" class="form-select" required>
                                <option value="">-- Select Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" 
                                        data-name="{{ $venue->venue_name }}"
                                        data-pin="{{ $venue->pincode }}"
                                        data-area="{{ $venue->area_name }}"
                                        data-district="{{ $venue->district }}"
                                        data-state="{{ $venue->state }}"
                                        data-addr="{{ $venue->venue_address }}"
                                        data-landmark="{{ $venue->wedding_location }}"
                                        data-map="{{ $venue->location_map }}"
                                        {{ $invitation->venue_id == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->venue_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="btn_add_venue">+ Add</button>
                            <button type="button" class="btn btn-warning" id="btn_edit_venue">Edit Selected</button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Theme Style</label>
                        <select name="theme" class="form-select">
                            <option value="classic" {{ $invitation->theme == 'classic' ? 'selected' : '' }}>Classic White</option>
                            <option value="royal" {{ $invitation->theme == 'royal' ? 'selected' : '' }}>Royal Gold</option>
                            <option value="floral" {{ $invitation->theme == 'floral' ? 'selected' : '' }}>Modern Floral</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="party_details_row">
            <div class="col-md-6" id="bride_card_container">
                <div class="card mb-4 shadow-sm border-info">
                    <div class="card-header bg-info text-white fw-bold">Bride's Information</div>
                    <div class="card-body">
                        <div class="mb-2"><label>Full Name</label><input type="text" name="bride_name" value="{{ $invitation->bride_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mobile Number</label><input type="text" name="bride_number" value="{{ $invitation->bride_number }}" class="form-control" required></div>
                        <div class="mb-2"><label>Email Address</label><input type="email" name="bride_email" value="{{ $invitation->bride_email }}" class="form-control" required></div>
                        <div class="mb-2"><label>Father's Name</label><input type="text" name="bride_father_name" value="{{ $invitation->bride_father_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mother's Name</label><input type="text" name="bride_mother_name" value="{{ $invitation->bride_mother_name }}" class="form-control" required></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="groom_card_container">
                <div class="card mb-4 shadow-sm border-secondary">
                    <div class="card-header bg-secondary text-white fw-bold">Groom's Information</div>
                    <div class="card-body">
                        <div class="mb-2"><label>Full Name</label><input type="text" name="groom_name" value="{{ $invitation->groom_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mobile Number</label><input type="text" name="groom_number" value="{{ $invitation->groom_number }}" class="form-control" required></div>
                        <div class="mb-2"><label>Email Address</label><input type="email" name="groom_email" value="{{ $invitation->groom_email }}" class="form-control" required></div>
                        <div class="mb-2"><label>Father's Name</label><input type="text" name="groom_father_name" value="{{ $invitation->groom_father_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mother's Name</label><input type="text" name="groom_mother_name" value="{{ $invitation->groom_mother_name }}" class="form-control" required></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Event Timing & Location</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" name="wedding_date" value="{{ $invitation->wedding_date }}" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" name="wedding_time" value="{{ $invitation->wedding_time }}" class="form-control" required></div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body text-center">
                <label class="fw-bold d-block mb-3">Current Invitation Card Image</label>
                <img src="{{ asset('storage/' . $invitation->wedding_image) }}" class="img-thumbnail mb-4 shadow-sm" style="max-height: 300px;">
                <div class="col-md-6 mx-auto">
                    <input type="file" name="wedding_image" class="form-control">
                    <small class="text-muted mt-2 d-block">Only upload if you wish to change the existing image.</small>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mb-5 shadow">Update Wedding Invitation</button>
    </form>
</div>

<div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 id="modalTitle">Add New Venue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="venueForm">
                @csrf
                <input type="hidden" id="q_v_id"> <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" id="q_v_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" id="q_v_pin" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><select id="q_v_area" class="form-select"></select></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" id="q_v_district" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" id="q_v_state" class="form-control" readonly></div>
                        <div class="col-md-6 mb-3"><label>Landmark</label><input type="text" id="q_v_wedding_location" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Map URL</label><input type="text" id="q_v_location_map" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Full Address</label><textarea id="q_v_addr" class="form-control" rows="2" required></textarea></div>
                    <input type="hidden" id="q_v_country" value="India"><input type="hidden" id="q_v_circle">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 shadow" id="btnSaveVenue">Save Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // 1. SWAP LOGIC
    function applySwap(val) {
        let brideSection = $('#bride_card_container');
        let groomSection = $('#groom_card_container');
        if(val.includes('groom')) {
            groomSection.insertBefore(brideSection);
        } else {
            brideSection.insertBefore(groomSection);
        }
    }
    applySwap($('#invite_dropdown').val());
    $('#invite_dropdown').on('change', function() { applySwap($(this).val()); });

    // 2. PINCODE LOGIC
    function fetchPin(pin, prefix, callback = null) {
        if(pin.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pin}`, function(data) {
                if(data[0].Status === "Success") {
                    let res = data[0].PostOffice;
                    $(`#${prefix}_district`).val(res[0].District);
                    $(`#${prefix}_state`).val(res[0].State);
                    $(`#${prefix}_circle`).val(res[0].Circle);
                    $(`#${prefix}_area`).empty();
                    res.forEach(o => $(`#${prefix}_area`).append(`<option value="${o.Name}">${o.Name}</option>`));
                    if(callback) callback();
                }
            });
        }
    }
    $('#q_v_pin').on('keyup', function() { fetchPin($(this).val(), 'q_v'); });

    // 3. OPEN MODAL FOR ADD
    $('#btn_add_venue').click(function() {
        $('#venueForm')[0].reset();
        $('#q_v_id').val('');
        $('#modalTitle').text('Add New Venue');
        $('#q_v_area').empty();
        $('#venueModal').modal('show');
    });

    // 4. OPEN MODAL FOR EDIT
    $('#btn_edit_venue').click(function() {
        let selected = $('#venue_dropdown option:selected');
        let id = selected.val();
        if(!id) { alert('Please select a venue to edit'); return; }

        $('#q_v_id').val(id);
        $('#q_v_name').val(selected.data('name'));
        $('#q_v_pin').val(selected.data('pin'));
        $('#q_v_addr').val(selected.data('addr'));
        $('#q_v_wedding_location').val(selected.data('landmark'));
        $('#q_v_location_map').val(selected.data('map'));
        $('#modalTitle').text('Edit Venue');

        // Fetch area list based on pin, then set the selected area
        fetchPin(selected.data('pin').toString(), 'q_v', function() {
            $('#q_v_area').val(selected.data('area'));
        });

        $('#venueModal').modal('show');
    });

    // 5. AJAX SAVE (Update or Store)
    $('#venueForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#q_v_id').val();
        let url = id ? `/host/venue/update/${id}` : "{{ route('host.venue.store') }}";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            method: id ? "POST" : "POST", // Laravel standard: use POST with _method PUT for updates
            data: {
                _token: "{{ csrf_token() }}",
                _method: method, 
                venue_name: $('#q_v_name').val(),
                pincode: $('#q_v_pin').val(),
                area_name: $('#q_v_area').val(),
                district: $('#q_v_district').val(),
                state: $('#q_v_state').val(),
                circle: $('#q_v_circle').val(),
                country: $('#q_v_country').val(),
                venue_address: $('#q_v_addr').val(),
                wedding_location: $('#q_v_wedding_location').val(),
                location_map:$('#q_v_location_map').val()
            },
            success: function(res) {
                if(id) {
                    // Update existing option text and data attributes
                    let opt = $(`#venue_dropdown option[value="${id}"]`);
                    opt.text(res.venue_name);
                    opt.data('name', res.venue_name).data('pin', res.pincode).data('area', res.area_name);
                    opt.data('addr', res.venue_address).data('landmark', res.wedding_location).data('map', res.location_map);
                } else {
                    // Append new option
                    $('#venue_dropdown').append(`<option value="${res.id}" 
                        data-name="${res.venue_name}" data-pin="${res.pincode}" 
                        data-area="${res.area_name}" data-addr="${res.venue_address}"
                        data-landmark="${res.wedding_location}" data-map="${res.location_map}" 
                        selected>${res.venue_name}</option>`);
                }
                $('#venueModal').modal('hide');
            },
            error: function() { alert('Error saving venue. Please check your data.'); }
        });
    });
</script>
@endsection