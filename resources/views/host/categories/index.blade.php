@extends('layouts.host')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Guest Categories</h3>
            <p class="text-muted small">Manage ceremony presets for your guests</p>
        </div>
        <a href="{{ route('host.categories.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Create New Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category Name</th>
                        <th class="px-4 py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Included Ceremonies</th>
                        <th class="px-4 py-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <h6 class="mb-0 fw-bold">{{ $category->category_name }}</h6>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $names = \App\Models\Ceramonies::whereIn('id', $category->ceremony_ids)->pluck('ceramony_name')->implode(', ');
                                @endphp
                                <span class="text-primary small fw-semibold">
                                    {{ $names ?: 'No ceremonies selected' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <form action="{{ route('host.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 fw-bold text-decoration-none small">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/list.svg" alt="No data" style="width: 150px;" class="mb-3">
                                <h6 class="text-muted">No categories created yet.</h6>
                                <p class="text-muted small">Create one to start batch-assigning ceremonies!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-xxs {
        font-size: 0.65rem;
    }
    .rounded-4 {
        border-radius: 1rem !important;
    }
</style>
@endsection