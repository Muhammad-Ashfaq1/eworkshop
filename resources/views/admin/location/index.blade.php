@extends('layout.main')
@section('content')
<div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Locations</h5>
                                <div class="float-end">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-location-modal">
                                        Add New Locations
                                    </button>
                            </div>
                            <div class="card-body">
                                <table id="js-locations-table" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th>
                                            <th data-ordering="false">SR No.</th>
                                            <th>Location</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @include('admin.location.data-table')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--end col-->
                </div>



                <!-- Modal starts here-->
                <div class="live-preview">

                                    <div class="modal fade" id="js-add-location-modal" tabindex="-1" aria-labelledby="exampleModalgridLabel">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalgridLabel">Grid Modals</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.location.store') }}" id="js-add-location-form" method="POST">
                                                        @csrf
                                                        <div class="row g-3">
                                                            <div class="col-xxl-6">
                                                                <div>
                                                                    <label for="firstName" class="form-label">Name</label>
                                                                    <input type="text" class="form-control" id="firstName" name="name" placeholder="Enter firstname">
                                                                </div>
                                                            </div>
                                                            <div class="col-xxl-6">
                                                                <div>
                                                                    <label for="slug" class="form-label">Slug</label>
                                                                    <input type="text" class="form-control" id="slug" readonly name="slug" placeholder="Enter firstname">
                                                                </div>
                                                            </div>
                                                            <!--end col-->
                                                            <div class="col-xxl-6">
                                                                <div>
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select name="is_active" id="myDropdown" class="form-control">
                                                                    <option value="" selected disabled>Select Status</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">InActive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="hstack gap-2 justify-content-end">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                                </div>
                                                            </div>
                                                            <!--end col-->
                                                        </div>
                                                        <!--end row-->
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
    $('#js-locations-table').DataTable();


    //add new location form submission starts here
    $('#js-add-location-form').on('submit',function(e){
        e.preventDefault();
        var url=$(this).attr('action');
        var formData = $(this).serialize();
        $.ajax({
            url:url,
            data:formData,
            type:'POST',
            dataType:'json',
            success:function(response){
                if(response.success)
                {
                    $('#js-add-location-modal').modal('hide');
                    $('#js-locations-table tbody').html(response.html);
                    $('#js-add-location-form')[0].reset();
                    toastr.success(response.message);
                }
                else
                {
                    toastr.error(response.message);
                }

            }

        });
    });
    //add new location form submission ends here


    //Edit Location starts Here
   /* $('.edit-item-btn').on('click', function() {
        var locationId = $(this).data('id');
        console.log(locationId);

        $.ajax({
            url: '',
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    var location = response.data;
                    $('#firstName').val(location.name);
                    $('#slug').val(location.slug);
                    $('#myDropdown').val(location.is_active);
                    $('#exampleModalgrid').modal('show');
                } else {
                    console.error('Error fetching location data:', response.message);
                }
        }
        });

    });
*/
    //Edit Location ends here



    //delete location starts here

    $(document).on('click', '#location-delete-btn', function()
    {
        var id = $(this).data('id');
        console.log(' hello from delete location button');
    });


    // // Delete employee button click handler
        $(document).on('click', '.remove-item-btn', function() {
            var deleteUrl = $(this).data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this employee?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading("Deleting employee...");
                    $.ajax({
                        url: deleteUrl,
                        type: "DELETE",
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        },
                        success: function(response) {
                            closeAlert();
                            $('#js-employees-table').empty().html(response.html);
                            showSuccessAlert("Employee deleted successfully!", 1500);
                        },
                        error: function(xhr) {
                            closeAlert();
                            showErrorAlert("Failed to delete employee. Please try again.");
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
    //slug ends here
});
</script>
@endsection
