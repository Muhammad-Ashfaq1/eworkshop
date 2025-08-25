@extends('layout.main')
@section('title', 'FleetManager/Mvi ')

@section('content')
    <div class="row">
        <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">FleetManager / Mvi</h5>
                        <div class="float-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#js-fleet-manager-modal">
                            Add New FleetManager/Mvi
                            </button>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="masters-datatable">
                        <table id="js-fleet-manager-table" class="table table-bordered dt-responsive nowrap table-striped align-middle location-datatable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
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

    <!-- Unified Location Modal -->
    <div class="modal fade" id="js-fleet-manager-modal" tabindex="-1" aria-labelledby="js-fleet-manager-label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                    <h5 class="modal-title" id="js-mvi-label">Add Location</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                <form id="js-fleet-manager-form" action="{{ route('admin.fleet-manager.addfleetmanager') }}" method="POST">
                    @csrf
                    <input type="hidden" id="js-fleet-manager-id" name="fleet-manager_id" value="">
                                        <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-xxl-6">
                                                        <div>
                                                        <label for="fleetMangerName" class="form-label">Fleet Manger/Mvi Name <x-req /></label>
                                                        <input type="text" class="form-control" id="fleetManagerName" name="fleetManager" placeholder="Enter Fleet Manager/ Mvi Name" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-xxl-6">
                                                        <div>
                                            <label for="Type" class="form-label"> Type <x-req /></label>
                                            <select name="type" id="js-type" class="form-control" required>
                                             <option value="" selected disabled>Select Type</option>
                                                            <option value="fleet_manager">FleetManager</option>
                                                            <option value="mvi">Mvi</option>
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
                        <button type="submit" class="btn btn-primary" id="js-fleet-manager-submit">Add FleetManager/Mvi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
    $('#js-fleet-manager-table').DataTable();
    $('#js-fleet-manager-form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        var fleetManagerId = $('#js-fleet-manager-id').val();
        var url = $(this).attr('action');


        $.ajax({
            url: url,
            method:'POST',
            data: data,
            success: function(response) {
                if(response.success) {
                $('#js-fleet-manager-modal').modal('hide');
                $('#js-fleet-manager-table').DataTable().ajax.reload();
                $('#js-fleet-manager-form')[0].reset();
                $('#js-fleet-manager-id').val('');
                $('#js-fleet-manager-submit').text('Add FleetManager/Mvi');
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }

        });
    });

});


</script>
@endsection
