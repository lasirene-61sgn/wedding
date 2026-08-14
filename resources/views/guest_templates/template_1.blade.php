<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $invitation->bride_name ?? 'Aanya' }} ♥ {{ $invitation->groom_name ?? 'Vihaan' }} · Shubh Vivaah</title>
    <meta name="description"
        content="You are warmly invited to the wedding of {{ $invitation->bride_name ?? 'Aanya' }} & {{ $invitation->groom_name ?? 'Vihaan' }}." />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/rozha-one@latest/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/great-vibes@latest/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontsource/css/mulish@latest/index.css" />
    <style>
        :root {
            --maroon: #4A0E12;
            --maroon-deep: #34080C;
            --maroon-2: #5E1118;
            --red: #A31621;
            --crimson: #C21E2A;
            --gold: #D9A441;
            --gold-b: #F0C060;
            --gold-p: #F3DCA0;
            --marigold: #F5A623;
            --saffron: #FF8F1F;
            --cream: #FBF0DA;
            --cream-2: #F4E3BE;
            --ink: #2E0A0D;
            --emerald: #14655A;
            --display: "Rozha One", "Noto Serif Devanagari", serif;
            --script: "Great Vibes", cursive;
            --body: "Mulish", system-ui, sans-serif;
            --maxw: 1180px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            scroll-behavior: auto;
            scroll-padding-top: 84px
        }

        body {
            font-family: var(--body);
            color: var(--ink);
            background: var(--maroon-deep);
            overflow: hidden;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        body.begun {
            overflow: auto
        }

        img {
            display: block;
            max-width: 100%
        }

        a {
            color: inherit;
            text-decoration: none
        }

        ::selection {
            background: var(--gold-b);
            color: var(--maroon-deep)
        }

        #petals {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 200;
            pointer-events: none
        }

        #progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0;
            z-index: 300;
            background: linear-gradient(90deg, var(--marigold), var(--gold-b), var(--crimson));
            box-shadow: 0 0 12px rgba(240, 192, 96, .7)
        }

        #nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 250;
            transform: translateY(-110%);
            transition: transform .5s cubic-bezier(.2, .8, .2, 1);
            background: rgba(52, 8, 12, .94);
            border-bottom: 1px solid rgba(217, 164, 65, .35);
            backdrop-filter: saturate(120%)
        }

        #nav.show {
            transform: translateY(0)
        }

        .nav-in {
            max-width: var(--maxw);
            margin: 0 auto;
            padding: .7rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: space-between
        }

        /* ---------- 3D NAV LOGO ---------- */
        .nav-mono {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-family: var(--display);
            font-size: 1.35rem;
            letter-spacing: .04em;
            color: var(--gold-b);
            text-decoration: none;
            perspective: 500px;
        }

        .nav-mono .nav-lotus {
            width: 18px;
            height: 18px;
            color: var(--gold-b);
            filter: drop-shadow(0 0 4px rgba(240, 192, 96, .6));
            animation: spin 14s linear infinite;
        }

        .nav-mono .nav-mark {
            display: inline-block;
            background: linear-gradient(180deg, #fff4c9 0%, #f0c060 40%, #b9892f 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            filter:
                drop-shadow(0 1px 0 rgba(255, 240, 200, .5)) drop-shadow(0 2px 0 rgba(120, 80, 20, .7));
            transform-style: preserve-3d;
            animation: navFloat 3.6s ease-in-out infinite;
        }

        .nav-mono .nav-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #fff4c9, #d9a441 60%, #7a5a18);
            box-shadow: 0 0 8px rgba(240, 192, 96, .8);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes navFloat {

            0%,
            100% {
                transform: rotateY(0) translateY(0)
            }

            50% {
                transform: rotateY(14deg) translateY(-1px)
            }
        }

        .nav-links {
            display: flex;
            gap: .2rem;
            overflow-x: auto;
            scrollbar-width: none
        }

        .nav-links::-webkit-scrollbar {
            display: none
        }

        .nav-links a {
            font-family: var(--body);
            font-weight: 700;
            font-size: .72rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--cream);
            padding: .5rem .7rem;
            border-radius: 999px;
            white-space: nowrap;
            transition: .25s
        }

        .nav-links a:hover {
            color: var(--maroon-deep);
            background: var(--gold-b)
        }

        .nav-rsvp {
            font-family: var(--body);
            font-weight: 800;
            font-size: .72rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--maroon-deep);
            background: var(--gold-b);
            padding: .55rem 1rem;
            border-radius: 999px;
            transition: .25s;
            white-space: nowrap
        }

        .nav-rsvp:hover {
            background: var(--marigold);
            transform: translateY(-2px)
        }

        #gate {
            position: fixed;
            inset: 0;
            z-index: 400;
            overflow: hidden
        }

        .gp {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            background:
                radial-gradient(120% 80% at 50% 30%, rgba(94, 17, 24, .4), transparent 60%),
                linear-gradient(180deg, var(--maroon), var(--maroon-deep));
            transition: transform 1.15s cubic-bezier(.76, 0, .24, 1)
        }

        .gp::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url("https://image.qwenlm.ai/public_source/941bea6b-9409-4ea8-911c-4611ff7a77ee/19c744af3-3ad9-4de7-80ec-d7e04e6b0ee1.png") center/cover;
            opacity: .12;
            mix-blend-mode: screen
        }

        .gp.l {
            left: 0;
            border-right: 1px solid rgba(217, 164, 65, .5)
        }

        .gp.r {
            right: 0;
            border-left: 1px solid rgba(217, 164, 65, .5)
        }

        #gate.open .gp.l {
            transform: translateX(-101%)
        }

        #gate.open .gp.r {
            transform: translateX(101%)
        }

        .seal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.1rem;
            cursor: pointer;
            transition: opacity .6s ease, transform .9s cubic-bezier(.7, 0, .3, 1);
            text-align: center;
            background: none;
            border: none
        }

        #gate.open .seal {
            opacity: 0;
            transform: translate(-50%, -50%) scale(1.7);
            pointer-events: none
        }

        .seal-top {
            font-family: var(--display);
            color: var(--gold-b);
            font-size: clamp(.8rem, 2.4vw, 1.05rem);
            letter-spacing: .05em;
            text-shadow: 0 2px 14px rgba(0, 0, 0, .6)
        }

        .seal-disc {
            position: relative;
            width: clamp(150px, 40vw, 200px);
            height: clamp(150px, 40vw, 200px);
            display: grid;
            place-items: center
        }

        .seal-disc svg.ring {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            animation: spin 22s linear infinite
        }

        .seal-disc svg.ring text {
            fill: var(--gold-p);
            font-family: var(--body);
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 3.4px;
            text-transform: uppercase
        }

        /* ---------- 3D WAX SEAL ---------- */
        .wax {
            width: 62%;
            height: 62%;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 35% 28%, #8a1c28 0%, #5a1018 45%, #2c070a 100%);
            box-shadow:
                0 10px 30px rgba(0, 0, 0, .55),
                inset 0 2px 6px rgba(255, 210, 140, .35),
                inset 0 -10px 22px rgba(0, 0, 0, .6),
                0 0 0 2px rgba(240, 192, 96, .4);
            border: 3px solid var(--gold);
            position: relative;
            perspective: 600px;
            overflow: visible;
        }

        .wax::before {
            content: "";
            position: absolute;
            inset: -14%;
            background:
                conic-gradient(from 0deg,
                    transparent 0 8%, rgba(240, 192, 96, .55) 8% 10%, transparent 10% 20%,
                    rgba(240, 192, 96, .55) 20% 22%, transparent 22% 32%,
                    rgba(240, 192, 96, .55) 32% 34%, transparent 34% 45%,
                    rgba(240, 192, 96, .55) 45% 47%, transparent 47% 58%,
                    rgba(240, 192, 96, .55) 58% 60%, transparent 60% 70%,
                    rgba(240, 192, 96, .55) 70% 72%, transparent 72% 82%,
                    rgba(240, 192, 96, .55) 82% 84%, transparent 84% 95%,
                    rgba(240, 192, 96, .55) 95% 97%, transparent 97% 100%);
            border-radius: 50%;
            animation: spin 18s linear infinite;
            opacity: .75;
            filter: blur(.3px);
            z-index: 0;
        }

        .wax::after {
            content: "";
            position: absolute;
            inset: 6%;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 28%, #8a1c28 0%, #5a1018 55%, #2c070a 100%);
            box-shadow: inset 0 2px 6px rgba(255, 210, 140, .3), inset 0 -8px 18px rgba(0, 0, 0, .55);
            z-index: 1;
        }

        .wax b {
            position: relative;
            z-index: 3;
            font-family: var(--display);
            font-size: clamp(1.6rem, 6vw, 2.4rem);
            letter-spacing: .04em;
            background: linear-gradient(180deg,
                    #fff4c9 0%, #f0c060 28%, #d9a441 52%, #b9892f 78%, #7a5a18 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            filter:
                drop-shadow(0 1px 0 rgba(255, 240, 200, .5)) drop-shadow(0 2px 0 rgba(180, 120, 40, .6)) drop-shadow(0 3px 6px rgba(0, 0, 0, .7));
            transform-style: preserve-3d;
            animation: float3d 4.5s ease-in-out infinite;
        }

        .wax .crown {
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 22px;
            height: 22px;
            color: var(--gold-b);
            z-index: 4;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .6));
            animation: float3d 4.5s ease-in-out infinite;
        }

        .wax .shine {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            z-index: 5;
            pointer-events: none;
            background: linear-gradient(115deg, transparent 30%, rgba(255, 240, 200, .35) 48%, rgba(255, 255, 255, .55) 50%, rgba(255, 240, 200, .35) 52%, transparent 70%);
            background-size: 220% 220%;
            animation: shine 3.8s ease-in-out infinite;
            mix-blend-mode: screen;
        }

        @keyframes float3d {

            0%,
            100% {
                transform: translateY(0) rotateX(0deg) rotateY(0deg)
            }

            25% {
                transform: translateY(-2px) rotateX(8deg) rotateY(-6deg)
            }

            50% {
                transform: translateY(-4px) rotateX(0deg) rotateY(0deg)
            }

            75% {
                transform: translateY(-2px) rotateX(-8deg) rotateY(6deg)
            }
        }

        @keyframes shine {
            0% {
                background-position: 220% 0
            }

            60%,
            100% {
                background-position: -80% 0
            }
        }

        .seal-cta {
            font-family: var(--body);
            font-weight: 800;
            font-size: .72rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--cream);
            animation: pulse 2s ease-in-out infinite
        }

        .seal-cta .arr {
            display: inline-block;
            animation: bob 1.4s ease-in-out infinite
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: .6
            }

            50% {
                opacity: 1
            }
        }

        @keyframes bob {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(4px)
            }
        }

        .hero {
            position: relative;
            min-height: 100svh;
            display: grid;
            place-items: center;
            overflow: hidden;
            background: var(--maroon-deep);
            padding: 6rem 1.2rem 4rem
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: url("https://image.qwenlm.ai/public_source/941bea6b-9409-4ea8-911c-4611ff7a77ee/19c744af3-3ad9-4de7-80ec-d7e04e6b0ee1.png") center/cover;
            opacity: .42;
            animation: kenburns 26s ease-in-out infinite alternate;
            will-change: transform
        }

        .hero-vig {
            position: absolute;
            inset: 0;
            background: radial-gradient(80% 70% at 50% 45%, transparent 30%, rgba(52, 8, 12, .55) 70%, var(--maroon-deep) 100%)
        }

        @keyframes kenburns {
            from {
                transform: scale(1.05) rotate(0deg)
            }

            to {
                transform: scale(1.18) rotate(2deg)
            }
        }

        .hero-core {
            position: relative;
            z-index: 3;
            width: min(640px, 92vw, 78svh);
            aspect-ratio: 1/1;
            max-height: 78svh;
            display: grid;
            place-items: center
        }

        .ring {
            position: absolute;
            border-radius: 50%;
            inset: 0;
            display: grid;
            place-items: center
        }

        .ring svg {
            width: 100%;
            height: 100%;
            overflow: visible
        }

        .ring-a svg {
            animation: spin 70s linear infinite
        }

        .ring-b svg {
            animation: spin 50s linear infinite reverse;
            inset: 8%;
            width: 84%;
            height: 84%;
            position: absolute
        }

        .lotus {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 46px;
            height: 46px;
            color: var(--gold-b);
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .5))
        }

        .lotus.top {
            top: -6px
        }

        .lotus.bot {
            bottom: -6px;
            transform: translateX(-50%) rotate(180deg)
        }

        .core-inner {
            position: relative;
            z-index: 4;
            text-align: center;
            padding: 1rem;
            opacity: 0;
            transform: translateY(18px) scale(.96);
            transition: opacity 1s ease .2s, transform 1s cubic-bezier(.2, .8, .2, 1) .2s
        }

        body.begun .core-inner {
            opacity: 1;
            transform: none
        }

        .blessing {
            font-family: var(--display);
            color: var(--gold-b);
            font-size: clamp(.7rem, 2.3vw, .95rem);
            letter-spacing: .03em;
            line-height: 1.5;
            margin-bottom: .9rem;
            text-shadow: 0 2px 12px rgba(0, 0, 0, .6)
        }

        .names {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            flex-wrap: wrap;
            font-family: var(--script);
            color: var(--gold-b);
            line-height: .9;
            text-shadow: 0 3px 18px rgba(0, 0, 0, .55), 0 0 1px var(--gold)
        }

        .names span {
            font-size: clamp(3rem, 11vw, 6.2rem)
        }

        .names .amp {
            font-family: var(--display);
            color: var(--marigold);
            font-size: clamp(1.4rem, 5vw, 2.4rem);
            align-self: center;
            animation: twinkle 3s ease-in-out infinite
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: .7;
                transform: scale(1)
            }

            50% {
                opacity: 1;
                transform: scale(1.18)
            }
        }

        .saveline {
            display: flex;
            align-items: center;
            gap: .8rem;
            justify-content: center;
            margin: 1.1rem 0 .6rem;
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .42em;
            text-transform: uppercase;
            color: var(--cream);
            font-size: clamp(.6rem, 2vw, .78rem)
        }

        .saveline span {
            height: 1px;
            width: clamp(28px, 12vw, 70px);
            background: linear-gradient(90deg, transparent, var(--gold-b))
        }

        .saveline span:last-child {
            background: linear-gradient(90deg, var(--gold-b), transparent)
        }

        .date {
            font-family: var(--display);
            color: var(--gold-p);
            font-size: clamp(1rem, 3.6vw, 1.5rem);
            letter-spacing: .04em
        }

        .diya {
            position: absolute;
            bottom: 5.5rem;
            width: 54px;
            z-index: 3;
            filter: drop-shadow(0 0 14px rgba(245, 166, 35, .5))
        }

        .diya.left {
            left: 6%
        }

        .diya.right {
            right: 6%
        }

        .flame {
            transform-origin: 50% 100%;
            animation: flick 1.6s ease-in-out infinite
        }

        .diya.right .flame {
            animation-delay: .4s
        }

        @keyframes flick {

            0%,
            100% {
                transform: scale(1) skewX(0)
            }

            25% {
                transform: scale(1.08, .95) skewX(3deg)
            }

            50% {
                transform: scale(.96, 1.06) skewX(-2deg)
            }

            75% {
                transform: scale(1.05, .98) skewX(2deg)
            }
        }

        .scroll-cue {
            position: absolute;
            bottom: 4.6rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            text-align: center;
            color: var(--gold-p);
            font-family: var(--body);
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            font-size: .62rem;
            background: none;
            border: none;
            cursor: pointer
        }

        .scroll-cue .dot {
            display: block;
            margin: .5rem auto 0;
            width: 22px;
            height: 34px;
            border: 2px solid var(--gold-b);
            border-radius: 14px;
            position: relative
        }

        .scroll-cue .dot::after {
            content: "";
            position: absolute;
            top: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 7px;
            border-radius: 3px;
            background: var(--gold-b);
            animation: wheel 1.6s ease-in-out infinite
        }

        @keyframes wheel {
            0% {
                opacity: 0;
                transform: translate(-50%, 0)
            }

            40% {
                opacity: 1
            }

            80% {
                opacity: 0;
                transform: translate(-50%, 12px)
            }
        }

        .marquee {
            position: relative;
            z-index: 4;
            background: var(--gold);
            color: var(--maroon-deep);
            border-top: 2px solid var(--maroon);
            border-bottom: 2px solid var(--maroon);
            overflow: hidden
        }

        .marquee__track {
            display: inline-flex;
            white-space: nowrap;
            animation: mq 30s linear infinite
        }

        .marquee:hover .marquee__track {
            animation-play-state: paused
        }

        .marquee__track span {
            font-family: var(--display);
            font-size: 1rem;
            letter-spacing: .08em;
            padding: .55rem 1.4rem;
            display: inline-flex;
            align-items: center;
            gap: 1.4rem
        }

        .marquee__track span::after {
            content: "✦";
            color: var(--crimson)
        }

        @keyframes mq {
            from {
                transform: translateX(0)
            }

            to {
                transform: translateX(-50%)
            }
        }

        section {
            position: relative;
            padding: clamp(4rem, 9vw, 7.5rem) 1.2rem
        }

        .wrap {
            max-width: var(--maxw);
            margin: 0 auto
        }

        .cream {
            background: var(--cream);
            color: var(--ink)
        }

        .cream-2 {
            background: var(--cream-2);
            color: var(--ink)
        }

        .dark {
            background: var(--maroon);
            color: var(--cream)
        }

        .dark-2 {
            background: var(--maroon-deep);
            color: var(--cream)
        }

        .dark .pat,
        .dark-2 .pat {
            position: absolute;
            inset: 0;
            opacity: .06;
            pointer-events: none;
            background: url("https://image.qwenlm.ai/public_source/941bea6b-9409-4ea8-911c-4611ff7a77ee/19c744af3-3ad9-4de7-80ec-d7e04e6b0ee1.png") center/520px repeat
        }

        .sec-head {
            text-align: center;
            max-width: 760px;
            margin: 0 auto clamp(2.4rem, 5vw, 3.6rem)
        }

        .kicker {
            font-family: var(--script);
            font-size: clamp(1.6rem, 5vw, 2.4rem);
            line-height: 1;
            margin-bottom: .2rem
        }

        .cream .kicker,
        .cream-2 .kicker {
            color: var(--red)
        }

        .dark .kicker,
        .dark-2 .kicker {
            color: var(--gold-b)
        }

        .sec-title {
            font-family: var(--display);
            font-size: clamp(2rem, 6.5vw, 3.4rem);
            line-height: 1.05;
            letter-spacing: .01em
        }

        .cream .sec-title,
        .cream-2 .sec-title {
            color: var(--maroon)
        }

        .dark .sec-title,
        .dark-2 .sec-title {
            color: var(--gold-b)
        }

        .sec-hi {
            font-family: var(--display);
            font-size: clamp(1rem, 3vw, 1.3rem);
            margin-top: .2rem;
            opacity: .85
        }

        .cream .sec-hi,
        .cream-2 .sec-hi {
            color: var(--crimson)
        }

        .dark .sec-hi,
        .dark-2 .sec-hi {
            color: var(--marigold)
        }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .7rem;
            margin-top: 1rem
        }

        .divider i {
            height: 1px;
            width: clamp(34px, 10vw, 80px);
            background: currentColor;
            opacity: .5
        }

        .divider svg {
            width: 26px;
            height: 26px;
            color: var(--gold)
        }

        .sec-lead {
            text-align: center;
            max-width: 640px;
            margin: 0 auto;
            color: inherit;
            opacity: .85;
            font-size: 1.02rem
        }

        .rv {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity .8s ease, transform .8s cubic-bezier(.2, .8, .2, 1);
            transition-delay: var(--d, 0s)
        }

        .rv.in {
            opacity: 1;
            transform: none
        }

        .letter {
            position: relative;
            max-width: 760px;
            margin: 0 auto;
            background: #fff8ea;
            border: 1px solid rgba(163, 22, 33, .25);
            padding: clamp(2rem, 6vw, 3.6rem);
            box-shadow: 0 24px 60px rgba(74, 14, 18, .18)
        }

        .letter::before {
            content: "";
            position: absolute;
            inset: 10px;
            border: 1px solid var(--gold);
            pointer-events: none
        }

        .corner {
            position: absolute;
            width: 54px;
            height: 54px;
            color: var(--gold);
            opacity: .9
        }

        .corner.tl {
            top: 14px;
            left: 14px
        }

        .corner.tr {
            top: 14px;
            right: 14px;
            transform: scaleX(-1)
        }

        .corner.bl {
            bottom: 14px;
            left: 14px;
            transform: scaleY(-1)
        }

        .corner.br {
            bottom: 14px;
            right: 14px;
            transform: scale(-1)
        }

        .letter .om {
            font-family: var(--display);
            color: var(--crimson);
            font-size: 1.6rem;
            text-align: center;
            margin-bottom: .6rem
        }

        .letter h3 {
            font-family: var(--script);
            color: var(--red);
            font-size: clamp(1.6rem, 5vw, 2.2rem);
            text-align: center;
            margin-bottom: 1rem
        }

        .letter p {
            margin-bottom: 1rem;
            text-align: center;
            color: #4a2427
        }

        .letter .sign {
            font-family: var(--script);
            color: var(--maroon);
            font-size: 1.5rem;
            text-align: center;
            margin-top: 1.4rem
        }

        .letter .hosts {
            font-family: var(--display);
            color: var(--maroon);
            text-align: center;
            letter-spacing: .03em;
            margin-top: .4rem
        }

        .count {
            display: flex;
            justify-content: center;
            gap: clamp(.6rem, 3vw, 1.8rem);
            flex-wrap: wrap;
            margin-bottom: 2.6rem
        }

        .count .unit {
            min-width: 96px;
            text-align: center
        }

        .count .num {
            font-family: var(--display);
            color: var(--gold-b);
            font-size: clamp(2.6rem, 9vw, 4.6rem);
            line-height: 1;
            text-shadow: 0 0 22px rgba(240, 192, 96, .35)
        }

        .count .num.pop {
            animation: pop .4s ease
        }

        @keyframes pop {
            0% {
                transform: scale(1)
            }

            40% {
                transform: scale(1.16);
                color: var(--marigold)
            }

            100% {
                transform: scale(1)
            }
        }

        .count .lab {
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
            font-size: .66rem;
            color: var(--gold-p);
            margin-top: .4rem
        }

        .count .sep {
            font-family: var(--display);
            color: var(--crimson);
            font-size: clamp(2rem, 7vw, 3.4rem);
            align-self: flex-start
        }

        .scratch-wrap {
            max-width: 560px;
            margin: 0 auto;
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 18px 50px rgba(0, 0, 0, .4);
            border: 2px solid var(--gold)
        }

        .scratch-card {
            padding: 2rem 1.6rem;
            background: linear-gradient(160deg, #5e1118, #34080c);
            text-align: center;
            position: relative
        }

        .scratch-card .tag {
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .2em;
            text-transform: uppercase;
            font-size: .66rem;
            color: var(--marigold)
        }

        .scratch-card .fn {
            font-family: var(--display);
            color: var(--gold-b);
            font-size: clamp(1.6rem, 6vw, 2.4rem);
            margin: .2rem 0
        }

        .scratch-card .row {
            display: flex;
            justify-content: center;
            gap: 1.4rem;
            flex-wrap: wrap;
            margin-top: .6rem;
            color: var(--cream)
        }

        .scratch-card .row div {
            font-family: var(--body)
        }

        .scratch-card .row b {
            display: block;
            font-family: var(--display);
            color: var(--gold-p);
            font-size: 1.1rem
        }

        .scratch-card .row small {
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            font-size: .6rem;
            opacity: .7
        }

        #scratch {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            touch-action: none;
            cursor: pointer;
            transition: opacity .7s ease
        }

        #scratch.done {
            opacity: 0;
            pointer-events: none
        }

        .scratch-hint {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
            font-size: .7rem;
            color: var(--maroon-deep);
            background: rgba(255, 243, 220, .92);
            padding: .5rem 1rem;
            border-radius: 999px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .3);
            pointer-events: none;
            animation: pulse 1.6s ease-in-out infinite
        }

        .scratch-hint.hide {
            opacity: 0;
            transition: opacity .4s
        }

        .timeline {
            position: relative;
            max-width: 920px;
            margin: 0 auto;
            padding-left: 8px
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 23px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: linear-gradient(var(--gold), var(--crimson), var(--gold))
        }

        .ev {
            position: relative;
            padding: 0 0 2rem 64px
        }

        .ev .badge {
            position: absolute;
            left: 0;
            top: 0;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--ac, var(--gold));
            color: #fff;
            font-family: var(--display);
            font-size: 1.3rem;
            box-shadow: 0 0 0 4px var(--cream-2), 0 6px 16px rgba(0, 0, 0, .2);
            z-index: 2
        }

        .ev-card {
            background: #fff8ea;
            border: 1px solid rgba(163, 22, 33, .18);
            border-left: 5px solid var(--ac, var(--gold));
            border-radius: 10px;
            padding: 1.3rem 1.4rem;
            box-shadow: 0 10px 28px rgba(74, 14, 18, .1);
            transition: .3s
        }

        .ev-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(74, 14, 18, .2);
            border-left-width: 9px
        }

        .ev-date {
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            font-size: .68rem;
            color: var(--ac, var(--red))
        }

        .ev-title {
            font-family: var(--display);
            color: var(--maroon);
            font-size: clamp(1.3rem, 4vw, 1.7rem);
            margin: .1rem 0 .5rem
        }

        .ev-desc {
            color: #4a2427;
            font-size: .96rem
        }

        .ev-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            margin-top: .9rem
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .74rem;
            font-weight: 700;
            background: rgba(163, 22, 33, .08);
            color: var(--maroon);
            padding: .35rem .7rem;
            border-radius: 999px
        }

        .chip .dotc {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, .15)
        }

        .gallery {
            display: flex;
            gap: 1.2rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: .4rem .2rem 1.4rem;
            scrollbar-width: thin;
            scrollbar-color: var(--gold) transparent
        }

        .gallery::-webkit-scrollbar {
            height: 8px
        }

        .gallery::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 8px
        }

        .gcard {
            flex: 0 0 min(360px, 82vw);
            scroll-snap-align: center;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--gold);
            box-shadow: 0 14px 36px rgba(0, 0, 0, .4)
        }

        .gcard img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            transition: transform .8s ease
        }

        .gcard:hover img {
            transform: scale(1.08)
        }

        .gcard figcaption {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 1.4rem 1.1rem .9rem;
            color: var(--cream);
            background: linear-gradient(transparent, rgba(52, 8, 12, .92));
            font-family: var(--script);
            font-size: 1.3rem;
            line-height: 1.2
        }

        .gal-hint {
            text-align: center;
            color: var(--gold-p);
            font-size: .7rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            font-weight: 700;
            margin-top: .4rem;
            opacity: .8
        }

        .vgrid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.4rem
        }

        .vcard {
            background: #fff8ea;
            border: 1px solid rgba(163, 22, 33, .18);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(74, 14, 18, .12);
            transition: .3s;
            display: flex;
            flex-direction: column
        }

        .vcard:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 46px rgba(74, 14, 18, .22)
        }

        .vcard .vimg {
            height: 180px;
            overflow: hidden
        }

        .vcard .vimg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .8s
        }

        .vcard:hover .vimg img {
            transform: scale(1.08)
        }

        .vcard .vicon {
            height: 120px;
            display: grid;
            place-items: center;
            background: linear-gradient(160deg, var(--maroon), var(--maroon-deep));
            color: var(--gold-b)
        }

        .vcard .vicon svg {
            width: 54px;
            height: 54px
        }

        .vbody {
            padding: 1.3rem 1.3rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: .5rem;
            flex: 1
        }

        .vtag {
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
            font-size: .64rem;
            color: var(--crimson)
        }

        .vname {
            font-family: var(--display);
            color: var(--maroon);
            font-size: 1.4rem
        }

        .vaddr {
            color: #4a2427;
            font-size: .92rem
        }

        .vnote {
            font-style: italic;
            color: var(--red);
            font-size: .86rem;
            margin-top: .2rem
        }

        .vbtn {
            margin-top: auto;
            align-self: flex-start;
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-size: .68rem;
            color: var(--maroon);
            border: 1.5px solid var(--gold);
            padding: .55rem 1rem;
            border-radius: 999px;
            transition: .25s
        }

        .vbtn:hover {
            background: var(--gold);
            color: var(--maroon-deep)
        }

        .rsvp-card {
            position: relative;
            max-width: 680px;
            margin: 0 auto;
            background: #fff8ea;
            border: 1px solid rgba(163, 22, 33, .25);
            padding: clamp(1.8rem, 5vw, 3rem);
            border-radius: 14px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .4)
        }

        .rsvp-card::before {
            content: "";
            position: absolute;
            inset: 9px;
            border: 1px solid var(--gold);
            border-radius: 10px;
            pointer-events: none
        }

        .field {
            margin-bottom: 1.2rem
        }

        .field label {
            display: block;
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-size: .68rem;
            color: var(--maroon);
            margin-bottom: .45rem
        }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            font-family: var(--body);
            font-size: 1rem;
            color: var(--ink);
            background: #fff;
            border: 1.5px solid rgba(163, 22, 33, .25);
            border-radius: 8px;
            padding: .7rem .85rem;
            transition: .2s
        }

        .field input:focus,
        .field textarea:focus,
        .field select:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(217, 164, 65, .25)
        }

        .field textarea {
            resize: vertical;
            min-height: 84px
        }

        .pills {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem
        }

        .pill {
            position: relative
        }

        .pill input {
            position: absolute;
            opacity: 0;
            inset: 0;
            cursor: pointer
        }

        .pill span {
            display: inline-block;
            font-family: var(--body);
            font-weight: 700;
            font-size: .82rem;
            color: var(--maroon);
            border: 1.5px solid rgba(163, 22, 33, .3);
            border-radius: 999px;
            padding: .5rem 1rem;
            cursor: pointer;
            transition: .2s
        }

        .pill input:checked+span {
            background: var(--red);
            color: #fff;
            border-color: var(--red);
            box-shadow: 0 6px 16px rgba(163, 22, 33, .3)
        }

        .pill input:focus-visible+span {
            outline: 2px solid var(--gold);
            outline-offset: 2px
        }

        .submit {
            position: relative;
            overflow: hidden;
            width: 100%;
            border: none;
            cursor: pointer;
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
            font-size: .82rem;
            color: var(--maroon-deep);
            background: linear-gradient(90deg, var(--marigold), var(--gold-b));
            padding: .95rem 1rem;
            border-radius: 999px;
            transition: .25s
        }

        .submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(217, 164, 65, .4)
        }

        .submit::after {
            content: "";
            position: absolute;
            top: 0;
            left: -60%;
            width: 40%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .6), transparent);
            transform: skewX(-20deg);
            transition: left .6s
        }

        .submit:hover::after {
            left: 120%
        }

        .err {
            color: var(--crimson);
            font-size: .78rem;
            font-weight: 700;
            margin-top: .3rem;
            display: none
        }

        .err.show {
            display: block
        }

        .success {
            display: none;
            text-align: center;
            padding: 1rem
        }

        .success.show {
            display: block;
            animation: fadeUp .6s ease
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .success .check {
            width: 84px;
            height: 84px;
            margin: 0 auto 1rem
        }

        .success .check circle,
        .success .check path {
            fill: none;
            stroke: var(--emerald);
            stroke-width: 5;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 240;
            stroke-dashoffset: 240;
            animation: draw 1s ease forwards
        }

        .success .check path {
            animation-delay: .4s
        }

        @keyframes draw {
            to {
                stroke-dashoffset: 0
            }
        }

        .success h3 {
            font-family: var(--display);
            color: var(--maroon);
            font-size: 2rem
        }

        .success .hi {
            font-family: var(--display);
            color: var(--crimson);
            font-size: 1.1rem
        }

        .success p {
            color: #4a2427;
            margin: .6rem 0 1.2rem
        }

        .ghost {
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-size: .7rem;
            color: var(--maroon);
            background: none;
            border: 1.5px solid var(--gold);
            padding: .6rem 1.1rem;
            border-radius: 999px;
            cursor: pointer;
            transition: .2s
        }

        .ghost:hover {
            background: var(--gold)
        }

        footer {
            background: var(--maroon-deep);
            color: var(--cream);
            text-align: center;
            padding: 3.5rem 1.2rem 2rem;
            position: relative
        }

        /* ---------- 3D FOOTER AV ---------- */
        footer .fmono {
            font-family: var(--script);
            font-size: 2.4rem;
            display: inline-block;
            background: linear-gradient(180deg, #fff4c9 0%, #f0c060 50%, #b9892f 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            filter: drop-shadow(0 2px 6px rgba(240, 192, 96, .35));
            position: relative;
        }

        footer .fmono::before,
        footer .fmono::after {
            content: "✦";
            color: var(--marigold);
            font-size: .9rem;
            margin: 0 .6rem;
            vertical-align: middle;
            -webkit-text-fill-color: var(--marigold);
            animation: twinkle 3s ease-in-out infinite;
        }

        footer .fmono::after {
            animation-delay: 1.5s
        }

        footer .fline {
            font-family: var(--display);
            color: var(--gold-p);
            letter-spacing: .04em;
            margin: .4rem 0
        }

        footer .hash {
            font-family: var(--body);
            font-weight: 800;
            letter-spacing: .1em;
            color: var(--marigold);
            margin: .6rem 0
        }

        footer .small {
            font-size: .74rem;
            opacity: .6;
            margin-top: 1.4rem;
            letter-spacing: .08em
        }

        footer .fdiya {
            width: 40px;
            margin: 1rem auto 0
        }

        #top {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 240;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            background: rgba(52, 8, 12, .92);
            color: var(--gold-b);
            cursor: pointer;
            display: grid;
            place-items: center;
            opacity: 0;
            transform: translateY(20px) scale(.8);
            pointer-events: none;
            transition: .35s
        }

        #top.show {
            opacity: 1;
            transform: none;
            pointer-events: auto
        }

        #top:hover {
            background: var(--gold);
            color: var(--maroon-deep)
        }

        #top svg {
            width: 22px;
            height: 22px
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @media (max-width:640px) {
            .hero {
                padding: 4.5rem 1rem 3rem
            }

            .hero-core {
                width: min(92vw, 62svh)
            }

            .core-inner {
                padding: .6rem
            }

            .blessing {
                font-size: .6rem;
                line-height: 1.45;
                margin-bottom: .6rem
            }

            .names span {
                font-size: clamp(2.3rem, 12.5vw, 3.2rem)
            }

            .names .amp {
                font-size: 1.05rem
            }

            .saveline {
                letter-spacing: .28em;
                margin: .8rem 0 .45rem;
                font-size: .58rem
            }

            .saveline span {
                width: 24px
            }

            .date {
                font-size: .92rem
            }

            .lotus {
                width: 32px;
                height: 32px
            }

            .lotus.top {
                top: -4px
            }

            .lotus.bot {
                bottom: -4px
            }

            .diya {
                width: 36px;
                bottom: 4.4rem
            }

            .diya.left {
                left: 4%
            }

            .diya.right {
                right: 4%
            }

            .scroll-cue {
                bottom: 3.4rem;
                font-size: .56rem
            }

            .scroll-cue .dot {
                width: 18px;
                height: 28px
            }

            .timeline::before {
                left: 19px
            }

            .ev {
                padding-left: 52px
            }

            .ev .badge {
                width: 40px;
                height: 40px;
                font-size: 1.1rem
            }
        }

        @media (prefers-reduced-motion:reduce) {
            * {
                animation: none !important;
                transition: none !important
            }

            #petals {
                display: none
            }
        }
    </style>
