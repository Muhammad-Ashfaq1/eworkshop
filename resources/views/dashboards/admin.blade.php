@extends('layout.main')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Admin</li>
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
                                    <div class="ribbon-two ribbon-two-primary"><span>ADMIN</span></div>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-lg me-3">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                                                <i class="ri-admin-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1"><i class="ri-shield-check-line text-primary me-2"></i>Welcome back, {{ $user->first_name }}!</h5>
                                            <p class="text-muted mb-0"><i class="ri-settings-3-line me-1"></i>Administrative control panel and system management.</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <h5 class="mb-0 text-info">
                                                        <i class="ri-shield-star-line me-1"></i>Admin
                                                    </h5>
                                                    <p class="text-muted mb-0 fs-12"><i class="ri-vip-crown-line me-1"></i>Full Access</p>
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-info-subtle text-info rounded-circle fs-1">
                                                        <i class="ri-user-star-line"></i>
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
                                                <i class="ri-map-pin-line me-1"></i>Total Locations
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['total_locations'] }}">{{ $stats['total_locations'] }}</span>
                                            </h4>
                                            <a href="{{ route('admin.location.index') }}" class="text-decoration-underline">
                                                <i class="ri-external-link-line me-1"></i>Manage locations
                                            </a>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded-circle fs-1">
                                                <i class="bx bx-map text-primary"></i>
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
                                                <i class="ri-team-line me-1"></i>Active DEOs
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['active_deos'] }}">{{ $stats['active_deos'] }}</span>
                                            </h4>
                                            <span class="status-badge active mb-0">
                                                <i class="ri-arrow-up-line align-middle"></i> Active
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded-circle fs-1">
                                                <i class="bx bx-user-pin text-success"></i>
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
                                                <i class="ri-file-list-line me-1"></i>Pending Reports
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['pending_reports'] }}">{{ $stats['pending_reports'] }}</span>
                                            </h4>
                                            <span class="status-badge pending mb-0">
                                                <i class="ri-time-line me-1 text-white"></i>Pending Review
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded-circle fs-1">
                                                <i class="bx bx-file text-warning"></i>
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
                                                <i class="ri-edit-2-line me-1"></i>Edits Today
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['reports_edited_today'] }}">{{ $stats['reports_edited_today'] }}</span>
                                            </h4>
                                            <span class="badge bg-info-subtle text-info mb-0">
                                                <i class="ri-calendar-check-line me-1 text-white"></i>Today
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-1">
                                                <i class="ri-edit-box-line text-info"></i>
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
                                    <h4 class="card-title mb-0"><i class="ri-flashlight-line text-warning me-2"></i>Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.location.index') }}" class="btn btn-primary w-100 btn-lg">
                                                <i class="ri-map-pin-user-line me-2 fs-4"></i><br>
                                                <span class="fs-6">Manage Locations</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.vehicle.part.index') }}" class="btn btn-secondary w-100 btn-lg">
                                                <i class="ri-tools-line me-2 fs-4"></i><br>
                                                <span class="fs-6">Vehicle Parts</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('defect-reports.index') }}" class="btn btn-info w-100 btn-lg">
                                                <i class="ri-bar-chart-box-line me-2 fs-4"></i><br>
                                                <span class="fs-6">View Reports</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('profile') }}" class="btn btn-success w-100 btn-lg">
                                                <i class="ri-user-settings-line me-2 fs-4"></i><br>
                                                <span class="fs-6">My Profile</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Recent System Activity</h4>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info" role="alert">
                                        <i class="ri-information-line me-2"></i>
                                        System activity tracking will be available once data entry operations are implemented.
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
