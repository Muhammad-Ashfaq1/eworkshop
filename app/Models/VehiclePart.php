<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehiclePart extends Model
{
    use SoftDeletes;

    protected $table = 'vehicle_parts';
    
    protected $fillable = ['name', 'slug', 'is_active', 'e_id'];
    
    public $timestamps = true;
}
