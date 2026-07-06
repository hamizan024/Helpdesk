<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IT Helpdesk &mdash; @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dashboard CSS -->
    @vite(['resources/css/dashboard.css'])

    @yield('head')
</head>
<body>

    <!-- Mobile Overlay -->
    <div class="sidenav-overlay" id="sidenavOverlay" onclick="closeSidenav()"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidenav" id="sidenav-main">
        <div class="sidenav-header">
            <a href="{{ route('dashboard') }}" class="sidenav-brand">
                <div class="sidenav-brand-icon">
                    <span class="material-icons-round">support_agent</span>
                </div>
                <span class="sidenav-brand-name">IT Helpdesk</span>
            </a>
        </div>
        <hr class="sidenav-divider">

        <nav>
            <span class="nav-section-label">Main</span>
            <ul class="list-unstyled mb-0">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <span class="material-icons-round">dashboard</span>
                        </span>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets.index') }}"
                       class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <span class="material-icons-round">confirmation_number</span>
                        </span>
                        Tickets
                    </a>
                </li>
            </ul>

            @if(Auth::user()->isAdmin())
            <span class="nav-section-label d-block mt-2">Master Data</span>
            <ul class="list-unstyled mb-0">
                <li class="nav-item">
                    <a href="{{ route('master.departments.index') }}"
                       class="nav-link {{ request()->routeIs('master.departments.*') ? 'active' : '' }}">
                        <span class="nav-icon"><span class="material-icons-round">business</span></span>
                        Departments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('master.categories.index') }}"
                       class="nav-link {{ request()->routeIs('master.categories.*') ? 'active' : '' }}">
                        <span class="nav-icon"><span class="material-icons-round">category</span></span>
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('master.priorities.index') }}"
                       class="nav-link {{ request()->routeIs('master.priorities.*') ? 'active' : '' }}">
                        <span class="nav-icon"><span class="material-icons-round">flag</span></span>
                        Priorities
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('master.statuses.index') }}"
                       class="nav-link {{ request()->routeIs('master.statuses.*') ? 'active' : '' }}">
                        <span class="nav-icon"><span class="material-icons-round">label</span></span>
                        Statuses
                    </a>
                </li>
            </ul>
            @endif

            <span class="nav-section-label d-block mt-2">Account</span>
            <ul class="list-unstyled mb-0">
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                       class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <span class="material-icons-round">manage_accounts</span>
                        </span>
                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.sessions') }}"
                       class="nav-link {{ request()->routeIs('profile.sessions') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <span class="material-icons-round">devices</span>
                        </span>
                        Active Sessions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('profile.login-history') }}"
                       class="nav-link {{ request()->routeIs('profile.login-history') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <span class="material-icons-round">history</span>
                        </span>
                        Login History
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit"
                                class="nav-link w-100 text-start"
                                style="background: none; border: none; cursor: pointer;">
                            <span class="nav-icon">
                                <span class="material-icons-round">logout</span>
                            </span>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">

        <!-- Top Navbar -->
        <nav class="navbar-main">
            <div class="navbar-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
                    </ol>
                </nav>
                <h6 class="navbar-page-title">@yield('title', 'Dashboard')</h6>
            </div>

            <div class="navbar-actions">
                <!-- Hamburger (mobile) -->
                <button class="nav-icon-btn sidenav-toggler" onclick="toggleSidenav()" type="button" aria-label="Toggle sidebar">
                    <span class="material-icons-round">menu</span>
                </button>

                <!-- New Ticket shortcut -->
                <a href="{{ route('tickets.create') }}" class="nav-icon-btn" title="Buat Tiket Baru">
                    <span class="material-icons-round">add_circle_outline</span>
                </a>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="nav-user-avatar dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="{{ Auth::user()->getAvatarUrl() ? 'padding:0;overflow:hidden;' : '' }}">
                        @if(Auth::user()->getAvatarUrl())
                            <img src="{{ Auth::user()->getAvatarUrl() }}"
                                 alt="{{ Auth::user()->name }}"
                                 style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="px-3 py-2">
                                <div class="fw-semibold" style="font-size:0.82rem; color:#344767;">
                                    {{ Auth::user()->name }}
                                </div>
                                <div style="font-size:0.72rem; color:#7b809a;">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <span class="material-icons-round me-2"
                                      style="font-size:1rem; vertical-align:middle;">manage_accounts</span>
                                Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <span class="material-icons-round me-2"
                                          style="font-size:1rem; vertical-align:middle;">logout</span>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid px-4 py-4">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- App JS via Vite -->
    @vite(['resources/js/app.js'])

    <script>
        function toggleSidenav() {
            document.getElementById('sidenav-main').classList.toggle('open');
            document.getElementById('sidenavOverlay').classList.toggle('open');
        }
        function closeSidenav() {
            document.getElementById('sidenav-main').classList.remove('open');
            document.getElementById('sidenavOverlay').classList.remove('open');
        }
    </script>

    @yield('scripts')
</body>
</html>
