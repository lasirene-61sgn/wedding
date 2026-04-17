@extends('layouts.admin')

@section('content')
<div class="container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Edit Host: {{ $host->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.host.update', $host->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h5 class="text-primary mb-3">Basic Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $host->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $host->email) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Primary Mobile</label>
                        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $host->mobile) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Alternate Number</label>
                        <input type="text" name="alternate_number" class="form-control" value="{{ old('alternate_number', $host->alternate_number) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $host->whatsapp_number) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">New Password <span class="text-muted small">(Leave blank to keep current)</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    </div>
                </div>

                <hr>

                <h5 class="text-primary mb-3">Address & Profile Details</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" id="pincode" class="form-control" value="{{ old('pincode', $host->pincode) }}" maxlength="6">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Complex Name</label>
                        <input type="text" name="complex_name" class="form-control" value="{{ old('complex_name', $host->complex_name) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Floor / Door No</label>
                        <div class="input-group">
                            <input type="text" name="floor" class="form-control" placeholder="Floor" value="{{ old('floor', $host->floor) }}">
                            <input type="text" name="door_no" class="form-control" placeholder="Door" value="{{ old('door_no', $host->door_no) }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Street/Area</label>
                        <input type="text" name="street_name" id="area_name" class="form-control" value="{{ old('street_name', $host->street_name) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $host->city) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">District</label>
                        <input type="text" name="district" id="district" class="form-control" value="{{ old('district', $host->district) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $host->state) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $host->country ?? 'India') }}">
                    </div>
                </div>

                <hr>

                <h5 class="text-primary mb-3">Access Control & Plan</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Package & Status</label>
                        <div class="input-group mb-2">
                            <select name="package_id" class="form-select">
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id', $host->package_id) == $package->id ? 'selected' : '' }}>
                                        {{ $package->package_name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $host->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $host->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sidebar Permissions</label>
                        <div class="d-flex flex-wrap gap-3 border p-2 rounded">
                            @php
                                $modules = ['Ceremonies', 'Gallery', 'Invitation', 'Save The Date', 'Guest List', 'Reports', 'Categories'];
                                $currentPermissions = $host->permissions ?? [];
                            @endphp
                            @foreach($modules as $module)
                                @php $slug = Str::slug($module); @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $slug }}" 
                                        id="perm_{{ $loop->index }}" 
                                        {{ in_array($slug, $currentPermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $loop->index }}">{{ $module }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">Update Host Account</button>
                    <a href="{{ route('admin.host.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

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