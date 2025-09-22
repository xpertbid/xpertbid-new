<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carrier;

class DummyCarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyCarriers = [
            [
                'tenant_id' => 1,
                'name' => 'Amazon Logistics',
                'code' => 'amazon_logistics',
                'logo_url' => 'https://example.com/logos/amazon.png',
                'description' => 'Amazon\'s own delivery service for fast and reliable shipping',
                'api_settings' => [
                    'api_key' => 'amazon_test_key',
                    'api_secret' => 'amazon_test_secret',
                    'account_number' => 'AMZ123456',
                ],
                'supported_countries' => ['US', 'CA', 'GB', 'DE', 'FR', 'IT', 'ES'],
                'supported_services' => ['standard', 'express', 'same_day', 'prime'],
                'is_active' => true,
                'is_integrated' => true,
                'base_rate' => 8.99,
                'rate_calculation' => [
                    'weight_rate' => 1.50,
                    'distance_rate' => 0.10,
                ],
                'sort_order' => 4,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Canada Post',
                'code' => 'canada_post',
                'logo_url' => 'https://example.com/logos/canada-post.png',
                'description' => 'Canada\'s national postal service for domestic and international shipping',
                'api_settings' => [
                    'api_key' => 'canada_post_key',
                    'api_secret' => 'canada_post_secret',
                    'account_number' => 'CP789012',
                ],
                'supported_countries' => ['CA', 'US'],
                'supported_services' => ['regular', 'expedited', 'priority', 'xpresspost'],
                'is_active' => true,
                'is_integrated' => false,
                'base_rate' => 7.50,
                'rate_calculation' => [
                    'weight_rate' => 1.25,
                    'distance_rate' => 0.08,
                ],
                'sort_order' => 5,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Royal Mail',
                'code' => 'royal_mail',
                'logo_url' => 'https://example.com/logos/royal-mail.png',
                'description' => 'UK\'s leading postal service with international delivery options',
                'api_settings' => [
                    'api_key' => 'royal_mail_key',
                    'api_secret' => 'royal_mail_secret',
                    'account_number' => 'RM345678',
                ],
                'supported_countries' => ['GB', 'IE', 'FR', 'DE', 'IT', 'ES', 'NL'],
                'supported_services' => ['standard', 'first_class', 'special_delivery', 'international'],
                'is_active' => false,
                'is_integrated' => true,
                'base_rate' => 6.99,
                'rate_calculation' => [
                    'weight_rate' => 1.75,
                    'distance_rate' => 0.12,
                ],
                'sort_order' => 6,
            ],
        ];

        foreach ($dummyCarriers as $carrierData) {
            Carrier::create($carrierData);
        }
    }
}
