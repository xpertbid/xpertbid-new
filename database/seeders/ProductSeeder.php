<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Electronics Products
            [
                'tenant_id' => 1,
                'vendor_id' => 1, // TechGear Solutions
                'category_id' => 2, // Smartphones
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'description' => 'The latest iPhone with advanced camera system, A17 Pro chip, and titanium design.',
                'short_description' => 'Latest iPhone with titanium design',
                'sku' => 'IPH15PM-256-TIT',
                'barcode' => '1234567890123',
                'type' => 'physical',
                'price' => 1199.00,
                'compare_price' => 1299.00,
                'cost_price' => 900.00,
                'quantity' => 50,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'track_quantity' => true,
                'allow_backorder' => false,
                'weight' => 0.221,
                'length' => 15.9,
                'width' => 7.7,
                'height' => 0.83,
                'status' => 'active',
                'is_featured' => true,
                'is_digital' => false,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2c3e50/ffffff?text=iPhone+15+Pro+Max',
                    'https://via.placeholder.com/800x600/3498db/ffffff?text=iPhone+15+Pro+Max+2'
                ]),
                'attributes' => json_encode([
                    'storage' => '256GB',
                    'color' => 'Titanium',
                    'screen_size' => '6.7 inch',
                    'camera' => '48MP Main Camera'
                ]),
                'variations' => json_encode([
                    'storage' => ['128GB', '256GB', '512GB', '1TB'],
                    'color' => ['Titanium', 'Blue Titanium', 'White Titanium', 'Black Titanium']
                ]),
                'seo_meta' => json_encode([
                    'meta_title' => 'iPhone 15 Pro Max - Latest Apple Smartphone',
                    'meta_description' => 'Buy iPhone 15 Pro Max with titanium design and advanced camera',
                    'keywords' => 'iPhone 15, Pro Max, Apple, smartphone, titanium'
                ]),
                'shipping_info' => json_encode([
                    'weight' => '0.221 kg',
                    'dimensions' => '15.9 x 7.7 x 0.83 cm',
                    'shipping_class' => 'standard'
                ]),
                'views_count' => 1250,
                'sales_count' => 45,
                'rating' => 4.8,
                'reviews_count' => 23,
                'published_at' => now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'vendor_id' => 1, // TechGear Solutions
                'category_id' => 3, // Laptops
                'name' => 'MacBook Pro 16-inch M3 Max',
                'slug' => 'macbook-pro-16-inch-m3-max',
                'description' => 'Powerful MacBook Pro with M3 Max chip, 16-inch Liquid Retina XDR display, and up to 22 hours battery life.',
                'short_description' => 'MacBook Pro with M3 Max chip',
                'sku' => 'MBP16-M3MAX-1TB',
                'barcode' => '1234567890124',
                'type' => 'physical',
                'price' => 3499.00,
                'compare_price' => 3699.00,
                'cost_price' => 2800.00,
                'quantity' => 25,
                'min_quantity' => 1,
                'max_quantity' => 5,
                'track_quantity' => true,
                'allow_backorder' => false,
                'weight' => 2.16,
                'length' => 35.57,
                'width' => 24.81,
                'height' => 1.68,
                'status' => 'active',
                'is_featured' => true,
                'is_digital' => false,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2c3e50/ffffff?text=MacBook+Pro+16',
                    'https://via.placeholder.com/800x600/3498db/ffffff?text=MacBook+Pro+16+2'
                ]),
                'attributes' => json_encode([
                    'processor' => 'M3 Max',
                    'memory' => '32GB',
                    'storage' => '1TB SSD',
                    'display' => '16.2-inch Liquid Retina XDR'
                ]),
                'variations' => json_encode([
                    'memory' => ['32GB', '64GB', '128GB'],
                    'storage' => ['1TB', '2TB', '4TB', '8TB'],
                    'color' => ['Space Gray', 'Silver']
                ]),
                'seo_meta' => json_encode([
                    'meta_title' => 'MacBook Pro 16-inch M3 Max - Professional Laptop',
                    'meta_description' => 'MacBook Pro with M3 Max chip and 16-inch display',
                    'keywords' => 'MacBook Pro, M3 Max, Apple, laptop, professional'
                ]),
                'shipping_info' => json_encode([
                    'weight' => '2.16 kg',
                    'dimensions' => '35.57 x 24.81 x 1.68 cm',
                    'shipping_class' => 'express'
                ]),
                'views_count' => 890,
                'sales_count' => 12,
                'rating' => 4.9,
                'reviews_count' => 8,
                'published_at' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Fashion Products
            [
                'tenant_id' => 1,
                'vendor_id' => 2, // Fashion Forward
                'category_id' => 5, // Women's Clothing
                'name' => 'Designer Evening Dress',
                'slug' => 'designer-evening-dress',
                'description' => 'Elegant evening dress perfect for special occasions. Made with premium silk fabric and intricate beading.',
                'short_description' => 'Elegant silk evening dress',
                'sku' => 'EVEDRESS-SILK-M',
                'barcode' => '1234567890125',
                'type' => 'physical',
                'price' => 299.99,
                'compare_price' => 399.99,
                'cost_price' => 150.00,
                'quantity' => 15,
                'min_quantity' => 1,
                'max_quantity' => 3,
                'track_quantity' => true,
                'allow_backorder' => false,
                'weight' => 0.5,
                'length' => 30,
                'width' => 20,
                'height' => 2,
                'status' => 'active',
                'is_featured' => true,
                'is_digital' => false,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/e74c3c/ffffff?text=Evening+Dress',
                    'https://via.placeholder.com/800x600/f39c12/ffffff?text=Evening+Dress+2'
                ]),
                'attributes' => json_encode([
                    'material' => 'Silk',
                    'color' => 'Black',
                    'style' => 'A-line',
                    'occasion' => 'Evening'
                ]),
                'variations' => json_encode([
                    'size' => ['XS', 'S', 'M', 'L', 'XL'],
                    'color' => ['Black', 'Navy', 'Red', 'Gold']
                ]),
                'seo_meta' => json_encode([
                    'meta_title' => 'Designer Evening Dress - Elegant Silk Gown',
                    'meta_description' => 'Elegant evening dress perfect for special occasions',
                    'keywords' => 'evening dress, designer, silk, elegant, formal'
                ]),
                'shipping_info' => json_encode([
                    'weight' => '0.5 kg',
                    'dimensions' => '30 x 20 x 2 cm',
                    'shipping_class' => 'standard'
                ]),
                'views_count' => 567,
                'sales_count' => 8,
                'rating' => 4.6,
                'reviews_count' => 5,
                'published_at' => now()->subDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Sports Products
            [
                'tenant_id' => 1,
                'vendor_id' => 2, // Fashion Forward (using existing vendor)
                'category_id' => 10, // Sports & Outdoors
                'name' => 'Professional Tennis Racket',
                'slug' => 'professional-tennis-racket',
                'description' => 'High-performance tennis racket designed for professional players. Lightweight carbon fiber construction.',
                'short_description' => 'Professional carbon fiber tennis racket',
                'sku' => 'TENRACK-CF-100',
                'barcode' => '1234567890126',
                'type' => 'physical',
                'price' => 199.99,
                'compare_price' => 249.99,
                'cost_price' => 120.00,
                'quantity' => 30,
                'min_quantity' => 1,
                'max_quantity' => 5,
                'track_quantity' => true,
                'allow_backorder' => true,
                'weight' => 0.3,
                'length' => 68.5,
                'width' => 25.4,
                'height' => 2.5,
                'status' => 'active',
                'is_featured' => false,
                'is_digital' => false,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Tennis+Racket',
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Tennis+Racket+2'
                ]),
                'attributes' => json_encode([
                    'material' => 'Carbon Fiber',
                    'weight' => '300g',
                    'head_size' => '100 sq in',
                    'string_pattern' => '16x19'
                ]),
                'variations' => json_encode([
                    'weight' => ['280g', '300g', '320g'],
                    'grip_size' => ['4 1/8', '4 1/4', '4 3/8', '4 1/2']
                ]),
                'seo_meta' => json_encode([
                    'meta_title' => 'Professional Tennis Racket - Carbon Fiber',
                    'meta_description' => 'High-performance tennis racket for professionals',
                    'keywords' => 'tennis racket, professional, carbon fiber, sports'
                ]),
                'shipping_info' => json_encode([
                    'weight' => '0.3 kg',
                    'dimensions' => '68.5 x 25.4 x 2.5 cm',
                    'shipping_class' => 'standard'
                ]),
                'views_count' => 234,
                'sales_count' => 3,
                'rating' => 4.4,
                'reviews_count' => 2,
                'published_at' => now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('products')->insert($products);
    }
}