<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::updateOrCreate(
            ['email' => 'test@crmprof.local'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // Seed Drivers
        $drivers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '1234567890', 'license_number' => 'DL12345', 'status' => 'active'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '0987654321', 'license_number' => 'DL67890', 'status' => 'active'],
            ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'phone' => '5551234567', 'license_number' => 'DL55555', 'status' => 'inactive'],
            ['name' => 'Alice Williams', 'email' => 'alice@example.com', 'phone' => '5559876543', 'license_number' => 'DL99999', 'status' => 'active'],
        ];

        foreach ($drivers as $driver) {
            Driver::updateOrCreate(['email' => $driver['email']], $driver);
        }

        // Seed Vehicles
        $vehicles = [
            ['make' => 'Toyota', 'model' => 'Camry', 'year' => 2022, 'plate_number' => 'ABC-1234', 'status' => 'available'],
            ['make' => 'Ford', 'model' => 'Transit', 'year' => 2021, 'plate_number' => 'TR-9876', 'status' => 'available'],
            ['make' => 'Mercedes', 'model' => 'Sprinter', 'year' => 2023, 'plate_number' => 'MS-5555', 'status' => 'busy'],
            ['make' => 'Freightliner', 'model' => 'Cascadia', 'year' => 2020, 'plate_number' => 'FL-1111', 'status' => 'maintenance'],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(['plate_number' => $vehicle['plate_number']], $vehicle);
        }

        // Seed Orders
        $driverIds = Driver::where('status', 'active')->pluck('id')->toArray();
        $vehicleIds = Vehicle::where('status', 'available')->pluck('id')->toArray();

        for ($i = 1; $i <= 15; $i++) {
            $status = ['pending', 'in_transit', 'delivered', 'cancelled'][rand(0, 3)];
            $isAssigned = $status !== 'pending' && $status !== 'cancelled';
            
            Order::create([
                'order_number' => 'ORD-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'client_name' => 'Client ' . $i,
                'pickup_address' => $i . ' Pickup St, City A',
                'delivery_address' => $i . ' Delivery Ave, City B',
                'pickup_date' => now()->addDays(rand(-5, 5)),
                'delivery_date' => now()->addDays(rand(6, 10)),
                'status' => $status,
                'driver_id' => $isAssigned ? $driverIds[array_rand($driverIds)] : null,
                'vehicle_id' => $isAssigned ? $vehicleIds[array_rand($vehicleIds)] : null,
                'amount' => rand(100, 1000) + (rand(0, 99) / 100),
            ]);
        }
    }
}
