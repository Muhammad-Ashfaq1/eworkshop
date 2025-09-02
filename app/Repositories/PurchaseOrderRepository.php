<?php

namespace App\Repositories;

use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;
use App\Models\Work;
use App\Helpers\FileUploadManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    public function getPurchaseOrderListing($data, $user): JsonResponse
    {
        // Set default values for DataTables parameters
        $data = array_merge([
            'start' => 0,
            'length' => 10,
            'draw' => 1,
            'search' => ['value' => ''],
            'order' => [],
            'columns' => []
        ], $data);

        $pageNumber = ($data['start'] / $data['length']) + 1; // gets the page number
        $pageLength = $data['length'];
        $skip = ($pageNumber - 1) * $pageLength; // calculates number of records to be skipped
        $search['search'] = $data['search']['value']; // gets the search value from request

        if (isset($data['order']) && !empty($data['order'])) {
            $index = $data['order'][0]['column'];
            $search['direction'] = $data['order'][0]['dir'];
            $search['column_name'] = $data['columns'][$index]['name'] ?? 'created_at';
        }

        $query = PurchaseOrder::forUser($user)
            ->with(['creator', 'works.vehiclePart', 'defectReport.vehicle', 'defectReport.location']);

        // Apply search filter
        if (!empty($search['search'])) {
            $query->where(function($q) use ($search) {
                $q->where('po_no', 'like', '%' . $search['search'] . '%')
                  ->orWhere('received_by', 'like', '%' . $search['search'] . '%')
                  ->orWhere('issue_date', 'like', '%' . $search['search'] . '%')
                  ->orWhereHas('defectReport', function($defectQuery) use ($search) {
                      $defectQuery->where('reference_number', 'like', '%' . $search['search'] . '%')
                                  ->orWhereHas('vehicle', function($vehicleQuery) use ($search) {
                                      $vehicleQuery->where('vehicle_number', 'like', '%' . $search['search'] . '%');
                                  })
                                  ->orWhereHas('location', function($locationQuery) use ($search) {
                                      $locationQuery->where('name', 'like', '%' . $search['search'] . '%');
                                  });
                  })
                  ->orWhereHas('creator', function($creatorQuery) use ($search) {
                      $creatorQuery->where('first_name', 'like', '%' . $search['search'] . '%')
                                   ->orWhere('last_name', 'like', '%' . $search['search'] . '%');
                  });
            });
        }

        // Apply ordering with relationship support
        if (isset($search['column_name']) && isset($search['direction'])) {
            $this->applyOrderBy($query, $search['column_name'], $search['direction']);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $recordsFiltered = $recordsTotal = $query->count(); // counts the total records filtered

        $purchaseOrders = $query->skip($skip)->take($pageLength)->get();

        // Add permission flags using can method
        $purchaseOrders->each(function ($purchaseOrder) use ($user) {
            $purchaseOrder->can_edit = $user->can('update_purchase_orders');
            $purchaseOrder->can_delete = $user->can('delete_purchase_orders');
        });

        $response['draw'] = $data['draw'];
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;
        $response['data'] = $purchaseOrders->toArray();

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Apply ordering to query with relationship support
     */
    private function applyOrderBy($query, $columnName, $direction)
    {
        // Handle relationship sorting
        switch ($columnName) {
            case 'defect_report.vehicle.vehicle_number':
                $query->join('defect_reports', 'purchase_orders.defect_report_id', '=', 'defect_reports.id')
                      ->join('vehicles', 'defect_reports.vehicle_id', '=', 'vehicles.id')
                      ->orderBy('vehicles.vehicle_number', $direction)
                      ->select('purchase_orders.*');
                break;
                
            case 'defect_report.location.name':
                $query->join('defect_reports', 'purchase_orders.defect_report_id', '=', 'defect_reports.id')
                      ->join('locations', 'defect_reports.location_id', '=', 'locations.id')
                      ->orderBy('locations.name', $direction)
                      ->select('purchase_orders.*');
                break;
                
            case 'creator.name':
                $query->leftJoin('users as creators', 'purchase_orders.created_by', '=', 'creators.id')
                      ->orderBy('creators.name', $direction)
                      ->select('purchase_orders.*');
                break;
                
            default:
                // Handle direct column sorting
                if (strpos($columnName, '.') === false) {
                    $query->orderBy($columnName, $direction);
                }
                break;
        }
    }

    public function getPurchaseOrderById($id)
    {
        return PurchaseOrder::with(['creator', 'works.vehiclePart', 'defectReport.vehicle', 'defectReport.location'])->find($id);
    }

    public function createPurchaseOrder($data): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'defect_report_id' => $data['defect_report_id'],
                'po_no' => $data['po_no'],
                'issue_date' => $data['issue_date'],
                'received_by' => $data['received_by'],
                'acc_amount' => $data['acc_amount'],
                'attachment_url' => null,
                'created_by' => Auth::id(),
            ]);

            // Handle file upload
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                $file = FileUploadManager::uploadFile($data['attachment_url'], 'purchase_orders/');
                $purchaseOrder->update(['attachment_url' => $file['path']]);
            }

            // Create works for parts
            if (isset($data['parts']) && is_array($data['parts'])) {
                foreach ($data['parts'] as $partData) {
                    Work::create([
                        'defect_report_id' => $data['defect_report_id'],
                        'purchase_order_id' => $purchaseOrder->id,
                        'type' => 'purchase_order',
                        'quantity' => $partData['quantity'] ?? 1,
                        'vehicle_part_id' => $partData['vehicle_part_id'],
                    ]);
                }
            }

            DB::commit();

            $response = [
                'purchaseOrder' => $purchaseOrder,
                'success' => true,
                'message' => 'Purchase order created successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase order. ' . $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function updatePurchaseOrder($id, $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::find($id);

            if (!$purchaseOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchase order not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Store original values BEFORE any modifications
            $originalValues = $purchaseOrder->getAttributes();
            
            // Store the original values in the observer's static property
            \App\Observers\PurchaseOrderObserver::setOriginalValues($purchaseOrder->id, $originalValues);
            
            // Update purchase order fields individually to preserve original values
            $purchaseOrder->defect_report_id = $data['defect_report_id'];
            $purchaseOrder->po_no = $data['po_no'];
            $purchaseOrder->issue_date = $data['issue_date'];
            $purchaseOrder->received_by = $data['received_by'];
            $purchaseOrder->acc_amount = $data['acc_amount'];
            
            // Save the changes - this will trigger the observer with proper original values
            $purchaseOrder->save();

            // Handle file upload if provided
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                // Delete old file if exists
                if ($purchaseOrder->attachment_url) {
                    FileUploadManager::deleteFile($purchaseOrder->attachment_url);
                }

                $file = FileUploadManager::uploadFile($data['attachment_url'], 'purchase_orders/');
                $purchaseOrder->update(['attachment_url' => $file['path']]);
            }

            // Delete existing works and create new ones
            $purchaseOrder->works()->delete();

            if (isset($data['parts']) && is_array($data['parts'])) {
                foreach ($data['parts'] as $partData) {
                    Work::create([
                        'defect_report_id' => $data['defect_report_id'],
                        'purchase_order_id' => $purchaseOrder->id,
                        'type' => 'purchase_order',
                        'quantity' => $partData['quantity'] ?? 1,
                        'vehicle_part_id' => $partData['vehicle_part_id'],
                    ]);
                }
            }

            DB::commit();

            $response = [
                'purchaseOrder' => $purchaseOrder,
                'success' => true,
                'message' => 'Purchase order updated successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase order. ' . $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function deletePurchaseOrder($id): JsonResponse
    {
        try {
            $purchaseOrder = PurchaseOrder::find($id);

            if (!$purchaseOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchase order not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Delete attached file if exists
            if ($purchaseOrder->attachment_url) {
                FileUploadManager::deleteFile($purchaseOrder->attachment_url);
            }

            // Delete related works
            $purchaseOrder->works()->delete();

            $purchaseOrder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order deleted successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase order. ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPurchaseOrdersForUser($user, $perPage = 15)
    {
        return PurchaseOrder::forUser($user)
            ->with(['creator', 'works.vehiclePart', 'defectReport.vehicle', 'defectReport.location'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function canViewPurchaseOrder($user, $purchaseOrder): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('deo')) {
            return $purchaseOrder->created_by == $user->id;
        }

        return false;
    }
}
