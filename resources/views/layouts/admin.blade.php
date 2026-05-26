<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Bootstrap Icons for Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-gray-50 text-gray-800 antialiased overflow-x-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-white border-r border-gray-200 p-6 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
        <div>
            <!-- Brand -->
            <a href="#" class="block text-xl font-extrabold tracking-wider text-gray-800 mb-8 px-2">
                ADMIN PANEL
            </a>

            <!-- Navigation Links -->
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-grid-1x2-fill mr-4 text-lg"></i> Dashboard
                </a>
                <a href="{{ route('admin.host.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.host.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-people-fill mr-4 text-lg"></i> Host
                </a>
                <a href="{{ route('admin.categoryvenue.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.categoryvenue.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-building-fill mr-4 text-lg"></i> Category Venue
                </a>
                <a href="{{ route('admin.ceramony.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.ceramony.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-calendar-event-fill mr-4 text-lg"></i> Ceremony
                </a>
                <a href="{{ route('admin.invitation.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.invitation.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-envelope-paper-fill mr-4 text-lg"></i> Invitation
                </a>
                <a href="{{ route('admin.package.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.package.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-box-seam-fill mr-4 text-lg"></i> Package
                </a>
                <a href="{{ route('admin.guestlist.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.guestlist.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-person-lines-fill mr-4 text-lg"></i> Guest List
                </a>
                <a href="{{ route('admin.venue.index') }}" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors {{ request()->routeIs('admin.venue.*') ? 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' : '' }}">
                    <i class="bi bi-geo-alt-fill mr-4 text-lg"></i> Manage Venues
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    <i class="bi bi-gear-fill mr-4 text-lg"></i> Settings
                </a>
            </nav>
        </div>

        <!-- Logout Action -->
        <div class="mt-auto">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl font-semibold hover:bg-red-600 hover:text-white transition-colors cursor-pointer">
                    <i class="bi bi-box-arrow-right mr-2 text-lg"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area Container -->
    <div class="lg:pl-64 min-h-screen flex flex-col transition-all duration-300">

        <!-- Top Navbar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 sticky top-0 z-30 flex items-center justify-between">
            <button id="mobile-toggle" class="lg:hidden flex items-center justify-center p-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer">
                <i class="bi bi-list text-xl"></i>
            </button>
            <div class="ml-auto flex items-center">
                <span class="font-semibold text-gray-700">Hello, Admin</span>
            </div>
        </header>

        <!-- Dynamic Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- JavaScript for Mobile Sidebar Interactivity -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('mobile-toggle');

        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
</body>

</html>