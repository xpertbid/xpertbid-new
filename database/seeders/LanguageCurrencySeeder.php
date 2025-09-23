<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating languages and currencies...');

        // Languages
        $languages = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_active' => true],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'is_active' => true],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'is_active' => true],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'is_active' => true],
            ['code' => 'it', 'name' => 'Italian', 'native_name' => 'Italiano', 'is_active' => true],
            ['code' => 'pt', 'name' => 'Portuguese', 'native_name' => 'Português', 'is_active' => true],
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский', 'is_active' => true],
            ['code' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語', 'is_active' => true],
            ['code' => 'ko', 'name' => 'Korean', 'native_name' => '한국어', 'is_active' => true],
            ['code' => 'zh', 'name' => 'Chinese', 'native_name' => '中文', 'is_active' => true],
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'is_active' => true],
            ['code' => 'hi', 'name' => 'Hindi', 'native_name' => 'हिन्दी', 'is_active' => true],
        ];

        // Get tenant
        $tenant = DB::table('tenants')->first();
        
        foreach ($languages as $lang) {
            $existing = DB::table('languages')->where('code', $lang['code'])->first();
            if (!$existing) {
                DB::table('languages')->insert([
                    'tenant_id' => $tenant->id,
                    'code' => $lang['code'],
                    'name' => $lang['name'],
                    'native_name' => $lang['native_name'],
                    'is_active' => $lang['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Created language: {$lang['name']}");
            } else {
                $this->command->info("Language {$lang['name']} already exists, skipping...");
            }
        }

        // Currencies
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'rate' => 1.00, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'rate' => 0.85, 'is_active' => true],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'rate' => 0.73, 'is_active' => true],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'rate' => 1.35, 'is_active' => true],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'rate' => 1.52, 'is_active' => true],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'rate' => 110.00, 'is_active' => true],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'rate' => 0.92, 'is_active' => true],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'rate' => 6.45, 'is_active' => true],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'rate' => 74.50, 'is_active' => true],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$', 'rate' => 5.25, 'is_active' => true],
            ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => '$', 'rate' => 20.15, 'is_active' => true],
            ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽', 'rate' => 73.50, 'is_active' => true],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'rate' => 1.35, 'is_active' => true],
            ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'rate' => 7.80, 'is_active' => true],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'rate' => 3.67, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            $existing = DB::table('currencies')->where('code', $currency['code'])->first();
            if (!$existing) {
                DB::table('currencies')->insert([
                    'tenant_id' => $tenant->id,
                    'code' => $currency['code'],
                    'name' => $currency['name'],
                    'symbol' => $currency['symbol'],
                    'symbol_position' => 'before',
                    'decimal_places' => 2,
                    'exchange_rate' => $currency['rate'],
                    'is_active' => $currency['is_active'],
                    'is_default' => $currency['code'] === 'USD',
                    'last_updated' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Created currency: {$currency['name']}");
            } else {
                $this->command->info("Currency {$currency['name']} already exists, skipping...");
            }
        }

        $this->command->info('Language and currency seeding completed!');
    }
}
