<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
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
            'data' => $towns
        ]);
    }

    public function getVehicleCategories(Request $request)
    {
        $categories = VehicleCategory::where('is_active', 1)->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
