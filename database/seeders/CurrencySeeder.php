<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'tenant_id' => 1,
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'exchange_rate' => 1.000000,
                'is_active' => true,
                'is_default' => true,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'symbol_position' => 'after',
                'decimal_places' => 2,
                'exchange_rate' => 0.850000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'British Pound',
                'code' => 'GBP',
                'symbol' => '£',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'exchange_rate' => 0.780000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Japanese Yen',
                'code' => 'JPY',
                'symbol' => '¥',
                'symbol_position' => 'before',
                'decimal_places' => 0,
                'exchange_rate' => 150.000000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Canadian Dollar',
                'code' => 'CAD',
                'symbol' => 'C$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'exchange_rate' => 1.350000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Australian Dollar',
                'code' => 'AUD',
                'symbol' => 'A$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'exchange_rate' => 1.520000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Swiss Franc',
                'code' => 'CHF',
                'symbol' => 'CHF',
                'symbol_position' => 'after',
                'decimal_places' => 2,
                'exchange_rate' => 0.880000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Chinese Yuan',
                'code' => 'CNY',
                'symbol' => '¥',
                'symbol_position' => 'before',
                'decimal_places' => 2,
                'exchange_rate' => 7.200000,
                'is_active' => true,
                'is_default' => false,
                'last_updated' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('currencies')->insert($currencies);
    }
}