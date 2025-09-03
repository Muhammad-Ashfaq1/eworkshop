// Initialize DataTable


if (!$.fn.DataTable.isDataTable('#js-vehicle-table')) {
    $('#js-vehicle-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
}

    // Add new vehicle form submission with validation
    $('#js-add-vehicle-form').validate({
        rules: {
            vehicle_number: {
                required: true,
                minlength: 3
            },
            category: {
                required: true
            },
            condition: {
                required: true
            },
            town: {
                required: true
            },
            is_active: {
                required: true
            }
        },
        messages: {
            vehicle_number: {
                required: "Please enter a vehicle number",
                minlength: "Vehicle number must be at least 3 characters"
            },
            category: {
                required: "Please select a category"
            },
            condition: {
                required: "Please select a condition"
            },
            town: {
                required: "Please select a town"
            },
            is_active: {
                required: "Please select a status"
            }
        },
        submitHandler: function(form) {
            // This only runs if form is valid
            var url = $(form).attr('action');
            var formData = $(form).serialize();
            var vehicleId = $('#js-vehicle-id').val();

            $.ajax({
                url: url,
                data: formData,
                type: 'POST',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    $('#js-add-vehicle-submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#js-add-vehicle-modal').modal('hide');
                        $('#js-vehicle-table-body').html(response.html);
                        $('#js-add-vehicle-form')[0].reset();

                        swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#js-add-vehicle-submit').text('Add');
                        $('#js-model-title').text('Add Vehicle');
                        $('#js-vehicle-id').val('');

                        // Reset validation
                        $('#js-add-vehicle-form').validate().resetForm();
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
                        toastr.error("An error occurred while processing your request.");
                    }
                },
                complete: function() {
                    $('#js-add-vehicle-submit').prop('disabled', false).text(vehicleId ? 'Update' : 'Add');
                }
            });
        }
    });

    // Edit Vehicle functionality
    $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var vehicle = response.vehicle;

                    // Populate form fields
                    $('#vehicleNumber').val(vehicle.vehicle_number);
                    $('#js-category').val(vehicle.vehicle_category_id);
                    $('#js-condition').val(vehicle.condition);
                    $('#js-town').val(vehicle.location_id);
                    $('#js-is-active').val(vehicle.is_active ? 1 : 0);
                    $('#js-vehicle-id').val(vehicle.id);

                    // Show modal
                    $('#js-add-vehicle-modal').modal('show');
                    $('#js-add-vehicle-submit').text('Update');
                    $('#js-model-title').text('Edit Vehicle');

                    // Populate dropdowns
                    getDynamicDropdownData('/get-towns', '#js-town');
                    getDynamicDropdownData('/get-vehicle-categories', '#js-category');

                    // Set selected values after dropdowns are populated
                    setTimeout(function() {
                        $('#js-category').val(vehicle.vehicle_category_id);
                        $('#js-town').val(vehicle.location_id);
                    }, 500);
                } else {
                    console.error('Error fetching vehicle data:', response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    toastr.error("Vehicle not found.");
                } else {
                    toastr.error("An error occurred while fetching vehicle data.");
                }
            }
        });
    });

    // Delete Vehicle functionality
    $(document).on('click', '.vehicle-delete-btn', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('href');

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this vehicle?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading("Deleting vehicle...");

                $.ajax({
                    url: deleteUrl,
                    type: "DELETE",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(response) {
                        if(response.success) {
                            showSuccessAlert("Vehicle deleted successfully!", 1500);
                        } else {
                            showErrorAlert("Failed to delete vehicle. Please try again.");
                        }
                        $('#js-vehicle-table-body').html(response.html);
                    },
                    error: function(xhr) {
                        closeAlert();
                        showErrorAlert("Failed to delete vehicle. Please try again.");
                    }
                });
            }
        });
    });

    // Add new vehicle button click handler
    $(document).on('click', '#js-add-vehicle-button', function() {
        $('#js-add-vehicle-form')[0].reset();
        $('#js-model-title').text('Add Vehicle');
        $('#js-vehicle-id').val('');
        $('#js-add-vehicle-submit').text('Add');

        // Populate dropdowns first
        getDynamicDropdownData('/get-towns', '#js-town');
        getDynamicDropdownData('/get-vehicle-categories', '#js-category');

        // Show modal after dropdowns are populated
        setTimeout(function() {
            $('#js-add-vehicle-modal').modal('show');
        }, 300);

        // Reset validation
        $('#js-add-vehicle-form').validate().resetForm();
    });

