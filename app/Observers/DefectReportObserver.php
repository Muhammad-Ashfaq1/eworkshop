<?php

namespace App\Observers;

use App\Models\ReportAudit;
use App\Models\DefectReport;
use Illuminate\Support\Facades\Auth;

class DefectReportObserver
{
    // Static property to store original values
    private static $originalValues = [];

    /**
     * Set original values for a specific defect report
     */
    public static function setOriginalValues($defectReportId, $originalValues)
    {
        static::$originalValues[$defectReportId] = $originalValues;
    }

    /**
     * Handle the DefectReport "created" event.
     */
    public function created(DefectReport $defectReport): void
    {

    }

    /**
     * Handle the DefectReport "updating" event.
     * This event is no longer needed since we're setting values from repository
     */
    public function updating(DefectReport $defectReport): void
    {
        // No longer needed - values are set from repository
    }

    /**
     * Handle the DefectReport "updated" event.
     */
    public function updated(DefectReport $defectReport): void
    {
        $user = Auth::user();

        // Get the original values from the static property
        $originalData = static::$originalValues[$defectReport->id] ?? [];
        $newData = $defectReport->getAttributes();

        // Only create audit log if there are actual changes (excluding timestamps)
        $changedFields = $this->getChangedFields($originalData, $newData);

        if (!empty($changedFields)) {
            // Get human-readable values for better audit logs
            $originalReadable = $this->getHumanReadableValues($defectReport, $originalData);
            $newReadable = $this->getHumanReadableValues($defectReport, $newData);

            ReportAudit::create([
                'modifier_id' => $user ? $user->id : null,
                'before_changing_record' => $originalData,
                'after_changing_record' => $newData,
                'before_changing_record_readable' => $originalReadable,
                'after_changing_record_readable' => $newReadable,
                'type' => 'defect_report',
            ]);
        }

        // Clean up the static property
        unset(static::$originalValues[$defectReport->id]);
    }

    /**
     * Handle the DefectReport "deleted" event.
     */
    public function deleted(DefectReport $defectReport): void
    {
        //
    }

    /**
     * Handle the DefectReport "restored" event.
     */
    public function restored(DefectReport $defectReport): void
    {
        //
    }

    /**
     * Handle the DefectReport "force deleted" event.
     */
    public function forceDeleted(DefectReport $defectReport): void
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
    private function getHumanReadableValues($defectReport, $data)
    {
        $readableData = [];

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'vehicle_id':
                    if ($value) {
                        $vehicle = \App\Models\Vehicle::with('category')->find($value);
                        $readableData[$key] = $vehicle ? $vehicle->vehicle_number . ' (' . ($vehicle->category->name ?? 'N/A') . ')' : 'N/A';
                    } else {
                        $readableData[$key] = 'N/A';
                    }
                    break;

                case 'location_id':
                    if ($value) {
                        $location = \App\Models\Location::find($value);
                        $readableData[$key] = $location ? $location->name : 'N/A';
                    } else {
                        $readableData[$key] = 'N/A';
                    }
                    break;

                case 'fleet_manager_id':
                    if ($value) {
                        $fleetManager = \App\Models\FleetManager::find($value);
                        $readableData[$key] = $fleetManager ? $fleetManager->name : 'N/A';
                    } else {
                        $readableData[$key] = 'N/A';
                    }
                    break;

                case 'mvi_id':
                    if ($value) {
                        $mvi = \App\Models\FleetManager::find($value);
                        $readableData[$key] = $mvi ? $mvi->name : 'N/A';
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

                case 'type':
                    $readableData[$key] = ucwords(str_replace('_', ' ', $value));
                    break;

                case 'date':
                    $readableData[$key] = $value ? date('d/m/Y', strtotime($value)) : 'N/A';
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
