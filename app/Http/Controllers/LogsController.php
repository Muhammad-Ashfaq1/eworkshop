<?php

namespace App\Http\Controllers;

use App\Models\ReportAudit;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        $report_logs = ReportAudit::with('modifier')->get();
        return view('admin.report-logs.index', compact('report_logs'));
    }
}
