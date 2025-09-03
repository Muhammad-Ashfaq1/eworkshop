<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/favicon.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/favicon.png') }}" alt="" height="40">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}" class="logo logo-light p-2">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/favicon.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/favicon.png') }}" alt="" height="40">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                @if(auth()->user()->image_url)
                    <img class="rounded header-profile-user" src="{{ auth()->user()->image_url }}" alt="Header Avatar">
                @else
                    <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                @endif
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{ Auth::user()->first_name ?? Auth::user()->name ?? 'Workshop User' }} {{ Auth::user()->last_name ?? '' }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{ Auth::user()->first_name ?? Auth::user()->name ?? 'Workshop User' }}!</h6>
            <a class="dropdown-item" href="{{ route('profile') }}">
                <i class="ri-user-line text-muted fs-16 align-middle me-1"></i>
                <span class="align-middle">Profile</span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="ri-logout-box-line text-muted fs-16 align-middle me-1"></i>
                <span class="align-middle">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-main-menu">Main Menu</span></li>

                {{-- Dynamic Dashboard Link --}}
                <li class="nav-item">
                    @php
                        $dashboardRoute = \App\Http\Controllers\DashboardController::getDashboardRoute();
                        $dashboardRouteName = \App\Http\Controllers\DashboardController::getDashboardRouteName();
                    @endphp
                    <a class="nav-link menu-link {{ request()->routeIs($dashboardRouteName) ? 'active' : '' }}"
                        href="{{ $dashboardRoute }}">
                        <i class="ri-dashboard-line"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <!-- User Management -->
                @can('read_users')
                    <li class="menu-title"><span data-key="t-user-management">User Management</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}"
                            href="{{ route('admin.user.index') }}">
                            <i class="ri-team-line"></i> <span data-key="t-user-management">Users</span>
                        </a>
                    </li>
                @endcan

                <!-- Workshop Management -->
                @role('super_admin|admin|deo')
                    <li class="menu-title"><span data-key="t-workshop-management">Workshop Management</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ (request()->routeIs('admin.location.*') && !request()->routeIs('admin.location.archieved')) || request()->routeIs('admin.vehicle.*') || request()->routeIs('admin.fleet-manager.*') ? 'active' : '' }}"
                            href="#sidebarMasterData" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ (request()->routeIs('admin.location.*') && !request()->routeIs('admin.location.archieved')) || request()->routeIs('admin.vehicle.*') || request()->routeIs('admin.fleet-manager.*') ? 'true' : 'false' }}"
                            aria-controls="sidebarMasterData">
                            <i class="ri-database-2-line"></i> <span data-key="t-master-data">Master Data</span>
                        </a>
                        <div class="collapse menu-dropdown {{ (request()->routeIs('admin.location.*') && !request()->routeIs('admin.location.archieved')) || request()->routeIs('admin.vehicle.*') || request()->routeIs('admin.fleet-manager.*') ? 'show' : '' }}"
                            id="sidebarMasterData">
                            <ul class="nav nav-sm flex-column">
                                @can('read_locations')
                                <li class="nav-item">
                                    <a href="{{ route('admin.location.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.location.*') ? 'active' : '' }}">
                                        <i class="ri-map-pin-line align-bottom me-1"></i>Locations / Workshop
                                    </a>
                                </li>
                                @endcan
                                @can('read_vehicle_parts')
                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle.part.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.vehicle.part.*') ? 'active' : '' }}">
                                        <i class="ri-settings-3-line align-bottom me-1"></i>Vehicle Parts
                                    </a>
                                </li>
                                @endcan

                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle-categories.index') }}"
                                        class="nav-link ">
                                        <i class="ri-car-line align-bottom me-1"></i>Vehicle Categories
                                    </a>
                                </li>


                                @can('read_vehicles')
                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.vehicle.index') ? 'active' : '' }}">
                                        <i class="ri-car-line align-bottom me-1"></i>Vehicle
                                    </a>
                                </li>
                                @endcan
                                @can('read_fleet_manager')
                                <li class="nav-item">
                                    <a href="{{ route('admin.fleet-manager.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.fleet-manager.*') ? 'active' : '' }}">
                                        <i class="ri-team-line align-bottom me-1"></i> Fleet Manager / MVI
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endrole

                <!-- Reports & Operations -->
                <li class="menu-title"><span data-key="t-operations">Operations & Reports</span></li>

                @role('super_admin|admin')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                            href="{{ route('admin.reports.index') }}">
                            <i class="ri-file-chart-line"></i> <span data-key="t-reports">Reports & Analytics</span>
                        </a>
                    </li>
                @endrole

                @role('super_admin|admin|deo')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('defect-reports.*') ? 'active' : '' }}"
                            href="{{ route('defect-reports.index') }}">
                            <i class="ri-file-damage-line"></i> <span data-key="t-defect-reports">Defect Reports</span>
                        </a>
                    </li>
                @endrole

                @can('read_purchase_orders')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}"
                            href="{{ route('purchase-orders.index') }}">
                            <i class="ri-shopping-cart-line"></i> <span data-key="t-purchase-orders">Purchase Orders</span>
                        </a>
                    </li>
                @endcan

                @can('view_report_logs')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}"
                            href="{{ route('admin.logs.index') }}">
                            <i class="ri-file-list-3-line"></i> <span data-key="t-report-logs">Report Logs</span>
                        </a>
                    </li>
                @endcan

                <!-- Archive Section -->
                @role('super_admin')
                    <li class="menu-title"><span data-key="t-archive">Archive</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('admin.location.archieved') || request()->routeIs('defect-reports.archieved') || request()->routeIs('admin.vehicle.archived') || request()->routeIs('admin.vehicle.part.archived') || request()->routeIs('admin.fleet-manager.archived') || request()->routeIs('purchase-orders.archived') ? 'active' : '' }}"
                            href="#sidebarArchived" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('admin.location.archieved') || request()->routeIs('defect-reports.archieved') || request()->routeIs('admin.vehicle.archived') || request()->routeIs('admin.vehicle.part.archived') || request()->routeIs('admin.fleet-manager.archived') || request()->routeIs('purchase-orders.archived') ? 'true' : 'false' }}"
                            aria-controls="sidebarArchived">
                            <i class="ri-archive-line"></i> <span data-key="t-archived">Archived Data</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('admin.location.archieved') || request()->routeIs('defect-reports.archieved') || request()->routeIs('admin.vehicle.archived') || request()->routeIs('admin.vehicle.part.archived') || request()->routeIs('admin.fleet-manager.archived') || request()->routeIs('purchase-orders.archived') ? 'show' : '' }}"
                            id="sidebarArchived">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.location.archieved') }}"
                                        class="nav-link {{ request()->routeIs('admin.location.archieved') ? 'active' : '' }}">
                                        <i class="ri-map-pin-line align-bottom me-1"></i>Archived Locations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle.archived') }}"
                                        class="nav-link {{ request()->routeIs('admin.vehicle.archived') ? 'active' : '' }}">
                                        <i class="ri-car-line align-bottom me-1"></i>Archived Vehicles
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle.part.archived') }}"
                                        class="nav-link {{ request()->routeIs('admin.vehicle.part.archived') ? 'active' : '' }}">
                                        <i class="ri-settings-3-line align-bottom me-1"></i>Archived Vehicle Parts
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle-categories.archieved') }}"
                                        class="nav-link {{ request()->routeIs('admin.vehicle.categories.archieved') ? 'active' : '' }}">
                                        <i class="ri-settings-3-line align-bottom me-1"></i>Archived Vehicle Categories
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.fleet-manager.archived') }}"
                                        class="nav-link {{ request()->routeIs('admin.fleet-manager.archived') ? 'active' : '' }}">
                                        <i class="ri-team-line align-bottom me-1"></i>Archived Fleet Managers
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('defect-reports.archieved') }}"
                                        class="nav-link {{ request()->routeIs('defect-reports.archieved') ? 'active' : '' }}">
                                        <i class="ri-file-damage-line align-bottom me-1"></i>Archived Defect Reports
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('purchase-orders.archived') }}"
                                        class="nav-link {{ request()->routeIs('purchase-orders.archived') ? 'active' : '' }}">
                                        <i class="ri-shopping-cart-line align-bottom me-1"></i>Archived Purchase Orders
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
