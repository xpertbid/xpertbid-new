<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
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

        // Check if admin user already exists
        $existingAdmin = DB::table('users')->where('email', 'admin@xpertbid.com')->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin user already exists. Updating password...');
            
            // Update the existing admin user
            DB::table('users')
                ->where('email', 'admin@xpertbid.com')
                ->update([
                    'password' => Hash::make('admin123'),
                    'name' => 'Administrator',
                    'updated_at' => now(),
                ]);
                
            $this->command->info('Admin password updated successfully!');
        } else {
            // Create new admin user
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Administrator',
                'email' => 'admin@xpertbid.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info("Admin user created successfully! ID: {$adminId}");
        }

        // Display login credentials
        $this->command->info('');
        $this->command->info('=== ADMIN LOGIN CREDENTIALS ===');
        $this->command->info('Email: admin@xpertbid.com');
        $this->command->info('Password: admin123');
        $this->command->info('===============================');
        $this->command->info('');
    }
}