</head>

<body>

    <canvas id="petals" aria-hidden="true"></canvas>
    <div id="progress" aria-hidden="true"></div>

    <div id="gate" role="dialog" aria-label="Open the wedding invitation">
        <div class="gp l"></div>
        <div class="gp r"></div>
        <button class="seal" id="sealBtn" aria-label="Break the seal to open the invitation">
            <span class="seal-top">॥ श्री गणेशाय नमः ॥</span>
            <span class="seal-disc">
                <svg class="ring" viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                        <path id="rp" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0" />
                    </defs>
                    <text>
                        <textPath href="#rp" startOffset="0">break the seal ✦ शुभ विवाह ✦ {{ strtolower($invitation->bride_name ?? 'aanya') }} &amp; {{ strtolower($invitation->groom_name ?? 'vihaan') }} ✦
                        </textPath>
                    </text>
                </svg>
                <span class="wax">
                    <svg class="crown" viewBox="0 0 64 64" aria-hidden="true">
                        <path fill="currentColor"
                            d="M32 4c4 8 4 16 0 24-4-8-4-16 0-24zM14 14c8 4 12 12 12 20-8-2-14-8-12-20zM50 14c2 12-4 18-12 20 0-8 4-16 12-20zM6 30c10 0 18 4 22 12-10 2-18-2-22-12zM58 30c-4 10-12 14-22 12 4-8 12-12 22-12z" />
                    </svg>
                    <b>{{ $invitation ? substr($invitation->bride_name, 0, 1) : 'A' }}·{{ $invitation ? substr($invitation->groom_name, 0, 1) : 'V' }}</b>
                    <span class="shine" aria-hidden="true"></span>
                </span>
            </span>
            <span class="seal-cta">Break the seal to begin <span class="arr">↓</span></span>
        </button>
    </div>

    <nav id="nav" aria-label="Sections">
        <div class="nav-in">
            <a class="nav-mono" href="#hero" aria-label="Home">
                <svg class="nav-lotus" viewBox="0 0 64 64" aria-hidden="true">
                    <path fill="currentColor"
                        d="M32 4c4 8 4 16 0 24-4-8-4-16 0-24zM14 14c8 4 12 12 12 20-8-2-14-8-12-20zM50 14c2 12-4 18-12 20 0-8 4-16 12-20zM6 30c10 0 18 4 22 12-10 2-18-2-22-12zM58 30c-4 10-12 14-22 12 4-8 12-12 22-12z" />
                </svg>
                <span class="nav-mark">{{ $invitation ? substr($invitation->bride_name, 0, 1) : 'A' }}</span>
                <span class="nav-dot"></span>
                <span class="nav-mark">{{ $invitation ? substr($invitation->groom_name, 0, 1) : 'V' }}</span>
            </a>
            <div class="nav-links" data-lenis-prevent>
                <a href="#invitation">Invitation</a>
                <a href="#muhurat">Muhurat</a>
                <a href="#functions">Functions</a>
                <a href="#gallery">Yaadein</a>
                <a href="#venues">Venues</a>
            </div>
            <a class="nav-rsvp" href="#rsvp">RSVP</a>
        </div>
    </nav>

    <header class="hero" id="hero">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="hero-vig" aria-hidden="true"></div>

        <svg class="diya left" viewBox="0 0 60 70" aria-hidden="true">
            <g class="flame">
                <path d="M30 4 C36 16 40 22 30 34 C20 22 24 16 30 4Z" fill="#FFD166" />
                <path d="M30 12 C33 19 35 23 30 30 C25 23 27 19 30 12Z" fill="#FF8F1F" />
            </g>
            <path d="M8 40 Q30 60 52 40 Q44 52 30 52 Q16 52 8 40Z" fill="#D9A441" />
            <path d="M8 40 Q30 48 52 40" fill="none" stroke="#7a4a12" stroke-width="2" />
        </svg>
        <svg class="diya right" viewBox="0 0 60 70" aria-hidden="true">
            <g class="flame">
                <path d="M30 4 C36 16 40 22 30 34 C20 22 24 16 30 4Z" fill="#FFD166" />
                <path d="M30 12 C33 19 35 23 30 30 C25 23 27 19 30 12Z" fill="#FF8F1F" />
            </g>
            <path d="M8 40 Q30 60 52 40 Q44 52 30 52 Q16 52 8 40Z" fill="#D9A441" />
            <path d="M8 40 Q30 48 52 40" fill="none" stroke="#7a4a12" stroke-width="2" />
        </svg>

        <div class="hero-core">
            <div class="ring ring-a" aria-hidden="true">
                <svg viewBox="0 0 400 400">
                    <circle cx="200" cy="200" r="194" fill="none" stroke="#D9A441" stroke-width="1.4"
                        stroke-dasharray="2 9" />
                    <circle cx="200" cy="200" r="178" fill="none" stroke="#F0C060" stroke-width="1" opacity=".6" />
                </svg>
            </div>
            <div class="ring ring-b" aria-hidden="true">
                <svg viewBox="0 0 400 400">
                    <circle cx="200" cy="200" r="190" fill="none" stroke="#F5A623" stroke-width="2"
                        stroke-dasharray="1 14" stroke-linecap="round" />
                </svg>
            </div>
            <svg class="lotus top" viewBox="0 0 64 64" aria-hidden="true">
                <path fill="currentColor"
                    d="M32 4c4 8 4 16 0 24-4-8-4-16 0-24zM14 14c8 4 12 12 12 20-8-2-14-8-12-20zM50 14c2 12-4 18-12 20 0-8 4-16 12-20zM6 30c10 0 18 4 22 12-10 2-18-2-22-12zM58 30c-4 10-12 14-22 12 4-8 12-12 22-12zM32 30c6 6 8 14 6 24h-12c-2-10 0-18 6-24z" />
            </svg>
            <svg class="lotus bot" viewBox="0 0 64 64" aria-hidden="true">
                <path fill="currentColor"
                    d="M32 4c4 8 4 16 0 24-4-8-4-16 0-24zM14 14c8 4 12 12 12 20-8-2-14-8-12-20zM50 14c2 12-4 18-12 20 0-8 4-16 12-20zM6 30c10 0 18 4 22 12-10 2-18-2-22-12zM58 30c-4 10-12 14-22 12 4-8 12-12 22-12zM32 30c6 6 8 14 6 24h-12c-2-10 0-18 6-24z" />
            </svg>

            <div class="core-inner">
                <p class="blessing">॥ वक्रतुण्ड महाकाय सूर्यकोटि समप्रभ ॥<br>॥ निर्विघ्नं कुरु मे देव सर्वकार्येषु
                    सर्वदा ॥</p>
                <h1 class="names"><span>{{ $invitation ? substr($invitation->bride_name, 0, 1) : 'A' }}</span><span class="amp">✦</span><span>{{ $invitation ? substr($invitation->groom_name, 0, 1) : 'V' }}</span></h1>
                @if($invitation && !empty($invitation->wedding_date))
                <div class="saveline"><span></span>Save the Date<span></span></div>
                <p class="date">
                    {{ \Carbon\Carbon::parse($invitation->wedding_date)->format('d F Y') }}
                    @if($invitation->wedding_city || $invitation->wedding_state)
                        · {{ $invitation->wedding_city }}{{ $invitation->wedding_city && $invitation->wedding_state ? ', ' : '' }}{{ $invitation->wedding_state }}
                    @endif
                </p>
                @endif
            </div>
        </div>

        <button class="scroll-cue" id="scrollCue" aria-label="Scroll to the invitation">Keep scrolling<div class="dot">
            </div></button>
    </header>

    <div class="marquee" aria-hidden="true">
        <div class="marquee__track">
            <span>शुभ विवाह</span><span>{{ $invitation->bride_name ?? 'Aanya' }} ♥ {{ $invitation->groom_name ?? 'Vihaan' }}</span>
            @if($invitation && !empty($invitation->wedding_date))
                <span>Save the Date</span><span>{{ \Carbon\Carbon::parse($invitation->wedding_date)->format('d M Y') }}</span>
            @endif
            <span>{{ $invitation->wedding_city ?? 'City' }}</span><span>सात फेरे</span>
            <span>शुभ विवाह</span><span>{{ $invitation->bride_name ?? 'Aanya' }} ♥ {{ $invitation->groom_name ?? 'Vihaan' }}</span>
            @if($invitation && !empty($invitation->wedding_date))
                <span>Save the Date</span><span>{{ \Carbon\Carbon::parse($invitation->wedding_date)->format('d M Y') }}</span>
            @endif
            <span>{{ $invitation->wedding_city ?? 'City' }}</span><span>सात फेरे</span>
        </div>
    </div>

    <section class="cream" id="invitation">
        <div class="wrap">
            <div class="sec-head rv">
                <p class="kicker">Shubh Aamantran</p>
                <h2 class="sec-title">You Are Warmly Invited</h2>
                <p class="sec-hi">आप सभी सादर आमंत्रित हैं</p>
                <div class="divider"><i></i><svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2c2 4 2 8 0 12-2-4-2-8 0-12zM3 8c4 2 6 6 6 10-4-1-7-4-6-10zM21 8c1 6-2 9-6 10 0-4 2-8 6-10zM12 12c3 3 4 7 3 12h-6c-1-5 0-9 3-12z" />
                    </svg><i></i></div>
            </div>

            <article class="letter rv" style="--d:.1s">
                <svg class="corner tl" viewBox="0 0 60 60" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 30 C4 14 14 4 30 4 M4 44 C4 22 22 4 44 4 M10 30 C10 18 18 10 30 10" />
                    <circle cx="30" cy="30" r="3" fill="currentColor" />
                </svg>
                <svg class="corner tr" viewBox="0 0 60 60" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 30 C4 14 14 4 30 4 M4 44 C4 22 22 4 44 4 M10 30 C10 18 18 10 30 10" />
                    <circle cx="30" cy="30" r="3" fill="currentColor" />
                </svg>
                <svg class="corner bl" viewBox="0 0 60 60" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 30 C4 14 14 4 30 4 M4 44 C4 22 22 4 44 4 M10 30 C10 18 18 10 30 10" />
                    <circle cx="30" cy="30" r="3" fill="currentColor" />
                </svg>
                <svg class="corner br" viewBox="0 0 60 60" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 30 C4 14 14 4 30 4 M4 44 C4 22 22 4 44 4 M10 30 C10 18 18 10 30 10" />
                    <circle cx="30" cy="30" r="3" fill="currentColor" />
                </svg>

                <div class="om">ॐ</div>
                <h3>Dear friends &amp; family,</h3>
                <p>With the blessings of our elders and the grace of Shree Ganesha, we joyfully invite you to celebrate
                    the wedding of our daughter <strong>{{ $invitation->bride_name ?? 'Aanya' }}</strong> and our son <strong>{{ $invitation->groom_name ?? 'Vihaan' }}</strong>.</p>
                <p>Two families, one table, and a week of marigolds, music and memories. Your presence
                    would mean the world to us — please come hungry, come dressed to dance, and come ready to bless the
                    couple as they begin their life together.</p>
                <p class="sign">Sneh sahit, with love</p>
                <p class="hosts">
                    {{ $invitation->bride_father_name ?? 'Shri' }} &amp; {{ $invitation->bride_mother_name ?? 'Smt. Sharma' }} &nbsp;·&nbsp;
                    {{ $invitation->groom_father_name ?? 'Shri' }} &amp; {{ $invitation->groom_mother_name ?? 'Smt. Malhotra' }}
                </p>
            </article>
        </div>
    </section>

    @if($invitation && !empty($invitation->wedding_date))
    <section class="dark" id="muhurat">
        <div class="pat" aria-hidden="true"></div>
        <div class="wrap">
            <div class="sec-head rv">
                <p class="kicker">Counting Down</p>
                <h2 class="sec-title">Until the Pheras</h2>
                <p class="sec-hi">सात फेरों तक</p>
                <div class="divider"><i></i><svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2c2 4 2 8 0 12-2-4-2-8 0-12zM3 8c4 2 6 6 6 10-4-1-7-4-6-10zM21 8c1 6-2 9-6 10 0-4 2-8 6-10zM12 12c3 3 4 7 3 12h-6c-1-5 0-9 3-12z" />
                    </svg><i></i></div>
            </div>

            <div class="count rv" id="count" aria-live="polite">
                <div class="unit">
                    <div class="num" data-u="d">000</div>
                    <div class="lab">Days</div>
                </div>
                <div class="sep">:</div>
                <div class="unit">
                    <div class="num" data-u="h">00</div>
                    <div class="lab">Hours</div>
                </div>
                <div class="sep">:</div>
                <div class="unit">
                    <div class="num" data-u="m">00</div>
                    <div class="lab">Minutes</div>
                </div>
                <div class="sep">:</div>
                <div class="unit">
                    <div class="num" data-u="s">00</div>
                    <div class="lab">Seconds</div>
                </div>
            </div>

            <p class="sec-lead rv" style="--d:.1s;margin-bottom:2rem">Every second brings us closer to the mandap. ✦
                Scratch the card to reveal the auspicious hour.</p>

            <div class="scratch-wrap rv" style="--d:.2s">
                <div class="scratch-card">
                    <p class="tag">Shubh Muhurat · शुभ मुहूर्त</p>
                    <p class="fn">Vivaah · {{ $invitation && $invitation->wedding_date ? \Carbon\Carbon::parse($invitation->wedding_date)->format('l') : 'Day' }}</p>
                    <div class="row">
                        <div><b>{{ $invitation && $invitation->wedding_date ? \Carbon\Carbon::parse($invitation->wedding_date)->format('d M Y') : 'Date TBD' }}</b><small>Date</small></div>
                        <div><b>{{ $invitation->wedding_time ?? 'Time TBD' }}</b><small>Muhurat</small></div>
                        <div><b>{{ $invitation->wedding_location ?? 'Venue TBD' }}</b><small>{{ $invitation->wedding_city ?? 'City' }}</small></div>
                    </div>
                </div>
                <canvas id="scratch"></canvas>
                <div class="scratch-hint" id="scratchHint">✦ Scratch to reveal ✦</div>
            </div>
        </div>
    </section>
    @endif

    <section class="cream-2" id="functions">
        <div class="wrap">
            <div class="sec-head rv">
                <p class="kicker">Five Days of Celebration</p>
                <h2 class="sec-title">The Wedding Functions</h2>
                <p class="sec-hi">विवाह कार्यक्रम</p>
                <div class="divider"><i></i><svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2c2 4 2 8 0 12-2-4-2-8 0-12zM3 8c4 2 6 6 6 10-4-1-7-4-6-10zM21 8c1 6-2 9-6 10 0-4 2-8 6-10zM12 12c3 3 4 7 3 12h-6c-1-5 0-9 3-12z" />
                    </svg><i></i></div>
            </div>

            <div class="timeline">
                @forelse($ceremonies as $index => $ceremony)
                    @php
                        $colors = ['#F5A623', '#14655A', '#C21E2A', '#D9A441', '#C97B1E'];
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="ev rv" style="--ac:{{ $color }}">
                        <div class="badge">{{ $index + 1 }}</div>
                        <div class="ev-card">
                            <p class="ev-date">
                                {{ \Carbon\Carbon::parse($ceremony->ceramony_date)->format('l · j F') }}
                                @if($ceremony->ceramony_time) - {{ \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') }} @endif
                            </p>
                            <h3 class="ev-title">{{ $ceremony->ceramony_name }}</h3>
                            <p class="ev-desc">Join us to celebrate the {{ $ceremony->ceramony_name }} ceremony.</p>
                            <div class="ev-meta">
                                <span class="chip">📍 {{ $ceremony->venue ? $ceremony->venue->venue_name : 'Venue TBD' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #64748b; font-style: italic;">No ceremonies added yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dark-2" id="gallery">
        <div class="pat" aria-hidden="true"></div>
        <div class="wrap">
            <div class="sec-head rv">
                <p class="kicker">Yaadein</p>
                <h2 class="sec-title">A Few of Our Favourites</h2>
                <p class="sec-hi">यादें</p>
                <div class="divider"><i></i><svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2c2 4 2 8 0 12-2-4-2-8 0-12zM3 8c4 2 6 6 6 10-4-1-7-4-6-10zM21 8c1 6-2 9-6 10 0-4 2-8 6-10zM12 12c3 3 4 7 3 12h-6c-1-5 0-9 3-12z" />
                    </svg><i></i></div>
            </div>

            <div class="gallery rv" data-lenis-prevent>
                @php
                    $allPictures = [];
                    foreach($pictures as $pic) {
                        $allPictures[] = ['url' => $pic->picture, 'caption' => ''];
                    }
                    foreach($albums as $album) {
                        if(is_array($album->album_images)) {
                            foreach($album->album_images as $img) {
                                $allPictures[] = ['url' => $img, 'caption' => $album->album_name];
                            }
                        }
                    }
                @endphp
                
                @forelse($allPictures as $picData)
                    <figure class="gcard"><img
                            src="{{ asset($picData['url']) }}"
                            alt="Gallery Image" loading="lazy">
                        @if($picData['caption'])
                            <figcaption>{{ $picData['caption'] }}</figcaption>
                        @endif
                    </figure>
                @empty
                    <div style="text-align: center; color: #64748b; width: 100%; font-style: italic;">No pictures added yet.</div>
                @endforelse
            </div>
            <p class="gal-hint">← swipe the memories →</p>
        </div>
    </section>

    <section class="cream" id="venues">
        <div class="wrap">
            <div class="sec-head rv">
                <p class="kicker">Where to Find Us</p>
                <h2 class="sec-title">The Venues</h2>
                <p class="sec-hi">स्थान</p>
                <div class="divider"><i></i><svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2c2 4 2 8 0 12-2-4-2-8 0-12zM3 8c4 2 6 6 6 10-4-1-7-4-6-10zM21 8c1 6-2 9-6 10 0-4 2-8 6-10zM12 12c3 3 4 7 3 12h-6c-1-5 0-9 3-12z" />
                    </svg><i></i></div>
            </div>

            <div class="vgrid">
                @forelse($ceremonies as $index => $ceremony)
                    <article class="vcard rv" style="--d:.{{ $index % 5 }}s">
                        <div class="vicon"><svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.4">
                                <path d="M12 38 Q32 56 52 38 Q44 50 32 50 Q20 50 12 38Z" fill="currentColor"
                                    stroke="none" />
                                <path d="M32 8 C38 20 42 26 32 36 C22 26 26 20 32 8Z" fill="currentColor" stroke="none" />
                            </svg></div>
                        <div class="vbody">
                            <p class="vtag">{{ $ceremony->ceramony_name }}</p>
                            <h3 class="vname">
                                @if($ceremony->venue && $ceremony->venue->location_map)
                                    <a href="{{ $ceremony->venue->location_map }}" target="_blank" rel="noopener" style="text-decoration: underline;">{{ $ceremony->venue->venue_name }}</a>
                                @else
                                    {{ $ceremony->venue ? $ceremony->venue->venue_name : 'Venue TBD' }}
                                @endif
                            </h3>
                            <p class="vaddr" style="margin-bottom: 0.2rem;">
                                @if($ceremony->venue)
                                    @if($ceremony->venue->venue_address){{ $ceremony->venue->venue_address }}<br>@endif
                                    @if($ceremony->venue->area_name){{ $ceremony->venue->area_name }}, @endif
                                    @if($ceremony->venue->circle){{ $ceremony->venue->circle }}, @endif
                                    @if($ceremony->venue->wedding_location){{ $ceremony->venue->wedding_location }}<br>@endif
                                    @if($ceremony->venue->district){{ $ceremony->venue->district }}, @endif
                                    @if($ceremony->venue->state){{ $ceremony->venue->state }}, @endif
                                    @if($ceremony->venue->country){{ $ceremony->venue->country }} @endif
                                    @if($ceremony->venue->pincode)- {{ $ceremony->venue->pincode }}@endif
                                @endif
                            </p>
                            <p class="vaddr">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($ceremony->ceramony_date)->format('d F Y') }}<br>
                                @if($ceremony->ceramony_time)<strong>Time:</strong> {{ \Carbon\Carbon::parse($ceremony->ceramony_time)->format('h:i A') }}@endif
                            </p>
                            <p class="vnote">{{ $ceremony->description ?? '' }}</p>
                            @if($ceremony->venue && $ceremony->venue->location_map)
                            <a class="vbtn" target="_blank" rel="noopener"
                                href="{{ $ceremony->venue->location_map }}">Get
                                Directions →</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; color: #64748b; width: 100%; font-style: italic;">No venues added yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- <section class="dark" id="rsvp">
        <div class="pat" aria-hidden="true"></div>
        <div class="wrap">
            <div class="sec-head rv">
                <p class="kicker">Kindly Reply</p>
                <h2 class="sec-title">Aap Aa Rahe Hain?</h2>
                <p class="sec-hi">कृपया उत्तर दीजिए</p>
                <div class="divider"><i></i><svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2c2 4 2 8 0 12-2-4-2-8 0-12zM3 8c4 2 6 6 6 10-4-1-7-4-6-10zM21 8c1 6-2 9-6 10 0-4 2-8 6-10zM12 12c3 3 4 7 3 12h-6c-1-5 0-9 3-12z" />
                    </svg><i></i></div>
                <p class="sec-lead">Please reply by the tenth of January so we can arrange your rooms and thaali.</p>
            </div>

            <div class="rsvp-card rv" style="--d:.1s" id="rsvpCard">
                <form id="rsvpForm" method="POST" action="{{ isset($guest) ? route('guest.rsvp.update', $guest->uuid) : '#' }}" novalidate>
                    @csrf
                    <div class="field">
                        <label for="rname">Your Name</label>
                        <input id="rname" name="name" type="text" autocomplete="name" placeholder="e.g. Priya Kapoor"
                            value="{{ isset($guest) ? $guest->guest_name : '' }}" required>
                        <p class="err" data-for="rname">Please tell us your name.</p>
                    </div>
                    <div class="field">
                        <label for="remail">Email</label>
                        <input id="remail" name="email" type="email" autocomplete="email" placeholder="you@example.com"
                            value="{{ isset($guest) ? $guest->guest_email : '' }}" required>
                        <p class="err" data-for="remail">A valid email helps us reach you.</p>
                    </div>
                    <div class="field">
                        <label>Your Reply</label>
                        <div class="pills">
                            <label class="pill"><input type="radio" name="rsvp_status" value="accepted" {{ (isset($guest) && $guest->rsvp_status == 'accepted') ? 'checked' : 'checked' }}><span>🌸
                                    Joyfully accepts</span></label>
                            <label class="pill"><input type="radio" name="rsvp_status" value="declined" {{ (isset($guest) && $guest->rsvp_status == 'declined') ? 'checked' : '' }}><span>🙏 Regretfully
                                    declines</span></label>
                        </div>
                    </div>
                    <div class="field">
                        <label for="rsize">Party Size</label>
                        <select id="rsize" name="size">
                            <option>Just me</option>
                            <option>Two of us</option>
                            <option>Three</option>
                            <option>Four</option>
                            <option>Five or more</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Which Functions</label>
                        <div class="pills">
                            <label class="pill"><input type="checkbox" name="fn" value="all" checked><span>All
                                    five</span></label>
                            <label class="pill"><input type="checkbox" name="fn" value="haldi"><span>Haldi &amp;
                                    Mehendi</span></label>
                            <label class="pill"><input type="checkbox" name="fn"
                                    value="sangeet"><span>Sangeet</span></label>
                            <label class="pill"><input type="checkbox" name="fn" value="vivaah"><span>Baraat &amp;
                                    Vivaah</span></label>
                            <label class="pill"><input type="checkbox" name="fn"
                                    value="reception"><span>Reception</span></label>
                        </div>
                    </div>
                    <div class="field">
                        <label for="rnote">A Note for Us</label>
                        <textarea id="rnote" name="note"
                            placeholder="Dietary needs, a song request, a blessing…"></textarea>
                    </div>
                    <button class="submit" type="submit">Bhejein · Send Reply</button>
                </form>

                <div class="success" id="rsvpSuccess">
                    <svg class="check" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="34" />
                        <path d="M24 42 l11 11 l22 -26" />
                    </svg>
                    <p class="hi">धन्यवाद</p>
                    <h3>Dhanyavaad!</h3>
                    <p>Your reply is safely with us. We cannot wait to see you under the marigolds.</p>
                    <button class="ghost" id="rsvpReset" type="button">Send Another Reply</button>
                </div>
            </div>
        </div>
    </section> -->

    <footer>
        <div class="fmono">{{ $invitation->bride_name ?? 'Aanya' }} &amp; {{ $invitation->groom_name ?? 'Vihaan' }}</div>
        <p class="fline">With the blessings of Shree Ganesha</p>
        <p class="hash">#{{ $invitation->bride_name ?? 'Aanya' }}Weds{{ $invitation->groom_name ?? 'Vihaan' }} &nbsp;·&nbsp; #{{ $invitation->groom_name ?? 'Vihaan' }}Ki{{ $invitation->bride_name ?? 'Aanya' }}</p>
        <svg class="fdiya" viewBox="0 0 60 70" aria-hidden="true">
            <g class="flame">
                <path d="M30 4 C36 16 40 22 30 34 C20 22 24 16 30 4Z" fill="#FFD166" />
                <path d="M30 12 C33 19 35 23 30 30 C25 23 27 19 30 12Z" fill="#FF8F1F" />
            </g>
            <path d="M8 40 Q30 60 52 40 Q44 52 30 52 Q16 52 8 40Z" fill="#D9A441" />
        </svg>
        <!-- <p class="small">Crafted with ♥ in {{ $invitation->wedding_city ?? 'the Pink City' }} · {{ $invitation && $invitation->wedding_date ? \Carbon\Carbon::parse($invitation->wedding_date)->format('d M Y') : 'Date TBD' }}</p> -->
    </footer>

    <button id="top" aria-label="Back to top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 19V5M5 12l7-7 7 7" />
        </svg></button>

    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js"></script>
    <script>
        (function () {
            "use strict";
            const $ = (s, r = document) => r.querySelector(s);
            const $$ = (s, r = document) => [...r.querySelectorAll(s)];
            const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

            let lenis = null;
            if (window.Lenis && !reduce) {
                lenis = new Lenis({
                    duration: 1.25,
                    easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                    smoothWheel: true,
                    wheelMultiplier: 1,
                    touchMultiplier: 1.6
                });
                (function raf(time) { lenis.raf(time); requestAnimationFrame(raf); })(performance.now());
            } else {
                document.documentElement.style.scrollBehavior = 'smooth';
            }
            function glideTo(target, offset) {
                if (lenis) lenis.scrollTo(target, { offset: offset || -72, duration: 1.5 });
                else if (typeof target === 'string') $(target).scrollIntoView({ behavior: 'smooth' });
                else window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            document.addEventListener('click', e => {
                const a = e.target.closest('a[href^="#"]');
                if (!a) return;
                const id = a.getAttribute('href');
                if (id.length < 2) return;
                const el = document.querySelector(id);
                if (!el) return;
                e.preventDefault();
                glideTo(el, -72);
            });

            const Petals = (function () {
                const cv = $('#petals'), ctx = cv.getContext('2d');
                const cols = ['#F5A623', '#FFB428', '#E8890C', '#FFD166', '#C21E2A', '#F0C060'];
                let W = 0, H = 0, pets = [], bursts = [], on = false;
                function resize() {
                    const dpr = Math.min(window.devicePixelRatio || 1, 2);
                    W = window.innerWidth; H = window.innerHeight;
                    cv.width = W * dpr; cv.height = H * dpr;
                    cv.style.width = W + 'px'; cv.style.height = H + 'px';
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                }
                function make(init) {
                    return {
                        x: Math.random() * W, y: init ? Math.random() * H : -20 - Math.random() * H,
                        r: 6 + Math.random() * 8, rot: Math.random() * 6.28, vr: (Math.random() - 0.5) * 0.04,
                        vy: 0.5 + Math.random() * 1.0, sw: Math.random() * 6.28, sws: 0.01 + Math.random() * 0.02,
                        swa: 0.5 + Math.random() * 1.1, col: cols[(Math.random() * cols.length) | 0], op: 0.5 + Math.random() * 0.4
                    };
                }
                function petal(p, a) {
                    ctx.save(); ctx.globalAlpha = a; ctx.translate(p.x, p.y); ctx.rotate(p.rot);
                    ctx.fillStyle = p.col; ctx.beginPath();
                    ctx.moveTo(0, -p.r);
                    ctx.bezierCurveTo(p.r * 0.7, -p.r * 0.6, p.r * 0.7, p.r * 0.6, 0, p.r);
                    ctx.bezierCurveTo(-p.r * 0.7, p.r * 0.6, -p.r * 0.7, -p.r * 0.6, 0, -p.r);
                    ctx.fill();
                    ctx.globalAlpha = a * 0.45; ctx.fillStyle = 'rgba(255,255,255,.6)';
                    ctx.beginPath(); ctx.ellipse(-p.r * 0.15, -p.r * 0.2, p.r * 0.16, p.r * 0.4, 0, 0, 6.28); ctx.fill();
                    ctx.restore();
                }
                function loop() {
                    ctx.clearRect(0, 0, W, H);
                    for (const p of pets) {
                        p.sw += p.sws; p.x += Math.sin(p.sw) * p.swa; p.y += p.vy; p.rot += p.vr;
                        if (p.y > H + 20) Object.assign(p, make(false)); petal(p, p.op);
                    }
                    for (let i = bursts.length - 1; i >= 0; i--) {
                        const b = bursts[i];
                        b.x += b.vx; b.y += b.vy; b.vy += 0.07; b.vx *= 0.99; b.rot += b.vr; b.life -= 0.013;
                        if (b.life <= 0) { bursts.splice(i, 1); continue; } petal(b, Math.max(0, b.life));
                    }
                    if (on) requestAnimationFrame(loop);
                }
                return {
                    start() { resize(); if (reduce) return; if (!pets.length) { const n = W < 640 ? 22 : 38; for (let i = 0; i < n; i++) pets.push(make(true)); } on = true; loop(); },
                    burst(x, y, n) {
                        if (reduce) return; for (let i = 0; i < n; i++) {
                            const a = Math.random() * 6.28, s = 2 + Math.random() * 6;
                            bursts.push({
                                x, y, vx: Math.cos(a) * s, vy: Math.sin(a) * s - 2.5, rot: Math.random() * 6.28, vr: (Math.random() - 0.5) * 0.3,
                                r: 5 + Math.random() * 7, col: cols[(Math.random() * cols.length) | 0], life: 1
                            });
                        }
                    },
                    onResize() { resize(); }
                };
            })();
            window.addEventListener('resize', () => Petals.onResize());

            const gate = $('#gate'), seal = $('#sealBtn');
            function openGate() {
                if (gate.classList.contains('open')) return;
                gate.classList.add('open');
                document.body.classList.add('begun');
                Petals.start();
                Petals.burst(window.innerWidth / 2, window.innerHeight / 2, 70);
                setTimeout(() => { gate.style.display = 'none'; }, 1300);
            }
            seal.addEventListener('click', openGate);

            $('#scrollCue').addEventListener('click', () => glideTo('#invitation', -60));

            const io = new IntersectionObserver((es) => {
                es.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
            }, { threshold: 0.16 });
            $$('.rv').forEach(el => io.observe(el));

            const target = new Date('2027-02-11T20:15:00+05:30').getTime();
            const nums = { d: $('[data-u=d]'), h: $('[data-u=h]'), m: $('[data-u=m]'), s: $('[data-u=s]') };
            const last = {};
            function pad(n, l) { return String(n).padStart(l, '0'); }
            function tick() {
                let diff = Math.max(0, target - Date.now());
                const d = Math.floor(diff / 86400000); diff -= d * 86400000;
                const h = Math.floor(diff / 3600000); diff -= h * 3600000;
                const m = Math.floor(diff / 60000); diff -= m * 60000;
                const s = Math.floor(diff / 1000);
                const vals = { d: pad(d, 3), h: pad(h, 2), m: pad(m, 2), s: pad(s, 2) };
                for (const k in vals) {
                    if (vals[k] !== last[k]) {
                        last[k] = vals[k]; const el = nums[k]; el.textContent = vals[k];
                        el.classList.remove('pop'); void el.offsetWidth; el.classList.add('pop');
                    }
                }
            }
            tick(); setInterval(tick, 1000);

            (function () {
                const cv = $('#scratch'), c = cv.getContext('2d'), hint = $('#scratchHint');
                let drawn = false, strokes = 0, cleared = false;
                function size() {
                    const r = cv.parentElement.getBoundingClientRect();
                    const dpr = Math.min(window.devicePixelRatio || 1, 2);
                    cv.width = r.width * dpr; cv.height = r.height * dpr;
                    c.setTransform(dpr, 0, 0, dpr, 0, 0);
                    paint(r.width, r.height);
                }
                function paint(w, h) {
                    const g = c.createLinearGradient(0, 0, w, h);
                    g.addColorStop(0, '#caa23f'); g.addColorStop(.5, '#f0c060'); g.addColorStop(1, '#b9892f');
                    c.globalCompositeOperation = 'source-over';
                    c.fillStyle = g; c.fillRect(0, 0, w, h);
                    c.save(); c.translate(w / 2, h / 2); c.rotate(-0.16);
                    c.fillStyle = 'rgba(74,14,18,.38)'; c.font = '600 20px "Rozha One", serif'; c.textAlign = 'center';
                    for (let y = -h; y < h; y += 32) { for (let x = -w; x < w; x += 150) { c.fillText('✦ शुभ मुहूर्त ✦', x, y); } }
                    c.restore();
                    c.strokeStyle = 'rgba(74,14,18,.5)'; c.lineWidth = 4; c.strokeRect(2, 2, w - 4, h - 4);
                    drawn = true;
                }
                function pos(e) { const r = cv.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; }
                function erase(e) {
                    if (cleared || !drawn) return;
                    const p = pos(e); c.globalCompositeOperation = 'destination-out';
                    c.beginPath(); c.arc(p.x, p.y, 24, 0, 6.28); c.fill();
                    c.globalCompositeOperation = 'source-over';
                    if (!hint.classList.contains('hide')) hint.classList.add('hide');
                    strokes++;
                    if (strokes % 14 === 0) check();
                }
                function check() {
                    const w = cv.width, h = cv.height; let data;
                    try { data = c.getImageData(0, 0, w, h).data; } catch (_) { return; }
                    let clear = 0, total = 0;
                    for (let i = 3; i < data.length; i += 80) { total++; if (data[i] < 60) clear++; }
                    if (clear / total > 0.5) { cleared = true; cv.classList.add('done'); }
                }
                let down = false;
                cv.addEventListener('pointerdown', e => { down = true; cv.setPointerCapture(e.pointerId); erase(e); });
                cv.addEventListener('pointermove', e => { if (down) erase(e); });
                cv.addEventListener('pointerup', () => { down = false; });
                cv.addEventListener('pointercancel', () => { down = false; });
                const obs = new IntersectionObserver((es, o) => { es.forEach(e => { if (e.isIntersecting) { size(); o.disconnect(); } }); }, { threshold: 0.1 });
                obs.observe(cv.parentElement);
                window.addEventListener('resize', () => { if (!cleared) size(); });
            })();

            const nav = $('#nav'), prog = $('#progress'), topBtn = $('#top');
            let ticking = false;
            function onScroll() {
                if (ticking) return; ticking = true;
                requestAnimationFrame(() => {
                    const y = window.scrollY, dh = document.documentElement.scrollHeight - window.innerHeight;
                    prog.style.width = (dh > 0 ? (y / dh * 100) : 0) + '%';
                    nav.classList.toggle('show', y > window.innerHeight * 0.55);
                    topBtn.classList.toggle('show', y > 700);
                    ticking = false;
                });
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            topBtn.addEventListener('click', () => glideTo(0));

            const form = $('#rsvpForm'), success = $('#rsvpSuccess'), card = $('#rsvpCard');
            function showErr(id, show) { const e = $('.err[data-for="' + id + '"]'); if (e) e.classList.toggle('show', show); }
            if (form) {
                form.addEventListener('submit', e => {
                    const name = $('#rname'), email = $('#remail');
                    let ok = true;
                    if (!name.value.trim()) { showErr('rname', true); ok = false; } else showErr('rname', false);
                    const em = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!em.test(email.value.trim())) { showErr('remail', true); ok = false; } else showErr('remail', false);
                    if (!ok) {
                        e.preventDefault();
                    }
                });
            }
            if ($('#rsvpReset')) {
                $('#rsvpReset').addEventListener('click', () => {
                    success.classList.remove('show'); form.reset(); form.style.display = 'block';
                });
            }
        })();
    </script>
</body>

</html>