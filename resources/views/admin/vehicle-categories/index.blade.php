@extends('layout.main')
@section('title', 'Vehicles Category')

@section('content')

<div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicle Categories</h5>
                    <div class="float-end">
                        @can('create_vehicle_categories')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#js-add-vehicle-categories-modal">
                                Add New Category
                            </button>
                            @endcan

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-vehicle-categories-table"
                            class="table table-bordered table-striped align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th style="min-width: 50px;" class="text-center">#</th>
                                    <th style="min-width: 150px;">Name</th>
                                    <th style="min-width: 120px;">Status</th>
                                    <th style="min-width: 120px;">Created At</th>
                                    <th style="min-width: 120px;">Updated At</th>
                                    <th style="min-width: 120px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="js-vehicle-categories-table-body">
                               @include('admin.vehicle-categories.data-table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal starts here-->
    <div class="live-preview">
        <div class="modal fade" id="js-add-vehicle-categories-modal" tabindex="-1" aria-labelledby="exampleModalgridLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="js-modal-title">Add Vehicle Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.vehicle-categories.store') }}" id="js-add-vehicle-categories-form" method="POST">
                            @csrf
                            <input type="text" id="js-vehicleCategory-id" name="vehicle_category_id" value="" hidden>
                            <div class="row g-3">
                                <div class="col-xxl-6">
                                    <div>
                                        <label for="VehicleCategoryName" class="form-label">Category Name <x-req /></label>
                                        <input type="text" class="form-control enhanced-dropdown" id="vehicleCategoryName" name="vehicle_category_name"
                                            placeholder="Enter Vechicle Category name">
                                        @error('vehicle_category_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <div>
                                        <label for="status" class="form-label">Status <x-req /></label>
                                        <select name="is_active" id="js-is-active" class="form-control enhanced-dropdown">
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                           @error('is_active')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"
                                            id="js-add-vehicle-categories-submit">Add</button>
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
@section('scripts')
<script>
$(document).ready(function(){
    // Initialize DataTable
    $('#js-vehicle-categories-table').DataTable();

    // Validate & submit form
    $('#js-add-vehicle-categories-form').validate({
        rules: {
            vehicle_category_name: {
                required: true,
                minlength: 2
            },
            is_active: {
                required: true
            }
        },
        messages: {
            vehicle_category_name: {
                required: "Please enter category name",
                minlength: "Name must be at least 2 characters long"
            },
            is_active: {
                required: "Please select a status"
            }
        },
        submitHandler: function(form) {
            var data = $(form).serialize();
            var url = $(form).attr('action');   // set action in form tag in Blade
            var method = $(form).attr('method');

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    if (response.success) {
                        // Hide modal
                        $('#js-add-vehicle-categories-modal').modal('hide');

                        // Reset form
                        $('#js-add-vehicle-categories-form')[0].reset();
                        $('#js-vehicleCategory-id').val('');
                        $('#js-add-vehicle-categories-submit').text('Add');

                        // Reload table body
                        $('#js-vehicle-categories-table-body').html(response.html);

                        toastr.success(response.message);
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
                    $('#js-add-vehicle-categories-part-submit').prop('disabled', false).text('Add Vehicle Category');
                }
            });
            return false; // prevent default
        }
    });
});

//Edit Vechicle Categories

$(document).on('click', '.edit-vehicle-categories-btn', function(e) {
    e.preventDefault();

    var url = $(this).attr('href');
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
        success: function(response) {
            if (response.success) {
                var vehicleCategory = response.data;

                // Fill modal form with fetched data
                $('#js-vehicleCategory-id').val(vehicleCategory.id);
                $('#vehicleCategoryName').val(vehicleCategory.name);
                $('#js-is-active').val(vehicleCategory.is_active);

                // Change modal title & button text for edit
                $('#js-modal-title').text('Edit Vehicle Category');
                $('#js-add-vehicle-categories-submit').text('Update');

                // Show modal
                $('#js-add-vehicle-categories-modal').modal('show');
            } else {
                toastr.error('Failed to fetch data. Please try again.');
            }
        },
        error: function(xhr) {
            toastr.error('An error occurred. Please try again.');
        }
    });

//Vehicle category delete

  $(document).on('click', '#js-vehicle-categories-delete-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
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
                        url: url,
                        type: 'DELETE',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]')
                                .attr('content'));
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#js-vehicle-categories-table-body').html();
                                $('#js-vehicle-categories-table-body').html(response.html);
                                toastr.success(response.message);
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
                        }
                    });
                }
            });


        });


});

</script>
@endsection

@endsection()
