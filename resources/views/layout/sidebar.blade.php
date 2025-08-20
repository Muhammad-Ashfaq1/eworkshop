<div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div class="dropdown sidebar-user m-1 rounded">
                <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                        <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                        <span class="text-start">
                            <span class="d-block fw-medium sidebar-user-name-text">Anna Adame</span>
                            <span class="d-block fs-14 sidebar-user-name-sub-text"><i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span class="align-middle">Online</span></span>
                        </span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <h6 class="dropdown-header">Welcome Anna!</h6>
                    <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                    <a class="dropdown-item" href="apps-chat.html"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Messages</span></a>
                    <a class="dropdown-item" href="apps-tasks-kanban.html"><i class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Taskboard</span></a>
                    <a class="dropdown-item" href="pages-faqs.html"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Help</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance : <b>$5971.67</b></span></a>
                    <a class="dropdown-item" href="pages-profile-settings.html"><span class="badge bg-success-subtle text-success mt-1 float-end">New</span><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
                    <a class="dropdown-item" href="auth-lockscreen-basic.html"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>
                    <a class="dropdown-item" href="auth-logout-basic.html"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                </div>
            </div>
            <div id="scrollbar">
                <div class="container-fluid">


                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                        {{-- Dynamic Dashboard Link --}}
                        <li class="nav-item">
                            @php
                                $dashboardRoute = \App\Http\Controllers\DashboardController::getDashboardRoute();
                                $dashboardRouteName = \App\Http\Controllers\DashboardController::getDashboardRouteName();
                            @endphp
                            <a class="nav-link menu-link {{ request()->routeIs($dashboardRouteName) ? 'active' : '' }}" href="{{ $dashboardRoute }}">
                                <i class="ri-dashboard-line"></i> <span data-key="t-dashboard">Dashboard</span>
                            </a>
                        </li>

                        @role('super_admin')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}" href="{{ route('admin.user.index') }}">
                                <i class="ri-team-line"></i> <span data-key="t-user-management">User Management</span>
                            </a>
                        </li>
                        @endrole

                        @role('super_admin|admin|deo')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('admin.location.*') || request()->routeIs('admin.vehicle.*') ? 'active' : '' }}"
                               href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                               aria-expanded="{{ request()->routeIs('admin.location.*') || request()->routeIs('admin.vehicle.*') ? 'true' : 'false' }}"
                               aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Master Data</span>
                            </a>
                            <div class="collapse menu-dropdown {{ request()->routeIs('admin.location.*') || request()->routeIs('admin.vehicle.*') ? 'show' : '' }}" id="sidebarDashboards">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.location.index') }}"
                                           class="nav-link {{ request()->routeIs('admin.location.*') ? 'active' : '' }}"
                                           data-key="t-analytics">
                                            <i class="ri-map-pin-line me-2"></i>Locations / Workshop
                                        </a>
                                    </li>
                                     <li class="nav-item">
                                        <a href="{{ route('admin.vehicle.part.index') }}"
                                           class="nav-link {{ request()->routeIs('admin.vehicle.part.*') ? 'active' : '' }}"
                                           data-key="t-analytics">
                                            <i class="ri-settings-3-line me-2"></i>Vehicle Parts
                                        </a>
                                    </li>
                                      <li class="nav-item">
                                        <a href="{{ route('admin.vehicle.index') }}"
                                           class="nav-link {{ request()->routeIs('admin.vehicle.index') ? 'active' : '' }}"
                                           data-key="t-analytics">
                                            <i class="ri-car-line me-2"></i>Vehicle
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endrole

                        @role('fleet_manager')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarFleetManagement" data-bs-toggle="collapse" role="button"
                               aria-expanded="false" aria-controls="sidebarFleetManagement">
                                <i class="ri-truck-line"></i> <span data-key="t-fleet-management">Fleet Management</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarFleetManagement">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-key="t-vehicle-tracking">
                                            <i class="ri-map-2-line me-2"></i>Vehicle Tracking
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-key="t-maintenance">
                                            <i class="ri-tools-line me-2"></i>Maintenance Schedule
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-key="t-fleet-reports">
                                            <i class="ri-file-chart-line me-2"></i>Fleet Reports
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endrole

                        @role('mvi')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarInspections" data-bs-toggle="collapse" role="button"
                               aria-expanded="false" aria-controls="sidebarInspections">
                                <i class="ri-search-eye-line"></i> <span data-key="t-inspections">Vehicle Inspections</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarInspections">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-key="t-new-inspection">
                                            <i class="ri-add-line me-2"></i>New Inspection
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-key="t-pending-approvals">
                                            <i class="ri-time-line me-2"></i>Pending Approvals
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" data-key="t-inspection-reports">
                                            <i class="ri-file-list-line me-2"></i>Inspection Reports
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
