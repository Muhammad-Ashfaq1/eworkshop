<?php

namespace App\Http\Controllers;

use App\Exports\DefectReportExport;
use App\Http\Requests\DefectReportRequest;
use App\Http\Requests\UpdateDefectReportRequest;
use App\Interfaces\DefectReportRepositoryInterface;
use App\Models\DefectReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $user = Auth::user();
        $validatedData = $request->validated();
        
        Log::info('Defect Report Creation Started', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'vehicle_id' => $validatedData['vehicle_id'] ?? null,
            'location_id' => $validatedData['location_id'] ?? null,
            'driver_name' => $validatedData['driver_name'] ?? null,
            'request_data' => $validatedData
        ]);

        try {
            $result = $this->defectReportRepository->createDefectReport($validatedData);
            
            if ($result->getData()->success) {
                Log::info('Defect Report Creation Successful', [
                    'user_id' => $user->id,
                    'defect_report_id' => $result->getData()->defectReport->id ?? null
                ]);
            } else {
                Log::warning('Defect Report Creation Failed', [
                    'user_id' => $user->id,
                    'error_message' => $result->getData()->message ?? 'Unknown error',
                    'request_data' => $validatedData
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Defect Report Creation Exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $validatedData
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating defect report.'
            ], 500);
        }
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

        return response()->json([
            'success' => true,
            'defectReport' => $defectReport,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDefectReportRequest $request, DefectReport $defectReport)
    {
        $this->authorize('update_defect_reports');

        $user = Auth::user();
        $validatedData = $request->validated();
        
        Log::info('Defect Report Update Started', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'defect_report_id' => $defectReport->id,
            'vehicle_id' => $validatedData['vehicle_id'] ?? null,
            'location_id' => $validatedData['location_id'] ?? null,
            'request_data' => $validatedData
        ]);

        try {
            $result = $this->defectReportRepository->updateDefectReport($defectReport->id, $validatedData);
            
            if ($result->getData()->success) {
                Log::info('Defect Report Update Successful', [
                    'user_id' => $user->id,
                    'defect_report_id' => $defectReport->id
                ]);
            } else {
                Log::warning('Defect Report Update Failed', [
                    'user_id' => $user->id,
                    'defect_report_id' => $defectReport->id,
                    'error_message' => $result->getData()->message ?? 'Unknown error',
                    'request_data' => $validatedData
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Defect Report Update Exception', [
                'user_id' => $user->id,
                'defect_report_id' => $defectReport->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $validatedData
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating defect report.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefectReport $defectReport)
    {
        $this->authorize('delete_defect_reports');
        $user = Auth::user();
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

    public function archieved()
    {
        $archievedDefectReports = DefectReport::with( 'vehicle','location','fleetManager','mvi','creator')->onlyTrashed()->get();
        return view('defect_reports.archieved', compact('archievedDefectReports'));
    }

    public function restoreArchieved($id)
    {
        $this->authorize('restore_defect_reports');
        $defectReport = DefectReport::withTrashed()->find($id);
        if (!$defectReport) {
            return response()->json(['success' => false, 'message' => 'Defect report not found'], 404);
        }
        $defectReport->restore();
        return response()->json(['success' => true, 'message' => 'Defect report restored successfully']);
    }
}
