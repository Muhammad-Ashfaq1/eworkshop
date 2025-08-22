<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Interfaces\LocationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private $locationRepository;

    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function index()
    {
        try {
            $this->authorize('read_locations');
            // Return view - DataTable will automatically load data via AJAX on initialization
            return view('admin.location.index');
        } catch (\Exception $e) {
            \Log::error('Location index authorization error:', [
                'error' => $e->getMessage(),
                'user' => auth()->user() ? auth()->user()->id : 'not authenticated'
            ]);
            
            // Return a proper error response instead of throwing
            return response()->json([
                'success' => false,
                'message' => 'Access denied: ' . $e->getMessage()
            ], 403);
        }
    }

    /**
     * Get locations listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getLocationListing(Request $request): JsonResponse
    {
        try {
            $this->authorize('read_locations');
            return $this->locationRepository->getLocationListing($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load locations: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreLocationRequest $request)
    {
        $this->authorize('create_locations');
        return $this->locationRepository->createOrUpdateLocation($request->all());
    }

    public function edit($id)
    {
        $this->authorize('update_locations');
        $location = $this->locationRepository->getLocationById($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'location' => $location,
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete_locations');
        return $this->locationRepository->deleteLocation($id);
    }
}
