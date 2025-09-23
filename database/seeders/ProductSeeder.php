<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating products...');

        // Get existing data
        $tenant = DB::table('tenants')->first();
        $vendors = DB::table('vendors')->get();
        $categories = DB::table('categories')->get();
        $brands = DB::table('brands')->get();

        if (!$tenant || $vendors->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        // Product data with free/open license images
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'The latest iPhone with advanced camera system and A17 Pro chip. Features titanium design, Action Button, and USB-C connectivity.',
                'short_description' => 'Latest iPhone with advanced features',
                'price' => 999.00,
                'sale_price' => 949.00,
                'sku' => 'IPHONE15PRO001',
                'stock_quantity' => 50,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 0.187,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'electronics')->first()->id,
                'brand_id' => $brands->where('slug', 'apple')->first()->id ?? null,
                'gallery' => json_encode([
                    '/images/products/iphone15pro-1.jpg',
                    '/images/products/iphone15pro-2.jpg',
                    '/images/products/iphone15pro-3.jpg',
                ]),
            ],
            [
                'name' => 'MacBook Pro 16-inch',
                'slug' => 'macbook-pro-16-inch',
                'description' => 'Powerful laptop with M3 Pro chip, 16-inch Liquid Retina XDR display, and all-day battery life.',
                'short_description' => 'Professional laptop with M3 Pro chip',
                'price' => 2499.00,
                'sale_price' => 2299.00,
                'sku' => 'MBP16M3PRO001',
                'stock_quantity' => 25,
                'min_stock_level' => 3,
                'stock_status' => 'in_stock',
                'weight' => 2.14,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'electronics')->first()->id,
                'brand_id' => $brands->where('slug', 'apple')->first()->id ?? null,
                'gallery' => json_encode([
                    '/images/products/macbookpro-1.jpg',
                    '/images/products/macbookpro-2.jpg',
                ]),
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Premium Android smartphone with S Pen, 200MP camera, and AI-powered features.',
                'short_description' => 'Premium Android smartphone with S Pen',
                'price' => 1299.00,
                'sale_price' => 1199.00,
                'sku' => 'GALS24ULTRA001',
                'stock_quantity' => 30,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 0.232,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'electronics')->first()->id,
                'brand_id' => $brands->where('slug', 'samsung')->first()->id ?? null,
                'gallery' => json_encode([
                    '/images/products/galaxys24-1.jpg',
                    '/images/products/galaxys24-2.jpg',
                ]),
            ],
            [
                'name' => 'Nike Air Max 270',
                'slug' => 'nike-air-max-270',
                'description' => 'Comfortable running shoes with Max Air cushioning and breathable mesh upper.',
                'short_description' => 'Comfortable running shoes',
                'price' => 150.00,
                'sale_price' => 120.00,
                'sku' => 'NIKEAM270001',
                'stock_quantity' => 100,
                'min_stock_level' => 10,
                'stock_status' => 'in_stock',
                'weight' => 0.8,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'fashion')->first()->id,
                'brand_id' => $brands->where('slug', 'nike')->first()->id ?? null,
                'gallery' => json_encode([
                    '/images/products/nikeairmax-1.jpg',
                    '/images/products/nikeairmax-2.jpg',
                ]),
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'slug' => 'adidas-ultraboost-22',
                'description' => 'High-performance running shoes with Boost midsole technology.',
                'short_description' => 'High-performance running shoes',
                'price' => 180.00,
                'sale_price' => 150.00,
                'sku' => 'ADIDASUB22001',
                'stock_quantity' => 80,
                'min_stock_level' => 10,
                'stock_status' => 'in_stock',
                'weight' => 0.7,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'fashion')->first()->id,
                'brand_id' => $brands->where('slug', 'adidas')->first()->id ?? null,
                'gallery' => json_encode([
                    '/images/products/adidasultraboost-1.jpg',
                    '/images/products/adidasultraboost-2.jpg',
                ]),
            ],
            [
                'name' => 'Smart Home Hub',
                'slug' => 'smart-home-hub',
                'description' => 'Central control system for your smart home devices with voice control and app integration.',
                'short_description' => 'Central smart home control system',
                'price' => 199.00,
                'sale_price' => 149.00,
                'sku' => 'SMARTHUB001',
                'stock_quantity' => 40,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 0.5,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->count() > 2 ? $vendors[2]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'home-garden')->first()->id,
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/smarthub-1.jpg',
                    '/images/products/smarthub-2.jpg',
                ]),
            ],
        ];

        foreach ($products as $productData) {
            // Check if product already exists
            $existingProduct = DB::table('products')->where('slug', $productData['slug'])->first();
            if ($existingProduct) {
                $this->command->info("Product {$productData['name']} already exists, skipping...");
                continue;
            }

            DB::table('products')->insert([
                'tenant_id' => $tenant->id,
                'vendor_id' => $productData['vendor_id'],
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'slug' => $productData['slug'],
                'description' => $productData['description'],
                'short_description' => $productData['short_description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'sku' => $productData['sku'],
                'quantity' => $productData['stock_quantity'],
                'min_quantity' => $productData['min_stock_level'],
                'stock_status' => $productData['stock_status'],
                'weight' => $productData['weight'],
                'status' => $productData['status'],
                'is_featured' => $productData['is_featured'],
                'gallery_images' => $productData['gallery'],
                'featured_image' => json_decode($productData['gallery'])[0] ?? null,
                'thumbnail_image' => json_decode($productData['gallery'])[0] ?? null,
                'product_type' => 'simple',
                'visibility' => 'visible',
                'catalog_visibility' => 'visible',
                'manage_stock' => true,
                'track_quantity' => true,
                'requires_shipping' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created product: {$productData['name']}");
        }

        $this->command->info('Product seeding completed!');
    }
}