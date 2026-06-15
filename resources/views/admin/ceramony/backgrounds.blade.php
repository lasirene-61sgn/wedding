@extends('layouts.admin') {{-- Replace with your actual admin layout path --}}

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Upload New Background</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('admin.ceramony.backgrounds.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="background_image" class="form-label">Choose Background Image</label>
                            <input type="file" name="background_image" id="background_image" class="form-control @error('background_image') is-invalid @enderror" required>
                            @error('background_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="form-text text-muted">Supported: JPG, JPEG, PNG, WEBP (Max: 4MB)</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Upload Image</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Available Background Choices for Hosts</h5>
                </div>
                <div class="card-body">
                    @if($backgrounds->isEmpty())
                        <p class="text-muted text-center py-4">No backgrounds uploaded yet. Upload one on the left panel.</p>
                    @else
                        <div class="row g-3">
                            @foreach($backgrounds as $bg)
                                <div class="col-sm-6 col-md-4">
                                    <div class="card h-100 border text-center position-relative">
                                        <img src="{{ asset('storage/' . $bg->image_path) }}" class="card-img-top img-fluid style-preview" style="height: 150px; object-fit: cover;" alt="Background Option">
                                        
                                        <div class="card-body p-2">
                                            <form action="{{ route('admin.ceramony.backgrounds.destroy', $bg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this background option?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
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
@endsection