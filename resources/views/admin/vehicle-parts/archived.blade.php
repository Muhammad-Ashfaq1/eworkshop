@extends('layout.main')
@section('title', 'Archived Vehicle Parts')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archived Vehicle Parts</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-archived-vehicle-parts-table" class="table table-bordered table-striped align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Part Number</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!@empty($archivedVehicleParts))
                                    @foreach($archivedVehicleParts as $vehiclePart)
                                        <tr>
                                            <td>{{ $vehiclePart->id }}</td>
                                            <td>{{ $vehiclePart->name }}</td>
                                            <td>{{ $vehiclePart->part_number ?? 'N/A' }}</td>
                                            <td>{{ $vehiclePart->description ?? 'N/A' }}</td>
                                            <td>{{ $vehiclePart->price ? '₹' . number_format($vehiclePart->price, 2) : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $vehiclePart->is_active ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                                    <i class="{{ $vehiclePart->is_active ? 'ri-check-line' : 'ri-close-line' }} me-1"></i>
                                                    {{ $vehiclePart->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $vehiclePart->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $vehiclePart->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('restore_vehicle_parts')
                                                    <button class="btn btn-sm btn-success restore-vehicle-part" 
                                                            data-id="{{ $vehiclePart->id }}"
                                                            title="Restore Vehicle Part">
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
            // Configure header icons for archived vehicle parts
            const headerConfig = [
                { icon: 'ri-hashtag', className: 'text-center' },
                { icon: 'ri-tools-line' },
                { icon: 'ri-link' },
                { icon: 'ri-checkbox-circle-line' },
                { icon: 'ri-calendar-line' },
                { icon: 'ri-delete-bin-line' },
                { icon: 'ri-settings-line', className: 'text-center' }
            ];

            // enhanceTableHeaders('#js-archived-vehicle-parts-table', headerConfig);

            $('#js-archived-vehicle-parts-table').DataTable({
                pageLength: 20,
                searching: true,
                lengthMenu: [
                    [20, 30, 50, 100],
                    ["20 entries", "30 entries", "50 entries", "100 entries"]
                ],
                order: [[7, 'desc']] // Sort by deleted_at descending
            });

            // Restore vehicle part
            $(document).on('click', '.restore-vehicle-part', function() {
                const vehiclePartId = $(this).data('id');
                
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to restore this vehicle part!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/vehicle-parts/restore-archived/${vehiclePartId}`,
                            type: 'POST',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#js-archived-vehicle-parts-table').DataTable().ajax.reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Failed to restore vehicle part. Please try again.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
