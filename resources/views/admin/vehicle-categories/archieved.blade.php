@extends('layout.main')
@section('title', 'Archived Vehicle categories')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archived Vehicle Categories</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-archived-vehicle-categories-table"
                            class="table table-bordered table-striped align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!@empty($archivedVehicleCategories))
                                    @foreach ($archivedVehicleCategories as $vehicleCategory)
                                        <tr>
                                            <td>{{ $vehicleCategory->id }}</td>
                                            <td>{{ $vehicleCategory->name }}</td>

                                            <td>
                                                <span
                                                    class="badge bg-{{ $vehicleCategory->is_active ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                                    <i
                                                        class="{{ $vehicleCategory->is_active ? 'ri-check-line' : 'ri-close-line' }} me-1"></i>
                                                    {{ $vehicleCategory->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $vehicleCategory->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $vehicleCategory->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('restore_vehicle_categories')
                                                    <button class="btn btn-sm btn-success restore-vehicle-category"
                                                        data-id="{{ $vehicleCategory->id }}" title="Restore Vehicle Category">
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
            // ✅ Initialize DataTable
            $('#js-archived-vehicle-categories-table').DataTable({
                pageLength: 20,
                searching: true,
                lengthMenu: [
                    [20, 30, 50, 100],
                    ["20 entries", "30 entries", "50 entries", "100 entries"]
                ],
                order: [
                    [4, 'desc']
                ] // Sort by deleted_at column
            });

            // ✅ Restore vehicle category
            $(document).on('click', '.restore-vehicle-category', function() {
                const vehicleCategoryId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to restore this Vehicle Category!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/vehicle-categories/restore-archived/${vehicleCategoryId}`,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);

                                    // ✅ Remove row from table
                                    $(`button[data-id="${vehicleCategoryId}"]`).closest(
                                        'tr').remove();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error(
                                    'Failed to restore vehicle category. Please try again.'
                                    );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
