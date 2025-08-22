<?php

namespace App\Repositories;

use App\Interfaces\DefectReportRepositoryInterface;
use App\Models\DefectReport;
use App\Models\Work;
use App\Helpers\FileUploadManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DefectReportRepository implements DefectReportRepositoryInterface
{
    public function getDefectReportListing($data, $user): JsonResponse
    {
        $pageNumber = ($data['start'] / $data['length']) + 1; // gets the page number
        $pageLength = $data['length'];
        $skip = ($pageNumber - 1) * $pageLength; // calculates number of records to be skipped
        $search['search'] = $data['search']['value']; // gets the search value from request

        if (isset($data['order'])) {
            $index = $data['order'][0]['column'];
            $search['direction'] = $data['order'][0]['dir'];
            $search['column_name'] = $data['columns'][$index]['name'];
        }

        $query = DefectReport::forUser($user)
            ->with(['creator', 'works', 'vehicle', 'location', 'fleetManager', 'mvi']);
        
        // Apply search filter
        if (!empty($search['search'])) {
            $query->where(function($q) use ($search) {
                $q->where('driver_name', 'like', '%' . $search['search'] . '%')
                  ->orWhere('date', 'like', '%' . $search['search'] . '%')
                  ->orWhere('type', 'like', '%' . $search['search'] . '%')
                  ->orWhereHas('vehicle', function($vehicleQuery) use ($search) {
                      $vehicleQuery->where('vehicle_number', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('location', function($locationQuery) use ($search) {
                      $locationQuery->where('name', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('creator', function($creatorQuery) use ($search) {
                      $creatorQuery->where('name', 'like', '%' . $search['search'] . '%');
                  });
            });
        }

        // Apply ordering
        if (isset($search['column_name']) && isset($search['direction'])) {
            $query->orderBy($search['column_name'], $search['direction']);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $recordsFiltered = $recordsTotal = $query->count(); // counts the total records filtered
        
        $response['draw'] = $data['draw'];
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;
        $response['data'] = $query->skip($skip)->take($pageLength)->get()->toArray(); // makes data using resource
        
        return response()->json($response, Response::HTTP_OK);
    }

    public function getDefectReportById($id)
    {
        return DefectReport::with(['creator', 'works', 'vehicle', 'location', 'fleetManager', 'mvi'])->find($id);
    }

    public function createDefectReport($data): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create defect report
            $defectReport = DefectReport::create([
                'vehicle_id' => $data['vehicle_id'],
                'location_id' => $data['location_id'],
                'driver_name' => $data['driver_name'],
                'fleet_manager_id' => $data['fleet_manager_id'],
                'mvi_id' => $data['mvi_id'],
                'date' => $data['date'],
                'type' => $data['type'] ?? DefectReport::TYPE_DEFECT_REPORT,
                'attachment_url' => null,
                'created_by' => Auth::id(),
            ]);

            // Handle file upload
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                $file = FileUploadManager::uploadFile($data['attachment_url'], 'defect_reports/');
                $defectReport->update(['attachment_url' => $file['path']]);
            }

            // Create works
            if (isset($data['works']) && is_array($data['works'])) {
                foreach ($data['works'] as $workData) {
                    Work::create([
                        'defect_report_id' => $defectReport->id,
                        'work' => $workData['work'],
                        'type' => $workData['type'],
                        'quantity' => $workData['quantity'] ?? null,
                        'vehicle_part_id' => $workData['vehicle_part_id'] ?? null,
                    ]);
                }
            }

            DB::commit();

            $response = [
                'defectReport' => $defectReport,
                'success' => true,
                'message' => 'Defect report created successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create defect report. ' . $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function updateDefectReport($id, $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $defectReport = DefectReport::find($id);
            
            if (!$defectReport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Defect report not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Update defect report
            $defectReport->update([
                'vehicle_id' => $data['vehicle_id'],
                'location_id' => $data['location_id'],
                'driver_name' => $data['driver_name'],
                'fleet_manager_id' => $data['fleet_manager_id'],
                'mvi_id' => $data['mvi_id'],
                'date' => $data['date'],
                'type' => $data['type'],
            ]);

            // Handle file upload
            if (isset($data['attach_file']) && $data['attach_file']) {
                // Delete old file if exists
                if ($defectReport->attach_file) {
                    FileUploadManager::deleteFile($defectReport->attach_file);
                }

                $file = FileUploadManager::uploadFile($data['attach_file'], 'defect_reports/');
                $defectReport->update(['attach_file' => $file['path']]);
            }

            // Delete existing works and create new ones
            $defectReport->works()->delete();

            if (isset($data['works']) && is_array($data['works'])) {
                foreach ($data['works'] as $workData) {
                    Work::create([
                        'defect_report_id' => $defectReport->id,
                        'work' => $workData['work'],
                        'type' => $workData['type'],
                        'quantity' => $workData['quantity'] ?? null,
                        'vehicle_part_id' => $workData['vehicle_part_id'] ?? null,
                    ]);
                }
            }

            DB::commit();

            $response = [
                'defectReport' => $defectReport,
                'success' => true,
                'message' => 'Defect report updated successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update defect report. ' . $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function deleteDefectReport($id): JsonResponse
    {
        try {
            $defectReport = DefectReport::find($id);
            
            if (!$defectReport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Defect report not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Delete attached file if exists
            if ($defectReport->attach_file) {
                FileUploadManager::deleteFile($defectReport->attach_file);
            }

            $defectReport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Defect report deleted successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete defect report. ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDefectReportsForUser($user, $perPage = 15)
    {
        return DefectReport::forUser($user)
            ->with(['creator', 'works', 'vehicle', 'location', 'fleetManager', 'mvi'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function canViewReport($user, $defectReport): bool
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
