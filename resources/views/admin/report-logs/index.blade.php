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
                                            <th>Type</th>
                                            <th>Changes Made</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!@empty($report_logs))
                                            @foreach($report_logs as $log)
                                                <tr>
                                                    <td>{{ $log->id }}</td>
                                                    <td>{{ $log->modifier->full_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $log->type === 'defect_report' ? 'primary' : 'success' }}">
                                                            {{ ucwords(str_replace('_', ' ', $log->type)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-info" 
                                                                onclick="viewLogDetails({{ $log->id }})">
                                                            <i class="ri-eye-line"></i> View Changes
                                                        </button>
                                                    </td>
                                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @can('view_report_logs')
                                                            <div class="dropdown">
                                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ri-more-fill align-middle"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <a class="dropdown-item" href="#" onclick="viewLogDetails({{ $log->id }})">
                                                                            <i class="ri-eye-line align-bottom me-2 text-muted"></i>View Details
                                                                        </a>
                                                                    </li>
                                                                    @role('super_admin')
                                                                    <li>
                                                                        <a class="dropdown-item text-danger" href="#" onclick="deleteLog({{ $log->id }})">
                                                                            <i class="ri-delete-bin-line align-bottom me-2 text-muted"></i>Delete Log
                                                                        </a>
                                                                    </li>
                                                                    @endrole
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

    <!-- Log Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logDetailsModalLabel">Report Change Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-danger">Before Changes</h6>
                            <div id="beforeChanges" class="border rounded p-3 bg-light">
                                <!-- Before changes will be loaded here -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">After Changes</h6>
                            <div id="afterChanges" class="border rounded p-3 bg-light">
                                <!-- After changes will be loaded here -->
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Change Summary</h6>
                            <div id="changeSummary" class="border rounded p-3">
                                <!-- Change summary will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#js-reports-table').DataTable({
                pageLength: 20,
                searching: true,
                lengthMenu: [
                    [20, 30, 50, 100],
                    ["20 entries", "30 entries", "50 entries", "100 entries"]
                ],
                order: [[4, 'desc']] // Sort by created_at descending
            });
        });

        function viewLogDetails(logId) {
            $.ajax({
                url: `/admin/logs/${logId}`,
                type: 'GET',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {
                    if (response.success) {
                        displayLogDetails(response.log);
                        $('#logDetailsModal').modal('show');
                    } else {
                        toastr.error('Failed to load log details');
                    }
                },
                error: function(xhr) {
                    toastr.error('Failed to load log details');
                }
            });
        }

        function displayLogDetails(log) {
            // Field labels for both Defect Reports and Purchase Orders
            const fieldLabels = {
                // Defect Report fields
                'reference_number': 'Reference Number',
                'vehicle_id': 'Vehicle',
                'location_id': 'Location',
                'driver_name': 'Driver Name',
                'fleet_manager_id': 'Fleet Manager',
                'mvi_id': 'MVI',
                'date': 'Date',
                'attachment_url': 'Attachment',
                'type': 'Type',
                'created_by': 'Created By',
                
                // Purchase Order fields
                'defect_report_id': 'Defect Report',
                'po_no': 'PO Number',
                'issue_date': 'Issue Date',
                'received_by': 'Received By',
                'acc_amount': 'Amount'
            };

            // Display before changes
            let beforeHtml = '<div class="table-responsive force-table-responsive table-scroll-indicator"><table class="table table-sm">';
            beforeHtml += '<thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>';
            
            if (log.before_changing_record_readable) {
                Object.entries(log.before_changing_record_readable).forEach(([key, value]) => {
                    if (key !== 'created_at' && key !== 'updated_at') {
                        beforeHtml += `<tr><td><strong>${fieldLabels[key] || key}</strong></td><td>${value || 'N/A'}</td></tr>`;
                    }
                });
            } else {
                beforeHtml += '<tr><td colspan="2">No data available</td></tr>';
            }
            beforeHtml += '</tbody></table></div>';
            $('#beforeChanges').html(beforeHtml);

            // Display after changes
            let afterHtml = '<div class="table-responsive force-table-responsive table-scroll-indicator"><table class="table table-sm">';
            afterHtml += '<thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>';
            
            if (log.after_changing_record_readable) {
                Object.entries(log.after_changing_record_readable).forEach(([key, value]) => {
                    if (key !== 'created_at' && key !== 'updated_at') {
                        afterHtml += `<tr><td><strong>${fieldLabels[key] || key}</strong></td><td>${value || 'N/A'}</td></tr>`;
                    }
                });
            } else {
                afterHtml += '<tr><td colspan="2">No data available</td></tr>';
            }
            afterHtml += '</tbody></table></div>';
            $('#afterChanges').html(afterHtml);

            // Display change summary - only show fields that actually changed
            let summaryHtml = '<div class="table-responsive force-table-responsive table-scroll-indicator"><table class="table table-sm">';
            summaryHtml += '<thead><tr><th>Field</th><th>Old Value</th><th>New Value</th><th>Status</th></tr></thead><tbody>';
            
            if (log.before_changing_record_readable && log.after_changing_record_readable) {
                let hasChanges = false;
                
                Object.keys(log.before_changing_record_readable).forEach(key => {
                    if (key === 'created_at' || key === 'updated_at') return;
                    
                    const oldValue = log.before_changing_record_readable[key] || 'N/A';
                    const newValue = log.after_changing_record_readable[key] || 'N/A';
                    const hasChanged = oldValue !== newValue;
                    
                    if (hasChanged) {
                        hasChanges = true;
                        summaryHtml += `<tr class="table-warning">
                            <td><strong>${fieldLabels[key] || key}</strong></td>
                            <td class="text-danger">${oldValue}</td>
                            <td class="text-success">${newValue}</td>
                            <td><span class="status-badge pending with-icon"><i class="ri-edit-line"></i>Changed</span></td>
                        </tr>`;
                    } else {
                        summaryHtml += `<tr class="table-success">
                            <td><strong>${fieldLabels[key] || key}</strong></td>
                            <td>${oldValue}</td>
                            <td>${newValue}</td>
                            <td><span class="status-badge active with-icon"><i class="ri-check-line"></i>No Change</span></td>
                        </tr>`;
                    }
                });
                
                if (!hasChanges) {
                    summaryHtml += '<tr><td colspan="4" class="text-center text-muted">No fields were modified</td></tr>';
                }
            } else {
                summaryHtml += '<tr><td colspan="4">No change data available</td></tr>';
            }
            summaryHtml += '</tbody></table></div>';
            $('#changeSummary').html(summaryHtml);
        }

        function deleteLog(logId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this log record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/logs/${logId}`,
                        type: 'DELETE',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                window.location.reload();
                            } else {
                                toastr.error(response.message || 'Failed to delete log');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to delete log. Please try again.');
                        }
                    });
                }
            });
        }
    </script>
@endsection
