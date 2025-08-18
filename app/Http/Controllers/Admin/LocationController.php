<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations=Location::latest()->get();
        return view('admin.location.index',compact('locations'));
    }
    public function store(StoreLocationRequest $request)
    {
        $location_id = $request->location_id ?? null;
        $name = $request->name;
        $slug = $request->slug;
        $is_active = $request->is_active;

        Location::updateOrCreate(
            ['id' => $location_id],
            [
                'name' => $name,
                'slug' => $slug,
                'is_active' => $is_active,
            ]
        );

        return $this->getLatestRecords(true, 'Location created successfully.');
    }

    public function edit($id)
    {
        $location=Location::findOrFail($id);
        return response()->json([
            'success'=>true,
            'location' => $location,
        ]);
    }


    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return $this->getLatestRecords(true, 'Location deleted successfully.');
    }

    private function getLatestRecords($success = true , $message = 'Location created successfully.')
    {
        $locations = Location::latest()->get();
        $html = view('admin.location.data-table', compact('locations'))->render();
        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
        ]);
    }
}

