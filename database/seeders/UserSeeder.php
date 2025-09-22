<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Super Admin
            [
                'tenant_id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@xpertbid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0101',
                'date_of_birth' => '1985-01-15',
                'gender' => 'male',
                'avatar' => 'https://via.placeholder.com/150/2c3e50/ffffff?text=SA',
                'status' => 'active',
                'two_factor_enabled' => true,
                'preferences' => json_encode([
                    'theme' => 'dark',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now(),
                'last_login_ip' => '192.168.1.1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Regular Customers
            [
                'tenant_id' => 1,
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0102',
                'date_of_birth' => '1990-05-20',
                'gender' => 'male',
                'avatar' => 'https://via.placeholder.com/150/3498db/ffffff?text=JS',
                'status' => 'active',
                'two_factor_enabled' => false,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subHours(2),
                'last_login_ip' => '192.168.1.2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0103',
                'date_of_birth' => '1988-12-10',
                'gender' => 'female',
                'avatar' => 'https://via.placeholder.com/150/e74c3c/ffffff?text=SJ',
                'status' => 'active',
                'two_factor_enabled' => false,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => false,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subDays(1),
                'last_login_ip' => '192.168.1.3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Mike Wilson',
                'email' => 'mike.wilson@email.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0104',
                'date_of_birth' => '1992-08-25',
                'gender' => 'male',
                'avatar' => 'https://via.placeholder.com/150/27ae60/ffffff?text=MW',
                'status' => 'active',
                'two_factor_enabled' => true,
                'preferences' => json_encode([
                    'theme' => 'dark',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subHours(5),
                'last_login_ip' => '192.168.1.4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 1,
                'name' => 'Emily Davis',
                'email' => 'emily.davis@email.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0105',
                'date_of_birth' => '1995-03-15',
                'gender' => 'female',
                'avatar' => 'https://via.placeholder.com/150/8e44ad/ffffff?text=ED',
                'status' => 'active',
                'two_factor_enabled' => false,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subDays(3),
                'last_login_ip' => '192.168.1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Estate Agents
            [
                'tenant_id' => 3, // RealEstate Pro
                'name' => 'Robert Martinez',
                'email' => 'robert.martinez@realestatepro.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0201',
                'date_of_birth' => '1980-07-12',
                'gender' => 'male',
                'avatar' => 'https://via.placeholder.com/150/27ae60/ffffff?text=RM',
                'status' => 'active',
                'two_factor_enabled' => true,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subHours(1),
                'last_login_ip' => '192.168.2.1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 3,
                'name' => 'Lisa Thompson',
                'email' => 'lisa.thompson@realestatepro.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0202',
                'date_of_birth' => '1983-11-08',
                'gender' => 'female',
                'avatar' => 'https://via.placeholder.com/150/2ecc71/ffffff?text=LT',
                'status' => 'active',
                'two_factor_enabled' => false,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subHours(3),
                'last_login_ip' => '192.168.2.2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Sales Agents for AutoTrade
            [
                'tenant_id' => 4, // AutoTrade Hub
                'name' => 'David Brown',
                'email' => 'david.brown@autotradehub.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0301',
                'date_of_birth' => '1978-04-22',
                'gender' => 'male',
                'avatar' => 'https://via.placeholder.com/150/8e44ad/ffffff?text=DB',
                'status' => 'active',
                'two_factor_enabled' => true,
                'preferences' => json_encode([
                    'theme' => 'dark',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subMinutes(30),
                'last_login_ip' => '192.168.3.1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 4,
                'name' => 'Jennifer Lee',
                'email' => 'jennifer.lee@autotradehub.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0302',
                'date_of_birth' => '1987-09-18',
                'gender' => 'female',
                'avatar' => 'https://via.placeholder.com/150/9b59b6/ffffff?text=JL',
                'status' => 'active',
                'two_factor_enabled' => false,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subHours(2),
                'last_login_ip' => '192.168.3.2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // TechMart Users
            [
                'tenant_id' => 2, // TechMart Marketplace
                'name' => 'Alex Chen',
                'email' => 'alex.chen@techmart.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0401',
                'date_of_birth' => '1991-06-30',
                'gender' => 'male',
                'avatar' => 'https://via.placeholder.com/150/e74c3c/ffffff?text=AC',
                'status' => 'active',
                'two_factor_enabled' => false,
                'preferences' => json_encode([
                    'theme' => 'dark',
                    'notifications' => true,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subHours(4),
                'last_login_ip' => '192.168.4.1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => 2,
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@techmart.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+1-555-0402',
                'date_of_birth' => '1989-02-14',
                'gender' => 'female',
                'avatar' => 'https://via.placeholder.com/150/f39c12/ffffff?text=MG',
                'status' => 'active',
                'two_factor_enabled' => true,
                'preferences' => json_encode([
                    'theme' => 'light',
                    'notifications' => false,
                    'language' => 'en',
                    'currency' => 'USD'
                ]),
                'last_login_at' => now()->subDays(2),
                'last_login_ip' => '192.168.4.2',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('users')->insert($users);
    }
}
