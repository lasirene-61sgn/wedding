@extends('layouts.host')

@section('content')
<div class="container-fluid">
    <!-- Action Status Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Host Family Configuration</h5>
            @if($hfamily->isEmpty())
            <a href="{{ route('host.hfamily.create') }}" class="btn btn-sm btn-success px-3">
                <i class="bi bi-plus-lg me-1"></i> Setup Family Details
            </a>
            @endif
        </div>
        <div class="card-body p-0">
            @if($hfamily->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-folder-x display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-0">No family config records found. Click the button above to set up your invitation details.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Primary Topic Details</th>
                            <th>Sub-sections Configured</th>
                            <th class="text-end pe-4">Actions</th>
                            <th class=text-end pe-4>Family Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hfamily as $details)
                        <tr>
                            <td class="ps-4 py-3">
                                <div>
                                    <span class="fw-bold text-dark d-block mb-1">
                                        {{ $details->topic_title_one ?? 'Untitled Header' }}
                                    </span>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 400px;">
                                        {{ $details->textone ?? 'No description text set.' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    {{ collect([
                                            $details->topic_title_two, $details->topic_title_three, 
                                            $details->topic_title_four, $details->topic_title_five, 
                                            $details->topic_title_six
                                        ])->filter()->count() }} / 5 Extra Sections Complete
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('host.hfamily.edit', $details->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Details
                                </a>
                            </td>
                            <td>
                                @if($details->is_active)
                                <span class="badge bg-success px-2 py-1"><i class="bi bi-eye-fill me-1"></i> Visible to Guests</span>
                                @else
                                <span class="badge bg-danger px-2 py-1"><i class="bi bi-eye-slash-fill me-1"></i> Hidden</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection