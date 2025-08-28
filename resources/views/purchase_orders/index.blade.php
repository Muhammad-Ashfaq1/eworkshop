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
                            <li class="breadcrumb-item"><a href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}">Dashboard</a></li>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purchaseOrderModal" onclick="resetForm()">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Purchase Order
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="masters-datatable table-responsive">
                            <div class="table-wrapper">
                                <table id="js-purchase-order-table" class="table table-bordered dt-responsive nowrap table-striped align-middle purchase-orders-datatable w-100" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PO Number</th>
                                            <th>Defect Report Ref</th>
                                            <th>Vehicle</th>
                                            <th>Office/Town</th>
                                            <th>Issue Date</th>
                                            <th>Received By</th>
                                            <th>Amount</th>
                                            <th>Parts Count</th>
                                            <th>Created By</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
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

<!-- Unified Purchase Order Modal -->
<div class="modal fade" id="purchaseOrderModal" tabindex="-1" aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseOrderModalLabel">Add Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="purchaseOrderForm" action="{{ route('purchase-orders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="purchase_order_id" name="purchase_order_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="defect_report_id" class="form-label">Defect Report Reference <x-req /></label>
                                <select class="form-select" id="defect_report_id" name="defect_report_id" required>
                                    <option value="" selected disabled>Select Defect Report Reference</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="po_no" class="form-label">PO Number <x-req /></label>
                                <input type="text" class="form-control" id="po_no" name="po_no" placeholder="Enter PO number" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="issue_date" class="form-label">Issue Date <x-req /></label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="received_by" class="form-label">Received By <x-req /></label>
                                <input type="text" class="form-control" id="received_by" name="received_by" placeholder="Enter who received the order" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="acc_amount" class="form-label">Account Amount <x-req /></label>
                                <input type="number" class="form-control" id="acc_amount" name="acc_amount" placeholder="Enter amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attachment_url" class="form-label">Attach File</label>
                                <input type="file" class="form-control" id="attachment_url" name="attachment_url" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 2MB</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Parts</h6>
                            <div id="parts-container">
                                <div class="part-item row mb-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Vehicle Part <x-req /></label>
                                        <select class="form-select vehicle-part-select" name="parts[0][vehicle_part_id]" required>
                                            <option value="" selected disabled>Select Vehicle Part</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Quantity <x-req /></label>
                                        <input type="number" class="form-control" name="parts[0][quantity]" placeholder="Enter quantity" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-part" style="display: none;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-sm" id="add-part">
                                    <i class="ri-add-line"></i> Add Part
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="purchaseOrderSubmit">Create Purchase Order</button>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        applyPurchaseOrdersDatatable();
        loadDropdownData();
        setupParts();
        setupFormValidation();

        // Handle modal close to reset Select2
        $('#purchaseOrderModal').on('hidden.bs.modal', function () {
            resetForm();
        });

        // Handle modal open to ensure proper state
        $('#purchaseOrderModal').on('show.bs.modal', function () {
            // If no purchase order ID is set, this is a create operation
            if (!$('#purchase_order_id').val()) {
                resetForm();
            }
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
    });

    function updateValidationRules() {
        const isEdit = $('#purchase_order_id').val() ? true : false;
        const $attachmentField = $('#attachment_url');
        
        if (isEdit) {
            // Remove required validation for attachment when editing
            $attachmentField.rules('remove', 'required');
            $attachmentField.removeClass('error');
            $attachmentField.siblings('.error').remove();
        } else {
            // Add required validation for attachment when creating new
            $attachmentField.rules('add', { required: true });
        }
    }

    function applyPurchaseOrdersDatatable() {
        var table = $('#js-purchase-order-table').DataTable({
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
            pageLength: 20,
            searching: true,
            lengthMenu: [[20, 30, 50, 100], ["20 entries", "30 entries", "50 entries", "100 entries"]],
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: '60vh',
            scrollCollapse: true,
            autoWidth: false,
            responsive: false,
            deferRender: true,
            scroller: true,
            ajax: {
                url: "{{ route('purchase-orders.listing') }}",
                type: "GET",
                complete: function() {
                    setTimeout(function() {
                        if (table) {
                            table.columns.adjust();
                            table.fixedHeader.adjust();
                        }
                    }, 100);
                }
            },
            columns: [
                {
                    data: null,
                    width: '50px',
                    render: function (data, type, row, meta) {
                        const start = meta.settings._iDisplayStart;
                        const pageLength = meta.settings._iDisplayLength;
                        const pageNumber = (start / pageLength) + 1;
                        return pageLength * (pageNumber - 1) + (meta.row + 1);
                    },
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "po_no",
                    width: '120px',
                    render: function (data, type, row) {
                        return data || 'N/A';
                    }
                },
                {
                    data: "defect_report",
                    width: '120px',
                    render: function (data, type, row) {
                        return data ? data.reference_number : 'N/A';
                    }
                },
                {
                    data: "defect_report",
                    width: '120px',
                    render: function (data, type, row) {
                        return data && data.vehicle ? data.vehicle.vehicle_number : 'N/A';
                    }
                },
                {
                    data: "defect_report",
                    width: '120px',
                    render: function (data, type, row) {
                        return data && data.location ? data.location.name : 'N/A';
                    }
                },
                {
                    data: "issue_date",
                    width: '120px',
                    render: function (data, type, row) {
                        return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                    }
                },
                {
                    data: "received_by",
                    width: '120px',
                    render: function (data, type, row) {
                        return data || 'N/A';
                    }
                },
                {
                    data: "acc_amount",
                    width: '100px',
                    render: function (data, type, row) {
                        return data ? '$' + parseFloat(data).toFixed(2) : 'N/A';
                    }
                },
                {
                    data: "works",
                    width: '100px',
                    render: function (data, type, row) {
                        return data ? `<span class="badge bg-info">${data.length}</span>` : '0';
                    }
                },
                {
                    data: "creator",
                    width: '120px',
                    render: function (data, type, row) {
                        if (data) {
                            return (data.first_name || '') + ' ' + (data.last_name || '');
                        }
                        return 'N/A';
                    }
                },
                {
                    data: null,
                    width: '100px',
                    orderable: false,
                    render: function (data, type, row) {
                        let buttons = `
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item view-purchase-order-btn" href="#" data-id="${row.id}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>`;

                        if (row.can_edit) {
                            buttons += `<li><a class="dropdown-item edit-purchase-order-btn" href="#" data-id="${row.id}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>`;
                        }

                        if (row.can_delete) {
                            buttons += `<li><a class="dropdown-item delete-purchase-order-btn" href="#" data-id="${row.id}"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>`;
                        }

                        buttons += `</ul></div>`;
                        return buttons;
                    }
                }
            ],
            order: [[5, 'desc']],
            initComplete: function(settings, json) {
                this.api().columns.adjust();
                this.api().fixedHeader.adjust();
            }
        });

        // Handle view action
        $(document).on('click', '.view-purchase-order-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            viewPurchaseOrder(id);
        });

        // Handle edit action
        $(document).on('click', '.edit-purchase-order-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
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
                table.columns.adjust().draw();
                table.fixedHeader.adjust();
            }
        }, 500);
    }

    function loadDropdownData() {
        // Load defect reports (only defect reports, not purchase orders)
        getDynamicDropdownData("{{ route('dropdown.getDefectReports') }}", '#defect_report_id');

        // Load vehicle parts
        getDynamicDropdownData("{{ route('dropdown.getVehicleParts') }}", '.vehicle-part-select');
    }

    function setupParts() {
        let partIndex = 1;

        $('#add-part').click(function() {
            const partItem = `
                <div class="part-item row mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Vehicle Part <x-req /></label>
                        <select class="form-select vehicle-part-select" name="parts[${partIndex}][vehicle_part_id]" required>
                            <option value="" selected disabled>Select Vehicle Part</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Quantity <x-req /></label>
                        <input type="number" class="form-control" name="parts[${partIndex}][quantity]" placeholder="Enter quantity" min="1" value="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-part">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#parts-container').append(partItem);
            
            // Initialize Select2 for the new part
            const newPart = $('#parts-container .part-item:last-child .vehicle-part-select');
            getDynamicDropdownData("{{ route('dropdown.getVehicleParts') }}", newPart);
            
            partIndex++;
        });

        $(document).on('click', '.remove-part', function() {
            $(this).closest('.part-item').remove();
        });
    }

    function setupFormValidation() {
        $('#purchaseOrderForm').validate({
            rules: {
                defect_report_id: { required: true },
                po_no: { required: true, minlength: 2 },
                issue_date: { required: true },
                received_by: { required: true, minlength: 2 },
                acc_amount: { required: true, min: 0 },
                'parts[0][vehicle_part_id]': { required: true },
                'parts[0][quantity]': { required: true, min: 1 }
            },
            messages: {
                defect_report_id: { required: "Please select a defect report reference" },
                po_no: { required: "Please enter PO number", minlength: "PO number must be at least 2 characters" },
                issue_date: { required: "Please select issue date" },
                received_by: { required: "Please enter who received the order", minlength: "Received by must be at least 2 characters" },
                acc_amount: { required: "Please enter account amount", min: "Account amount must be greater than or equal to 0" },
                'parts[0][vehicle_part_id]': { required: "Please select a vehicle part" },
                'parts[0][quantity]': { required: "Please enter quantity", min: "Quantity must be at least 1" }
            },
                         submitHandler: function(form) {
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
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        $('#purchaseOrderSubmit').prop('disabled', true).html('<i class="ri-loader-4-line align-bottom me-1"></i> Processing...');
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
                        $('#purchaseOrderSubmit').prop('disabled', false).html(isEdit ? 'Update Purchase Order' : 'Create Purchase Order');
                    }
                });
            }
        });
    }

    function resetForm() {
        $('#purchaseOrderForm')[0].reset();
        $('#purchase_order_id').val('');
        $('#purchaseOrderModalLabel').text('Add Purchase Order');
        $('#purchaseOrderSubmit').text('Create Purchase Order');
        $('#purchaseOrderForm').attr('action', "{{ route('purchase-orders.store') }}");
        
        // Remove all parts except the first one
        $('#parts-container .part-item:not(:first)').remove();
        
        // Reset Select2
        $('#defect_report_id').val('').trigger('change');
        $('.vehicle-part-select').val('').trigger('change');
        
        // Clear validation errors
        $('#purchaseOrderForm').find('.error').remove();
        $('#purchaseOrderForm').find('.is-invalid').removeClass('is-invalid');
    }

    function viewPurchaseOrder(id) {
        // Implement view functionality
        toastr.info('View functionality to be implemented');
    }

    function editPurchaseOrder(id) {
        $.ajax({
            url: `/purchase-orders/${id}/edit`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const po = response.purchaseOrder;
                    
                    $('#purchase_order_id').val(po.id);
                    $('#defect_report_id').val(po.defect_report_id).trigger('change');
                    $('#po_no').val(po.po_no);
                    $('#issue_date').val(po.issue_date);
                    $('#received_by').val(po.received_by);
                    $('#acc_amount').val(po.acc_amount);
                    
                    $('#purchaseOrderModalLabel').text('Edit Purchase Order');
                    $('#purchaseOrderSubmit').text('Update Purchase Order');
                    $('#purchaseOrderForm').attr('action', `/purchase-orders/${po.id}`);
                    
                    // Clear existing parts
                    $('#parts-container').empty();
                    
                    // Add parts
                    if (po.works && po.works.length > 0) {
                        po.works.forEach((work, index) => {
                            const partItem = `
                                <div class="part-item row mb-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Vehicle Part <x-req /></label>
                                        <select class="form-select vehicle-part-select" name="parts[${index}][vehicle_part_id]" required>
                                            <option value="" selected disabled>Select Vehicle Part</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Quantity <x-req /></label>
                                        <input type="number" class="form-control" name="parts[${index}][quantity]" placeholder="Enter quantity" min="1" value="${work.quantity}" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-part" ${index === 0 ? 'style="display: none;"' : ''}>
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            $('#parts-container').append(partItem);
                            
                            // Initialize Select2 and set value
                            const newPart = $(`#parts-container .part-item:eq(${index}) .vehicle-part-select`);
                            getDynamicDropdownData("{{ route('dropdown.getVehicleParts') }}", newPart, function() {
                                newPart.val(work.vehicle_part_id).trigger('change');
                            });
                        });
                    }
                    
                    $('#purchaseOrderModal').modal('show');
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
</script>
@endsection
