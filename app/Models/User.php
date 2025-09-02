<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\AttachmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use AttachmentStatus, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'image_url',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
    
    /**
     * Get defect reports created by this user
     */
    public function defectReports()
    {
        return $this->hasMany(DefectReport::class, 'created_by');
    }
    
    /**
     * Get purchase orders created by this user
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by');
    }
    
    /**
     * Get report audits where this user was the modifier
     */
    public function modifiedReports()
    {
        return $this->hasMany(ReportAudit::class, 'modifier_id');
    }
    
    /**
     * Get report audits where this user was the original creator
     */
    public function originalReports()
    {
        return $this->hasMany(ReportAudit::class, 'original_creator_id');
    }
    
    /**
     * Get user statistics for dashboard
     */
    public function getDashboardStats()
    {
        $stats = [
            'defect_reports' => [
                'total' => $this->defectReports()->count(),
                'today' => $this->defectReports()->whereDate('created_at', today())->count(),
                'this_week' => $this->defectReports()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => $this->defectReports()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ],
            'purchase_orders' => [
                'total' => $this->purchaseOrders()->count(),
                'today' => $this->purchaseOrders()->whereDate('created_at', today())->count(),
                'this_week' => $this->purchaseOrders()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => $this->purchaseOrders()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ]
        ];
        
        // Add accuracy stats for DEO users
        if ($this->hasRole('deo')) {
            $stats['accuracy'] = ReportAudit::getAccuracyStats($this->id);
        }
        
        return $stats;
    }
    
    /**
     * Get recent reports created by this user
     */
    public function getRecentReports($limit = 5)
    {
        $defectReports = $this->defectReports()
            ->with(['vehicle', 'location', 'fleetManager', 'mvi'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($report) {
                $report->report_type = 'defect_report';
                return $report;
            });
            
        $purchaseOrders = $this->purchaseOrders()
            ->with(['defectReport.vehicle', 'defectReport.location'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($order) {
                $order->report_type = 'purchase_order';
                return $order;
            });
        
        return $defectReports->concat($purchaseOrders)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }
}
