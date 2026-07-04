@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Ceremony Backgrounds</h3>
            <p class="text-secondary small mb-0">Manage background options available to hosts</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background: #ecfdf5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Upload Section -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-cloud-upload text-primary me-2"></i>Upload New</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.ceramony.backgrounds.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 rounded-4 border border-dashed text-center">
                        @csrf
                        <div class="mb-4">
                            <i class="bi bi-image text-muted opacity-50 mb-3 d-block" style="font-size: 3rem;"></i>
                            <label for="background_image" class="form-label fw-bold text-secondary">Choose Background Image</label>
                            <input type="file" name="background_image" id="background_image" class="form-control rounded-3 @error('background_image') is-invalid @enderror" required accept="image/*">
                            @error('background_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="form-text small mt-2">Supported: JPG, PNG, WEBP (Max: 4MB)</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-upload me-2"></i>Upload Image
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Available Backgrounds Section -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-images text-primary me-2"></i>Available Choices</h5>
                </div>
                <div class="card-body p-4">
                    @if($backgrounds->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-folder-x text-muted opacity-25" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No backgrounds uploaded yet</h5>
                            <p class="text-secondary small mb-0">Upload a background image on the left panel to get started.</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($backgrounds as $bg)
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative group-hover">
                                        <img src="{{ asset('storage/' . $bg->image_path) }}" class="card-img-top w-100" style="height: 180px; object-fit: cover;" alt="Background Option">
                                        <div class="card-img-overlay d-flex flex-column justify-content-end p-0 bg-gradient-dark hover-overlay transition-all">
                                            <div class="p-3 w-100">
                                                <form action="{{ route('admin.ceramony.backgrounds.destroy', $bg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this background option?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold shadow">
                                                        <i class="bi bi-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
    }
    .hover-overlay {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    .group-hover:hover .hover-overlay {
        opacity: 1;
    }
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
        border-color: #dee2e6 !important;
    }
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
</style>
@endsection