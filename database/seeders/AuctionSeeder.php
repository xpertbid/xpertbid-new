<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuctionSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating auctions...');
        
        // Get the first tenant
        $tenant = DB::table('tenants')->first();
        if (!$tenant) {
            $this->command->error('No tenant found. Please create a tenant first.');
            return;
        }
        
        // Get the first vendor
        $vendor = DB::table('vendors')->first();
        if (!$vendor) {
            $this->command->error('No vendor found. Please create a vendor first.');
            return;
        }
        
        // Get some products to create auctions for
        $products = DB::table('products')->limit(10)->get();
        if ($products->isEmpty()) {
            $this->command->error('No products found. Please create products first.');
            return;
        }
        
        $auctions = [
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendor->id,
                'product_id' => $products[0]->id ?? 1,
                'title' => 'Vintage Rolex Submariner Watch',
                'description' => 'Authentic vintage Rolex Submariner in excellent condition. Perfect for collectors.',
                'type' => 'english',
                'starting_price' => 5000.00,
                'reserve_price' => 8000.00,
                'buy_now_price' => 12000.00,
                'current_bid' => 5500.00,
                'bid_increment' => 100.00,
                'bid_count' => 15,
                'start_time' => Carbon::now()->subDays(2)->toDateTimeString(),
                'end_time' => Carbon::now()->addDays(5)->toDateTimeString(),
                'status' => 'active',
                'is_featured' => true,
                'auto_extend' => true,
                'extend_minutes' => 5,
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=600&fit=crop'
                ]),
                'terms_conditions' => json_encode(['terms' => 'All bids are binding. Winner must pay within 48 hours.']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendor->id,
                'product_id' => $products[1]->id ?? 2,
                'title' => 'Antique Persian Rug',
                'description' => 'Beautiful hand-woven Persian rug from the 1920s. Excellent condition.',
                'type' => 'english',
                'starting_price' => 800.00,
                'reserve_price' => 1500.00,
                'buy_now_price' => 2500.00,
                'current_bid' => 1200.00,
                'bid_increment' => 50.00,
                'bid_count' => 8,
                'start_time' => Carbon::now()->subDays(1)->toDateTimeString(),
                'end_time' => Carbon::now()->addDays(3)->toDateTimeString(),
                'status' => 'active',
                'is_featured' => false,
                'auto_extend' => false,
                'extend_minutes' => 0,
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop'
                ]),
                'terms_conditions' => json_encode(['terms' => 'Rug will be professionally cleaned before shipping.']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendor->id,
                'product_id' => $products[2]->id ?? 3,
                'title' => 'Classic Gibson Les Paul Guitar',
                'description' => '1959 Gibson Les Paul reissue in cherry sunburst. Perfect for collectors and players.',
                'type' => 'english',
                'starting_price' => 2000.00,
                'reserve_price' => 3500.00,
                'buy_now_price' => 4500.00,
                'current_bid' => 2800.00,
                'bid_increment' => 100.00,
                'bid_count' => 12,
                'start_time' => Carbon::now()->subHours(12)->toDateTimeString(),
                'end_time' => Carbon::now()->addDays(2)->toDateTimeString(),
                'status' => 'active',
                'is_featured' => true,
                'auto_extend' => true,
                'extend_minutes' => 3,
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?w=800&h=600&fit=crop'
                ]),
                'terms_conditions' => json_encode(['terms' => 'Guitar comes with original case and documentation.']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendor->id,
                'product_id' => $products[3]->id ?? 4,
                'title' => 'Vintage Wine Collection',
                'description' => 'Collection of 12 vintage wines from the 1990s. Perfect for wine enthusiasts.',
                'type' => 'english',
                'starting_price' => 300.00,
                'reserve_price' => 600.00,
                'buy_now_price' => 900.00,
                'current_bid' => 450.00,
                'bid_increment' => 25.00,
                'bid_count' => 6,
                'start_time' => Carbon::now()->subHours(6)->toDateTimeString(),
                'end_time' => Carbon::now()->addHours(18)->toDateTimeString(),
                'status' => 'active',
                'is_featured' => false,
                'auto_extend' => false,
                'extend_minutes' => 0,
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?w=800&h=600&fit=crop'
                ]),
                'terms_conditions' => json_encode(['terms' => 'Wines must be stored properly. Buyer responsible for proper storage.']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'vendor_id' => $vendor->id,
                'product_id' => $products[4]->id ?? 5,
                'title' => 'Signed Baseball Memorabilia',
                'description' => 'Authentic signed baseball from the 1998 World Series. Certificate of authenticity included.',
                'type' => 'english',
                'starting_price' => 150.00,
                'reserve_price' => 300.00,
                'buy_now_price' => 500.00,
                'current_bid' => 200.00,
                'bid_increment' => 25.00,
                'bid_count' => 4,
                'start_time' => Carbon::now()->subHours(3)->toDateTimeString(),
                'end_time' => Carbon::now()->addHours(21)->toDateTimeString(),
                'status' => 'active',
                'is_featured' => false,
                'auto_extend' => false,
                'extend_minutes' => 0,
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1566577739112-5180d4bf9390?w=800&h=600&fit=crop'
                ]),
                'terms_conditions' => json_encode(['terms' => 'Certificate of authenticity included with purchase.']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        foreach ($auctions as $auction) {
            DB::table('auctions')->insert($auction);
        }
        
        $this->command->info('Created ' . count($auctions) . ' auctions.');
    }
}