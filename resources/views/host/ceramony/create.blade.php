@extends('layouts.host')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0 col-md-8 mx-auto">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">Create New Ceremony</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('host.ceramony.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Select Venue</label>
                        <div class="input-group">
                            <select name="venue_id" id="venue_select" class="form-select">
                                <option value="">-- Choose My Venue --</option>
                                @foreach($venues as $v)
                                    <option value="{{ $v->id }}">{{ $v->venue_name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">
                                + New
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ceremony Name</label>
                    <input type="text" name="ceramony_name" class="form-control" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="ceramony_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time</label>
                        <input type="time" name="ceramony_time" class="form-control">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Ceremony Banner</label>
                    <input type="file" name="ceramony_image" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Ceremony</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addVenueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Add Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quickVenueForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Venue Name</label>
                            <input type="text" id="v_name" name="venue_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Pincode</label>
                            <input type="text" id="v_pincode" name="pincode" class="form-control" maxlength="6">
                            <small id="pin_load" class="text-primary" style="display:none;">Fetching...</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Area</label>
                            <select id="v_area" name="area_name" class="form-select"></select>
                        </div>
                        <div class="col-md-4">
                            <label>District</label>
                            <input type="text" id="v_district" name="district" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>State</label>
                            <input type="text" id="v_state" name="state" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Landmark</label>
                            <input type="text" id="v_wedding_location" name="wedding_location" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Map</label>
                            <input type="text" id="v_location_map" name="location_map" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Full Address</label>
                        <textarea id="v_address" name="venue_address" class="form-control"></textarea>
                    </div>
                    <input type="hidden" id="v_circle" name="circle">
                    <input type="hidden" id="v_country" name="country">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveVenueBtn" class="btn btn-primary">Save & Select Venue</button>
            </div>
        </div>
    </div>
</div>

<script>
// PINCODE API FETCHING
document.getElementById('v_pincode').addEventListener('keyup', function() {
    let pin = this.value;
    if (pin.length === 6) {
        document.getElementById('pin_load').style.display = 'block';
        fetch(`https://api.postalpincode.in/pincode/${pin}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('pin_load').style.display = 'none';
                if (data[0].Status === "Success") {
                    let offices = data[0].PostOffice;
                    let area = document.getElementById('v_area');
                    area.innerHTML = '';
                    offices.forEach(o => {
                        area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                    });
                    document.getElementById('v_district').value = offices[0].District;
                    document.getElementById('v_state').value = offices[0].State;
                    document.getElementById('v_circle').value = offices[0].Circle;
                    document.getElementById('v_country').value = offices[0].Country;
                }
            });
    }
});

// AJAX TO SAVE VENUE AND AUTO-SELECT IT
document.getElementById('saveVenueBtn').addEventListener('click', function() {
    let formData = new FormData(document.getElementById('quickVenueForm'));
    
    fetch("{{ route('host.venue.store') }}", {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        // Add the new venue to the dropdown and select it
        let select = document.getElementById('venue_select');
        let option = new Option(data.venue_name, data.id, true, true);
        select.add(option);
        
        // Close modal
        var myModalEl = document.getElementById('addVenueModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();
    })
    .catch(err => alert("Error saving venue. Make sure all fields are filled."));
});
</script>
@endsection