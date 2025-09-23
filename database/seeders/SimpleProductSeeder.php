<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimpleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get tenant, vendors, and categories
        $tenant = DB::table('tenants')->first();
        $vendors = DB::table('vendors')->get();
        $categories = DB::table('categories')->get();

        if (!$tenant || $vendors->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Missing required data. Please run tenant, vendor, and category seeders first.');
            return;
        }

        $products = [
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendors[0]->id, // TechStore Pro
                'category_id' => $categories->where('slug', 'electronics')->first()->id,
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'The latest iPhone with advanced camera system and A17 Pro chip.',
                'short_description' => 'Latest iPhone with advanced features',
                'price' => 999.00,
                'sale_price' => 949.00,
                'sku' => 'IPHONE15PRO001',
                'stock_quantity' => 50,
                'min_stock_level' => 5,
                'stock_status' => 'instock',
                'weight' => 0.187,
                'status' => 'publish',
                'is_featured' => true,
                'gallery' => json_encode([
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendors[0]->id,
                'category_id' => $categories->where('slug', 'laptops')->first()->id,
                'name' => 'MacBook Pro 16"',
                'slug' => 'macbook-pro-16',
                'description' => 'Powerful laptop with M3 Max chip, perfect for professionals.',
                'short_description' => 'Professional laptop with M3 Max chip',
                'price' => 2499.00,
                'sale_price' => null,
                'sku' => 'MBP16M3MAX001',
                'stock_quantity' => 25,
                'min_stock_level' => 3,
                'stock_status' => 'instock',
                'weight' => 2.14,
                'status' => 'publish',
                'is_featured' => true,
                'gallery' => json_encode([
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendors[1]->id, // Fashion Hub
                'category_id' => $categories->where('slug', 'fashion')->first()->id,
                'name' => 'Designer Leather Jacket',
                'slug' => 'designer-leather-jacket',
                'description' => 'Premium leather jacket with modern design and excellent craftsmanship.',
                'short_description' => 'Premium leather jacket',
                'price' => 299.00,
                'sale_price' => 249.00,
                'sku' => 'LEATHERJACKET001',
                'stock_quantity' => 30,
                'min_stock_level' => 5,
                'stock_status' => 'instock',
                'weight' => 1.2,
                'status' => 'publish',
                'is_featured' => false,
                'gallery' => json_encode([
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendors[2]->id, // Home Decor Plus
                'category_id' => $categories->where('slug', 'furniture')->first()->id,
                'name' => 'Modern Dining Table',
                'slug' => 'modern-dining-table',
                'description' => 'Beautiful modern dining table perfect for family gatherings.',
                'short_description' => 'Modern dining table for family gatherings',
                'price' => 599.00,
                'sale_price' => null,
                'sku' => 'DININGTABLE001',
                'stock_quantity' => 15,
                'min_stock_level' => 2,
                'stock_status' => 'instock',
                'weight' => 45.0,
                'length' => 180,
                'width' => 90,
                'height' => 75,
                'status' => 'publish',
                'is_featured' => true,
                'gallery' => json_encode([
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendors[0]->id,
                'category_id' => $categories->where('slug', 'smartphones')->first()->id,
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'description' => 'Latest Samsung flagship with advanced AI features.',
                'short_description' => 'Samsung flagship with AI features',
                'price' => 799.00,
                'sale_price' => 749.00,
                'sku' => 'SAMSUNGS24001',
                'stock_quantity' => 40,
                'min_stock_level' => 5,
                'stock_status' => 'instock',
                'weight' => 0.167,
                'status' => 'publish',
                'is_featured' => false,
                'gallery' => json_encode([
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendors[2]->id,
                'category_id' => $categories->where('slug', 'furniture')->first()->id,
                'name' => 'Ergonomic Office Chair',
                'slug' => 'ergonomic-office-chair',
                'description' => 'Comfortable ergonomic chair perfect for long work sessions.',
                'short_description' => 'Ergonomic chair for office work',
                'price' => 199.00,
                'sale_price' => 179.00,
                'sku' => 'OFFICECHAIR001',
                'stock_quantity' => 35,
                'min_stock_level' => 5,
                'stock_status' => 'instock',
                'weight' => 15.0,
                'status' => 'publish',
                'is_featured' => false,
                'gallery' => json_encode([
                    '/images/placeholder.svg',
                    '/images/placeholder.svg',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            $productId = DB::table('products')->insertGetId($product);
            $this->command->info("Created product: {$product['name']} (ID: {$productId})");
        }
    }
}