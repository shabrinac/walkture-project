<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription ?? 'Walkture — Discover aesthetic photo spots & connect with street photographers in Samarinda.' }}">
    <title>{{ $title ?? 'Walkture' }} — Aesthetic Photo Spot GIS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Walkture Logo.png') }}">

    {{-- Inter & Material Symbols --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Design System: Inter base ── */
        * { font-family: 'Inter', sans-serif; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            font-size: 20px;
        }

        /* ── Sidebar ── */
        #wt-sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 8px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #wt-sidebar.open { transform: translateX(0); }

        /* ── Sticky Header ── */
        #wt-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 64px;
            background: rgba(249, 249, 255, 0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
            z-index: 40;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
        }

        /* ── Overlay ── */
        #wt-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20, 27, 43, 0.35);
            z-index: 49;
        }
        #wt-overlay.show { display: block; }

        /* ── Main content ── */
        #wt-main {
            padding-top: 64px;
            min-height: 100vh;
            background: #f9f9ff;
        }

        /* ── Nav items ── */
        .wt-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 2px 12px;
            border-radius: 10px;
            color: #424842;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .wt-nav-item:hover {
            background: #f1f3ff;
            color: #43664c;
        }
        .wt-nav-item.active {
            background: #c5eccb;
            color: #43664c;
            font-weight: 600;
        }
        .wt-nav-section {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #c2c8c0;
            padding: 16px 28px 6px;
        }

        /* ── Polaroid card ── */
        .polaroid {
            background: #ffffff;
            padding: 12px 12px 32px 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);
            border-radius: 4px;
            transition: transform 0.2s ease;
        }
        .polaroid:hover { transform: rotate(-1deg) scale(1.02); }

        /* ── Scrollbar hide ── */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Focus sage green ── */
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #7ba082 !important;
            box-shadow: 0 0 0 3px rgba(123, 160, 130, 0.15);
        }
    </style>

    @stack('head')
