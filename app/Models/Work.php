<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'defect_report_id',
        'purchase_order_id',
        'work',
        'type',
        'quantity',
        'vehicle_part_id',
    ];

    // Relationships
    public function defectReport()
    {
        return $this->belongsTo(DefectReport::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function vehiclePart()
    {
        return $this->belongsTo(VehiclePart::class);
    }

    // Constants for work types
    const TYPE_DEFECT = 'defect';

    const TYPE_PURCHASE_ORDER = 'purchase_order';

    public static function getTypes()
    {
        return [
            self::TYPE_DEFECT => 'Defect',
            self::TYPE_PURCHASE_ORDER => 'Purchase Order',
        ];
    }

    // Scopes
    public function scopeDefects($query)
    {
        return $query->where('type', self::TYPE_DEFECT);
    }

    public function scopePurchaseOrders($query)
    {
        return $query->where('type', self::TYPE_PURCHASE_ORDER);
    }

    // Accessors
    public function getWorkDisplayAttribute()
    {
        if ($this->type === self::TYPE_PURCHASE_ORDER && $this->vehiclePart) {
            return $this->vehiclePart->name.($this->quantity ? " (Qty: {$this->quantity})" : '');
        }

        return $this->work ?? 'N/A';
    }
}
