<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive database seeding...');

        // Create tenant first
        $tenant = $this->createTenant();
        
        // Create admin user
        $this->createAdminUser($tenant);
        
        // Create vendors
        $vendors = $this->createVendors($tenant);
        
        // Create categories
        $categories = $this->createCategories($tenant);
        
        // Create brands
        $brands = $this->createBrands($tenant);
        
        // Create products
        $products = $this->createProducts($tenant, $vendors, $categories, $brands);
        
        // Create auctions
        $this->createAuctions($tenant, $vendors, $categories);
        
        // Create properties
        $this->createProperties($tenant, $vendors);
        
        // Create vehicles
        $this->createVehicles($tenant, $vendors);
        
        // Create languages and currencies
        $this->createLanguagesAndCurrencies();
        
        // Create roles and permissions
        $this->createRolesAndPermissions();
        
        // Create shipping data
        $this->createShippingData($tenant);
        
        // Create payment gateways
        $this->createPaymentGateways($tenant);

        $this->command->info('Comprehensive seeding completed successfully!');
    }

    private function createTenant()
    {
        $this->command->info('Creating tenant...');
        
        // Check if tenant already exists
        $existingTenant = DB::table('tenants')->first();
        if ($existingTenant) {
            $this->command->info('Tenant already exists, using existing tenant...');
            return $existingTenant->id;
        }
        
        $tenant = DB::table('tenants')->insertGetId([
            'name' => 'XpertBid Marketplace',
            'domain' => 'xpertbid.com',
            'subdomain' => 'main',
            'custom_domain' => null,
            'database_name' => 'xpertbid_main',
            'status' => 'active',
            'subscription_plan' => 'premium',
            'settings' => json_encode([
                'theme' => 'woodmart',
                'currency' => 'USD',
                'language' => 'en',
                'timezone' => 'UTC'
            ]),
            'limits' => json_encode([
                'vendors' => 1000,
                'products' => 10000,
                'storage' => 50000,
                'bandwidth' => 100000
            ]),
            'monthly_fee' => 99.00,
            'vendor_limit' => 1000,
            'product_limit' => 10000,
            'storage_limit_mb' => 50000,
            'bandwidth_limit_mb' => 100000,
            'white_label' => false,
            'logo_url' => '/images/logo.svg',
            'primary_color' => '#83AD32',
            'secondary_color' => '#2C3E50',
            'trial_ends_at' => now()->addDays(30),
            'subscription_ends_at' => now()->addYear(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $tenant;
    }

    private function createAdminUser($tenantId)
    {
        $this->command->info('Creating admin user...');
        
        // Check if admin user already exists
        $existingAdmin = DB::table('users')->where('email', 'admin@xpertbid.com')->first();
        if ($existingAdmin) {
            $this->command->info('Admin user already exists, using existing admin...');
            return $existingAdmin->id;
        }
        
        $adminUser = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@xpertbid.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
            'tenant_id' => $tenantId,
            'role' => 'admin',
            'phone' => '+1-555-0123',
            'avatar' => '/images/avatars/admin.jpg',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $adminUser;
    }

    private function createVendors($tenantId)
    {
        $this->command->info('Creating vendors...');
        
        $vendors = [];
        
        $vendorData = [
            [
                'name' => 'TechStore Pro',
                'email' => 'vendor1@example.com',
                'business_name' => 'TechStore Pro',
                'business_type' => 'Electronics Retail',
                'description' => 'Leading electronics retailer specializing in the latest technology products.',
                'logo' => '/images/vendors/techstore.jpg',
                'website' => 'https://techstore.com',
                'phone' => '+1-555-0101',
                'address' => '456 Tech Avenue',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '94102',
            ],
            [
                'name' => 'Fashion Forward',
                'email' => 'vendor2@example.com',
                'business_name' => 'Fashion Forward',
                'business_type' => 'Fashion Retail',
                'description' => 'Trendy fashion store offering the latest clothing and accessories.',
                'logo' => '/images/vendors/fashion.jpg',
                'website' => 'https://fashionforward.com',
                'phone' => '+1-555-0102',
                'address' => '789 Fashion Street',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90210',
            ],
            [
                'name' => 'Home & Garden',
                'email' => 'vendor3@example.com',
                'business_name' => 'Home & Garden Solutions',
                'business_type' => 'Home Improvement',
                'description' => 'Complete home and garden solutions for modern living.',
                'logo' => '/images/vendors/homegarden.jpg',
                'website' => 'https://homegarden.com',
                'phone' => '+1-555-0103',
                'address' => '321 Garden Lane',
                'city' => 'Austin',
                'state' => 'TX',
                'country' => 'USA',
                'postal_code' => '73301',
            ]
        ];

        foreach ($vendorData as $data) {
            // Check if vendor user already exists
            $existingVendorUser = DB::table('users')->where('email', $data['email'])->first();
            if ($existingVendorUser) {
                $this->command->info("Vendor user {$data['email']} already exists, skipping...");
                continue;
            }
            
            // Create vendor user
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'tenant_id' => $tenantId,
                'role' => 'vendor',
                'phone' => $data['phone'],
                'avatar' => '/images/avatars/vendor.jpg',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create vendor profile
            $vendorId = DB::table('vendors')->insertGetId([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'business_name' => $data['business_name'],
                'business_type' => $data['business_type'],
                'store_name' => $data['business_name'],
                'store_slug' => Str::slug($data['business_name']),
                'store_description' => $data['description'],
                'store_logo' => $data['logo'],
                'contact_email' => $data['email'],
                'contact_phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'postal_code' => $data['postal_code'],
                'status' => 'active',
                'verified' => true,
                'commission_rate' => 5.0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $vendors[] = ['id' => $vendorId, 'user_id' => $userId];
        }

        return $vendors;
    }

    private function createCategories($tenantId)
    {
        $this->command->info('Creating categories...');
        
        $categories = [];
        
        $categoryData = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets for modern living',
                'image' => '/images/categories/electronics.jpg',
                'parent_id' => null,
                'sort_order' => 1,
            ],
            [
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'description' => 'Latest smartphones and mobile devices',
                'image' => '/images/categories/smartphones.jpg',
                'parent_id' => null, // Will be updated after parent is created
                'sort_order' => 2,
            ],
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Laptops and portable computers',
                'image' => '/images/categories/laptops.jpg',
                'parent_id' => null, // Will be updated after parent is created
                'sort_order' => 3,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing and fashion accessories',
                'image' => '/images/categories/fashion.jpg',
                'parent_id' => null,
                'sort_order' => 4,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home and garden products',
                'image' => '/images/categories/homegarden.jpg',
                'parent_id' => null,
                'sort_order' => 5,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment and outdoor gear',
                'image' => '/images/categories/sports.jpg',
                'parent_id' => null,
                'sort_order' => 6,
            ],
        ];

        foreach ($categoryData as $data) {
            $categoryId = DB::table('categories')->insertGetId([
                'tenant_id' => $tenantId,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'image' => $data['image'],
                'parent_id' => $data['parent_id'],
                'sort_order' => $data['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categories[] = ['id' => $categoryId, 'slug' => $data['slug']];

            // Update parent_id for subcategories
            if ($data['slug'] === 'smartphones' || $data['slug'] === 'laptops') {
                DB::table('categories')
                    ->where('id', $categoryId)
                    ->update(['parent_id' => $categories[0]['id']]);
            }
        }

        return $categories;
    }

    private function createBrands($tenantId)
    {
        $this->command->info('Creating brands...');
        
        $brands = [];
        
        $brandData = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Innovative technology company',
                'logo' => '/images/brands/apple.jpg',
                'website' => 'https://apple.com',
                'is_active' => true,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Leading electronics manufacturer',
                'logo' => '/images/brands/samsung.jpg',
                'website' => 'https://samsung.com',
                'is_active' => true,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Just Do It - Athletic wear and shoes',
                'logo' => '/images/brands/nike.jpg',
                'website' => 'https://nike.com',
                'is_active' => true,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Impossible is Nothing - Sports brand',
                'logo' => '/images/brands/adidas.jpg',
                'website' => 'https://adidas.com',
                'is_active' => true,
            ],
        ];

        foreach ($brandData as $data) {
            $brandId = DB::table('brands')->insertGetId([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'logo' => $data['logo'],
                'website_url' => $data['website'],
                'status' => $data['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $brands[] = ['id' => $brandId, 'slug' => $data['slug']];
        }

        return $brands;
    }

    private function createProducts($tenantId, $vendors, $categories, $brands)
    {
        $this->command->info('Creating products...');
        
        $products = [];
        
        $productData = [
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
                'stock_status' => 'instock',
                'weight' => 0.187,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors[0]['id'],
                'category_id' => $categories[1]['id'], // Smartphones
                'brand_id' => $brands[0]['id'], // Apple
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
                'stock_status' => 'instock',
                'weight' => 2.14,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors[0]['id'],
                'category_id' => $categories[2]['id'], // Laptops
                'brand_id' => $brands[0]['id'], // Apple
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
                'stock_status' => 'instock',
                'weight' => 0.232,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors[0]['id'],
                'category_id' => $categories[1]['id'], // Smartphones
                'brand_id' => $brands[1]['id'], // Samsung
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
                'stock_status' => 'instock',
                'weight' => 0.8,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors[1]['id'],
                'category_id' => $categories[5]['id'], // Sports
                'brand_id' => $brands[2]['id'], // Nike
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
                'stock_status' => 'instock',
                'weight' => 0.7,
                'status' => 'publish',
                'is_featured' => false,
                'vendor_id' => $vendors[1]['id'],
                'category_id' => $categories[5]['id'], // Sports
                'brand_id' => $brands[3]['id'], // Adidas
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
                'stock_status' => 'instock',
                'weight' => 0.5,
                'status' => 'publish',
                'is_featured' => true,
                'vendor_id' => $vendors[2]['id'],
                'category_id' => $categories[4]['id'], // Home & Garden
                'brand_id' => null,
                'gallery' => json_encode([
                    '/images/products/smarthub-1.jpg',
                    '/images/products/smarthub-2.jpg',
                ]),
            ],
        ];

        foreach ($productData as $data) {
            $productId = DB::table('products')->insertGetId([
                'tenant_id' => $tenantId,
                'vendor_id' => $data['vendor_id'],
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'price' => $data['price'],
                'sale_price' => $data['sale_price'],
                'sku' => $data['sku'],
                'stock_quantity' => $data['stock_quantity'],
                'min_stock_level' => $data['min_stock_level'],
                'stock_status' => $data['stock_status'],
                'weight' => $data['weight'],
                'status' => $data['status'],
                'is_featured' => $data['is_featured'],
                'gallery' => $data['gallery'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $products[] = ['id' => $productId, 'slug' => $data['slug']];
        }

        return $products;
    }

    private function createAuctions($tenantId, $vendors, $categories)
    {
        $this->command->info('Creating auctions...');
        
        $auctionData = [
            [
                'title' => 'Vintage Rolex Submariner',
                'description' => 'Rare vintage Rolex Submariner from 1960s in excellent condition.',
                'starting_price' => 5000.00,
                'current_price' => 7500.00,
                'reserve_price' => 8000.00,
                'increment' => 100.00,
                'status' => 'active',
                'start_time' => now(),
                'end_time' => now()->addDays(7),
                'vendor_id' => $vendors[0]['id'],
                'category_id' => $categories[0]['id'], // Electronics
            ],
            [
                'title' => 'Antique Persian Rug',
                'description' => 'Beautiful handwoven Persian rug from the 1920s.',
                'starting_price' => 2000.00,
                'current_price' => 2800.00,
                'reserve_price' => 3000.00,
                'increment' => 50.00,
                'status' => 'active',
                'start_time' => now(),
                'end_time' => now()->addDays(5),
                'vendor_id' => $vendors[1]['id'],
                'category_id' => $categories[4]['id'], // Home & Garden
            ],
        ];

        foreach ($auctionData as $data) {
            DB::table('auctions')->insert([
                'tenant_id' => $tenantId,
                'vendor_id' => $data['vendor_id'],
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'starting_price' => $data['starting_price'],
                'current_price' => $data['current_price'],
                'reserve_price' => $data['reserve_price'],
                'increment' => $data['increment'],
                'status' => $data['status'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createProperties($tenantId, $vendors)
    {
        $this->command->info('Creating properties...');
        
        $propertyData = [
            [
                'title' => 'Modern Downtown Apartment',
                'description' => 'Beautiful modern apartment in downtown with city views.',
                'price' => 450000.00,
                'property_type' => 'apartment',
                'listing_type' => 'sale',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area' => 1200,
                'area_unit' => 'sqft',
                'address' => '123 Downtown Ave',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'status' => 'active',
                'vendor_id' => $vendors[2]['id'],
            ],
            [
                'title' => 'Luxury Villa with Pool',
                'description' => 'Stunning luxury villa with private pool and garden.',
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
                'vendor_id' => $vendors[2]['id'],
            ],
        ];

        foreach ($propertyData as $data) {
            DB::table('properties')->insert([
                'tenant_id' => $tenantId,
                'vendor_id' => $data['vendor_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price'],
                'property_type' => $data['property_type'],
                'listing_type' => $data['listing_type'],
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'area' => $data['area'],
                'area_unit' => $data['area_unit'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'postal_code' => $data['postal_code'],
                'status' => $data['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createVehicles($tenantId, $vendors)
    {
        $this->command->info('Creating vehicles...');
        
        $vehicleData = [
            [
                'title' => '2023 Tesla Model S',
                'description' => 'Electric luxury sedan with autopilot and premium features.',
                'price' => 95000.00,
                'vehicle_type' => 'car',
                'listing_type' => 'sale',
                'make' => 'Tesla',
                'model' => 'Model S',
                'year' => 2023,
                'mileage' => 5000,
                'fuel_type' => 'electric',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors[0]['id'],
            ],
            [
                'title' => '2022 BMW X5',
                'description' => 'Luxury SUV with premium interior and advanced technology.',
                'price' => 65000.00,
                'vehicle_type' => 'suv',
                'listing_type' => 'sale',
                'make' => 'BMW',
                'model' => 'X5',
                'year' => 2022,
                'mileage' => 15000,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'condition' => 'excellent',
                'status' => 'active',
                'vendor_id' => $vendors[0]['id'],
            ],
        ];

        foreach ($vehicleData as $data) {
            DB::table('vehicles')->insert([
                'tenant_id' => $tenantId,
                'vendor_id' => $data['vendor_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price'],
                'vehicle_type' => $data['vehicle_type'],
                'listing_type' => $data['listing_type'],
                'make' => $data['make'],
                'model' => $data['model'],
                'year' => $data['year'],
                'mileage' => $data['mileage'],
                'fuel_type' => $data['fuel_type'],
                'transmission' => $data['transmission'],
                'condition' => $data['condition'],
                'status' => $data['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createLanguagesAndCurrencies()
    {
        $this->command->info('Creating languages and currencies...');
        
        // Languages
        $languages = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_active' => true],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'is_active' => true],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'is_active' => true],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'is_active' => true],
        ];

        foreach ($languages as $lang) {
            DB::table('languages')->insert([
                'code' => $lang['code'],
                'name' => $lang['name'],
                'native_name' => $lang['native_name'],
                'is_active' => $lang['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Currencies
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'rate' => 1.00, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'rate' => 0.85, 'is_active' => true],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'rate' => 0.73, 'is_active' => true],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'rate' => 1.35, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->insert([
                'code' => $currency['code'],
                'name' => $currency['name'],
                'symbol' => $currency['symbol'],
                'rate' => $currency['rate'],
                'is_active' => $currency['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createRolesAndPermissions()
    {
        $this->command->info('Creating roles and permissions...');
        
        // Roles
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full system access'],
            ['name' => 'vendor', 'display_name' => 'Vendor', 'description' => 'Vendor account access'],
            ['name' => 'customer', 'display_name' => 'Customer', 'description' => 'Customer account access'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Permissions
        $permissions = [
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Create, edit, and delete users'],
            ['name' => 'manage_products', 'display_name' => 'Manage Products', 'description' => 'Create, edit, and delete products'],
            ['name' => 'manage_orders', 'display_name' => 'Manage Orders', 'description' => 'View and manage orders'],
            ['name' => 'manage_vendors', 'display_name' => 'Manage Vendors', 'description' => 'Manage vendor accounts'],
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'description' => 'Access system reports'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createShippingData($tenantId)
    {
        $this->command->info('Creating shipping data...');
        
        // Shipping Zones
        $shippingZones = [
            [
                'name' => 'United States',
                'countries' => json_encode(['US']),
                'states' => json_encode([]),
                'is_active' => true,
            ],
            [
                'name' => 'Canada',
                'countries' => json_encode(['CA']),
                'states' => json_encode([]),
                'is_active' => true,
            ],
            [
                'name' => 'Europe',
                'countries' => json_encode(['GB', 'DE', 'FR', 'IT', 'ES']),
                'states' => json_encode([]),
                'is_active' => true,
            ],
        ];

        foreach ($shippingZones as $zone) {
            $zoneId = DB::table('shipping_zones')->insertGetId([
                'tenant_id' => $tenantId,
                'name' => $zone['name'],
                'countries' => $zone['countries'],
                'states' => $zone['states'],
                'is_active' => $zone['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Shipping Methods for each zone
            $methods = [
                [
                    'name' => 'Standard Shipping',
                    'method_type' => 'standard',
                    'description' => 'Standard shipping (5-7 business days)',
                    'cost' => 9.99,
                    'free_shipping_threshold' => 75.00,
                ],
                [
                    'name' => 'Express Shipping',
                    'method_type' => 'express',
                    'description' => 'Express shipping (2-3 business days)',
                    'cost' => 19.99,
                    'free_shipping_threshold' => 150.00,
                ],
            ];

            foreach ($methods as $method) {
                DB::table('shipping_methods')->insert([
                    'tenant_id' => $tenantId,
                    'shipping_zone_id' => $zoneId,
                    'name' => $method['name'],
                    'method_type' => $method['method_type'],
                    'description' => $method['description'],
                    'cost' => $method['cost'],
                    'free_shipping_threshold' => $method['free_shipping_threshold'],
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createPaymentGateways($tenantId)
    {
        $this->command->info('Creating payment gateways...');
        
        $gateways = [
            [
                'name' => 'Stripe',
                'code' => 'stripe',
                'type' => 'online',
                'description' => 'Accept credit cards, debit cards, and digital wallets',
                'logo_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => true,
                'transaction_fee' => 0.029,
                'fixed_fee' => 0.30,
                'supported_currencies' => json_encode(['USD', 'EUR', 'GBP', 'CAD', 'AUD']),
                'supported_countries' => json_encode(['US', 'CA', 'GB', 'AU', 'DE', 'FR']),
                'sort_order' => 1,
                'settings' => json_encode([
                    'publishable_key' => 'pk_test_...',
                    'secret_key' => 'sk_test_...',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/stripe',
                ]),
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'type' => 'online',
                'description' => 'Accept PayPal payments and credit cards',
                'logo_url' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => true,
                'transaction_fee' => 0.034,
                'fixed_fee' => 0.35,
                'supported_currencies' => json_encode(['USD', 'EUR', 'GBP', 'CAD', 'AUD']),
                'supported_countries' => json_encode(['US', 'CA', 'GB', 'AU', 'DE', 'FR']),
                'sort_order' => 2,
                'settings' => json_encode([
                    'client_id' => 'test_client_id',
                    'client_secret' => 'test_client_secret',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/paypal',
                ]),
            ],
            [
                'name' => 'Razorpay',
                'code' => 'razorpay',
                'type' => 'online',
                'description' => 'Accept UPI, cards, wallets, and net banking',
                'logo_url' => 'https://images.unsplash.com/photo-1556742111-a301076d9d18?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => true,
                'transaction_fee' => 0.02,
                'fixed_fee' => 2.00,
                'supported_currencies' => json_encode(['INR', 'USD', 'EUR']),
                'supported_countries' => json_encode(['IN', 'US', 'GB']),
                'sort_order' => 3,
                'settings' => json_encode([
                    'key_id' => 'test_key_id',
                    'key_secret' => 'test_key_secret',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/razorpay',
                ]),
            ],
            [
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'type' => 'offline',
                'description' => 'Pay when your order is delivered',
                'logo_url' => 'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => false,
                'transaction_fee' => 0,
                'fixed_fee' => 0,
                'supported_currencies' => json_encode(['USD', 'EUR', 'GBP', 'INR']),
                'supported_countries' => json_encode(['US', 'CA', 'GB', 'IN']),
                'sort_order' => 4,
                'settings' => json_encode([
                    'instructions' => 'Payment will be collected upon delivery',
                    'delivery_fee' => 5.00,
                ]),
            ],
        ];

        foreach ($gateways as $gateway) {
            DB::table('payment_gateways')->insert([
                'tenant_id' => $tenantId,
                'name' => $gateway['name'],
                'code' => $gateway['code'],
                'type' => $gateway['type'],
                'description' => $gateway['description'],
                'logo_url' => $gateway['logo_url'],
                'is_active' => $gateway['is_active'],
                'is_test_mode' => $gateway['is_test_mode'],
                'transaction_fee' => $gateway['transaction_fee'],
                'fixed_fee' => $gateway['fixed_fee'],
                'supported_currencies' => $gateway['supported_currencies'],
                'supported_countries' => $gateway['supported_countries'],
                'sort_order' => $gateway['sort_order'],
                'settings' => $gateway['settings'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
