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
            <button class="sidenav-minimize-toggler" id="sidenavMinimizeToggler" type="button" aria-label="Minimize sidebar" title="Minimize sidebar">
                <span class="material-icons-round">chevron_left</span>
            </button>
        </div>
        <hr class="sidenav-divider">

        <nav>
            @php $mainActive = request()->routeIs('dashboard') || request()->routeIs('tickets.*'); @endphp
            <span class="nav-section-label">Main</span>
            <ul class="list-unstyled mb-0">
                <li class="nav-item nav-group">
                    <a href="#" class="nav-link nav-group-toggle {{ $mainActive ? 'active' : '' }}"
                       data-bs-toggle="collapse" data-bs-target="#mainMenuSubmenu"
                       aria-expanded="{{ $mainActive ? 'true' : 'false' }}" title="Main">
                        <span class="nav-icon"><span class="material-icons-round">apps</span></span>
                        <span class="nav-link-text">Main</span>
                        <span class="nav-caret material-icons-round">expand_more</span>
                    </a>
                    <ul class="nav-submenu collapse list-unstyled {{ $mainActive ? 'show' : '' }}" id="mainMenuSubmenu">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                               class="nav-sublink {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tickets.index') }}"
                               class="nav-sublink {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Tickets
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            @if(Auth::user()->isAdmin())
            @php $masterDataActive = request()->routeIs('master.*'); @endphp
            <span class="nav-section-label d-block mt-2">Master Data</span>
            <ul class="list-unstyled mb-0">
                <li class="nav-item nav-group">
                    <a href="#" class="nav-link nav-group-toggle {{ $masterDataActive ? 'active' : '' }}"
                       data-bs-toggle="collapse" data-bs-target="#masterDataSubmenu"
                       aria-expanded="{{ $masterDataActive ? 'true' : 'false' }}" title="Master Data">
                        <span class="nav-icon"><span class="material-icons-round">inventory_2</span></span>
                        <span class="nav-link-text">Master Data</span>
                        <span class="nav-caret material-icons-round">expand_more</span>
                    </a>
                    <ul class="nav-submenu collapse list-unstyled {{ $masterDataActive ? 'show' : '' }}" id="masterDataSubmenu">
                        <li class="nav-item">
                            <a href="{{ route('master.users.index') }}"
                               class="nav-sublink {{ request()->routeIs('master.users.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.departments.index') }}"
                               class="nav-sublink {{ request()->routeIs('master.departments.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Departments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.categories.index') }}"
                               class="nav-sublink {{ request()->routeIs('master.categories.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.priorities.index') }}"
                               class="nav-sublink {{ request()->routeIs('master.priorities.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Priorities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.statuses.index') }}"
                               class="nav-sublink {{ request()->routeIs('master.statuses.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Statuses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.technicians.index') }}"
                               class="nav-sublink {{ request()->routeIs('master.technicians.*') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Technicians
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endif

            @php $accountActive = request()->routeIs('profile.edit') || request()->routeIs('profile.sessions') || request()->routeIs('profile.login-history'); @endphp
            <span class="nav-section-label d-block mt-2">Account</span>
            <ul class="list-unstyled mb-0">
                <li class="nav-item nav-group">
                    <a href="#" class="nav-link nav-group-toggle {{ $accountActive ? 'active' : '' }}"
                       data-bs-toggle="collapse" data-bs-target="#accountMenuSubmenu"
                       aria-expanded="{{ $accountActive ? 'true' : 'false' }}" title="Account">
                        <span class="nav-icon"><span class="material-icons-round">account_circle</span></span>
                        <span class="nav-link-text">Account</span>
                        <span class="nav-caret material-icons-round">expand_more</span>
                    </a>
                    <ul class="nav-submenu collapse list-unstyled {{ $accountActive ? 'show' : '' }}" id="accountMenuSubmenu">
                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}"
                               class="nav-sublink {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.sessions') }}"
                               class="nav-sublink {{ request()->routeIs('profile.sessions') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Active Sessions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.login-history') }}"
                               class="nav-sublink {{ request()->routeIs('profile.login-history') ? 'active' : '' }}">
                                <span class="nav-subdot"></span>
                                Login History
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="nav-sublink w-100 text-start"
                                        style="background: none; border: none; cursor: pointer;">
                                    <span class="nav-subdot"></span>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
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

                <!-- Notification Bell -->
                @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                <div class="dropdown">
                    <button class="nav-icon-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                        <span class="material-icons-round">notifications</span>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-white"
                                  style="background:linear-gradient(195deg,#EC407A,#D81B60);font-size:0.6rem;padding:3px 5px;min-width:16px;line-height:1;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width:320px;max-height:400px;overflow-y:auto;">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                            <span class="fw-semibold" style="font-size:0.82rem;color:#344767;">Notifications</span>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm p-0" style="font-size:0.72rem;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        @forelse(Auth::user()->notifications()->latest()->take(8)->get() as $notif)
                            @php $data = $notif->data; @endphp
                            <a href="{{ isset($data['ticket_id']) ? route('tickets.show', $data['ticket_id']) : '#' }}"
                               class="dropdown-item py-2 px-3 border-bottom {{ $notif->read_at ? '' : 'fw-semibold' }}"
                               style="white-space:normal;font-size:0.78rem;{{ $notif->read_at ? 'color:#7b809a;' : 'color:#344767;background:#fef9fa;' }}"
                               onclick="markRead('{{ $notif->id }}', event)">
                                <div>{{ $data['message'] ?? 'New notification' }}</div>
                                <div class="text-muted" style="font-size:0.68rem;">{{ $notif->created_at->diffForHumans() }}</div>
                            </a>
                        @empty
                            <div class="px-3 py-4 text-center text-muted" style="font-size:0.82rem;">No notifications yet.</div>
                        @endforelse
                    </div>
                </div>

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
        function markRead(id, event) {
            fetch('/notifications/' + id + '/read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
        }

        // ===== Sidebar minimize =====
        (function () {
            var body = document.body;
            var toggler = document.getElementById('sidenavMinimizeToggler');
            var isMinimized = localStorage.getItem('sidenav-minimized') === '1';
            if (isMinimized) body.classList.add('sidenav-minimized');

            toggler.addEventListener('click', function () {
                var minimized = body.classList.toggle('sidenav-minimized');
                localStorage.setItem('sidenav-minimized', minimized ? '1' : '0');
            });
        })();

        // ===== Nav group caret rotation (Bootstrap collapse events) =====
        document.querySelectorAll('.nav-submenu').forEach(function (submenu) {
            var toggle = document.querySelector('[data-bs-target="#' + submenu.id + '"]');
            if (!toggle) return;
            submenu.addEventListener('show.bs.collapse', function () { toggle.setAttribute('aria-expanded', 'true'); });
            submenu.addEventListener('hide.bs.collapse', function () { toggle.setAttribute('aria-expanded', 'false'); });
        });

        // ===== Minimized-sidebar flyout submenus =====
        // Reparents the submenu to <body> as position:fixed so it isn't clipped
        // by the sidebar's overflow, and positions it next to the hovered icon.
        (function () {
            var isDesktop = function () { return window.matchMedia('(min-width: 992px)').matches; };

            document.querySelectorAll('.nav-group').forEach(function (group) {
                var submenu = group.querySelector('.nav-submenu');
                var toggle = group.querySelector('.nav-group-toggle');
                if (!submenu || !toggle) return;

                var closeTimer = null;
                var openFlyout = function () {
                    clearTimeout(closeTimer);
                    if (!document.body.classList.contains('sidenav-minimized') || !isDesktop()) return;
                    var rect = toggle.getBoundingClientRect();
                    submenu.style.top = rect.top + 'px';
                    submenu.style.left = (rect.right + 10) + 'px';
                    document.body.appendChild(submenu);
                    submenu.classList.add('nav-flyout-active');
                };
                var scheduleClose = function () {
                    clearTimeout(closeTimer);
                    closeTimer = setTimeout(function () {
                        submenu.classList.remove('nav-flyout-active');
                        submenu.style.top = '';
                        submenu.style.left = '';
                        group.appendChild(submenu);
                    }, 150);
                };

                group.addEventListener('mouseenter', openFlyout);
                group.addEventListener('mouseleave', scheduleClose);
                submenu.addEventListener('mouseenter', function () { clearTimeout(closeTimer); });
                submenu.addEventListener('mouseleave', scheduleClose);
            });
        })();
    </script>

    @yield('scripts')
</body>
</html>
