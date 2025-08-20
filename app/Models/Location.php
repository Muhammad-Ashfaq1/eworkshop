<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','slug','is_active','location_type'];



    const LOCATION_TYPE_TOWN = 'town';
    const LOCATION_TYPE_WORKSHOP = 'workshop';

    const IS_ACTIVE = true;
    const IS_INACTIVE = false;

}
