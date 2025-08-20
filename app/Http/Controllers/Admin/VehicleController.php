<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;


class VehicleController extends Controller
{
    public function index()
    {
        return view('admin.vehicle.index');
    }
    public function store(VehicleRequest $request)
    {
        
    }
}
