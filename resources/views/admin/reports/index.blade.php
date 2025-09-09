@extends('layout.main')
@section('title', 'Reports & Analytics')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reports & Analytics</h5>
                    <div class="float-end">
                        <button type="button" class="btn btn-success" id="js-export-report-btn">
                            <i class="ri-download-2-line me-1"></i>Export Report
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Report Type Selection -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="reportType" class="form-label">Report Type <x-req /></label>
                            <select class="form-control enhanced-dropdown" id="reportType" name="reportType">
                                <option value="" selected disabled>Select Report Type</option>
                                <option value="vehicles">Vehicles Report</option>
                                <option value="defect_reports">Defect Reports</option>
                                <option value="purchase_orders">Purchase Orders</option>
                                <option value="vehicle_parts">Vehicle Parts</option>
                                <option value="locations">Locations</option>
                                <option value="purchase_orders">Purchase Order</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="dateRange" class="form-label">Date Range</label>
                            <select class="form-control enhanced-dropdown" id="dateRange" name="dateRange">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="last_7_days">Last 7 Days</option>
                                <option value="last_30_days">Last 30 Days</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateFrom" class="form-label">From Date</label>
                            <input type="date" class="form-control enhanced-dropdown" id="dateFrom" name="dateFrom">
                        </div>
                        <div class="col-md-3">
                            <label for="dateTo" class="form-label">To Date</label>
                            <input type="date" class="form-control enhanced-dropdown" id="dateTo" name="dateTo">
                        </div>
                    </div>

                    <!-- Dynamic Filters Based on Report Type -->
                    <div id="dynamicFilters">
                        <!-- Filters will be loaded here based on report type -->
                    </div>

                    <!-- Search and Generate -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="searchTerm" class="form-label">Search</label>
                            <input type="text" class="form-control enhanced-dropdown" id="searchTerm" name="searchTerm"
                                placeholder="Search in results...">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary me-2" id="js-generate-report-btn">
                                <i class="ri-file-chart-line me-1"></i>Generate Report
                            </button>
                            <button type="button" class="btn btn-secondary" id="js-clear-filters-btn">
                                <i class="ri-refresh-line me-1"></i>Clear Filters
                            </button>
                        </div>
                    </div>

                    <!-- Report Results -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Report Results</h6>
                                    <div class="float-end">
                                        <span class="badge bg-secondary" id="resultsCount">No report generated</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive force-table-responsive table-scroll-indicator">
                                        <table id="js-reports-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;">#</th>
                                                    <th>Report Data</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted py-4">
                                                        Select report type and click "Generate Report" to view data
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
<<<<<<< HEAD
    <script>
        $(document).ready(function() {
            // Add a small delay to ensure DOM is fully ready
            setTimeout(function() {
                initializeReports();
                setupEventListeners();
                loadFilterOptions();
            }, 100);
=======
<script>
    $(document).ready(function(){
        // Add a small delay to ensure DOM is fully ready
        setTimeout(function() {
            initializeReports();
            setupEventListeners();
            loadFilterOptions();
        }, 100);
    });

    let currentReportType = 'vehicles';
    let filterOptions = {};
    let reportsDataTable = null;

    function initializeReports() {
        // Set default date range
        setDefaultDateRange();
        
        // Show empty state initially (no DataTable initialization)
        showEmptyState();
        
        // Show welcome message
    }

    function setupEventListeners() {
        // Report type change
        $('#reportType').on('change', function() {
            currentReportType = $(this).val();
            loadDynamicFilters();
            resetToEmptyState();
            
            // Show info message
            const reportTypeNames = {
                'vehicles': 'Vehicles Report',
                'defect_reports': 'Defect Reports',
                'purchase_orders': 'Purchase Orders',
                'vehicle_parts': 'Vehicle Parts',
                'locations': 'Locations'
            };
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5
        });

        let currentReportType = 'vehicles';
        let filterOptions = {};
        let reportsDataTable = null;

        function initializeReports() {
            // Set default date range
            setDefaultDateRange();

            // Show empty state initially (no DataTable initialization)
            showEmptyState();

            // Show welcome message
        }

        function setupEventListeners() {
            // Report type change
            $('#reportType').on('change', function() {
                currentReportType = $(this).val();
                loadDynamicFilters();
                resetToEmptyState();

                // Show info message
                const reportTypeNames = {
                    'vehicles': 'Vehicles Report',
                    'defect_reports': 'Defect Reports',
                    'vehicle_parts': 'Vehicle Parts',
                    'locations': 'Locations',
                    'purchase_orders': 'Purchase Orders'
                };
            });

            // Date range change
            $('#dateRange').on('change', function() {
                handleDateRangeChange();

                // Show info message
                const range = $(this).val();
                if (range) {
                    const rangeNames = {
                        'today': 'Today',
                        'yesterday': 'Yesterday',
                        'last_7_days': 'Last 7 Days',
                        'last_30_days': 'Last 30 Days',
                        'this_month': 'This Month',
                        'last_month': 'Last Month',
                        'custom': 'Custom Range'
                    };
                }
            });

            // Generate report button
            $('#js-generate-report-btn').on('click', function() {
                // Check if any filters are applied
                const filters = collectFilters();
                const hasFilters = Object.values(filters).some(value => value !== '' && value !== null);
                refreshDataTable();
            });

            // Clear filters button
            $('#js-clear-filters-btn').on('click', function() {
                clearFilters();
            });

            // Export report button
            $('#js-export-report-btn').on('click', function() {
                exportReport();
            });
        }

<<<<<<< HEAD
        function loadFilterOptions() {
            // Filter options are loaded from the controller
            filterOptions = @json($filterOptions);
        }
=======
    function getDataTableUrl() {
        const endpoints = {
            'vehicles': "{{ route('admin.reports.vehicles.listing') }}",
            'defect_reports': "{{ route('admin.reports.defect-reports.listing') }}",
            'purchase_orders': "{{ route('admin.reports.purchase-orders.listing') }}",
            'vehicle_parts': "{{ route('admin.reports.vehicle-parts.listing') }}",
            'locations': "{{ route('admin.reports.locations.listing') }}"
        };
        return endpoints[currentReportType] || endpoints.vehicles;
    }
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5

        function initializeDataTable() {
            if (reportsDataTable) {
                reportsDataTable.destroy();
            }

            const columns = getDataTableColumns();

            reportsDataTable = $('#js-reports-table').DataTable({
                scrollX: false,

                scrollCollapse: false,


                processing: false,
                serverSide: false, // Start with client-side to show empty state
                data: [], // Start with empty data
                columns: columns,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [0, 'asc']
                ],
                responsive: true,
                language: {
                    processing: '<i class="ri-loader-4-line me-1"></i>Loading...',
                    emptyTable: 'No report data available. Please select filters and click "Generate Report" to view data.',
                    zeroRecords: 'No matching records found'
                }
            });

            // Update results count
            reportsDataTable.on('draw', function() {
                const count = reportsDataTable.page.info().recordsDisplay;
                updateResultsCount(count);
            });
        }

        function refreshDataTable() {
            try {
                // Show loading toastr
                toastr.info('Generating report...', 'Please wait');

                // Check if table element exists
                const tableElement = $('#js-reports-table');
                if (tableElement.length === 0) {
                    console.error('Table element not found');
                    toastr.error('Table element not found', 'Error');
                    return;
                }

                // Destroy existing DataTable if it exists
                if (reportsDataTable) {
                    try {
                        reportsDataTable.destroy();
                    } catch (e) {
                        console.warn('Error destroying existing DataTable:', e);
                    }
                }

                // Update table headers based on report type
                updateTableHeaders();

                // Clear the table body and add loading row
                tableElement.find('tbody').html(
                    '<tr><td colspan="2" class="text-center"><i class="ri-loader-4-line me-1"></i>Loading...</td></tr>');

                const columns = getDataTableColumns();

                // Initialize DataTable with error handling
                reportsDataTable = tableElement.DataTable({
                    scrollX: false,

                    scrollCollapse: false,


                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: getDataTableUrl(),
                        type: 'GET',
                        data: function(d) {
                            // Add custom filters to DataTables request
                            const filters = collectFilters();
                            Object.keys(filters).forEach(key => {
                                if (filters[key] !== '' && filters[key] !== null) {
                                    d[key] = filters[key];
                                }
                            });

                            // Debug: Log filters being sent
                            console.log('Applied filters:', filters);

                            return d;
                        },
                        error: function(xhr, error, thrown) {
                            console.error('DataTables AJAX error:', error);
                            tableElement.find('tbody').html(
                                '<tr><td colspan="2" class="text-center text-danger">Error loading data. Please try again.</td></tr>'
                            );
                            toastr.error('Failed to load report data', 'Error');
                        }
                    },
                    columns: columns,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [0, 'asc']
                    ],
                    // Disable responsive to avoid DOM manipulation issues
                    language: {
                        processing: '<i class="ri-loader-4-line me-1"></i>Loading...',
                        emptyTable: 'No data available',
                        zeroRecords: 'No matching records found'
                    },
                    drawCallback: function(settings) {
                        // Safe way to update results count
                        try {
                            const count = this.api().page.info().recordsDisplay;
                            updateResultsCount(count);

                            // Show success message
                            if (count > 0) {
                                toastr.success(`Report generated successfully with ${count} records`,
                                    'Success');
                            } else {
                                toastr.warning('Report generated but no data found for current filters',
                                    'Warning');
                            }
                        } catch (e) {
                            console.warn('Error updating results count:', e);
                        }
                    }
                });

            } catch (error) {
                console.error('Error initializing DataTable:', error);
                $('#js-reports-table tbody').html(
                    '<tr><td colspan="2" class="text-center text-danger">Error initializing table. Please refresh the page.</td></tr>'
                );
                toastr.error('Failed to initialize report table', 'Error');
            }
        }

        function getDataTableUrl() {
            const endpoints = {
                'vehicles': "{{ route('admin.reports.vehicles.listing') }}",
                'defect_reports': "{{ route('admin.reports.defect-reports.listing') }}",
                'vehicle_parts': "{{ route('admin.reports.vehicle-parts.listing') }}",
                'locations': "{{ route('admin.reports.locations.listing') }}",
                'purchase_orders': "{{ route('admin.reports.purchase-order.listing') }}"
            };
            return endpoints[currentReportType] || endpoints.vehicles;
        }

        function updateTableHeaders() {
            const thead = $('#js-reports-table thead tr');
            let headers = '';

            switch (currentReportType) {
                case 'vehicles':
                    headers = `
                    <th style="width: 50px;">#</th>
                    <th>Vehicle Number</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Condition</th>
                    <th>Status</th>
                    <th>Created At</th>
                `;
                    break;
                case 'defect_reports':
                    headers = `
                    <th style="width: 50px;">#</th>
                    <th>Driver Name</th>
                    <th>Type</th>
                    <th>Vehicle</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Created By</th>
                `;
<<<<<<< HEAD
                    break;
                case 'purchase_orders':
                    headers = `
                    <th style="width: 50px;">#</th>
                    <th>Driver Name</th>
                    <th>Type</th>
                    <th>Vehicle</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Created By</th>
                `;
                    break;
                case 'vehicle_parts':
                    headers = `
=======
                break;
            case 'purchase_orders':
                headers = `
                    <th style="width: 50px;">#</th>
                    <th>PO Number</th>
                    <th>Vehicle</th>
                    <th>Location</th>
                    <th>Issue Date</th>
                    <th>Amount</th>
                    <th>Created By</th>
                `;
                break;
            case 'vehicle_parts':
                headers = `
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5
                    <th style="width: 50px;">#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                `;
                    break;
                case 'locations':
                    headers = `
                    <th style="width: 50px;">#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                `;
                    break;

                default:
                    headers = `
                    <th style="width: 50px;">#</th>
                    <th>Report Data</th>
                `;
            }

<<<<<<< HEAD
            thead.html(headers);
=======
    function getColumnCount() {
        switch(currentReportType) {
            case 'vehicles':
                return 7;
            case 'defect_reports':
                return 7;
            case 'purchase_orders':
                return 7;
            case 'vehicle_parts':
                return 5;
            case 'locations':
                return 5;
            default:
                return 2;
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5
        }

        function getColumnCount() {
            switch (currentReportType) {
                case 'vehicles':
                    return 7;
                case 'defect_reports':
                    return 7;
                case 'purchase_orders':
                    return 7;
                case 'vehicle_parts':
                    return 5;
                case 'locations':
                    return 5;
                default:
                    return 2;
            }
        }

        function getDataTableColumns() {
            switch (currentReportType) {
                case 'vehicles':
                    return [{
                            data: null,
                            name: '#',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'vehicle_number',
                            name: 'vehicle_number',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'category.name',
                            name: 'category',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'location.name',
                            name: 'location',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'condition',
                            name: 'condition',
                            render: function(data, type, row) {
                                if (!data) return 'N/A';
                                const badgeClass = data === 'new' ? 'success' : 'warning';
                                return `<span class="badge bg-${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render: function(data, type, row) {
                                const badgeClass = data ? 'success' : 'danger';
                                const text = data ? 'Active' : 'Inactive';
                                return `<span class="badge bg-${badgeClass}">${text}</span>`;
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, row) {
                                return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                            }
                        }
<<<<<<< HEAD
                    ];
                case 'defect_reports':
                    return [{
                            data: null,
                            name: '#',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'driver_name',
                            name: 'driver_name',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'type',
                            name: 'type',
                            render: function(data, type, row) {
                                if (!data) return 'N/A';
                                return `<span class="status-badge active">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            data: 'vehicle.vehicle_number',
                            name: 'vehicle',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'location.name',
                            name: 'location',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'date',
                            name: 'date',
                            render: function(data, type, row) {
                                return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                            }
                        },
                        {
                            data: 'creator',
                            name: 'creator',
                            render: function(data, type, row) {
                                if (data && data.full_name) {
                                    return data.full_name;
                                } else if (data && data.first_name && data.last_name) {
                                    return data.first_name + ' ' + data.last_name;
                                } else if (data && data.name) {
                                    return data.name;
                                } else {
                                    return 'N/A';
                                }
                            }
=======
                    }
                ];
            case 'purchase_orders':
                return [
                    {
                        data: null,
                        name: '#',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'po_no',
                        name: 'po_no',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'defect_report.vehicle.vehicle_number',
                        name: 'vehicle',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'defect_report.location.name',
                        name: 'location',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'issue_date',
                        name: 'issue_date',
                        render: function(data, type, row) {
                            return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                        }
                    },
                    {
                        data: 'acc_amount',
                        name: 'acc_amount',
                        render: function(data, type, row) {
                            return data ? parseFloat(data).toFixed(2) : 'N/A';
                        }
                    },
                    {
                        data: 'creator',
                        name: 'creator',
                        render: function(data, type, row) {
                            if (data && data.full_name) {
                                return data.full_name;
                            } else if (data && data.first_name && data.last_name) {
                                return data.first_name + ' ' + data.last_name;
                            } else if (data && data.name) {
                                return data.name;
                            } else {
                                return 'N/A';
                            }
                        }
                    }
                ];
            case 'vehicle_parts':
                return [
                    {
                        data: null,
                        name: '#',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5
                        }
                    ];

                case 'purchase_orders':
                    return [{
                            data: null,
                            name: '#',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'driver_name',
                            name: 'driver_name',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'type',
                            name: 'type',
                            render: function(data, type, row) {
                                if (!data) return 'N/A';
                                return `<span class="status-badge active">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            data: 'vehicle.vehicle_number',
                            name: 'vehicle',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'location.name',
                            name: 'location',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'date',
                            name: 'date',
                            render: function(data, type, row) {
                                return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                            }
                        },
                        {
                            data: 'creator',
                            name: 'creator',
                            render: function(data, type, row) {
                                if (data && data.full_name) {
                                    return data.full_name;
                                } else if (data && data.first_name && data.last_name) {
                                    return data.first_name + ' ' + data.last_name;
                                } else if (data && data.name) {
                                    return data.name;
                                } else {
                                    return 'N/A';
                                }
                            }
                        }
                    ];

<<<<<<< HEAD
                case 'vehicle_parts':
                    return [{
                            data: null,
                            name: '#',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'slug',
                            name: 'slug',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render: function(data, type, row) {
                                const badgeClass = data ? 'success' : 'danger';
                                const text = data ? 'Active' : 'Inactive';
                                return `<span class="badge bg-${badgeClass}">${text}</span>`;
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, row) {
                                return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                            }
                        }
                    ];
                case 'locations':
                    return [{
                            data: null,
                            name: '#',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function(data, type, row) {
                                return data || 'N/A';
                            }
                        },
                        {
                            data: 'location_type',
                            name: 'location_type',
                            render: function(data, type, row) {
                                if (!data) return 'N/A';
                                const badgeClass = data === 'town' ? 'info' : 'warning';
                                return `<span class="badge bg-${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render: function(data, type, row) {
                                const badgeClass = data ? 'success' : 'danger';
                                const text = data ? 'Active' : 'Inactive';
                                return `<span class="badge bg-${badgeClass}">${text}</span>`;
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, row) {
                                return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                            }
                        }
                    ];
                default:
                    return [{
                            data: null,
                            name: '#',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: null,
                            name: 'Report Data',
                            orderable: false,
                            searchable: false,
                            render: function() {
                                return 'Select report type and click "Generate Report" to view data';
                            }
                        }
                    ];
            }
        }

        function loadDynamicFilters() {
            const filtersContainer = $('#dynamicFilters');
            let filtersHtml = '';
=======
        switch(currentReportType) {
            case 'vehicles':
                filtersHtml = generateVehicleFilters();
                break;
            case 'defect_reports':
                filtersHtml = generateDefectReportFilters();
                break;
            case 'purchase_orders':
                filtersHtml = generatePurchaseOrderFilters();
                break;
            case 'vehicle_parts':
                filtersHtml = generateVehiclePartFilters();
                break;
            case 'locations':
                filtersHtml = generateLocationFilters();
                break;
        }

        filtersContainer.html(filtersHtml);
        
        // Initialize Select2 for vehicle dropdowns
        setTimeout(function() {
            if (currentReportType === 'purchase_orders' && $('#poVehicle').length > 0) {
                $('#poVehicle').select2({
                    placeholder: 'Select Vehicle...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#poVehicle').parent()
                });
            } else if (currentReportType === 'defect_reports' && $('#defectVehicle').length > 0) {
                $('#defectVehicle').select2({
                    placeholder: 'Select Vehicle...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#defectVehicle').parent()
                });
            }
            
            // Initialize Select2 for select dropdowns only (exclude date inputs)
            $('.enhanced-dropdown:not(input[type="date"])').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        placeholder: 'Select...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $(this).parent()
                    });
                }
            });
        }, 100);
    }
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5

            switch (currentReportType) {
                case 'vehicles':
                    filtersHtml = generateVehicleFilters();
                    break;
                case 'defect_reports':
                    filtersHtml = generateDefectReportFilters();
                    break;
                case 'vehicle_parts':
                    filtersHtml = generateVehiclePartFilters();
                    break;
                case 'locations':
                    filtersHtml = generateLocationFilters();
                    break;
                case 'purchase_orders':
                    filtersHtml = generatePurchaseOrderFilter();
                    break;
            }

            filtersContainer.html(filtersHtml);
        }

        function generateVehicleFilters() {
            return `
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="vehicleCategory" class="form-label">Vehicle Category</label>
                    <select class="form-control enhanced-dropdown" id="vehicleCategory" name="vehicleCategory">
                        <option value="">All Categories</option>
                        ${generateOptions(filterOptions.vehicles?.categories || {})}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicleLocation" class="form-label">Location</label>
                    <select class="form-control enhanced-dropdown" id="vehicleLocation" name="vehicleLocation">
                        <option value="">All Locations</option>
                        ${generateOptions(filterOptions.vehicles?.locations || {})}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicleCondition" class="form-label">Condition</label>
                    <select class="form-control enhanced-dropdown" id="vehicleCondition" name="vehicleCondition">
                        <option value="">All Conditions</option>
                        ${generateOptions(filterOptions.vehicles?.conditions || [])}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicleStatus" class="form-label">Status</label>
                    <select class="form-control enhanced-dropdown" id="vehicleStatus" name="vehicleStatus">
                        <option value="">All Statuses</option>
                        ${generateOptions(filterOptions.vehicles?.statuses || {})}
                    </select>
                </div>
            </div>
        `;
        }

          function generateDefectReportFilters() {
        return `
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="defectVehicle" class="form-label">Vehicle</label>
                    <select class="form-control enhanced-dropdown select2-vehicle" id="defectVehicle" name="defectVehicle">
                        <option value="">All Vehicles</option>
                        ${generateOptions(filterOptions.defect_reports?.vehicles || {})}
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="defectLocation" class="form-label">Location</label>
                    <select class="form-control enhanced-dropdown" id="defectLocation" name="defectLocation">
                        <option value="">All Locations</option>
                        ${generateOptions(filterOptions.defect_reports?.locations || {})}
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="defectDate" class="form-label">Date</label>
                    <input type="date" class="form-control enhanced-dropdown" id="defectDate" name="defectDate">
                </div>
            </div>
        `;
    }
