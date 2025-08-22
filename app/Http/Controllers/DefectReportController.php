<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadManager;
use App\Http\Requests\DefectReportRequest;
use App\Models\DefectReport;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefectReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Get defect reports based on user role
        $defectReports = DefectReport::forUser($user)
            ->with(['creator', 'works', 'vehicle', 'location', 'fleetManager', 'mvi'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('defect_reports.index', compact('defectReports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DefectReportRequest $request)
    {
        // Only DEO can create defect reports
        if (! Auth::user()->hasRole('deo')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Create defect report
            $defectReport = DefectReport::create([
                'vehicle_id' => $request->vehicle_id,
                'location_id' => $request->location_id,
                'driver_name' => $request->driver_name,
                'fleet_manager_id' => $request->fleet_manager_id,
                'mvi_id' => $request->mvi_id,
                'date' => $request->date,
                'type' => $request->type ?? DefectReport::TYPE_DEFECT_REPORT,
                'attachment_url' => null,
                'created_by' => Auth::id(),
            ]);

            // Handle file upload
            if ($request->hasFile('attachment_url')) {
                $file = FileUploadManager::uploadFile($request->file('attachment_url'), 'defect_reports/');
                $defectReport->update(['attachment_url' => $file['path']]);
            }

            // Create works
            foreach ($request->works as $workData) {
                Work::create([
                    'defect_report_id' => $defectReport->id,
                    'work' => $workData['work'],
                    'type' => $workData['type'],
                    'quantity' => $workData['quantity'] ?? null,
                    'vehicle_part_id' => $workData['vehicle_part_id'] ?? null,
                ]);
            }

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Defect report created successfully.',
                ]);
            }

            return redirect()->route('defect-reports.index')
                ->with('success', 'Defect report created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create defect report. '.$e->getMessage(),
                ], 422);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create defect report. '.$e->getMessage()]);
        }
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

        try {
            DB::beginTransaction();

            // Update defect report
            $defectReport->update([
                'vehicle_id' => $request->vehicle_id,
                'location_id' => $request->location_id,
                'driver_name' => $request->driver_name,
                'fleet_manager_id' => $request->fleet_manager_id,
                'mvi_id' => $request->mvi_id,
                'date' => $request->date,
                'type' => $request->type,
            ]);

            // Handle file upload
            if ($request->hasFile('attach_file')) {
                // Delete old file if exists
                if ($defectReport->attach_file) {
                    FileUploadManager::deleteFile($defectReport->attach_file);
                }

                $file = FileUploadManager::uploadFile($request->file('attach_file'), 'defect_reports/');
                $defectReport->update(['attach_file' => $file['path']]);
            }

            // Delete existing works and create new ones
            $defectReport->works()->delete();

            foreach ($request->works as $workData) {
                Work::create([
                    'defect_report_id' => $defectReport->id,
                    'work' => $workData['work'],
                    'type' => $workData['type'],
                    'quantity' => $workData['quantity'] ?? null,
                    'vehicle_part_id' => $workData['vehicle_part_id'] ?? null,
                ]);
            }

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Defect report updated successfully.',
                ]);
            }

            return redirect()->route('defect-reports.index')
                ->with('success', 'Defect report updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update defect report. '.$e->getMessage(),
                ], 422);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update defect report. '.$e->getMessage()]);
        }
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

        try {
            // Delete attached file if exists
            if ($defectReport->attach_file) {
                FileUploadManager::deleteFile($defectReport->attach_file);
            }

            $defectReport->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Defect report deleted successfully.',
                ]);
            }

            return redirect()->route('defect-reports.index')
                ->with('success', 'Defect report deleted successfully.');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete defect report. '.$e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Failed to delete defect report. '.$e->getMessage()]);
        }
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
}
