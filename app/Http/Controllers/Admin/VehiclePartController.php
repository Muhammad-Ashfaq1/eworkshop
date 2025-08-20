<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehiclePartRequest;
use App\Models\VehiclePart;
use Illuminate\Support\Str;


class VehiclePartController extends Controller
{
    public function index()
    {
        $vehicle_parts = VehiclePart::latest()->get();
        return view('admin.vehicle-parts.index', compact('vehicle_parts'));
    }

    public function store(StoreVehiclePartRequest $request)
    {
        $vehicle_part=$request->vehicle_part ?? null;
        $is_active = $request->is_active;
        $name = $request->name;
        $slug = Str::slug($name);
        VehiclePart::updateOrCreate([
            'id' => $vehicle_part,
        ], [
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);
        return $this->getLatestRecords(true, 'Vehicle Part saved successfully.');

    }
    public function destroy($id)
    {
        $vehicle_part = VehiclePart::findOrFail($id);
        if (!$vehicle_part) {
            return response()->json(['success' => false, 'message' => 'Vehicle Part not found.'], 404);
        }
        $vehicle_part->delete();
        return $this->getLatestRecords(true, 'Vehicle Part deleted successfully.');
    }

    public function edit($id)
    {
        $vehicle_part = VehiclePart::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Vehicle Part retrieved successfully.',
            'vehiclePart' => $vehicle_part,
        ]);
    }

    private function getLatestRecords($success = true , $message = 'vehicle saved successfully.')
    {
        $vehicle_parts = VehiclePart::latest()->get();
        $html = view('admin.vehicle-parts.data-table', compact('vehicle_parts'))->render();
        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }
}
