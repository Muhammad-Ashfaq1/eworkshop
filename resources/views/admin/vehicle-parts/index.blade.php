@extends('layout.main')
@section('title', 'VehicleParts')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicle PArts</h5>
                    <div class="float-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-vehicle-part-modal">
                            Add New Part
                        </button>
                </div>
                <div class="card-body">
                    <table id="js-vehicle-part-table" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                    </div>
                                </th>
                                <th data-ordering="false">SR No.</th>
                                <th>Part Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="js-vehicle-part-table-body">
                            @include('admin.vehicle-parts.data-table')
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--end col-->
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
                                <div class="row g-3">
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="partName" class="form-label">Part Name</label>
                                            <input type="text" class="form-control" id="partName" name="name" placeholder="Enter partname">
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
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('#js-vehicle-part-table').DataTable();
        //add vechile part form
        $('#js-add-vehicle-part-form').on('submit',function(e){
            e.preventDefault();

            console.log('Form submitted'); // Debugging line to check if the form is submitted

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
                        $('#js-add-vehicle-part-modal').modal('hide');
                        $('#js-vehicle-part-table-body').html();
                        $('#js-vehicle-part-table-body').html(response.html);
                        $('#js-add-vehicle-part-form')[0].reset();
                    }
                    else
                    {
                        toastr.error(response.message);

                    }
                }
            });


        });
    });
</script>

@endsection
