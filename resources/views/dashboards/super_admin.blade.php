@extends('layout.main')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-border">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Super Admin</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col">
                <div class="h-100">
                    <!-- Welcome Card -->
                    <div class="row mb-3 pb-1">
                        <div class="col-12">
                            <div class="card ribbon-box border shadow-none mb-lg-0">
                                <div class="card-body">
                                    <div class="ribbon-two ribbon-two-warning"><span>SUPER ADMIN</span></div>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-lg me-3">
                                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-1">
                                                <i class="ri-vip-crown-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1"><i class="ri-shield-crown-line text-warning me-2"></i>Welcome back, {{ $user->first_name }}!</h5>
                                            <p class="text-muted mb-0"><i class="ri-global-line me-1"></i>Complete system control and administration.</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <h5 class="mb-0 text-warning">
                                                        <i class="ri-vip-diamond-line me-1"></i>Super Admin
                                                    </h5>
                                                    <p class="text-muted mb-0 fs-12"><i class="ri-star-line me-1"></i>Ultimate Control</p>
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-1">
                                                        <i class="ri-crown-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Statistics -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                <i class="ri-team-line me-1"></i>Total Users
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['total_users'] }}">{{ $stats['total_users'] }}</span>
                                            </h4>
                                            <span class="badge bg-success-subtle text-success mb-0">
                                                <i class="ri-user-add-line me-1"></i>All Users
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded-circle fs-1">
                                                <i class="bx bx-user-circle text-success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                <i class="ri-user-star-line me-1"></i>Active Users
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['active_users'] }}">{{ $stats['active_users'] }}</span>
                                            </h4>
                                            <span class="status-badge active mb-0">
                                                <i class="ri-check-line me-1 text-white"></i>Active
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-1">
                                                <i class="bx bx-user-check text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                <i class="ri-shield-user-line me-1"></i>Roles
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['total_roles'] }}">{{ $stats['total_roles'] }}</span>
                                            </h4>
                                            <span class="status-badge pending mb-0">
                                                <i class="ri-group-line me-1 text-white"></i>Roles System
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded-circle fs-1">
                                                <i class="bx bx-group text-warning"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                <i class="ri-key-line me-1"></i>Permissions
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['total_permissions'] }}">{{ $stats['total_permissions'] }}</span>
                                            </h4>
                                            <span class="badge bg-primary-subtle text-primary mb-0">
                                                <i class="ri-shield-check-line me-1"></i>System Permissions
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded-circle fs-1">
                                                <i class="bx bx-shield text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0"><i class="ri-rocket-line text-warning me-2"></i>Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.user.index') }}" class="btn btn-primary w-100 btn-lg">
                                                <i class="ri-team-line me-2 fs-4"></i><br>
                                                <span class="fs-6">Manage Users</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.location.index') }}" class="btn btn-secondary w-100 btn-lg">
                                                <i class="ri-map-pin-user-line me-2 fs-4"></i><br>
                                                <span class="fs-6">Manage Locations</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.vehicle.part.index') }}" class="btn btn-info w-100 btn-lg">
                                                <i class="ri-tools-line me-2 fs-4"></i><br>
                                                <span class="fs-6">Vehicle Parts</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('profile') }}" class="btn btn-success w-100 btn-lg">
                                                <i class="ri-user-crown-line me-2 fs-4"></i><br>
                                                <span class="fs-6">My Profile</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
