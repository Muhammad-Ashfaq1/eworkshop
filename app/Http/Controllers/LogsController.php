<?php

namespace App\Http\Controllers;

use App\Models\ReportAudit;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        $this->authorize('view_report_logs');
        
        $report_logs = ReportAudit::with('modifier')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.report-logs.index', compact('report_logs'));
    }

    /**
     * Get log details for modal display
     */
    public function getLogDetails($id)
    {
        $this->authorize('view_report_logs');
        
        $log = ReportAudit::with('modifier')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'log' => $log
        ]);
    }

    /**
     * Delete a report log (Super Admin only)
     */
    public function destroy($id)
    {
        $this->authorize('delete_report_logs');
        
        $log = ReportAudit::findOrFail($id);
        $log->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Report log deleted successfully'
        ]);
    }
}
