@extends('layout.main')
@section('title', 'Archived Vehicles')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archived Vehicles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-archived-vehicles-table" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle Number</th>
                                    <th>Category</th>
                                    <th>Model</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!@empty($archivedVehicles))
                                    @foreach($archivedVehicles as $vehicle)
                                        <tr>
                                            <td>{{ $vehicle->id }}</td>
                                            <td>{{ $vehicle->vehicle_number }}</td>
                                            <td>{{ $vehicle->category->name ?? 'N/A' }}</td>
                                            <td>{{ $vehicle->model ?? 'N/A' }}</td>
                                            <td>{{ $vehicle->year ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $vehicle->is_active ? 'success' : 'danger' }}">
                                                    {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $vehicle->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $vehicle->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('restore_vehicles')
                                                    <button class="btn btn-sm btn-success restore-vehicle" 
                                                            data-id="{{ $vehicle->id }}"
                                                            title="Restore Vehicle">
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
            $('#js-archived-vehicles-table').DataTable({
                order: [[7, 'desc']] // Sort by deleted_at descending
            });

            // Restore vehicle
            $(document).on('click', '.restore-vehicle', function() {
                const vehicleId = $(this).data('id');
                
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to restore this vehicle!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/vehicles/restore-archived/${vehicleId}`,
                            type: 'POST',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#js-archived-vehicles-table').DataTable().ajax.reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Failed to restore vehicle. Please try again.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
