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
                                        <div class="avatar-lg me-3">
                                            <div class="avatar-title bg-success-subtle text-success rounded-circle fs-1">
                                                <i class="ri-user-star-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1"><i class="ri-hand-heart-line text-primary me-2"></i>Welcome back, {{ $user->first_name }}!</h5>
                                            <p class="text-muted mb-0"><i class="ri-briefcase-4-line me-1"></i>Your data entry and operational tasks dashboard.</p>
                                        </div>
                                        @if(isset($stats['accuracy']))
                                        <div class="text-end">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <h5 class="mb-0 text-{{ $stats['accuracy']['accuracy_percentage'] >= 80 ? 'success' : ($stats['accuracy']['accuracy_percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                        <i class="ri-trophy-line me-1"></i>{{ $stats['accuracy']['accuracy_percentage'] }}%
                                                    </h5>
                                                    <p class="text-muted mb-0 fs-12"><i class="ri-target-line me-1"></i>Accuracy</p>
                                                </div>
                                                <div class="avatar-lg">
                                                    <div class="avatar-title bg-{{ $stats['accuracy']['accuracy_percentage'] >= 80 ? 'success' : ($stats['accuracy']['accuracy_percentage'] >= 60 ? 'warning' : 'danger') }}-subtle text-{{ $stats['accuracy']['accuracy_percentage'] >= 80 ? 'success' : ($stats['accuracy']['accuracy_percentage'] >= 60 ? 'warning' : 'danger') }} rounded-circle fs-1">
                                                        <i class="ri-medal-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                <i class="ri-calendar-check-line me-1"></i>Today's Defect Reports
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['defect_reports']['today'] }}">{{ $stats['defect_reports']['today'] }}</span>
                                            </h4>
                                            <span class="badge bg-primary-subtle text-primary mb-0">
                                                <i class="ri-time-line me-1"></i>Today
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle rounded-circle fs-1">
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
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                <i class="ri-calendar-check-line me-1"></i>Today's Purchase Orders
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['purchase_orders']['today'] }}">{{ $stats['purchase_orders']['today'] }}</span>
                                            </h4>
                                            <span class="badge bg-info-subtle text-info mb-0">
                                                <i class="ri-time-line me-1"></i>Today
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-1">
                                                <i class="ri-shopping-cart-line text-info"></i>
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
                                                <i class="ri-file-list-3-line me-1"></i>Total Defect Reports
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['defect_reports']['total'] }}">{{ $stats['defect_reports']['total'] }}</span>
                                            </h4>
                                            <span class="badge bg-success-subtle text-success mb-0">
                                                <i class="ri-infinity-line me-1"></i>All Time
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle rounded-circle fs-1">
                                                <i class="ri-file-chart-line text-success"></i>
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
                                                <i class="ri-shopping-bag-3-line me-1"></i>Total Purchase Orders
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value" data-target="{{ $stats['purchase_orders']['total'] }}">{{ $stats['purchase_orders']['total'] }}</span>
                                            </h4>
                                            <span class="badge bg-warning-subtle text-warning mb-0">
                                                <i class="ri-infinity-line me-1"></i>All Time
                                            </span>
                                        </div>
                                        <div class="avatar-lg flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle rounded-circle fs-1">
                                                <i class="ri-shopping-bag-line text-warning"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accuracy & Performance Statistics -->
                    @if(isset($stats['accuracy']))
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0"><i class="ri-dashboard-3-line text-primary me-2"></i>Performance Overview</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="avatar-md mx-auto mb-3">
                                                    <div class="avatar-title bg-{{ $stats['accuracy']['accuracy_percentage'] >= 80 ? 'success' : ($stats['accuracy']['accuracy_percentage'] >= 60 ? 'warning' : 'danger') }}-subtle text-{{ $stats['accuracy']['accuracy_percentage'] >= 80 ? 'success' : ($stats['accuracy']['accuracy_percentage'] >= 60 ? 'warning' : 'danger') }} rounded-circle fs-1">
                                                        <i class="ri-target-line"></i>
                                                    </div>
                                                </div>
                                                <h3 class="text-{{ $stats['accuracy']['accuracy_percentage'] >= 80 ? 'success' : ($stats['accuracy']['accuracy_percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                    <i class="ri-trophy-line me-1"></i>{{ $stats['accuracy']['accuracy_percentage'] }}%
                                                </h3>
                                                <p class="text-muted mb-0"><i class="ri-focus-3-line me-1"></i>Accuracy Rate</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="avatar-md mx-auto mb-3">
                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                                                        <i class="ri-database-2-line"></i>
                                                    </div>
                                                </div>
                                                <h3 class="text-primary"><i class="ri-file-list-3-line me-1"></i>{{ $stats['accuracy']['total_records'] }}</h3>
                                                <p class="text-muted mb-0"><i class="ri-stack-line me-1"></i>Total Records</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="avatar-md mx-auto mb-3">
                                                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-1">
                                                        <i class="ri-checkbox-circle-line"></i>
                                                    </div>
                                                </div>
                                                <h3 class="text-success"><i class="ri-check-double-line me-1"></i>{{ $stats['accuracy']['accurate_records'] }}</h3>
                                                <p class="text-muted mb-0"><i class="ri-verified-badge-line me-1"></i>Accurate Records</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="avatar-md mx-auto mb-3">
                                                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-1">
                                                        <i class="ri-edit-2-line"></i>
                                                    </div>
                                                </div>
                                                <h3 class="text-danger"><i class="ri-pencil-line me-1"></i>{{ $stats['accuracy']['edited_records'] }}</h3>
                                                <p class="text-muted mb-0"><i class="ri-admin-line me-1"></i>Admin Edited</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($stats['accuracy']['accuracy_percentage'] < 80)
                                    <div class="alert alert-warning mt-3" role="alert">
                                        <i class="ri-alert-line me-2"></i>
                                        <strong><i class="ri-information-line me-1"></i>Accuracy Notice:</strong> Your accuracy is below 80%. Please review data entry guidelines to improve accuracy.
                                    </div>
                                    @elseif($stats['accuracy']['accuracy_percentage'] >= 95)
                                    <div class="alert alert-success mt-3" role="alert">
                                        <i class="ri-award-line me-2"></i>
                                        <strong><i class="ri-star-line me-1"></i>Excellent Work!</strong> Your accuracy is excellent. Keep up the great work!
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0"><i class="ri-edit-box-line text-danger me-2"></i>Recent Admin Edits</h4>
                                </div>
                                <div class="card-body">
                                    @if(isset($stats['recent_admin_edits']) && $stats['recent_admin_edits']->count() > 0)
                                        @foreach($stats['recent_admin_edits']->take(3) as $edit)
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-12">
                                                    <i class="ri-edit-line"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fs-14">
                                                    <i class="ri-file-text-line me-1"></i>{{ ucwords(str_replace('_', ' ', $edit->record_type)) }}
                                                </h6>
                                                <p class="text-muted mb-0 fs-12">
                                                    <i class="ri-user-settings-line me-1"></i>Edited by {{ $edit->modifier->first_name ?? 'Admin' }}
                                                </p>
                                                <p class="text-muted mb-0 fs-11">
                                                    <i class="ri-time-line me-1"></i>{{ $edit->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($stats['reports_edited_by_admin'] > 3)
                                        <div class="text-center">
                                            <small class="text-muted">
                                                <i class="ri-more-line me-1"></i>+{{ $stats['reports_edited_by_admin'] - 3 }} more edits
                                            </small>
                                        </div>
                                        @endif
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="ri-check-line fs-2"></i>
                                            <p class="mb-0"><i class="ri-thumb-up-line me-1"></i>No admin edits yet!</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0"><i class="ri-flash-line text-warning me-2"></i>Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="{{ route('defect-reports.index') }}" class="btn btn-primary w-100 btn-lg">
                                                <i class="ri-file-list-2-line me-2 fs-4"></i><br>
                                                <span class="fs-6">View Defect Reports</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('defect-reports.create') }}" class="btn btn-success w-100 btn-lg">
                                                <i class="ri-file-add-line me-2 fs-4"></i><br>
                                                <span class="fs-6">New Defect Report</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-info w-100 btn-lg">
                                                <i class="ri-shopping-cart-2-line me-2 fs-4"></i><br>
                                                <span class="fs-6">Purchase Orders</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('profile') }}" class="btn btn-secondary w-100 btn-lg">
                                                <i class="ri-user-settings-line me-2 fs-4"></i><br>
                                                <span class="fs-6">My Profile</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">
                                        <i class="ri-file-history-line text-info me-2"></i>Recent Reports
                                    </h4>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('defect-reports.index') }}" class="btn btn-soft-info btn-sm me-2">
                                            <i class="ri-file-damage-line align-middle me-1"></i>Defect Reports
                                        </a>
                                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-soft-success btn-sm">
                                            <i class="ri-shopping-cart-line align-middle me-1"></i>Purchase Orders
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($recentReports->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-centered align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><i class="ri-apps-line me-1"></i>Type</th>
                                                        <th><i class="ri-hashtag me-1"></i>Reference</th>
                                                        <th><i class="ri-information-line me-1"></i>Details</th>
                                                        <th><i class="ri-calendar-line me-1"></i>Date</th>
                                                        <th><i class="ri-check-line me-1"></i>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentReports as $report)
                                                    <tr>
                                                        <td>
                                                            @if($report->report_type === 'defect_report')
                                                                <span class="badge bg-primary-subtle text-primary fs-6">
                                                                    <i class="ri-file-damage-line me-1"></i>Defect Report
                                                                </span>
                                                            @else
                                                                <span class="badge bg-info-subtle text-info fs-6">
                                                                    <i class="ri-shopping-cart-line me-1"></i>Purchase Order
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($report->report_type === 'defect_report')
                                                                <span class="fw-semibold">
                                                                    <i class="ri-file-text-line me-1"></i>#{{ $report->reference_number ?? $report->id }}
                                                                </span>
                                                            @else
                                                                <span class="fw-semibold">
                                                                    <i class="ri-receipt-line me-1"></i>#{{ $report->po_no }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($report->report_type === 'defect_report')
                                                                <div class="text-muted">
                                                                    <div><i class="ri-car-line me-1"></i>{{ $report->vehicle->vehicle_number ?? 'N/A' }}</div>
                                                                    <small><i class="ri-map-pin-line me-1"></i>{{ $report->location->name ?? 'N/A' }}</small>
                                                                </div>
                                                            @else
                                                                <div class="text-muted">
                                                                    <div><i class="ri-user-line me-1"></i>{{ $report->received_by }}</div>
                                                                    <small><i class="ri-money-dollar-circle-line me-1"></i>₹{{ number_format($report->acc_amount, 2) }}</small>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">
                                                                <i class="ri-calendar-event-line me-1"></i>{{ $report->created_at->format('d M Y') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="status-badge active with-icon">
                                                                <i class="ri-check-double-line"></i>Completed
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                                                    <i class="ri-inbox-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-2"><i class="ri-information-line me-1"></i>No reports found yet.</h5>
                                            <p class="text-muted mb-3">Start creating your first report to see it here.</p>
                                            <a href="{{ route('defect-reports.create') }}" class="btn btn-primary btn-sm me-2">
                                                <i class="ri-file-add-line me-1"></i>Create Defect Report
                                            </a>
                                        </div>
                                    @endif
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

@section('scripts')
<script>
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
