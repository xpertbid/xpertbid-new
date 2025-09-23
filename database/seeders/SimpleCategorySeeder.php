<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimpleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant
        $tenant = DB::table('tenants')->first();
        if (!$tenant) {
            $this->command->error('No tenants found. Please run TenantSeeder first.');
            return;
        }

        $categories = [
            [
                'tenant_id' => $tenant->id,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'image' => '/images/placeholder.svg',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'description' => 'Latest smartphones and mobile devices',
                'image' => '/images/placeholder.svg',
                'parent_id' => 1, // Will be updated after parent is created
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Laptops and portable computers',
                'image' => '/images/placeholder.svg',
                'parent_id' => 1,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing and fashion accessories',
                'image' => '/images/placeholder.svg',
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Home and office furniture',
                'image' => '/images/placeholder.svg',
                'parent_id' => null,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Sports equipment and outdoor gear',
                'image' => '/images/placeholder.svg',
                'parent_id' => null,
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert categories one by one to get the correct parent_id
        foreach ($categories as $index => $category) {
            if ($category['parent_id'] === 1) {
                // Find the electronics category ID
                $electronicsId = DB::table('categories')
                    ->where('slug', 'electronics')
                    ->where('tenant_id', $tenant->id)
                    ->value('id');
                $category['parent_id'] = $electronicsId;
            }
            
            $categoryId = DB::table('categories')->insertGetId($category);
            $this->command->info("Created category: {$category['name']} (ID: {$categoryId})");
        }
    }
}