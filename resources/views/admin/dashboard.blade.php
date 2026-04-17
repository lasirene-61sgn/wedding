@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-5">
                <h2 class="fw-bold">Welcome back, Admin!</h2>
                <p class="text-muted">You are now logged into the secure administrative area.</p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">1,240</h4>
                            <small class="text-muted">Total Users</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">$4,500</h4>
                            <small class="text-muted">Revenue</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <h4 class="mb-0">12</h4>
                            <small class="text-muted">Pending Tasks</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection