<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name ?? 'Host' }} Panel | Wedding Management</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1e293b;
            --secondary-color: #64748b;
            --accent-color: #4f46e5;
            --wedding-gold: #c5a059;
            --wedding-rose: #f43f5e;
            --bg-light: #f8fafc;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: var(--primary-color);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar { 
            width: var(--sidebar-width); 
            position: fixed; 
            height: 100vh; 
            background: #ffffff; 
            color: var(--primary-color); 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
            z-index: 1050;
            overflow-y: auto;
            border-right: 1px solid #e2e8f0;
            scrollbar-width: thin;
        }

        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        #main-content { 
            margin-left: var(--sidebar-width); 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation Links */
        .nav-item {
            margin: 4px 15px;
        }

        .nav-link { 
            color: var(--secondary-color); 
            padding: 12px 18px;
            display: flex;
            align-items: center;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            transition: all 0.2s ease;
        }

        .nav-link:hover { 
            color: var(--accent-color); 
            background: #f1f5f9; 
            transform: translateX(5px);
        }

        .nav-link.active { 
            color: #ffffff; 
            background: var(--accent-color); 
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .nav-link.active i {
            color: #ffffff;
        }

        /* Branding */
        .sidebar-brand {
            padding: 30px 25px;
            margin-bottom: 10px;
        }
        
        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .brand-logo span {
            color: var(--accent-color);
        }

        /* Top Navbar */
        .top-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Mobile Adjustments */
        @media (max-width: 992px) { 
            #sidebar { margin-left: calc(-1 * var(--sidebar-width)); } 
            #sidebar.active { margin-left: 0; box-shadow: 20px 0 25px -5px rgba(0, 0, 0, 0.1); } 
            #main-content { margin-left: 0; } 
            .sidebar-overlay.show { display: block; }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(2px);
            z-index: 1040;
        }

        /* Status Badge */
        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        /* Logout Section */
        .sidebar-footer {
            margin-top: auto;
            padding: 25px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-logout {
            background: #fff1f2;
            color: #e11d48;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #e11d48;
            color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $user = Auth::guard('host')->user();
        $perms = $user->permissions ?? [];
    @endphp

    <div class="sidebar-overlay" id="overlay"></div>

    <aside id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('host.dashboard') }}" class="brand-logo">
                Wedding<span>Hub</span>
            </a>
            <p class="text-muted small mb-0 mt-1">Host Panel v2.0</p>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('host.dashboard') }}" class="nav-link {{ request()->routeIs('host.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>

            <div class="px-4 mt-4 mb-2 small text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 1px;">Management</div>

            @if(in_array('ceremonies', $perms))
            <li class="nav-item">
                <a href="{{ route('host.ceramony.index') }}" class="nav-link {{ request()->routeIs('host.ceramony.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar4-event"></i> Ceremonies
                </a>
            </li>
            @endif

            @if(in_array('guest-list', $perms))
            <li class="nav-item">
                <a href="{{ route('host.guestlist.index') }}" class="nav-link {{ request()->routeIs('host.guestlist.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Guest List
                </a>
            </li>
            @endif

            @if(in_array('gallery', $perms))
            <li class="nav-item">
                <a href="{{ route('host.picture.index') }}" class="nav-link {{ request()->routeIs('host.picture.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i> Media Gallery
                </a>
            </li>
            @endif

            <div class="px-4 mt-4 mb-2 small text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 1px;">Invitation Tools</div>

            @if(in_array('invitation', $perms))
            <li class="nav-item">
                <a href="{{ route('host.invitation.index') }}" class="nav-link {{ request()->routeIs('host.invitation.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper"></i> Invitation Card
                </a>
            </li>
            @endif

            @if(in_array('save-the-date', $perms))
            <li class="nav-item">
                <a href="{{ route('host.savedate.index') }}" class="nav-link {{ request()->routeIs('host.savedate.*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i> Save The Date
                </a>
            </li>
            @endif

            <div class="px-4 mt-4 mb-2 small text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 1px;">System</div>

            @if(in_array('categories', $perms))
            <li class="nav-item">
                <a href="{{ route('host.categories.index') }}" class="nav-link {{ request()->routeIs('host.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('host.profile.edit') }}" class="nav-link {{ request()->routeIs('host.profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Profile Settings
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('host.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main id="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light border-0 d-lg-none me-3" id="mobile-toggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                
                <div class="search-box d-none d-md-block">
                    <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">Host Dashboard</h5>
                </div>

                <div class="ms-auto d-flex align-items-center">
                    <div class="d-none d-sm-block text-end me-3">
                        <div class="fw-bold small">{{ $user->name }}</div>
                        <div class="text-muted" style="font-size: 11px;"><span class="status-dot"></span>Online</div>
                    </div>
                    <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; font-size: 14px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <section class="content-wrapper flex-grow-1">
            @yield('content')
        </section>

        <footer class="mt-auto py-3 px-4 bg-white border-top text-center text-muted small">
            &copy; {{ date('Y') }} WeddingHub Management System. All rights reserved.
        </footer>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('mobile-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('show');
        }

        if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);

        // Status Check Logic
        function checkStatus() {
            $.ajax({
                url: "{{ route('admin.check.status') }}", 
                method: "GET",
                success: function(response) {
                    if (response.status === 'inactive') {
                        window.location.href = "{{ route('host.login') }}";
                    }
                }
            });
        }
        setInterval(checkStatus, 15000);
    </script>
    @stack('scripts')
</body>
</html>