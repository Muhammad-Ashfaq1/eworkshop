@extends('layout.main')
@section('title', 'Locations')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Locations / Workshop</h5>
                    <div class="float-end">
                        @can('create_locations')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#js-location-modal">
                                Add New Location
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="masters-datatable table-responsive">
                        <div class="table-wrapper">
                            <table id="js-location-table"
                                class="table table-bordered dt-responsive nowrap table-striped align-middle location-datatable"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Location</th>
                                        <th>Slug</th>
                                        <th>Type</th>
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

    <!-- Unified Location Modal -->
    <div class="modal fade" id="js-location-modal" tabindex="-1" aria-labelledby="js-location-modal-label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="js-location-modal-label">Add Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="js-location-form" action="{{ route('admin.location.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="js-location-id" name="location_id" value="">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-xxl-6">
                                <div>
                                    <label for="locationName" class="form-label">Location <x-req /></label>
                                    <input type="text" class="form-control" id="locationName" name="name"
                                        placeholder="Enter Location / Workshop Name" required>
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <div>
                                    <label for="slug" class="form-label">Slug <x-req /></label>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        placeholder="Enter Slug" required>
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <div>
                                    <label for="locationType" class="form-label">Select Location Type <x-req /></label>
                                    <select name="location_type" id="js-location-type" class="form-control" required>
                                        <option value="" selected disabled>Select Location Type</option>
                                        <option value="town">Town</option>
                                        <option value="workshop">Workshop</option>
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
                        <button type="submit" class="btn btn-primary" id="js-location-submit">Add Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            applyLocationsDatatable();
            setupFormValidation();
            setupSlugGeneration();
        });

        function applyLocationsDatatable() {
            var table = $('#js-location-table').DataTable({
                dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
                // buttons: [
                //     {
                //         text: '<i class="fas fa-plus me-2"></i>Add New',
                //         className: 'btn btn-primary',
                //         action: function (e, dt, node, config) {
                //             resetForm();
                //             $('#js-location-modal-label').text('Add Location');
                //             $('#js-location-submit').text('Add Location');
                //             $('#js-location-form').attr('action', "{{ route('admin.location.store') }}");
                //             $('#js-location-modal').modal('show');
                //         }
                //     }
                // ],
                pageLength: 20,
                searching: true,
                lengthMenu: [
                    [20, 30, 50, 100],
                    ["20 entries", "30 entries", "50 entries", "100 entries"]
                ],
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollY: '60vh',
                scrollCollapse: true,
                autoWidth: false,
                responsive: false, // Disable responsive to force scroll behavior
                deferRender: true,
                scroller: true,
                ajax: {
                    url: "/admin/location/listing",
                    type: "GET",
                    complete: function() {
                        // Force column adjustment after AJAX completes
                        setTimeout(function() {
                            if (table) {
                                if (table.columns) {
                                    table.columns.adjust();
                                }
                                if (table.fixedHeader && table.fixedHeader.adjust) {
                                    table.fixedHeader.adjust();
                                }
                            }
                        }, 100);
                    }
                },
                columns: [{
                        data: null,
                        width: '50px',
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
                        width: '150px',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: "slug",
                        width: '150px',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: "location_type",
                        width: '120px',
                        render: function(data, type, row) {
                            if (!data) return 'N/A';
                            const badgeClass = data === 'town' ? 'bg-info' : 'bg-warning';
                            const displayText = data.charAt(0).toUpperCase() + data.slice(1);
                            return `<span class="badge ${badgeClass}">${displayText}</span>`;
                        }
                    },
                    {
                        data: "is_active",
                        width: '100px',
                        render: function(data, type, row) {
                            return data == 1 ?
                                '<span class="badge bg-success">Active</span>' :
                                '<span class="badge bg-danger">Inactive</span>';
                        }
                    },
                    {
                        data: "created_at",
                        width: '120px',
                        render: function(data, type, row) {
                            return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                        }
                    },
                    {
                        data: "updated_at",
                        width: '120px',
                        render: function(data, type, row) {
                            return data ? moment(data).format('MMM DD, YYYY') : 'N/A';
                        }
                    },
                    {
                        data: null,
                        width: '100px',
                        orderable: false,
                        render: function(data, type, row) {
                            let buttons = `
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">`

                            if (row.can_edit) {
                                buttons +=
                                    `<li><a class="dropdown-item edit-location-btn" href="#" data-id="${row.id}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>`;
                            }

                            if (row.can_delete) {
                                buttons +=
                                    `<li><a class="dropdown-item delete-location-btn" href="#" data-id="${row.id}"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>`;
                            }

                            buttons += `</ul></div>`;
                            return buttons;
                        }
                    }
                ],
                order: [
                    [5, 'desc']
                ],
                initComplete: function(settings, json) {
                    // Force column adjustment after table is fully loaded
                    if (this.api().columns) {
                        this.api().columns.adjust();
                    }
                    if (this.api().fixedHeader && this.api().fixedHeader.adjust) {
                        this.api().fixedHeader.adjust();
                    }
                }
            });

            // Handle edit action
            $(document).on('click', '.edit-location-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                editLocation(id);
            });

            // Handle delete action
            $(document).on('click', '.delete-location-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                deleteLocation(id);
            });

            // Force table to show all columns after initialization
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

        function setupFormValidation() {
            $('#js-location-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    slug: {
                        required: true,
                        minlength: 2
                    },
                    location_type: {
                        required: true
                    },
                    is_active: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter location name",
                        minlength: "Location name must be at least 2 characters"
                    },
                    slug: {
                        required: "Please enter slug",
                        minlength: "Slug must be at least 2 characters"
                    },
                    location_type: {
                        required: "Please select a location type"
                    },
                    is_active: {
                        required: "Please select a status"
                    }
                },
                submitHandler: function(form) {
                    const formData = new FormData(form);
                    const url = $(form).attr('action');
                    const method ='POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                                'content'));
                            $('#js-location-submit').prop('disabled', true).text('Saving...');
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#js-location-modal').modal('hide');
                                $('#js-location-table').DataTable().ajax.reload();
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
                            $('#js-location-submit').prop('disabled', false).text('Add Location');
                        }
                    });
                }
            });
        }

        function setupSlugGeneration() {
            $('#locationName').on('input', function() {
                const name = $(this).val();
                if (name) {
                    const slug = name.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim('-');
                    $('#slug').val(slug);
                }
            });
        }

        function editLocation(id) {
            $.get(`/admin/location/edit/${id}`, function(response) {
                if (response.success) {
                    const location = response.location;
                    populateForm(location);
                    $('#js-location-modal-label').text(`Edit Location: ${location.name}`);
                    $('#js-location-submit').text('Update Location');
                    $('#js-location-form').attr('action', `/admin/location/store`);
                    $('#js-location-modal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            });
        }

        function deleteLocation(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this Location!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/location/destroy/${id}`,
                        type: "DELETE",
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                                'content'));
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#js-location-table').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error("Failed to delete Location. Please try again.");
                        }
                    });
                }
            });
        }

        function populateForm(location) {
            $('#js-location-id').val(location.id);
            $('#locationName').val(location.name);
            $('#slug').val(location.slug);
            $('#js-location-type').val(location.location_type);
            $('#js-is-active').val(location.is_active);
        }

        function resetForm() {
            $('#js-location-form')[0].reset();
            $('#js-location-id').val('');
            $('#js-location-form').attr('action', "{{ route('admin.location.store') }}");
        }
    </script>
@endsection
