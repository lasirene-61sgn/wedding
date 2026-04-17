@extends('layouts.host') {{-- Using your host layout --}}

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-subtle text-primary me-3">
                            <i class="bi bi-person-circle fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Profile Settings</h4>
                            <p class="text-muted small mb-0">Update your account information and preferences</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('host.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Account Info --}}
                            <div class="col-12">
                                <h6 class="text-primary text-uppercase fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Account Information</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $host->name) }}" placeholder="Enter your full name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $host->email) }}" placeholder="Enter your email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">WhatsApp Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="whatsapp_number" class="form-control rounded-3 border-start-0" value="{{ old('whatsapp_number', $host->whatsapp_number) }}" placeholder="WhatsApp number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Alternate Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="alternate_number" class="form-control rounded-3 border-start-0" value="{{ old('alternate_number', $host->alternate_number) }}" placeholder="Alternate number">
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="col-12 mt-5">
                                <h6 class="text-primary text-uppercase fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Address & Location</h6>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Pincode</label>
                                <input type="text" name="pincode" id="pincode" class="form-control rounded-3" value="{{ old('pincode', $host->pincode) }}" maxlength="6" placeholder="6-digit pincode">
                            </div>
                            <div class="col-md-9">
                                <label class="form-label small fw-bold">Complex/Building/House Name</label>
                                <input type="text" name="complex_name" class="form-control rounded-3" value="{{ old('complex_name', $host->complex_name) }}" placeholder="Building name">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Floor</label>
                                <input type="text" name="floor" class="form-control rounded-3" value="{{ old('floor', $host->floor) }}" placeholder="Floor">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Door No</label>
                                <input type="text" name="door_no" class="form-control rounded-3" value="{{ old('door_no', $host->door_no) }}" placeholder="Door No">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Area / Street Name</label>
                                <input type="text" name="street_name" id="area_name" class="form-control rounded-3" value="{{ old('street_name', $host->street_name) }}" placeholder="Street name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">City / Town</label>
                                <input type="text" name="city" id="city" class="form-control rounded-3 bg-light" value="{{ old('city', $host->city) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">District</label>
                                <input type="text" name="district" id="district" class="form-control rounded-3 bg-light" value="{{ old('district', $host->district) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">State</label>
                                <input type="text" name="state" id="state" class="form-control rounded-3 bg-light" value="{{ old('state', $host->state) }}" readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Google Maps Location Link</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="location_map" class="form-control rounded-3 border-start-0" value="{{ old('location_map', $host->location_map) }}" placeholder="https://maps.google.com/...">
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-12 mt-5">
                                <h6 class="text-primary text-uppercase fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Security</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">New Password (Leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3">
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .rounded-4 {
        border-radius: 1rem !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#pincode').on('keyup', function() {
        let pincode = $(this).val();
        if (pincode.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pincode}`, function(data) {
                if (data[0].Status === "Success") {
                    let post = data[0].PostOffice[0];
                    $('#city').val(post.Block);
                    $('#district').val(post.District);
                    $('#state').val(post.State);
                    $('#area_name').val(post.Name);
                }
            });
        }
    });
</script>
@endsection