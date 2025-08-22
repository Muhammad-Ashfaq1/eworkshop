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
                            <select class="form-control" id="reportType" name="reportType">
                                <option value="vehicles">Vehicles Report</option>
                                <option value="defect_reports">Defect Reports</option>
                                <option value="vehicle_parts">Vehicle Parts</option>
                                <option value="locations">Locations</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateRange" class="form-label">Date Range</label>
                            <select class="form-control" id="dateRange" name="dateRange">
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
                            <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                        </div>
                        <div class="col-md-3">
                            <label for="dateTo" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="dateTo" name="dateTo">
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
                            <input type="text" class="form-control" id="searchTerm" name="searchTerm" placeholder="Search in results...">
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
                                    <div class="table-responsive">
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
    }

    function setupEventListeners() {
        // Report type change
        $('#reportType').on('change', function() {
            currentReportType = $(this).val();
            loadDynamicFilters();
            resetToEmptyState();
        });

        // Date range change
        $('#dateRange').on('change', function() {
            handleDateRangeChange();
        });

        // Generate report button
        $('#js-generate-report-btn').on('click', function() {
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

    function loadFilterOptions() {
        // Filter options are loaded from the controller
        filterOptions = @json($filterOptions);
    }

    function initializeDataTable() {
        if (reportsDataTable) {
            reportsDataTable.destroy();
        }

        const columns = getDataTableColumns();
        
        reportsDataTable = $('#js-reports-table').DataTable({
            processing: false,
            serverSide: false, // Start with client-side to show empty state
            data: [], // Start with empty data
            columns: columns,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'asc']],
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
        // Destroy existing DataTable if it exists
        if (reportsDataTable) {
            reportsDataTable.destroy();
        }
        
        // Clear the table body first
        $('#js-reports-table tbody').empty();
        
        const columns = getDataTableColumns();
        
        reportsDataTable = $('#js-reports-table').DataTable({
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
                    return d;
                }
            },
            columns: columns,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'asc']],
                responsive: true,
                language: {
                    processing: '<i class="ri-loader-4-line me-1"></i>Loading...',
                    emptyTable: 'No data available',
                    zeroRecords: 'No matching records found'
                }
            });

            // Update results count
            reportsDataTable.on('draw', function() {
                const count = reportsDataTable.page.info().recordsDisplay;
                updateResultsCount(count);
            });
        }
    }

    function getDataTableUrl() {
        const endpoints = {
            'vehicles': "{{ route('admin.reports.vehicles.listing') }}",
            'defect_reports': "{{ route('admin.reports.defect-reports.listing') }}",
            'vehicle_parts': "{{ route('admin.reports.vehicle-parts.listing') }}",
            'locations': "{{ route('admin.reports.locations.listing') }}"
        };
        return endpoints[currentReportType] || endpoints.vehicles;
    }

    function getDataTableColumns() {
        switch(currentReportType) {
            case 'vehicles':
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
                ];
            case 'defect_reports':
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
                            return `<span class="badge bg-info">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
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
                        data: 'creator.full_name',
                        name: 'creator',
                        render: function(data, type, row) {
                            return data || 'N/A';
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
                return [
                    { data: null, name: '#', orderable: false, searchable: false },
                    { data: null, name: 'Report Data', orderable: false, searchable: false, 
                      render: function() { return 'Select report type and click "Generate Report" to view data'; } }
                ];
        }
    }

    function loadDynamicFilters() {
        const filtersContainer = $('#dynamicFilters');
        let filtersHtml = '';

        switch(currentReportType) {
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
        }

        filtersContainer.html(filtersHtml);
    }

    function generateVehicleFilters() {
        return `
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="vehicleCategory" class="form-label">Vehicle Category</label>
                    <select class="form-control" id="vehicleCategory" name="vehicleCategory">
                        <option value="">All Categories</option>
                        ${generateOptions(filterOptions.vehicles?.categories || {})}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicleLocation" class="form-label">Location</label>
                    <select class="form-control" id="vehicleLocation" name="vehicleLocation">
                        <option value="">All Locations</option>
                        ${generateOptions(filterOptions.vehicles?.locations || {})}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicleCondition" class="form-label">Condition</label>
                    <select class="form-control" id="vehicleCondition" name="vehicleCondition">
                        <option value="">All Conditions</option>
                        ${generateOptions(filterOptions.vehicles?.conditions || [])}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicleStatus" class="form-label">Status</label>
                    <select class="form-control" id="vehicleStatus" name="vehicleStatus">
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
                <div class="col-md-3">
                    <label for="defectType" class="form-label">Defect Type</label>
                    <select class="form-control" id="defectType" name="defectType">
                        <option value="">All Types</option>
                        ${generateOptions(filterOptions.defect_reports?.types || [])}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="defectVehicle" class="form-label">Vehicle</label>
                    <select class="form-control" id="defectVehicle" name="defectVehicle">
                        <option value="">All Vehicles</option>
                        ${generateOptions(filterOptions.defect_reports?.vehicles || {})}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="defectLocation" class="form-label">Location</label>
                    <select class="form-control" id="defectLocation" name="defectLocation">
                        <option value="">All Locations</option>
                        ${generateOptions(filterOptions.defect_reports?.locations || {})}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="defectCreatedBy" class="form-label">Created By</label>
                    <select class="form-control" id="defectCreatedBy" name="defectCreatedBy">
                        <option value="">All Users</option>
                        ${generateOptions(filterOptions.defect_reports?.users || {})}
                    </select>
                </div>
            </div>
        `;
    }

    function generateVehiclePartFilters() {
        return `
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="partStatus" class="form-label">Status</label>
                    <select class="form-control" id="partStatus" name="partStatus">
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
                    <select class="form-control" id="locationType" name="locationType">
                        <option value="">All Types</option>
                        ${generateOptions(filterOptions.locations?.types || [])}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="locationStatus" class="form-label">Status</label>
                    <select class="form-control" id="locationStatus" name="locationStatus">
                        <option value="">All Statuses</option>
                        ${generateOptions(filterOptions.locations?.statuses || {})}
                    </select>
                </div>
            </div>
        `;
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
                filters.type = $('#defectType').val();
                filters.vehicle_id = $('#defectVehicle').val();
                filters.location_id = $('#defectLocation').val();
                filters.created_by = $('#defectCreatedBy').val();
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
        
        // Show empty table with message
        $('#js-reports-table tbody').html('<tr><td colspan="2" class="text-center text-muted py-4">Select report type and click "Generate Report" to view data</td></tr>');
    }

    function resetToEmptyState() {
        // Destroy DataTable if it exists
        if (reportsDataTable) {
            reportsDataTable.destroy();
            reportsDataTable = null;
        }
        
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
        
        switch(range) {
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
        
        // For now, just show a success message
        // In the future, this could download a file
        toastr.success('Export functionality will be implemented soon!');
        
        // You could also make an AJAX call to the export endpoint
        // $.post("{{ route('admin.reports.export') }}", filters)
        //     .done(function(response) {
        //         // Handle export response
        //     });
    }
</script>
@endsection
