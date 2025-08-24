@extends('layout.main')

@section('title', 'Defect Reports')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-border">
                    <h4 class="mb-sm-0">Defect Reports</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Defect Reports</li>
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
                                <h4 class="card-title mb-0">Defect Reports List</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                @role('deo')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#defectReportModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Defect Report
                                </button>
                                @endrole
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="masters-datatable">
                            <table id="js-defect-report-table" class="table table-bordered dt-responsive nowrap table-striped align-middle defect-reports-datatable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vehicle</th>
                                        <th>Office/Town</th>
                                        <th>Driver Name</th>
                                        <th>Fleet Manager</th>
                                        <th>MVI</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Works Count</th>
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

<!-- Unified Defect Report Modal -->
<div class="modal fade" id="defectReportModal" tabindex="-1" aria-labelledby="defectReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defectReportModalLabel">Add Defect Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="defectReportForm" action="{{ route('defect-reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="defect_report_id" name="defect_report_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Vehicle <x-req /></label>
                                <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                    <option value="" selected disabled>Select Vehicle</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location_id" class="form-label">Office/Town <x-req /></label>
                                <select class="form-select" id="location_id" name="location_id" required>
                                    <option value="" selected disabled>Select Office/Town</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="driver_name" class="form-label">Driver Name <x-req /></label>
                                <input type="text" class="form-control" id="driver_name" name="driver_name" placeholder="Enter driver name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date <x-req /></label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fleet_manager_id" class="form-label">Fleet Manager <x-req /></label>
                                <select class="form-select" id="fleet_manager_id" name="fleet_manager_id" required>
                                    <option value="" selected disabled>Select Fleet Manager</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mvi_id" class="form-label">MVI</label>
                                <select class="form-select" id="mvi_id" name="mvi_id">
                                    <option value="" selected disabled>Select MVI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Report Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="defect_report">Defect Report</option>
                                    <option value="purchase_order">Purchase Order</option>
                                </select>
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
                            <h6 class="mb-3">Work Items</h6>
                            <div id="works-container">
                                <div class="work-item row mb-3">
                                    <div class="col-md-10">
                                        <label class="form-label">Work Description <x-req /></label>
                                        <input type="text" class="form-control work-description" name="works[0][work]" placeholder="Enter work description" maxlength="300" required>
                                        <input type="hidden" name="works[0][type]" value="defect">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-work" style="display: none;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-sm" id="add-work">
                                    <i class="ri-add-line"></i> Add Work Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="defectReportSubmit">Create Defect Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        applyDefectReportsDatatable();
        loadDropdownData();
        setupWorkItems();
        setupFormValidation();
        
        // Handle modal close to reset Select2
        $('#defectReportModal').on('hidden.bs.modal', function () {
            resetForm();
        });
    });

    function applyDefectReportsDatatable() {
        var table = $('#js-defect-report-table').DataTable({
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
            // buttons: [
            //     {
            //         text: '<i class="fas fa-plus me-2"></i>Add New',
            //         className: 'btn btn-primary',
            //         action: function (e, dt, node, config) {
            //             resetForm();
            //             $('#defectReportModalLabel').text('Add Defect Report');
            //             $('#defectReportSubmit').text('Create Defect Report');
            //             $('#defectReportForm').attr('action', "{{ route('defect-reports.store') }}");
            //             $('#defectReportModal').modal('show');
            //         }
            //     }
            // ],
            pageLength: 20,
            searching: true,
            lengthMenu: [[20, 30, 50, 100], ["20 entries", "30 entries", "50 entries", "100 entries"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('defect-reports.listing') }}",
                type: "GET"
            },
            columns: [
                {
                    data: null,
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
                    data: "vehicle",
                    render: function (data, type, row) {
                        return data ? data.vehicle_number : 'N/A';
                    }
                },
                {
                    data: "location",
                    render: function (data, type, row) {
                        return data ? data.name : 'N/A';
                    }
                },
                {
                    data: "driver_name",
                    render: function (data, type, row) {
                        return data || 'N/A';
                    }
                },
                {
                    data: "fleet_manager",
                    render: function (data, type, row) {
                        if (data) {
                            return (data.first_name || '') + ' ' + (data.last_name || '');
                        }
                        return 'N/A';
                    }
                },
                {
                    data: "mvi",
                    render: function (data, type, row) {
                        if (data) {
                            return (data.first_name || '') + ' ' + (data.last_name || '');
                        }
                        return 'N/A';
                    }
                },
                {
                    data: "date",
                    render: function (data, type, row) {
                        return data ? moment(data).format('DD/MM/YYYY') : 'N/A';
                    }
                },
                {
                    data: "type",
                    render: function (data, type, row) {
                        const badgeClass = data === 'defect_report' ? 'bg-warning' : 'bg-info';
                        const displayText = data ? data.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'N/A';
                        return `<span class="badge ${badgeClass}">${displayText}</span>`;
                    }
                },
                {
                    data: "works",
                    render: function (data, type, row) {
                        return data ? `<span class="badge bg-info">${data.length}</span>` : '0';
                    }
                },
                {
                    data: "creator",
                    render: function (data, type, row) {
                        if (data) {
                            return (data.first_name || '') + ' ' + (data.last_name || '');
                        }
                        return 'N/A';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        let buttons = `
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item view-defect-report-btn" href="#" data-id="${row.id}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>`;
                        
                        if (row.can_edit) {
                            buttons += `<li><a class="dropdown-item edit-defect-report-btn" href="#" data-id="${row.id}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>`;
                        }
                        
                        if (row.can_delete) {
                            buttons += `<li><a class="dropdown-item delete-defect-report-btn" href="#" data-id="${row.id}"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>`;
                        }
                        
                        buttons += `</ul></div>`;
                        return buttons;
                    }
                }
            ],
            order: [[6, 'desc']]
        });

        // Handle view action
        $(document).on('click', '.view-defect-report-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            viewDefectReport(id);
        });

        // Handle edit action
        $(document).on('click', '.edit-defect-report-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            editDefectReport(id);
        });

        // Handle delete action
        $(document).on('click', '.delete-defect-report-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            deleteDefectReport(id);
        });
    }

    function loadDropdownData() {
        // Load vehicles
        getDynamicDropdownData("{{ route('dropdown.getVehicles') }}", '#vehicle_id');
        
        // Load locations
        getDynamicDropdownData("{{ route('dropdown.getLocations') }}", '#location_id');
        
        // Load fleet managers
        getDynamicDropdownData("{{ route('dropdown.getFleetManagers') }}", '#fleet_manager_id');
        
        // Load MVIs
        getDynamicDropdownData("{{ route('dropdown.getMvis') }}", '#mvi_id');
    }

    function setupWorkItems() {
        let workIndex = 1;

        $('#add-work').click(function() {
            const workItem = `
                <div class="work-item row mb-3">
                    <div class="col-md-10">
                        <label class="form-label">Work Description <x-req /></label>
                        <input type="text" class="form-control work-description" name="works[${workIndex}][work]" placeholder="Enter work description" maxlength="300" required>
                        <input type="hidden" name="works[${workIndex}][type]" value="defect">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-work">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#works-container').append(workItem);
            workIndex++;
        });

        $(document).on('click', '.remove-work', function() {
            $(this).closest('.work-item').remove();
        });
    }

    function setupFormValidation() {
        $('#defectReportForm').validate({
            rules: {
                vehicle_id: { required: true },
                location_id: { required: true },
                driver_name: { required: true, minlength: 2 },
                date: { required: true },
                fleet_manager_id: { required: true },
                'works[0][work]': { required: true, minlength: 5 }
            },
            messages: {
                vehicle_id: { required: "Please select a vehicle" },
                location_id: { required: "Please select a location" },
                driver_name: { required: "Please enter driver name", minlength: "Driver name must be at least 2 characters" },
                date: { required: "Please select a date" },
                fleet_manager_id: { required: "Please select a fleet manager" },
                'works[0][work]': { required: "Please enter work description", minlength: "Work description must be at least 5 characters" }
            },
            submitHandler: function(form) {
                const formData = new FormData(form);
                const url = $(form).attr('action');
                const method = $('#defect_report_id').val() ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        $('#defectReportSubmit').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            $('#defectReportModal').modal('hide');
                            $('#js-defect-report-table').DataTable().ajax.reload();
                            resetForm();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    },
                    complete: function() {
                        $('#defectReportSubmit').prop('disabled', false).text('Create Defect Report');
                    }
                });
            }
        });
    }

    function viewDefectReport(id) {
        $.get(`/defect-reports/${id}/edit`, function(response) {
            if(response.success) {
                const report = response.defectReport;
                populateForm(report, true);
                $('#defectReportModalLabel').text(`View Defect Report #${report.id}`);
                $('#defectReportSubmit').hide();
                $('#defectReportModal').modal('show');
            } else {
                toastr.error(response.message);
            }
        });
    }

    function editDefectReport(id) {
        $.get(`/defect-reports/${id}/edit`, function(response) {
            if(response.success) {
                const report = response.defectReport;
                populateForm(report, false);
                $('#defectReportModalLabel').text(`Edit Defect Report #${report.id}`);
                $('#defectReportSubmit').text('Update Defect Report').show();
                $('#defectReportForm').attr('action', `/defect-reports/${id}`);
                $('#defectReportModal').modal('show');
            } else {
                toastr.error(response.message);
            }
        });
    }

    function deleteDefectReport(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this Defect Report!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/defect-reports/${id}`,
                    type: "DELETE",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            $('#js-defect-report-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Failed to delete Defect Report. Please try again.");
                    }
                });
            }
        });
    }

    function populateForm(report, isReadOnly) {
        $('#defect_report_id').val(report.id);
        
        // Handle Select2 dropdowns properly
        if (isReadOnly) {
            $('#vehicle_id').val(report.vehicle_id).prop('disabled', true).trigger('change');
            $('#location_id').val(report.location_id).prop('disabled', true).trigger('change');
            $('#fleet_manager_id').val(report.fleet_manager_id).prop('disabled', true).trigger('change');
            $('#mvi_id').val(report.mvi_id).prop('disabled', true).trigger('change');
        } else {
            $('#vehicle_id').val(report.vehicle_id).prop('disabled', false).trigger('change');
            $('#location_id').val(report.location_id).prop('disabled', false).trigger('change');
            $('#fleet_manager_id').val(report.fleet_manager_id).prop('disabled', false).trigger('change');
            $('#mvi_id').val(report.mvi_id).prop('disabled', false).trigger('change');
        }
        
        $('#driver_name').val(report.driver_name).prop('readonly', isReadOnly);
        $('#date').val(report.date).prop('readonly', isReadOnly);
        $('#type').val(report.type).prop('disabled', isReadOnly);
        $('#attachment_url').prop('disabled', isReadOnly);

        // Populate works
        $('#works-container').empty();
        if (report.works && report.works.length > 0) {
            report.works.forEach(function(work, index) {
                const workItem = `
                    <div class="work-item row mb-3">
                        <div class="col-md-10">
                            <label class="form-label">Work Description <x-req /></label>
                            <input type="text" class="form-control work-description" name="works[${index}][work]" value="${work.work}" readonly="${isReadOnly}" required>
                            <input type="hidden" name="works[${index}][type]" value="${work.type}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            ${!isReadOnly ? `<button type="button" class="btn btn-danger btn-sm remove-work"><i class="ri-delete-bin-line"></i></button>` : ''}
                        </div>
                    </div>
                `;
                $('#works-container').append(workItem);
            });
        }

        if (isReadOnly) {
            $('#add-work').hide();
        } else {
            $('#add-work').show();
        }
    }

    function resetForm() {
        $('#defectReportForm')[0].reset();
        $('#defect_report_id').val('');
        $('#defectReportForm').attr('action', "{{ route('defect-reports.store') }}");
        $('#works-container').html(`
            <div class="work-item row mb-3">
                <div class="col-md-10">
                    <label class="form-label">Work Description <x-req /></label>
                    <input type="text" class="form-control work-description" name="works[0][work]" placeholder="Enter work description" maxlength="300" required>
                    <input type="hidden" name="works[0][type]" value="defect">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-work" style="display: none;">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `);
        
        // Reset Select2 dropdowns
        $('#vehicle_id, #location_id, #fleet_manager_id, #mvi_id').val('').trigger('change');
        $('.form-control, .form-select').prop('disabled', false).prop('readonly', false);
        $('#add-work').show();
        $('#defectReportSubmit').show();
    }
</script>
@endsection
