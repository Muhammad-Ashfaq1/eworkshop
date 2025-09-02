<?php

namespace App\Observers;

use App\Models\ReportAudit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderObserver
{
    // Static property to store original values
    private static $originalValues = [];

    /**
     * Set original values for a specific purchase order
     */
    public static function setOriginalValues($purchaseOrderId, $originalValues)
    {
        static::$originalValues[$purchaseOrderId] = $originalValues;
    }

    /**
     * Handle the PurchaseOrder "created" event.
     */
    public function created(PurchaseOrder $purchaseOrder): void
    {
        // Do not create audit logs for new records - only track updates
    }

    /**
     * Handle the PurchaseOrder "updating" event.
     * This event is no longer needed since we're setting values from repository
     */
    public function updating(PurchaseOrder $purchaseOrder): void
    {
        // No longer needed - values are set from repository
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        $user = Auth::user();

        // Get the original values from the static property
        $originalData = static::$originalValues[$purchaseOrder->id] ?? [];
        $newData = $purchaseOrder->getAttributes();
        
        // Only create audit log if there are actual changes (excluding timestamps)
        $changedFields = $this->getChangedFields($originalData, $newData);
        
        // Only create audit log if there are actual changes AND we have original data
        // This prevents audit logs for new records (which have no original data)
        if (!empty($changedFields) && !empty($originalData)) {
            // Get human-readable values for better audit logs
            $originalReadable = $this->getHumanReadableValues($purchaseOrder, $originalData);
            $newReadable = $this->getHumanReadableValues($purchaseOrder, $newData);

            ReportAudit::create([
                'modifier_id' => $user ? $user->id : null,
                'before_changing_record' => $originalData,
                'after_changing_record' => $newData,
                'before_changing_record_readable' => $originalReadable,
                'after_changing_record_readable' => $newReadable,
                'type' => 'purchase_order',
            ]);
        }
        
        // Clean up the static property
        unset(static::$originalValues[$purchaseOrder->id]);
    }

    /**
     * Handle the PurchaseOrder "deleted" event.
     */
    public function deleted(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "restored" event.
     */
    public function restored(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "force deleted" event.
     */
    public function forceDeleted(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Get only the fields that actually changed
     */
    private function getChangedFields($originalData, $newData)
    {
        $changedFields = [];
        $excludeFields = ['created_at', 'updated_at']; // Exclude timestamp fields
        
        foreach ($newData as $key => $value) {
            if (in_array($key, $excludeFields)) {
                continue;
            }
            
            $originalValue = $originalData[$key] ?? null;
            if ($originalValue !== $value) {
                $changedFields[$key] = [
                    'from' => $originalValue,
                    'to' => $value
                ];
            }
        }
        
        return $changedFields;
    }

    /**
     * Get human-readable values for audit logs
     */
    private function getHumanReadableValues($purchaseOrder, $data)
    {
        $readableData = [];
        
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'defect_report_id':
                    if ($value) {
                        $defectReport = \App\Models\DefectReport::with(['vehicle', 'location'])->find($value);
                        if ($defectReport) {
                            $vehicle = $defectReport->vehicle ? $defectReport->vehicle->vehicle_number : 'N/A';
                            $location = $defectReport->location ? $defectReport->location->name : 'N/A';
                            $readableData[$key] = "DR-{$defectReport->reference_number} ({$vehicle} - {$location})";
                        } else {
                            $readableData[$key] = 'N/A';
                        }
                    } else {
                        $readableData[$key] = 'N/A';
                    }
                    break;
                    
                case 'created_by':
                    if ($value) {
                        $creator = \App\Models\User::find($value);
                        $readableData[$key] = $creator ? $creator->full_name : 'N/A';
                    } else {
                        $readableData[$key] = 'N/A';
                    }
                    break;
                    
                case 'issue_date':
                    $readableData[$key] = $value ? date('d/m/Y', strtotime($value)) : 'N/A';
                    break;
                    
                case 'acc_amount':
                    $readableData[$key] = $value ? number_format($value, 2) : '0.00';
                    break;
                    
                case 'attachment_url':
                    $readableData[$key] = $value ? 'File Attached' : 'No File';
                    break;
                    
                default:
                    $readableData[$key] = $value;
                    break;
            }
        }
        
        return $readableData;
    }
}
