@extends('layouts.host')

@section('content')


<div class="container mt-4">
    <div class="row">
        <!-- Form Side -->
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4">
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
                                        <option value="" data-name="Venue to be announced">-- Choose My Venue --</option>
                                        @foreach($venues as $v)
                                        <option value="{{ $v->id }}" data-name="{{ $v->venue_name }}">{{ $v->venue_name }}</option>
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
                            <input type="text" name="ceramony_name" id="ceramony_name" class="form-control" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="ceramony_date" id="ceramony_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Time</label>
                                <input type="time" name="ceramony_time" id="ceramony_time" class="form-control">
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold">Banner Image</label>
                            <input type="file" name="ceramony_image" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Canva Integration</label>
                            
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" id="btn-design-on-canva" class="btn btn-outline-info rounded-pill fw-bold">
                                    <i class="bi bi-magic me-2"></i> Design on Canva
                                </button>
                                <span class="text-muted small">or enter a Template ID below to auto-generate:</span>
                            </div>
                            
                            <input type="text" name="canva_template_id" id="canva_template_id" class="form-control" placeholder="Enter Canva Template ID (e.g. DACxxxx)">
                            <small class="text-muted">Enter a template ID if you want Canva to auto-generate an invitation based on the details above.</small>
                            
                            <input type="hidden" name="canva_design_url" id="canva_design_url_input">
                            <div id="canva_preview_container" class="mt-3 text-center" style="display: none;">
                                <label class="fw-bold text-success d-block text-start mb-2">Your Canva Design Preview:</label>
                                <img id="canva_preview_image" src="" alt="Canva Design" class="img-fluid rounded shadow" style="max-height: 250px;">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Create Ceremony</button>
                        </div>
                    </form>
                </div>
            </div>
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

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnDesignOnCanva = document.getElementById('btn-design-on-canva');
        if (btnDesignOnCanva) {
            btnDesignOnCanva.addEventListener('click', function() {
                const tplInput = document.getElementById('canva_template_id');
                const templateId = tplInput ? tplInput.value.trim() : '';
                let url = '{{ route("canva.redirect") }}';
                if (templateId) {
                    url += '?template_id=' + encodeURIComponent(templateId);
                }
                window.location.href = url;
            });
        }
    });

    document.getElementById('saveVenueBtn').addEventListener('click', function(e) {
        e.preventDefault();
        let formData = new FormData(document.getElementById('quickVenueForm'));
        fetch("{{ route('host.venue.store') }}", {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                let select = document.getElementById('venue_select');
                let option = new Option(data.venue_name, data.id, true, true);
                option.setAttribute('data-name', data.venue_name);
                select.add(option);
                select.dispatchEvent(new Event('change'));
                var modal = bootstrap.Modal.getInstance(document.getElementById('addVenueModal'));
                modal.hide();
            })
            .catch(err => alert("Error saving venue."));
    });
</script>
@endpush
@endsection