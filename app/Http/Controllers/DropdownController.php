<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Location;
use App\Models\FleetManager;
use Illuminate\Http\Request;
use App\Models\VehicleCategory;

class DropdownController extends Controller
{
    public function getTowns(Request $request)
    {
        $towns = Location::where('location_type', Location::LOCATION_TYPE_TOWN)->where('is_active', Location::IS_ACTIVE)->get(
            ['id', 'name']
        );

        return response()->json([
            'success' => true,
            'data' => $towns,
        ]);
    }

    public function getVehicleCategories(Request $request)
    {
        $categories = VehicleCategory::where('is_active', 1)->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function getVehicles(Request $request)
    {
        $vehicles = Vehicle::with('category')
            ->where('is_active', 1)
            ->get(['id', 'vehicle_number', 'vehicle_category_id']);

        $formattedVehicles = $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'text' => $vehicle->vehicle_number.' - '.($vehicle->category->name ?? 'N/A'),
                'name' => $vehicle->vehicle_number.' - '.($vehicle->category->name ?? 'N/A'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedVehicles,
        ]);
    }

    public function getLocations(Request $request)
    {
        $locations = Location::where('is_active', 1)->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }

    public function getFleetManagers(Request $request)
    {
        $managers = \App\Models\User::role('fleet_manager')
            ->where('is_active', 1)
            ->get(['id', 'first_name', 'last_name']);

        $formattedManagers = $managers->map(function ($manager) {
            return [
                'id' => $manager->id,
                'text' => $manager->first_name.' '.$manager->last_name,
                'name' => $manager->first_name.' '.$manager->last_name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedManagers,
        ]);
    }

    public function getMvis(Request $request)
    {
        $mvis =FleetManager::where('is_active', 1)->where('type',FleetManager::TYPE_MVI)
            ->get();

        $formattedMvis = $mvis->map(function ($mvi) {
            return [
                'id' => $mvi->id,
                'name' => $mvi->name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedMvis,
        ]);
    }
}
