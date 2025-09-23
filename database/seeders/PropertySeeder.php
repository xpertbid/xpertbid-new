<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating properties...');

        // Get existing data
        $tenant = DB::table('tenants')->first();
        $vendors = DB::table('vendors')->get();

        if (!$tenant || $vendors->isEmpty()) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        $properties = [
            [
                'title' => 'Modern Downtown Apartment',
                'description' => 'Beautiful modern apartment in downtown with stunning city views. Features open floor plan, premium finishes, and access to building amenities including gym and rooftop terrace.',
                'price' => 450000.00,
                'property_type' => 'apartment',
                'listing_type' => 'sale',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area' => 1200,
                'area_unit' => 'sqft',
                'address' => '123 Downtown Avenue',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => 'Luxury Villa with Pool',
                'description' => 'Stunning luxury villa with private pool and beautifully landscaped garden. Features gourmet kitchen, master suite with walk-in closet, and outdoor entertainment area.',
                'price' => 850000.00,
                'property_type' => 'house',
                'listing_type' => 'sale',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 2500,
                'area_unit' => 'sqft',
                'address' => '456 Luxury Lane',
                'city' => 'Beverly Hills',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90210',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => 'Cozy Studio in Arts District',
                'description' => 'Charming studio apartment in the vibrant arts district. Perfect for young professionals. Features exposed brick walls, hardwood floors, and modern appliances.',
                'price' => 280000.00,
                'property_type' => 'apartment',
                'listing_type' => 'sale',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'area' => 600,
                'area_unit' => 'sqft',
                'address' => '789 Arts Street',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90013',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => 'Family Home in Suburbs',
                'description' => 'Spacious family home in quiet suburban neighborhood. Features large backyard, two-car garage, and excellent school district. Perfect for families.',
                'price' => 320000.00,
                'property_type' => 'house',
                'listing_type' => 'sale',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 1800,
                'area_unit' => 'sqft',
                'address' => '321 Suburban Drive',
                'city' => 'Austin',
                'state' => 'TX',
                'country' => 'USA',
                'postal_code' => '73301',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => 'Penthouse with City Views',
                'description' => 'Luxurious penthouse apartment with panoramic city views. Features floor-to-ceiling windows, private terrace, and premium finishes throughout.',
                'price' => 1200000.00,
                'property_type' => 'apartment',
                'listing_type' => 'sale',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area' => 2000,
                'area_unit' => 'sqft',
                'address' => '555 Skyline Boulevard',
                'city' => 'Miami',
                'state' => 'FL',
                'country' => 'USA',
                'postal_code' => '33101',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => 'Historic Brownstone',
                'description' => 'Beautifully restored historic brownstone with original architectural details. Features high ceilings, original hardwood floors, and modern updates.',
                'price' => 650000.00,
                'property_type' => 'house',
                'listing_type' => 'sale',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 2200,
                'area_unit' => 'sqft',
                'address' => '888 Historic Street',
                'city' => 'Boston',
                'state' => 'MA',
                'country' => 'USA',
                'postal_code' => '02108',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
            [
                'title' => 'Waterfront Condo',
                'description' => 'Stunning waterfront condo with private dock access. Features open floor plan, granite countertops, and floor-to-ceiling windows overlooking the water.',
                'price' => 750000.00,
                'property_type' => 'apartment',
                'listing_type' => 'sale',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area' => 1500,
                'area_unit' => 'sqft',
                'address' => '777 Waterfront Drive',
                'city' => 'Seattle',
                'state' => 'WA',
                'country' => 'USA',
                'postal_code' => '98101',
                'status' => 'active',
                'vendor_id' => $vendors->first()->id,
            ],
            [
                'title' => 'Mountain Retreat Cabin',
                'description' => 'Charming mountain cabin perfect for weekend getaways. Features stone fireplace, large deck, and access to hiking trails. Fully furnished.',
                'price' => 180000.00,
                'property_type' => 'house',
                'listing_type' => 'sale',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area' => 900,
                'area_unit' => 'sqft',
                'address' => '999 Mountain View Road',
                'city' => 'Denver',
                'state' => 'CO',
                'country' => 'USA',
                'postal_code' => '80201',
                'status' => 'active',
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
            ],
        ];

        foreach ($properties as $propertyData) {
            DB::table('properties')->insert([
                'tenant_id' => $tenant->id,
                'vendor_id' => $propertyData['vendor_id'],
                'title' => $propertyData['title'],
                'description' => $propertyData['description'],
                'price' => $propertyData['price'],
                'property_type' => $propertyData['property_type'],
                'listing_type' => $propertyData['listing_type'],
                'bedrooms' => $propertyData['bedrooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'area_sqft' => $propertyData['area'],
                'address' => $propertyData['address'],
                'city' => $propertyData['city'],
                'state' => $propertyData['state'],
                'country' => $propertyData['country'],
                'postal_code' => $propertyData['postal_code'],
                'property_status' => $propertyData['status'],
                'is_featured' => rand(0, 1),
                'is_verified' => true,
                'show_price' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created property: {$propertyData['title']}");
        }

        $this->command->info('Property seeding completed!');
    }
}