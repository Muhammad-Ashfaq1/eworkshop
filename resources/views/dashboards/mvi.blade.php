@extends('layout.main')

@section('title', 'MVI Dashboard')

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
                            <li class="breadcrumb-item active">MVI</li>
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
                                    <div class="ribbon-two ribbon-two-warning"><span>MVI</span></div>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-16">
                                                <i class="ri-search-eye-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">Welcome back, {{ $user->first_name }}!</h5>
                                            <p class="text-muted mb-0">Conduct vehicle inspections and manage approval processes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Inspections</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['pending_inspections'] }}">{{ $stats['pending_inspections'] }}</span>
                                            </h4>
                                            <a href="#" class="text-decoration-underline">Start inspection</a>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                <i class="bx bx-time text-warning"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Completed Today</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['completed_today'] }}">{{ $stats['completed_today'] }}</span>
                                            </h4>
                                            <span class="badge bg-success-subtle text-success mb-0">
                                                <i class="ri-arrow-up-line align-middle"></i> Today
                                            </span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                                <i class="bx bx-check-circle text-success"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Approved</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['approved_inspections'] }}">{{ $stats['approved_inspections'] }}</span>
                                            </h4>
                                            <span class="badge bg-primary-subtle text-primary mb-0">Approved</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                <i class="bx bx-like text-primary"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Rejected</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['rejected_inspections'] }}">{{ $stats['rejected_inspections'] }}</span>
                                            </h4>
                                            <span class="badge bg-danger-subtle text-danger mb-0">Rejected</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-danger-subtle rounded fs-3">
                                                <i class="bx bx-dislike text-danger"></i>
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
                                    <h4 class="card-title mb-0">Inspection Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-primary w-100">
                                                <i class="ri-search-line me-1"></i> New Inspection
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-secondary w-100">
                                                <i class="ri-check-line me-1"></i> Approve Pending
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-info w-100">
                                                <i class="ri-file-chart-line me-1"></i> Inspection Reports
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('profile') }}" class="btn btn-success w-100">
                                                <i class="ri-user-line me-1"></i> My Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inspection Queue -->
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Inspection Queue</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View All</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-refresh-line align-bottom me-2 text-muted"></i> Refresh</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info" role="alert">
                                        <i class="ri-information-line me-2"></i>
                                        Vehicle inspection queue and management system will be available once inspection models are implemented.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Recent Activity</h4>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning" role="alert">
                                        <i class="ri-time-line me-2"></i>
                                        Recent inspection activity timeline will be displayed here.
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Inspection Guidelines</h4>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-12">
                                                    <i class="ri-check-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Safety Standards</h6>
                                                <small class="text-muted">Verify all safety requirements</small>
                                            </div>
                                        </div>
                                        <div class="list-group-item d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-12">
                                                    <i class="ri-eye-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Visual Inspection</h6>
                                                <small class="text-muted">Check external condition</small>
                                            </div>
                                        </div>
                                        <div class="list-group-item d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-12">
                                                    <i class="ri-settings-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Mechanical Check</h6>
                                                <small class="text-muted">Engine and parts inspection</small>
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
</div>
@endsection
