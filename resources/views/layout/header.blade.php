<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            @php
                $headerUser = auth()->user();
                $headerName = trim(($headerUser->first_name ?? '') . ' ' . ($headerUser->last_name ?? ''));
                $headerRole = strtoupper(str_replace('_', ' ', $headerUser->getRoleNames()->first() ?? 'user'));
                $headerAvatar = $headerUser->image_url;
                $headerInitials = strtoupper(substr($headerUser->first_name ?? 'U', 0, 1) . substr($headerUser->last_name ?? '', 0, 1));
                $isProfileRoute = request()->routeIs('profile') || request()->routeIs('update.user') || request()->routeIs('update.password');
            @endphp

            <div class="d-flex align-items-center">
                <div class="dropdown header-item pos-header-user">
                    <a class="nav-link dropdown-toggle hide-arrow p-0 pos-header-user-btn"
                       href="javascript:void(0);"
                       id="page-header-user-dropdown"
                       data-bs-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <div class="pos-header-avatar avatar-online">
                            @if ($headerAvatar)
                                <img src="{{ $headerAvatar }}"
                                     alt="{{ $headerName }}"
                                     class="rounded-circle pos-header-avatar-img">
                            @else
                                <span class="pos-header-avatar-initial rounded-circle">{{ $headerInitials }}</span>
                            @endif
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end pos-header-dropdown" aria-labelledby="page-header-user-dropdown">
                        <li>
                            <div class="dropdown-item-text">
                                <div class="fw-medium">{{ $headerName }}</div>
                                <small class="text-muted">{{ $headerUser->email }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-item-text">
                                <small class="text-muted text-uppercase">{{ $headerRole }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ $isProfileRoute ? 'active' : '' }}" href="{{ route('profile') }}">
                                <i class="ri-user-line me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-box-r-line me-2"></i>
                                Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
