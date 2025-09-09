<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;

trait ReportAuditRelation{

     public function modifier()
    {
        return $this->belongsTo(User::class, 'modifier_id');
    }

    public function originalCreator()
    {
        return $this->belongsTo(User::class, 'original_creator_id');
    }

    /**
     * Get the defect report (if this audit is for a defect report)
     */
    public function defectReport()
    {
        return $this->belongsTo(DefectReport::class, 'record_id')->where('record_type', 'defect_report');
    }

    /**
     * Get the purchase order (if this audit is for a purchase order)
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'record_id')->where('record_type', 'purchase_order');
    }




}



?>
