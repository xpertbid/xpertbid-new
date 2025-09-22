<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            $this->call([
                TenantSeeder::class,
                UserSeeder::class,
                VendorSeeder::class,
                BrandSeeder::class,
                TagSeeder::class,
                CategorySeeder::class,
                ProductSeeder::class,
                PropertySeeder::class,
                VehicleSeeder::class,
                AuctionSeeder::class,
                LanguageSeeder::class,
                CurrencySeeder::class,
                ProductAttributeSeeder::class,
                UnitSeeder::class,
                ShippingSeeder::class,
                TaxSeeder::class,
                PaymentGatewaySeeder::class,
                AffiliateSeeder::class,
            ]);
    }
}
