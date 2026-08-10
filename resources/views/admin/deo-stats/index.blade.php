@extends('layout.main')
@section('title', 'DEO Performance')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-border">
                        <h4 class="mb-sm-0">DEO Performance</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">DEO Performance</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">DEO Stats</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card border border-primary">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <i class="ri-calendar-range-line me-2"></i>Date Range Filter
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="dateRange" class="form-label">Date Range</label>
                                                    <select class="form-control" id="dateRange" name="dateRange">
                                                        <option value="today" selected>Today</option>
                                                        <option value="last_7_days">Last 7 Days</option>
                                                        <option value="last_30_days">Last 30 Days</option>
                                                        <option value="custom">Custom Range</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="dateFrom" class="form-label">From Date</label>
                                                    <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="dateTo" class="form-label">To Date</label>
                                                    <input type="date" class="form-control" id="dateTo" name="dateTo">
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary" id="apply-date-filter">
                                                            <i class="ri-search-line me-1"></i>Filter
                                                        </button>
                                                        <button type="button" class="btn btn-secondary" id="clear-date-filter">
                                                            <i class="ri-refresh-line me-1"></i>Clear
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <small class="text-muted">
                                                        <i class="ri-information-line me-1"></i>
                                                        Counts defect reports and purchase orders created by each DEO in the selected period.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive force-table-responsive table-scroll-indicator">
                                <table id="js-deo-stats-table" class="table table-bordered table-striped align-middle table-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>DEO Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Defect Reports</th>
                                            <th>Purchase Orders</th>
                                        </tr>
                                    </thead>
                                </table>
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
    $(document).ready(function () {
        const $dateRange = $('#dateRange');
        const $dateFrom = $('#dateFrom');
        const $dateTo = $('#dateTo');
        let deoStatsTable = null;

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function applyPreset(range) {
            const today = new Date();
            let from = today;
            let to = today;

            switch (range) {
                case 'today':
                    from = today;
                    to = today;
                    break;
                case 'last_7_days':
                    from = new Date(today.getTime() - (7 * 24 * 60 * 60 * 1000));
                    to = today;
                    break;
                case 'last_30_days':
                    from = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
                    to = today;
                    break;
                case 'custom':
                    return;
            }

            $dateFrom.val(formatDate(from));
            $dateTo.val(formatDate(to));
        }

        function toggleCustomDates(isCustom) {
            $dateFrom.prop('readonly', !isCustom);
            $dateTo.prop('readonly', !isCustom);
        }

        function initTable() {
            deoStatsTable = $('#js-deo-stats-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.deo-stats.listing') }}",
                    data: function (d) {
                        d.date_from = $dateFrom.val();
                        d.date_to = $dateTo.val();
                    },
                    error: function () {
                        toastr.error('Failed to load DEO stats', 'Error');
                    }
                },
                columns: [
                    { data: 'sr_no', name: 'sr_no', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'status', name: 'status', orderable: true, searchable: false },
                    { data: 'defect_reports_count', name: 'defect_reports_count', className: 'text-center' },
                    { data: 'purchase_orders_count', name: 'purchase_orders_count', className: 'text-center' }
                ],
                order: [[4, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    processing: '<i class="ri-loader-4-line me-1"></i>Loading...',
                    emptyTable: 'No DEO records found',
                    zeroRecords: 'No matching DEOs found'
                }
            });
        }

        $dateRange.on('change', function () {
            const range = $(this).val();
            toggleCustomDates(range === 'custom');
            applyPreset(range);
        });

        $('#apply-date-filter').on('click', function () {
            if (!$dateFrom.val() || !$dateTo.val()) {
                toastr.error('Please select both from and to dates', 'Validation Error');
                return;
            }

            if ($dateFrom.val() > $dateTo.val()) {
                toastr.error('From date cannot be greater than to date', 'Validation Error');
                return;
            }

            deoStatsTable.ajax.reload();
        });

        $('#clear-date-filter').on('click', function () {
            $dateRange.val('today');
            toggleCustomDates(false);
            applyPreset('today');
            deoStatsTable.ajax.reload();
        });

        applyPreset('today');
        toggleCustomDates(false);
        initTable();
    });
</script>
@endsection
