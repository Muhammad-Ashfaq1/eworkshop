<?php

namespace App\Models;

use App\Models\Traits\AttachmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetManager extends Model
{
    use AttachmentStatus, HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'type', 'is_active'];

    const TYPE_FLEET_MANAGER = 'fleet_manager';
    const TYPE_MVI = 'mvi';

    public static function getTypes()
    {
        return [
            self::TYPE_FLEET_MANAGER => 'Fleet Manager',
            self::TYPE_MVI => 'MVI',
        ];
    }
}
