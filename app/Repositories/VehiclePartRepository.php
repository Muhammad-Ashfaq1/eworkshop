<?php

namespace App\Repositories;

use App\Models\VehiclePart;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VehiclePartRepository
{
    public function getVehiclePartListing($data): JsonResponse
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

        $query = VehiclePart::query();
        
        // Apply search filter
        if (!empty($search['search'])) {
            $query->where('name', 'like', '%' . $search['search'] . '%')
                  ->orWhere('slug', 'like', '%' . $search['search'] . '%');
        }

        // Apply ordering
        if (isset($search['column_name']) && isset($search['direction'])) {
            $query->orderBy($search['column_name'], $search['direction']);
        } else {
            $query->orderBy('id', 'desc');
        }

        $recordsFiltered = $recordsTotal = $query->count(); // counts the total records filtered
        
        $response['draw'] = $data['draw'];
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;
        $response['data'] = $query->skip($skip)->take($pageLength)->get()->toArray(); // convert to array for DataTables
        
        return response()->json($response, Response::HTTP_OK);
    }

    public function getVehiclePartById($id)
    {
        return VehiclePart::find($id);
    }

    public function createOrUpdateVehiclePart($data): JsonResponse
    {
        $vehiclePart = VehiclePart::updateOrCreate(
            ['id' => @$data['vehicle_part']], 
            [
                'name' => $data['name'],
                'slug' => \Illuminate\Support\Str::slug($data['name']),
                'is_active' => $data['is_active'] ?? 1,
            ]
        );

        $response = [
            'vehiclePart' => $vehiclePart,
            'success' => true,
            'message' => $data['vehicle_part'] ? 'Vehicle Part Updated Successfully' : 'Vehicle Part Created Successfully',
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function deleteVehiclePart($id): JsonResponse
    {
        $vehiclePart = VehiclePart::find($id);
        
        if (!$vehiclePart) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle Part not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $vehiclePart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle Part deleted successfully'
        ], Response::HTTP_OK);
    }
}
