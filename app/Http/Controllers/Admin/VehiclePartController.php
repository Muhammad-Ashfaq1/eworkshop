<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehiclePartRequest;
use App\Interfaces\VehiclePartRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehiclePartController extends Controller
{
    private $vehiclePartRepository;

    public function __construct(VehiclePartRepositoryInterface $vehiclePartRepository)
    {
        $this->vehiclePartRepository = $vehiclePartRepository;
    }

    public function index()
    {
        $this->authorize('read_vehicle_parts');
        return view('admin.vehicle-parts.index');
    }

    /**
     * Get vehicle parts listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getVehiclePartsListing(Request $request): JsonResponse
    {
        $this->authorize('read_vehicle_parts');
        return $this->vehiclePartRepository->getVehiclePartListing($request->all());
    }

    public function store(StoreVehiclePartRequest $request)
    {
        $this->authorize('create_vehicle_parts');
        return $this->vehiclePartRepository->createOrUpdateVehiclePart($request->all());
    }

    public function destroy($id)
    {
        $this->authorize('delete_vehicle_parts');
        return $this->vehiclePartRepository->deleteVehiclePart($id);
    }

    public function edit($id)
    {
        $this->authorize('read_vehicle_parts');
        $vehiclePart = $this->vehiclePartRepository->getVehiclePartById($id);

        if (!$vehiclePart) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle Part not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vehicle Part retrieved successfully.',
            'vehiclePart' => $vehiclePart,
        ]);
    }
}
