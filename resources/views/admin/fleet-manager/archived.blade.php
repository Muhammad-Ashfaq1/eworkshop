@extends('layout.main')
@section('title', 'Archived Fleet Managers')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archived Fleet Managers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-archived-fleet-managers-table"
                            class="table table-bordered table-striped align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!@empty($archivedFleetManagers))
                                    @foreach ($archivedFleetManagers as $fleetManager)
                                        <tr>
                                            <td>{{ $fleetManager->id }}</td>
                                            <td>{{ $fleetManager->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $fleetManager->type === 'fleet_manager' ? 'primary' : 'info' }}">
                                                    {{ ucwords(str_replace('_', ' ', $fleetManager->type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $fleetManager->is_active ? 'success' : 'danger' }}">
                                                    {{ $fleetManager->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $fleetManager->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $fleetManager->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('restore_fleet_manager')
                                                    <button class="btn btn-sm btn-success restore-fleet-manager"
                                                        data-id="{{ $fleetManager->id }}" title="Restore Fleet Manager">
                                                        <i class="ri-refresh-line"></i> Restore
                                                    </button>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            quickResponsiveTable('#js-archived-fleet-managers-table', {
        
        
                order: [
                    [5, 'desc']
                ] // Sort by deleted_at descending
            });

            // Restore fleet manager
            $(document).on('click', '.restore-fleet-manager', function() {
                const fleetManagerId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to restore this fleet manager!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/fleet-managers/restore-archived/${fleetManagerId}`,
                            type: 'POST',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                    'meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#js-archived-fleet-managers-table').DataTable()
                                        .ajax.reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error(
                                    'Failed to restore fleet manager. Please try again.'
                                    );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
