@extends('layouts.guest_ui')

@section('title', 'Save the Date | ' . ($invite->host->name ?? 'Wedding'))

@push('styles')
<style>
    .invitation-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 20px;
    }

    .save-the-date-card {
        max-width: 500px;
        width: 100%;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    h1.std-title {
        font-family: 'Great Vibes', cursive;
        color: var(--gold);
        font-size: 3.5rem;
        margin-bottom: 0;
        letter-spacing: 2px;
        animation: fadeInDown 0.8s;
    }

    .sub-text {
        color: var(--dark);
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.8rem;
        margin-bottom: 20px;
        margin-top: 5px;
    }

    hr.elegant-divider {
        border: 0;
        height: 1px;
        background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(212, 175, 55, 0.75), rgba(0, 0, 0, 0));
        margin: 30px 0;
    }

    .couple-names h3 {
        font-size: 2.2rem;
        font-family: 'Great Vibes', cursive;
        color: var(--pink-dark);
        margin: 10px 0;
    }

    .ceremonies-preview {
        background: rgba(255, 255, 255, 0.6);
        padding: 20px;
        border-radius: 15px;
        margin: 20px 0;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    .ceremonies-preview h4 {
        font-size: 0.9rem;
        color: var(--gold-dark);
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .btn-reject {
        background: transparent;
        color: var(--gray);
        padding: 12px 30px;
        border-radius: 50px;
        border: 1px solid #dfe6e9;
        font-weight: 600;
        cursor: pointer;
        margin-left: 10px;
        transition: all 0.3s;
    }
    
    .btn-reject:hover {
        background: #f1f2f6;
        color: var(--dark);
    }
</style>
@endpush

@section('header')
    <div class="nav-bar-container">
        <div class="nav-bar">
            <a href="{{ route('guest.select') }}"><i class="fas fa-arrow-left"></i> Back to Selections</a>
            <a href="{{ route('guest.wedding.details', $invite->id) }}"><i class="fas fa-eye"></i> View Dashboard</a>
        </div>
    </div>
@endsection

@section('content')
<div class="invitation-wrapper">
    <div class="glass-panel save-the-date-card">
        
        <h1 class="std-title">Save the Date</h1>
        <p class="sub-text">We invite you to the wedding of</p>
        
        <div class="couple-names">
            <h3>{{ $invite->host->bride_name ?? 'Bride' }} & {{ $invite->host->groom_name ?? 'Groom' }}</h3>
        </div>
        
        <p style="color: var(--gray); font-size: 0.9rem; font-style: italic;">Hosted by {{ $invite->host->name ?? 'Unknown Host' }}</p>

        <hr class="elegant-divider">

        <div class="ceremonies-preview" style="text-align: left;">
            <h4 style="margin-bottom: 20px;">Ceremonies you are invited to:</h4>
            
            @php 
                $assignedCeremonies = explode(', ', $invite->assigned_ceremonies); 
                $statuses = $invite->ceremony_status ?? [];
            @endphp
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($assignedCeremonies as $ceremonyName)
                    @php 
                        $status = $statuses[$ceremonyName] ?? 'pending'; 
                    @endphp
                    <div style="background: rgba(255,255,255,0.8); padding: 15px; border-radius: 10px; border: 1px solid rgba(214, 51, 132, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <strong style="color: var(--dark); font-size: 1.1rem;">{{ $ceremonyName }}</strong>
                            <div style="font-size: 0.85rem; margin-top: 5px;">
                                Status: 
                                @if($status === 'accepted') <span style="color: green; font-weight: 600;"><i class="fas fa-check"></i> Accepted</span>
                                @elseif($status === 'rejected' || $status === 'declined') <span style="color: red; font-weight: 600;"><i class="fas fa-times"></i> Declined</span>
                                @else <span style="color: orange; font-weight: 600;"><i class="fas fa-hourglass-half"></i> Pending</span>
                                @endif
                            </div>
                        </div>

                                        @if($status === 'pending')
                                        <div style="display: flex; gap: 10px;">
                                            <form action="{{ route('guest.update_ceremony_status', $invite->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="ceremony_name" value="{{ $ceremonyName }}">
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn-primary-wedding" style="padding: 8px 15px; font-size: 0.85rem; background: var(--gold); border: none;">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                            </form>

                                            <form action="{{ route('guest.update_ceremony_status', $invite->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="ceremony_name" value="{{ $ceremonyName }}">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn-reject" style="padding: 8px 15px; font-size: 0.85rem;">
                                                    <i class="fas fa-times"></i> Decline
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @php
                                $hasPending = false;
                                foreach($assignedCeremonies as $c) {
                                    if (($statuses[$c] ?? 'pending') === 'pending') {
                                        $hasPending = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($hasPending)
                            <div style="margin-top: 30px; text-align: center;">
                                <hr class="elegant-divider" style="margin: 20px 0;">
                                <h4 style="margin-bottom: 20px;">Or respond to all at once:</h4>
                                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                                    <form action="{{ route('guest.update_status', $invite->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn-primary-wedding" style="padding: 12px 30px;">
                                            <i class="fas fa-check-double"></i> Accept All Ceremonies
                                        </button>
                                    </form>

                                    <form action="{{ route('guest.update_status', $invite->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn-reject" style="padding: 12px 30px;">
                                            <i class="fas fa-times-circle"></i> Decline All Ceremonies
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
        </div>

        <div class="actions" style="margin-top: 30px;">
            <a href="{{ route('guest.wedding.details', $invite->id) }}" class="btn-primary-wedding" style="width: 100%; display: block; box-sizing: border-box;">
                Enter Wedding Dashboard
            </a>
        </div>
    </div>
</div>
@endsection