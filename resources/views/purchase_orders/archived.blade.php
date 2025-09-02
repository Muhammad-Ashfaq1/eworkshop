@extends('layout.main')
@section('title', 'Archived Purchase Orders')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Archived Purchase Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="js-archived-purchase-orders-table"
                            class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PO Number</th>
                                    <th>Defect Report</th>
                                    <th>Vehicle</th>
                                    <th>Location</th>
                                    <th>Issue Date</th>
                                    <th>Received By</th>
                                    <th>Amount</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!@empty($archivedPurchaseOrders))
                                    @foreach ($archivedPurchaseOrders as $purchaseOrder)
                                        <tr>
                                            <td>{{ $purchaseOrder->id }}</td>
                                            <td>{{ $purchaseOrder->po_no }}</td>
                                            <td>{{ $purchaseOrder->defectReport->reference_number ?? 'N/A' }}</td>
                                            <td>{{ $purchaseOrder->defectReport->vehicle->vehicle_number ?? 'N/A' }}</td>
                                            <td>{{ $purchaseOrder->defectReport->location->name ?? 'N/A' }}</td>
                                            <td>{{ $purchaseOrder->issue_date->format('d/m/Y') }}</td>
                                            <td>{{ $purchaseOrder->received_by }}</td>
                                            <td>₹{{ number_format($purchaseOrder->acc_amount, 2) }}</td>
                                            <td>{{ $purchaseOrder->creator->full_name ?? 'N/A' }}</td>
                                            <td>{{ $purchaseOrder->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $purchaseOrder->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @can('restore_purchase_orders')
                                                    <button class="btn btn-sm btn-success restore-purchase-order"
                                                        data-id="{{ $purchaseOrder->id }}" title="Restore Purchase Order">
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
            $('#js-archived-purchase-orders-table').DataTable({
                order: [
                    [10, 'desc']
                ] // Sort by deleted_at descending
            });

            // Restore purchase order
            $(document).on('click', '.restore-purchase-order', function() {
                const purchaseOrderId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to restore this purchase order!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/purchase-orders/restore-archived/${purchaseOrderId}`,
                            type: 'POST',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                    'meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#js-archived-purchase-orders-table').DataTable()
                                        .ajax.reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error(
                                    'Failed to restore purchase order. Please try again.'
                                    );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
