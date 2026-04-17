@extends('layouts.host')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Create Wedding Invitation</h2>
        <a href="{{ route('host.invitation.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('host.invitation.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card mb-4 shadow-sm border-0 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Who is Inviting?</label>
                        <select name="invite" id="invite_dropdown" class="form-select" required>
                            <option value="brideparents">Bride's Parents</option>
                            <option value="groomparents">Groom's Parents</option>
                            <option value="bride">Bride</option>
                            <option value="groom">Groom</option>
                            <option value="weddingcouple">Wedding Couple</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Select Venue</label>
                        <div class="input-group">
                            <select name="venue_id" id="venue_dropdown" class="form-select" required>
                                <option value="">-- Choose Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->venue_name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">+ Add New</button>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Invitation Theme</label>
                        <select name="theme" class="form-select">
                            <option value="classic">Classic White</option>
                            <option value="royal">Royal Gold</option>
                            <option value="floral">Modern Floral</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="party_details_row">
            <div class="col-md-6" id="bride_card_container">
                <div class="card mb-4 shadow-sm border-info">
                    <div class="card-header bg-info text-white fw-bold" id="bride_header_text">Bride's Information</div>
                    <div class="card-body">
                        <div class="mb-2"><label>Bride Name</label><input type="text" name="bride_name" class="form-control" required></div>
                        <div class="mb-2"><label>Mobile</label><input type="text" name="bride_number" class="form-control" required></div>
                        <div class="mb-2"><label>Email</label><input type="email" name="bride_email" class="form-control" required></div>
                        <div class="mb-2"><label>Father's Name</label><input type="text" name="bride_father_name" class="form-control" required></div>
                        <div class="mb-2"><label>Mother's Name</label><input type="text" name="bride_mother_name" class="form-control" required></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="groom_card_container">
                <div class="card mb-4 shadow-sm border-secondary">
                    <div class="card-header bg-secondary text-white fw-bold" id="groom_header_text">Groom's Information</div>
                    <div class="card-body">
                        <div class="mb-2"><label>Groom Name</label><input type="text" name="groom_name" class="form-control" required></div>
                        <div class="mb-2"><label>Mobile</label><input type="text" name="groom_number" class="form-control" required></div>
                        <div class="mb-2"><label>Email</label><input type="email" name="groom_email" class="form-control" required></div>
                        <div class="mb-2"><label>Father's Name</label><input type="text" name="groom_father_name" class="form-control" required></div>
                        <div class="mb-2"><label>Mother's Name</label><input type="text" name="groom_mother_name" class="form-control" required></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold">Event Location & Schedule</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Wedding Date</label><input type="date" name="wedding_date" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Wedding Time</label><input type="time" name="wedding_time" class="form-control" required></div>
                    <!-- <div class="col-md-4 mb-3"><label>Landmark</label><input type="text" name="wedding_location" class="form-control" placeholder="Near Temple, Hall, etc."></div> -->
                </div>
                <!-- <div class="row">
                    <div class="col-md-2 mb-3"><label>Pincode</label><input type="text" name="pincode" id="inv_pincode" class="form-control" maxlength="6"></div>
                    <div class="col-md-3 mb-3"><label>Area</label><select name="area_name" id="inv_area" class="form-select"></select></div>
                    <div class="col-md-2 mb-3"><label>District</label><input type="text" name="district" id="inv_district" class="form-control" readonly></div>
                    <div class="col-md-2 mb-3"><label>State</label><input type="text" name="state" id="inv_state" class="form-control" readonly></div>
                    <div class="col-md-3 mb-3"><label>Country</label><input type="text" name="country" id="inv_country" value="India" class="form-control" readonly></div>
                    <input type="hidden" name="circle" id="inv_circle">
                </div> -->
                <div class="mt-3">
                    <label class="fw-bold">Upload Invitation Image</label>
                    <input type="file" name="wedding_image" class="form-control" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 mb-5 shadow-sm">Save & Generate Invitation</button>
    </form>
</div>

<div class="modal fade" id="addVenueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white"><h5>Add New Venue</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form id="quickVenueForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" id="q_v_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" id="q_v_pin" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><select id="q_v_area" class="form-select"></select></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" id="q_v_district" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" id="q_v_state" class="form-control" readonly></div>
                        <div class="col-md-4 mb-3"><label>Landmark</label><input type="text" id="q_v_wedding_location" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Map</label><input type="text" id="q_v_location_map" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Detailed Address</label><textarea id="q_v_addr" class="form-control" rows="2" required></textarea></div>
                    <input type="hidden" id="q_v_country" value="India"><input type="hidden" id="q_v_circle">
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary w-100 shadow">Save Venue</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // LOGIC: SWAP BRIDE AND GROOM BASED ON INVITER
    $('#invite_dropdown').on('change', function() {
        let inviter = $(this).val();
        let brideSection = $('#bride_card_container');
        let groomSection = $('#groom_card_container');

        if(inviter === 'groomparents' || inviter === 'groom') {
            groomSection.insertBefore(brideSection);
            $('#groom_header_text').text('Primary: Groom Details');
            $('#bride_header_text').text('Secondary: Bride Details');
        } else {
            brideSection.insertBefore(groomSection);
            $('#bride_header_text').text('Primary: Bride Details');
            $('#groom_header_text').text('Secondary: Groom Details');
        }
    });

    // LOGIC: PINCODE FETCHING
    function fetchAddress(pin, prefix) {
        if(pin.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pin}`, function(data) {
                if(data[0].Status === "Success") {
                    let post = data[0].PostOffice;
                    $(`#${prefix}_district`).val(post[0].District);
                    $(`#${prefix}_state`).val(post[0].State);
                    $(`#${prefix}_circle`).val(post[0].Circle);
                    $(`#${prefix}_area`).empty();
                    post.forEach(o => $(`#${prefix}_area`).append(`<option value="${o.Name}">${o.Name}</option>`));
                }
            });
        }
    }
    $('#inv_pincode').on('keyup', function() { fetchAddress($(this).val(), 'inv'); });
    $('#q_v_pin').on('keyup', function() { fetchAddress($(this).val(), 'q_v'); });

    // LOGIC: AJAX VENUE STORE
    $('#quickVenueForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('host.venue.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
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
                $('#venue_dropdown').append(`<option value="${res.id}" selected>${res.venue_name}</option>`);
                $('#addVenueModal').modal('hide');
                $('#quickVenueForm')[0].reset();
            }
        });
    });
</script>
@endsection