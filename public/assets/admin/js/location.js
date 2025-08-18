// Ensure jQuery is loaded
    $('#js-locations-table').DataTable();


    //add new location form submission starts here
    // Add new location form submission with validation
        $('#js-add-location-form').validate({
        rules: {
            // Define your validation rules here
            name: {
                required: true,
                minlength: 3
            },
            slug: {
                required: true,
                minlength: 3
            },
            is_active: {
                required: true,
            }
        },
        messages: {
            // Custom error messages
            name: {
                required: "Please enter a location name",
                minlength: "Location name must be at least 3 characters"
            },
            slug: {
                required: "Please enter a slug",
                minlength: "Slug must be at least 3 characters"
            },
            is_active: {
                required: "Please select a status",
            }
        },
        submitHandler: function(form) {
            // This only runs if form is valid
            var url = $(form).attr('action');
            var formData = $(form).serialize();
            var locationId = $('#js-location-id').val();

            $.ajax({
            url: url,
            data: formData,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                $('#js-add-location-submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.success) {
                $('#js-add-location-modal').modal('hide');
                $('#js-location-table-body').html(response.html);
                $('#js-add-location-form')[0].reset();

                swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });

                $('#js-add-location-submit').text('Add');
                $('#js-model-title').text('Add Location');
                $('#js-location-id').val('');

                // Reset validation
                $('#js-add-location-form').validate().resetForm();
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
                $('#js-add-location-submit').prop('disabled', false).text(locationId ? 'Update' : 'Add');
            }
            });
        }
        });
    //add new location form submission ends here


    //Edit Location starts Here
  $(document).on('click', '.edit-item-btn', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.success) {
                    var location = response.location;
                    $('#firstName').val(location.name);
                    $('#slug').val(location.slug);
                    $('#js-is-active').val(location.is_active);
                    $('#js-location-id').val(location.id);
                    $('#js-add-location-modal').modal('show');
                    $('#js-add-location-submit').text('Update');
                    $('#js-model-title').text('Edit Location');

                } else {
                    console.error('Error fetching location data:', response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    toastr.error("Location not found.");
                } else {
                    toastr.error("An error occurred while fetching location data.");
                }
            }
        });

    });

    //Edit Location ends here



    //delete location starts here

    $(document).on('click', '#location-delete-btn', function(e)
    {
        e.preventDefault();
        var deleteUrl = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this location?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading("Deleting location...");
                $.ajax({
                    url: deleteUrl,
                    type: "DELETE",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(response) {
                        if(response.success) {
                            showSuccessAlert("Location deleted successfully!", 1500);
                        } else {
                            showErrorAlert("Failed to delete location. Please try again.");
                        }
                        // closeAlert();
                        $('#js-location-table-body').html(response.html);
                    },
                    error: function(xhr) {
                        closeAlert();
                        showErrorAlert("Failed to delete location. Please try again.");
                    }
                });
            }
        });
    });
    //delete location end here


    // slug starts here
     $(document).on('keyup', '#firstName', function(){
        let slug = $(this).val()
            .toLowerCase()              // convert to lowercase
            .trim()                     // remove leading/trailing spaces
            .replace(/\s+/g, '-')       // replace spaces with -
            .replace(/[^a-z0-9\-]/g, ''); // remove all non-alphanumeric characters except hyphens

        $('#slug').val(slug);
    });
