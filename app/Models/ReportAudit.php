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
        'before_changing_record_readable',
        'after_changing_record_readable',
        'type',
    ];
     protected $casts = [
        'before_changing_record' => 'array',
        'after_changing_record' => 'array',
        'before_changing_record_readable' => 'array',
        'after_changing_record_readable' => 'array',
    ];

     public function modifier()
    {
        return $this->belongsTo(User::class, 'modifier_id');
    }
}
