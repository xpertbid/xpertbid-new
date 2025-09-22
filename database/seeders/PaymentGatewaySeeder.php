<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

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
                'name' => 'PayPal',
                'code' => 'paypal',
                'type' => 'online',
                'description' => 'PayPal payment processing',
                'logo_url' => 'https://example.com/logos/paypal.png',
                'settings' => [
                    'client_id' => 'test_client_id',
                    'client_secret' => 'test_client_secret',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/paypal',
                    'sandbox_mode' => true,
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'supported_countries' => ['US', 'GB', 'DE', 'FR', 'IT', 'ES', 'CA', 'AU'],
                'transaction_fee' => 0.029, // 2.9%
                'fixed_fee' => 0.30,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 1,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Stripe',
                'code' => 'stripe',
                'type' => 'online',
                'description' => 'Stripe payment processing',
                'logo_url' => 'https://example.com/logos/stripe.png',
                'settings' => [
                    'publishable_key' => 'pk_test_...',
                    'secret_key' => 'sk_test_...',
                    'webhook_secret' => 'whsec_...',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/stripe',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF'],
                'supported_countries' => ['US', 'GB', 'DE', 'FR', 'IT', 'ES', 'CA', 'AU', 'JP', 'CH'],
                'transaction_fee' => 0.029, // 2.9%
                'fixed_fee' => 0.30,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 2,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Razorpay',
                'code' => 'razorpay',
                'type' => 'online',
                'description' => 'Razorpay payment processing for India',
                'logo_url' => 'https://example.com/logos/razorpay.png',
                'settings' => [
                    'key_id' => 'rzp_test_...',
                    'key_secret' => 'test_secret',
                    'webhook_secret' => 'test_webhook_secret',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/razorpay',
                ],
                'supported_currencies' => ['INR'],
                'supported_countries' => ['IN'],
                'transaction_fee' => 0.02, // 2%
                'fixed_fee' => 0.00,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 3,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Square',
                'code' => 'square',
                'type' => 'online',
                'description' => 'Square payment processing',
                'logo_url' => 'https://example.com/logos/square.png',
                'settings' => [
                    'application_id' => 'test_app_id',
                    'access_token' => 'test_access_token',
                    'webhook_url' => 'https://api.xpertbid.com/webhooks/square',
                ],
                'supported_currencies' => ['USD', 'CAD', 'GBP', 'AUD'],
                'supported_countries' => ['US', 'CA', 'GB', 'AU'],
                'transaction_fee' => 0.026, // 2.6%
                'fixed_fee' => 0.10,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 4,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Bank Transfer',
                'code' => 'bank_transfer',
                'type' => 'offline',
                'description' => 'Direct bank transfer payment',
                'logo_url' => 'https://example.com/logos/bank.png',
                'settings' => [
                    'bank_name' => 'Example Bank',
                    'account_number' => '1234567890',
                    'routing_number' => '987654321',
                    'instructions' => 'Please include order number in transfer memo',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP'],
                'supported_countries' => ['US', 'GB', 'DE', 'FR'],
                'transaction_fee' => 0.00,
                'fixed_fee' => 0.00,
                'is_active' => true,
                'is_test_mode' => false,
                'sort_order' => 5,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'type' => 'offline',
                'description' => 'Cash on delivery payment',
                'logo_url' => 'https://example.com/logos/cod.png',
                'settings' => [
                    'handling_fee' => 5.00,
                    'max_amount' => 500.00,
                    'available_zones' => ['US', 'IN'],
                ],
                'supported_currencies' => ['USD', 'INR'],
                'supported_countries' => ['US', 'IN'],
                'transaction_fee' => 0.00,
                'fixed_fee' => 5.00,
                'is_active' => true,
                'is_test_mode' => false,
                'sort_order' => 6,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Apple Pay',
                'code' => 'apple_pay',
                'type' => 'online',
                'description' => 'Apple Pay mobile payment',
                'logo_url' => 'https://example.com/logos/apple_pay.png',
                'settings' => [
                    'merchant_id' => 'merchant.com.xpertbid',
                    'certificate_path' => '/path/to/certificate.pem',
                    'private_key_path' => '/path/to/private_key.pem',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'supported_countries' => ['US', 'GB', 'DE', 'FR', 'IT', 'ES', 'CA', 'AU'],
                'transaction_fee' => 0.029, // 2.9%
                'fixed_fee' => 0.30,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 7,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Google Pay',
                'code' => 'google_pay',
                'type' => 'online',
                'description' => 'Google Pay mobile payment',
                'logo_url' => 'https://example.com/logos/google_pay.png',
                'settings' => [
                    'merchant_id' => '12345678901234567890',
                    'merchant_name' => 'XpertBid',
                    'environment' => 'test',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'supported_countries' => ['US', 'GB', 'DE', 'FR', 'IT', 'ES', 'CA', 'AU'],
                'transaction_fee' => 0.029, // 2.9%
                'fixed_fee' => 0.30,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($gateways as $gatewayData) {
            PaymentGateway::create($gatewayData);
        }
    }
}
