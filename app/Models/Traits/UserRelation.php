<?php

namespace App\Models\Traits;
use App\Models\ReportAudit;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;

trait UserRelation
{

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




}



?>