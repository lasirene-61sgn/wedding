<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding Portal | Plan & Celebrate Forever</title>

    <!-- Premium Romantic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Pinyon+Script&family=Montserrat:wght@300;400;500;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- FontAwesome & Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        serif: ['Cinzel', 'serif'],
                        cursive: ['Pinyon Script', 'cursive'],
                        display: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        royal: {
                            burgundy: '#4A1525',
                            wine: '#2D0B15',
                            blush: '#FBF2F4',
                            rosewater: '#F4E6E9',
                            gold: '#DFBA6B',
                            champagne: '#FFFDF9'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .wedding-bg {
            background-color: #FFFDF9;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='%234a1525' fill-opacity='0.01'/%3E%3C/svg%3E");
        }

        .luxury-line {
            background: linear-gradient(90deg, transparent, #DFBA6B 50%, transparent);
        }

        /* ============ LOADING SCREEN ============ */
        .loader {
            position: fixed;
            inset: 0;
            background: #FFFDF9;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 1s ease, visibility 1s ease;
        }
        .loader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        .loader-ring {
            width: 60px;
            height: 60px;
            border: 2px solid rgba(223,186,107,0.2);
            border-top-color: #DFBA6B;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ============ CURSOR GLOW ============ */
        .cursor-glow {
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(223,186,107,0.15) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease;
            mix-blend-mode: screen;
        }

        /* ============ FLOATING PETALS ============ */
        .petal {
            position: absolute;
            width: 18px;
            height: 22px;
            background: radial-gradient(ellipse at center, #FBF2F4 0%, #F4E6E9 70%);
            border-radius: 150% 0 150% 0;
            opacity: 0.75;
            pointer-events: none;
            z-index: 2;
            animation: petalFall linear infinite;
        }
        @keyframes petalFall {
            0% {
                transform: translateY(-10vh) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% { opacity: 0.8; }
            90% { opacity: 0.7; }
            100% {
                transform: translateY(110vh) translateX(80px) rotate(720deg);
                opacity: 0;
            }
        }

        /* ============ SPARKLES ============ */
        .sparkle {
            position: absolute;
            width: 4px; height: 4px;
            background: #DFBA6B;
            border-radius: 50%;
            pointer-events: none;
            z-index: 3;
            box-shadow: 0 0 6px #DFBA6B, 0 0 12px rgba(223,186,107,0.6);
            animation: sparklePulse 3s ease-in-out infinite;
        }
        @keyframes sparklePulse {
            0%, 100% { opacity: 0; transform: scale(0.5); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        /* ============ ANIMATED RINGS ============ */
        .rings-container {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto;
        }
        .ring-svg {
            animation: ringFloat 6s ease-in-out infinite;
            filter: drop-shadow(0 0 20px rgba(223,186,107,0.6));
        }
        @keyframes ringFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(5deg); }
        }
        .ring-glow {
            position: absolute;
            inset: 20px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(223,186,107,0.4) 0%, transparent 70%);
            animation: glowPulse 3s ease-in-out infinite;
        }
        @keyframes glowPulse {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.15); }
        }

        /* ============ 3D TILT BUTTONS ============ */
        .tilt-btn {
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.4s ease;
            overflow: hidden;
        }
        .tilt-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .tilt-btn:hover::before { opacity: 1; }
        .tilt-btn:hover {
            transform: translateY(-4px) rotateX(5deg);
            box-shadow: 0 20px 40px rgba(74,21,37,0.4), 0 0 0 1px rgba(223,186,107,0.3);
        }

        /* ============ GLASSMORPHISM CARD ============ */
        .glass-card {
            background: rgba(255,253,249,0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(223,186,107,0.25);
            box-shadow:
                0 8px 32px rgba(0,0,0,0.2),
                inset 0 1px 0 rgba(255,255,255,0.1);
        }

        /* ============ COUNTDOWN ============ */
        .countdown-box {
            background: rgba(255,253,249,0.06);
            border: 1px solid rgba(223,186,107,0.3);
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
        }
        .countdown-box:hover {
            background: rgba(223,186,107,0.15);
            border-color: #DFBA6B;
            transform: translateY(-4px);
        }

        /* ============ SCROLL INDICATOR ============ */
        .scroll-indicator {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }
        .scroll-mouse {
            width: 26px;
            height: 42px;
            border: 2px solid #DFBA6B;
            border-radius: 13px;
            position: relative;
        }
        .scroll-dot {
            width: 4px;
            height: 8px;
            background: #DFBA6B;
            border-radius: 2px;
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            animation: scrollDown 2s ease-in-out infinite;
        }
        @keyframes scrollDown {
            0% { top: 8px; opacity: 1; }
            100% { top: 26px; opacity: 0; }
        }

        /* ============ NAV ============ */
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 1px;
            background: #DFBA6B;
            transition: all 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        /* ============ DECORATIVE SVG FLOWERS ============ */
        .flower-deco {
            position: absolute;
            opacity: 0.15;
            pointer-events: none;
            animation: flowerSway 8s ease-in-out infinite;
        }
        @keyframes flowerSway {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }

        /* ============ MUSIC TOGGLE ============ */
        .music-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #4A1525;
            color: #DFBA6B;
            border: 1px solid #DFBA6B;
            z-index: 100;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(74,21,37,0.5);
        }
        .music-btn:hover {
            transform: scale(1.1);
            background: #DFBA6B;
            color: #4A1525;
        }
        .music-btn.playing {
            animation: musicPulse 2s ease-in-out infinite;
        }
        @keyframes musicPulse {
            0%, 100% { box-shadow: 0 4px 20px rgba(74,21,37,0.5); }
            50% { box-shadow: 0 4px 30px rgba(223,186,107,0.8), 0 0 0 8px rgba(223,186,107,0.15); }
        }

        /* Sound wave animation inside button */
        .sound-wave {
            display: flex;
            align-items: center;
            gap: 2px;
            height: 16px;
        }
        .sound-wave span {
            display: block;
            width: 3px;
            background: currentColor;
            border-radius: 2px;
            animation: soundBar 1s ease-in-out infinite;
        }
        .sound-wave span:nth-child(1) { height: 8px; animation-delay: 0s; }
        .sound-wave span:nth-child(2) { height: 14px; animation-delay: 0.15s; }
        .sound-wave span:nth-child(3) { height: 10px; animation-delay: 0.3s; }
        .sound-wave span:nth-child(4) { height: 16px; animation-delay: 0.45s; }
        .sound-wave span:nth-child(5) { height: 12px; animation-delay: 0.6s; }
        .sound-wave.paused span {
            animation-play-state: paused;
            height: 4px !important;
        }
        @keyframes soundBar {
            0%, 100% { transform: scaleY(0.5); }
            50% { transform: scaleY(1.2); }
        }

        /* ============ TIMELINE ============ */
        .timeline-line {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, transparent, #DFBA6B, transparent);
            transform: translateX(-50%);
        }
        .timeline-dot {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #4A1525;
            border: 3px solid #DFBA6B;
            box-shadow: 0 0 0 4px rgba(223,186,107,0.2);
        }

        /* ============ GALLERY ============ */
        .gallery-item {
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        .gallery-item img {
            transition: transform 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        .gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(45,11,21,0.6) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .gallery-item:hover::after {
            opacity: 1;
        }

        /* ============ RSVP MODAL ============ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(45,11,21,0.8);
            backdrop-filter: blur(10px);
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            transform: scale(0.9);
            transition: transform 0.4s ease;
        }
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        /* ============ COUNTER ANIMATION ============ */
        .counter-value {
            font-variant-numeric: tabular-nums;
        }

        /* ============ MUSIC TOOLTIP ============ */
        .music-tooltip {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: #4A1525;
            color: #FFFDF9;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-family: 'Cinzel', serif;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            white-space: nowrap;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }
        .music-tooltip::after {
            content: '';
            position: absolute;
            bottom: -4px;
            right: 20px;
            width: 8px;
            height: 8px;
            background: #4A1525;
            transform: rotate(45deg);
        }
        .music-btn:hover .music-tooltip {
            opacity: 1;
            transform: translateY(0);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .timeline-line { left: 20px; }
            .timeline-dot { left: 20px; }
        }
    </style>
</head>

<body class="font-sans text-royal-burgundy antialiased overflow-x-hidden wedding-bg">

    <!-- AUDIO ELEMENT (Royalty-Free Wedding Music) -->
    <audio id="weddingMusic" loop preload="auto">
        <source src="https://cdn.pixabay.com/audio/2022/10/30/audio_347111d650.mp3" type="audio/mpeg">
        <!-- Fallback: romantic piano instrumental -->
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
    </audio>

    <!-- LOADER -->
    <div class="loader" id="loader">
        <div class="text-center">
            <div class="loader-ring mx-auto mb-4"></div>
            <p class="font-serif tracking-[0.4em] text-royal-burgundy uppercase text-xs">Preparing Your Experience</p>
        </div>
    </div>

    <!-- CURSOR GLOW -->
    <div class="cursor-glow hidden md:block" id="cursorGlow"></div>

    <!-- REDESIGNED HIGH-END NAVIGATION HEADER -->
    <nav class="fixed top-0 w-full bg-royal-champagne/90 backdrop-blur-md z-50 border-t-4 border-b border-royal-gold/60 shadow-sm transition-all duration-300" id="mainNav">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-4 flex justify-between items-center">

            <!-- Elegant Crest-Style Logo -->
            <a href="#" class="font-serif tracking-[0.2em] text-lg sm:text-2xl font-medium uppercase text-royal-burgundy flex items-center gap-1">
                W<span class="text-royal-gold font-cursive text-2xl sm:text-3xl lowercase normal-case tracking-normal -ml-0.5">P</span>
                <span class="hidden sm:inline-block text-[11px] tracking-[0.3em] text-royal-burgundy/60 pl-2 ml-2 border-l border-royal-gold/30 font-sans font-light">PORTAL</span>
            </a>

            <!-- High-Contrast Clean Action Links -->
            <div class="flex items-center gap-3 sm:gap-6">
                <a href="{{route('guest.login')}}" class="text-[10px] sm:text-xs tracking-[0.15em] uppercase font-bold text-royal-burgundy hover:text-royal-gold transition-colors duration-300 px-2 py-1">
                    <span class="sm:hidden"><i class="fa-solid fa-user-tag text-sm"></i></span>
                    <span class="hidden sm:inline">Guest Entry</span>
                </a>

                <a href="{{route('host.login')}}" class="px-3 sm:px-5 py-2 rounded-none border border-royal-burgundy text-royal-burgundy font-bold text-[10px] sm:text-xs tracking-[0.15em] uppercase transition-all duration-300 hover:bg-royal-burgundy hover:text-white bg-white/40 shadow-sm">
                    <span class="sm:hidden"><i class="fa-solid fa-key text-xs mr-1"></i> Host</span>
                    <span class="hidden sm:inline">Host Access</span>
                </a>
            </div>

        </div>
    </nav>

    <!-- RE-POLISHED FIRST SECTION: Clean Stretched Luxury Layout -->
    <section class="relative min-h-screen flex items-center justify-center px-6 sm:px-12 md:px-16 lg:px-24 pt-36 pb-24 overflow-hidden bg-[#FAF7F2]">

        <!-- Fine-Art Canvas Paper Texture Backdrop Overlay -->
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png');"></div>

        <!-- Animated Petals Container -->
        <div id="petalsContainer" class="absolute inset-0 pointer-events-none z-[2]"></div>

        <!-- Sparkles Container -->
        <div id="sparklesContainer" class="absolute inset-0 pointer-events-none z-[3]"></div>

        <!-- Fine Accent Lines to fill the background space dynamically -->
        <div class="absolute inset-y-0 left-12 w-[1px] bg-royal-gold/20 pointer-events-none hidden md:block"></div>
        <div class="absolute inset-y-0 right-12 w-[1px] bg-royal-gold/20 pointer-events-none hidden md:block"></div>
        <div class="absolute inset-x-0 bottom-24 h-[1px] bg-royal-gold/15 pointer-events-none hidden md:block"></div>

        <!-- Decorative SVG Flowers -->
        <svg class="flower-deco top-20 left-10 w-32 h-32 text-royal-gold" viewBox="0 0 100 100" fill="currentColor">
            <path d="M50 10 Q55 30 70 30 Q55 35 55 50 Q50 35 35 35 Q50 30 50 10 Z" />
            <path d="M50 90 Q45 70 30 70 Q45 65 45 50 Q50 65 65 65 Q50 70 50 90 Z" />
            <path d="M10 50 Q30 45 30 30 Q35 45 50 45 Q35 50 35 65 Q30 50 10 50 Z" />
            <path d="M90 50 Q70 55 70 70 Q65 55 50 55 Q65 50 65 35 Q70 50 90 50 Z" />
            <circle cx="50" cy="50" r="4" />
        </svg>
        <svg class="flower-deco bottom-24 right-12 w-40 h-40 text-royal-gold" style="animation-delay: -4s;" viewBox="0 0 100 100" fill="currentColor">
            <path d="M50 10 Q55 30 70 30 Q55 35 55 50 Q50 35 35 35 Q50 30 50 10 Z" />
            <path d="M50 90 Q45 70 30 70 Q45 65 45 50 Q50 65 65 65 Q50 70 50 90 Z" />
            <path d="M10 50 Q30 45 30 30 Q35 45 50 45 Q35 50 35 65 Q30 50 10 50 Z" />
            <path d="M90 50 Q70 55 70 70 Q65 55 50 55 Q65 50 65 35 Q70 50 90 50 Z" />
            <circle cx="50" cy="50" r="4" />
        </svg>

        <!-- Wide Grid Wrapper -->
        <div class="relative max-w-7xl w-full mx-auto z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

            <!-- LEFT COLUMN: Stretched Grand Display Typography Architecture -->
            <div class="lg:col-span-7 text-center lg:text-left relative py-4">
                <!-- Large Soft Initial Monogram Watermark behind the main heading -->
                <div class="absolute -top-16 lg:-top-24 left-1/2 lg:left-0 -translate-x-1/2 lg:translate-x-0 font-serif text-[10rem] sm:text-[14rem] md:text-[18rem] text-royal-gold/[0.04] font-bold select-none leading-none tracking-widest z-0">
                    WP
                </div>

                <!-- Animated Rings -->
                <div class="rings-container mb-6 hidden lg:block" data-aos="fade-up" data-aos-duration="1200">
                    <div class="ring-glow"></div>
                    <svg class="ring-svg w-full h-full" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FFFDF9" />
                                <stop offset="50%" stop-color="#DFBA6B" />
                                <stop offset="100%" stop-color="#8B6F3C" />
                            </linearGradient>
                        </defs>
                        <circle cx="85" cy="100" r="55" stroke="url(#goldGrad)" stroke-width="6" fill="none" />
                        <circle cx="85" cy="100" r="45" stroke="url(#goldGrad)" stroke-width="1.5" fill="none" opacity="0.5" />
                        <circle cx="115" cy="100" r="55" stroke="url(#goldGrad)" stroke-width="6" fill="none" />
                        <circle cx="115" cy="100" r="45" stroke="url(#goldGrad)" stroke-width="1.5" fill="none" opacity="0.5" />
                        <path d="M85 45 L92 52 L85 59 L78 52 Z" fill="#FFFDF9" />
                    </svg>
                </div>

                <!-- High Contrast Premium Badge -->
                <div class="mb-8 inline-flex" data-aos="fade-right" data-aos-duration="1200">
                    <span class="text-[10px] sm:text-xs font-bold tracking-[0.4em] uppercase text-royal-burgundy bg-royal-rosewater px-5 py-2 border border-royal-gold/30 shadow-sm">
                        The Digital Invitation Portal
                    </span>
                </div>

                <!-- Added defensive spacing wrappers to prevent word collisions -->
                <div class="relative z-10 mt-2 mb-4">
                    <p class="font-cursive text-royal-gold text-4xl sm:text-5xl md:text-6xl mb-4 normal-case font-normal tracking-wide" data-aos="fade-in" data-aos-delay="200">
                        Together with their families
                    </p>

                    <h1 class="font-serif text-4xl sm:text-6xl md:text-[5.5rem] font-light text-royal-burgundy tracking-[0.1em] uppercase leading-none" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300">
                        Plan & Celebrate
                    </h1>
                </div>

                <!-- Flowing Foreground Calligraphy -->
                <div class="relative pt-6 z-20" data-aos="zoom-in" data-aos-duration="1400" data-aos-delay="400">
                    <span class="font-cursive text-royal-gold text-8xl sm:text-9xl md:text-[11.5rem] block normal-case font-normal select-none drop-shadow-sm tracking-wide">
                        Forever
                    </span>
                </div>

                <!-- Decorative Divider -->
                <div class="flex items-center justify-center lg:justify-start gap-4 mt-8" data-aos="fade-in" data-aos-delay="600">
                    <span class="w-16 h-[1px] bg-gradient-to-r from-transparent to-royal-gold"></span>
                    <i class="fa-solid fa-diamond text-royal-gold text-xs"></i>
                    <span class="w-16 h-[1px] bg-gradient-to-l from-transparent to-royal-gold"></span>
                </div>
            </div>

            <!-- RIGHT COLUMN: Elegant Presentation Space & Custom Square Luxury Buttons -->
            <div class="lg:col-span-5 flex flex-col items-center lg:items-start text-center lg:text-left border-t lg:border-t-0 lg:border-l border-royal-gold/30 pt-10 lg:pt-6 lg:pl-12 self-center" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="400">

                <!-- Fine Floral Branch Decorative Icon -->
                <div class="mb-6 text-royal-gold text-xl hidden lg:block">
                    <i class="fa-solid fa-leaf opacity-80"></i>
                </div>

                <p class="font-serif italic text-base sm:text-lg text-gray-600 mb-10 leading-relaxed max-w-md">
                    We invite you to step into a premium, unified gateway crafted beautifully for organizing hosts and honored attendees. Create customized stationery interfaces, manage registries, and preserve love.
                </p>

                <!-- Live Countdown Timer -->
                <div class="w-full max-w-sm lg:max-w-none mb-8">
                    <p class="font-serif text-[10px] tracking-[0.3em] text-royal-burgundy uppercase mb-3">Counting Down To Our Special Day</p>
                    <div class="grid grid-cols-4 gap-2" id="countdown">
                        <div class="countdown-box rounded-sm p-3 text-center">
                            <div class="font-serif text-2xl sm:text-3xl text-royal-burgundy font-light counter-value" id="days">00</div>
                            <div class="font-serif text-[9px] tracking-[0.2em] text-royal-gold uppercase mt-1">Days</div>
                        </div>
                        <div class="countdown-box rounded-sm p-3 text-center">
                            <div class="font-serif text-2xl sm:text-3xl text-royal-burgundy font-light counter-value" id="hours">00</div>
                            <div class="font-serif text-[9px] tracking-[0.2em] text-royal-gold uppercase mt-1">Hours</div>
                        </div>
                        <div class="countdown-box rounded-sm p-3 text-center">
                            <div class="font-serif text-2xl sm:text-3xl text-royal-burgundy font-light counter-value" id="minutes">00</div>
                            <div class="font-serif text-[9px] tracking-[0.2em] text-royal-gold uppercase mt-1">Min</div>
                        </div>
                        <div class="countdown-box rounded-sm p-3 text-center">
                            <div class="font-serif text-2xl sm:text-3xl text-royal-burgundy font-light counter-value" id="seconds">00</div>
                            <div class="font-serif text-[9px] tracking-[0.2em] text-royal-gold uppercase mt-1">Sec</div>
                        </div>
                    </div>
                </div>

                <!-- Modern Square Luxury Action Buttons -->
                <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row items-center gap-4 w-full max-w-sm lg:max-w-none">
                    <a href="{{route('host.register')}}" class="tilt-btn w-full text-center px-10 py-4 bg-royal-burgundy text-[#FFFDF9] font-serif tracking-[0.2em] text-xs uppercase shadow-xl hover:bg-royal-gold hover:text-royal-wine transition-all duration-300 rounded-none font-bold">
                        Host Entry
                    </a>
                    <a href="#guest-experience" class="tilt-btn w-full text-center px-10 py-4 border border-royal-burgundy text-royal-burgundy bg-white/40 font-serif tracking-[0.2em] text-xs uppercase font-bold transition-all duration-300 hover:bg-royal-burgundy hover:text-white shadow-sm rounded-none">
                        Guest Portal
                    </a>
                </div>
            </div>

        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator" data-aos="fade-up" data-aos-delay="800">
            <div class="flex flex-col items-center gap-2">
                <span class="font-serif text-[9px] tracking-[0.3em] text-royal-gold uppercase">Scroll</span>
                <div class="scroll-mouse">
                    <div class="scroll-dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Divider Line -->
    <!-- <div class="w-full h-[1px] luxury-line"></div> -->

    <!-- Live Guest Counter Section -->
    <!-- <section class="py-16 px-6 md:px-16 bg-royal-blush border-t border-royal-gold/20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10" data-aos="fade-up">
                <span class="text-royal-gold font-serif tracking-[0.2em] text-xs uppercase">Join The Celebration</span>
                <h2 class="font-serif text-3xl sm:text-4xl text-royal-burgundy font-light mt-1">Loved Ones Already Registered</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-white/50 border border-royal-gold/20" data-aos="fade-up">
                    <div class="font-serif text-4xl sm:text-5xl text-royal-burgundy font-light counter-value" data-target="247">0</div>
                    <div class="font-serif text-[10px] tracking-[0.3em] text-royal-gold uppercase mt-2">Confirmed Guests</div>
                </div>
                <div class="text-center p-6 bg-white/50 border border-royal-gold/20" data-aos="fade-up" data-aos-delay="100">
                    <div class="font-serif text-4xl sm:text-5xl text-royal-burgundy font-light counter-value" data-target="12">0</div>
                    <div class="font-serif text-[10px] tracking-[0.3em] text-royal-gold uppercase mt-2">Family Tables</div>
                </div>
                <div class="text-center p-6 bg-white/50 border border-royal-gold/20" data-aos="fade-up" data-aos-delay="200">
                    <div class="font-serif text-4xl sm:text-5xl text-royal-burgundy font-light counter-value" data-target="8">0</div>
                    <div class="font-serif text-[10px] tracking-[0.3em] text-royal-gold uppercase mt-2">Hours of Joy</div>
                </div>
                <div class="text-center p-6 bg-white/50 border border-royal-gold/20" data-aos="fade-up" data-aos-delay="300">
                    <div class="font-serif text-4xl sm:text-5xl text-royal-burgundy font-light counter-value" data-target="1">0</div>
                    <div class="font-serif text-[10px] tracking-[0.3em] text-royal-gold uppercase mt-2">Perfect Day</div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Section Divider Line -->
    <!-- <div class="w-full h-[1px] luxury-line"></div> -->

    <!-- Event Timeline Section -->
    <!-- <section class="py-24 px-6 md:px-16 bg-royal-champagne relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png');"></div>
        <div class="max-w-5xl mx-auto relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-royal-gold font-serif tracking-[0.2em] text-xs uppercase">The Celebration Schedule</span>
                <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl text-royal-burgundy font-light mt-1">Moments To Cherish</h2>
                <div class="w-12 h-[1px] bg-royal-gold mx-auto mt-4"></div>
            </div>

            <div class="relative">
                <div class="timeline-line hidden md:block"></div>

                <div class="space-y-12 md:space-y-24">
                    <div class="relative md:flex items-center" data-aos="fade-right">
                        <div class="timeline-dot hidden md:block" style="top: 24px;"></div>
                        <div class="md:w-1/2 md:pr-12 md:text-right">
                            <span class="font-cursive text-royal-gold text-3xl block">4:00 PM</span>
                            <h3 class="font-serif text-2xl text-royal-burgundy font-medium mt-1">The Ceremony</h3>
                            <p class="text-gray-600 text-sm font-light leading-relaxed mt-2">Exchange of vows in an intimate garden setting surrounded by loved ones.</p>
                        </div>
                        <div class="md:w-1/2 md:pl-12 hidden md:block"></div>
                    </div>

                    <div class="relative md:flex items-center" data-aos="fade-left">
                        <div class="timeline-dot hidden md:block" style="top: 24px;"></div>
                        <div class="md:w-1/2 md:pr-12 hidden md:block"></div>
                        <div class="md:w-1/2 md:pl-12">
                            <span class="font-cursive text-royal-gold text-3xl block">6:00 PM</span>
                            <h3 class="font-serif text-2xl text-royal-burgundy font-medium mt-1">Cocktail Hour</h3>
                            <p class="text-gray-600 text-sm font-light leading-relaxed mt-2">Handcrafted cocktails and canapés as the sun sets over the estate.</p>
                        </div>
                    </div>

                    <div class="relative md:flex items-center" data-aos="fade-right">
                        <div class="timeline-dot hidden md:block" style="top: 24px;"></div>
                        <div class="md:w-1/2 md:pr-12 md:text-right">
                            <span class="font-cursive text-royal-gold text-3xl block">7:30 PM</span>
                            <h3 class="font-serif text-2xl text-royal-burgundy font-medium mt-1">Grand Reception</h3>
                            <p class="text-gray-600 text-sm font-light leading-relaxed mt-2">A five-course dinner experience with live orchestral accompaniment.</p>
                        </div>
                        <div class="md:w-1/2 md:pl-12 hidden md:block"></div>
                    </div>

                    <div class="relative md:flex items-center" data-aos="fade-left">
                        <div class="timeline-dot hidden md:block" style="top: 24px;"></div>
                        <div class="md:w-1/2 md:pr-12 hidden md:block"></div>
                        <div class="md:w-1/2 md:pl-12">
                            <span class="font-cursive text-royal-gold text-3xl block">10:00 PM</span>
                            <h3 class="font-serif text-2xl text-royal-burgundy font-medium mt-1">Dancing & Celebration</h3>
                            <p class="text-gray-600 text-sm font-light leading-relaxed mt-2">The dance floor opens for an unforgettable night of joy and revelry.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Section Divider Line -->
    <div class="w-full h-[1px] luxury-line"></div>

    <!-- Host Experience Section (Split Modern Fine-Art Layout) -->
    <section id="host-experience" class="py-24 px-6 md:px-16 bg-royal-blush border-t border-royal-gold/20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Title Banner with High Visibility Bold Label -->
                <div class="lg:col-span-4 lg:pr-8 text-center lg:text-left" data-aos="fade-right">
                    <p class="font-sans tracking-[0.2em] text-xs uppercase text-royal-burgundy font-bold border-b border-royal-gold pb-2 inline-block mb-3">
                        The Organizer's Suite
                    </p>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl text-royal-burgundy font-light leading-tight">Everything A Host Needs</h2>
                    <p class="text-sm text-gray-600 mt-4 font-light leading-relaxed">From design conception to tracking RSVPs effortlessly, everything is meticulously curated to streamline your modern wedding administration workflow.</p>
                </div>

                <!-- Right Seamless Clean List Map -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-8 sm:gap-10">

                    <div class="flex items-start gap-4" data-aos="fade-up">
                        <span class="text-royal-gold text-xl pt-1"><i class="fa-solid fa-compass-drafting"></i></span>
                        <div>
                            <h3 class="font-serif text-lg text-royal-burgundy font-medium tracking-wide mb-1">Host Dashboard</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Keep absolute tracking tabs over user registries, calendar schedules, and RSVP data summaries continuously.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4" data-aos="fade-up">
                        <span class="text-royal-gold text-xl pt-1"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                        <div>
                            <h3 class="font-serif text-lg text-royal-burgundy font-medium tracking-wide mb-1">Design & Customize</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Modify beautiful bespoke presentation card themes, graphic settings, and text placements dynamically.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4" data-aos="fade-up">
                        <span class="text-royal-gold text-xl pt-1"><i class="fa-solid fa-user-check"></i></span>
                        <div>
                            <h3 class="font-serif text-lg text-royal-burgundy font-medium tracking-wide mb-1">Guest Management</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Easily add names to populate your list and automate schedule reminders securely.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4" data-aos="fade-up">
                        <span class="text-royal-gold text-xl pt-1"><i class="fa-solid fa-images"></i></span>
                        <div>
                            <h3 class="font-serif text-lg text-royal-burgundy font-medium tracking-wide mb-1">Family & Media Vault</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Structure key family layout info blocks and host digital interactive display highlights flawlessly.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4" data-aos="fade-up">
                        <span class="text-royal-gold text-xl pt-1"><i class="fa-solid fa-chart-line"></i></span>
                        <div>
                            <h3 class="font-serif text-lg text-royal-burgundy font-medium tracking-wide mb-1">Detailed Reports</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Export single-tap updates for catering firms, attendance status checks, and planner metrics.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4" data-aos="fade-up">
                        <span class="text-royal-gold text-xl pt-1"><i class="fa-solid fa-paper-plane"></i></span>
                        <div>
                            <h3 class="font-serif text-lg text-royal-burgundy font-medium tracking-wide mb-1">Multi-Channel Alerts</h3>
                            <p class="text-gray-500 text-sm font-light leading-relaxed">Deliver venue coordinate changes or timeline edits instantly across multiple channels.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Guest Experience Section (Deep Royal Wine Aesthetic Accent) -->
    <section id="guest-experience" class="bg-royal-wine text-royal-champagne py-24 px-6 md:px-16 relative">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-royal-gold font-serif tracking-[0.2em] text-xs uppercase">The Attendee Gateway</span>
                <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl text-white font-light mt-1">A Premium Guest Journey</h2>
                <div class="w-12 h-[1px] bg-royal-gold mx-auto mt-4"></div>
            </div>

            <!-- Fine-art Typography Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-16">

                <div class="group border-l border-royal-gold/30 pl-6" data-aos="fade-up">
                    <p class="font-cursive text-royal-gold text-3xl mb-1">Instant Notifications</p>
                    <h3 class="font-serif text-md uppercase tracking-wider text-white mb-2 font-medium">Timely Updates</h3>
                    <p class="text-royal-rosewater/70 text-sm font-light leading-relaxed">Receive gorgeously styled reminders and digital invitations seamlessly.</p>
                </div>

                <div class="group border-l border-royal-gold/30 pl-6" data-aos="fade-up">
                    <p class="font-cursive text-royal-gold text-3xl mb-1">One-Click RSVPs</p>
                    <h3 class="font-serif text-md uppercase tracking-wider text-white mb-2 font-medium">Effortless Response</h3>
                    <p class="text-royal-rosewater/70 text-sm font-light leading-relaxed">Accept or decline the warm invitation with a single mobile tap.</p>
                </div>

                <div class="group border-l border-royal-gold/30 pl-6" data-aos="fade-up">
                    <p class="font-cursive text-royal-gold text-3xl mb-1">Immersive Motions</p>
                    <h3 class="font-serif text-md uppercase tracking-wider text-white mb-2 font-medium">Bespoke Visuals</h3>
                    <p class="text-royal-rosewater/70 text-sm font-light leading-relaxed">View custom fluid animations created specifically by your hosts.</p>
                </div>

                <div class="group border-l border-royal-gold/30 pl-6" data-aos="fade-up">
                    <p class="font-cursive text-royal-gold text-3xl mb-1">Digital Gallery</p>
                    <h3 class="font-serif text-md uppercase tracking-wider text-white mb-2 font-medium">Shared Highlights</h3>
                    <p class="text-royal-rosewater/70 text-sm font-light leading-relaxed">Browse high-definition photography collections straight from the live venue floor.</p>
                </div>

                <div class="group border-l border-royal-gold/30 pl-6" data-aos="fade-up">
                    <p class="font-cursive text-royal-gold text-3xl mb-1">Guest Profiles</p>
                    <h3 class="font-serif text-md uppercase tracking-wider text-white mb-2 font-medium">Personal Details</h3>
                    <p class="text-royal-rosewater/70 text-sm font-light leading-relaxed">Quickly specify catering preferences or dietary guidelines effortlessly.</p>
                </div>

                <div class="group border-l border-royal-gold/30 pl-6" data-aos="fade-up">
                    <p class="font-cursive text-royal-gold text-3xl mb-1">Family Management</p>
                    <h3 class="font-serif text-md uppercase tracking-wider text-white mb-2 font-medium">Plus-One Access</h3>
                    <p class="text-royal-rosewater/70 text-sm font-light leading-relaxed">Add details for accompanying family members to ensure smooth registration check-ins.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Section Divider Line -->
    <div class="w-full h-[1px] luxury-line"></div>

    <!-- Photo Gallery Section -->
    <!--  
    <!-- Section Divider Line -->
    <div class="w-full h-[1px] luxury-line"></div>

    <!-- Invitation Bottom Call to Action Frame -->
    <section class="py-28 text-center bg-royal-champagne relative px-4">
        <div class="max-w-3xl mx-auto py-12 px-6 border border-royal-gold/30 outline outline-4 outline-royal-gold/5 outline-offset-8" data-aos="zoom-in">
            <span class="font-cursive text-royal-gold text-5xl block mb-2 font-normal">Let the celebration unfold</span>
            <h2 class="font-serif text-2xl sm:text-4xl text-royal-burgundy tracking-wide uppercase font-light mb-6">Ready to start your journey?</h2>
            <p class="font-sans text-gray-500 text-sm max-w-lg mx-auto mb-8 font-light leading-relaxed">Whether you are organizing the wedding of your dreams or attending as a valued guest, our premium portal ensures absolute elegance at every turn.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="openModal()" class="tilt-btn inline-block px-10 py-3.5 bg-royal-burgundy text-white font-serif tracking-[0.2em] text-xs uppercase shadow-xl hover:bg-royal-gold hover:text-royal-wine transition-all duration-300">
                    RSVP Now
                </button>
                <a href="#host-experience" class="tilt-btn inline-block px-10 py-3.5 border border-royal-burgundy text-royal-burgundy font-serif tracking-[0.2em] text-xs uppercase hover:bg-royal-burgundy hover:text-white transition-all duration-300">
                    Host Your Celebration
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-royal-wine text-royal-rosewater/40 text-center py-8 px-4 border-t border-royal-gold/10 font-serif tracking-[0.2em] text-[10px] sm:text-xs uppercase">
        &copy; 2026 Wedding Portal. All rights reserved. <span class="text-royal-gold font-cursive text-lg lowercase normal-case tracking-normal block sm:inline sm:ml-2">Crafted with absolute elegance.</span>
    </footer>

    <!-- RSVP Modal -->
    <div class="modal-overlay" id="rsvpModal">
        <div class="modal-content bg-royal-champagne max-w-md w-full mx-4 p-8 border border-royal-gold/30 relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-royal-burgundy hover:text-royal-gold transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div class="text-center mb-6">
                <i class="fa-solid fa-heart text-royal-gold text-2xl mb-2"></i>
                <h3 class="font-serif text-2xl text-royal-burgundy">RSVP</h3>
                <p class="font-cursive text-royal-gold text-2xl mt-1">We'd love to have you</p>
            </div>
            <form onsubmit="submitRSVP(event)" class="space-y-4">
                <div>
                    <label class="font-serif text-xs tracking-[0.2em] text-royal-burgundy uppercase block mb-1">Full Name</label>
                    <input type="text" required class="w-full px-4 py-2 border border-royal-gold/30 bg-white focus:outline-none focus:border-royal-gold transition-colors" />
                </div>
                <div>
                    <label class="font-serif text-xs tracking-[0.2em] text-royal-burgundy uppercase block mb-1">Email</label>
                    <input type="email" required class="w-full px-4 py-2 border border-royal-gold/30 bg-white focus:outline-none focus:border-royal-gold transition-colors" />
                </div>
                <div>
                    <label class="font-serif text-xs tracking-[0.2em] text-royal-burgundy uppercase block mb-1">Attendance</label>
                    <select class="w-full px-4 py-2 border border-royal-gold/30 bg-white focus:outline-none focus:border-royal-gold transition-colors">
                        <option>Joyfully Accepts</option>
                        <option>Regretfully Declines</option>
                    </select>
                </div>
                <div>
                    <label class="font-serif text-xs tracking-[0.2em] text-royal-burgundy uppercase block mb-1">Number of Guests</label>
                    <input type="number" min="1" max="5" value="1" class="w-full px-4 py-2 border border-royal-gold/30 bg-white focus:outline-none focus:border-royal-gold transition-colors" />
                </div>
                <button type="submit" class="w-full px-10 py-3 bg-royal-burgundy text-white font-serif tracking-[0.2em] text-xs uppercase hover:bg-royal-gold hover:text-royal-wine transition-all duration-300">
                    Send Response
                </button>
            </form>
        </div>
    </div>

    <!-- MUSIC TOGGLE BUTTON WITH SOUND WAVE -->
    <button class="music-btn" id="musicBtn" aria-label="Toggle music">
        <span class="music-tooltip" id="musicTooltip">Play Music</span>
        <div class="sound-wave paused" id="soundWave">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <i class="fa-solid fa-music hidden" id="musicIcon"></i>
    </button>

    <!-- AOS JS Continuous Scroll Animation Configuration -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: false,
            mirror: true,
            offset: 80,
            duration: 1000
        });

        // ============ LOADER ============
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('loader').classList.add('hidden');
            }, 800);
        });

        // ============ CURSOR GLOW ============
        const cursorGlow = document.getElementById('cursorGlow');
        document.addEventListener('mousemove', (e) => {
            cursorGlow.style.left = e.clientX + 'px';
            cursorGlow.style.top = e.clientY + 'px';
        });

        // ============ NAV SCROLL EFFECT ============
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(255,253,249,0.95)';
                nav.style.boxShadow = '0 4px 30px rgba(74,21,37,0.1)';
            } else {
                nav.style.boxShadow = '0 1px 3px rgba(74,21,37,0.1)';
            }
        });

        // ============ FLOATING PETALS ============
        const petalsContainer = document.getElementById('petalsContainer');
        function createPetal() {
            const petal = document.createElement('div');
            petal.className = 'petal';
            const size = Math.random() * 15 + 10;
            petal.style.width = size + 'px';
            petal.style.height = (size * 1.2) + 'px';
            petal.style.left = Math.random() * 100 + '%';
            petal.style.animationDuration = (Math.random() * 8 + 8) + 's';
            petal.style.animationDelay = Math.random() * 5 + 's';
            petal.style.opacity = Math.random() * 0.5 + 0.3;

            const colors = ['#FBF2F4', '#F4E6E9', '#DFBA6B', '#FFFDF9'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            petal.style.background = `radial-gradient(ellipse at center, ${color} 0%, ${color}dd 70%)`;

            petalsContainer.appendChild(petal);
            setTimeout(() => petal.remove(), 20000);
        }
        setInterval(createPetal, 800);
        for (let i = 0; i < 15; i++) setTimeout(createPetal, i * 200);

        // ============ SPARKLES ============
        const sparklesContainer = document.getElementById('sparklesContainer');
        function createSparkle() {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            sparkle.style.left = Math.random() * 100 + '%';
            sparkle.style.top = Math.random() * 100 + '%';
            sparkle.style.animationDelay = Math.random() * 3 + 's';
            sparkle.style.animationDuration = (Math.random() * 2 + 2) + 's';
            const size = Math.random() * 3 + 2;
            sparkle.style.width = size + 'px';
            sparkle.style.height = size + 'px';
            sparklesContainer.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 5000);
        }
        setInterval(createSparkle, 500);

        // ============ COUNTDOWN TIMER ============
        const weddingDate = new Date();
        weddingDate.setDate(weddingDate.getDate() + 180);
        weddingDate.setHours(18, 0, 0, 0);

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = weddingDate.getTime() - now;

            if (distance < 0) return;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // ============ COUNTER ANIMATION ============
        const counters = document.querySelectorAll('[data-target]');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = parseInt(entry.target.getAttribute('data-target'));
                    let current = 0;
                    const increment = target / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            entry.target.textContent = target;
                            clearInterval(timer);
                        } else {
                            entry.target.textContent = Math.floor(current);
                        }
                    }, 40);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(counter => counterObserver.observe(counter));

        // ============ MUSIC PLAYER ============
        const weddingMusic = document.getElementById('weddingMusic');
        const musicBtn = document.getElementById('musicBtn');
        const soundWave = document.getElementById('soundWave');
        const musicTooltip = document.getElementById('musicTooltip');
        let isPlaying = false;

        weddingMusic.src = 'audio/wedding.mp3';

        // Set volume to 30% for pleasant background level
        weddingMusic.volume = 0.3;

        // Try to auto-play on first user interaction (browsers block autoplay)
        function tryAutoPlay() {
            if (!isPlaying) {
                weddingMusic.play().then(() => {
                    setPlayingState(true);
                }).catch(err => {
                    console.log('Autoplay blocked, waiting for user interaction');
                });
            }
        }

        function setPlayingState(playing) {
            isPlaying = playing;
            if (playing) {
                soundWave.classList.remove('paused');
                musicBtn.classList.add('playing');
                musicTooltip.textContent = 'Pause Music';
            } else {
                soundWave.classList.add('paused');
                musicBtn.classList.remove('playing');
                musicTooltip.textContent = 'Play Music';
            }
        }

        musicBtn.addEventListener('click', () => {
            if (isPlaying) {
                weddingMusic.pause();
                setPlayingState(false);
            } else {
                weddingMusic.play().then(() => {
                    setPlayingState(true);
                }).catch(err => {
                    console.error('Playback failed:', err);
                });
            }
        });

        // Attempt autoplay on first click anywhere on page
        document.addEventListener('click', function firstClickHandler() {
            tryAutoPlay();
            document.removeEventListener('click', firstClickHandler);
        }, { once: true });

        // Handle audio errors gracefully
        weddingMusic.addEventListener('error', () => {
            console.log('Audio source unavailable, music disabled');
            musicBtn.style.display = 'none';
        });

        // ============ 3D TILT EFFECT ON BUTTONS ============
        document.querySelectorAll('.tilt-btn').forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 8;
                const rotateY = (centerX - x) / 8;
                btn.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
            });
        });

        // ============ RSVP MODAL ============
        function openModal() {
            document.getElementById('rsvpModal').classList.add('active');
        }
        function closeModal() {
            document.getElementById('rsvpModal').classList.remove('active');
        }
        function submitRSVP(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = 'Response Sent ✓';
            btn.style.background = '#DFBA6B';
            btn.style.color = '#4A1525';
            setTimeout(() => {
                closeModal();
                btn.textContent = originalText;
                btn.style.background = '';
                btn.style.color = '';
                e.target.reset();
            }, 1500);
        }

        // Close modal on overlay click
        document.getElementById('rsvpModal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeModal();
        });

        // ============ SMOOTH SCROLL ============
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>

</html>
