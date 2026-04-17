@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card col-md-6 mx-auto shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Add New Category</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categoryvenue.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" 
                           class="form-control @error('category_name') is-invalid @enderror" 
                           placeholder="e.g. Wedding" 
                           value="{{ old('category_name') }}">
                    
                    {{-- FIXED THE LINE BELOW: Changed @error to @enderror --}}
                    @error('category_name') 
                        <span class="text-danger small">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.categoryvenue.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection