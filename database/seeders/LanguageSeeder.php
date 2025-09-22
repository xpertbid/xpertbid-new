<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'tenant_id' => 1,
                'name' => 'English',
                'code' => 'en',
                'native_name' => 'English',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => true,
                'flag_url' => 'https://via.placeholder.com/32x24/3498db/ffffff?text=EN',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Spanish',
                'code' => 'es',
                'native_name' => 'Español',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'flag_url' => 'https://via.placeholder.com/32x24/e74c3c/ffffff?text=ES',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'French',
                'code' => 'fr',
                'native_name' => 'Français',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'flag_url' => 'https://via.placeholder.com/32x24/2ecc71/ffffff?text=FR',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'German',
                'code' => 'de',
                'native_name' => 'Deutsch',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'flag_url' => 'https://via.placeholder.com/32x24/8e44ad/ffffff?text=DE',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Arabic',
                'code' => 'ar',
                'native_name' => 'العربية',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => false,
                'flag_url' => 'https://via.placeholder.com/32x24/27ae60/ffffff?text=AR',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Chinese',
                'code' => 'zh',
                'native_name' => '中文',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'flag_url' => 'https://via.placeholder.com/32x24/f39c12/ffffff?text=ZH',
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('languages')->insert($languages);
    }
}