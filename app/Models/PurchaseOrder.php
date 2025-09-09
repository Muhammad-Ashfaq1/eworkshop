<?php

namespace App\Models;

use App\Models\Traits\AttachmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\PurchaseOrderRelation;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    use AttachmentStatus, HasFactory, SoftDeletes,PurchaseOrderRelation;

    protected $fillable = [
        'defect_report_id',
        'po_no',
        'issue_date',
        'received_by',
        'acc_amount',
        'attachment_url',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'acc_amount' => 'decimal:2',
    ];

    // Relationships

    // Scopes for role-based filtering
    public function scopeForUser($query, $user)
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return $query;
        } elseif ($user->hasRole('deo')) {
            return $query->where('purchase_orders.created_by', $user->id); // Only their own POs
        }

        return $query->where('id', 0);
    }
}
