<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        return Vehicle::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'plate_number' => 'required|string|unique:vehicles',
            'status' => 'required|string',
        ]);

        return Vehicle::create($validated);
    }

    public function show(Vehicle $vehicle)
    {
        return $vehicle->load('orders');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'make' => 'string',
            'model' => 'string',
            'year' => 'integer',
            'plate_number' => 'string|unique:vehicles,plate_number,' . $vehicle->id,
            'status' => 'string',
        ]);

        $vehicle->update($validated);
        return $vehicle;
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return response()->noContent();
    }
}
