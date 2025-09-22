<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Apple Inc. is an American multinational technology company.',
                'logo' => 'https://via.placeholder.com/150x150/000000/FFFFFF?text=Apple',
                'website_url' => 'https://www.apple.com',
                'status' => true,
                'seo_title' => 'Apple Products - Premium Technology',
                'seo_description' => 'Discover Apple\'s innovative products including iPhone, iPad, Mac, and more.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Samsung is a South Korean multinational conglomerate.',
                'logo' => 'https://via.placeholder.com/150x150/1428A0/FFFFFF?text=Samsung',
                'website_url' => 'https://www.samsung.com',
                'status' => true,
                'seo_title' => 'Samsung Electronics - Innovation for Everyone',
                'seo_description' => 'Explore Samsung\'s wide range of electronics and home appliances.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Sony Corporation is a Japanese multinational conglomerate.',
                'logo' => 'https://via.placeholder.com/150x150/000000/FFFFFF?text=Sony',
                'website_url' => 'https://www.sony.com',
                'status' => true,
                'seo_title' => 'Sony - Be Moved',
                'seo_description' => 'Experience Sony\'s premium electronics, gaming, and entertainment products.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Nike, Inc. is an American multinational corporation.',
                'logo' => 'https://via.placeholder.com/150x150/000000/FFFFFF?text=Nike',
                'website_url' => 'https://www.nike.com',
                'status' => true,
                'seo_title' => 'Nike - Just Do It',
                'seo_description' => 'Shop Nike\'s athletic shoes, clothing, and sports equipment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Adidas AG is a German multinational corporation.',
                'logo' => 'https://via.placeholder.com/150x150/000000/FFFFFF?text=Adidas',
                'website_url' => 'https://www.adidas.com',
                'status' => true,
                'seo_title' => 'Adidas - Impossible is Nothing',
                'seo_description' => 'Discover Adidas\' sports and lifestyle products.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Microsoft',
                'slug' => 'microsoft',
                'description' => 'Microsoft Corporation is an American multinational technology corporation.',
                'logo' => 'https://via.placeholder.com/150x150/00BCF2/FFFFFF?text=Microsoft',
                'website_url' => 'https://www.microsoft.com',
                'status' => true,
                'seo_title' => 'Microsoft - Empowering Every Person',
                'seo_description' => 'Explore Microsoft\'s software, hardware, and cloud services.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Google',
                'slug' => 'google',
                'description' => 'Google LLC is an American multinational technology company.',
                'logo' => 'https://via.placeholder.com/150x150/4285F4/FFFFFF?text=Google',
                'website_url' => 'https://www.google.com',
                'status' => true,
                'seo_title' => 'Google - Organize the World\'s Information',
                'seo_description' => 'Discover Google\'s products and services.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tesla',
                'slug' => 'tesla',
                'description' => 'Tesla, Inc. is an American electric vehicle and clean energy company.',
                'logo' => 'https://via.placeholder.com/150x150/CC0000/FFFFFF?text=Tesla',
                'website_url' => 'https://www.tesla.com',
                'status' => true,
                'seo_title' => 'Tesla - Accelerating the World\'s Transition',
                'seo_description' => 'Explore Tesla\'s electric vehicles and energy products.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BMW',
                'slug' => 'bmw',
                'description' => 'Bayerische Motoren Werke AG is a German multinational corporation.',
                'logo' => 'https://via.placeholder.com/150x150/0066CC/FFFFFF?text=BMW',
                'website_url' => 'https://www.bmw.com',
                'status' => true,
                'seo_title' => 'BMW - The Ultimate Driving Machine',
                'seo_description' => 'Discover BMW\'s luxury vehicles and motorcycles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mercedes-Benz',
                'slug' => 'mercedes-benz',
                'description' => 'Mercedes-Benz is a German luxury automotive brand.',
                'logo' => 'https://via.placeholder.com/150x150/000000/FFFFFF?text=Mercedes',
                'website_url' => 'https://www.mercedes-benz.com',
                'status' => true,
                'seo_title' => 'Mercedes-Benz - The Best or Nothing',
                'seo_description' => 'Experience Mercedes-Benz luxury vehicles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }
}