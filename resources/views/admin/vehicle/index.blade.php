@extends('layout.main')
@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vehicles</h5>
                        <div class="float-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-add-vehicle-modal">
                                Add New Vehicles
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="js-vehicle-table" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
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
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="js-vehicle-table-body">
                                @include('admin.vehicle.data-table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end col-->
        </div>



        <!-- Modal starts here-->
        <div class="live-preview">
            <div class="modal fade" id="js-add-vehicle-modal" tabindex="-1" aria-labelledby="exampleModalgridLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="js-model-title">Add Vehicle</h5>
                            <button type="button" class="btn-close" id="js-add-vehicle-button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.vehicle.store') }}" id="js-add-vehicle-form" method="POST">
                                @csrf
                                <input type="text" id="js-vehicle-id" name="vehicle_id" value="" hidden>
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
                                            <select name="category" id="js-category" class="form-control">
                                                <option value="" selected disabled>Select Category</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="condition" class="form-label">Condition <x-req /></label>
                                            <select name="condition" id="js-condition" class="form-control">
                                                <option value="" selected disabled>Select Condition</option>
                                                <option value="new">New</option>
                                                <option value="old">Old</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="town" class="form-label">Town <x-req /></label>
                                            <select name="town" id="js-town" class="form-control">
                                                <option value="" selected disabled>Select Town</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div>
                                            <label for="status" class="form-label">Status <x-req /></label>
                                            <select name="is_active" id="js-is-active" class="form-control">
                                                <option value="" selected disabled >Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">InActive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="js-add-vehicle-submit">Add</button>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end col-->
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
    <script src="{{ asset('assets/admin/js/vehicle.js') }}"></script>
@endsection
