<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating brands...');

        $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Innovative technology company known for premium products',
                'logo' => '/images/brands/apple.jpg',
                'website_url' => 'https://apple.com',
                'status' => 1,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Leading electronics manufacturer and smartphone innovator',
                'logo' => '/images/brands/samsung.jpg',
                'website_url' => 'https://samsung.com',
                'status' => 1,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Just Do It - Leading athletic wear and sports equipment',
                'logo' => '/images/brands/nike.jpg',
                'website_url' => 'https://nike.com',
                'status' => 1,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Impossible is Nothing - Sports and lifestyle brand',
                'logo' => '/images/brands/adidas.jpg',
                'website_url' => 'https://adidas.com',
                'status' => 1,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Electronics, gaming, and entertainment technology',
                'logo' => '/images/brands/sony.jpg',
                'website_url' => 'https://sony.com',
                'status' => 1,
            ],
            [
                'name' => 'LG',
                'slug' => 'lg',
                'description' => 'Consumer electronics and home appliances',
                'logo' => '/images/brands/lg.jpg',
                'website_url' => 'https://lg.com',
                'status' => 1,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Computer technology and IT solutions',
                'logo' => '/images/brands/dell.jpg',
                'website_url' => 'https://dell.com',
                'status' => 1,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'Personal computing and printing solutions',
                'logo' => '/images/brands/hp.jpg',
                'website_url' => 'https://hp.com',
                'status' => 1,
            ],
            [
                'name' => 'Canon',
                'slug' => 'canon',
                'description' => 'Digital imaging and camera technology',
                'logo' => '/images/brands/canon.jpg',
                'website_url' => 'https://canon.com',
                'status' => 1,
            ],
            [
                'name' => 'Nintendo',
                'slug' => 'nintendo',
                'description' => 'Gaming consoles and entertainment software',
                'logo' => '/images/brands/nintendo.jpg',
                'website_url' => 'https://nintendo.com',
                'status' => 1,
            ],
            [
                'name' => 'Xbox',
                'slug' => 'xbox',
                'description' => 'Microsoft gaming platform and accessories',
                'logo' => '/images/brands/xbox.jpg',
                'website_url' => 'https://xbox.com',
                'status' => 1,
            ],
            [
                'name' => 'PlayStation',
                'slug' => 'playstation',
                'description' => 'Sony gaming console and entertainment platform',
                'logo' => '/images/brands/playstation.jpg',
                'website_url' => 'https://playstation.com',
                'status' => 1,
            ],
            [
                'name' => 'IKEA',
                'slug' => 'ikea',
                'description' => 'Affordable home furniture and accessories',
                'logo' => '/images/brands/ikea.jpg',
                'website_url' => 'https://ikea.com',
                'status' => 1,
            ],
            [
                'name' => 'Zara',
                'slug' => 'zara',
                'description' => 'Fast fashion clothing and accessories',
                'logo' => '/images/brands/zara.jpg',
                'website_url' => 'https://zara.com',
                'status' => 1,
            ],
            [
                'name' => 'H&M',
                'slug' => 'hm',
                'description' => 'Fashion and lifestyle brand for the whole family',
                'logo' => '/images/brands/hm.jpg',
                'website_url' => 'https://hm.com',
                'status' => 1,
            ],
            [
                'name' => 'Toyota',
                'slug' => 'toyota',
                'description' => 'Reliable automotive manufacturer',
                'logo' => '/images/brands/toyota.jpg',
                'website_url' => 'https://toyota.com',
                'status' => 1,
            ],
            [
                'name' => 'Honda',
                'slug' => 'honda',
                'description' => 'Automotive and motorcycle manufacturer',
                'logo' => '/images/brands/honda.jpg',
                'website_url' => 'https://honda.com',
                'status' => 1,
            ],
            [
                'name' => 'BMW',
                'slug' => 'bmw',
                'description' => 'Luxury automotive and motorcycle manufacturer',
                'logo' => '/images/brands/bmw.jpg',
                'website_url' => 'https://bmw.com',
                'status' => 1,
            ],
            [
                'name' => 'Mercedes-Benz',
                'slug' => 'mercedes-benz',
                'description' => 'Luxury automotive manufacturer',
                'logo' => '/images/brands/mercedes.jpg',
                'website_url' => 'https://mercedes-benz.com',
                'status' => 1,
            ],
            [
                'name' => 'Tesla',
                'slug' => 'tesla',
                'description' => 'Electric vehicles and clean energy solutions',
                'logo' => '/images/brands/tesla.jpg',
                'website_url' => 'https://tesla.com',
                'status' => 1,
            ],
        ];

        foreach ($brands as $brandData) {
            // Check if brand already exists
            $existingBrand = DB::table('brands')->where('slug', $brandData['slug'])->first();
            if ($existingBrand) {
                $this->command->info("Brand {$brandData['name']} already exists, skipping...");
                continue;
            }

            DB::table('brands')->insert([
                'name' => $brandData['name'],
                'slug' => $brandData['slug'],
                'description' => $brandData['description'],
                'logo' => $brandData['logo'],
                'website_url' => $brandData['website_url'],
                'status' => $brandData['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created brand: {$brandData['name']}");
        }

        $this->command->info('Brand seeding completed!');
    }
}