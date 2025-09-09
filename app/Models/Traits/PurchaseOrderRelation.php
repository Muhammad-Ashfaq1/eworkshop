<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Work;
use App\Models\DefectReport;

trait PurchaseOrderRelation{

    public function defectReport()
    {
        return $this->belongsTo(DefectReport::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function works()
    {
        return $this->hasMany(Work::class, 'purchase_order_id');
    }


}

?>
