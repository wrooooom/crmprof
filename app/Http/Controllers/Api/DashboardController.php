<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return [
            'stats' => [
                'total_orders' => Order::count(),
                'active_drivers' => Driver::where('status', 'active')->count(),
                'available_vehicles' => Vehicle::where('status', 'available')->count(),
                'total_revenue' => Order::where('status', 'delivered')->sum('amount'),
            ],
            'recent_orders' => Order::with(['driver', 'vehicle'])->orderBy('created_at', 'desc')->take(5)->get(),
        ];
    }
}
