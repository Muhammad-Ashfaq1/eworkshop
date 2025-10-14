<?php

namespace App\Repositories;

use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;
use App\Models\Work;
use App\Helpers\FileUploadManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            ->with(['creator', 'works.vehiclePart', 'defectReport.vehicle', 'defectReport.location', 'defectReport']);

        // Apply date range filter
        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $query->where('issue_date', '>=', $data['start_date']);
        }
        if (isset($data['end_date']) && !empty($data['end_date'])) {
            $query->where('issue_date', '<=', $data['end_date']);
        }

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
                      ->orderByRaw("CONCAT(creators.first_name, ' ', creators.last_name) " . $direction)
                      ->select('purchase_orders.*');
                break;
            case 'parts_count':
                $query->orderBy('created_at', $direction);
                break;
                $query->orderBy('po_no', $direction);
                break;
                
            case 'received_by':
                $query->orderBy('received_by', $direction);
                break;
                
            case 'acc_amount':
                $query->orderBy('acc_amount', $direction);
                break;
                
            case 'issue_date':
                $query->orderBy('issue_date', $direction);
                break;
                
            case 'defect_report_ref':
                $query->join('defect_reports', 'purchase_orders.defect_report_id', '=', 'defect_reports.id')
                      ->orderBy('defect_reports.reference_number', $direction)
                      ->select('purchase_orders.*');
                break;
                
            case 'parts_count':
                $query->orderBy('works_count', $direction)
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
        return PurchaseOrder::with(['creator', 'works.vehiclePart', 'defectReport.vehicle', 'defectReport.location', 'defectReport'])->find($id);
    }

    public function createPurchaseOrder($data): JsonResponse
    {
        try {
            DB::beginTransaction();

            Log::info('PurchaseOrderRepository: Creating purchase order', [
                'user_id' => Auth::id(),
                'defect_report_id' => $data['defect_report_id'] ?? null,
                'po_no' => $data['po_no'] ?? null,
                'acc_amount' => $data['acc_amount'] ?? null,
                'parts_count' => isset($data['parts']) && is_array($data['parts']) ? count($data['parts']) : 0
            ]);

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

            Log::info('PurchaseOrderRepository: Purchase order created', [
                'purchase_order_id' => $purchaseOrder->id,
                'defect_report_id' => $purchaseOrder->defect_report_id
            ]);

            // Handle file upload
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                try {
                    $file = FileUploadManager::uploadFile($data['attachment_url'], 'purchase_orders/');
                    $purchaseOrder->update(['attachment_url' => $file['path']]);
                    
                    Log::info('PurchaseOrderRepository: File uploaded successfully', [
                        'purchase_order_id' => $purchaseOrder->id,
                        'file_path' => $file['path']
                    ]);
                } catch (\Exception $fileException) {
                    Log::error('PurchaseOrderRepository: File upload failed', [
                        'purchase_order_id' => $purchaseOrder->id,
                        'error' => $fileException->getMessage()
                    ]);
                    throw $fileException;
                }
            }

            // Create works for parts
            if (isset($data['parts']) && is_array($data['parts'])) {
                foreach ($data['parts'] as $index => $partData) {
                    try {
                        Work::create([
                            'defect_report_id' => $data['defect_report_id'],
                            'purchase_order_id' => $purchaseOrder->id,
                            'type' => 'purchase_order',
                            'quantity' => $partData['quantity'] ?? 1,
                            'vehicle_part_id' => $partData['vehicle_part_id'],
                        ]);
                        
                        Log::debug('PurchaseOrderRepository: Work created', [
                            'purchase_order_id' => $purchaseOrder->id,
                            'part_index' => $index,
                            'vehicle_part_id' => $partData['vehicle_part_id']
                        ]);
                    } catch (\Exception $workException) {
                        Log::error('PurchaseOrderRepository: Work creation failed', [
                            'purchase_order_id' => $purchaseOrder->id,
                            'part_index' => $index,
                            'error' => $workException->getMessage()
                        ]);
                        throw $workException;
                    }
                }
            }

            DB::commit();

            Log::info('PurchaseOrderRepository: Purchase order creation completed successfully', [
                'purchase_order_id' => $purchaseOrder->id
            ]);

            $response = [
                'purchaseOrder' => $purchaseOrder,
                'success' => true,
                'message' => 'Purchase order created successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PurchaseOrderRepository: Purchase order creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

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

            Log::info('PurchaseOrderRepository: Updating purchase order', [
                'user_id' => Auth::id(),
                'purchase_order_id' => $id,
                'defect_report_id' => $data['defect_report_id'] ?? null,
                'po_no' => $data['po_no'] ?? null,
                'acc_amount' => $data['acc_amount'] ?? null,
                'parts_count' => isset($data['parts']) && is_array($data['parts']) ? count($data['parts']) : 0,
                'incoming_parts' => $data['parts'] ?? [],
                'incoming_data' => $data
            ]);

            $purchaseOrder = PurchaseOrder::find($id);

            if (!$purchaseOrder) {
                Log::warning('PurchaseOrderRepository: Purchase order not found for update', [
                    'purchase_order_id' => $id,
                    'user_id' => Auth::id()
                ]);
                
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

            Log::info('PurchaseOrderRepository: Purchase order basic fields updated', [
                'purchase_order_id' => $purchaseOrder->id
            ]);

            // Handle file upload if provided
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                try {
                    // Delete old file if exists
                    if ($purchaseOrder->attachment_url) {
                        FileUploadManager::deleteFile($purchaseOrder->attachment_url);
                        Log::info('PurchaseOrderRepository: Old file deleted', [
                            'purchase_order_id' => $purchaseOrder->id,
                            'old_file_path' => $purchaseOrder->attachment_url
                        ]);
                    }

                    $file = FileUploadManager::uploadFile($data['attachment_url'], 'purchase_orders/');
                    $purchaseOrder->update(['attachment_url' => $file['path']]);
                    
                    Log::info('PurchaseOrderRepository: New file uploaded', [
                        'purchase_order_id' => $purchaseOrder->id,
                        'new_file_path' => $file['path']
                    ]);
                } catch (\Exception $fileException) {
                    Log::error('PurchaseOrderRepository: File upload failed during update', [
                        'purchase_order_id' => $purchaseOrder->id,
                        'error' => $fileException->getMessage()
                    ]);
                    throw $fileException;
                }
            }

            // Delete only purchase order works for THIS purchase order (preserve defect works)
            $existingPOWorksCount = $purchaseOrder->works()
                ->where('purchase_order_id', $purchaseOrder->id)
                ->where('type', 'purchase_order')
                ->count();
            
            $purchaseOrder->works()
                ->where('purchase_order_id', $purchaseOrder->id)
                ->where('type', 'purchase_order')
                ->delete();
            
            Log::info('PurchaseOrderRepository: Existing purchase order works deleted', [
                'purchase_order_id' => $purchaseOrder->id,
                'deleted_po_works_count' => $existingPOWorksCount
            ]);

            // Create new purchase order works from incoming parts data
            if (isset($data['parts']) && is_array($data['parts'])) {
                foreach ($data['parts'] as $index => $partData) {
                    try {
                        Work::create([
                            'defect_report_id' => $data['defect_report_id'],
                            'purchase_order_id' => $purchaseOrder->id,
                            'type' => 'purchase_order',
                            'quantity' => $partData['quantity'] ?? 1,
                            'vehicle_part_id' => $partData['vehicle_part_id'],
                        ]);
                        
                        Log::debug('PurchaseOrderRepository: Part work created/updated', [
                            'purchase_order_id' => $purchaseOrder->id,
                            'part_index' => $index,
                            'vehicle_part_id' => $partData['vehicle_part_id'],
                            'quantity' => $partData['quantity'] ?? 1
                        ]);
                    } catch (\Exception $workException) {
                        Log::error('PurchaseOrderRepository: Part work creation failed during update', [
                            'purchase_order_id' => $purchaseOrder->id,
                            'part_index' => $index,
                            'error' => $workException->getMessage()
                        ]);
                        throw $workException;
                    }
                }
            }

            DB::commit();

            Log::info('PurchaseOrderRepository: Purchase order update completed successfully', [
                'purchase_order_id' => $purchaseOrder->id
            ]);

            $response = [
                'purchaseOrder' => $purchaseOrder,
                'success' => true,
                'message' => 'Purchase order updated successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PurchaseOrderRepository: Purchase order update failed', [
                'user_id' => Auth::id(),
                'purchase_order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

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
            ->with(['creator', 'works.vehiclePart', 'defectReport.vehicle', 'defectReport.location', 'defectReport'])
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
