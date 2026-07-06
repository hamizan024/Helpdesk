<style>
    .profile-nav .nav-link {
        color: #7b809a;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 10px 16px;
        border: none;
        border-bottom: 2px solid transparent;
    }
    .profile-nav .nav-link.active {
        color: #EC407A;
        border-bottom: 2px solid #EC407A;
        background: none;
    }
    .profile-nav .nav-link:hover:not(.active) {
        color: #344767;
        border-bottom: 2px solid #dee2e6;
        background: none;
    }
</style>

<div class="profile-nav mb-4">
    <ul class="nav border-bottom">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
               href="{{ route('profile.edit') }}">
                <span class="material-icons-round me-1" style="font-size:0.95rem;vertical-align:-3px;">manage_accounts</span>
                Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.sessions') ? 'active' : '' }}"
               href="{{ route('profile.sessions') }}">
                <span class="material-icons-round me-1" style="font-size:0.95rem;vertical-align:-3px;">devices</span>
                Active Sessions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.login-history') ? 'active' : '' }}"
               href="{{ route('profile.login-history') }}">
                <span class="material-icons-round me-1" style="font-size:0.95rem;vertical-align:-3px;">history</span>
                Login History
            </a>
        </li>
    </ul>
</div>