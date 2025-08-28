@extends('layout.main')
@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vehicles</h5>
                        <div class="float-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-vehicle-modal">
                                Add New Vehicle
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="masters-datatable table-responsive">
                        <div class="table-wrapper">
                            <table id="js-vehicle-table" class="table table-bordered dt-responsive nowrap table-striped align-middle vehicle-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle Number</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
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

    <!-- Unified Vehicle Modal -->
    <div class="modal fade" id="js-vehicle-modal" tabindex="-1" aria-labelledby="js-vehicle-modal-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                    <h5 class="modal-title" id="js-vehicle-modal-label">Add Vehicle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                <form id="js-vehicle-form" action="{{ route('admin.vehicle.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="js-vehicle-id" name="vehicle_id" value="">
                        <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="vehicleNumber" class="form-label">Vehicle Number <x-req /></label>
                                            <input type="text" class="form-control" id="vehicleNumber" name="vehicle_number" placeholder="Enter Vehicle Number" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="category" class="form-label">Category <x-req /></label>
                                    <select name="category" id="js-category" class="form-control" required>
                                                <option value="" selected disabled>Select Category</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="condition" class="form-label">Condition <x-req /></label>
                                    <select name="condition" id="js-condition" class="form-control" required>
                                                <option value="" selected disabled>Select Condition</option>
                                                <option value="new">New</option>
                                                <option value="old">Old</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="town" class="form-label">Town <x-req /></label>
                                    <select name="town" id="js-town" class="form-control" required>
                                                <option value="" selected disabled>Select Town</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="status" class="form-label">Status <x-req /></label>
                                    <select name="is_active" id="js-is-active" class="form-control" required>
                                        <option value="" selected disabled>Select Status</option>
                                                <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="js-vehicle-submit">Add Vehicle</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        applyVehiclesDatatable();
        loadDropdownData();
        setupFormValidation();
    });

    function applyVehiclesDatatable() {
        console.log('Initializing vehicles DataTable...');
        console.log('Table element found:', $('#js-vehicle-table').length);
        
        var table = $('#js-vehicle-table').DataTable({
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
            // buttons: [
            //     {
            //         text: '<i class="fas fa-plus me-2"></i>Add New',
            //         className: 'btn btn-primary',
            //         action: function (e, dt, node, config) {
            //             resetForm();
            //             $('#js-vehicle-modal-label').text('Add Vehicle');
            //             $('#js-vehicle-submit').text('Add Vehicle');
            //             $('#js-vehicle-form').attr('action', "{{ route('admin.vehicle.store') }}");
            //             $('#js-vehicle-modal').modal('show');
            //         }
            //     }
            // ],
            pageLength: 20,
            searching: true,
            lengthMenu: [[20, 30, 50, 100], ["20 entries", "30 entries", "50 entries", "100 entries"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.vehicle.listing') }}",
                type: "GET",
                dataSrc: function(json) {
                    console.log('AJAX response received:', json);
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX error:', error, thrown);
                    console.error('Response:', xhr.responseText);
                }
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
                    data: "vehicle_number",
                    render: function (data, type, row) {
                        return data || 'N/A';
                    }
                },
                {
                    data: "location",
                    render: function (data, type, row) {
                        return data ? data.name : 'N/A';
                    }
                },
                {
                    data: "category",
                    render: function (data, type, row) {
                        return data ? data.name : 'N/A';
                    }
                },
                {
                    data: "condition",
                    render: function (data, type, row) {
                        if (!data) return 'N/A';
                        const badgeClass = data === 'new' ? 'bg-success' : 'bg-warning';
                        const displayText = data.charAt(0).toUpperCase() + data.slice(1);
                        return `<span class="badge ${badgeClass}">${displayText}</span>`;
                    }
                },
                {
                    data: "is_active",
                    render: function (data, type, row) {
                        return data == 1 ? 
                            '<span class="badge bg-success">Active</span>' : 
                            '<span class="badge bg-danger">Inactive</span>';
                    }
                },
                {
                    data: "created_at",
                    render: function (data, type, row) {
                        return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                    }
                },
                {
                    data: "updated_at",
                    render: function (data, type, row) {
                        return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item edit-vehicle-btn" href="#" data-id="${row.id}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                    <li><a class="dropdown-item delete-vehicle-btn" href="#" data-id="${row.id}"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ],
            order: [[6, 'desc']]
        });

        // Handle edit action
        $(document).on('click', '.edit-vehicle-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            editVehicle(id);
        });

        // Handle delete action
        $(document).on('click', '.delete-vehicle-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            deleteVehicle(id);
        });
    }

    function loadDropdownData() {
        // Load vehicle categories
        $.get("{{ route('dropdown.getVehicleCategories') }}", function(data) {
            let options = '<option value="" selected disabled>Select Category</option>';
            data.forEach(function(category) {
                options += `<option value="${category.id}">${category.name}</option>`;
            });
            $('#js-category').html(options);
        });

        // Load locations/towns
        $.get("{{ route('dropdown.getTowns') }}", function(data) {
            let options = '<option value="" selected disabled>Select Town</option>';
            data.forEach(function(town) {
                options += `<option value="${town.id}">${town.name}</option>`;
            });
            $('#js-town').html(options);
        });
    }

    function setupFormValidation() {
        $('#js-vehicle-form').validate({
            rules: {
                vehicle_number: { required: true, minlength: 2 },
                category: { required: true },
                condition: { required: true },
                town: { required: true },
                is_active: { required: true }
            },
            messages: {
                vehicle_number: { required: "Please enter vehicle number", minlength: "Vehicle number must be at least 2 characters" },
                category: { required: "Please select a category" },
                condition: { required: "Please select a condition" },
                town: { required: "Please select a town" },
                is_active: { required: "Please select a status" }
            },
            submitHandler: function(form) {
                const formData = new FormData(form);
                const url = $(form).attr('action');
                const method = $('#js-vehicle-id').val() ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        $('#js-vehicle-submit').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            $('#js-vehicle-modal').modal('hide');
                            $('#js-vehicle-table').DataTable().ajax.reload();
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
                        $('#js-vehicle-submit').prop('disabled', false).text('Add Vehicle');
                    }
                });
            }
        });
    }

    function editVehicle(id) {
        $.get(`/admin/vehicles/edit/${id}`, function(response) {
            if(response.success) {
                const vehicle = response.vehicle;
                populateForm(vehicle);
                $('#js-vehicle-modal-label').text(`Edit Vehicle #${vehicle.id}`);
                $('#js-vehicle-submit').text('Update Vehicle');
                $('#js-vehicle-form').attr('action', `/admin/vehicles/${id}`);
                $('#js-vehicle-modal').modal('show');
            } else {
                toastr.error(response.message);
            }
        });
    }

    function deleteVehicle(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this Vehicle!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/vehicles/destroy/${id}`,
                    type: "DELETE",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            $('#js-vehicle-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Failed to delete Vehicle. Please try again.");
                    }
                });
            }
        });
    }

    function populateForm(vehicle) {
        $('#js-vehicle-id').val(vehicle.id);
        $('#vehicleNumber').val(vehicle.vehicle_number);
        $('#js-category').val(vehicle.vehicle_category_id);
        $('#js-condition').val(vehicle.condition);
        $('#js-town').val(vehicle.location_id);
        $('#js-is-active').val(vehicle.is_active);
    }

    function resetForm() {
        $('#js-vehicle-form')[0].reset();
        $('#js-vehicle-id').val('');
        $('#js-vehicle-form').attr('action', "{{ route('admin.vehicle.store') }}");
    }
</script>
@endsection
