@extends('layouts.host') <!-- Change this to your actual host layout if different -->

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 my-5">
            <div class="p-5 bg-white rounded shadow-sm">
                <i class="bi bi-tools text-warning display-1 mb-4"></i>
                <h2 class="fw-bold text-dark">Under Construction</h2>
                <p class="text-muted mb-4">We are hard at work building this amazing feature for you. Stay tuned!</p>
                <a href="{{ route('host.dashboard') }}" class="btn btn-primary px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection