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
        
        <div style="margin-bottom: 20px;">
            <h4 style="color: var(--gold-dark); font-weight: 500; font-size: 2rem; font-family: 'Great Vibes', cursive; letter-spacing: 2px; margin: 0;">Welcome {{ $invite->guest_name ?? 'Guest' }},</h4>
        </div>

        <h1 class="std-title" style="margin-top: 10px;">Save the Date</h1>
        
        @php
            $relation = strtolower(trim($invite->relation ?? ''));
            
            $invitingParents = null;
            if (in_array($relation, ['bride', 'bride_parent'])) {
                $parents = array_filter([$invitation->bride_mother_name ?? null, $invitation->bride_father_name ?? null]);
                if (!empty($parents)) {
                    $invitingParents = implode(' & ', $parents);
                }
            } elseif (in_array($relation, ['groom', 'groom_parent'])) {
                $parents = array_filter([$invitation->groom_mother_name ?? null, $invitation->groom_father_name ?? null]);
                if (!empty($parents)) {
                    $invitingParents = implode(' & ', $parents);
                }
            }
        @endphp

        @if($invitingParents)
            <p class="sub-text" style="font-weight: 600; font-size: 1rem; color: var(--dark); margin-bottom: 5px; text-transform: uppercase;">{{ $invitingParents }}</p>
            <p class="sub-text" style="text-transform: none; font-size: 0.9rem; letter-spacing: 1px; color: var(--gray);">cordially invite you to the wedding of</p>
        @else
            <p class="sub-text">We cordially invite you to the wedding of</p>
        @endif
        
        <div class="couple-names">
            <h3>{{ $invitation->bride_name ?? 'Bride' }} & {{ $invitation->groom_name ?? 'Groom' }}</h3>
        </div>
        
        <p style="color: var(--gray); font-size: 0.9rem; font-style: italic;">Hosted by {{ $invite->host->name ?? 'Unknown Host' }}</p>

        @if(isset($saveDateData) && $saveDateData->image)
            <div style="margin: 25px 0;">
                <img src="{{ asset('storage/' . $saveDateData->image) }}" alt="Save the Date" style="max-width: 100%; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15);">
                @if($saveDateData->message)
                    <p style="margin-top: 15px; font-style: italic; color: var(--dark);">"{{ $saveDateData->message }}"</p>
                @endif
            </div>
        @endif

        @if(isset($weddingDate))
            <div style="margin: 20px 0; background: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 12px; border: 1px dashed var(--gold); display: inline-block; min-width: 250px;">
                <h4 style="color: var(--pink-dark); font-size: 1.3rem; margin-bottom: 15px;"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($weddingDate)->format('l, F jS, Y') }}</h4>
                
                <div id="countdown" style="display: flex; gap: 15px; justify-content: center; color: var(--gold-dark);">
                    <div style="text-align: center;">
                        <div id="cd-days" style="font-size: 1.8rem; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Days</div>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: bold;">:</div>
                    <div style="text-align: center;">
                        <div id="cd-hours" style="font-size: 1.8rem; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Hours</div>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: bold;">:</div>
                    <div style="text-align: center;">
                        <div id="cd-minutes" style="font-size: 1.8rem; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Mins</div>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: bold;">:</div>
                    <div style="text-align: center;">
                        <div id="cd-seconds" style="font-size: 1.8rem; font-weight: bold; line-height: 1;">00</div>
                        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Secs</div>
                    </div>
                </div>
                <div id="cd-expired" style="display: none; font-size: 1.5rem; font-weight: bold; color: var(--gold-dark); margin: 0;">It's Today!</div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Set the date we're counting down to
                    var countDownDate = new Date("{{ \Carbon\Carbon::parse($weddingDate)->format('M d, Y 00:00:00') }}").getTime();

                    // Update the count down every 1 second
                    var x = setInterval(function() {
                        var now = new Date().getTime();
                        var distance = countDownDate - now;

                        if (distance <= 0) {
                            clearInterval(x);
                            document.getElementById("countdown").style.display = "none";
                            document.getElementById("cd-expired").style.display = "block";
                            return;
                        }

                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        document.getElementById("cd-days").innerHTML = days < 10 ? '0' + days : days;
                        document.getElementById("cd-hours").innerHTML = hours < 10 ? '0' + hours : hours;
                        document.getElementById("cd-minutes").innerHTML = minutes < 10 ? '0' + minutes : minutes;
                        document.getElementById("cd-seconds").innerHTML = seconds < 10 ? '0' + seconds : seconds;
                    }, 1000);
                });
            </script>
        @endif


    </div>
</div>
@endsection