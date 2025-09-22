<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'tenant_id' => 4, // AutoTrade Hub
                'vendor_id' => 4, // Brown's Auto Sales
                'sales_agent_id' => 8, // David Brown
                'title' => '2022 BMW 3 Series Sedan',
                'description' => 'Well-maintained BMW 3 Series with low mileage, premium interior, and excellent performance. Single owner vehicle.',
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'BMW',
                'model' => '3 Series',
                'year' => 2022,
                'variant' => '330i',
                'body_type' => 'sedan',
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'mileage' => 25000,
                'mileage_unit' => 'miles',
                'color' => 'Jet Black',
                'doors' => 4,
                'seats' => 5,
                'engine_size' => 2.0,
                'engine_power' => '255 HP',
                'price' => 42000.00,
                'rent_price' => null,
                'currency' => 'USD',
                'condition' => 'used',
                'vehicle_status' => 'available',
                'vin_number' => 'WBA3A5G59CN123456',
                'registration_number' => 'ABC123',
                'registration_date' => '2022-03-15',
                'insurance_expiry' => '2024-03-15',
                'features' => json_encode([
                    'Leather Seats',
                    'Sunroof',
                    'Navigation System',
                    'Bluetooth Connectivity',
                    'Backup Camera',
                    'Heated Seats',
                    'Automatic Climate Control',
                    'Premium Sound System'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/8e44ad/ffffff?text=BMW+3+Series+Front',
                    'https://via.placeholder.com/800x600/9b59b6/ffffff?text=BMW+3+Series+Side',
                    'https://via.placeholder.com/800x600/8e44ad/ffffff?text=BMW+3+Series+Interior'
                ]),
                'documents' => json_encode([
                    'title' => 'Clean Title',
                    'registration' => 'Current Registration',
                    'insurance' => 'Valid Insurance',
                    'inspection' => 'Passed Inspection'
                ]),
                'is_featured' => true,
                'is_verified' => true,
                'commission_rate' => 2.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 4,
                'vendor_id' => 4,
                'sales_agent_id' => 9, // Jennifer Lee
                'title' => '2021 Toyota Camry Hybrid',
                'description' => 'Fuel-efficient Toyota Camry Hybrid with excellent reliability and low maintenance costs. Perfect for daily commuting.',
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'Toyota',
                'model' => 'Camry',
                'year' => 2021,
                'variant' => 'LE Hybrid',
                'body_type' => 'sedan',
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'mileage' => 35000,
                'mileage_unit' => 'miles',
                'color' => 'Silver',
                'doors' => 4,
                'seats' => 5,
                'engine_size' => 2.5,
                'engine_power' => '208 HP',
                'price' => 28000.00,
                'rent_price' => null,
                'currency' => 'USD',
                'condition' => 'used',
                'vehicle_status' => 'available',
                'vin_number' => '4T1C11AK5MU123456',
                'registration_number' => 'DEF456',
                'registration_date' => '2021-06-20',
                'insurance_expiry' => '2024-06-20',
                'features' => json_encode([
                    'Hybrid Engine',
                    'Cloth Seats',
                    'Automatic Climate Control',
                    'Bluetooth',
                    'USB Ports',
                    'Backup Camera',
                    'Lane Departure Warning',
                    'Automatic Emergency Braking'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/9b59b6/ffffff?text=Toyota+Camry+Front',
                    'https://via.placeholder.com/800x600/8e44ad/ffffff?text=Toyota+Camry+Side',
                    'https://via.placeholder.com/800x600/9b59b6/ffffff?text=Toyota+Camry+Interior'
                ]),
                'documents' => json_encode([
                    'title' => 'Clean Title',
                    'registration' => 'Current Registration',
                    'insurance' => 'Valid Insurance',
                    'maintenance' => 'Service Records Available'
                ]),
                'is_featured' => false,
                'is_verified' => true,
                'commission_rate' => 2.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 4,
                'vendor_id' => 4,
                'sales_agent_id' => 8, // David Brown
                'title' => '2023 Ford F-150 Pickup Truck',
                'description' => 'Powerful Ford F-150 with towing capacity and spacious interior. Perfect for work and recreation.',
                'vehicle_type' => 'truck',
                'listing_type' => 'sale',
                'make' => 'Ford',
                'model' => 'F-150',
                'year' => 2023,
                'variant' => 'XLT',
                'body_type' => 'pickup',
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'mileage' => 15000,
                'mileage_unit' => 'miles',
                'color' => 'White',
                'doors' => 4,
                'seats' => 5,
                'engine_size' => 3.5,
                'engine_power' => '400 HP',
                'price' => 45000.00,
                'rent_price' => null,
                'currency' => 'USD',
                'condition' => 'used',
                'vehicle_status' => 'available',
                'vin_number' => '1FTFW1E85NFA12345',
                'registration_number' => 'GHI789',
                'registration_date' => '2023-01-10',
                'insurance_expiry' => '2024-01-10',
                'features' => json_encode([
                    '4WD',
                    'Towing Package',
                    'Bed Liner',
                    'Navigation',
                    'Bluetooth',
                    'Backup Camera',
                    'Power Windows',
                    'Cruise Control'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/8e44ad/ffffff?text=Ford+F150+Front',
                    'https://via.placeholder.com/800x600/9b59b6/ffffff?text=Ford+F150+Side',
                    'https://via.placeholder.com/800x600/8e44ad/ffffff?text=Ford+F150+Bed'
                ]),
                'documents' => json_encode([
                    'title' => 'Clean Title',
                    'registration' => 'Current Registration',
                    'insurance' => 'Valid Insurance',
                    'warranty' => 'Manufacturer Warranty'
                ]),
                'is_featured' => true,
                'is_verified' => true,
                'commission_rate' => 2.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 4,
                'vendor_id' => 4,
                'sales_agent_id' => 9, // Jennifer Lee
                'title' => '2020 Honda Civic Sedan',
                'description' => 'Reliable Honda Civic with excellent fuel economy and low maintenance. Great for first-time car buyers.',
                'vehicle_type' => 'car',
                'listing_type' => 'rent',
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => 2020,
                'variant' => 'LX',
                'body_type' => 'sedan',
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'mileage' => 45000,
                'mileage_unit' => 'miles',
                'color' => 'Blue',
                'doors' => 4,
                'seats' => 5,
                'engine_size' => 2.0,
                'engine_power' => '158 HP',
                'price' => 0.00,
                'rent_price' => 350.00,
                'currency' => 'USD',
                'condition' => 'used',
                'vehicle_status' => 'available',
                'vin_number' => '2HGFB2F59LH123456',
                'registration_number' => 'JKL012',
                'registration_date' => '2020-08-15',
                'insurance_expiry' => '2024-08-15',
                'features' => json_encode([
                    'Automatic Transmission',
                    'Air Conditioning',
                    'Power Windows',
                    'Bluetooth',
                    'USB Port',
                    'Backup Camera',
                    'Cruise Control',
                    'Remote Keyless Entry'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/9b59b6/ffffff?text=Honda+Civic+Front',
                    'https://via.placeholder.com/800x600/8e44ad/ffffff?text=Honda+Civic+Side',
                    'https://via.placeholder.com/800x600/9b59b6/ffffff?text=Honda+Civic+Interior'
                ]),
                'documents' => json_encode([
                    'title' => 'Clean Title',
                    'registration' => 'Current Registration',
                    'insurance' => 'Valid Insurance',
                    'inspection' => 'Passed Inspection'
                ]),
                'is_featured' => false,
                'is_verified' => true,
                'commission_rate' => 2.50,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('vehicles')->insert($vehicles);
    }
}