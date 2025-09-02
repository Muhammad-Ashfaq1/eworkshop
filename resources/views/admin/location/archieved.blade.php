@extends('layout.main')
@section('title', 'Archieved Locations')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archieved Locations</h5>

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
                                        @if (!@empty($archievedLocations))
                                            @foreach ($archievedLocations as $location)
                                                <tr>
                                                    <td>{{ $location->id }}</td>
                                                    <td>{{ $location->name . ' - ' . $location->id }}</td>
                                                    <td>{{ $location->slug }}</td>
                                                    <td>{{ $location->location_type }}</td>
                                                    <td>{{ $location->is_active }}</td>
                                                    <td>{{ $location->created_at }}</td>
                                                    <td>{{ $location->updated_at }}</td>
                                                    <td>
                                                        @can('restore_locations')
                                                            <div class="dropdown">
                                                                <button class="btn btn-soft-secondary btn-sm dropdown"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <i class="ri-more-fill align-middle"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item restore-location-btn"
                                                                            data-id="{{ $location->id }}"
                                                                            id="js-location-restore-btn"><i
                                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>Restore</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#js-location-table').DataTable();


            //restore record  starts here
            $(document).on('click', '#js-location-restore-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                restoreLocation(id);
            });

        });

        function restoreLocation(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to resotre this Location!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/location/restore/${id}`,
                        type: "GET",
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                                'content'));
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                window.location.reload();
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
    </script>
@endsection
