<?php

namespace App\Repositories;

use App\Interfaces\ReportsRepositoryInterface;
use App\Models\Vehicle;
use App\Models\DefectReport;
use App\Models\PurchaseOrder;
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
                'statuses' => [1 => 'Active', 0 => 'Inactive'],
                'vehicles' => Vehicle::orderBy('vehicle_number', 'asc')->pluck('vehicle_number', 'id')
            ],
            'defect_reports' => [
                'vehicles' => Vehicle::orderBy('vehicle_number', 'asc')->pluck('vehicle_number', 'id'),
                'locations' => Location::pluck('name', 'id'),
                'users' => User::role(['admin', 'deo'])->get()->mapWithKeys(function($user) {
                    return [$user->id => $user->full_name];
                })
            ],
            'purchase_orders' => [
                'vehicles' => Vehicle::orderBy('vehicle_number', 'asc')->pluck('vehicle_number', 'id'),
                'locations' => Location::pluck('name', 'id'),
                'users' => User::role(['admin', 'deo'])->get()->mapWithKeys(function($user) {
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

    public function getPurchaseOrdersReport(array $filters): JsonResponse
    {
        try {
            $query = PurchaseOrder::with(['defectReport.vehicle', 'defectReport.location', 'creator']);

            // Apply filters
            $this->applyPurchaseOrderFilters($query, $filters);

            // Get results
            $results = $query->get();

            // Apply additional filtering if needed
            if (!empty($filters['search'])) {
                $results = $results->filter(function ($po) use ($filters) {
                    return stripos($po->po_no, $filters['search']) !== false ||
                           stripos($po->defectReport?->vehicle?->vehicle_number ?? '', $filters['search']) !== false ||
                           stripos($po->defectReport?->location?->name ?? '', $filters['search']) !== false;
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
                'message' => 'Failed to generate purchase orders report: ' . $e->getMessage()
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

            // Get total count before applying any filters
            $totalRecords = Vehicle::count();

            // Apply custom filters first
            $this->applyVehicleFilters($query, $data);

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

            // Get total count before applying any filters
            $totalRecords = DefectReport::count();

            // Apply custom filters first
            $this->applyDefectReportFilters($query, $data);

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

            // Get total count before applying any filters
            $totalRecords = VehiclePart::count();

            // Apply custom filters first
            $this->applyVehiclePartFilters($query, $data);

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

            // Debug: Log the received data
            \Log::info('Locations Report Listing - Received data:', $data);

            // Get total count before applying any filters
            $totalRecords = Location::count();

            // Apply custom filters first
            $this->applyLocationFilters($query, $data);

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

    /**
     * Get purchase orders report with DataTables pagination
     */
    public function getPurchaseOrdersReportListing(array $data): JsonResponse
    {
        try {
            $query = PurchaseOrder::with(['defectReport.vehicle', 'defectReport.location', 'creator']);

            // Get total count before applying any filters
            $totalRecords = PurchaseOrder::count();

            // Apply custom filters first
            $this->applyPurchaseOrderFilters($query, $data);

            // Apply search
            if (!empty($data['search']['value'])) {
                $searchValue = $data['search']['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('po_no', 'like', "%{$searchValue}%")
                      ->orWhereHas('defectReport.vehicle', function($vehQ) use ($searchValue) {
                          $vehQ->where('vehicle_number', 'like', "%{$searchValue}%");
                      })
                      ->orWhereHas('defectReport.location', function($locQ) use ($searchValue) {
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
                
                $columns = ['id', 'po_no', 'issue_date', 'acc_amount', 'created_at'];
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
                'message' => 'Failed to generate purchase orders listing: ' . $e->getMessage()
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
                case 'purchase_orders':
                    $data = $this->getPurchaseOrdersReport($filters);
                    break;
                case 'vehicle_parts':
                    $data = $this->getVehiclePartsReport($filters);
                    break;
                case 'locations':
                    $data = $this->getLocationsReport($filters);
                    break;
                case 'vehicle_wise':
                    $data = $this->getVehicleWiseReportForExport($filters);
                    break;
                default:
                    throw new \Exception('Invalid report type');
            }

            // Convert data to CSV format
            $csvContent = $this->convertToCSV($data->getData(true)['data'], $reportType);
            
            return response()->json([
                'success' => true,
                'data' => $csvContent,
                'message' => 'Report exported successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function convertToCSV(array $data, string $reportType): string
    {
        if (empty($data)) {
            return "No data available\n";
        }

        $headers = $this->getCSVHeaders($reportType);
        $csvContent = implode(',', $headers) . "\n";

        foreach ($data as $row) {
            $csvRow = $this->formatCSVRow($row, $reportType);
            $csvContent .= implode(',', $csvRow) . "\n";
        }

        return $csvContent;
    }

    private function getCSVHeaders(string $reportType): array
    {
        switch ($reportType) {
            case 'vehicles':
                return ['ID', 'Vehicle Number', 'Location', 'Category', 'Condition', 'Status', 'Created At'];
            case 'defect_reports':
                return ['ID', 'Reference Number', 'Vehicle', 'Location', 'Driver Name', 'Date', 'Type', 'Created At'];
            case 'purchase_orders':
                return ['ID', 'PO Number', 'Vehicle', 'Location', 'Issue Date', 'Amount', 'Created At'];
            case 'vehicle_parts':
                return ['ID', 'Name', 'Description', 'Status', 'Created At'];
            case 'locations':
                return ['ID', 'Name', 'Slug', 'Type', 'Status', 'Created At'];
            case 'vehicle_wise':
                return ['ID', 'Vehicle Number', 'Category', 'Location', 'Defect Reports Count', 'Purchase Orders Count', 'Total Amount', 'Status'];
            default:
                return ['ID', 'Data'];
        }
    }

    private function formatCSVRow(array $row, string $reportType): array
    {
        switch ($reportType) {
            case 'vehicles':
                return [
                    $row['id'] ?? '',
                    $row['vehicle_number'] ?? '',
                    $row['location']['name'] ?? '',
                    $row['category']['name'] ?? '',
                    $row['condition'] ?? '',
                    $row['is_active'] ? 'Active' : 'Inactive',
                    $row['created_at'] ?? ''
                ];
            case 'defect_reports':
                return [
                    $row['id'] ?? '',
                    $row['reference_number'] ?? '',
                    $row['vehicle']['vehicle_number'] ?? '',
                    $row['location']['name'] ?? '',
                    $row['driver_name'] ?? '',
                    $row['date'] ?? '',
                    $row['type'] ?? '',
                    $row['created_at'] ?? ''
                ];
            case 'purchase_orders':
                return [
                    $row['id'] ?? '',
                    $row['po_no'] ?? '',
                    $row['defect_report']['vehicle']['vehicle_number'] ?? '',
                    $row['defect_report']['location']['name'] ?? '',
                    $row['issue_date'] ?? '',
                    $row['acc_amount'] ?? '',
                    $row['created_at'] ?? ''
                ];
            case 'vehicle_parts':
                return [
                    $row['id'] ?? '',
                    $row['name'] ?? '',
                    $row['description'] ?? '',
                    $row['is_active'] ? 'Active' : 'Inactive',
                    $row['created_at'] ?? ''
                ];
            case 'locations':
                return [
                    $row['id'] ?? '',
                    $row['name'] ?? '',
                    $row['slug'] ?? '',
                    $row['location_type'] ?? '',
                    $row['is_active'] ? 'Active' : 'Inactive',
                    $row['created_at'] ?? ''
                ];
            case 'vehicle_wise':
                return [
                    $row['id'] ?? '',
                    $row['vehicle_number'] ?? '',
                    $row['category'] ?? '',
                    $row['location'] ?? '',
                    $row['defect_reports_count'] ?? '0',
                    $row['purchase_orders_count'] ?? '0',
                    $row['total_amount'] ?? '0.00',
                    $row['is_active'] ? 'Active' : 'Inactive'
                ];
            default:
                return [json_encode($row)];
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
        if (!empty($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['defect_date'])) {
            $query->where('date', $filters['defect_date']);
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

    private function applyPurchaseOrderFilters($query, array $filters): void
    {
        if (!empty($filters['vehicle_id'])) {
            $query->whereHas('defectReport', function($q) use ($filters) {
                $q->where('vehicle_id', $filters['vehicle_id']);
            });
        }

        if (!empty($filters['location_id'])) {
            $query->whereHas('defectReport', function($q) use ($filters) {
                $q->where('location_id', $filters['location_id']);
            });
        }

        if (!empty($filters['issue_date'])) {
            $query->where('issue_date', $filters['issue_date']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('issue_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('issue_date', '<=', $filters['date_to'] . ' 23:59:59');
        }
    }

    public function getVehicleWiseReport(array $filters): JsonResponse
    {
        try {
            // Validate required date filters
            if (empty($filters['date_from']) || empty($filters['date_to'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date and end date are required for vehicle-wise report'
                ], 400);
            }

            $query = Vehicle::with(['category', 'location']);

            // If specific vehicle is selected, filter by that vehicle
            if (!empty($filters['vehicle_id'])) {
                $query->where('id', $filters['vehicle_id']);
            } else {
                // If no vehicle selected, show only vehicles that have defect reports or purchase orders in the date range
                $query->where(function($q) use ($filters) {
                    $q->whereHas('defectReports', function($defectQuery) use ($filters) {
                        $defectQuery->whereBetween('date', [$filters['date_from'], $filters['date_to'] . ' 23:59:59']);
                    })
                    ->orWhereHas('purchaseOrders', function($poQuery) use ($filters) {
                        $poQuery->whereBetween('issue_date', [$filters['date_from'], $filters['date_to'] . ' 23:59:59']);
                    });
                });
            }

            // Get vehicle statistics
            $vehicles = $query->get()->map(function($vehicle) use ($filters) {
                $vehicleId = $vehicle->id;
                
                // Count defect reports for this vehicle
                $defectReportsQuery = DefectReport::where('vehicle_id', $vehicleId);
                if (!empty($filters['date_from'])) {
                    $defectReportsQuery->where('date', '>=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $defectReportsQuery->where('date', '<=', $filters['date_to']);
                }
                $defectReportsCount = $defectReportsQuery->count();

                // Count purchase orders for this vehicle (through defect reports)
                $purchaseOrdersQuery = PurchaseOrder::whereHas('defectReport', function($q) use ($vehicleId) {
                    $q->where('vehicle_id', $vehicleId);
                });
                if (!empty($filters['date_from'])) {
                    $purchaseOrdersQuery->where('issue_date', '>=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $purchaseOrdersQuery->where('issue_date', '<=', $filters['date_to']);
                }
                $purchaseOrdersCount = $purchaseOrdersQuery->count();

                // Calculate total amount of purchase orders for this vehicle
                $totalAmount = $purchaseOrdersQuery->sum('acc_amount');

                return [
                    'id' => $vehicle->id,
                    'vehicle_number' => $vehicle->vehicle_number,
                    'category' => $vehicle->category ? $vehicle->category->name : 'N/A',
                    'location' => $vehicle->location ? $vehicle->location->name : 'N/A',
                    'defect_reports_count' => $defectReportsCount,
                    'purchase_orders_count' => $purchaseOrdersCount,
                    'total_amount' => number_format($totalAmount, 2),
                    'total_amount_raw' => $totalAmount,
                    'condition' => $vehicle->condition,
                    'is_active' => $vehicle->is_active,
                    'created_at' => $vehicle->created_at
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicle-wise report generated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicle-wise report: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getVehicleWiseReportListing(array $data): JsonResponse
    {
        try {
            // Validate required date filters
            if (empty($data['date_from']) || empty($data['date_to'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date and end date are required for vehicle-wise report'
                ], 400);
            }

            // Set default values for DataTables parameters
            $data = array_merge([
                'start' => 0,
                'length' => 10,
                'draw' => 1,
                'search' => ['value' => ''],
                'order' => [],
                'columns' => []
            ], $data);

            $pageNumber = ($data['start'] / $data['length']) + 1;
            $pageLength = $data['length'];
            $skip = ($pageNumber - 1) * $pageLength;
            $searchValue = $data['search']['value'];

            // Build base query
            $query = Vehicle::with(['category', 'location']);

            // If specific vehicle is selected, filter by that vehicle
            if (!empty($data['vehicle_id'])) {
                $query->where('id', $data['vehicle_id']);
            } else {
                // If no vehicle selected, show only vehicles that have defect reports or purchase orders in the date range
                $query->where(function($q) use ($data) {
                    $q->whereHas('defectReports', function($defectQuery) use ($data) {
                        $defectQuery->whereBetween('date', [$data['date_from'], $data['date_to'] . ' 23:59:59']);
                    })
                    ->orWhereHas('defectReports.purchaseOrders', function($poQuery) use ($data) {
                        $poQuery->whereBetween('issue_date', [$data['date_from'], $data['date_to'] . ' 23:59:59']);
                    });
                });
            }

            // Apply filters
            $filters = $this->extractFiltersFromDataTablesData($data);

            // Apply search if provided
            if (!empty($searchValue)) {
                $query->where(function($q) use ($searchValue) {
                    $q->where('vehicle_number', 'like', '%' . $searchValue . '%')
                      ->orWhereHas('category', function($categoryQuery) use ($searchValue) {
                          $categoryQuery->where('name', 'like', '%' . $searchValue . '%');
                      })
                      ->orWhereHas('location', function($locationQuery) use ($searchValue) {
                          $locationQuery->where('name', 'like', '%' . $searchValue . '%');
                      });
                });
            }

            // Get total count before pagination
            $totalRecords = $query->count();

            // Apply ordering
            if (isset($data['order']) && !empty($data['order'])) {
                $orderColumn = $data['columns'][$data['order'][0]['column']]['name'] ?? 'vehicle_number';
                $orderDirection = $data['order'][0]['dir'] ?? 'asc';
                
                // Handle special cases for non-orderable columns
                if ($orderColumn === '#' || $orderColumn === null || $orderColumn === '') {
                    $query->orderBy('vehicle_number', 'asc');
                } else {
                    switch ($orderColumn) {
                        case 'category':
                            $query->join('vehicle_categories', 'vehicles.category_id', '=', 'vehicle_categories.id')
                                  ->orderBy('vehicle_categories.name', $orderDirection)
                                  ->select('vehicles.*');
                            break;
                        case 'location':
                            $query->join('locations', 'vehicles.location_id', '=', 'locations.id')
                                  ->orderBy('locations.name', $orderDirection)
                                  ->select('vehicles.*');
                            break;
                        default:
                            $query->orderBy($orderColumn, $orderDirection);
                            break;
                    }
                }
            } else {
                $query->orderBy('vehicle_number', 'asc');
            }

            // Apply pagination
            $vehicles = $query->skip($skip)->take($pageLength)->get();

            // Calculate statistics for each vehicle
            $vehiclesWithStats = $vehicles->map(function($vehicle) use ($data) {
                $vehicleId = $vehicle->id;
                
                // Count defect reports
                $defectReportsQuery = DefectReport::where('vehicle_id', $vehicleId);
                if (!empty($data['date_from'])) {
                    $defectReportsQuery->where('date', '>=', $data['date_from']);
                }
                if (!empty($data['date_to'])) {
                    $defectReportsQuery->where('date', '<=', $data['date_to'] . ' 23:59:59');
                }
                $defectReportsCount = $defectReportsQuery->count();

                // Count purchase orders
                $purchaseOrdersQuery = PurchaseOrder::whereHas('defectReport', function($q) use ($vehicleId) {
                    $q->where('vehicle_id', $vehicleId);
                });
                if (!empty($data['date_from'])) {
                    $purchaseOrdersQuery->where('issue_date', '>=', $data['date_from']);
                }
                if (!empty($data['date_to'])) {
                    $purchaseOrdersQuery->where('issue_date', '<=', $data['date_to'] . ' 23:59:59');
                }
                $purchaseOrdersCount = $purchaseOrdersQuery->count();

                // Calculate total amount
                $totalAmount = $purchaseOrdersQuery->sum('acc_amount');

                return [
                    'id' => $vehicle->id,
                    'vehicle_number' => $vehicle->vehicle_number,
                    'category' => $vehicle->category ? $vehicle->category->name : 'N/A',
                    'location' => $vehicle->location ? $vehicle->location->name : 'N/A',
                    'defect_reports_count' => $defectReportsCount,
                    'purchase_orders_count' => $purchaseOrdersCount,
                    'total_amount' => number_format($totalAmount, 2),
                    'total_amount_raw' => $totalAmount,
                    'condition' => $vehicle->condition,
                    'is_active' => $vehicle->is_active,
                    'created_at' => $vehicle->created_at
                ];
            });

            return response()->json([
                'draw' => intval($data['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $vehiclesWithStats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicle-wise report listing: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getVehicleWiseReportForExport(array $filters): JsonResponse
    {
        try {
            // Validate required date filters
            if (empty($filters['date_from']) || empty($filters['date_to'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date and end date are required for vehicle-wise report'
                ], 400);
            }

            $query = Vehicle::with(['category', 'location']);

            // If specific vehicle is selected, filter by that vehicle
            if (!empty($filters['vehicle_id'])) {
                $query->where('id', $filters['vehicle_id']);
            } else {
                // If no vehicle selected, show only vehicles that have defect reports or purchase orders in the date range
                $query->where(function($q) use ($filters) {
                    $q->whereHas('defectReports', function($defectQuery) use ($filters) {
                        $defectQuery->whereBetween('date', [$filters['date_from'], $filters['date_to'] . ' 23:59:59']);
                    })
                    ->orWhereHas('defectReports.purchaseOrders', function($poQuery) use ($filters) {
                        $poQuery->whereBetween('issue_date', [$filters['date_from'], $filters['date_to'] . ' 23:59:59']);
                    });
                });
            }

            // Get all vehicles (no pagination for export)
            $vehicles = $query->orderBy('vehicle_number', 'asc')->get();

            // Calculate statistics for each vehicle
            $vehiclesWithStats = $vehicles->map(function($vehicle) use ($filters) {
                $vehicleId = $vehicle->id;
                
                // Count defect reports
                $defectReportsQuery = DefectReport::where('vehicle_id', $vehicleId);
                if (!empty($filters['date_from'])) {
                    $defectReportsQuery->where('date', '>=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $defectReportsQuery->where('date', '<=', $filters['date_to'] . ' 23:59:59');
                }
                $defectReportsCount = $defectReportsQuery->count();

                // Count purchase orders
                $purchaseOrdersQuery = PurchaseOrder::whereHas('defectReport', function($q) use ($vehicleId) {
                    $q->where('vehicle_id', $vehicleId);
                });
                if (!empty($filters['date_from'])) {
                    $purchaseOrdersQuery->where('issue_date', '>=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $purchaseOrdersQuery->where('issue_date', '<=', $filters['date_to'] . ' 23:59:59');
                }
                $purchaseOrdersCount = $purchaseOrdersQuery->count();

                // Calculate total amount
                $totalAmount = $purchaseOrdersQuery->sum('acc_amount');

                return [
                    'id' => $vehicle->id,
                    'vehicle_number' => $vehicle->vehicle_number,
                    'category' => $vehicle->category ? $vehicle->category->name : 'N/A',
                    'location' => $vehicle->location ? $vehicle->location->name : 'N/A',
                    'defect_reports_count' => $defectReportsCount,
                    'purchase_orders_count' => $purchaseOrdersCount,
                    'total_amount' => number_format($totalAmount, 2),
                    'total_amount_raw' => $totalAmount,
                    'condition' => $vehicle->condition,
                    'is_active' => $vehicle->is_active,
                    'created_at' => $vehicle->created_at
                ];
            });

            \Log::info('Vehicle-wise report export completed', ['count' => $vehiclesWithStats->count()]);

            return response()->json([
                'success' => true,
                'data' => $vehiclesWithStats->toArray()
            ]);

        } catch (\Exception $e) {
            \Log::error('Vehicle-wise report export failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate vehicle-wise report for export: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function extractFiltersFromDataTablesData(array $data): array
    {
        $filters = [];
        
        // Extract filters from DataTables data - only vehicle_id and dates for vehicle-wise report
        foreach ($data as $key => $value) {
            if (in_array($key, ['vehicle_id', 'date_from', 'date_to'])) {
                $filters[$key] = $value;
            }
        }
        
        return $filters;
    }
}
