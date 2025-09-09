<?php

namespace App\Models;

use App\Models\Traits\AttachmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Traits\DefectReportRelation;

class DefectReport extends Model
{
    use AttachmentStatus, HasFactory, SoftDeletes,DefectReportRelation;

    protected $fillable = [
        'reference_number',
        'vehicle_id',
        'location_id',
        'driver_name',
        'fleet_manager_id',
        'mvi_id',
        'date',
        'attachment_url',
        'type',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Constants for report types
    const TYPE_DEFECT_REPORT = 'defect_report';
    const TYPE_PURCHASE_ORDER = 'purchase_order';

    // constants for roles
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_DEO = 'deo';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($defectReport) {
            if (empty($defectReport->reference_number)) {
                $defectReport->reference_number = self::generateReferenceNumber();
            }
        });
    }

    /**
     * Generate a unique reference number for defect reports
     */
    public static function generateReferenceNumber()
    {
        do {
            $reference = 'DR-'. strtoupper(Str::random(5));
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }

    public static function getTypes()
    {
        return [
            self::TYPE_DEFECT_REPORT => 'Defect Report',
            self::TYPE_PURCHASE_ORDER => 'Purchase Order',
        ];
    }

    // Relationships


    /**
     * Get field labels for display
     */
    public static function getFieldLabels()
    {
        return [
            'reference_number' => 'Reference Number',
            'vehicle_id' => 'Vehicle',
            'location_id' => 'Location',
            'driver_name' => 'Driver Name',
            'fleet_manager_id' => 'Fleet Manager',
            'mvi_id' => 'MVI',
            'date' => 'Date',
            'attachment_url' => 'Attachment',
            'type' => 'Type',
            'created_by' => 'Created By',
        ];
    }


    // Scopes for role-based filtering
    public function scopeForUser($query, $user)
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return $query;
        } elseif ($user->hasRole('deo')) {
            return $query->where('created_by', $user->id); // Only their own reports
        }

        return $query->where('id', 0);
    }

    // Scopes for filtering by type
    public function scopeDefectReports($query)
    {
        return $query->where('type', self::TYPE_DEFECT_REPORT);
    }

    public function scopePurchaseOrders($query)
    {
        return $query->where('type', self::TYPE_PURCHASE_ORDER);
    }
}
