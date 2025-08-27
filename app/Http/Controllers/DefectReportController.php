<?php

namespace App\Http\Controllers;

use App\Exports\DefectReportExport;
use App\Http\Requests\DefectReportRequest;
use App\Interfaces\DefectReportRepositoryInterface;
use App\Models\DefectReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DefectReportController extends Controller
{
    private $defectReportRepository;

    public function __construct(DefectReportRepositoryInterface $defectReportRepository)
    {
        $this->defectReportRepository = $defectReportRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('read_defect_reports');
        
        $user = Auth::user();

        // Get defect reports based on user role
        $defectReports = $this->defectReportRepository->getDefectReportsForUser($user, 15);

        return view('defect_reports.index', compact('defectReports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_defect_reports');
        
        return view('defect_reports.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(DefectReport $defectReport)
    {
        $this->authorize('read_defect_reports');
        
        $user = Auth::user();

        // Check if user can view this specific report
        if (!$this->canViewReport($user, $defectReport)) {
            abort(403, 'You are not authorized to view this defect report.');
        }

        return view('defect_reports.show', compact('defectReport'));
    }

    /**
     * Get defect reports listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getDefectReportListing(Request $request): JsonResponse
    {
        $this->authorize('read_defect_reports');
        
        $user = Auth::user();
        return $this->defectReportRepository->getDefectReportListing($request->all(), $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DefectReportRequest $request)
    {
        $this->authorize('create_defect_reports');

        return $this->defectReportRepository->createDefectReport($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('read_defect_reports');
        
        $defectReport = $this->defectReportRepository->getDefectReportById($id);

        if (!$defectReport) {
            return response()->json([
                'success' => false,
                'message' => 'Defect report not found'
            ], 404);
        }

        // Check if user can view this specific report
        $user = Auth::user();
        if (!$this->canViewReport($user, $defectReport)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this defect report.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'defectReport' => $defectReport,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DefectReportRequest $request, DefectReport $defectReport)
    {
        $this->authorize('update_defect_reports');

        $user = Auth::user();

        // Check if user can update this specific report
        if (!$this->canViewReport($user, $defectReport)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this defect report.'
            ], 403);
        }

        return $this->defectReportRepository->updateDefectReport($defectReport->id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefectReport $defectReport)
    {
        $this->authorize('delete_defect_reports');

        $user = Auth::user();

        // Check if user can delete this specific report
        if (!$this->canViewReport($user, $defectReport)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this defect report.'
            ], 403);
        }

        return $this->defectReportRepository->deleteDefectReport($defectReport->id);
    }

    /**
     * Check if user can view the report
     */
    private function canViewReport($user, $defectReport)
    {
        // Super admin and admin can view all reports
        if ($user->can('read_defect_reports')) {
            return true;
        }
        
        // DEO can only view their own reports
        if ($user->hasRole('deo')) {
            return $defectReport->created_by == $user->id;
        }
        
        // Fleet manager can view reports assigned to them
        if ($user->hasRole('fleet_manager')) {
            return $defectReport->fleet_manager_id == $user->id;
        }
        
        // MVI can view reports assigned to them
        if ($user->hasRole('mvi')) {
            return $defectReport->mvi_id == $user->id;
        }

        return false;
    }

    /**
     * Export defect reports
     */
    public function exportReports()
    {
        $this->authorize('export_data');
        
        return Excel::download(new DefectReportExport, 'defect_reports.xlsx');
    }
}
