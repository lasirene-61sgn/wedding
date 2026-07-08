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

    .welcome-title {
        color: var(--gold-dark);
        font-weight: 500;
        font-size: 2rem;
        font-family: 'Great Vibes', cursive;
        letter-spacing: 2px;
        margin: 0;
    }

    h1.std-title {
        font-family: 'Great Vibes', cursive;
        color: var(--gold);
        font-size: 3.5rem;
        margin-bottom: 0;
        letter-spacing: 2px;
        animation: fadeInDown 0.8s;
        margin-top: 10px;
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

    .sub-text.parents {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 5px;
    }
    
    .sub-text.invite-line {
        text-transform: none;
        font-size: 0.9rem;
        letter-spacing: 1px;
        color: var(--gray);
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

    .host-text {
        color: var(--gray);
        font-size: 0.9rem;
        font-style: italic;
    }

    .std-image-container {
        margin: 25px 0;
    }
    .std-image {
        max-width: 100%;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .std-message {
        margin-top: 15px;
        font-style: italic;
        color: var(--dark);
    }

    .date-container {
        margin: 20px 0;
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 12px;
        border: 1px dashed var(--gold);
        display: inline-block;
        min-width: 250px;
    }
    
    .date-text {
        color: var(--pink-dark);
        font-size: 1.3rem;
        margin-bottom: 15px;
    }

    .countdown-wrapper {
        display: flex;
        gap: 15px;
        justify-content: center;
        color: var(--gold-dark);
    }
    
    .countdown-item {
        text-align: center;
    }
    .countdown-value {
        font-size: 1.8rem;
        font-weight: bold;
        line-height: 1;
    }
    .countdown-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .countdown-colon {
        font-size: 1.5rem;
        font-weight: bold;
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

    @media (max-width: 768px) {
        .invitation-wrapper {
            padding: 15px 10px;
        }
        .save-the-date-card {
            padding: 25px 15px;
        }
        .welcome-title {
            font-size: 1.5rem;
        }
        h1.std-title {
            font-size: 2.2rem;
            margin-top: 5px;
        }
        .couple-names h3 {
            font-size: 1.8rem;
        }
        .sub-text {
            font-size: 0.75rem;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }
        .sub-text.parents {
            font-size: 0.85rem;
        }
        .sub-text.invite-line {
            font-size: 0.8rem;
        }
        .date-container {
            min-width: auto;
            width: 100%;
            padding: 15px;
            box-sizing: border-box;
        }
        .countdown-wrapper {
            gap: 8px;
        }
        .countdown-value {
            font-size: 1.3rem;
        }
        .countdown-label {
            font-size: 0.6rem;
        }
        .countdown-colon {
            font-size: 1.1rem;
        }
        .date-text {
            font-size: 1.1rem;
        }
    }

    @keyframes pumpEffect {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }

    .pump-animation {
        animation: pumpEffect 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .revealed-color .date-text,
    .revealed-color .countdown-value,
    .revealed-color .countdown-colon {
        color: #d4af37 !important; /* Elegant gold color upon reveal */
        text-shadow: 0px 4px 15px rgba(212, 175, 55, 0.4);
        transition: color 0.5s ease-in-out, text-shadow 0.5s ease-in-out;
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
            <h4 class="welcome-title">Welcome {{ $invite->guest_name ?? 'Guest' }},</h4>
        </div>

        
        
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
            <p class="sub-text parents">{{ $invitingParents }} cordially invite you to the wedding of</p>
            <p class="sub-text invite-line"></p>
        @else
            <p class="sub-text">We cordially invite you to the wedding of</p>
        @endif
        
        <div class="couple-names">
            <h3>{{ $invitation->bride_name ?? 'Bride' }} & {{ $invitation->groom_name ?? 'Groom' }}</h3>
        </div>
        
        <p class="host-text">Hosted by {{ $invite->host->name ?? 'Unknown Host' }}</p>

        @if(isset($saveDateData) && $saveDateData->image)
            <div class="std-image-container">
                <img src="{{ asset('storage/' . $saveDateData->image) }}" alt="Save the Date" class="std-image">
                @if($saveDateData->message)
                    <p class="std-message">"{{ $saveDateData->message }}"</p>
                @endif
            </div>
        @endif

        @if(isset($weddingDate))
            <div class="scratch-wrapper" style="position: relative; display: inline-block; margin: 20px 0;">
                <div class="date-container" id="reveal-content" style="margin: 0; position: relative; z-index: 1;">
                    <h4 class="date-text"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($weddingDate)->format('l, F jS, Y') }}</h4>
                    
                    <div id="countdown" class="countdown-wrapper">
                        <div class="countdown-item">
                            <div id="cd-days" class="countdown-value">00</div>
                            <div class="countdown-label">Days</div>
                        </div>
                        <div class="countdown-colon">:</div>
                        <div class="countdown-item">
                            <div id="cd-hours" class="countdown-value">00</div>
                            <div class="countdown-label">Hours</div>
                        </div>
                        <div class="countdown-colon">:</div>
                        <div class="countdown-item">
                            <div id="cd-minutes" class="countdown-value">00</div>
                            <div class="countdown-label">Mins</div>
                        </div>
                        <div class="countdown-colon">:</div>
                        <div class="countdown-item">
                            <div id="cd-seconds" class="countdown-value">00</div>
                            <div class="countdown-label">Secs</div>
                        </div>
                    </div>
                    <div id="cd-expired" style="display: none;">It's Today!</div>
                </div>
                <canvas id="scratch-canvas" style="position: absolute; top: 0; left: 0; z-index: 2; border-radius: 12px; cursor: pointer; touch-action: none;"></canvas>
            </div>
            <h1 class="std-title">Save the Date</h1>
            <p id="scratch-instruction" class="text-muted small mt-1" style="font-style: italic;">Scratch the card to reveal the date!</p>

            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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

                    // Scratch Card Logic
                    const canvas = document.getElementById('scratch-canvas');
                    const ctx = canvas.getContext('2d');
                    const content = document.getElementById('reveal-content');
                    const instruction = document.getElementById('scratch-instruction');

                    // Initialize canvas size (slight delay ensures fonts/styles load)
                    setTimeout(() => {
                        canvas.width = content.offsetWidth;
                        canvas.height = content.offsetHeight;
                        
                        // Fill canvas with metallic scratch color
                        ctx.fillStyle = '#D4AF37'; 
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        
                        // Add pattern/text
                        ctx.font = 'bold 22px Arial';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('Scratch to Reveal', canvas.width / 2, canvas.height / 2);
                    }, 100);

                    let isDrawing = false;

                    function getCoordinates(e) {
                        const rect = canvas.getBoundingClientRect();
                        let clientX = e.clientX;
                        let clientY = e.clientY;
                        
                        if (e.touches && e.touches.length > 0) {
                            clientX = e.touches[0].clientX;
                            clientY = e.touches[0].clientY;
                        }
                        
                        return {
                            x: clientX - rect.left,
                            y: clientY - rect.top
                        };
                    }

                    function scratch(e) {
                        if (!isDrawing) return;
                        e.preventDefault();
                        
                        const pos = getCoordinates(e);
                        ctx.globalCompositeOperation = 'destination-out';
                        ctx.beginPath();
                        ctx.arc(pos.x, pos.y, 25, 0, Math.PI * 2);
                        ctx.fill();
                        
                        checkScratchPercent();
                    }

                    canvas.addEventListener('mousedown', (e) => { isDrawing = true; scratch(e); });
                    canvas.addEventListener('mousemove', scratch);
                    window.addEventListener('mouseup', () => { isDrawing = false; });
                    
                    canvas.addEventListener('touchstart', (e) => { isDrawing = true; scratch(e); });
                    canvas.addEventListener('touchmove', scratch);
                    window.addEventListener('touchend', () => { isDrawing = false; });

                    let cleared = false;
                    function checkScratchPercent() {
                        if (cleared) return;
                        const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
                        let transparentPixels = 0;
                        const totalPixels = pixels.length / 4;
                        
                        // Check every 10th pixel for performance
                        for (let i = 3; i < pixels.length; i += 4 * 10) {
                            if (pixels[i] === 0) {
                                transparentPixels++;
                            }
                        }
                        
                        const percent = (transparentPixels / (totalPixels / 10)) * 100;
                        
                        if (percent > 45) { // If 45% scratched, auto clear
                            cleared = true;
                            canvas.style.transition = 'opacity 0.6s ease-out';
                            canvas.style.opacity = '0';
                            instruction.style.transition = 'opacity 0.4s';
                            instruction.style.opacity = '0';
                            setTimeout(() => {
                                canvas.style.display = 'none';
                                instruction.style.display = 'none';
                                
                                // Trigger animation and color change
                                content.classList.add('pump-animation', 'revealed-color');
                                
                                // Elegant Wedding Cascading Confetti Effect
                                var duration = 3000;
                                var end = Date.now() + duration;

                                (function frame() {
                                    confetti({
                                        particleCount: 5,
                                        angle: 60,
                                        spread: 55,
                                        origin: { x: 0 },
                                        colors: ['#d4af37', '#ffd700', '#ffffff', '#ffb6c1']
                                    });
                                    confetti({
                                        particleCount: 5,
                                        angle: 120,
                                        spread: 55,
                                        origin: { x: 1 },
                                        colors: ['#d4af37', '#ffd700', '#ffffff', '#ffb6c1']
                                    });

                                    if (Date.now() < end) {
                                        requestAnimationFrame(frame);
                                    }
                                }());
                                
                            }, 600);
                        }
                    }
                });
            </script>
        @endif


    </div>
</div>
@endsection