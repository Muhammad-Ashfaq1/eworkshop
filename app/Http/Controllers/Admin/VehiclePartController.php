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
        $name = $request->name;
      $slug = Str::of($request->name)
        ->slug()
        ->replace('-', '_')
        ->lower()
        ->toString();

        $is_active = $request->is_active;

        VehiclePart::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => $is_active,
        ]);
        return $this->getLatestRecords(true, 'Vehicle Part created successfully.');

    }
    public function destroy($id)
    {
        $vehicle_part = VehiclePart::findOrFail($id);
        $vehicle_part->delete();
        return $this->getLatestRecords(true, 'Vehicle Part deleted successfully.');
    }

    private function getLatestRecords($success = true , $message = 'Location created successfully.')
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
