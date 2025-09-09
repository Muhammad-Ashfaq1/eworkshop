<?php

namespace App\Models\Traits;
use App\Models\Location;
use App\Models\VehicleCategory;

trait VehicleRelation{

 public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }


}



?>
