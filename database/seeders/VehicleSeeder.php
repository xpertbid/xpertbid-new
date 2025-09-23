<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating vehicles...');

        // Get existing data
        $tenant = DB::table('tenants')->first();
        $vendors = DB::table('vendors')->get();

        if (!$tenant || $vendors->isEmpty()) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        $vehicles = [
            [
                'title' => '2023 Tesla Model S Plaid',
                'description' => 'Electric luxury sedan with incredible performance. Features autopilot, premium interior, and over 400 miles of range. 0-60 mph in under 2 seconds.',
                'price' => 129990.00,
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'Tesla',
                'model' => 'Model S',
                'year' => 2023,
                'mileage' => 2500,
                'fuel_type' => 'electric',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => '2022 BMW X5 M Competition',
                'description' => 'High-performance luxury SUV with M Competition package. Features premium leather interior, advanced driver assistance, and powerful V8 engine.',
                'price' => 95000.00,
                'vehicle_type' => 'suv',
                'listing_type' => 'sale',
                'make' => 'BMW',
                'model' => 'X5',
                'year' => 2022,
                'mileage' => 18000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => '2021 Mercedes-Benz C-Class',
                'description' => 'Luxury sedan with advanced technology and elegant design. Features MBUX infotainment system, premium materials, and smooth performance.',
                'price' => 55000.00,
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'year' => 2021,
                'mileage' => 22000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => '2023 Toyota Camry Hybrid',
                'description' => 'Reliable and fuel-efficient hybrid sedan. Features advanced safety systems, comfortable interior, and excellent fuel economy.',
                'price' => 32000.00,
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'Toyota',
                'model' => 'Camry',
                'year' => 2023,
                'mileage' => 8500,
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => '2022 Honda CR-V',
                'description' => 'Practical and reliable compact SUV. Features spacious interior, advanced safety features, and excellent resale value.',
                'price' => 35000.00,
                'vehicle_type' => 'suv',
                'listing_type' => 'sale',
                'make' => 'Honda',
                'model' => 'CR-V',
                'year' => 2022,
                'mileage' => 15000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => '2020 Ford F-150 Raptor',
                'description' => 'High-performance pickup truck built for off-road adventures. Features powerful V6 engine, advanced suspension, and rugged design.',
                'price' => 65000.00,
                'vehicle_type' => 'truck',
                'listing_type' => 'sale',
                'make' => 'Ford',
                'model' => 'F-150',
                'year' => 2020,
                'mileage' => 32000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'good',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => '2023 Porsche 911 Carrera',
                'description' => 'Iconic sports car with legendary performance. Features rear-engine layout, precise handling, and timeless design.',
                'price' => 115000.00,
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'Porsche',
                'model' => '911',
                'year' => 2023,
                'mileage' => 1200,
                'fuel_type' => 'gasoline',
                'transmission' => 'manual',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => '2021 Audi Q7',
                'description' => 'Premium three-row SUV with luxurious interior and advanced technology. Features quattro all-wheel drive and premium sound system.',
                'price' => 68000.00,
                'vehicle_type' => 'suv',
                'listing_type' => 'sale',
                'make' => 'Audi',
                'model' => 'Q7',
                'year' => 2021,
                'mileage' => 25000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => '2022 Subaru Outback',
                'description' => 'Adventure-ready wagon with standard all-wheel drive. Features spacious interior, advanced safety systems, and excellent ground clearance.',
                'price' => 38000.00,
                'vehicle_type' => 'wagon',
                'listing_type' => 'sale',
                'make' => 'Subaru',
                'model' => 'Outback',
                'year' => 2022,
                'mileage' => 18000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => '2020 Harley-Davidson Street Glide',
                'description' => 'Classic touring motorcycle with comfortable seating and advanced electronics. Features Milwaukee-Eight engine and premium audio system.',
                'price' => 28000.00,
                'vehicle_type' => 'motorcycle',
                'listing_type' => 'sale',
                'make' => 'Harley-Davidson',
                'model' => 'Street Glide',
                'year' => 2020,
                'mileage' => 12000,
                'fuel_type' => 'gasoline',
                'transmission' => 'manual',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            DB::table('vehicles')->insert([
                'tenant_id' => $tenant->id,
                'vendor_id' => $vehicleData['vendor_id'],
                'title' => $vehicleData['title'],
                'description' => $vehicleData['description'],
                'price' => $vehicleData['price'],
                'vehicle_type' => $vehicleData['vehicle_type'],
                'listing_type' => $vehicleData['listing_type'],
                'make' => $vehicleData['make'],
                'model' => $vehicleData['model'],
                'year' => $vehicleData['year'],
                'mileage' => $vehicleData['mileage'],
                'fuel_type' => $vehicleData['fuel_type'],
                'transmission' => $vehicleData['transmission'],
                'condition' => $vehicleData['condition'],
                'vehicle_status' => $vehicleData['status'],
                'mileage_unit' => 'miles',
                'color' => ['Black', 'White', 'Silver', 'Red', 'Blue'][rand(0, 4)],
                'doors' => $vehicleData['vehicle_type'] === 'motorcycle' ? 0 : rand(2, 4),
                'seats' => $vehicleData['vehicle_type'] === 'motorcycle' ? 2 : rand(2, 8),
                'engine_size' => rand(10, 65) / 10,
                'is_featured' => rand(0, 1),
                'is_verified' => true,
                'show_price' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created vehicle: {$vehicleData['title']}");
        }

        $this->command->info('Vehicle seeding completed!');
    }
}