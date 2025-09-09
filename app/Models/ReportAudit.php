<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\ReportAuditRelation;

class ReportAudit extends Model
{
    use SoftDeletes,ReportAuditRelation;

    protected $fillable = [
        'modifier_id',
        'original_creator_id',
        'record_id',
        'record_type',
        'before_changing_record',
        'after_changing_record',
        'before_changing_record_readable',
        'after_changing_record_readable',
        'type',
    ];

    protected $casts = [
        'before_changing_record' => 'array',
        'after_changing_record' => 'array',
        'before_changing_record_readable' => 'array',
        'after_changing_record_readable' => 'array',
    ];

    /**
     * Get the record that was audited (accessor method, not a relationship)
     */
    public function getRecordAttribute()
    {
        if ($this->record_type === 'defect_report') {
            return $this->defectReport;
        } elseif ($this->record_type === 'purchase_order') {
            return $this->purchaseOrder;
        }

        return null;
    }

    /**
     * Check if this audit was made by an admin/super admin (affects accuracy)
     */
    public function isAdminEdit()
    {
        if (!$this->modifier) {
            return false;
        }

        return $this->modifier->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Check if this audit affects DEO accuracy
     * (Admin edits to DEO-created records affect accuracy)
     */
    public function affectsAccuracy()
    {
        // Must be an admin edit
        if (!$this->isAdminEdit()) {
            return false;
        }

        // Original creator must be a DEO
        if (!$this->originalCreator || !$this->originalCreator->hasRole('deo')) {
            return false;
        }

        // Must not be the same person (DEO editing their own record doesn't affect accuracy)
        return $this->modifier_id !== $this->original_creator_id;
    }

    /**
     * Get accuracy statistics for a user
     */
    public static function getAccuracyStats($userId)
    {
        $totalRecords = DefectReport::where('created_by', $userId)->count() +
                       PurchaseOrder::where('created_by', $userId)->count();

        $editedRecords = self::where('original_creator_id', $userId)
                           ->whereHas('modifier', function($query) {
                               $query->whereHas('roles', function($roleQuery) {
                                   $roleQuery->whereIn('name', ['admin', 'super_admin']);
                               });
                           })
                           ->distinct(['record_id', 'record_type'])
                           ->count();

        if ($totalRecords === 0) {
            return [
                'total_records' => 0,
                'edited_records' => 0,
                'accurate_records' => 0,
                'accuracy_percentage' => 0
            ];
        }

        $accurateRecords = $totalRecords - $editedRecords;
        $accuracyPercentage = ($accurateRecords / $totalRecords) * 100;

        return [
            'total_records' => $totalRecords,
            'edited_records' => $editedRecords,
            'accurate_records' => $accurateRecords,
            'accuracy_percentage' => round($accuracyPercentage, 2)
        ];
    }
}
