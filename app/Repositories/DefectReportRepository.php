<?php

namespace App\Repositories;

use App\Interfaces\DefectReportRepositoryInterface;
use App\Models\DefectReport;
use App\Models\Work;
use App\Helpers\FileUploadManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DefectReportRepository implements DefectReportRepositoryInterface
{
    public function getDefectReportListing($data, $user): JsonResponse
    {
        // Set default values for DataTables parameters
        $data = array_merge([
            'start' => 0,
            'length' => 10,
            'draw' => 1,
            'search' => ['value' => ''],
            'order' => [],
            'columns' => []
        ], $data);

        $pageNumber = ($data['start'] / $data['length']) + 1; // gets the page number
        $pageLength = $data['length'];
        $skip = ($pageNumber - 1) * $pageLength; // calculates number of records to be skipped
        $search['search'] = $data['search']['value']; // gets the search value from request

        if (isset($data['order']) && !empty($data['order'])) {
            $index = $data['order'][0]['column'];
            $search['direction'] = $data['order'][0]['dir'];
            $search['column_name'] = $data['columns'][$index]['name'] ?? 'created_at';
        }

        $query = DefectReport::forUser($user)
            ->with(['creator', 'works', 'vehicle', 'location', 'fleetManager', 'mvi']);

        // Apply date range filter
        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $query->where('date', '>=', $data['start_date']);
        }
        if (isset($data['end_date']) && !empty($data['end_date'])) {
            $query->where('date', '<=', $data['end_date']);
        }

        // Apply search filter
        if (!empty($search['search'])) {
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', '%' . $search['search'] . '%')
                  ->orWhere('driver_name', 'like', '%' . $search['search'] . '%')
                  ->orWhere('date', 'like', '%' . $search['search'] . '%')
                  ->orWhere('type', 'like', '%' . $search['search'] . '%')
                  ->orWhereHas('vehicle', function($vehicleQuery) use ($search) {
                      $vehicleQuery->where('vehicle_number', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('location', function($locationQuery) use ($search) {
                      $locationQuery->where('name', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('creator', function($creatorQuery) use ($search) {
                      $creatorQuery->where('first_name', 'like', '%' . $search['search'] . '%')
                                  ->orWhere('last_name', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('fleetManager', function($fleetManagerQuery) use ($search) {
                      $fleetManagerQuery->where('name', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('mvi', function($mviQuery) use ($search) {
                      $mviQuery->where('name', 'like', '%' . $search['search'] . '%');
                  });
            });
        }

        // Apply ordering with relationship support
        if (isset($search['column_name']) && isset($search['direction'])) {
            $this->applyOrderBy($query, $search['column_name'], $search['direction']);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $recordsFiltered = $recordsTotal = $query->count(); // counts the total records filtered

        $defectReports = $query->skip($skip)->take($pageLength)->get();

        // Add permission flags using can method
        $defectReports->each(function ($defectReport) use ($user) {
            $defectReport->can_edit = $user->can('update_defect_reports');
            $defectReport->can_delete = $user->can('delete_defect_reports');
        });

        $response['draw'] = $data['draw'];
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;
        $response['data'] = $defectReports->toArray();

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Apply ordering to query with relationship support
     */
    private function applyOrderBy($query, $columnName, $direction)
    {
        // Handle relationship sorting
        switch ($columnName) {
            case 'vehicle.vehicle_number':
                $query->join('vehicles', 'defect_reports.vehicle_id', '=', 'vehicles.id')
                      ->orderBy('vehicles.vehicle_number', $direction)
                      ->select('defect_reports.*');
                break;
                
            case 'location.name':
                $query->join('locations', 'defect_reports.location_id', '=', 'locations.id')
                      ->orderBy('locations.name', $direction)
                      ->select('defect_reports.*');
                break;
                
            case 'fleet_manager.name':
                $query->leftJoin('fleet_managers as fleet_managers', 'defect_reports.fleet_manager_id', '=', 'fleet_managers.id')
                      ->orderBy('fleet_managers.name', $direction)
                      ->select('defect_reports.*');
                break;
                
            case 'mvi.name':
                $query->leftJoin('fleet_managers as mvis', 'defect_reports.mvi_id', '=', 'mvis.id')
                      ->orderBy('mvis.name', $direction)
                      ->select('defect_reports.*');
                break;
                
            case 'creator.name':
                $query->leftJoin('users as creators', 'defect_reports.created_by', '=', 'creators.id')
                      ->orderBy('creators.name', $direction)
                      ->select('defect_reports.*');
                break;
                
            default:
                // Handle direct column sorting
                if (strpos($columnName, '.') === false) {
                    $query->orderBy($columnName, $direction);
                }
                break;
        }
    }

    public function getDefectReportById($id)
    {
        return DefectReport::with(['creator', 'works', 'vehicle', 'location', 'fleetManager', 'mvi'])->find($id);
    }

    public function createDefectReport($data): JsonResponse
    {
        try {
            DB::beginTransaction();

            Log::info('DefectReportRepository: Creating defect report', [
                'user_id' => Auth::id(),
                'vehicle_id' => $data['vehicle_id'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'works_count' => isset($data['works']) && is_array($data['works']) ? count($data['works']) : 0
            ]);

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

            Log::info('DefectReportRepository: Defect report created', [
                'defect_report_id' => $defectReport->id,
                'vehicle_id' => $defectReport->vehicle_id
            ]);

            // Handle file upload
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                try {
                    $file = FileUploadManager::uploadFile($data['attachment_url'], 'defect_reports/');
                    $defectReport->update(['attachment_url' => $file['path']]);
                    Log::info('DefectReportRepository: File uploaded successfully', [
                        'defect_report_id' => $defectReport->id,
                        'file_path' => $file['path']
                    ]);
                } catch (\Exception $fileException) {
                    Log::error('DefectReportRepository: File upload failed', [
                        'defect_report_id' => $defectReport->id,
                        'error' => $fileException->getMessage()
                    ]);
                    throw $fileException;
                }
            }

            // Create works
            if (isset($data['works']) && is_array($data['works'])) {
                foreach ($data['works'] as $index => $workData) {
                    try {
                        Work::create([
                            'defect_report_id' => $defectReport->id,
                            'work' => $workData['work'],
                            'type' => $workData['type'],
                            'quantity' => !empty($workData["quantity"]) ? $workData["quantity"] : null,
                            'vehicle_part_id' => !empty($workData["vehicle_part_id"]) ? $workData["vehicle_part_id"] : null,
                        ]);
                        Log::debug('DefectReportRepository: Work created', [
                            'defect_report_id' => $defectReport->id,
                            'work_index' => $index,
                            'work_type' => $workData['type']
                        ]);
                    } catch (\Exception $workException) {
                        Log::error('DefectReportRepository: Work creation failed', [
                            'defect_report_id' => $defectReport->id,
                            'work_index' => $index,
                            'error' => $workException->getMessage()
                        ]);
                        throw $workException;
                    }
                }
            }

            DB::commit();

            Log::info('DefectReportRepository: Defect report creation completed successfully', [
                'defect_report_id' => $defectReport->id
            ]);

            $response = [
                'defectReport' => $defectReport,
                'success' => true,
                'message' => 'Defect report created successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('DefectReportRepository: Defect report creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

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

            Log::info('DefectReportRepository: Updating defect report', [
                'user_id' => Auth::id(),
                'defect_report_id' => $id,
                'vehicle_id' => $data['vehicle_id'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'works_count' => isset($data['works']) && is_array($data['works']) ? count($data['works']) : 0
            ]);

            $defectReport = DefectReport::find($id);

            if (!$defectReport) {
                Log::warning('DefectReportRepository: Defect report not found for update', [
                    'defect_report_id' => $id,
                    'user_id' => Auth::id()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Defect report not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Store original values BEFORE any modifications
            $originalValues = $defectReport->getAttributes();
            
            // Store the original values in the observer's static property
            \App\Observers\DefectReportObserver::setOriginalValues($defectReport->id, $originalValues);
            
            // Update defect report fields individually to preserve original values
            $defectReport->vehicle_id = $data['vehicle_id'];
            $defectReport->location_id = $data['location_id'];
            $defectReport->driver_name = $data['driver_name'];
            $defectReport->fleet_manager_id = $data['fleet_manager_id'];
            $defectReport->mvi_id = $data['mvi_id'];
            $defectReport->date = $data['date'];
            $defectReport->type = $data['type'];
            
            // Save the changes - this will trigger the observer with proper original values
            $defectReport->save();

            Log::info('DefectReportRepository: Defect report basic fields updated', [
                'defect_report_id' => $defectReport->id
            ]);

            // Handle file upload if provided
            if (isset($data['attachment_url']) && $data['attachment_url']) {
                try {
                    // Delete old file if exists
                    if ($defectReport->attachment_url) {
                        FileUploadManager::deleteFile($defectReport->attachment_url);
                        Log::info('DefectReportRepository: Old file deleted', [
                            'defect_report_id' => $defectReport->id,
                            'old_file_path' => $defectReport->attachment_url
                        ]);
                    }

                    $file = FileUploadManager::uploadFile($data['attachment_url'], 'defect_reports/');
                    $defectReport->update(['attachment_url' => $file['path']]);
                    
                    Log::info('DefectReportRepository: New file uploaded', [
                        'defect_report_id' => $defectReport->id,
                        'new_file_path' => $file['path']
                    ]);
                } catch (\Exception $fileException) {
                    Log::error('DefectReportRepository: File upload failed during update', [
                        'defect_report_id' => $defectReport->id,
                        'error' => $fileException->getMessage()
                    ]);
                    throw $fileException;
                }
            }

            // Delete existing works and create new ones
            $existingWorksCount = $defectReport->works()->count();
            $defectReport->works()->delete();
            
            Log::info('DefectReportRepository: Existing works deleted', [
                'defect_report_id' => $defectReport->id,
                'deleted_works_count' => $existingWorksCount
            ]);

            if (isset($data['works']) && is_array($data['works'])) {
                foreach ($data['works'] as $index => $workData) {
                    try {
                        Work::create([
                            'defect_report_id' => $defectReport->id,
                            'work' => $workData['work'],
                            'type' => $workData['type'],
                            'quantity' => !empty($workData["quantity"]) ? $workData["quantity"] : null,
                            'vehicle_part_id' => !empty($workData["vehicle_part_id"]) ? $workData["vehicle_part_id"] : null,
                        ]);
                        
                        Log::debug('DefectReportRepository: Work updated', [
                            'defect_report_id' => $defectReport->id,
                            'work_index' => $index,
                            'work_type' => $workData['type']
                        ]);
                    } catch (\Exception $workException) {
                        Log::error('DefectReportRepository: Work update failed', [
                            'defect_report_id' => $defectReport->id,
                            'work_index' => $index,
                            'error' => $workException->getMessage()
                        ]);
                        throw $workException;
                    }
                }
            }

            DB::commit();

            Log::info('DefectReportRepository: Defect report update completed successfully', [
                'defect_report_id' => $defectReport->id
            ]);

            $response = [
                'defectReport' => $defectReport,
                'success' => true,
                'message' => 'Defect report updated successfully.',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('DefectReportRepository: Defect report update failed', [
                'user_id' => Auth::id(),
                'defect_report_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

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
            if ($defectReport->attachment_url) {
                FileUploadManager::deleteFile($defectReport->attachment_url);
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
