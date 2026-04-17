@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Edit Ceremony: {{ $ceramony->ceramony_name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ceramony.update', $ceramony->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Host</label>
                        <select name="host_id" class="form-select" required>
                            @foreach($hosts as $host)
                                <option value="{{ $host->id }}" {{ $ceramony->host_id == $host->id ? 'selected' : '' }}>
                                    {{ $host->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $ceramony->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Venue</label>
                        <select name="venue_id" class="form-select">
                            <option value="">-- No Venue --</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ $ceramony->venue_id == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->venue_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ceremony Name</label>
                        <input type="text" name="ceramony_name" class="form-control" value="{{ $ceramony->ceramony_name }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="ceramony_date" class="form-control" value="{{ $ceramony->ceramony_date }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" name="ceramony_time" class="form-control" value="{{ $ceramony->ceramony_time }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Current Ceremony Image</label>
                    @if($ceramony->ceramony_image)
                        <img src="{{ asset('storage/' . $ceramony->ceramony_image) }}" class="img-thumbnail mb-2" style="height: 150px;">
                    @else
                        <p class="text-muted">No image uploaded.</p>
                    @endif
                    
                    <label class="form-label d-block mt-2">Upload New Image (Leave blank to keep current)</label>
                    <input type="file" name="ceramony_image" class="form-control">
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.ceramony.index') }}" class="btn btn-secondary">Back to List</a>
                    <button type="submit" class="btn btn-primary">Update Ceremony</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection