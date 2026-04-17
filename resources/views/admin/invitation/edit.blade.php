@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Admin: Edit Invitation</h2>
        <a href="{{ route('admin.invitation.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>

    <form action="{{ route('admin.invitation.update', $invitation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card mb-4 shadow-sm border-0 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Assigned Host</label>
                        <select name="host_id" class="form-select" required>
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}" {{ $invitation->host_id == $host->id ? 'selected' : '' }}>{{ $host->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Who is Inviting?</label>
                        <select name="invite" id="invite_dropdown" class="form-select" required>
                            @foreach(['brideparents'=>"Bride's Parents",'groomparents'=>"Groom's Parents",'bride'=>'Bride','groom'=>'Groom','weddingcouple'=>'Wedding Couple'] as $k => $v)
                                <option value="{{ $k }}" {{ $invitation->invite == $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Select Venue</label>
                        <div class="input-group">
                            <select name="venue_id" id="venue_dropdown" class="form-select" required>
                                @foreach($venues as $v)
                                    <option value="{{ $v->id }}" 
                                        data-name="{{ $v->venue_name }}" data-pin="{{ $v->pincode }}" 
                                        data-area="{{ $v->area_name }}" data-district="{{ $v->district }}" 
                                        data-state="{{ $v->state }}" data-addr="{{ $v->venue_address }}" 
                                        data-landmark="{{ $v->wedding_location }}" data-map="{{ $v->location_map }}"
                                        {{ $invitation->venue_id == $v->id ? 'selected' : '' }}>
                                        {{ $v->venue_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" id="btn_add_venue">+</button>
                            <button type="button" class="btn btn-warning" id="btn_edit_venue"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Theme</label>
                        <select name="theme" class="form-select">
                            <option value="classic" {{ $invitation->theme == 'classic' ? 'selected' : '' }}>Classic</option>
                            <option value="royal" {{ $invitation->theme == 'royal' ? 'selected' : '' }}>Royal</option>
                            <option value="floral" {{ $invitation->theme == 'floral' ? 'selected' : '' }}>Floral</option>
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
                        <div class="mb-2"><label>Bride Name</label><input type="text" name="bride_name" value="{{ $invitation->bride_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mobile</label><input type="text" name="bride_number" value="{{ $invitation->bride_number }}" class="form-control" required></div>
                        <div class="mb-2"><label>Email</label><input type="email" name="bride_email" value="{{ $invitation->bride_email }}" class="form-control" required></div>
                        <div class="mb-2"><label>Father</label><input type="text" name="bride_father_name" value="{{ $invitation->bride_father_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mother</label><input type="text" name="bride_mother_name" value="{{ $invitation->bride_mother_name }}" class="form-control" required></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="groom_card_container">
                <div class="card mb-4 shadow-sm border-secondary">
                    <div class="card-header bg-secondary text-white fw-bold">Groom's Information</div>
                    <div class="card-body">
                        <div class="mb-2"><label>Groom Name</label><input type="text" name="groom_name" value="{{ $invitation->groom_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mobile</label><input type="text" name="groom_number" value="{{ $invitation->groom_number }}" class="form-control" required></div>
                        <div class="mb-2"><label>Email</label><input type="email" name="groom_email" value="{{ $invitation->groom_email }}" class="form-control" required></div>
                        <div class="mb-2"><label>Father</label><input type="text" name="groom_father_name" value="{{ $invitation->groom_father_name }}" class="form-control" required></div>
                        <div class="mb-2"><label>Mother</label><input type="text" name="groom_mother_name" value="{{ $invitation->groom_mother_name }}" class="form-control" required></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $invitation->wedding_image) }}" class="img-thumbnail mb-3" style="max-height: 200px;">
                <input type="file" name="wedding_image" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 mb-5 shadow-sm">Update Invitation</button>
    </form>
</div>

@include('admin.invitation.venue_modal')
@endsection