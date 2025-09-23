<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating auctions...');

        // Get existing data
        $tenant = DB::table('tenants')->first();
        $vendors = DB::table('vendors')->get();
        $categories = DB::table('categories')->get();
        $products = DB::table('products')->get();

        if (!$tenant || $vendors->isEmpty() || $categories->isEmpty() || $products->isEmpty()) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        $auctions = [
            [
                'title' => 'Vintage Rolex Submariner 1960s',
                'description' => 'Rare vintage Rolex Submariner from 1960s in excellent condition. Original dial, hands, and movement. Comes with original box and papers.',
                'starting_price' => 15000.00,
                'current_price' => 18500.00,
                'reserve_price' => 20000.00,
                'increment' => 250.00,
                'status' => 'active',
                'start_time' => now()->subDays(2),
                'end_time' => now()->addDays(5),
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'electronics')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Antique Persian Rug 1920s',
                'description' => 'Beautiful handwoven Persian rug from the 1920s. 8x10 feet, wool and silk blend. Excellent condition with minor wear consistent with age.',
                'starting_price' => 5000.00,
                'current_price' => 7200.00,
                'reserve_price' => 8000.00,
                'increment' => 100.00,
                'status' => 'active',
                'start_time' => now()->subDays(1),
                'end_time' => now()->addDays(4),
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'home-garden')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Signed Picasso Lithograph',
                'description' => 'Original signed lithograph by Pablo Picasso from 1960s. Certificate of authenticity included. Excellent condition.',
                'starting_price' => 8000.00,
                'current_price' => 9500.00,
                'reserve_price' => 12000.00,
                'increment' => 200.00,
                'status' => 'active',
                'start_time' => now(),
                'end_time' => now()->addDays(7),
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'home-garden')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Vintage Gibson Les Paul 1959',
                'description' => 'Rare 1959 Gibson Les Paul Standard in sunburst finish. All original hardware and electronics. Comes with original case.',
                'starting_price' => 25000.00,
                'current_price' => 32000.00,
                'reserve_price' => 40000.00,
                'increment' => 500.00,
                'status' => 'active',
                'start_time' => now()->subDays(3),
                'end_time' => now()->addDays(6),
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'electronics')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Diamond Engagement Ring 3ct',
                'description' => 'Beautiful 3-carat diamond engagement ring with platinum setting. GIA certified diamond with excellent cut, color, and clarity.',
                'starting_price' => 15000.00,
                'current_price' => 18000.00,
                'reserve_price' => 22000.00,
                'increment' => 300.00,
                'status' => 'active',
                'start_time' => now()->subHours(12),
                'end_time' => now()->addDays(3),
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'fashion')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Vintage Wine Collection 1961',
                'description' => 'Collection of 12 bottles of vintage wine from 1961, including Bordeaux and Burgundy. Properly stored in temperature-controlled cellar.',
                'starting_price' => 3000.00,
                'current_price' => 4200.00,
                'reserve_price' => 5000.00,
                'increment' => 100.00,
                'status' => 'active',
                'start_time' => now()->subDays(1),
                'end_time' => now()->addDays(2),
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'home-garden')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Classic Car 1967 Mustang',
                'description' => 'Restored 1967 Ford Mustang Fastback in original red color. Matching numbers engine and transmission. Recent restoration with new paint and interior.',
                'starting_price' => 35000.00,
                'current_price' => 42000.00,
                'reserve_price' => 50000.00,
                'increment' => 1000.00,
                'status' => 'active',
                'start_time' => now()->subDays(4),
                'end_time' => now()->addDays(8),
                'vendor_id' => $vendors->first()->id,
                'category_id' => $categories->where('slug', 'electronics')->first()->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Sports Memorabilia Collection',
                'description' => 'Extensive collection of sports memorabilia including signed jerseys, baseball cards, and championship rings. Over 100 items total.',
                'starting_price' => 2000.00,
                'current_price' => 2800.00,
                'reserve_price' => 3500.00,
                'increment' => 50.00,
                'status' => 'active',
                'start_time' => now()->subHours(6),
                'end_time' => now()->addDays(1),
                'vendor_id' => $vendors->count() > 1 ? $vendors[1]->id : $vendors->first()->id,
                'category_id' => $categories->where('slug', 'sports-outdoors')->first()->id ?? $categories->first()->id,
            ],
        ];

        foreach ($auctions as $auctionData) {
            DB::table('auctions')->insert([
                'tenant_id' => $tenant->id,
                'vendor_id' => $auctionData['vendor_id'],
                'product_id' => $products->random()->id, // Assign random product
                'title' => $auctionData['title'],
                'description' => $auctionData['description'],
                'type' => 'live',
                'starting_price' => $auctionData['starting_price'],
                'reserve_price' => $auctionData['reserve_price'],
                'buy_now_price' => $auctionData['current_price'] * 1.2, // Buy now price is 20% higher
                'current_bid' => $auctionData['current_price'],
                'bid_increment' => $auctionData['increment'],
                'bid_count' => rand(5, 25),
                'start_time' => $auctionData['start_time'],
                'end_time' => $auctionData['end_time'],
                'status' => $auctionData['status'],
                'is_featured' => rand(0, 1),
                'auto_extend' => true,
                'extend_minutes' => 5,
                'images' => json_encode(['/images/auctions/auction-placeholder.jpg']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created auction: {$auctionData['title']}");
        }

        $this->command->info('Auction seeding completed!');
    }
}