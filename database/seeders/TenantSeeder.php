<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'XpertBid Main',
                'domain' => 'xpertbid.com',
                'subdomain' => 'main',
                'custom_domain' => null,
                'database_name' => 'xpertbid_main',
                'status' => 'active',
                'subscription_plan' => 'enterprise',
                'settings' => json_encode([
                    'timezone' => 'UTC',
                    'date_format' => 'Y-m-d',
                    'currency' => 'USD',
                    'language' => 'en',
                    'features' => ['auctions', 'properties', 'vehicles', 'multi_vendor']
                ]),
                'limits' => json_encode([
                    'vendors' => 1000,
                    'products' => 50000,
                    'storage' => 10000,
                    'bandwidth' => 100000
                ]),
                'monthly_fee' => 299.99,
                'vendor_limit' => 1000,
                'product_limit' => 50000,
                'storage_limit_mb' => 10000,
                'bandwidth_limit_mb' => 100000,
                'white_label' => false,
                'logo_url' => 'https://xpertbid.com/logo.png',
                'primary_color' => '#2c3e50',
                'secondary_color' => '#3498db',
                'trial_ends_at' => null,
                'subscription_ends_at' => now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TechMart Marketplace',
                'domain' => 'techmart.xpertbid.com',
                'subdomain' => 'techmart',
                'custom_domain' => 'techmart.com',
                'database_name' => 'techmart_marketplace',
                'status' => 'active',
                'subscription_plan' => 'premium',
                'settings' => json_encode([
                    'timezone' => 'America/New_York',
                    'date_format' => 'm/d/Y',
                    'currency' => 'USD',
                    'language' => 'en',
                    'features' => ['auctions', 'multi_vendor']
                ]),
                'limits' => json_encode([
                    'vendors' => 500,
                    'products' => 25000,
                    'storage' => 5000,
                    'bandwidth' => 50000
                ]),
                'monthly_fee' => 199.99,
                'vendor_limit' => 500,
                'product_limit' => 25000,
                'storage_limit_mb' => 5000,
                'bandwidth_limit_mb' => 50000,
                'white_label' => true,
                'logo_url' => 'https://techmart.com/logo.png',
                'primary_color' => '#e74c3c',
                'secondary_color' => '#f39c12',
                'trial_ends_at' => null,
                'subscription_ends_at' => now()->addMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'RealEstate Pro',
                'domain' => 'realestate.xpertbid.com',
                'subdomain' => 'realestate',
                'custom_domain' => 'realestatepro.com',
                'database_name' => 'realestate_pro',
                'status' => 'active',
                'subscription_plan' => 'premium',
                'settings' => json_encode([
                    'timezone' => 'America/Los_Angeles',
                    'date_format' => 'm/d/Y',
                    'currency' => 'USD',
                    'language' => 'en',
                    'features' => ['properties', 'auctions']
                ]),
                'limits' => json_encode([
                    'vendors' => 200,
                    'products' => 10000,
                    'storage' => 2000,
                    'bandwidth' => 20000
                ]),
                'monthly_fee' => 149.99,
                'vendor_limit' => 200,
                'product_limit' => 10000,
                'storage_limit_mb' => 2000,
                'bandwidth_limit_mb' => 20000,
                'white_label' => true,
                'logo_url' => 'https://realestatepro.com/logo.png',
                'primary_color' => '#27ae60',
                'secondary_color' => '#2ecc71',
                'trial_ends_at' => null,
                'subscription_ends_at' => now()->addMonths(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AutoTrade Hub',
                'domain' => 'autotrade.xpertbid.com',
                'subdomain' => 'autotrade',
                'custom_domain' => 'autotradehub.com',
                'database_name' => 'autotrade_hub',
                'status' => 'active',
                'subscription_plan' => 'basic',
                'settings' => json_encode([
                    'timezone' => 'America/Chicago',
                    'date_format' => 'm/d/Y',
                    'currency' => 'USD',
                    'language' => 'en',
                    'features' => ['vehicles', 'auctions']
                ]),
                'limits' => json_encode([
                    'vendors' => 100,
                    'products' => 5000,
                    'storage' => 1000,
                    'bandwidth' => 10000
                ]),
                'monthly_fee' => 99.99,
                'vendor_limit' => 100,
                'product_limit' => 5000,
                'storage_limit_mb' => 1000,
                'bandwidth_limit_mb' => 10000,
                'white_label' => false,
                'logo_url' => 'https://autotradehub.com/logo.png',
                'primary_color' => '#8e44ad',
                'secondary_color' => '#9b59b6',
                'trial_ends_at' => now()->addDays(14),
                'subscription_ends_at' => now()->addMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Global Marketplace',
                'domain' => 'global.xpertbid.com',
                'subdomain' => 'global',
                'custom_domain' => null,
                'database_name' => 'global_marketplace',
                'status' => 'pending',
                'subscription_plan' => 'basic',
                'settings' => json_encode([
                    'timezone' => 'UTC',
                    'date_format' => 'Y-m-d',
                    'currency' => 'EUR',
                    'language' => 'en',
                    'features' => ['multi_vendor']
                ]),
                'limits' => json_encode([
                    'vendors' => 50,
                    'products' => 1000,
                    'storage' => 500,
                    'bandwidth' => 5000
                ]),
                'monthly_fee' => 49.99,
                'vendor_limit' => 50,
                'product_limit' => 1000,
                'storage_limit_mb' => 500,
                'bandwidth_limit_mb' => 5000,
                'white_label' => false,
                'logo_url' => null,
                'primary_color' => '#34495e',
                'secondary_color' => '#7f8c8d',
                'trial_ends_at' => now()->addDays(30),
                'subscription_ends_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('tenants')->insert($tenants);
    }
}
