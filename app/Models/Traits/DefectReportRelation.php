<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Work;
use App\Models\Vehicle;
use App\Models\Location;
use App\Models\FleetManager;
use App\Models\PurchaseOrder;

trait DefectReportRelation{

public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function fleetManager()
    {
        return $this->belongsTo(FleetManager::class, 'fleet_manager_id');
    }

    public function mvi()
    {
        return $this->belongsTo(FleetManager::class, 'mvi_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

        public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function defectWorks()
    {
        return $this->hasMany(Work::class)->defects();
    }

    public function purchaseOrderWorks()
    {
        return $this->hasMany(Work::class)->purchaseOrders();
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

}

?>
