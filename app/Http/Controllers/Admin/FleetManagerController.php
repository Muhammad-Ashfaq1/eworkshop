<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FleetManager;
use Illuminate\Http\Request;

class FleetManagerController extends Controller
{
    public function index()
    {
        return view('admin.fleet-manger.index');
    }
    public function addfleetmanager(Request $request)
    {
        $validated = $request->validate([
            'fleetManager' => 'required|string|max:255',
            'type' => 'required|in:fleet_manager,mvi',
            'is_active' => 'sometimes|boolean',
        ] );
        FleetManager::create([
            'name' => $request->fleetManager,
            'type' => $request->type,
            'is_active' => $request->is_active
        ]);
        return redirect()->route('admin.fleet-manager.index')->with('success', 'Fleet Manager/Mvi added successfully.');
    }
}
