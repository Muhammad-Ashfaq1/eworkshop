<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetManager extends Model
{
    protected $fillable = ['name', 'type', 'is_active'];







    const TYPE_FLEET_MANAGER = 'fleet_manager';
    const TYPE_MVI = 'mvi';

}
