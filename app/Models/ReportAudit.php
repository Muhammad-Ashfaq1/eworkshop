<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportAudit extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'modifier_id',
        'before_changing_record',
        'after_changing_record',
        'type',
    ];
     protected $casts = [
        'before_changing_record' => 'array',
        'after_changing_record' => 'array',
    ];

     public function modifier()
    {
        return $this->belongsTo(User::class, 'modifier_id');
    }
}
