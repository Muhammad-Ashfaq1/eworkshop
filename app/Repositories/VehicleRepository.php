<?php

namespace App\Repositories;

use App\Interfaces\VehicleRepositoryInterface;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VehicleRepository implements VehicleRepositoryInterface
{
    public function getVehicleListing($data): JsonResponse
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

        $query = Vehicle::with(['location', 'category']);

        // Apply search filter
        if (!empty($search['search'])) {
            $query->where(function($q) use ($search) {
                $q->where('vehicle_number', 'like', '%' . $search['search'] . '%')
                  ->orWhere('condition', 'like', '%' . $search['search'] . '%')
                  ->orWhereHas('location', function($locQuery) use ($search) {
                      $locQuery->where('name', 'like', '%' . $search['search'] . '%');
                  })
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name', 'like', '%' . $search['search'] . '%');
                  });
            });
        }

        // Apply ordering
        if (isset($search['column_name']) && isset($search['direction'])) {
            $query->orderBy($search['column_name'], $search['direction']);
        } else {
            $query->orderBy('id', 'desc');
        }

        $recordsFiltered = $recordsTotal = $query->count(); // counts the total records filtered

        $vehicles = $query->skip($skip)->take($pageLength)->get();
            // Add permission flags using can method
        $vehicles->each(function ($vehicle)  {
            $vehicle->can_edit = auth()->user()->can('update_vehicles');
            $vehicle->can_delete = auth()->user()->can('delete_vehicles');
        });

        $response['draw'] = $data['draw'];
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;
        $response['data'] =$vehicles = $query->skip($skip)->take($pageLength)->get();

// Add permission flags
$vehicles->each(function ($vehicle) {
    $vehicle->can_edit = auth()->user()->can('update_vehicles');
    $vehicle->can_delete = auth()->user()->can('delete_vehicles');
});

$response['draw'] = $data['draw'];
$response['recordsTotal'] = $recordsTotal;
$response['recordsFiltered'] = $recordsFiltered;
$response['data'] = $vehicles->toArray();
        return response()->json($response, Response::HTTP_OK);
    }

    public function getVehicleById($id)
    {
        return Vehicle::with(['location', 'category'])->find($id);
    }

    public function createOrUpdateVehicle($data): JsonResponse
    {
        try {
            $vehicle = Vehicle::updateOrCreate(
                ['id' => @$data['vehicle_id']],
                [
                    'vehicle_number' => $data['vehicle_number'],
                    'location_id' => $data['town'],
                    'vehicle_category_id' => $data['category'],
                    'condition' => $data['condition'],
                    'is_active' => $data['is_active'] ? 1 : 0,
                ]
            );

            $response = [
                'vehicle' => $vehicle,
                'success' => true,
                'message' => $data['vehicle_id'] ? 'Vehicle Updated Successfully' : 'Vehicle Created Successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save vehicle: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteVehicle($id): JsonResponse
    {
        try {
            $vehicle = Vehicle::find($id);

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $vehicle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllVehiclesWithRelations()
    {
        return Vehicle::with(['location', 'category'])->latest()->get();
    }
}
