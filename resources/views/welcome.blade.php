<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Portal | Plan & Celebrate</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --pink-primary: #d63384;
            --pink-dark: #b02663;
            --pink-light: #fff5f8;
            --gold: #d4af37;
            --gold-light: #f9f6e8;
            --dark: #2d3436;
            --gray: #636e72;
            --bg-warm: #fdfbf9;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-warm);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .brand {
            font-family: 'Great Vibes', cursive;
            font-size: 2.2rem;
            color: var(--pink-primary);
            text-decoration: none;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-outline {
            border: 2px solid var(--pink-primary);
            color: var(--pink-primary);
        }

        .btn-outline:hover {
            background: var(--pink-primary);
            color: #fff;
        }

        .btn-filled {
            background: linear-gradient(135deg, var(--gold), #b5952f);
            color: #fff;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-filled:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 20px 60px;
            background: radial-gradient(circle at center, var(--pink-light) 0%, var(--bg-warm) 100%);
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png');
            opacity: 0.6;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4.5rem;
            color: var(--dark);
            margin-bottom: 20px;
            line-height: 1.1;
        }

        .hero-title span {
            color: var(--pink-primary);
            font-family: 'Great Vibes', cursive;
            font-size: 5.5rem;
            display: block;
            margin-top: 10px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 40px;
        }

        /* Sections */
        .section {
            padding: 100px 50px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            text-align: center;
            margin-bottom: 60px;
            color: var(--dark);
        }

        /* Cards Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            border: 1px solid rgba(214, 51, 132, 0.1);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(214, 51, 132, 0.15);
            border-color: var(--pink-primary);
        }

        .feature-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: linear-gradient(90deg, var(--gold), var(--pink-primary));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover::after {
            opacity: 1;
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--gold);
            margin-bottom: 20px;
        }

        .feature-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }

        .feature-desc {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .split-section {
            background: var(--dark);
            color: #fff;
            padding: 100px 50px;
            position: relative;
        }
        
        .split-section .section-title {
            color: #fff;
        }

        .split-section .feature-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            color: #fff;
        }

        .split-section .feature-desc {
            color: #ccc;
        }
        
        .split-section .feature-card:hover {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        /* Footer */
        footer {
            background: #1a1e21;
            color: #fff;
            text-align: center;
            padding: 30px;
            font-family: 'Playfair Display', serif;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 3rem; }
            .hero-title span { font-size: 4rem; }
            nav { padding: 15px 20px; }
            .section { padding: 60px 20px; }
            .nav-links { gap: 10px; }
            .btn { padding: 8px 15px; font-size: 0.85rem; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="" class="brand">Wedding Portal</a>
        <div class="nav-links">
            <a href="{{ route('guest.login') }}" class="btn btn-outline">Guest Login</a>
            <a href="{{ route('host.login') }}" class="btn btn-filled">Host Login</a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content" data-aos="zoom-in" data-aos-duration="1000">
            <h1 class="hero-title">The Ultimate Wedding Experience <br><span>Plan & Celebrate</span></h1>
            <p class="hero-subtitle">A seamless platform connecting Hosts with their Guests. Craft beautiful invitations, manage events, and share memories effortlessly.</p>
            <div class="hero-buttons">
                <a href="#host-features" class="btn btn-outline" style="margin-right: 15px; padding: 12px 30px;">For Hosts</a>
                <a href="#guest-features" class="btn btn-filled" style="padding: 12px 30px;">For Guests</a>
            </div>
        </div>
    </section>

    <!-- Host Features -->
    <section id="host-features" class="section">
        <h2 class="section-title" data-aos="fade-up">Everything a Host Needs</h2>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-chart-pie feature-icon"></i>
                <h3 class="feature-title">Host Dashboard</h3>
                <p class="feature-desc">Monitor your entire wedding setup from a centralized dashboard. Keep track of RSVPs, guests, and events instantly.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-paint-brush feature-icon"></i>
                <h3 class="feature-title">Design & Customize</h3>
                <p class="feature-desc">Select beautiful background images and freely align text for your Ceremonies, Invitations, and Save the Dates.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-users feature-icon"></i>
                <h3 class="feature-title">Guest List Management</h3>
                <p class="feature-desc">Add guests and send them Save the Dates, RSVPs, and automated Reminder notifications with ease.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-images feature-icon"></i>
                <h3 class="feature-title">Family & Gallery</h3>
                <p class="feature-desc">Add your host family details and manage stunning photo and video galleries to share with your guests.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <i class="fas fa-file-invoice feature-icon"></i>
                <h3 class="feature-title">Detailed Reports</h3>
                <p class="feature-desc">Generate comprehensive reports on guest attendance, RSVP statuses, and overall event planning.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                <i class="fas fa-envelope-open-text feature-icon"></i>
                <h3 class="feature-title">Multi-Channel Alerts</h3>
                <p class="feature-desc">Ensure your guests never miss an update by dispatching notifications across multiple channels simultaneously.</p>
            </div>
        </div>
    </section>

    <!-- Guest Features -->
    <section id="guest-features" class="section split-section">
        <h2 class="section-title" data-aos="fade-up">A Premium Guest Journey</h2>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-bell feature-icon" style="color: var(--pink-primary);"></i>
                <h3 class="feature-title">Instant Notifications</h3>
                <p class="feature-desc">Receive Save the Dates, RSVPs, and Reminders directly via Email, SMS, and WhatsApp.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-reply-all feature-icon" style="color: var(--pink-primary);"></i>
                <h3 class="feature-title">One-Click RSVPs</h3>
                <p class="feature-desc">Easily accept or reject event invitations directly from your device with a single tap.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-film feature-icon" style="color: var(--pink-primary);"></i>
                <h3 class="feature-title">Immersive Animations</h3>
                <p class="feature-desc">Experience the beautiful, dynamic animations and customized designs the host created for ceremonies and invitations.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-camera-retro feature-icon" style="color: var(--pink-primary);"></i>
                <h3 class="feature-title">Digital Gallery</h3>
                <p class="feature-desc">Access high-quality photo and video galleries to view and relive the beautiful moments of the wedding.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <i class="fas fa-user-circle feature-icon" style="color: var(--pink-primary);"></i>
                <h3 class="feature-title">Guest Profile</h3>
                <p class="feature-desc">Easily update your personal details and contact information seamlessly through your private guest profile.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                <i class="fas fa-user-plus feature-icon" style="color: var(--pink-primary);"></i>
                <h3 class="feature-title">Add Family Members</h3>
                <p class="feature-desc">Easily add and manage the details of your own family members who are attending the wedding with you.</p>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section" style="text-align: center; background: var(--pink-light);">
        <div data-aos="zoom-in">
            <h2 class="section-title" style="margin-bottom: 20px;">Ready to start your journey?</h2>
            <p class="hero-subtitle">Whether you are hosting the wedding of your dreams or attending one, we have you covered.</p>
            <a href="{{ route('host.login') }}" class="btn btn-filled" style="padding: 15px 40px; font-size: 1.1rem;">Host Your Wedding</a>
        </div>
    </section>

    <footer>
        &copy; {{ date('Y') }} Wedding Portal. All rights reserved. Crafted with elegance.
    </footer>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100,
        });
    </script>
</body>
</html>
