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


}



?>
