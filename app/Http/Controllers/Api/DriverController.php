<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        return Driver::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers',
            'phone' => 'required|string',
            'license_number' => 'required|string|unique:drivers',
            'status' => 'required|string',
        ]);

        return Driver::create($validated);
    }

    public function show(Driver $driver)
    {
        return $driver->load('orders');
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:drivers,email,' . $driver->id,
            'phone' => 'string',
            'license_number' => 'string|unique:drivers,license_number,' . $driver->id,
            'status' => 'string',
        ]);

        $driver->update($validated);
        return $driver;
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return response()->noContent();
    }
}
