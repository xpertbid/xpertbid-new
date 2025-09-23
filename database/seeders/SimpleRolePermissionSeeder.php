<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimpleRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating essential roles and permissions...');

        // Get existing data
        $tenant = DB::table('tenants')->first();

        if (!$tenant) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        // Create essential permissions
        $permissions = [
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'description' => 'Access admin dashboard', 'module' => 'dashboard', 'action' => 'view', 'resource' => 'dashboard'],
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Manage user accounts', 'module' => 'users', 'action' => 'manage', 'resource' => 'users'],
            ['name' => 'manage_products', 'display_name' => 'Manage Products', 'description' => 'Manage product listings', 'module' => 'products', 'action' => 'manage', 'resource' => 'products'],
            ['name' => 'manage_orders', 'display_name' => 'Manage Orders', 'description' => 'Manage customer orders', 'module' => 'orders', 'action' => 'manage', 'resource' => 'orders'],
            ['name' => 'manage_auctions', 'display_name' => 'Manage Auctions', 'description' => 'Manage auction listings', 'module' => 'auctions', 'action' => 'manage', 'resource' => 'auctions'],
            ['name' => 'manage_properties', 'display_name' => 'Manage Properties', 'description' => 'Manage property listings', 'module' => 'properties', 'action' => 'manage', 'resource' => 'properties'],
            ['name' => 'manage_vehicles', 'display_name' => 'Manage Vehicles', 'description' => 'Manage vehicle listings', 'module' => 'vehicles', 'action' => 'manage', 'resource' => 'vehicles'],
            ['name' => 'manage_vendors', 'display_name' => 'Manage Vendors', 'description' => 'Manage vendor accounts', 'module' => 'vendors', 'action' => 'manage', 'resource' => 'vendors'],
            ['name' => 'manage_content', 'display_name' => 'Manage Content', 'description' => 'Manage blog posts and pages', 'module' => 'content', 'action' => 'manage', 'resource' => 'content'],
            ['name' => 'manage_finances', 'display_name' => 'Manage Finances', 'description' => 'Manage financial reports and payments', 'module' => 'finances', 'action' => 'manage', 'resource' => 'finances'],
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'description' => 'Manage system settings', 'module' => 'settings', 'action' => 'manage', 'resource' => 'settings'],
            ['name' => 'manage_support', 'display_name' => 'Manage Support', 'description' => 'Manage customer support', 'module' => 'support', 'action' => 'manage', 'resource' => 'support'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permissionData) {
            $existing = DB::table('permissions')->where('name', $permissionData['name'])->first();
            if (!$existing) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => $permissionData['name'],
                    'display_name' => $permissionData['display_name'],
                    'description' => $permissionData['description'],
                    'module' => $permissionData['module'],
                    'action' => $permissionData['action'],
                    'resource' => $permissionData['resource'],
                    'is_system' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $permissionIds[] = $permissionId;
                $this->command->info("Created permission: {$permissionData['display_name']}");
            } else {
                $permissionIds[] = $existing->id;
                $this->command->info("Permission {$permissionData['display_name']} already exists, skipping...");
            }
        }

        // Create essential roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => ['view_dashboard', 'manage_users', 'manage_products', 'manage_orders', 'manage_auctions', 'manage_properties', 'manage_vehicles', 'manage_vendors', 'manage_content', 'manage_finances', 'manage_settings', 'manage_support'],
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access to most system features',
                'permissions' => ['view_dashboard', 'manage_users', 'manage_products', 'manage_orders', 'manage_auctions', 'manage_properties', 'manage_vehicles', 'manage_vendors', 'manage_content', 'manage_support'],
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'description' => 'Content moderation and user support',
                'permissions' => ['view_dashboard', 'manage_products', 'manage_orders', 'manage_auctions', 'manage_properties', 'manage_vehicles', 'manage_content', 'manage_support'],
            ],
            [
                'name' => 'vendor',
                'display_name' => 'Vendor',
                'description' => 'Vendor account with selling permissions',
                'permissions' => ['view_dashboard', 'manage_products', 'manage_orders', 'manage_auctions', 'manage_properties', 'manage_vehicles'],
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Standard customer account',
                'permissions' => [],
            ],
            [
                'name' => 'support_agent',
                'display_name' => 'Support Agent',
                'description' => 'Customer support representative',
                'permissions' => ['view_dashboard', 'manage_orders', 'manage_support'],
            ],
        ];

        foreach ($roles as $roleData) {
            $existing = DB::table('roles')->where('name', $roleData['name'])->first();
            if (!$existing) {
                $roleId = DB::table('roles')->insertGetId([
                    'tenant_id' => $tenant->id,
                    'name' => $roleData['name'],
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                    'user_type' => 'admin',
                    'level' => 'high',
                    'restrictions' => json_encode([]),
                    'is_active' => true,
                    'is_system' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Assign permissions to role
                foreach ($roleData['permissions'] as $permissionName) {
                    $permission = DB::table('permissions')->where('name', $permissionName)->first();
                    if ($permission) {
                        DB::table('role_permissions')->insert([
                            'role_id' => $roleId,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                $this->command->info("Created role: {$roleData['display_name']} with " . count($roleData['permissions']) . " permissions");
            } else {
                $this->command->info("Role {$roleData['display_name']} already exists, skipping...");
            }
        }

        $this->command->info('Simple role and permission seeding completed!');
    }
}
