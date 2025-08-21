<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_number',
        'location_id',
        'vehicle_category_id',
        'condition',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const IS_ACTIVE = 1;
    const IS_INACTIVE = 0;



    const CONDITION_NEW = 'new';
    const CONDITION_OLD = 'old';

    // Relationships
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }
}
