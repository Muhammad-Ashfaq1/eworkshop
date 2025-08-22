<?php

namespace App\Repositories;

use App\Interfaces\LocationRepositoryInterface;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LocationRepository implements LocationRepositoryInterface
{
    public function getLocationListing($data): JsonResponse
    {
        try {
            \Log::info('LocationRepository: Starting getLocationListing', ['data' => $data]);
            
            $search = $data;
            $pageLength = $search['length'] ?? 10;
            $start = $search['start'] ?? 0;
            $skip = $start;
            
            $query = Location::query();
            
            // Apply search filter
            if (!empty($search['search'])) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search['search'] . '%')
                      ->orWhere('slug', 'like', '%' . $search['search'] . '%')
                      ->orWhere('location_type', 'like', '%' . $search['search'] . '%');
                });
            }

            // Apply ordering
            if (isset($search['column_name']) && isset($search['direction'])) {
                $query->orderBy($search['column_name'], $search['direction']);
            } else {
                $query->orderBy('id', 'desc');
            }

            $recordsFiltered = $recordsTotal = $query->count();
            
            \Log::info('LocationRepository: Query results', [
                'total' => $recordsTotal,
                'filtered' => $recordsFiltered,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
            
            $response['draw'] = $data['draw'];
            $response['recordsTotal'] = $recordsTotal;
            $response['recordsFiltered'] = $recordsFiltered;
            $response['data'] = $query->skip($skip)->take($pageLength)->get()->toArray();
            
            \Log::info('LocationRepository: Response prepared', [
                'data_count' => count($response['data']),
                'sample_data' => !empty($response['data']) ? $response['data'][0] : 'No data'
            ]);
            
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('LocationRepository: Error in getLocationListing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load locations: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLocationById($id)
    {
        return Location::find($id);
    }

    public function createOrUpdateLocation($data): JsonResponse
    {
        try {
            $location_id = $data['location_id'] ?? null;
            $name = $data['name'];
            $slug = $data['slug'];
            $location_type = $data['location_type'];
            
            if (!$slug) {
                $slug = str($name)->slug();
            }
            
            $is_active = $data['is_active'];

            $location = Location::updateOrCreate(
                ['id' => $location_id],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'location_type' => $location_type,
                    'is_active' => $is_active,
                ]
            );

            $response = [
                'location' => $location,
                'success' => true,
                'message' => $location_id ? 'Location Updated Successfully' : 'Location Created Successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save location: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteLocation($id): JsonResponse
    {
        try {
            $location = Location::find($id);
            
            if (!$location) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $location->delete();

            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete location: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
