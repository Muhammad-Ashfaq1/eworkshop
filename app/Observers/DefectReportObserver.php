<?php

namespace App\Observers;

use App\Models\ReportAudit;
use App\Models\DefectReport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DefectReportObserver
{

    /**
     * Handle the DefectReport "created" event.
     */
    public function created(DefectReport $defectReport): void
    {

    }
    public function updating(DefectReport $report)
    {

    }


    /**
     * Handle the DefectReport "updated" event.
     */
    public function updated(DefectReport $defectReport): void
    {
         $user = Auth::user();

        ReportAudit::create([
            'modifier_id' => $user ? $user->id : null,
            'before_changing_record' => $defectReport->getOriginal(),
            'after_changing_record' => $defectReport->getAttributes(),
            'type' => 'defect_report',
        ]);
    }

    /**
     * Handle the DefectReport "deleted" event.
     */
    public function deleted(DefectReport $defectReport): void
    {
        //
    }

    /**
     * Handle the DefectReport "restored" event.
     */
    public function restored(DefectReport $defectReport): void
    {
        //
    }

    /**
     * Handle the DefectReport "force deleted" event.
     */
    public function forceDeleted(DefectReport $defectReport): void
    {
        //
    }
}
