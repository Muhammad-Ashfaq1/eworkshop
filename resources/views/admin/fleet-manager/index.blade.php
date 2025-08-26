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
                            <tbody id="js-table-body">
                               @include('admin.fleet-manager.data-table')
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
                <form id="js-fleet-manager-form" action="{{ route('admin.fleet-manager.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="js-fleet-manager-id" name="fleet-manager_id" value="">
                                        <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-xxl-6">
                                                        <div>
                                                        <label for="fleetMangerName" class="form-label">Fleet Manger/Mvi Name <x-req /></label>
                                                        <input type="text" class="form-control" id="fleetManagerName" name="fleetManager" placeholder="Enter Fleet Manager/ Mvi Name" required>
                                                            @error('fleetManager')
                                                        <span class="text-danger">{{ $message }}</span>
                                                            @enderror
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
                                                            @error('type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                            @enderror
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
                                                            @error('is_active')
                                                        <span class="text-danger">{{ $message }}</span>
                                                            @enderror
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
$(document).ready(function () {
    $('#js-fleet-manager-table').DataTable();


    $('#js-fleet-manager-form').validate({
        rules: {
            fleetManager: {
                required: true,
                minlength: 2
            },
            type: {
                required: true
            },
            is_active: {
                required: true
            }
        },
        messages: {
            fleetManager: {
                required: "Please enter a name",
                minlength: "Name must be at least 2 characters long"
            },
            type: {
                required: "Please select a type"
            },
            is_active: {
                required: "Please select a status"
            }
        },
        submitHandler: function (form) {



            var data = $(form).serialize();
            var fleetManagerId = $('#js-fleet-manager-id').val();
            var url = $(form).attr('action');
            var method=$(form).attr('method');

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        $('#js-fleet-manager-modal').modal('hide');
                        // $('#js-fleet-manager-table').DataTable().ajax.reload();
                        $('#js-fleet-manager-form')[0].reset();
                        $('#js-fleet-manager-id').val('');
                        $('#js-fleet-manager-submit').text('Add FleetManager/Mvi');
                        $('#js-table-body').html(response.html);
                    toastr.success(response.message);
                    }
                    else  {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }

                },
                complete: function () {
                    $('#js-fleet-manager-submit').prop('disabled', false).text('Add Fleet Manager/Mvi');
                }
            });
            return false;
        }
    });
});
</script>

@endsection
