@extends('layout.main')
@section('title', 'Locations')
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
                            <tbody id="js-location-table-body">
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
                                            <h5 class="modal-title" id="js-model-title">Add Location</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('admin.location.store') }}" id="js-add-location-form" method="POST">
                                                @csrf
                                                <input type="text" id="js-location-id" name="location_id" value="" hidden>
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
                                                            <select name="is_active" id="js-is-active" class="form-control">
                                                            <option value="" selected disabled>Select Status</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">InActive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" id="js-add-location-submit">Add</button>
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

<script src="{{ asset('assets/admin/js/location.js') }}"></script>
@endsection
