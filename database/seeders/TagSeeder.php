<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Technology Tags
            [
                'name' => 'Smartphone',
                'slug' => 'smartphone',
                'color' => '#3B82F6',
                'description' => 'Mobile phones with advanced computing capabilities',
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
                'color' => '#10B981',
                'description' => 'Portable personal computers',
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tablet',
                'slug' => 'tablet',
                'color' => '#F59E0B',
                'description' => 'Portable touchscreen devices',
                'status' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'color' => '#EF4444',
                'description' => 'Gaming-related products and accessories',
                'status' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wireless',
                'slug' => 'wireless',
                'color' => '#8B5CF6',
                'description' => 'Wireless technology products',
                'status' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fashion Tags
            [
                'name' => 'Casual',
                'slug' => 'casual',
                'color' => '#6B7280',
                'description' => 'Casual wear and everyday clothing',
                'status' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Formal',
                'slug' => 'formal',
                'color' => '#1F2937',
                'description' => 'Formal wear and business attire',
                'status' => true,
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'color' => '#059669',
                'description' => 'Sports and athletic wear',
                'status' => true,
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Summer',
                'slug' => 'summer',
                'color' => '#F97316',
                'description' => 'Summer season products',
                'status' => true,
                'sort_order' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Winter',
                'slug' => 'winter',
                'color' => '#0EA5E9',
                'description' => 'Winter season products',
                'status' => true,
                'sort_order' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Home & Living Tags
            [
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'color' => '#DC2626',
                'description' => 'Kitchen appliances and accessories',
                'status' => true,
                'sort_order' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bedroom',
                'slug' => 'bedroom',
                'color' => '#7C3AED',
                'description' => 'Bedroom furniture and accessories',
                'status' => true,
                'sort_order' => 21,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Living Room',
                'slug' => 'living-room',
                'color' => '#059669',
                'description' => 'Living room furniture and decor',
                'status' => true,
                'sort_order' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Garden',
                'slug' => 'garden',
                'color' => '#16A34A',
                'description' => 'Garden tools and outdoor products',
                'status' => true,
                'sort_order' => 23,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Automotive Tags
            [
                'name' => 'Electric',
                'slug' => 'electric',
                'color' => '#10B981',
                'description' => 'Electric vehicles and accessories',
                'status' => true,
                'sort_order' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'color' => '#D97706',
                'description' => 'Luxury vehicles and premium products',
                'status' => true,
                'sort_order' => 31,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SUV',
                'slug' => 'suv',
                'color' => '#1D4ED8',
                'description' => 'Sport Utility Vehicles',
                'status' => true,
                'sort_order' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sedan',
                'slug' => 'sedan',
                'color' => '#6B7280',
                'description' => 'Sedan vehicles',
                'status' => true,
                'sort_order' => 33,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // General Tags
            [
                'name' => 'New',
                'slug' => 'new',
                'color' => '#10B981',
                'description' => 'New products',
                'status' => true,
                'sort_order' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sale',
                'slug' => 'sale',
                'color' => '#EF4444',
                'description' => 'Products on sale',
                'status' => true,
                'sort_order' => 41,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Featured',
                'slug' => 'featured',
                'color' => '#F59E0B',
                'description' => 'Featured products',
                'status' => true,
                'sort_order' => 42,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Limited Edition',
                'slug' => 'limited-edition',
                'color' => '#8B5CF6',
                'description' => 'Limited edition products',
                'status' => true,
                'sort_order' => 43,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eco-Friendly',
                'slug' => 'eco-friendly',
                'color' => '#059669',
                'description' => 'Environmentally friendly products',
                'status' => true,
                'sort_order' => 44,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tags')->insert($tags);
    }
}