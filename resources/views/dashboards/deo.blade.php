@extends('layout.main')

@section('title', 'DEO Dashboard')

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
                            <li class="breadcrumb-item active">DEO</li>
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
                                    <div class="ribbon-two ribbon-two-success"><span>DEO</span></div>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-16">
                                                <i class="ri-edit-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">Welcome back, {{ $user->first_name }}!</h5>
                                            <p class="text-muted mb-0">Your data entry and operational tasks dashboard.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Today's Entries</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['entries_today'] }}">{{ $stats['entries_today'] }}</span>
                                            </h4>
                                            <span class="badge bg-primary-subtle text-primary mb-0">Today</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                <i class="bx bx-edit text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Entries</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['total_entries'] }}">{{ $stats['total_entries'] }}</span>
                                            </h4>
                                            <span class="badge bg-success-subtle text-success mb-0">
                                                <i class="ri-arrow-up-line align-middle"></i> All Time
                                            </span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                                <i class="bx bx-data text-success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Tasks</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['pending_tasks'] }}">{{ $stats['pending_tasks'] }}</span>
                                            </h4>
                                            <span class="badge bg-warning-subtle text-warning mb-0">Pending</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                <i class="bx bx-task text-warning"></i>
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
                                    <h4 class="card-title mb-0">Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.location.index') }}" class="btn btn-primary w-100">
                                                <i class="ri-map-pin-line me-1"></i> Manage Locations
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-secondary w-100">
                                                <i class="ri-add-line me-1"></i> New Data Entry
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-info w-100">
                                                <i class="ri-file-chart-line me-1"></i> View Reports
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

                    <!-- Recent Work -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Recent Data Entries</h4>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="btn btn-soft-info btn-sm">
                                            <i class="ri-refresh-line align-middle"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info" role="alert">
                                        <i class="ri-information-line me-2"></i>
                                        Recent entries will be displayed here once data entry functionality is implemented.
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
