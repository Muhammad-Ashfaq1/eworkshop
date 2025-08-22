<?php

namespace App\Models;

use App\Models\Traits\AttachmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefectReport extends Model
{
    use AttachmentStatus, HasFactory, SoftDeletes;

    protected $fillable = [
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

    const ROLE_FLEET_MANAGER = 'fleet_manager';

    const ROLE_MVI = 'mvi';

    const ROLE_DEO = 'deo';

    public static function getTypes()
    {
        return [
            self::TYPE_DEFECT_REPORT => 'Defect Report',
            self::TYPE_PURCHASE_ORDER => 'Purchase Order',
        ];
    }

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function fleetManager()
    {
        return $this->belongsTo(User::class, 'fleet_manager_id');
    }

    public function mvi()
    {
        return $this->belongsTo(User::class, 'mvi_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function defectWorks()
    {
        return $this->hasMany(Work::class)->defects();
    }

    public function purchaseOrderWorks()
    {
        return $this->hasMany(Work::class)->purchaseOrders();
    }

    // Scopes for role-based filtering
    public function scopeForUser($query, $user)
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return $query;
        } elseif ($user->hasRole('deo')) {
            return $query->where('created_by', $user->id); // Only their own reports
        } elseif ($user->hasRole('fleet_manager')) {
            return $query->where('fleet_manager_id', $user->id);
        } elseif ($user->hasRole('mvi')) {
            return $query->where('mvi_id', $user->id);
        }

        return $query->where('id', 0); // No access for other roles
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
