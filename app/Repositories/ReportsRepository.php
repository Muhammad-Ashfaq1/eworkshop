<?php

namespace App\Repositories;

use App\Interfaces\ReportsRepositoryInterface;
use App\Models\Vehicle;
use App\Models\DefectReport;
use App\Models\VehiclePart;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReportsRepository implements ReportsRepositoryInterface
{
    public function getFilterOptions(): array
    {
        return [
            'vehicles' => [
                'categories' => \App\Models\VehicleCategory::pluck('name', 'id'),
                'locations' => Location::pluck('name', 'id'),
                'conditions' => ['new', 'old'],
                'statuses' => [1 => 'Active', 0 => 'Inactive']
            ],
            'defect_reports' => [
                'types' => ['mechanical', 'electrical', 'body', 'other'],
                'vehicles' => Vehicle::pluck('vehicle_number', 'id'),
                'locations' => Location::pluck('name', 'id'),
                'users' => User::role(['fleet_manager', 'mvi'])->get()->mapWithKeys(function($user) {
                    return [$user->id => $user->full_name];
                })
            ],
            'vehicle_parts' => [
                'statuses' => [1 => 'Active', 0 => 'Inactive']
            ],
            'locations' => [
                'types' => ['town', 'workshop'],
                'statuses' => [1 => 'Active', 0 => 'Inactive']
            ]
        ];
    }

    public function getVehiclesReport(array $filters): JsonResponse
    {
        try {
            $query = Vehicle::with(['location', 'category']);

            // Apply filters
            $this->applyVehicleFilters($query, $filters);

            // Get results
            $results = $query->get();

            // Apply additional filtering if needed
            if (!empty($filters['search'])) {
                $results = $results->filter(function ($vehicle) use ($filters) {
                    return stripos($vehicle->vehicle_number, $filters['search']) !== false ||
                           stripos($vehicle->condition, $filters['search']) !== false ||
                           stripos($vehicle->location?->name ?? '', $filters['search']) !== false ||
                           stripos($vehicle->category?->name ?? '', $filters['search']) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'total' => $results->count(),
                'filters_applied' => $filters
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicles report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDefectReportsReport(array $filters): JsonResponse
    {
        try {
            $query = DefectReport::with(['vehicle', 'location', 'creator']);

            // Apply filters
            $this->applyDefectReportFilters($query, $filters);

            // Get results
            $results = $query->get();

            // Apply additional filtering if needed
            if (!empty($filters['search'])) {
                $results = $results->filter(function ($report) use ($filters) {
                    return stripos($report->driver_name, $filters['search']) !== false ||
                           stripos($report->type, $filters['search']) !== false ||
                           stripos($report->vehicle?->vehicle_number ?? '', $filters['search']) !== false ||
                           stripos($report->location?->name ?? '', $filters['search']) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'total' => $results->count(),
                'filters_applied' => $filters
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate defect reports report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getVehiclePartsReport(array $filters): JsonResponse
    {
        try {
            $query = VehiclePart::query();

            // Apply filters
            $this->applyVehiclePartFilters($query, $filters);

            // Get results
            $results = $query->get();

            // Apply additional filtering if needed
            if (!empty($filters['search'])) {
                $results = $results->filter(function ($part) use ($filters) {
                    return stripos($part->name, $filters['search']) !== false ||
                           stripos($part->slug, $filters['search']) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'total' => $results->count(),
                'filters_applied' => $filters
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicle parts report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLocationsReport(array $filters): JsonResponse
    {
        try {
            $query = Location::query();

            // Apply filters
            $this->applyLocationFilters($query, $filters);

            // Get results
            $results = $query->get();

            // Apply additional filtering if needed
            if (!empty($filters['search'])) {
                $results = $results->filter(function ($location) use ($filters) {
                    return stripos($location->name, $filters['search']) !== false ||
                           stripos($location->slug, $filters['search']) !== false ||
                           stripos($location->location_type, $filters['search']) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'total' => $results->count(),
                'filters_applied' => $filters
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate locations report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get vehicles report with DataTables pagination
     */
    public function getVehiclesReportListing(array $data): JsonResponse
    {
        try {
            $query = Vehicle::with(['location', 'category']);

            // Get total count before applying pagination
            $totalRecords = $query->count();

            // Apply search
            if (!empty($data['search']['value'])) {
                $searchValue = $data['search']['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('vehicle_number', 'like', "%{$searchValue}%")
                      ->orWhere('condition', 'like', "%{$searchValue}%")
                      ->orWhereHas('location', function($locQ) use ($searchValue) {
                          $locQ->where('name', 'like', "%{$searchValue}%");
                      })
                      ->orWhereHas('category', function($catQ) use ($searchValue) {
                          $catQ->where('name', 'like', "%{$searchValue}%");
                      });
                });
            }

            // Get filtered count
            $filteredRecords = $query->count();

            // Apply ordering
            if (!empty($data['order'])) {
                $columnIndex = $data['order'][0]['column'];
                $columnDirection = $data['order'][0]['dir'];
                
                $columns = ['id', 'vehicle_number', 'condition', 'is_active', 'created_at'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDirection);
                }
            }

            // Apply pagination
            $pageLength = $data['length'] ?? 10;
            $start = $data['start'] ?? 0;
            $results = $query->skip($start)->take($pageLength)->get();

            return response()->json([
                'draw' => intval($data['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $results->toArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicles report listing: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get defect reports with DataTables pagination
     */
    public function getDefectReportsReportListing(array $data): JsonResponse
    {
        try {
            $query = DefectReport::with(['vehicle', 'location', 'creator']);

            // Get total count before applying pagination
            $totalRecords = $query->count();

            // Apply search
            if (!empty($data['search']['value'])) {
                $searchValue = $data['search']['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('driver_name', 'like', "%{$searchValue}%")
                      ->orWhere('type', 'like', "%{$searchValue}%")
                      ->orWhereHas('vehicle', function($vehQ) use ($searchValue) {
                          $vehQ->where('vehicle_number', 'like', "%{$searchValue}%");
                      })
                      ->orWhereHas('location', function($locQ) use ($searchValue) {
                          $locQ->where('name', 'like', "%{$searchValue}%");
                      });
                });
            }

            // Get filtered count
            $filteredRecords = $query->count();

            // Apply ordering
            if (!empty($data['order'])) {
                $columnIndex = $data['order'][0]['column'];
                $columnDirection = $data['order'][0]['dir'];
                
                $columns = ['id', 'driver_name', 'type', 'date', 'created_at'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDirection);
                }
            }

            // Apply pagination
            $pageLength = $data['length'] ?? 10;
            $start = $data['start'] ?? 0;
            $results = $query->skip($start)->take($pageLength)->get();

            return response()->json([
                'draw' => intval($data['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $results->toArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate defect reports listing: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get vehicle parts report with DataTables pagination
     */
    public function getVehiclePartsReportListing(array $data): JsonResponse
    {
        try {
            $query = VehiclePart::query();

            // Get total count before applying pagination
            $totalRecords = $query->count();

            // Apply search
            if (!empty($data['search']['value'])) {
                $searchValue = $data['search']['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('slug', 'like', "%{$searchValue}%");
                });
            }

            // Get filtered count
            $filteredRecords = $query->count();

            // Apply ordering
            if (!empty($data['order'])) {
                $columnIndex = $data['order'][0]['column'];
                $columnDirection = $data['order'][0]['dir'];
                
                $columns = ['id', 'name', 'slug', 'is_active', 'created_at'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDirection);
                }
            }

            // Apply pagination
            $pageLength = $data['length'] ?? 10;
            $start = $data['start'] ?? 0;
            $results = $query->skip($start)->take($pageLength)->get();

            return response()->json([
                'draw' => intval($data['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $results->toArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicle parts listing: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get locations report with DataTables pagination
     */
    public function getLocationsReportListing(array $data): JsonResponse
    {
        try {
            $query = Location::query();

            // Get total count before applying pagination
            $totalRecords = $query->count();

            // Apply search
            if (!empty($data['search']['value'])) {
                $searchValue = $data['search']['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('slug', 'like', "%{$searchValue}%")
                      ->orWhere('location_type', 'like', "%{$searchValue}%");
                });
            }

            // Get filtered count
            $filteredRecords = $query->count();

            // Apply ordering
            if (!empty($data['order'])) {
                $columnIndex = $data['order'][0]['column'];
                $columnDirection = $data['order'][0]['dir'];
                
                $columns = ['id', 'name', 'location_type', 'is_active', 'created_at'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDirection);
                }
            }

            // Apply pagination
            $pageLength = $data['length'] ?? 10;
            $start = $data['start'] ?? 0;
            $results = $query->skip($start)->take($pageLength)->get();

            return response()->json([
                'draw' => intval($data['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $results->toArray()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate locations listing: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function exportReport(array $filters): JsonResponse
    {
        try {
            $reportType = $filters['report_type'] ?? 'vehicles';
            
            switch ($reportType) {
                case 'vehicles':
                    $data = $this->getVehiclesReport($filters);
                    break;
                case 'defect_reports':
                    $data = $this->getDefectReportsReport($filters);
                    break;
                case 'vehicle_parts':
                    $data = $this->getVehiclePartsReport($filters);
                    break;
                case 'locations':
                    $data = $this->getLocationsReport($filters);
                    break;
                default:
                    throw new \Exception('Invalid report type');
            }

            // For now, return the data as JSON
            // In the future, this could generate CSV, PDF, or Excel files
            return $data;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function applyVehicleFilters($query, array $filters): void
    {
        if (!empty($filters['category_id'])) {
            $query->where('vehicle_category_id', $filters['category_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (isset($filters['condition']) && $filters['condition'] !== '') {
            $query->where('condition', $filters['condition']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }
    }

    private function applyDefectReportFilters($query, array $filters): void
    {
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['created_by'])) {
            $query->where('created_by', $filters['created_by']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to'] . ' 23:59:59');
        }
    }

    private function applyVehiclePartFilters($query, array $filters): void
    {
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }
    }

    private function applyLocationFilters($query, array $filters): void
    {
        if (!empty($filters['location_type'])) {
            $query->where('location_type', $filters['location_type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }
    }
}