<<<<<<< HEAD
     function generatePurchaseOrderFilter() {
=======

    function generatePurchaseOrderFilters() {
        return `
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="poVehicle" class="form-label">Vehicle</label>
                    <select class="form-control enhanced-dropdown select2-vehicle" id="poVehicle" name="poVehicle">
                        <option value="">All Vehicles</option>
                        ${generateOptions(filterOptions.purchase_orders?.vehicles || {})}
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="poLocation" class="form-label">Location</label>
                    <select class="form-control enhanced-dropdown" id="poLocation" name="poLocation">
                        <option value="">All Locations</option>
                        ${generateOptions(filterOptions.purchase_orders?.locations || {})}
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="poDate" class="form-label">Issue Date</label>
                    <input type="date" class="form-control enhanced-dropdown" id="poDate" name="poDate">
                </div>
            </div>
        `;
    }

    function generateVehiclePartFilters() {
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5
        return `
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="purchaseOrderVehicle" class="form-label">Vehicle</label>
                <select class="form-control enhanced-dropdown" id="purchaseOrderVehicle" name="purchaseOrderVehicle">
                    <option value="">All Vehicles</option>
                    ${generateOptions(filterOptions.defect_reports?.vehicles ||{})}
                </select>
            </div>
            <div class="col-md-4">
                <label for="purchaseOrderLocation" class="form-label">Location</label>
                <select class="form-control enhanced-dropdown" id="purchaseOrderLocation" name="purchaseOrderLocation">
                    <option value="">All Locations</option>
                    ${generateOptions(filterOptions.purchase_orders?.locations || {})}
                </select>
            </div>
            <div class="col-md-4">
                <label for="purchaseOrderDate" class="form-label">Date</label>
                <input type="date" class="form-control enhanced-dropdown" id="purchaseOrderDate" name="purchaseOrderDate">
            </div>
        </div>
    `;
}


        function generateVehiclePartFilters() {
            return `
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="partStatus" class="form-label">Status</label>
                    <select class="form-control enhanced-dropdown" id="partStatus" name="partStatus">
                        <option value="">All Statuses</option>
                        ${generateOptions(filterOptions.vehicle_parts?.statuses || {})}
                    </select>
                </div>
            </div>
        `;
        }

        function generateLocationFilters() {
            return `
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="locationType" class="form-label">Location Type</label>
                    <select class="form-control enhanced-dropdown" id="locationType" name="locationType">
                        <option value="">All Types</option>
                        ${generateOptions(filterOptions.locations?.types || [])}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="locationStatus" class="form-label">Status</label>
                    <select class="form-control enhanced-dropdown" id="locationStatus" name="locationStatus">
                        <option value="">All Statuses</option>
                        ${generateOptions(filterOptions.locations?.statuses || {})}
                    </select>
                </div>
            </div>
        `;
<<<<<<< HEAD
=======
    }

    function generateOptions(options) {
        if (Array.isArray(options)) {
            return options.map(option => `<option value="${option}">${option.charAt(0).toUpperCase() + option.slice(1)}</option>`).join('');
        } else if (typeof options === 'object') {
            return Object.entries(options).map(([value, label]) => `<option value="${value}">${label}</option>`).join('');
        }
        return '';
    }



    function collectFilters() {
        const filters = {
            report_type: currentReportType,
            date_from: $('#dateFrom').val(),
            date_to: $('#dateTo').val(),
            search: $('#searchTerm').val()
        };

        // Add dynamic filters based on report type
        switch(currentReportType) {
            case 'vehicles':
                filters.category_id = $('#vehicleCategory').val();
                filters.location_id = $('#vehicleLocation').val();
                filters.condition = $('#vehicleCondition').val();
                filters.is_active = $('#vehicleStatus').val();
                break;
            case 'defect_reports':
                filters.vehicle_id = $('#defectVehicle').val();
                filters.location_id = $('#defectLocation').val();
                filters.defect_date = $('#defectDate').val();
                break;
            case 'purchase_orders':
                filters.vehicle_id = $('#poVehicle').val();
                filters.location_id = $('#poLocation').val();
                filters.issue_date = $('#poDate').val();
                break;
            case 'vehicle_parts':
                filters.is_active = $('#partStatus').val();
                break;
            case 'locations':
                filters.location_type = $('#locationType').val();
                filters.is_active = $('#locationStatus').val();
                break;
>>>>>>> 63a2df877a837ea6ef395b67d4ae8c96e63cccb5
        }


        function generateOptions(options) {
            if (Array.isArray(options)) {
                return options.map(option =>
                    `<option value="${option}">${option.charAt(0).toUpperCase() + option.slice(1)}</option>`).join('');
            } else if (typeof options === 'object') {
                return Object.entries(options).map(([value, label]) => `<option value="${value}">${label}</option>`).join(
                    '');
            }
            return '';
        }



        function collectFilters() {
            const filters = {
                report_type: currentReportType,
                date_from: $('#dateFrom').val(),
                date_to: $('#dateTo').val(),
                search: $('#searchTerm').val()
            };

            // Add dynamic filters based on report type
            switch (currentReportType) {
                case 'vehicles':
                    filters.category_id = $('#vehicleCategory').val();
                    filters.location_id = $('#vehicleLocation').val();
                    filters.condition = $('#vehicleCondition').val();
                    filters.is_active = $('#vehicleStatus').val();
                    break;
                case 'defect_reports':
                    filters.vehicle_id = $('#defectVehicle').val();
                    filters.location_id = $('#defectLocation').val();
                    filters.defect_date = $('#defectDate').val();
                    break;
                case 'purchase_orders':
                    filters.vehicle_id = $('#purchaseOrderVehicle').val();
                    filters.location_id = $('#purchaseOrderLocation').val();
                    filters.purchase_order_date = $('#purchaseOrderDate').val();
                    break;
                case 'vehicle_parts':
                    filters.is_active = $('#partStatus').val();
                    break;
                case 'locations':
                    filters.location_type = $('#locationType').val();
                    filters.is_active = $('#locationStatus').val();
                    break;
            }

            return filters;
        }

        function updateResultsCount(count) {
            if (count > 0) {
                $('#resultsCount').text(count + ' results').removeClass('bg-secondary').addClass('bg-success');
            } else {
                $('#resultsCount').text('No data found').removeClass('bg-secondary bg-success').addClass('bg-warning');
            }
        }

        function showEmptyState() {
            // Show empty state message
            $('#resultsCount').text('No report generated').removeClass('bg-info bg-success').addClass('bg-secondary');

            // Get the correct colspan based on current report type
            const columnCount = getColumnCount();

            // Show empty table with message
            $('#js-reports-table tbody').html(
                `<tr><td colspan="${columnCount}" class="text-center text-muted py-4">Select report type and click "Generate Report" to view data</td></tr>`
            );
        }

        function resetToEmptyState() {
            // Destroy DataTable if it exists
            if (reportsDataTable) {
                reportsDataTable.destroy();
                reportsDataTable = null;
            }

            // Update headers for new report type
            updateTableHeaders();

            // Show empty state
            showEmptyState();
        }

        function clearFilters() {
            // Reset all form elements
            $('#dynamicFilters select, #dynamicFilters input').val('');
            $('#dateRange, #dateFrom, #dateTo, #searchTerm').val('');

            // Set default date range
            setDefaultDateRange();

            // Reset to empty state
            resetToEmptyState();

            // Show success message
            toastr.success('All filters have been cleared', 'Filters Reset');
        }

        function setDefaultDateRange() {
            const today = new Date();
            const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));

            $('#dateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);
            $('#dateTo').val(today.toISOString().split('T')[0]);
            $('#dateRange').val('last_30_days');
        }

        function handleDateRangeChange() {
            const range = $('#dateRange').val();
            const today = new Date();

            switch (range) {
                case 'today':
                    $('#dateFrom').val(today.toISOString().split('T')[0]);
                    $('#dateTo').val(today.toISOString().split('T')[0]);
                    break;
                case 'yesterday':
                    const yesterday = new Date(today.getTime() - (24 * 60 * 60 * 1000));
                    $('#dateFrom').val(yesterday.toISOString().split('T')[0]);
                    $('#dateTo').val(yesterday.toISOString().split('T')[0]);
                    break;
                case 'last_7_days':
                    const sevenDaysAgo = new Date(today.getTime() - (7 * 24 * 60 * 60 * 1000));
                    $('#dateFrom').val(sevenDaysAgo.toISOString().split('T')[0]);
                    $('#dateTo').val(today.toISOString().split('T')[0]);
                    break;
                case 'last_30_days':
                    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
                    $('#dateFrom').val(thirtyDaysAgo.toISOString().split('T')[0]);
                    $('#dateTo').val(today.toISOString().split('T')[0]);
                    break;
                case 'this_month':
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    $('#dateFrom').val(firstDay.toISOString().split('T')[0]);
                    $('#dateTo').val(today.toISOString().split('T')[0]);
                    break;
                case 'last_month':
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastMonthLastDay = new Date(today.getFullYear(), today.getMonth(), 0);
                    $('#dateFrom').val(lastMonth.toISOString().split('T')[0]);
                    $('#dateTo').val(lastMonthLastDay.toISOString().split('T')[0]);
                    break;
                case 'custom':
                    // Keep current custom dates
                    break;
            }
        }

        function exportReport() {
            const filters = collectFilters();

            // Show loading message
            toastr.info('Preparing CSV export...', 'Please wait');

            // Make AJAX call to export endpoint
            $.ajax({
                url: "{{ route('admin.reports.export') }}",
                type: 'POST',
                data: filters,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Create and download CSV file
                        downloadCSV(response.data, generateFileName(filters));
                        toastr.success('Export completed successfully!', 'Export');
                    } else {
                        toastr.error(response.message || 'Export failed', 'Export Error');
                    }
                },
                error: function(xhr) {
                    toastr.error('Export failed. Please try again.', 'Export Error');
                    console.error('Export error:', xhr);
                }
            });
        }

        function downloadCSV(csvContent, filename) {
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');

            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        function generateFileName(filters) {
            const timestamp = new Date().toISOString().slice(0, 10);
            const reportType = filters.report_type || 'report';

            let fileName = `${reportType}_${timestamp}`;

            // Add filter information to filename
            if (filters.vehicle_id) {
                fileName += '_vehicle_' + filters.vehicle_id;
            }
            if (filters.location_id) {
                fileName += '_location_' + filters.location_id;
            }
            if (filters.date_from) {
                fileName += '_from_' + filters.date_from;
            }
            if (filters.date_to) {
                fileName += '_to_' + filters.date_to;
            }
            if (filters.search) {
                fileName += '_search_' + filters.search.replace(/[^a-zA-Z0-9]/g, '_');
            }

            return fileName + '.csv';
        }
    </script>
@endsection
