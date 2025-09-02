@extends('layout.main')
@section('title', 'Vehicle Parts')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicle Parts</h5>
                    <div class="float-end">
                        @can('create_vehicle_parts')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#js-add-vehicle-part-modal">
                                Add New Part
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-vehicle-part-table"
                            class="table table-bordered table-striped align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th style="min-width: 50px;" class="text-center">#</th>
                                    <th style="min-width: 150px;">Part Name</th>
                                    <th style="min-width: 120px;">Slug</th>
                                    <th style="min-width: 100px;">Status</th>
                                    <th style="min-width: 120px;">Created At</th>
                                    <th style="min-width: 120px;">Updated At</th>
                                    <th style="min-width: 120px;" class="text-center">Actions</th>
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

    <!-- Modal starts here-->
    <div class="live-preview">
        <div class="modal fade" id="js-add-vehicle-part-modal" tabindex="-1" aria-labelledby="exampleModalgridLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="js-modal-title">Add Vehicle Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.vehicle.part.store') }}" id="js-add-vehicle-part-form" method="POST">
                            @csrf
                            <input type="text" id="js-vehiclePart-id" name="vehicle_part" value="" hidden>
                            <div class="row g-3">
                                <div class="col-xxl-6">
                                    <div>
                                        <label for="partName" class="form-label">Part Name <x-req /></label>
                                        <input type="text" class="form-control enhanced-dropdown" id="partName" name="name"
                                            placeholder="Enter part name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <div>
                                        <label for="status" class="form-label">Status <x-req /></label>
                                        <select name="is_active" id="myDropdown" class="form-control enhanced-dropdown">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"
                                            id="js-add-vehicle-part-submit">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal ends here -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            applyVehiclePartsDatatable();

            // Add vehicle part form
            $('#js-add-vehicle-part-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    is_active: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter part name",
                        minlength: "Part name must be at least 2 characters long"
                    },
                    is_active: {
                        required: "Please select status"
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    const method = 'POST'
                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                'meta[name="csrf-token"]').attr('content'));
                            $('#js-add-vehicle-part-submit').prop('disabled', true).text(
                                'Saving...');
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#js-vehicle-part-table').DataTable().ajax.reload();
                                $('#js-add-vehicle-part-modal').modal('hide');
                                $('#js-add-vehicle-part-form')[0].reset();
                                $('#js-modal-title').text('Add Vehicle Part');
                                $('#js-vehiclePart-id').val('');
                                $('#js-add-vehicle-part-submit').text('Add');
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error('An error occurred. Please try again.');
                            }
                        },
                        complete: function() {
                            $('#js-add-vehicle-part-submit').prop('disabled', false).text(
                                'Add');
                        }
                    });
                }
            });

            // Edit vehicle part
            $(document).on('click', '.edit-vehicle-part-btn', function(e) {
                e.preventDefault();

                let id = $(this).data('id'); // get id from data-id
                let url = "{{ route('admin.vehicle.part.edit', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(response) {
                        if (response.success) {
                            var vehiclePart = response.vehiclePart;
                            $('#js-modal-title').text('Edit Vehicle Part');
                            $('#js-vehiclePart-id').val(vehiclePart.id);
                            $('#partName').val(vehiclePart.name);
                            $('#myDropdown').val(vehiclePart.is_active);
                            $('#js-add-vehicle-part-modal').modal('show');
                            $('#js-add-vehicle-part-submit').text('Update');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 404) {
                            toastr.error("Vehicle part not found.");
                        } else {
                            toastr.error("An error occurred while fetching vehicle part data.");
                        }
                    }
                });
            });

            // Delete vehicle part
            $(document).on('click', '.delete-vehicle-part-btn', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                 let deleteUrl = "{{ route('admin.vehicle.part.destroy', ':id') }}";
                 deleteUrl = deleteUrl.replace(':id', id);
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this Vehicle Part!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: "DELETE",
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                    'meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#js-vehicle-part-table').DataTable().ajax
                                        .reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error(
                                    "Failed to delete Vehicle part. Please try again."
                                );
                            }
                        });
                    }
                });
            });
        });

        function applyVehiclePartsDatatable() {
            // Configure header icons for vehicle parts
            const headerConfig = [
                { icon: 'ri-hashtag', className: 'text-center' },
                { icon: 'ri-tools-line' },
                { icon: 'ri-link' },
                { icon: 'ri-checkbox-circle-line' },
                { icon: 'ri-calendar-line' },
                { icon: 'ri-calendar-event-line' },
                { icon: 'ri-settings-line', className: 'text-center' }
            ];

            // enhanceTableHeaders('#js-vehicle-part-table', headerConfig);

            var table = $('#js-vehicle-part-table').DataTable({
                pageLength: 20,
                searching: true,
                lengthMenu: [
                    [20, 30, 50, 100],
                    ["20 entries", "30 entries", "50 entries", "100 entries"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.vehicle.part.listing') }}",
                    type: "GET"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            const start = meta.settings._iDisplayStart;
                            const pageLength = meta.settings._iDisplayLength;
                            const pageNumber = (start / pageLength) + 1;
                            return pageLength * (pageNumber - 1) + (meta.row + 1);
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "name",
                        name: "name",
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: "slug",
                        name: "slug",
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: "is_active",
                        name: "is_active",
                        render: function(data, type, row) {
                            return data == 1 ?
                                '<span class="status-badge active with-icon"><i class="ri-check-line"></i>Active</span>' :
                                '<span class="status-badge inactive with-icon"><i class="ri-close-line"></i>Inactive</span>';
                        }
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                        render: function(data, type, row) {
                            return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                        }
                    },
                    {
                        data: "updated_at",
                        name: "updated_at",
                        render: function(data, type, row) {
                            return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            let buttons = `<div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">`

                            if (row.can_edit) {
                                buttons +=
                                    `<li><a class="dropdown-item edit-vehicle-part-btn" href="#" data-id="${row.id}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>`;
                            }

                            if (row.can_delete) {
                                buttons +=
                                    `<li><a class="dropdown-item delete-vehicle-part-btn" href="#" data-id="${row.id}"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>`;
                            }

                            buttons += `</ul></div>`;
                            return buttons;

                        }
                    }
                ],
                order: [
                    [4, 'desc']
                ]
            });
        }
    </script>
@endsection
