<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffiliateProgram;
use App\Models\Affiliate;
use App\Models\Referral;
use App\Models\AffiliateCommission;
use App\Models\User;

class AffiliateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create affiliate programs
        $programs = [
            [
                'tenant_id' => 1,
                'name' => 'General Affiliate Program',
                'slug' => 'general-affiliate-program',
                'description' => 'Our main affiliate program with competitive commission rates',
                'type' => 'general',
                'commission_rate' => 0.05, // 5%
                'fixed_commission' => 0.00,
                'commission_type' => 'percentage',
                'minimum_payout' => 50.00,
                'cookie_duration' => 30,
                'terms_conditions' => [
                    'minimum_sales' => 5,
                    'payment_schedule' => 'monthly',
                    'excluded_products' => [],
                    'marketing_guidelines' => 'Must comply with our marketing guidelines',
                ],
                'is_active' => true,
                'requires_approval' => true,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Electronics Affiliate Program',
                'slug' => 'electronics-affiliate-program',
                'description' => 'Specialized program for electronics and tech products',
                'type' => 'category_specific',
                'commission_rate' => 0.08, // 8%
                'fixed_commission' => 0.00,
                'commission_type' => 'percentage',
                'minimum_payout' => 25.00,
                'cookie_duration' => 45,
                'terms_conditions' => [
                    'minimum_sales' => 3,
                    'payment_schedule' => 'bi-weekly',
                    'excluded_products' => [],
                    'marketing_guidelines' => 'Tech-focused marketing preferred',
                ],
                'is_active' => true,
                'requires_approval' => true,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Fashion Affiliate Program',
                'slug' => 'fashion-affiliate-program',
                'description' => 'Exclusive program for fashion and lifestyle products',
                'type' => 'category_specific',
                'commission_rate' => 0.10, // 10%
                'fixed_commission' => 0.00,
                'commission_type' => 'percentage',
                'minimum_payout' => 30.00,
                'cookie_duration' => 30,
                'terms_conditions' => [
                    'minimum_sales' => 2,
                    'payment_schedule' => 'monthly',
                    'excluded_products' => [],
                    'marketing_guidelines' => 'Fashion and lifestyle content preferred',
                ],
                'is_active' => true,
                'requires_approval' => true,
            ],
            [
                'tenant_id' => 1,
                'name' => 'Premium Affiliate Program',
                'slug' => 'premium-affiliate-program',
                'description' => 'High-tier program for established affiliates',
                'type' => 'general',
                'commission_rate' => 0.12, // 12%
                'fixed_commission' => 5.00,
                'commission_type' => 'hybrid',
                'minimum_payout' => 100.00,
                'cookie_duration' => 60,
                'terms_conditions' => [
                    'minimum_sales' => 20,
                    'payment_schedule' => 'weekly',
                    'excluded_products' => [],
                    'marketing_guidelines' => 'Professional marketing standards required',
                ],
                'is_active' => true,
                'requires_approval' => true,
            ],
        ];

        foreach ($programs as $programData) {
            AffiliateProgram::create($programData);
        }

        // Create affiliates
        $users = User::where('tenant_id', 1)->take(5)->get();
        $programs = AffiliateProgram::all();

        foreach ($users as $index => $user) {
            if ($index < count($programs)) {
                $program = $programs[$index];
                
                $affiliate = Affiliate::create([
                    'tenant_id' => $user->tenant_id,
                    'user_id' => $user->id,
                    'affiliate_program_id' => $program->id,
                    'affiliate_code' => Affiliate::generateAffiliateCode($user->id),
                    'status' => 'approved',
                    'application_data' => [
                        'full_name' => $user->name,
                        'email' => $user->email,
                        'phone' => '+1-555-012' . $index,
                        'website' => 'https://affiliate' . $index . '.com',
                        'social_media' => [
                            'instagram' => '@affiliate' . $index,
                            'youtube' => 'Affiliate' . $index . 'Channel',
                        ],
                        'marketing_methods' => ['social_media', 'email_marketing', 'content_creation'],
                        'experience' => 'Experienced affiliate marketer with ' . ($index + 1) . ' years in the industry',
                    ],
                    'total_earnings' => rand(100, 1000),
                    'total_paid' => rand(50, 500),
                    'pending_earnings' => rand(20, 200),
                    'total_referrals' => rand(10, 50),
                    'total_sales' => rand(5, 25),
                    'payment_methods' => [
                        'paypal' => [
                            'email' => 'affiliate' . $index . '@example.com',
                        ],
                        'bank_transfer' => [
                            'bank_name' => 'Example Bank',
                            'account_number' => '****' . rand(1000, 9999),
                            'routing_number' => '****' . rand(1000, 9999),
                        ],
                    ],
                    'settings' => [
                        'email_notifications' => true,
                        'sms_notifications' => false,
                        'marketing_materials' => true,
                    ],
                    'approved_at' => now()->subDays(rand(30, 365)),
                    'last_activity_at' => now()->subDays(rand(1, 7)),
                ]);

                // Create some referrals for each affiliate
                for ($i = 0; $i < rand(3, 8); $i++) {
                    $referral = Referral::create([
                        'tenant_id' => $affiliate->tenant_id,
                        'affiliate_id' => $affiliate->id,
                        'referral_code' => $affiliate->affiliate_code,
                        'referral_type' => ['signup', 'purchase', 'subscription'][rand(0, 2)],
                        'status' => ['pending', 'converted', 'expired'][rand(0, 2)],
                        'ip_address' => '192.168.1.' . rand(100, 200),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        'metadata' => [
                            'source' => ['facebook', 'instagram', 'google', 'email'][rand(0, 3)],
                            'campaign' => 'summer_sale_' . rand(2023, 2024),
                            'device' => ['desktop', 'mobile', 'tablet'][rand(0, 2)],
                        ],
                        'converted_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                        'expires_at' => now()->addDays($program->cookie_duration),
                    ]);

                    // Create commissions for converted referrals
                    if ($referral->status === 'converted') {
                        $orderAmount = rand(50, 500);
                        $commissionAmount = $program->calculateCommission($orderAmount);

                        AffiliateCommission::create([
                            'tenant_id' => $affiliate->tenant_id,
                            'affiliate_id' => $affiliate->id,
                            'referral_id' => $referral->id,
                            'commission_type' => 'order',
                            'order_amount' => $orderAmount,
                            'commission_rate' => $program->commission_rate,
                            'commission_amount' => $commissionAmount,
                            'status' => ['pending', 'approved', 'paid'][rand(0, 2)],
                            'notes' => 'Commission for order #' . rand(1000, 9999),
                            'approved_at' => rand(0, 1) ? now()->subDays(rand(1, 15)) : null,
                            'paid_at' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                        ]);
                    }
                }
            }
        }

        // Create some additional referrals without affiliates (for testing)
        for ($i = 0; $i < 10; $i++) {
            $randomAffiliate = Affiliate::inRandomOrder()->first();
            
            Referral::create([
                'tenant_id' => $randomAffiliate->tenant_id,
                'affiliate_id' => $randomAffiliate->id,
                'referral_code' => $randomAffiliate->affiliate_code,
                'referral_type' => ['signup', 'purchase'][rand(0, 1)],
                'status' => 'pending',
                'ip_address' => '192.168.1.' . rand(200, 255),
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X)',
                'metadata' => [
                    'source' => ['facebook', 'instagram', 'tiktok'][rand(0, 2)],
                    'campaign' => 'holiday_sale_2024',
                    'device' => 'mobile',
                ],
                'expires_at' => now()->addDays(30),
            ]);
        }
    }
}
