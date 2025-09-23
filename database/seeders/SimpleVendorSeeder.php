<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SimpleVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant
        $tenant = DB::table('tenants')->first();
        if (!$tenant) {
            $this->command->error('No tenants found. Please run TenantSeeder first.');
            return;
        }

        // Create vendor users first
        $vendorUsers = [
            [
                'name' => 'TechStore Pro',
                'email' => 'vendor1@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fashion Hub',
                'email' => 'vendor2@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Home Decor Plus',
                'email' => 'vendor3@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $vendors = [];
        foreach ($vendorUsers as $userData) {
            $userId = DB::table('users')->insertGetId($userData);
            
            $vendors[] = [
                'tenant_id' => $tenant->id,
                'user_id' => $userId,
                'business_name' => $userData['name'],
                'business_type' => 'Retail',
                'description' => 'Professional online retailer specializing in quality products',
                'logo' => '/images/placeholder.svg',
                'website' => 'https://example.com',
                'phone' => '+1-555-0123',
                'email' => $userData['email'],
                'address' => '123 Business St',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'commission_rate' => 5.00,
                'status' => 'active',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert vendors
        foreach ($vendors as $vendor) {
            $vendorId = DB::table('vendors')->insertGetId($vendor);
            $this->command->info("Created vendor: {$vendor['business_name']} (ID: {$vendorId})");
        }
    }
}