</head>
<body class="bg-[#f9f9ff] text-[#141b2b] antialiased">

    {{-- ── Sidebar Overlay (click to close) ─────────────────────── --}}
    <div id="wt-overlay" onclick="closeSidebar()"></div>

    {{-- ── Sidebar Drawer ──────────────────────────────────────── --}}
    <aside id="wt-sidebar">
        {{-- Logo & Brand --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-[#e5e7eb] flex-shrink-0">
            <img src="{{ asset('images/Walkture Logo.png') }}" alt="Walkture"
                 class="h-9 w-auto object-contain flex-shrink-0">
            <div class="flex-1 min-w-0">
                <p class="text-[15px] font-bold text-[#141b2b] leading-none">Walkture</p>
                <p class="text-[11px] text-[#727971] mt-0.5">
                    @if(auth()->user()->isAdmin())
                        <span class="text-[#43664c] font-semibold">Administrator</span>
                    @else
                        GIS Photo Platform
                    @endif
                </p>
            </div>
            <button onclick="closeSidebar()" class="p-1.5 rounded-lg text-[#727971] hover:bg-[#f1f3ff] transition-colors" title="Close sidebar">
                <span class="material-symbols-outlined" style="font-size:18px">close</span>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto no-scrollbar py-3">

            {{-- ═══ USER SIDEBAR (role: user) ═══ --}}
            @if(auth()->user()->isUser())
                <p class="wt-nav-section">Explore</p>

                <a href="{{ route('user.dashboard') }}" id="nav-user-dashboard"
                   class="wt-nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('user.map') }}" id="nav-user-map"
                   class="wt-nav-item {{ request()->routeIs('user.map') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">map</span>
                    <span>Interactive Map</span>
                </a>

                <a href="{{ route('user.photographers') }}" id="nav-user-photographers"
                   class="wt-nav-item {{ request()->routeIs('user.photographers') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">photo_camera</span>
                    <span>Photographers</span>
                </a>



                <p class="wt-nav-section">Account</p>

                <a href="{{ route('user.profile') }}" id="nav-user-profile"
                   class="wt-nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">person</span>
                    <span>My Profile</span>
                </a>

                <a href="{{ route('user.settings') }}" id="nav-user-settings"
                   class="wt-nav-item {{ request()->routeIs('user.settings') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <span>Settings</span>
                </a>
            @endif

            {{-- ═══ ADMIN SIDEBAR (role: admin) ═══ --}}
            @if(auth()->user()->isAdmin())
                <p class="wt-nav-section">Overview</p>

                <a href="{{ route('admin.dashboard') }}" id="nav-admin-dashboard"
                   class="wt-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">bar_chart</span>
                    <span>Admin Dashboard</span>
                </a>

                <p class="wt-nav-section">GIS Management</p>

                <a href="{{ route('admin.spatial-data') }}" id="nav-admin-spatial"
                   class="wt-nav-item {{ request()->routeIs('admin.spatial-data') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">layers</span>
                    <span>Spatial Data</span>
                    <span class="ml-auto text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-1.5 py-0.5 rounded-full">GIS</span>
                </a>


                <p class="wt-nav-section">Directory</p>

                <a href="{{ route('admin.directory') }}" id="nav-admin-directory"
                   class="wt-nav-item {{ request()->routeIs('admin.directory') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">photo_camera</span>
                    <span>Photographer Directory</span>
                </a>

                <p class="wt-nav-section">System</p>

                <a href="{{ route('admin.users') }}" id="nav-admin-users"
                   class="wt-nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">group</span>
                    <span>User Management</span>
                </a>
            @endif
        </nav>

        {{-- User Info Footer --}}
        <div class="border-t border-[#e5e7eb] p-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                @if(auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-9 h-9 rounded-full object-cover flex-shrink-0 ring-2 ring-[#c5eccb]">
                @else
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-sm" style="background:#7ba082">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-[#141b2b] truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-[#727971] truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" id="sidebar-logout-btn" title="Log out"
                            class="p-2 rounded-lg text-[#727971] hover:text-red-500 hover:bg-red-50 transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Sticky Header ────────────────────────────────────────── --}}
    <header id="wt-header">
        <button id="menu-toggle-btn" onclick="toggleSidebar()"
                class="p-2 rounded-xl text-[#424842] hover:bg-[#e9edff] transition-colors flex-shrink-0">
            <span class="material-symbols-outlined">menu</span>
        </button>

        {{-- Brand in header --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <img src="{{ asset('images/Walkture Logo.png') }}" alt="Walkture"
                 class="h-8 w-auto object-contain">
        </div>

        {{-- Page Title (injected from view) --}}
        <div class="flex-1 min-w-0">
            @hasSection('page-title')
                <h1 class="text-[15px] font-semibold text-[#141b2b] truncate">@yield('page-title')</h1>
            @endif
        </div>

        {{-- Header Right Actions --}}
        <div class="flex items-center gap-2 ml-auto">
            {{-- Optional: Public pages links --}}
            <a href="{{ route('guest.contact') }}" id="header-contact-link"
               class="hidden md:flex items-center gap-1 text-[13px] text-[#424842] hover:text-[#43664c] transition-colors px-2 py-1.5 rounded-lg hover:bg-[#f1f3ff]">
                <span class="material-symbols-outlined" style="font-size:16px">support_agent</span>
                <span>Support</span>
            </a>

            {{-- User avatar button --}}
            @if(auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}"
                     alt="{{ auth()->user()->name }}"
                     class="w-8 h-8 rounded-full object-cover cursor-pointer ring-2 ring-[#7ba082] hover:ring-[#43664c] transition-all"
                     onclick="toggleSidebar()" title="Open menu">
            @else
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold cursor-pointer hover:opacity-80 transition-opacity" style="background:#7ba082"
                     onclick="toggleSidebar()" title="Open menu">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
    </header>

    {{-- ── Main Content ─────────────────────────────────────────── --}}
    <main id="wt-main">
        @if (session('error'))
            <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px;color:#ba1a1a">error</span>
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-[#c5eccb] border border-[#a9d0b0] rounded-xl text-sm text-[#14361f] flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px;color:#43664c">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}

        {{-- Footer (hidden on map route) --}}
        @if (!request()->routeIs('user.map'))
        <footer class="border-t border-[#e5e7eb] mt-16 py-8 px-6 bg-white">
            <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:#43664c">
                        <span class="material-symbols-outlined text-white" style="font-size:12px">route</span>
                    </div>
                    <span class="text-sm font-semibold text-[#141b2b]">Walkture</span>
                    <span class="text-sm text-[#727971]">— GIS Photo Platform, Samarinda</span>
                </div>
                <div class="flex items-center gap-4 text-[13px] text-[#727971]">
                    <a href="{{ route('guest.privacy') }}" id="footer-privacy-link" class="hover:text-[#43664c] transition-colors">Privacy</a>
                    <a href="{{ route('guest.terms') }}" id="footer-terms-link" class="hover:text-[#43664c] transition-colors">Terms</a>
                    <a href="{{ route('guest.contact') }}" id="footer-contact-link" class="hover:text-[#43664c] transition-colors">Contact</a>
                    <span>© {{ date('Y') }} Walkture</span>
                </div>
            </div>
        </footer>
        @endif
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('wt-sidebar');
            const overlay = document.getElementById('wt-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('wt-sidebar').classList.remove('open');
            document.getElementById('wt-overlay').classList.remove('show');
        }
        // Close on Escape key
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
    </script>

    @stack('scripts')
</body>
</html>
