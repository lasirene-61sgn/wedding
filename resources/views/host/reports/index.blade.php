@extends('layouts.host')

@section('content')
<style>
    /* Stats Card Styling */
    .stat-card { 
        background: white; 
        padding: 20px; 
        border-radius: 12px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }

    /* Custom Badge Colors */
    .badge-accepted { background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-pending { background: #fef9c3; color: #854d0e; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-rejected { background: #fee2e2; color: #991b1b; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }

    /* Table Styling */
    .table thead th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
    
    /* PRINT LOGIC: This is critical for professional reports */
    @media print {
        .no-print, .sidebar, .navbar, .pagination-wrapper, .btn-print, .filter-section { 
            display: none !important; 
        }
        .container-fluid { width: 100%; padding: 0; margin: 0; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; }
        body { background: white !important; }
        .ps-4 { padding-left: 10px !important; }
    }
</style>

<div class="container-fluid" style="padding: 25px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h2 class="mb-1" style="font-weight: 800; color: #1e293b;">Wedding Reports</h2>
            <p class="text-muted small">Track guest responses and manage attendance.</p>
        </div>
        <button onclick="window.print()" class="btn btn-dark shadow-sm px-4">
            <i class="fa fa-print me-2"></i> Print Report
        </button>
    </div>

    <div class="row mb-4 no-print">
        <div class="col-md-2 mb-3">
            <div class="stat-card" style="border-top: 4px solid #6366f1;">
                <small class="text-muted fw-bold">TOTAL GUESTS</small>
                <h2 class="mb-0 mt-2">{{ number_format($stats['total']) }}</h2>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card" style="border-top: 4px solid #10b981;">
                <small class="text-success fw-bold">ACCEPTED</small>
                <h2 class="mb-0 mt-2">{{ number_format($stats['accepted']) }}</h2>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card" style="border-top: 4px solid #f59e0b;">
                <small class="text-warning fw-bold">PENDING</small>
                <h2 class="mb-0 mt-2">{{ number_format($stats['pending']) }}</h2>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card" style="border-top: 4px solid #ef4444;">
                <small class="text-danger fw-bold">REJECTED</small>
                <h2 class="mb-0 mt-2">{{ number_format($stats['rejected']) }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="border-top: 4px solid #8b5cf6;">
                <small class="text-muted fw-bold" style="color: #8b5cf6 !important;">TOTAL CEREMONIES</small>
                <h2 class="mb-0 mt-2">{{ number_format($stats['ceremonies']) }}</h2>
            </div>
        </div>
    </div>

    <!-- Ceremony Distribution -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3" style="color: #1e293b;">Ceremony-wise Guest Distribution</h5>
                <div class="row">
                    @forelse($ceremony_stats as $c_stat)
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <div class="small fw-bold text-muted text-uppercase mb-1">{{ $c_stat->ceramony_name }}</div>
                            <div class="h4 mb-0 fw-bold">{{ $c_stat->guest_count }} <small class="text-muted" style="font-size: 14px;">Guests</small></div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted small">No ceremonies found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4 no-print border-0 shadow-sm">
        <form action="{{ route('host.reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Search Guest</label>
                <input type="text" name="search" class="form-control" placeholder="Name or Number..." value="{{ request('search') }}">
            </div>
            
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">Invitation Via</label>
                <select name="via" class="form-select">
                    <option value="">All</option>
                    <option value="whatsapp" {{ request('via') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="sms" {{ request('via') == 'sms' ? 'selected' : '' }}>SMS</option>
                    <option value="email" {{ request('via') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">RSVP Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">Show Entries</label>
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach([15, 25, 50, 100] as $count)
                        <option value="{{ $count }}" {{ request('per_page') == $count ? 'selected' : '' }}>{{ $count }} Rows</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Apply Filters</button>
                <a href="{{ route('host.reports.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 no-print" style="width: 40px;">
                            <input type="checkbox" id="master-checkbox" class="form-check-input">
                        </th>
                        <th>Guest Information</th>
                        <th>Invited Via</th>
                        <th>RSVP Status</th>
                        <th>Assigned Ceremonies</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guests as $guest)
                    <tr>
                        <td class="ps-4 no-print">
                            <input type="checkbox" class="form-check-input guest-item" name="guest_ids[]" value="{{ $guest->id }}">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $guest->guest_name }}</div>
                            <div class="text-muted small">{{ $guest->guest_number }}</div>
                        </td>
                        <td>
                            <span class="text-uppercase small fw-bold text-muted border px-2 py-1 rounded bg-light" style="font-size: 10px;">
                                {{ $guest->via ?? 'Manual' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-{{ $guest->status }}">
                                {{ ucfirst($guest->status) }}
                            </span>
                        </td>
                        <td class="text-muted small" style="max-width: 250px;">
                            {{ $guest->assigned_ceremonies }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">No guest records found matching your filters.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex justify-content-between align-items-center no-print border-top bg-light">
            <div class="small text-muted fw-bold">
                Showing {{ $guests->firstItem() ?? 0 }} to {{ $guests->lastItem() ?? 0 }} of {{ $guests->total() }} entries
            </div>
            <div class="pagination-container">
                {{ $guests->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const master = document.getElementById('master-checkbox');
        const items = document.querySelectorAll('.guest-item');

        if(master) {
            master.addEventListener('change', function() {
                items.forEach(checkbox => {
                    checkbox.checked = master.checked;
                });
            });
        }
    });
</script>

@endsection