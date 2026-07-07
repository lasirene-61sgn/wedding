@extends('layouts.host')

@section('content')

<div class="container mt-4">
    <div class="row">
        <!-- Form Side -->
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Edit Ceremony</h5>
                    <a href="{{ route('host.ceramony.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('host.ceramony.update', $ceramony->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $ceramony->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Venue Location</label>
                                <div class="input-group">
                                    <select name="venue_id" id="venue_select" class="form-select">
                                        <option value="" data-name="Venue to be announced">-- Select Venue --</option>
                                        @foreach($venues as $v)
                                        <option value="{{ $v->id }}"
                                            data-name="{{ $v->venue_name }}"
                                            data-address="{{ $v->venue_address }}"
                                            data-pin="{{ $v->pincode }}"
                                            data-area="{{ $v->area_name }}"
                                            data-district="{{ $v->district }}"
                                            data-state="{{ $v->state }}"
                                            data-country="{{ $v->country }}"
                                            data-circle="{{ $v->circle }}"
                                            data-landmark="{{ $v->wedding_location }}"
                                            data-map="{{ $v->location_map }}"
                                            {{ $ceramony->venue_id == $v->id ? 'selected' : '' }}>
                                            {{ $v->venue_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-warning text-white" id="edit_venue_btn">Edit</button>
                                    <button type="button" class="btn btn-primary" id="new_venue_btn">+ New</button>
                                </div>
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold">Ceremony Name</label>
                            <input type="text" name="ceramony_name" id="ceramony_name" class="form-control" value="{{ $ceramony->ceramony_name }}" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date</label>
                                <input type="date" name="ceramony_date" id="ceramony_date" class="form-control" value="{{ $ceramony->ceramony_date }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Time</label>
                                <input type="time" name="ceramony_time" id="ceramony_time" class="form-control" value="{{ $ceramony->ceramony_time }}">
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="form-label fw-bold">Banner Image</label>
                            @if($ceramony->ceramony_image)
                            <div class="mb-2"><img src="{{ asset('storage/'.$ceramony->ceramony_image) }}" width="100" class="rounded border"></div>
                            @endif
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

                            <input type="text" name="canva_template_id" id="canva_template_id" class="form-control" value="{{ $ceramony->canva_template_id }}" placeholder="Enter Canva Template ID (e.g. DACxxxx)">
                            <small class="text-muted">Enter a template ID if you want Canva to auto-generate an invitation based on the details above.</small>
                            
                            <input type="hidden" name="canva_design_url" id="canva_design_url_input" value="{{ $ceramony->canva_design_url }}">
                            <div id="canva_preview_container" class="mt-3 text-center" style="{{ $ceramony->canva_design_url ? 'display: block;' : 'display: none;' }}">
                                <label class="fw-bold text-success d-block text-start mb-2">Your Canva Design Preview:</label>
                                <img id="canva_preview_image" src="{{ $ceramony->canva_design_url }}" alt="Canva Design" class="img-fluid rounded shadow" style="max-height: 250px;">
                                @if($ceramony->canva_design_url)
                                <div class="mt-2">
                                    <a href="{{ $ceramony->canva_design_url }}" target="_blank" class="btn btn-sm btn-info text-white"><i class="bi bi-box-arrow-up-right me-1"></i> Open Full Image</a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('host.ceramony.index') }}" class="btn btn-light border fw-bold">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Update Ceremony</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitle">Add Venue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="venueForm">
                    @csrf
                    <input type="hidden" name="id" id="v_id">
                    <div class="row g-3">
                        <div class="col-md-6"><label>Venue Name</label><input type="text" name="venue_name" id="v_name" class="form-control" required></div>
                        <div class="col-md-6"><label>Pincode</label><input type="text" name="pincode" id="v_pincode" class="form-control" maxlength="6"></div>
                        <div class="col-md-4"><label>Area</label><select name="area_name" id="v_area" class="form-select"></select></div>
                        <div class="col-md-4"><label>District</label><input type="text" name="district" id="v_district" class="form-control" readonly></div>
                        <div class="col-md-4"><label>State</label><input type="text" name="state" id="v_state" class="form-control" readonly></div>
                        <div class="col-md-4"><label>Country</label><input type="text" name="country" id="v_country" class="form-control" readonly></div>
                        <div class="col-md-4"><label>Circle</label><input type="text" name="circle" id="v_circle" class="form-control" readonly></div>
                        <div class="col-md-4"><label>Landmark</label><input type="text" name="wedding_location" id="v_wedding_location" class="form-control"></div>
                        <div class="col-md-12"><label>Map URL</label><input type="text" name="location_map" id="v_location_map" class="form-control"></div>
                        <div class="col-12"><label>Full Address</label><textarea name="venue_address" id="v_address" class="form-control" rows="2"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveVenueBtn" class="btn btn-primary px-4">Save Venue</button>
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
                let url = '{{ route("canva.redirect") }}?ceramony_id={{ $ceramony->id ?? "" }}';
                if (templateId) {
                    url += '&template_id=' + encodeURIComponent(templateId);
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
<script>

        // --- Venue Modal Logic ---
        const modalElement = document.getElementById('venueModal');
        const venueModal = new bootstrap.Modal(modalElement);

        document.getElementById('new_venue_btn').addEventListener('click', () => {
            document.getElementById('venueForm').reset();
            document.getElementById('v_id').value = '';
            document.getElementById('v_area').innerHTML = '';
            document.getElementById('modalTitle').innerText = "Add New Venue";
            venueModal.show();
        });

        document.getElementById('edit_venue_btn').addEventListener('click', () => {
            const select = document.getElementById('venue_select');
            const opt = select.options[select.selectedIndex];

            if (!select.value) return alert("Please select a venue from the list first!");

            document.getElementById('v_id').value = select.value;
            document.getElementById('v_name').value = opt.dataset.name || '';
            document.getElementById('v_address').value = opt.dataset.address || '';
            document.getElementById('v_pincode').value = opt.dataset.pin || '';
            document.getElementById('v_district').value = opt.dataset.district || '';
            document.getElementById('v_state').value = opt.dataset.state || '';
            document.getElementById('v_country').value = opt.dataset.country || 'India';
            document.getElementById('v_circle').value = opt.dataset.circle || '';
            document.getElementById('v_wedding_location').value = opt.dataset.landmark || '';
            document.getElementById('v_location_map').value = opt.dataset.map || '';

            const areaVal = opt.dataset.area;
            const areaSelect = document.getElementById('v_area');
            areaSelect.innerHTML = areaVal ? `<option value="${areaVal}" selected>${areaVal}</option>` : '';

            document.getElementById('modalTitle').innerText = "Update Venue Details";
            venueModal.show();
        });

        document.getElementById('v_pincode').addEventListener('keyup', function() {
            if (this.value.length === 6) {
                fetch(`https://api.postalpincode.in/pincode/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data[0].Status === "Success") {
                            let offices = data[0].PostOffice;
                            let area = document.getElementById('v_area');
                            area.innerHTML = '';
                            offices.forEach(o => {
                                area.innerHTML += `<option value="${o.Name}">${o.Name}</option>`;
                            });
                            document.getElementById('v_district').value = offices[0].District;
                            document.getElementById('v_state').value = offices[0].State;
                            document.getElementById('v_country').value = offices[0].Country;
                            document.getElementById('v_circle').value = offices[0].Circle;
                        }
                    });
            }
        });

        document.getElementById('saveVenueBtn').addEventListener('click', function() {
            const form = document.getElementById('venueForm');
            const formData = new FormData(form);
            const id = document.getElementById('v_id').value;

            let url = id ? `/host/venue/update/${id}` : "{{ route('host.venue.store') }}";

            if (id) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.id) {
                        location.reload();
                    } else {
                        alert("Error saving venue. Check console.");
                        console.log(data);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Something went wrong!");
                });
        });
</script>
@endpush
@endsection