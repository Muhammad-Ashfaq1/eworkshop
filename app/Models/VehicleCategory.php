<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const IS_ACTIVE = 1;

    const IS_INACTIVE = 0;

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_category_id');
    }
}
