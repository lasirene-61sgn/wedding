@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h2>All Ceremonies</h2>
        <a href="{{ route('admin.ceramony.create') }}" class="btn btn-primary">Create Ceremony</a>
    </div>

    <table class="table table-striped bg-white">
        <thead>
            <tr>
                <th>Image</th>
                <th>Ceremony Name</th>
                <th>Host</th>
                <th>Category</th>
                <th>Venue</th>
                <th>Date/Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ceramonies as $ceramony)
            <tr>
                <td>
                    <img src="{{ asset('storage/' . $ceramony->ceramony_image) }}" width="60" class="rounded">
                </td>
                <td>{{ $ceramony->ceramony_name }}</td>
                <td>{{ $ceramony->host->name ?? 'N/A' }}</td>
                <td>{{ $ceramony->category->category_name ?? 'N/A' }}</td>
                <td>{{ $ceramony->venue->venue_name ?? 'N/A' }}</td>
                <td>{{ $ceramony->ceramony_date }} {{ $ceramony->ceramony_time }}</td>
                <td>
                    <a href="{{ route('admin.ceramony.edit', $ceramony->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('admin.ceramony.destroy', $ceramony->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection