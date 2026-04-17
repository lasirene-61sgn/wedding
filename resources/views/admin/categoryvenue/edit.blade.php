@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card col-md-6 mx-auto shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit Category</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categoryvenue.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Category Name</label>
                    <input type="text" name="category_name" 
                           value="{{ old('category_name', $category->category_name) }}" 
                           class="form-control @error('category_name') is-invalid @enderror">
                    @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.categoryvenue.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection