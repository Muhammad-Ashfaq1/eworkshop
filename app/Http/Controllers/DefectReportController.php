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
        $user = Auth::user();

        // Get defect reports based on user role
        $defectReports = $this->defectReportRepository->getDefectReportsForUser($user, 15);

        return view('defect_reports.index', compact('defectReports'));
    }

    /**
     * Get defect reports listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getDefectReportListing(Request $request): JsonResponse
    {
        $user = Auth::user();
        return $this->defectReportRepository->getDefectReportListing($request->all(), $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DefectReportRequest $request)
    {
        // Only DEO admin can create defect reports
        if (! Auth::user()->hasRole('deo') && ! Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $this->defectReportRepository->createDefectReport($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $defectReport = $this->defectReportRepository->getDefectReportById($id);

        if (!$defectReport) {
            return response()->json([
                'success' => false,
                'message' => 'Defect report not found'
            ], 404);
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
        $user = Auth::user();

        // Only super admin and admin can update
        if (! $user->hasRole('super_admin') && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $this->defectReportRepository->updateDefectReport($defectReport->id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefectReport $defectReport)
    {
        $user = Auth::user();

        // Only super admin and admin can delete
        if (! $user->hasRole('super_admin') && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $this->defectReportRepository->deleteDefectReport($defectReport->id);
    }

    /**
     * Check if user can view the report
     */
    private function canViewReport($user, $defectReport)
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('deo')) {
            return $defectReport->created_by == $user->id;
        } elseif ($user->hasRole('fleet_manager')) {
            return $defectReport->fleet_manager_id == $user->id;
        } elseif ($user->hasRole('mvi')) {
            return $defectReport->mvi_id == $user->id;
        }

        return false;
    }

    public function exportReports()
    {
        return Excel::download(new DefectReportExport, 'defect_reports.xlsx');
    }
}
