@extends('layout.main')

@section('title', 'Purchase Orders')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-border">
                        <h4 class="mb-sm-0">Purchase Orders</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Purchase Orders</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="card-title mb-0">Purchase Orders List</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    @can('create_purchase_orders')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#purchaseOrderModal" onclick="resetForm()">
                                            <i class="ri-add-line align-bottom me-1"></i> Add Purchase Order
                                        </button>
                                    @endcan

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Date Range Filter -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card border border-primary">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <i class="ri-calendar-range-line me-2"></i>Date Range Filter
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="start_date" class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="end_date" class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
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
                                                <div class="col-md-12">
                                                    <small class="text-muted">
                                                        <i class="ri-information-line me-1"></i>
                                                        Filter purchase orders by issue date
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="no-purchase-orders-msg" class="alert alert-info text-center mb-3"
                                style="display: none;">
                                <i class="ri-information-line me-2"></i>
                                <strong>No Purchase Orders Found</strong><br>
                                <small>Start by creating a purchase order for a defect report that doesn't already have
                                    one.</small>
                            </div>

                            <!-- DataTable Controls Area (Fixed) -->
                            <div id="datatable-controls-wrapper">
                                <!-- DataTable controls will be moved here -->
                            </div>

                            <!-- Table Scroll Area -->
                            <div class="table-responsive">
                                <table id="js-purchase-order-table"
                                    class="table table-bordered table-striped align-middle table-nowrap">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 50px;" class="text-center">#</th>
                                            <th style="min-width: 120px;"><i class="ri-file-text-line me-1"></i>PO Number</th>
                                            <th style="min-width: 140px;"><i class="ri-file-copy-line me-1"></i>Defect Report Ref</th>
                                            <th style="min-width: 120px;"><i class="ri-truck-line me-1"></i>Vehicle</th>
                                            <th style="min-width: 120px;"><i class="ri-building-line me-1"></i>Office/Town</th>
                                            <th style="min-width: 120px;" data-column-type="date"><i class="ri-calendar-line me-1"></i>Issue Date</th>
                                            <th style="min-width: 120px;" data-column-type="user"><i class="ri-user-received-line me-1"></i>Received By</th>
                                            <th style="min-width: 100px;"><i class="ri-money-dollar-circle-line me-1"></i>Amount</th>
                                            <th style="min-width: 100px;" class="text-center"><i class="ri-shopping-cart-line me-1"></i>Parts Count</th>
                                            <th style="min-width: 100px;" class="text-center"><i class="ri-attachment-line me-1"></i>PO Attachment</th>
                                            <th style="min-width: 100px;" class="text-center"><i class="ri-file-damage-line me-1"></i>Defect Attachment</th>
                                            <th style="min-width: 120px;" data-column-type="user"><i class="ri-user-add-line me-1"></i>Created By</th>
                                            <th style="min-width: 120px;" class="text-center"><i class="ri-settings-line me-1"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- DataTable Info and Pagination Area (Fixed) -->
                            <div id="datatable-bottom-wrapper">
                                <!-- DataTable info and pagination will be moved here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Purchase Order Modal -->
    <div class="modal fade" id="purchaseOrderModal" tabindex="-1" aria-labelledby="purchaseOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseOrderModalLabel">Add Purchase Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="purchaseOrderForm" action="{{ route('purchase-orders.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="purchase_order_id" name="purchase_order_id" value="">
                    <input type="hidden" id="modal_mode" name="modal_mode" value="create">
                    <div class="modal-body">
                        <div class="alert alert-info mb-3" id="info-alert">
                            <i class="ri-information-line me-2"></i>
                            <strong>Note:</strong> Purchase orders can only be created for defect reports that don't already
                            have a purchase order. This ensures each defect report has only one purchase order.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="defect_report_id" class="form-label">Defect Report Reference
                                        <x-req /></label>
                                    <select class="form-select enhanced-dropdown" id="defect_report_id" name="defect_report_id" required>
                                        <option value="" selected disabled>Select Defect Report Reference</option>
                                    </select>
                                    <div class="form-text">Only defect reports without existing purchase orders are shown
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="po_no" class="form-label">PO Number <x-req /></label>
                                    <input type="text" class="form-control enhanced-dropdown" id="po_no" name="po_no"
                                        placeholder="Enter PO number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Issue Date <x-req /></label>
                                    <input type="date" class="form-control enhanced-dropdown" id="issue_date" name="issue_date"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="received_by" class="form-label">Received By <x-req /></label>
                                    <input type="text" class="form-control enhanced-dropdown" id="received_by" name="received_by"
                                        placeholder="Enter who received the order" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="acc_amount" class="form-label">Account Amount <x-req /></label>
                                    <input type="number" class="form-control enhanced-dropdown" id="acc_amount" name="acc_amount"
                                        placeholder="Enter amount" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attachment_url" class="form-label">Attach File <span id="attachment-required" class="text-danger" style="color: red" title="This field is required">*</span></label>
                                    <input type="file" class="form-control enhanced-dropdown" id="attachment_url" name="attachment_url"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 2MB
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Parts</h6>
                                <div id="parts-container">
                                    <div class="part-item row mb-3">
                                        <div class="col-md-8">
                                            <label class="form-label">Part 1 - Vehicle Part <x-req /></label>
                                            <select class="form-select vehicle-part-select"
                                                name="parts[0][vehicle_part_id]" required>
                                                <option value="" selected disabled>Select Vehicle Part</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Quantity <x-req /></label>
                                            <input type="number" class="form-control enhanced-dropdown" name="parts[0][quantity]"
                                                placeholder="Enter quantity" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-part"
                                                style="display: none;">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-3" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                    <button type="button" class="btn btn-success btn-sm" id="add-part">
                                        <i class="ri-add-line"></i> Add Part
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex flex-wrap gap-2 justify-content-end w-100">
                            <button type="button" class="btn btn-secondary flex-fill flex-sm-grow-0" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>Close
                            </button>
                            <button type="submit" class="btn btn-primary flex-fill flex-sm-grow-0" id="purchaseOrderSubmit"
                                style="display: none;">
                                <i class="ri-save-line me-1"></i>Create Purchase Order
                            </button>
                            @can('update_purchase_orders')
                                <button type="button" class="btn btn-warning flex-fill flex-sm-grow-0" id="editPurchaseOrderBtn"
                                    style="display: none;">
                                    <i class="ri-edit-line me-1"></i>Edit Purchase Order
                                </button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/purchase-orders.css') }}">
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            applyPurchaseOrdersDatatable();
            loadDropdownData();
            setupParts();
            setupFormValidation();
            setupDateFilter();

            // Handle modal close to reset Select2
            $('#purchaseOrderModal').on('hidden.bs.modal', function() {
                resetForm();
            });

            // Handle modal open to ensure proper state
            $('#purchaseOrderModal').on('show.bs.modal', function() {
                // If no purchase order ID is set, this is a create operation
                if (!$('#purchase_order_id').val()) {
                    resetForm();
                }
                
                // Initialize all select2 dropdowns in the modal
                initializeModalSelect2();
            });

            // Check if modal should be opened automatically
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('openModal') === 'true') {
                // Clear the parameter from URL
                window.history.replaceState({}, document.title, window.location.pathname);
                // Open the modal
                resetForm();
                $('#purchaseOrderModal').modal('show');
            }

            // Handle edit button click in view mode
            $(document).on('click', '#editPurchaseOrderBtn', function() {
                setModalMode('edit');
                enableFormFields();
            });
        });

        function updateValidationRules() {
            const isEdit = $('#purchase_order_id').val() ? true : false;
            const $attachmentField = $('#attachment_url');
            const $requiredIndicator = $('#attachment-required');

            if (isEdit) {
                // Remove required validation for attachment when editing
                $attachmentField.rules('remove', 'required');
                $attachmentField.removeClass('error');
                $attachmentField.siblings('.error').remove();
                $requiredIndicator.hide();
            } else {
                // Add required validation for attachment when creating new
                $attachmentField.rules('add', {
                    required: true
                });
                $requiredIndicator.show();
            }
        }

        function applyPurchaseOrdersDatatable() {
            // Configure header icons
            const headerConfig = [
                { icon: 'ri-hashtag', className: 'text-center' },
                { icon: 'ri-file-text-line' },
                { icon: 'ri-file-copy-line' },
                { icon: 'ri-truck-line' },
                { icon: 'ri-building-line' },
                { icon: 'ri-calendar-line' },
                { icon: 'ri-user-received-line' },
                { icon: 'ri-money-dollar-circle-line' },
                { icon: 'ri-shopping-cart-line', className: 'text-center' },
                { icon: 'ri-attachment-line', className: 'text-center' },
                { icon: 'ri-file-damage-line', className: 'text-center' },
                { icon: 'ri-user-add-line' },
                { icon: 'ri-settings-line', className: 'text-center' }
            ];

            // Apply header enhancements
            // enhanceTableHeaders('#js-purchase-order-table', headerConfig);

            var table = $('#js-purchase-order-table').DataTable({
                pageLength: 20,
                searching: true,
                lengthMenu: [
                    [20, 30, 50, 100],
                    ["20 entries", "30 entries", "50 entries", "100 entries"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('purchase-orders.listing') }}",
                    type: "GET",
                    data: function(d) {
                        // Add date filter parameters
                        if ($('#start_date').val()) {
                            d.start_date = $('#start_date').val();
                        }
                        if ($('#end_date').val()) {
                            d.end_date = $('#end_date').val();
                        }
                        return d;
                    }
                },
                language: {
                    emptyTable: "No purchase orders found. Create your first purchase order by clicking the 'Add Purchase Order' button above.",
                    zeroRecords: "No purchase orders match your search criteria."
                },
                columns: [{
                        data: null,
                        name: 'serial',
                        render: function(data, type, row, meta) {
                            const start = meta.settings._iDisplayStart;
                            const pageLength = meta.settings._iDisplayLength;
                            const pageNumber = (start / pageLength) + 1;
                            return pageLength * (pageNumber - 1) + (meta.row + 1);
                        },
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: "po_no",
                        name: 'po_no',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: "defect_report",
                        name: 'defect_report_ref',
                        render: function(data, type, row) {
                            return data ? data.reference_number : 'N/A';
                        }
                    },
                    {
                        data: "defect_report",
                        name: 'defect_report.vehicle.vehicle_number',
                        render: function(data, type, row) {
                            return data && data.vehicle ? data.vehicle.vehicle_number : 'N/A';
                        },
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "defect_report",
                        name: 'defect_report.location.name',
                        render: function(data, type, row) {
                            return data && data.location ? data.location.name : 'N/A';
                        },
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "issue_date",
                        name: 'issue_date',
                        render: function(data, type, row) {
                            if (!data) return 'N/A';
                            if (type === 'sort' || type === 'type') return data;
                            return moment(data).format('MMM DD, YYYY');
                        },
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "received_by",
                        name: 'received_by',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        },
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "acc_amount",
                        name: 'acc_amount',
                        render: function(data, type, row) {
                            if (!data || data === 0) return 'N/A';
                            if (type === 'sort' || type === 'type') return parseFloat(data);
                            return parseFloat(data).toFixed(2);
                        },
                        orderable: true,
                        searchable: false,
                        className: 'text-end'
                    },
                    {
                        data: "works",
                        name: 'parts_count',
                        render: function(data, type, row) {
                            return createCountBadge(data, 'Parts');
                        },
                        className: 'text-center',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "attachment_url",
                        name: 'attachment',
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="${data}" target="_blank" class="btn btn-sm btn-outline-success"><i class="ri-eye-line me-1"></i>View</a>`;
                            }
                            return createAttachmentBadge(null);
                        },
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "defect_report",
                        name: 'defect_attachment',
                        render: function(data, type, row) {
                            if (data && data.attachment_url) {
                                return `<a href="${data.attachment_url}" target="_blank" class="btn btn-sm btn-outline-warning"><i class="ri-file-damage-line me-1"></i>View</a>`;
                            }
                            return createAttachmentBadge(null);
                        },
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "creator",
                        name: 'created_by',
                        render: function(data, type, row) {
                            if (data) {
                                return (data.first_name || '') + ' ' + (data.last_name || '');
                            }
                            return 'N/A';
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let buttons =
                                `
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item view-purchase-order-btn" href="#" data-id="${row.id}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>`;

                            if (row.can_edit) {
                                buttons +=
                                    `<li><a class="dropdown-item edit-purchase-order-btn" href="#" data-id="${row.id}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>`;
                            }

                            if (row.can_delete) {
                                buttons +=
                                    `<li><a class="dropdown-item delete-purchase-order-btn" href="#" data-id="${row.id}"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>`;
                            }

                            buttons += `</ul></div>`;
                            return buttons;
                        },
                        className: 'text-center'
                    }
                ],
                order: [
                    [5, 'desc']
                ]
            });

            // Fix DataTable controls layout using utility function
            fixDataTableControlsLayout('#js-purchase-order-table');

            // Refresh sorting icons after DataTable initialization
            setTimeout(function() {
                refreshSortingIcons('#js-purchase-order-table');
            }, 200);

            // Handle view action
            $(document).on('click', '.view-purchase-order-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                console.log('View button clicked for ID:', id); // Debug log
                viewPurchaseOrder(id);
            });

            // Handle edit action
            $(document).on('click', '.edit-purchase-order-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                console.log('Edit button clicked for ID:', id); // Debug log
                editPurchaseOrder(id);
            });

            // Handle delete action
            $(document).on('click', '.delete-purchase-order-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                deletePurchaseOrder(id);
            });

            setTimeout(function() {
                if (table) {
                    if (table.columns) {
                        table.columns.adjust().draw();
                    }
                    if (table.fixedHeader && table.fixedHeader.adjust) {
                        table.fixedHeader.adjust();
                    }
                }
            }, 500);
        }

        // Store vehicle parts data globally
        let vehiclePartsData = [];
        let defectReportsData = [];
        let defectReportsLoaded = false;

        function loadDropdownData() {
            // Load vehicle parts once and store globally
            loadVehiclePartsData();
            
            // Load defect reports for create mode (exclude those with existing POs)
            loadDefectReportsForCreate();
        }

        function initializeModalSelect2() {
            // Initialize defect report dropdown
            if ($('#defect_report_id').length && !$('#defect_report_id').hasClass('select2-hidden-accessible')) {
                $('#defect_report_id').select2({
                    placeholder: 'Select Defect Report Reference',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#purchaseOrderModal')
                });
            }
            
            // Initialize all vehicle part dropdowns
            $('.vehicle-part-select').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    populateVehiclePartDropdown($(this));
                }
            });
        }

        function loadDefectReportsForCreate() {
            if (defectReportsLoaded) {
                populateDefectReportsDropdown('#defect_report_id', 'exclude_po_id=1');
                return;
            }

            $.ajax({
                url: "{{ route('dropdown.getDefectReports') }}?exclude_po_id=1",
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        defectReportsData = response.data;
                        defectReportsLoaded = true;
                        populateDefectReportsDropdown('#defect_report_id', 'exclude_po_id=1');
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load defect reports:', xhr);
                    toastr.error('Failed to load defect reports');
                }
            });
        }

        function populateDefectReportsDropdown(selector, mode) {
            const $select = $(selector);
            $select.empty();
            $select.append('<option value="" selected disabled>Select Defect Report Reference</option>');
            
            console.log('Populating dropdown with mode:', mode, 'Data:', defectReportsData);
            
            if (mode === 'exclude_po_id=1') {
                // For create mode - show only defect reports without purchase orders
                defectReportsData.forEach(function(report) {
                    const refNumber = report.reference_number || report.text || report.name || 'N/A';
                    $select.append(`<option value="${report.id}">${refNumber}</option>`);
                });
                
                // Check if dropdown is empty
                if (defectReportsData.length === 0) {
                    $select.append('<option value="" disabled>No defect reports available for PO creation</option>');
                    $select.prop('disabled', true);
                    if (!$('#no-defect-reports-msg').length) {
                        $select.after('<div id="no-defect-reports-msg" class="form-text text-warning">No defect reports available for purchase order creation. All defect reports may already have purchase orders.</div>');
                    }
                }
            } else if (mode === 'include_po_id') {
                // For edit/view mode - show all defect reports including the current one
                defectReportsData.forEach(function(report) {
                    const refNumber = report.reference_number || report.text || report.name || 'N/A';
                    $select.append(`<option value="${report.id}">${refNumber}</option>`);
                });
                
                // Check if dropdown is empty for edit mode
                if (defectReportsData.length === 0) {
                    $select.append('<option value="" disabled>No defect reports available</option>');
                    $select.prop('disabled', true);
                    if (!$('#no-defect-reports-msg').length) {
                        $select.after('<div id="no-defect-reports-msg" class="form-text text-warning">No defect reports available for editing.</div>');
                    }
                }
            }
            
            // Initialize Select2 if not already initialized
            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    placeholder: 'Select Defect Report Reference',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#purchaseOrderModal')
                });
            }
        }

        function loadDefectReportsForEditView(poId, callback) {
            $.ajax({
                url: "{{ route('dropdown.getDefectReports') }}?include_po_id=" + poId,
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        defectReportsData = response.data;
                        defectReportsLoaded = true;
                        populateDefectReportsDropdown('#defect_report_id', 'include_po_id');
                        if (callback) callback();
                    } else {
                        console.error('No defect reports data received for edit/view mode');
                        toastr.error('Failed to load defect reports for editing');
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load defect reports for edit/view:', xhr);
                    toastr.error('Failed to load defect reports');
                }
            });
        }

        function loadVehiclePartsData() {
            $.ajax({
                url: "{{ route('dropdown.getVehicleParts') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        vehiclePartsData = response.data;
                        // Initialize the first part dropdown
                        populateVehiclePartDropdown('#parts-container .vehicle-part-select:first');
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load vehicle parts:', xhr);
                    toastr.error('Failed to load vehicle parts');
                }
            });
        }

        function populateVehiclePartDropdown(selector) {
            const $select = $(selector);
            const isDisabled = $select.prop('disabled');
            
            // Destroy existing Select2 if it exists
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
            
            $select.empty();
            $select.append('<option value="" selected disabled>Select Vehicle Part</option>');
            
            vehiclePartsData.forEach(function(part) {
                $select.append(`<option value="${part.id}">${part.name}</option>`);
            });
            
            // Initialize Select2 on the dropdown with proper modal configuration
            $select.select2({
                placeholder: 'Select Vehicle Part',
                allowClear: true,
                width: '100%',
                disabled: isDisabled,
                dropdownParent: $('#purchaseOrderModal') // Ensure dropdown appears above modal
            });
        }

        function setupParts() {
            let partIndex = 1;

            $('#add-part').click(function() {
                const partNumber = partIndex + 1;
                const partItem = `
                <div class="part-item row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Part ${partNumber} - Vehicle Part <x-req /></label>
                        <select class="form-select vehicle-part-select" name="parts[${partIndex}][vehicle_part_id]" required>
                            <option value="" selected disabled>Select Vehicle Part</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity <x-req /></label>
                        <input type="number" class="form-control enhanced-dropdown" name="parts[${partIndex}][quantity]" placeholder="Enter quantity" min="1" value="1" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-part">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            `;
                $('#parts-container').append(partItem);

                // Populate the new part dropdown with cached data
                const newPart = $('#parts-container .part-item:last-child .vehicle-part-select');
                populateVehiclePartDropdown(newPart);

                partIndex++;
                updatePartLabels();
            });

            $(document).on('click', '.remove-part', function() {
                $(this).closest('.part-item').remove();
                updatePartLabels();
            });
        }

        function updatePartLabels() {
            $('#parts-container .part-item').each(function(index) {
                const label = $(this).find('label');
                const partNumber = index + 1;
                const removeButton = $(this).find('.remove-part');
                const totalItems = $('#parts-container .part-item').length;
                
                label.html(`Part ${partNumber} - Vehicle Part <span class="text-danger" style="color: red" title="This field is required">*</span>`);
                
                // Show remove button if there's more than one item and not in view mode
                const isViewMode = $('#purchaseOrderSubmit').is(':hidden');
                if (totalItems > 1 && !isViewMode) {
                    removeButton.show();
                } else {
                    removeButton.hide();
                }
            });
        }

        function setupFormValidation() {
            $('#purchaseOrderForm').validate({
                rules: {
                    defect_report_id: {
                        required: true
                    },
                    po_no: {
                        required: true,
                        minlength: 2
                    },
                    issue_date: {
                        required: true
                    },
                    received_by: {
                        required: true,
                        minlength: 2
                    },
                    acc_amount: {
                        required: true,
                        min: 0
                    },
                    attachment_url: {
                        required: function() {
                            // Required for new records, optional for editing
                            return !$('#purchase_order_id').val();
                        }
                    },
                    'parts[0][vehicle_part_id]': {
                        required: true
                    },
                    'parts[0][quantity]': {
                        required: true,
                        min: 1
                    }
                },
                messages: {
                    defect_report_id: {
                        required: "Please select a defect report reference"
                    },
                    po_no: {
                        required: "Please enter PO number",
                        minlength: "PO number must be at least 2 characters"
                    },
                    issue_date: {
                        required: "Please select issue date"
                    },
                    received_by: {
                        required: "Please enter who received the order",
                        minlength: "Received by must be at least 2 characters"
                    },
                    acc_amount: {
                        required: "Please enter account amount",
                        min: "Account amount must be greater than or equal to 0"
                    },
                    attachment_url: {
                        required: "Please attach a file for new purchase orders"
                    },
                    'parts[0][vehicle_part_id]': {
                        required: "Please select a vehicle part"
                    },
                    'parts[0][quantity]': {
                        required: "Please enter quantity",
                        min: "Quantity must be at least 1"
                    }
                },
                submitHandler: function(form) {
                    // Don't submit if in view mode
                    if ($('#modal_mode').val() === 'view') {
                        return false;
                    }

                    // Validate all parts
                    let hasValidParts = true;
                    $('.vehicle-part-select').each(function() {
                        if (!$(this).val()) {
                            $(this).addClass('error');
                            hasValidParts = false;
                        } else {
                            $(this).removeClass('error');
                        }
                    });

                    $('input[name*="[quantity]"]').each(function() {
                        if (!$(this).val() || parseInt($(this).val()) < 1) {
                            $(this).addClass('error');
                            hasValidParts = false;
                        } else {
                            $(this).removeClass('error');
                        }
                    });

                    if (!hasValidParts) {
                        toastr.error('All parts must have a selected vehicle part and valid quantity');
                        return false;
                    }

                    const formData = new FormData(form);
                    const url = $(form).attr('action');
                    const method = $('#purchase_order_id').val() ? 'POST' : 'POST';

                    // Add method override for PUT requests
                    if ($('#purchase_order_id').val()) {
                        formData.append('_method', 'PUT');
                    }

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                                'content'));
                            $('#purchaseOrderSubmit').prop('disabled', true).html(
                                '<i class="ri-loader-4-line align-bottom me-1"></i> Processing...'
                                );
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#purchaseOrderModal').modal('hide');
                                resetForm();
                                $('#js-purchase-order-table').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.message || 'Something went wrong!');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Something went wrong!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            toastr.error(errorMessage);
                        },
                        complete: function() {
                            const isEdit = $('#purchase_order_id').val() ? true : false;
                            $('#purchaseOrderSubmit').prop('disabled', false).html(isEdit ?
                                'Update Purchase Order' : 'Create Purchase Order');
                        }
                    });
                }
            });
        }

        function resetForm() {
            $('#purchaseOrderForm')[0].reset();
            $('#purchase_order_id').val('');
            $('#modal_mode').val('create');
            $('#purchaseOrderModalLabel').text('Add Purchase Order');
            $('#purchaseOrderSubmit').text('Create Purchase Order').show();
            $('#editPurchaseOrderBtn').hide();
            $('#purchaseOrderForm').attr('action', "{{ route('purchase-orders.store') }}");

            // Remove all parts except the first one
            $('#parts-container .part-item:not(:first)').remove();
            
            // Update the first part label to show "Part 1"
            $('#parts-container .part-item:first label').html('Part 1 - Vehicle Part <span class="text-danger" style="color: red" title="This field is required">*</span>');

            // Reset Select2
            $('#defect_report_id').val('').trigger('change');
            
            // Destroy and reinitialize all vehicle part selects
            $('.vehicle-part-select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });
            
            // Re-populate the first part dropdown with cached data
            populateVehiclePartDropdown('#parts-container .vehicle-part-select:first');

            // Initialize all select2 dropdowns in the modal
            setTimeout(function() {
                initializeModalSelect2();
            }, 100);

            // Clear validation errors
            $('#purchaseOrderForm').find('.error').remove();
            $('#purchaseOrderForm').find('.is-invalid').removeClass('is-invalid');

            // Enable all form fields
            enableFormFields();

            // Show info alert for create mode
            $('#info-alert').show();

            // Update validation rules for create mode (attachment required)
            updateValidationRules();

            // Reload defect reports for create mode using cached data
            if (defectReportsLoaded) {
                populateDefectReportsDropdown('#defect_report_id', 'exclude_po_id=1');
            } else {
                loadDefectReportsForCreate();
            }
            
            // Ensure vehicle parts data is loaded
            if (vehiclePartsData.length === 0) {
                loadVehiclePartsData();
            }
        }

        function enableFormFields() {
            $('#purchaseOrderForm input, #purchaseOrderForm select, #purchaseOrderForm textarea').prop('disabled', false);
            $('#add-part').prop('disabled', false);
            $('.remove-part').prop('disabled', false);
        }

        function disableFormFields() {
            $('#purchaseOrderForm input, #purchaseOrderForm select, #purchaseOrderForm textarea').prop('disabled', true);
            $('#add-part').prop('disabled', true);
            $('.remove-part').prop('disabled', true);
        }

        function setModalMode(mode) {
            console.log('setModalMode called with mode:', mode); // Debug log
            $('#modal_mode').val(mode);

            switch (mode) {
                case 'create':
                    console.log('Setting modal to create mode'); // Debug log
                    $('#purchaseOrderModalLabel').text('Add Purchase Order');
                    $('#purchaseOrderSubmit').text('Create Purchase Order').show();
                    $('#editPurchaseOrderBtn').hide();
                    $('#info-alert').show();
                    enableFormFields();
                    updateValidationRules();
                    break;
                case 'edit':
                    console.log('Setting modal to edit mode'); // Debug log
                    $('#purchaseOrderModalLabel').text('Edit Purchase Order');
                    $('#purchaseOrderSubmit').text('Update Purchase Order').show();
                    $('#editPurchaseOrderBtn').hide();
                    $('#info-alert').hide();
                    enableFormFields();
                    updateValidationRules();
                    break;
                case 'view':
                    console.log('Setting modal to view mode'); // Debug log
                    $('#purchaseOrderModalLabel').text('View Purchase Order');
                    $('#purchaseOrderSubmit').hide();
                    $('#editPurchaseOrderBtn').show();
                    $('#info-alert').hide();
                    disableFormFields();
                    break;
            }
        }

        function viewPurchaseOrder(id) {
            $.ajax({
                url: `/purchase-orders/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const po = response.purchaseOrder;

                        // Always load defect reports for view mode to ensure we have the correct data
                        loadDefectReportsForEditView(po.id, function() {
                            populateViewForm(po);
                        });
                    } else {
                        toastr.error(response.message || 'Failed to load purchase order');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to load purchase order';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                }
            });
        }

        function populateViewForm(po) {
            // Populate form fields with data
            $('#purchase_order_id').val(po.id);
            $('#po_no').val(po.po_no);

            // Format date properly for input field (YYYY-MM-DD)
            let issueDate = po.issue_date;
            if (issueDate && typeof issueDate === 'string') {
                // If it's a date string, convert to YYYY-MM-DD format
                const date = new Date(issueDate);
                if (!isNaN(date.getTime())) {
                    issueDate = date.toISOString().split('T')[0];
                }
            }
            $('#issue_date').val(issueDate);

            $('#received_by').val(po.received_by);
            $('#acc_amount').val(po.acc_amount);

            // Set defect report ID after ensuring dropdown is loaded
            $('#defect_report_id').val(po.defect_report_id).trigger('change');

            // Clear existing parts
            $('#parts-container').empty();

            // Add parts
            if (po.works && po.works.length > 0) {
                po.works.forEach((work, index) => {
                    const partNumber = index + 1;
                    const partItem = `
                    <div class="part-item row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Part ${partNumber} - Vehicle Part</label>
                            <select class="form-select vehicle-part-select" name="parts[${index}][vehicle_part_id]" disabled>
                                <option value="" selected disabled>Select Vehicle Part</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control enhanced-dropdown" name="parts[${index}][quantity]" value="${work.quantity}" disabled>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-part" style="display: none;">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                `;
                    $('#parts-container').append(partItem);

                    // Populate dropdown with cached data and set value
                    const newPart = $(`#parts-container .part-item:eq(${index}) .vehicle-part-select`);
                    populateVehiclePartDropdown(newPart);
                    newPart.val(work.vehicle_part_id).trigger('change');
                });
            }

            // Set modal to view mode
            setModalMode('view');

            // Initialize select2 dropdowns after populating the form
            setTimeout(function() {
                initializeModalSelect2();
            }, 100);

            // Show the modal
            $('#purchaseOrderModal').modal('show');
        }

        function editPurchaseOrder(id) {
            console.log('editPurchaseOrder called with id:', id); // Debug log

            // Test if modal can be opened manually first
            console.log('Testing modal open before AJAX...');
            $('#purchaseOrderModal').modal('show');
            console.log('Modal show called in editPurchaseOrder');

            $.ajax({
                url: `/purchase-orders/${id}/edit`,
                type: 'GET',
                success: function(response) {
                    console.log('Edit response:', response); // Debug log
                    if (response.success) {
                        const po = response.purchaseOrder;
                        console.log('Purchase Order data:', po); // Debug log

                        // Always load defect reports for edit mode to ensure we have the correct data
                        loadDefectReportsForEditView(po.id, function() {
                            populateEditForm(po);
                        });
                    } else {
                        toastr.error(response.message || 'Failed to load purchase order');
                    }
                },
                error: function(xhr) {
                    console.log('Edit AJAX error:', xhr); // Debug log
                    let errorMessage = 'Failed to load purchase order';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                }
            });
        }

        function populateEditForm(po) {
            console.log('Populating edit form with:', po); // Debug log

            $('#purchase_order_id').val(po.id);
            $('#po_no').val(po.po_no);

            // Format date properly for input field (YYYY-MM-DD)
            let issueDate = po.issue_date;
            if (issueDate && typeof issueDate === 'string') {
                // If it's a date string, convert to YYYY-MM-DD format
                const date = new Date(issueDate);
                if (!isNaN(date.getTime())) {
                    issueDate = date.toISOString().split('T')[0];
                }
            }
            $('#issue_date').val(issueDate);

            $('#received_by').val(po.received_by);
            $('#acc_amount').val(po.acc_amount);

            console.log('Setting defect_report_id to:', po.defect_report_id); // Debug log
            // Set defect report ID after ensuring dropdown is loaded
            $('#defect_report_id').val(po.defect_report_id).trigger('change');

            // Set modal to edit mode
            setModalMode('edit');
            $('#purchaseOrderForm').attr('action', `/purchase-orders/${po.id}`);

            // Clear existing parts
            $('#parts-container').empty();

            // Add parts
            if (po.works && po.works.length > 0) {
                console.log('Adding parts:', po.works); // Debug log
                po.works.forEach((work, index) => {
                    const partNumber = index + 1;
                    const showRemoveButton = po.works.length > 1;
                    const partItem = `
                    <div class="part-item row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Part ${partNumber} - Vehicle Part <x-req /></label>
                            <select class="form-select vehicle-part-select" name="parts[${index}][vehicle_part_id]" required>
                                <option value="" selected disabled>Select Vehicle Part</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantity <x-req /></label>
                            <input type="number" class="form-control enhanced-dropdown" name="parts[${index}][quantity]" placeholder="Enter quantity" min="1" value="${work.quantity}" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-part" ${showRemoveButton ? '' : 'style="display: none;"'}>
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                `;
                    $('#parts-container').append(partItem);

                    // Populate dropdown with cached data and set value
                    const newPart = $(`#parts-container .part-item:eq(${index}) .vehicle-part-select`);
                    populateVehiclePartDropdown(newPart);
                    newPart.val(work.vehicle_part_id).trigger('change');
                });
            } else {
                console.log('No works found for this PO'); // Debug log
            }

            // Initialize select2 dropdowns after populating the form
            setTimeout(function() {
                initializeModalSelect2();
            }, 100);

            $('#purchaseOrderModal').modal('show');
        }

        function deletePurchaseOrder(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/purchase-orders/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#js-purchase-order-table').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.message || 'Failed to delete purchase order');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to delete purchase order';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            toastr.error(errorMessage);
                        }
                    });
                }
            });
        }

        function setupDateFilter() {
            // Apply date filter
            $('#apply-date-filter').click(function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                
                // Validate date range
                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    toastr.error('Start date cannot be greater than end date');
                    return;
                }
                
                // Reload DataTable with new filter
                $('#js-purchase-order-table').DataTable().ajax.reload();
                
                // Show success message
                if (startDate || endDate) {
                    let message = 'Filter applied';
                    if (startDate && endDate) {
                        message += ` from ${moment(startDate).format('MMM DD, YYYY')} to ${moment(endDate).format('MMM DD, YYYY')}`;
                    } else if (startDate) {
                        message += ` from ${moment(startDate).format('MMM DD, YYYY')}`;
                    } else if (endDate) {
                        message += ` until ${moment(endDate).format('MMM DD, YYYY')}`;
                    }
                    toastr.success(message);
                }
            });
            
            // Clear date filter
            $('#clear-date-filter').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#js-purchase-order-table').DataTable().ajax.reload();
                toastr.info('Date filter cleared');
            });
            
            // Allow Enter key to apply filter
            $('#start_date, #end_date').keypress(function(e) {
                if (e.which === 13) { // Enter key
                    $('#apply-date-filter').click();
                }
            });
        }
    </script>
@endsection
