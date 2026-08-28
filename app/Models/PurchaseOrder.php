<?php

namespace App\Models;

use App\Enums\PurchaseOrderReceivedBy;
use App\Models\Traits\AttachmentStatus;
use App\Models\Traits\PurchaseOrderRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use AttachmentStatus, HasFactory, PurchaseOrderRelation, SoftDeletes;

    protected $fillable = [
        'defect_report_id',
        'po_no',
        'issue_date',
        'received_by',
        'received_by_other',
        'acc_amount',
        'attachment_url',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'acc_amount' => 'decimal:2',
        'received_by' => PurchaseOrderReceivedBy::class,
    ];

    protected $appends = ['received_by_display'];

    public function getReceivedByDisplayAttribute(): string
    {
        return $this->received_by === PurchaseOrderReceivedBy::Other
            ? ($this->received_by_other ?: PurchaseOrderReceivedBy::Other->label())
            : PurchaseOrderReceivedBy::StoreKeeper->label();
    }

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
