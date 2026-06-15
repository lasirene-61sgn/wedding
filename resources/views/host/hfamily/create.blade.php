@extends('layouts.host')

@section('content')
<div class="container-fluid max-width-lg">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-success"></i>Create Host Family Details</h5>
        </div>
        <div class="card-body p-4">
            <div class="mb-4 bg-white p-3 border rounded shadow-sm">
                <div class="form-check form-switch fs-5">
                    <input class="form-check-input cursor-pointer" type="checkbox" name="is_active" id="isActiveToggle" value="1" checked>
                    <label class="form-check-label fw-bold text-dark cursor-pointer" for="isActiveToggle">
                        Show on Guest Panel
                    </label>
                </div>
                <small class="text-muted d-block mt-1">If turned off, this entire family section will remain completely hidden from your guests' invitation dashboards.</small>
            </div>
            <form action="{{ route('host.hfamily.store') }}" method="POST">
                @csrf

                <!-- Theme Selection Group Component -->
                <div class="mb-4">
                    <label class="form-label d-block mb-3"><strong>Select Guest Panel Background Theme</strong></label>
                    <div class="row g-3">
                        @foreach($backgrounds as $bg)
                        <div class="col-6 col-md-3">
                            <label class="card h-100 text-center border p-2 position-relative cursor-pointer hover-shadow">
                                <input type="radio" name="selected_background_id" value="{{ $bg->id }}" class="position-absolute top-0 start-0 m-2" {{ old('selected_background_id') == $bg->id ? 'checked' : '' }}>
                                <img src="{{ asset('storage/' . $bg->image_path) }}" class="card-img-top img-fluid rounded" style="height: 120px; object-fit: cover;">
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('selected_background_id')
                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                    @enderror
                </div>

                <hr class="my-4">

                <!-- Input Content Matrix -->
                <h5 class="text-secondary fw-bold mb-4"><i class="bi bi-card-text me-2"></i>Invitation Topic Layout</h5>

                @php
                $fields = [
                'one' => 'First Topic',
                'two' => 'Second Topic',
                'three' => 'Third Topic',
                'four' => 'Fourth Topic',
                'five' => 'Fifth Topic',
                'six' => 'Sixth Topic'
                ];
                @endphp

                @foreach($fields as $key => $label)
                <div class="row g-3 mb-4 align-items-start border-bottom pb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">{{ $label }} Heading Title</label>
                        <input type="text" name="topic_title_{{ $key }}" class="form-control" value="{{ old('topic_title_'.$key) }}" placeholder="e.g., Groom's Parents">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-dark">{{ $label }} Content Body / Names</label>
                        <textarea name="text{{ $key }}" class="form-control" rows="2" placeholder="List family members or relevant notes..."></textarea>
                    </div>
                </div>
                @endforeach

                <!-- Standalone Text Field Seven -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Additional Invitation Footer Notes (Text Seven)</label>
                    <textarea name="textseven" class="form-control" rows="3" placeholder="Any final matching invitation details..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('host.hfamily.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection