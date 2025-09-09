<?php

namespace App\Models\Traits;
use App\Models\VehiclePart;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;

trait WorkRelation{

    public function defectReport()
    {
        return $this->belongsTo(DefectReport::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function vehiclePart()
    {
        return $this->belongsTo(VehiclePart::class);
    }



}



?>
