<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtendedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating extended product catalog...');

        // Get existing data
        $tenant = DB::table('tenants')->first();
        $vendors = DB::table('vendors')->get();
        $categories = DB::table('categories')->get();
        $brands = DB::table('brands')->get();

        if (!$tenant || $vendors->isEmpty() || $categories->isEmpty()) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        // Get category IDs
        $electronicsId = $categories->where('slug', 'electronics')->first()->id ?? $categories->first()->id;
        $smartphonesId = $categories->where('slug', 'smartphones')->first()->id ?? $categories->first()->id;
        $laptopsId = $categories->where('slug', 'laptops')->first()->id ?? $categories->first()->id;
        $fashionId = $categories->where('slug', 'fashion')->first()->id ?? $categories->first()->id;
        $homeGardenId = $categories->where('slug', 'home-garden')->first()->id ?? $categories->first()->id;
        $sportsId = $categories->where('slug', 'sports-outdoors')->first()->id ?? $categories->first()->id;

        // Get brand IDs
        $appleId = $brands->where('slug', 'apple')->first()->id ?? null;
        $samsungId = $brands->where('slug', 'samsung')->first()->id ?? null;
        $nikeId = $brands->where('slug', 'nike')->first()->id ?? null;
        $adidasId = $brands->where('slug', 'adidas')->first()->id ?? null;
        $sonyId = $brands->where('slug', 'sony')->first()->id ?? null;
        $lgId = $brands->where('slug', 'lg')->first()->id ?? null;
        $dellId = $brands->where('slug', 'dell')->first()->id ?? null;
        $hpId = $brands->where('slug', 'hp')->first()->id ?? null;
        $canonId = $brands->where('slug', 'canon')->first()->id ?? null;
        $nintendoId = $brands->where('slug', 'nintendo')->first()->id ?? null;
        $xboxId = $brands->where('slug', 'xbox')->first()->id ?? null;
        $playstationId = $brands->where('slug', 'playstation')->first()->id ?? null;
        $ikeaId = $brands->where('slug', 'ikea')->first()->id ?? null;
        $zaraId = $brands->where('slug', 'zara')->first()->id ?? null;
        $hmId = $brands->where('slug', 'hm')->first()->id ?? null;

        // Extended product catalog
        $products = [
            // Smartphones
            [
                'name' => 'iPhone 14',
                'slug' => 'iphone-14',
                'description' => 'Advanced smartphone with A15 Bionic chip and dual-camera system.',
                'short_description' => 'Advanced smartphone with A15 Bionic chip',
                'price' => 799.00,
                'sale_price' => 699.00,
                'sku' => 'IPHONE14-001',
                'stock_quantity' => 75,
                'min_stock_level' => 10,
                'stock_status' => 'in_stock',
                'weight' => 0.172,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $smartphonesId,
                'brand_id' => $appleId,
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=600&fit=crop',
                ]),
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'slug' => 'samsung-galaxy-s23',
                'description' => 'Premium Android smartphone with advanced camera technology.',
                'short_description' => 'Premium Android smartphone',
                'price' => 899.00,
                'sale_price' => 799.00,
                'sku' => 'GALS23-001',
                'stock_quantity' => 60,
                'min_stock_level' => 8,
                'stock_status' => 'in_stock',
                'weight' => 0.168,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $smartphonesId,
                'brand_id' => $samsungId,
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop',
                ]),
            ],
            [
                'name' => 'Google Pixel 7',
                'slug' => 'google-pixel-7',
                'description' => 'AI-powered smartphone with exceptional camera capabilities.',
                'short_description' => 'AI-powered smartphone',
                'price' => 599.00,
                'sale_price' => 499.00,
                'sku' => 'GPIXEL7-001',
                'stock_quantity' => 45,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 0.197,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $smartphonesId,
                'brand_id' => null,
                'gallery' => json_encode([
                    'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=600&fit=crop',
                ]),
            ],

            // Laptops
            [
                'name' => 'Dell XPS 13',
                'slug' => 'dell-xps-13',
                'description' => 'Ultrabook with InfinityEdge display and premium build quality.',
                'short_description' => 'Premium ultrabook laptop',
                'price' => 1299.00,
                'sale_price' => 1099.00,
                'sku' => 'DELLXPS13-001',
                'stock_quantity' => 30,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 1.27,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $laptopsId,
                'brand_id' => $dellId,
                'gallery' => json_encode([
                    '/images/products/dellxps13-1.jpg',
                    '/images/products/dellxps13-2.jpg',
                ]),
            ],
            [
                'name' => 'HP Pavilion 15',
                'slug' => 'hp-pavilion-15',
                'description' => 'Reliable laptop perfect for work and entertainment.',
                'short_description' => 'Reliable work laptop',
                'price' => 699.00,
                'sale_price' => 599.00,
                'sku' => 'HPPAV15-001',
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'stock_status' => 'in_stock',
                'weight' => 1.75,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $laptopsId,
                'brand_id' => $hpId,
                'gallery' => json_encode([
                    '/images/products/hppavilion15-1.jpg',
                    '/images/products/hppavilion15-2.jpg',
                ]),
            ],
            [
                'name' => 'ASUS ROG Strix',
                'slug' => 'asus-rog-strix',
                'description' => 'Gaming laptop with high-performance graphics and cooling.',
                'short_description' => 'High-performance gaming laptop',
                'price' => 1599.00,
                'sale_price' => 1399.00,
                'sku' => 'ASUSROG-001',
                'stock_quantity' => 20,
                'min_stock_level' => 3,
                'stock_status' => 'in_stock',
                'weight' => 2.3,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $laptopsId,
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/asusrog-1.jpg',
                    '/images/products/asusrog-2.jpg',
                ]),
            ],

            // Gaming Consoles
            [
                'name' => 'PlayStation 5',
                'slug' => 'playstation-5',
                'description' => 'Next-generation gaming console with ultra-fast SSD.',
                'short_description' => 'Next-gen gaming console',
                'price' => 499.00,
                'sale_price' => 449.00,
                'sku' => 'PS5-001',
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 4.5,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $electronicsId,
                'brand_id' => $playstationId,
                'gallery' => json_encode([
                    '/images/products/ps5-1.jpg',
                    '/images/products/ps5-2.jpg',
                ]),
            ],
            [
                'name' => 'Xbox Series X',
                'slug' => 'xbox-series-x',
                'description' => 'Most powerful Xbox console with 4K gaming.',
                'short_description' => 'Most powerful Xbox console',
                'price' => 499.00,
                'sale_price' => 449.00,
                'sku' => 'XBOX-X-001',
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 4.45,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $electronicsId,
                'brand_id' => $xboxId,
                'gallery' => json_encode([
                    '/images/products/xbox-x-1.jpg',
                    '/images/products/xbox-x-2.jpg',
                ]),
            ],
            [
                'name' => 'Nintendo Switch',
                'slug' => 'nintendo-switch',
                'description' => 'Hybrid gaming console for home and on-the-go play.',
                'short_description' => 'Hybrid gaming console',
                'price' => 299.00,
                'sale_price' => 279.00,
                'sku' => 'NSWITCH-001',
                'stock_quantity' => 40,
                'min_stock_level' => 8,
                'stock_status' => 'in_stock',
                'weight' => 0.66,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $electronicsId,
                'brand_id' => $nintendoId,
                'gallery' => json_encode([
                    '/images/products/switch-1.jpg',
                    '/images/products/switch-2.jpg',
                ]),
            ],

            // Fashion - Shoes
            [
                'name' => 'Nike Air Jordan 1',
                'slug' => 'nike-air-jordan-1',
                'description' => 'Classic basketball sneaker with timeless design.',
                'short_description' => 'Classic basketball sneaker',
                'price' => 170.00,
                'sale_price' => 150.00,
                'sku' => 'NIKE-AJ1-001',
                'stock_quantity' => 100,
                'min_stock_level' => 15,
                'stock_status' => 'in_stock',
                'weight' => 0.8,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $fashionId,
                'brand_id' => $nikeId,
                'gallery' => json_encode([
                    '/images/products/jordan1-1.jpg',
                    '/images/products/jordan1-2.jpg',
                ]),
            ],
            [
                'name' => 'Adidas Stan Smith',
                'slug' => 'adidas-stan-smith',
                'description' => 'Iconic tennis shoe with minimalist design.',
                'short_description' => 'Iconic tennis shoe',
                'price' => 80.00,
                'sale_price' => 65.00,
                'sku' => 'ADIDAS-SS-001',
                'stock_quantity' => 120,
                'min_stock_level' => 20,
                'stock_status' => 'in_stock',
                'weight' => 0.6,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $fashionId,
                'brand_id' => $adidasId,
                'gallery' => json_encode([
                    '/images/products/stansmith-1.jpg',
                    '/images/products/stansmith-2.jpg',
                ]),
            ],
            [
                'name' => 'Nike Dunk Low',
                'slug' => 'nike-dunk-low',
                'description' => 'Retro basketball-inspired sneaker.',
                'short_description' => 'Retro basketball sneaker',
                'price' => 110.00,
                'sale_price' => 95.00,
                'sku' => 'NIKE-DUNK-001',
                'stock_quantity' => 80,
                'min_stock_level' => 12,
                'stock_status' => 'in_stock',
                'weight' => 0.7,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $fashionId,
                'brand_id' => $nikeId,
                'gallery' => json_encode([
                    '/images/products/dunk-1.jpg',
                    '/images/products/dunk-2.jpg',
                ]),
            ],

            // Fashion - Clothing
            [
                'name' => 'Zara Basic T-Shirt',
                'slug' => 'zara-basic-t-shirt',
                'description' => 'Comfortable cotton t-shirt in various colors.',
                'short_description' => 'Comfortable cotton t-shirt',
                'price' => 19.99,
                'sale_price' => 15.99,
                'sku' => 'ZARA-TSHIRT-001',
                'stock_quantity' => 200,
                'min_stock_level' => 30,
                'stock_status' => 'in_stock',
                'weight' => 0.2,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $fashionId,
                'brand_id' => $zaraId,
                'gallery' => json_encode([
                    '/images/products/zara-tshirt-1.jpg',
                    '/images/products/zara-tshirt-2.jpg',
                ]),
            ],
            [
                'name' => 'H&M Denim Jacket',
                'slug' => 'hm-denim-jacket',
                'description' => 'Classic denim jacket for casual wear.',
                'short_description' => 'Classic denim jacket',
                'price' => 39.99,
                'sale_price' => 29.99,
                'sku' => 'HM-DENIM-001',
                'stock_quantity' => 75,
                'min_stock_level' => 10,
                'stock_status' => 'in_stock',
                'weight' => 0.8,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $fashionId,
                'brand_id' => $hmId,
                'gallery' => json_encode([
                    '/images/products/hm-denim-1.jpg',
                    '/images/products/hm-denim-2.jpg',
                ]),
            ],

            // Home & Garden
            [
                'name' => 'IKEA BILLY Bookcase',
                'slug' => 'ikea-billy-bookcase',
                'description' => 'Versatile bookcase perfect for organizing books and displays.',
                'short_description' => 'Versatile bookcase',
                'price' => 89.99,
                'sale_price' => 69.99,
                'sku' => 'IKEA-BILLY-001',
                'stock_quantity' => 30,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 25.0,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 2 ? $vendors[2]->id : $vendors->first()->id,
                'category_id' => $homeGardenId,
                'brand_id' => $ikeaId,
                'gallery' => json_encode([
                    '/images/products/billy-1.jpg',
                    '/images/products/billy-2.jpg',
                ]),
            ],
            [
                'name' => 'Smart LED Light Bulbs',
                'slug' => 'smart-led-light-bulbs',
                'description' => 'WiFi-enabled smart LED bulbs with color changing capabilities.',
                'short_description' => 'WiFi smart LED bulbs',
                'price' => 49.99,
                'sale_price' => 39.99,
                'sku' => 'SMART-LED-001',
                'stock_quantity' => 100,
                'min_stock_level' => 15,
                'stock_status' => 'in_stock',
                'weight' => 0.3,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->count() > 2 ? $vendors[2]->id : $vendors->first()->id,
                'category_id' => $homeGardenId,
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/smart-led-1.jpg',
                    '/images/products/smart-led-2.jpg',
                ]),
            ],
            [
                'name' => 'Coffee Maker Deluxe',
                'slug' => 'coffee-maker-deluxe',
                'description' => 'Programmable coffee maker with built-in grinder.',
                'short_description' => 'Programmable coffee maker',
                'price' => 199.99,
                'sale_price' => 149.99,
                'sku' => 'COFFEE-DELUXE-001',
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'stock_status' => 'in_stock',
                'weight' => 3.5,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 2 ? $vendors[2]->id : $vendors->first()->id,
                'category_id' => $homeGardenId,
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/coffee-maker-1.jpg',
                    '/images/products/coffee-maker-2.jpg',
                ]),
            ],

            // Sports & Outdoors
            [
                'name' => 'Yoga Mat Premium',
                'slug' => 'yoga-mat-premium',
                'description' => 'Non-slip yoga mat with carrying strap.',
                'short_description' => 'Non-slip yoga mat',
                'price' => 45.99,
                'sale_price' => 35.99,
                'sku' => 'YOGA-MAT-001',
                'stock_quantity' => 60,
                'min_stock_level' => 10,
                'stock_status' => 'in_stock',
                'weight' => 1.2,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $sportsId,
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/yoga-mat-1.jpg',
                    '/images/products/yoga-mat-2.jpg',
                ]),
            ],
            [
                'name' => 'Resistance Bands Set',
                'slug' => 'resistance-bands-set',
                'description' => 'Complete set of resistance bands for home workouts.',
                'short_description' => 'Resistance bands workout set',
                'price' => 29.99,
                'sale_price' => 24.99,
                'sku' => 'RESISTANCE-SET-001',
                'stock_quantity' => 80,
                'min_stock_level' => 15,
                'stock_status' => 'in_stock',
                'weight' => 0.5,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $sportsId,
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/resistance-1.jpg',
                    '/images/products/resistance-2.jpg',
                ]),
            ],
            [
                'name' => 'Bluetooth Headphones',
                'slug' => 'bluetooth-headphones',
                'description' => 'Wireless headphones with noise cancellation.',
                'short_description' => 'Wireless noise-cancelling headphones',
                'price' => 199.99,
                'sale_price' => 149.99,
                'sku' => 'BT-HEADPHONES-001',
                'stock_quantity' => 40,
                'min_stock_level' => 8,
                'stock_status' => 'in_stock',
                'weight' => 0.4,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $electronicsId,
                'brand_id' => $sonyId,
                'gallery' => json_encode([
                    '/images/products/bt-headphones-1.jpg',
                    '/images/products/bt-headphones-2.jpg',
                ]),
            ],
            [
                'name' => 'Digital Camera DSLR',
                'slug' => 'digital-camera-dslr',
                'description' => 'Professional DSLR camera with 24MP sensor.',
                'short_description' => 'Professional DSLR camera',
                'price' => 899.99,
                'sale_price' => 799.99,
                'sku' => 'DSLR-CAM-001',
                'stock_quantity' => 15,
                'min_stock_level' => 3,
                'stock_status' => 'in_stock',
                'weight' => 1.2,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors->first()->id,
                'category_id' => $electronicsId,
                'brand_id' => $canonId,
                'gallery' => json_encode([
                    '/images/products/dslr-1.jpg',
                    '/images/products/dslr-2.jpg',
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

        $this->command->info('Extended product seeding completed!');
    }
}
