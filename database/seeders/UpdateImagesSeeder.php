<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Updating images for products, auctions, properties, and vehicles...');

        // Free image URLs from Unsplash (commercial use allowed)
        $imageUrls = [
            // Electronics/Smartphones
            'smartphones' => [
                'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1574944985070-8f3ebc6b79d2?w=800&h=600&fit=crop',
            ],
            // Laptops/Computers
            'laptops' => [
                'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&h=600&fit=crop',
            ],
            // Gaming Consoles
            'gaming' => [
                'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=800&h=600&fit=crop',
            ],
            // Fashion/Shoes
            'fashion' => [
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&h=600&fit=crop',
            ],
            // Home & Garden
            'home' => [
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop',
            ],
            // Sports & Fitness
            'sports' => [
                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
            ],
            // Audio/Electronics
            'audio' => [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop',
            ],
            // Cameras
            'cameras' => [
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=600&fit=crop',
            ],
            // Auctions (luxury items)
            'auctions' => [
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=600&fit=crop',
            ],
            // Properties
            'properties' => [
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
            ],
            // Vehicles
            'vehicles' => [
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&h=600&fit=crop',
            ],
        ];

        // Update Products
        $this->command->info('Updating product images...');
        $products = DB::table('products')->get();
        
        foreach ($products as $product) {
            $categoryName = strtolower($product->name);
            $imageCategory = 'smartphones'; // default
            
            // Determine image category based on product name
            if (strpos($categoryName, 'laptop') !== false || strpos($categoryName, 'macbook') !== false || strpos($categoryName, 'xps') !== false) {
                $imageCategory = 'laptops';
            } elseif (strpos($categoryName, 'playstation') !== false || strpos($categoryName, 'xbox') !== false || strpos($categoryName, 'nintendo') !== false) {
                $imageCategory = 'gaming';
            } elseif (strpos($categoryName, 'nike') !== false || strpos($categoryName, 'adidas') !== false || strpos($categoryName, 'shoe') !== false) {
                $imageCategory = 'fashion';
            } elseif (strpos($categoryName, 'home') !== false || strpos($categoryName, 'smart') !== false || strpos($categoryName, 'coffee') !== false) {
                $imageCategory = 'home';
            } elseif (strpos($categoryName, 'yoga') !== false || strpos($categoryName, 'resistance') !== false) {
                $imageCategory = 'sports';
            } elseif (strpos($categoryName, 'headphone') !== false || strpos($categoryName, 'audio') !== false) {
                $imageCategory = 'audio';
            } elseif (strpos($categoryName, 'camera') !== false) {
                $imageCategory = 'cameras';
            }
            
            $images = $imageUrls[$imageCategory];
            $gallery = json_encode([
                $images[array_rand($images)],
                $images[array_rand($images)],
            ]);
            
            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'gallery_images' => $gallery,
                    'featured_image' => json_decode($gallery)[0],
                    'thumbnail_image' => json_decode($gallery)[0],
                    'updated_at' => now(),
                ]);
        }

        // Update Auctions
        $this->command->info('Updating auction images...');
        $auctions = DB::table('auctions')->get();
        
        foreach ($auctions as $auction) {
            $images = $imageUrls['auctions'];
            $gallery = json_encode([
                $images[array_rand($images)],
                $images[array_rand($images)],
            ]);
            
            DB::table('auctions')
                ->where('id', $auction->id)
                ->update([
                    'images' => $gallery,
                    'updated_at' => now(),
                ]);
        }

        // Update Properties
        $this->command->info('Updating property images...');
        $properties = DB::table('properties')->get();
        
        foreach ($properties as $property) {
            $images = $imageUrls['properties'];
            $gallery = json_encode([
                $images[array_rand($images)],
                $images[array_rand($images)],
                $images[array_rand($images)],
            ]);
            
            DB::table('properties')
                ->where('id', $property->id)
                ->update([
                    'images' => $gallery,
                    'updated_at' => now(),
                ]);
        }

        // Update Vehicles
        $this->command->info('Updating vehicle images...');
        $vehicles = DB::table('vehicles')->get();
        
        foreach ($vehicles as $vehicle) {
            $images = $imageUrls['vehicles'];
            $gallery = json_encode([
                $images[array_rand($images)],
                $images[array_rand($images)],
                $images[array_rand($images)],
            ]);
            
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update([
                    'images' => $gallery,
                    'updated_at' => now(),
                ]);
        }

        $this->command->info('Image update completed!');
    }
}
