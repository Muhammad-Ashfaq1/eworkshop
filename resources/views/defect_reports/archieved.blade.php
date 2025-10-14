@extends('layout.main')
@section('title', 'Archieved Defect Reports')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archieved Defect Reports</h5>

                    <div class="card-body">
                        <div class="masters-datatable table-responsive">
                            <div class="table-wrapper">
                                <table id="js-archieved-defect-reports-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Reference #</th>
                                            <th>Vehicle</th>
                                            <th>Office/Town</th>
                                            <th>Driver Name</th>
                                            <th>Fleet Manager</th>
                                            <th>MVI</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Works Count</th>
                                            <th>Attachment</th>
                                            <th>Created By</th>
                                            <th>created_at</th>
                                            <th>updated_at</th>
                                            <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($archievedDefectReports))
                                            @foreach ($archievedDefectReports as $archievedDefectReport)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $archievedDefectReport->reference_number ?? 'N/A' }}</td>
                                                    <td>{{ $archievedDefectReport->vehicle->vehicle_number ?? 'N/A' }}</td>
                                                    <td>{{ ucfirst($archievedDefectReport->location->location_type) }}</td>
                                                    <td>{{ $archievedDefectReport->driver_name }}</td>
                                                    <td>{{ $archievedDefectReport->fleetManager->name }}</td>
                                                    <td>{{ $archievedDefectReport->mvi->name }}</td><!--mvi-->
                                                    <td>{{ $archievedDefectReport->date }}</td>
                                                    <td>{{ ucfirst($archievedDefectReport->type) }}</td>
                                                    <td>{{ count($archievedDefectReport->defectWorks ?? []) }}</td>
                                                    <td><a href="{{ $archievedDefectReport->attachment_url }}"
                                                            target="_blank">
                                                            View Attachment</a>

                                                    </td>
                                                    <td>{{ $archievedDefectReport->creator->fullName }}</td>
                                                    <td>{{ $archievedDefectReport->created_at->format('d M Y, h:i A') }}
                                                    </td>
                                                    <td>{{ $archievedDefectReport->updated_at->format('d M Y, h:i A') }}
                                                    </td>
                                                    <td>
                                                        @can('restore_defect_reports')
                                                        <div class="dropdown">
                                                            <button class="btn btn-soft-secondary btn-sm dropdown"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="ri-more-fill align-middle"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item restore-defect-report-btn"
                                                                        data-id="{{ $archievedDefectReport->id }}"
                                                                        id="js-defect-report-restore-btn"><i
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
            $('#js-archieved-defect-reports-table').DataTable({
            pageLength: 20,
            searching: true,
            lengthMenu: [
                [20, 30, 50, 100],
                ["20 entries", "30 entries", "50 entries", "100 entries"]
            ]
        });
            $(document).on('click', '#js-defect-report-restore-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                restoreDefectReport(id);
            });

        });

        function restoreDefectReport(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to resotre this Defect Report!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/defect-reports/restore-archieved/${id}`,
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
