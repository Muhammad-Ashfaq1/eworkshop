@extends('layout.main')
@section('title', 'Report Logs')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Report Logs</h5>

                    <div class="card-body">
                        <div class="masters-datatable table-responsive">
                            <div class="table-wrapper">
                                <table id="js-reports-table"
                                    class="table table-bordered dt-responsive nowrap table-striped align-middle location-datatable"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Modifier Name</th>
                                            <th>Before Change</th>
                                            <th>After Change</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!@empty($report_logs))
                                            @foreach($report_logs as $log)
                                
                                                <tr>
                                                    <td>{{ $log->id }}</td>
                                                    <td>{{ $log->modifier->full_name ?? 'N/A' }}</td>
                                                    <td><pre>{{ json_encode($log->before_changing_record, JSON_PRETTY_PRINT) }}</pre></td>
                                                    <td><pre>{{ json_encode($log->after_changing_record, JSON_PRETTY_PRINT) }}</pre></td>
                                                    <td>{{ $log->created_at }}</td>
                                                    <td>{{ $log->updated_at }}</td>
                                                    <td>
                                                        @can('view_report_logs')
                                                            <div class="dropdown">
                                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ri-more-fill align-middle"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" data-id="{{ $log->id }}" id="js-location-restore-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i>View Logs</a></li>
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
            $('#js-reports-table').DataTable();


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
