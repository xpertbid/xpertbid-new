<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'tenant_id' => 1,
                'name' => 'Stripe',
                'code' => 'stripe',
                'type' => 'online',
                'description' => 'Accept credit cards, debit cards, and digital wallets',
                'logo_url' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => true,
                'transaction_fee' => 0.029,
                'fixed_fee' => 0.30,
                'supported_currencies' => json_encode(['USD', 'EUR', 'GBP', 'CAD', 'AUD']),
                'supported_countries' => json_encode(['US', 'CA', 'GB', 'AU', 'DE', 'FR']),
                'sort_order' => 1,
                'settings' => json_encode([
                    'publishable_key' => 'pk_test_...',
                    'secret_key' => 'sk_test_...',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/stripe',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'PayPal',
                'code' => 'paypal',
                'type' => 'online',
                'description' => 'Accept PayPal payments and credit cards',
                'logo_url' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => true,
                'transaction_fee' => 0.034,
                'fixed_fee' => 0.35,
                'supported_currencies' => json_encode(['USD', 'EUR', 'GBP', 'CAD', 'AUD']),
                'supported_countries' => json_encode(['US', 'CA', 'GB', 'AU', 'DE', 'FR']),
                'sort_order' => 2,
                'settings' => json_encode([
                    'client_id' => 'test_client_id',
                    'client_secret' => 'test_client_secret',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/paypal',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Razorpay',
                'code' => 'razorpay',
                'type' => 'online',
                'description' => 'Accept UPI, cards, wallets, and net banking',
                'logo_url' => 'https://images.unsplash.com/photo-1556742111-a301076d9d18?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => true,
                'transaction_fee' => 0.02,
                'fixed_fee' => 2.00,
                'supported_currencies' => json_encode(['INR', 'USD', 'EUR']),
                'supported_countries' => json_encode(['IN', 'US', 'GB']),
                'sort_order' => 3,
                'settings' => json_encode([
                    'key_id' => 'test_key_id',
                    'key_secret' => 'test_key_secret',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/razorpay',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'type' => 'offline',
                'description' => 'Pay when your order is delivered',
                'logo_url' => 'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?w=100&h=60&fit=crop',
                'is_active' => true,
                'is_test_mode' => false,
                'transaction_fee' => 0,
                'fixed_fee' => 0,
                'supported_currencies' => json_encode(['USD', 'EUR', 'GBP', 'INR']),
                'supported_countries' => json_encode(['US', 'CA', 'GB', 'IN']),
                'sort_order' => 4,
                'settings' => json_encode([
                    'instructions' => 'Payment will be collected upon delivery',
                    'delivery_fee' => 5.00,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_gateways')->insert($gateways);
    }
}