<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [
            [
                'tenant_id' => 3, // RealEstate Pro
                'vendor_id' => 3, // Martinez Real Estate Group
                'estate_agent_id' => 6, // Robert Martinez
                'title' => 'Luxury Modern Villa in Beverly Hills',
                'description' => 'Stunning modern villa featuring panoramic city views, infinity pool, and smart home technology. Perfect for luxury living.',
                'property_type' => 'house',
                'listing_type' => 'sale',
                'price' => 2500000.00,
                'rent_price' => null,
                'currency' => 'USD',
                'address' => '123 Sunset Boulevard',
                'city' => 'Beverly Hills',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90210',
                'latitude' => 34.0736,
                'longitude' => -118.4004,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'area_sqft' => 4500.00,
                'lot_size' => 0.75,
                'year_built' => 2020,
                'property_status' => 'available',
                'amenities' => json_encode([
                    'Swimming Pool',
                    'Garage (3 cars)',
                    'Smart Home System',
                    'Wine Cellar',
                    'Home Theater',
                    'Garden',
                    'Security System',
                    'Central Air Conditioning'
                ]),
                'facilities' => json_encode([
                    'Schools: Beverly Hills High School (0.5 miles)',
                    'Shopping: Rodeo Drive (0.3 miles)',
                    'Hospital: Cedars-Sinai (2.1 miles)',
                    'Airport: LAX (12 miles)',
                    'Beach: Malibu Beach (8 miles)'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Luxury+Villa+Exterior',
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Luxury+Villa+Living+Room',
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Luxury+Villa+Pool'
                ]),
                'floor_plans' => json_encode([
                    'https://via.placeholder.com/800x600/34495e/ffffff?text=Floor+Plan+1',
                    'https://via.placeholder.com/800x600/7f8c8d/ffffff?text=Floor+Plan+2'
                ]),
                'virtual_tour' => json_encode([
                    'type' => '360_video',
                    'url' => 'https://example.com/virtual-tour/villa-beverly-hills'
                ]),
                'is_featured' => true,
                'is_verified' => true,
                'commission_rate' => 3.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 3,
                'vendor_id' => 3,
                'estate_agent_id' => 7, // Lisa Thompson
                'title' => 'Downtown Loft with City Views',
                'description' => 'Contemporary loft apartment in the heart of downtown with floor-to-ceiling windows and modern amenities.',
                'property_type' => 'apartment',
                'listing_type' => 'rent',
                'price' => 0.00,
                'rent_price' => 4500.00,
                'currency' => 'USD',
                'address' => '456 Downtown Plaza',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90013',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqft' => 1200.00,
                'lot_size' => null,
                'year_built' => 2018,
                'property_status' => 'available',
                'amenities' => json_encode([
                    'Floor-to-Ceiling Windows',
                    'Hardwood Floors',
                    'Modern Kitchen',
                    'In-Unit Laundry',
                    'Balcony',
                    'Central Air',
                    'Elevator',
                    'Concierge Service'
                ]),
                'facilities' => json_encode([
                    'Shopping: Grand Central Market (0.2 miles)',
                    'Entertainment: Staples Center (0.5 miles)',
                    'Transportation: Metro Station (0.1 miles)',
                    'Restaurants: Arts District (0.3 miles)'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Downtown+Loft+Living',
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Downtown+Loft+Kitchen',
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Downtown+Loft+View'
                ]),
                'floor_plans' => json_encode([
                    'https://via.placeholder.com/800x600/34495e/ffffff?text=Loft+Floor+Plan'
                ]),
                'virtual_tour' => json_encode([
                    'type' => 'virtual_reality',
                    'url' => 'https://example.com/virtual-tour/downtown-loft'
                ]),
                'is_featured' => false,
                'is_verified' => true,
                'commission_rate' => 3.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 3,
                'vendor_id' => 3,
                'estate_agent_id' => 6, // Robert Martinez
                'title' => 'Commercial Office Space in Financial District',
                'description' => 'Prime commercial office space in the financial district with modern amenities and excellent transportation access.',
                'property_type' => 'commercial',
                'listing_type' => 'both',
                'price' => 1200000.00,
                'rent_price' => 8500.00,
                'currency' => 'USD',
                'address' => '789 Financial Plaza',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90071',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'bedrooms' => null,
                'bathrooms' => 4,
                'area_sqft' => 2500.00,
                'lot_size' => null,
                'year_built' => 2015,
                'property_status' => 'available',
                'amenities' => json_encode([
                    'Modern HVAC System',
                    'Elevator Access',
                    'Parking (10 spaces)',
                    'Conference Rooms',
                    'Reception Area',
                    'Kitchenette',
                    'High-Speed Internet',
                    'Security System'
                ]),
                'facilities' => json_encode([
                    'Transportation: Metro Station (0.2 miles)',
                    'Banking: Multiple banks nearby',
                    'Restaurants: Financial District dining',
                    'Parking: Street and garage options'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Commercial+Office+Exterior',
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Commercial+Office+Interior',
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Commercial+Office+Conference'
                ]),
                'floor_plans' => json_encode([
                    'https://via.placeholder.com/800x600/34495e/ffffff?text=Office+Floor+Plan'
                ]),
                'virtual_tour' => json_encode([
                    'type' => '360_photos',
                    'url' => 'https://example.com/virtual-tour/commercial-office'
                ]),
                'is_featured' => true,
                'is_verified' => true,
                'commission_rate' => 2.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 3,
                'vendor_id' => 3,
                'estate_agent_id' => 7, // Lisa Thompson
                'title' => 'Beachfront Condo in Malibu',
                'description' => 'Stunning beachfront condominium with direct beach access and panoramic ocean views.',
                'property_type' => 'condo',
                'listing_type' => 'sale',
                'price' => 1800000.00,
                'rent_price' => null,
                'currency' => 'USD',
                'address' => '321 Pacific Coast Highway',
                'city' => 'Malibu',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90265',
                'latitude' => 34.0259,
                'longitude' => -118.7798,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqft' => 1800.00,
                'lot_size' => null,
                'year_built' => 2019,
                'property_status' => 'available',
                'amenities' => json_encode([
                    'Beach Access',
                    'Ocean Views',
                    'Balcony',
                    'Modern Kitchen',
                    'Hardwood Floors',
                    'Central Air',
                    'Garage (2 cars)',
                    'Community Pool'
                ]),
                'facilities' => json_encode([
                    'Beach: Direct beach access',
                    'Shopping: Malibu Country Mart (1 mile)',
                    'Restaurants: Malibu Pier (0.5 miles)',
                    'Schools: Malibu Elementary (0.8 miles)'
                ]),
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Beachfront+Condo+Exterior',
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Beachfront+Condo+Ocean+View',
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Beachfront+Condo+Living'
                ]),
                'floor_plans' => json_encode([
                    'https://via.placeholder.com/800x600/34495e/ffffff?text=Condo+Floor+Plan'
                ]),
                'virtual_tour' => json_encode([
                    'type' => '360_video',
                    'url' => 'https://example.com/virtual-tour/beachfront-condo'
                ]),
                'is_featured' => true,
                'is_verified' => true,
                'commission_rate' => 3.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('properties')->insert($properties);
    }
}