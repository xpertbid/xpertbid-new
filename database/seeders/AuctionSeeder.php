<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auctions = [
            [
                'tenant_id' => 1,
                'vendor_id' => 1, // TechGear Solutions
                'product_id' => 1, // iPhone 15 Pro Max
                'title' => 'iPhone 15 Pro Max 256GB - Limited Edition',
                'description' => 'Brand new iPhone 15 Pro Max in Titanium finish. This is a limited edition model with exclusive packaging.',
                'type' => 'english',
                'starting_price' => 1000.00,
                'reserve_price' => 1200.00,
                'buy_now_price' => 1500.00,
                'current_bid' => 1100.00,
                'bid_increment' => 25.00,
                'bid_count' => 8,
                'start_time' => now()->subDays(2),
                'end_time' => now()->addDays(1),
                'status' => 'active',
                'is_featured' => true,
                'auto_extend' => true,
                'extend_minutes' => 5,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2c3e50/ffffff?text=iPhone+15+Pro+Max+Auction',
                    'https://via.placeholder.com/800x600/3498db/ffffff?text=iPhone+15+Pro+Max+Box'
                ]),
                'terms_conditions' => json_encode([
                    'Payment within 48 hours',
                    'Shipping included in final price',
                    'No returns on auction items',
                    'Warranty as per manufacturer'
                ]),
                'winner_id' => null,
                'won_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'vendor_id' => 2, // Fashion Forward
                'product_id' => 3, // Designer Evening Dress
                'title' => 'Designer Evening Dress - Vintage Collection',
                'description' => 'Exclusive vintage designer evening dress from our premium collection. Perfect for special occasions.',
                'type' => 'reserve',
                'starting_price' => 200.00,
                'reserve_price' => 350.00,
                'buy_now_price' => null,
                'current_bid' => 250.00,
                'bid_increment' => 10.00,
                'bid_count' => 5,
                'start_time' => now()->subHours(12),
                'end_time' => now()->addHours(12),
                'status' => 'active',
                'is_featured' => false,
                'auto_extend' => false,
                'extend_minutes' => 0,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/e74c3c/ffffff?text=Designer+Dress+Auction',
                    'https://via.placeholder.com/800x600/f39c12/ffffff?text=Designer+Dress+Detail'
                ]),
                'terms_conditions' => json_encode([
                    'Payment within 24 hours',
                    'Free shipping on orders over $200',
                    'Size exchange available',
                    'Dry clean only'
                ]),
                'winner_id' => null,
                'won_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'vendor_id' => 1, // TechGear Solutions
                'product_id' => 2, // MacBook Pro 16-inch
                'title' => 'MacBook Pro 16-inch M3 Max - Open Box',
                'description' => 'Open box MacBook Pro 16-inch with M3 Max chip. Never used, only opened for inspection.',
                'type' => 'buy_now',
                'starting_price' => 3000.00,
                'reserve_price' => null,
                'buy_now_price' => 3200.00,
                'current_bid' => 3000.00,
                'bid_increment' => 50.00,
                'bid_count' => 3,
                'start_time' => now()->subDays(1),
                'end_time' => now()->addDays(2),
                'status' => 'active',
                'is_featured' => true,
                'auto_extend' => true,
                'extend_minutes' => 10,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2c3e50/ffffff?text=MacBook+Pro+Auction',
                    'https://via.placeholder.com/800x600/3498db/ffffff?text=MacBook+Pro+Box'
                ]),
                'terms_conditions' => json_encode([
                    'Payment within 72 hours',
                    'Express shipping available',
                    'Full manufacturer warranty',
                    'Technical support included'
                ]),
                'winner_id' => null,
                'won_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'vendor_id' => 2, // Fashion Forward
                'product_id' => 4, // Professional Tennis Racket
                'title' => 'Professional Tennis Racket - Signed by Pro Player',
                'description' => 'Professional tennis racket signed by a top-ranked player. Includes certificate of authenticity.',
                'type' => 'english',
                'starting_price' => 150.00,
                'reserve_price' => 200.00,
                'buy_now_price' => 300.00,
                'current_bid' => 175.00,
                'bid_increment' => 5.00,
                'bid_count' => 12,
                'start_time' => now()->subHours(6),
                'end_time' => now()->addHours(18),
                'status' => 'active',
                'is_featured' => false,
                'auto_extend' => true,
                'extend_minutes' => 3,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/27ae60/ffffff?text=Tennis+Racket+Auction',
                    'https://via.placeholder.com/800x600/2ecc71/ffffff?text=Tennis+Racket+Signature'
                ]),
                'terms_conditions' => json_encode([
                    'Payment within 48 hours',
                    'Certificate of authenticity included',
                    'No returns on signed items',
                    'Standard shipping included'
                ]),
                'winner_id' => null,
                'won_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'vendor_id' => 2, // Fashion Forward
                'product_id' => 3, // Designer Evening Dress
                'title' => 'Designer Handbag - Limited Edition',
                'description' => 'Exclusive limited edition designer handbag from a luxury brand. Only 100 pieces made worldwide.',
                'type' => 'private',
                'starting_price' => 500.00,
                'reserve_price' => 800.00,
                'buy_now_price' => null,
                'current_bid' => 600.00,
                'bid_increment' => 25.00,
                'bid_count' => 4,
                'start_time' => now()->subDays(3),
                'end_time' => now()->addDays(1),
                'status' => 'active',
                'is_featured' => true,
                'auto_extend' => false,
                'extend_minutes' => 0,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/e74c3c/ffffff?text=Designer+Handbag+Auction',
                    'https://via.placeholder.com/800x600/f39c12/ffffff?text=Designer+Handbag+Detail'
                ]),
                'terms_conditions' => json_encode([
                    'Private auction - invitation only',
                    'Payment within 24 hours',
                    'Certificate of authenticity',
                    'Insurance included in shipping'
                ]),
                'winner_id' => null,
                'won_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Ended Auction
            [
                'tenant_id' => 1,
                'vendor_id' => 1, // TechGear Solutions
                'product_id' => 1, // iPhone 15 Pro Max
                'title' => 'iPhone 14 Pro Max 128GB - Ended Auction',
                'description' => 'Previous generation iPhone 14 Pro Max in excellent condition.',
                'type' => 'english',
                'starting_price' => 800.00,
                'reserve_price' => 900.00,
                'buy_now_price' => null,
                'current_bid' => 950.00,
                'bid_increment' => 20.00,
                'bid_count' => 15,
                'start_time' => now()->subDays(5),
                'end_time' => now()->subDays(1),
                'status' => 'ended',
                'is_featured' => false,
                'auto_extend' => true,
                'extend_minutes' => 5,
                'images' => json_encode([
                    'https://via.placeholder.com/800x600/2c3e50/ffffff?text=iPhone+14+Pro+Max',
                    'https://via.placeholder.com/800x600/3498db/ffffff?text=iPhone+14+Pro+Max+Box'
                ]),
                'terms_conditions' => json_encode([
                    'Payment within 48 hours',
                    'Shipping included',
                    'No returns',
                    'Warranty as per manufacturer'
                ]),
                'winner_id' => 2, // John Smith
                'won_at' => now()->subDays(1),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1),
            ]
        ];

        DB::table('auctions')->insert($auctions);
    }
}