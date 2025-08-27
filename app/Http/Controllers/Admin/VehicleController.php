<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Interfaces\VehicleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    private $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function index()
    {
        $this->authorize('read_vehicles');
        // Return view - DataTable will automatically load data via AJAX on initialization
        return view('admin.vehicle.index');
    }

    /**
     * Get vehicles listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getVehicleListing(Request $request): JsonResponse
    {
        $this->authorize('read_vehicles');
        return $this->vehicleRepository->getVehicleListing($request->all());
    }

    public function store(VehicleRequest $request)
    {
        $this->authorize('create_vehicles');
        return $this->vehicleRepository->createOrUpdateVehicle($request->all());
    }

    public function edit($id)
    {
        $this->authorize('read_vehicles');
        $vehicle = $this->vehicleRepository->getVehicleById($id);

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'vehicle' => $vehicle,
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete_vehicles');
        return $this->vehicleRepository->deleteVehicle($id);
    }
}
