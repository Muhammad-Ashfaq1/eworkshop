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

                    <!-- Defect Reports Statistics -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Today's Reports</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $defectStats['today'] }}">{{ $defectStats['today'] }}</span>
                                            </h4>
                                            <span class="badge bg-primary-subtle text-primary mb-0">Today</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                <i class="ri-file-damage-line text-primary"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">This Week</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $defectStats['this_week'] }}">{{ $defectStats['this_week'] }}</span>
                                            </h4>
                                            <span class="badge bg-info-subtle text-info mb-0">This Week</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                                <i class="ri-calendar-week-line text-info"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">This Month</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $defectStats['this_month'] }}">{{ $defectStats['this_month'] }}</span>
                                            </h4>
                                            <span class="badge bg-success-subtle text-success mb-0">This Month</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                                <i class="ri-calendar-month-line text-success"></i>
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Reports</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $defectStats['total'] }}">{{ $defectStats['total'] }}</span>
                                            </h4>
                                            <span class="badge bg-warning-subtle text-warning mb-0">All Time</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                <i class="ri-file-chart-line text-warning"></i>
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
                                            <a href="{{ route('defect-reports.index') }}" class="btn btn-primary w-100">
                                                <i class="ri-file-damage-line me-1"></i> Defect Reports
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('defect-reports.index') }}" class="btn btn-success w-100">
                                                <i class="ri-add-line me-1"></i> New Defect Report
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-info w-100" onclick="showPurchaseOrderInfo()">
                                                <i class="ri-shopping-cart-line me-1"></i> Purchase Orders
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('profile') }}" class="btn btn-secondary w-100">
                                                <i class="ri-user-line me-1"></i> My Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Defect Reports -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Recent Defect Reports</h4>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('defect-reports.index') }}" class="btn btn-soft-info btn-sm">
                                            <i class="ri-eye-line align-middle"></i> View All
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($recentReports->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-centered align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Report ID</th>
                                                        <th>Vehicle</th>
                                                        <th>Location</th>
                                                        <th>Driver</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentReports as $report)
                                                    <tr>
                                                        <td>
                                                            <span class="fw-semibold">#{{ $report->id }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $report->vehicle->vehicle_number ?? 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $report->location->name ?? 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $report->driver_name }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $report->created_at->format('d/m/Y') }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="ri-inbox-line fs-1"></i>
                                            <p class="mt-2">No defect reports found yet.</p>
                                            <a href="{{ route('defect-reports.index') }}" class="btn btn-primary btn-sm">
                                                <i class="ri-add-line me-1"></i> Create First Report
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Future Features Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Upcoming Features</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                        <i class="ri-shopping-cart-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Purchase Order Reports</h6>
                                                    <p class="text-muted mb-2">Create and manage purchase orders for vehicle parts and supplies.</p>
                                                    <span class="badge bg-warning-subtle text-warning">Coming Soon</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-16">
                                                        <i class="ri-bar-chart-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Advanced Analytics</h6>
                                                    <p class="text-muted mb-2">Detailed reports and analytics for better decision making.</p>
                                                    <span class="badge bg-info-subtle text-info">Planned</span>
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
</div>

<!-- Purchase Order Info Modal -->
<div class="modal fade" id="purchaseOrderInfoModal" tabindex="-1" aria-labelledby="purchaseOrderInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseOrderInfoModalLabel">Purchase Order Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ri-shopping-cart-line text-info" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-center mb-3">Feature Coming Soon!</h6>
                <p class="text-muted text-center">
                    Purchase Order Reports functionality is currently under development. 
                    This feature will allow you to create and manage purchase orders for vehicle parts and supplies.
                </p>
                <div class="alert alert-info" role="alert">
                    <i class="ri-information-line me-2"></i>
                    <strong>Expected Features:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Create purchase orders for vehicle parts</li>
                        <li>Track order status and delivery</li>
                        <li>Manage supplier information</li>
                        <li>Generate purchase order reports</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showPurchaseOrderInfo() {
    $('#purchaseOrderInfoModal').modal('show');
}

// Counter animation
$(document).ready(function() {
    $('.counter-value').each(function() {
        const $this = $(this);
        const countTo = $this.attr('data-target');
        
        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 2000,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
});
</script>
@endsection
