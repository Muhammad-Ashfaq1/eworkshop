<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'vehicle_number',
        'town',
        'vehicle_category',
        'condition',
        'is_active',
    ];
}
