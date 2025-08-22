<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehiclePartRequest;
use App\Models\VehiclePart;
use App\Repositories\VehiclePartRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VehiclePartController extends Controller
{
    private $vehiclePartRepository;

    public function __construct(VehiclePartRepository $vehiclePartRepository)
    {
        $this->vehiclePartRepository = $vehiclePartRepository;
    }

    public function index()
    {
        return view('admin.vehicle-parts.index');
    }

    /**
     * Get vehicle parts listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getVehiclePartsListing(Request $request): JsonResponse
    {
        return $this->vehiclePartRepository->getVehiclePartListing($request->all());
    }

    public function store(StoreVehiclePartRequest $request)
    {
        return $this->vehiclePartRepository->createOrUpdateVehiclePart($request->all());
    }

    public function destroy($id)
    {
        return $this->vehiclePartRepository->deleteVehiclePart($id);
    }

    public function edit($id)
    {
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
