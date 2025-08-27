<?php

namespace App\Models;

use App\Models\Traits\AttachmentStatus;
use Illuminate\Database\Eloquent\Model;

class FleetManager extends Model
{
    use AttachmentStatus;
    protected $fillable = ['name', 'type', 'is_active'];







    const TYPE_FLEET_MANAGER = 'fleet_manager';
    const TYPE_MVI = 'mvi';

}
