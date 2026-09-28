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

    /**
     * Get the Financial Year (1st July to 30th June) date range for a given date.
     *
     * @param  \Carbon\Carbon|string|\DateTimeInterface|null  $date
     * @return array{start_date: string, end_date: string, label: string, full_label: string}
     */
    public static function getFiscalYearRange($date = null): array
    {
        $carbon = $date ? \Carbon\Carbon::parse($date) : now();
        $year = (int) $carbon->format('Y');
        $month = (int) $carbon->format('n');

        $startYear = $month >= 7 ? $year : $year - 1;
        $endYear = $startYear + 1;

        return [
            'start_date' => sprintf('%04d-07-01', $startYear),
            'end_date' => sprintf('%04d-06-30', $endYear),
            'label' => "FY {$startYear}-".substr((string) $endYear, -2),
            'full_label' => "{$startYear}-{$endYear}",
        ];
    }

    /**
     * Check if a PO number already exists in the given date's financial year.
     *
     * @param  string|int|null  $poNo
     * @param  \Carbon\Carbon|string|\DateTimeInterface|null  $date
     * @param  int|null  $ignoreId
     */
    public static function isPoNumberExistsInFiscalYear($poNo, $date, $ignoreId = null): bool
    {
        if (empty($poNo) || empty($date)) {
            return false;
        }

        try {
            $fy = self::getFiscalYearRange($date);
        } catch (\Exception $e) {
            return false;
        }

        return self::where('po_no', $poNo)
            ->whereBetween('issue_date', [$fy['start_date'], $fy['end_date']])
            ->when($ignoreId, function ($query, $ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();
    }
}
