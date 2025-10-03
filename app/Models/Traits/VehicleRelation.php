<?php

namespace App\Models\Traits;
use App\Models\Location;
use App\Models\VehicleCategory;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;

trait VehicleRelation{

 public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    public function defectReports()
    {
        return $this->hasMany(DefectReport::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'defect_report_id', 'id')
                    ->join('defect_reports', 'purchase_orders.defect_report_id', '=', 'defect_reports.id')
                    ->where('defect_reports.vehicle_id', '=', 'vehicles.id');
    }

}



?>
