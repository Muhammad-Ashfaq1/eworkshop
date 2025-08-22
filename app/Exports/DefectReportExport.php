<?php

namespace App\Exports;

use App\Models\DefectReport;
use Maatwebsite\Excel\Concerns\FromCollection;

class DefectReportExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $report = DefectReport::with(['vehicle', 'location', 'fleetManager', 'mvi', 'creator'])->get();

        $defectReports = $report->map(function ($report) {
            // Map the data to match the desired export format
            return (object) [
                'Vehicle Number' => $report->vehicle->vehicle_number ?? 'N/A',
                'Town/Office' => $report->location->name ?? 'N/A',
                'Driver Name' => $report->driver_name,
                'Fleet Manager' => $report->fleetManager->full_name ?? 'N/A',
                'MVI' => $report->mvi->full_name ?? 'N/A',
                'Date' => $report->date->format('d/m/Y'),
                'Type' => ucfirst(str_replace('_', ' ', ucfirst($report->type))),
                'Created By' => $report->creator->full_name,
                'Created At' => $report->created_at->format('d/m/Y H:i'),
            ];
        });

        return $defectReports;
    }

    public function headings(): array
    {
        return [
            'Vehicle Number',
            'Town/Office',
            'Driver Name',
            'Fleet Manager',
            'MVI',
            'Date',
            'Type',
            'Created By',
            'Created At',
        ];
    }
}
