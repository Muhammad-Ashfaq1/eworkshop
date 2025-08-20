<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use SoftDeletes, HasFactory;
    
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

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }
}
