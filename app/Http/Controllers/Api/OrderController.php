<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['driver', 'vehicle'])->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|unique:orders',
            'client_name' => 'required|string',
            'pickup_address' => 'required|string',
            'delivery_address' => 'required|string',
            'pickup_date' => 'required|date',
            'delivery_date' => 'required|date',
            'status' => 'required|string',
            'driver_id' => 'nullable|exists:drivers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'amount' => 'required|numeric',
        ]);

        return Order::create($validated);
    }

    public function show(Order $order)
    {
        return $order->load(['driver', 'vehicle']);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_number' => 'string|unique:orders,order_number,' . $order->id,
            'client_name' => 'string',
            'pickup_address' => 'string',
            'delivery_address' => 'string',
            'pickup_date' => 'date',
            'delivery_date' => 'date',
            'status' => 'string',
            'driver_id' => 'nullable|exists:drivers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'amount' => 'numeric',
        ]);

        $order->update($validated);
        return $order;
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent();
    }
}
