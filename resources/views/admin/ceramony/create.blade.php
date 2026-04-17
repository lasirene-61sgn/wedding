@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">Create New Ceremony</div>
    <div class="card-body">
        <form action="{{ route('admin.ceramony.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Select Host</label>
                    <select name="host_id" class="form-control" required>
                        <option value="">-- Choose Host --</option>
                        @foreach($hosts as $host)
                            <option value="{{ $host->id }}">{{ $host->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Ceremony Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Choose Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Select Venue</label>
                    <select name="venue_id" class="form-control">
                        <option value="">-- Choose Venue (Optional) --</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->venue_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Ceremony Name</label>
                    <input type="text" name="ceramony_name" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Date</label>
                    <input type="date" name="ceramony_date" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Time</label>
                    <input type="time" name="ceramony_time" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Ceremony Banner/Image</label>
                <input type="file" name="ceramony_image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Create Ceremony</button>
        </form>
    </div>
</div>
@endsection