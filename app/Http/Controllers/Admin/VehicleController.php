<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['location', 'category'])->latest()->get();
        return view('admin.vehicle.index', compact('vehicles'));
    }

    public function store(VehicleRequest $request)
    {
        try {
            $vehicle_id = $request->vehicle_id ?? null;
            $vehicle_number = $request->vehicle_number;
            $location_id = $request->town;
            $vehicle_category_id = $request->category;
            $condition = $request->condition;
            $is_active = $request->is_active ? 1 : 0;

            Vehicle::updateOrCreate(
                ['id' => $vehicle_id],
                [
                    'vehicle_number' => $vehicle_number,
                    'location_id' => $location_id,
                    'vehicle_category_id' => $vehicle_category_id,
                    'condition' => $condition,
                    'is_active' => $is_active,
                ]
            );

            return $this->getLatestRecords(true, 'Vehicle saved successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $vehicle = Vehicle::with(['location', 'category'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'vehicle' => $vehicle,
        ]);
    }

    public function destroy($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();
            return $this->getLatestRecords(true, 'Vehicle deleted successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getLatestRecords($success = true, $message = 'Vehicle saved successfully.')
    {
        $vehicles = Vehicle::with(['location', 'category'])->latest()->get();
        $html = view('admin.vehicle.data-table', compact('vehicles'))->render();
        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }
}
