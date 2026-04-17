@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Admin: Create Invitation</h2>
        <a href="{{ route('admin.invitation.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <form action="{{ route('admin.invitation.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card mb-4 shadow-sm border-0 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-danger">Assign to Host (User)</label>
                        <select name="host_id" class="form-select" required>
                            <option value="">-- Select Host --</option>
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}">{{ $host->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Who is Inviting?</label>
                        <select name="invite" id="invite_dropdown" class="form-select" required>
                            <option value="brideparents">Bride's Parents</option>
                            <option value="groomparents">Groom's Parents</option>
                            <option value="bride">Bride</option>
                            <option value="groom">Groom</option>
                            <option value="weddingcouple">Wedding Couple</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Select Venue</label>
                        <div class="input-group">
                            <select name="venue_id" id="venue_dropdown" class="form-select" required>
                                <option value="">-- Choose Venue --</option>
                                @foreach($venues as $v)
                                    <option value="{{ $v->id }}" 
                                        data-name="{{ $v->venue_name }}" data-pin="{{ $v->pincode }}" 
                                        data-area="{{ $v->area_name }}" data-district="{{ $v->district }}" 
                                        data-state="{{ $v->state }}" data-addr="{{ $v->venue_address }}" 
                                        data-landmark="{{ $v->wedding_location }}" data-map="{{ $v->location_map }}">
                                        {{ $v->venue_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="btn_add_venue">+</button>
                            <button type="button" class="btn btn-warning" id="btn_edit_venue"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
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
            <div class="card-header bg-dark text-white fw-bold">Schedule & Image</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Wedding Date</label><input type="date" name="wedding_date" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>Wedding Time</label><input type="time" name="wedding_time" class="form-control" required></div>
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Upload Invitation Image</label>
                        <input type="file" name="wedding_image" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 mb-5 shadow-sm">Generate Invitation</button>
    </form>
</div>

@include('admin.invitation.venue_modal') {{-- See Step 3 for this file --}}
@endsection