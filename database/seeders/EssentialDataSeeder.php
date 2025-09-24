<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EssentialDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating essential data...');
        
        // Get or create tenant
        $tenant = DB::table('tenants')->first();
        if (!$tenant) {
            $tenantId = DB::table('tenants')->insertGetId([
                'name' => 'XpertBid',
                'domain' => 'xpertbid.com',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $tenantId = $tenant->id;
        }
        
        // Get existing user for vendor
        $user = DB::table('users')->where('email', 'contact@premiumauctions.com')->first();
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'tenant_id' => $tenantId,
                'name' => 'John Smith',
                'email' => 'contact@premiumauctions.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->id;
        }
        
        // Get or create vendor
        $vendor = DB::table('vendors')->where('store_name', 'Premium Auctions Store')->first();
        if (!$vendor) {
            $vendorId = DB::table('vendors')->insertGetId([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'business_name' => 'Premium Auctions',
                'business_type' => 'auction_house',
                'contact_email' => 'contact@premiumauctions.com',
                'contact_phone' => '+1-555-0123',
                'address' => '123 Auction Street',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'US',
                'postal_code' => '10001',
                'store_name' => 'Premium Auctions Store',
                'store_slug' => 'premium-auctions',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $vendorId = $vendor->id;
        }
        
        // Create categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Collectibles', 'slug' => 'collectibles', 'description' => 'Rare and valuable collectibles'],
            ['name' => 'Art & Antiques', 'slug' => 'art-antiques', 'description' => 'Artworks and antique items'],
            ['name' => 'Jewelry & Watches', 'slug' => 'jewelry-watches', 'description' => 'Fine jewelry and luxury watches'],
            ['name' => 'Sports Memorabilia', 'slug' => 'sports-memorabilia', 'description' => 'Sports collectibles and memorabilia'],
        ];
        
        $categoryIds = [];
        foreach ($categories as $category) {
            $existingCategory = DB::table('categories')->where('slug', $category['slug'])->first();
            if (!$existingCategory) {
                $categoryId = DB::table('categories')->insertGetId([
                    'tenant_id' => $tenantId,
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $categoryId = $existingCategory->id;
            }
            $categoryIds[] = $categoryId;
        }
        
        // Create products
        $products = [
            [
                'name' => 'Vintage Rolex Submariner',
                'slug' => 'vintage-rolex-submariner',
                'description' => 'Authentic vintage Rolex Submariner in excellent condition.',
                'category_id' => $categoryIds[3], // Jewelry & Watches
                'price' => 8000.00,
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Antique Persian Rug',
                'slug' => 'antique-persian-rug',
                'description' => 'Beautiful hand-woven Persian rug from the 1920s.',
                'category_id' => $categoryIds[2], // Art & Antiques
                'price' => 1500.00,
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Classic Gibson Les Paul',
                'slug' => 'classic-gibson-les-paul',
                'description' => '1959 Gibson Les Paul reissue in cherry sunburst.',
                'category_id' => $categoryIds[0], // Electronics (musical instruments)
                'price' => 3500.00,
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Vintage Wine Collection',
                'slug' => 'vintage-wine-collection',
                'description' => 'Collection of 12 vintage wines from the 1990s.',
                'category_id' => $categoryIds[1], // Collectibles
                'price' => 600.00,
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Signed Baseball Memorabilia',
                'slug' => 'signed-baseball-memorabilia',
                'description' => 'Authentic signed baseball from the 1998 World Series.',
                'category_id' => $categoryIds[4], // Sports Memorabilia
                'price' => 300.00,
                'status' => 'active',
                'is_featured' => false,
            ],
        ];
        
        $productIds = [];
        foreach ($products as $index => $product) {
            $productId = DB::table('products')->insertGetId([
                'tenant_id' => $tenantId,
                'vendor_id' => $vendorId,
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'description' => $product['description'],
                'sku' => 'PROD-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'price' => $product['price'],
                'status' => $product['status'],
                'is_featured' => $product['is_featured'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $productIds[] = $productId;
        }
        
        $this->command->info('Created tenant, vendor, ' . count($categories) . ' categories, and ' . count($products) . ' products.');
    }
}
