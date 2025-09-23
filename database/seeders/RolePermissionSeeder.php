<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating roles and permissions...');

        // Get existing data
        $tenant = DB::table('tenants')->first();

        if (!$tenant) {
            $this->command->error('Required data not found. Please run other seeders first.');
            return;
        }

        // Create permissions
        $permissions = [
            // User Management
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'View user accounts and profiles', 'module' => 'users', 'action' => 'view', 'resource' => 'users'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'description' => 'Create new user accounts', 'module' => 'users', 'action' => 'create', 'resource' => 'users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'description' => 'Edit user account information', 'module' => 'users', 'action' => 'edit', 'resource' => 'users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'description' => 'Delete user accounts', 'module' => 'users', 'action' => 'delete', 'resource' => 'users'],
            ['name' => 'manage_user_roles', 'display_name' => 'Manage User Roles', 'description' => 'Assign and modify user roles', 'module' => 'users', 'action' => 'manage', 'resource' => 'roles'],
            ['name' => 'ban_users', 'display_name' => 'Ban Users', 'description' => 'Suspend or ban user accounts', 'module' => 'users', 'action' => 'ban', 'resource' => 'users'],

            // Product Management
            ['name' => 'view_products', 'display_name' => 'View Products', 'description' => 'View product listings'],
            ['name' => 'create_products', 'display_name' => 'Create Products', 'description' => 'Create new product listings'],
            ['name' => 'edit_products', 'display_name' => 'Edit Products', 'description' => 'Edit product information'],
            ['name' => 'delete_products', 'display_name' => 'Delete Products', 'description' => 'Delete product listings'],
            ['name' => 'manage_product_categories', 'display_name' => 'Manage Product Categories', 'description' => 'Create and manage product categories'],
            ['name' => 'approve_products', 'display_name' => 'Approve Products', 'description' => 'Approve product listings for publication'],

            // Order Management
            ['name' => 'view_orders', 'display_name' => 'View Orders', 'description' => 'View customer orders'],
            ['name' => 'edit_orders', 'display_name' => 'Edit Orders', 'description' => 'Edit order details and status'],
            ['name' => 'cancel_orders', 'display_name' => 'Cancel Orders', 'description' => 'Cancel customer orders'],
            ['name' => 'process_refunds', 'display_name' => 'Process Refunds', 'description' => 'Process order refunds'],
            ['name' => 'manage_shipping', 'display_name' => 'Manage Shipping', 'description' => 'Manage shipping options and tracking'],

            // Auction Management
            ['name' => 'view_auctions', 'display_name' => 'View Auctions', 'description' => 'View auction listings'],
            ['name' => 'create_auctions', 'display_name' => 'Create Auctions', 'description' => 'Create new auction listings'],
            ['name' => 'edit_auctions', 'display_name' => 'Edit Auctions', 'description' => 'Edit auction details'],
            ['name' => 'delete_auctions', 'display_name' => 'Delete Auctions', 'description' => 'Delete auction listings'],
            ['name' => 'manage_auction_bids', 'display_name' => 'Manage Auction Bids', 'description' => 'View and manage auction bids'],
            ['name' => 'resolve_auction_disputes', 'display_name' => 'Resolve Auction Disputes', 'description' => 'Handle auction-related disputes'],

            // Property Management
            ['name' => 'view_properties', 'display_name' => 'View Properties', 'description' => 'View property listings'],
            ['name' => 'create_properties', 'display_name' => 'Create Properties', 'description' => 'Create new property listings'],
            ['name' => 'edit_properties', 'display_name' => 'Edit Properties', 'description' => 'Edit property information'],
            ['name' => 'delete_properties', 'display_name' => 'Delete Properties', 'description' => 'Delete property listings'],
            ['name' => 'verify_properties', 'display_name' => 'Verify Properties', 'description' => 'Verify property listings'],

            // Vehicle Management
            ['name' => 'view_vehicles', 'display_name' => 'View Vehicles', 'description' => 'View vehicle listings'],
            ['name' => 'create_vehicles', 'display_name' => 'Create Vehicles', 'description' => 'Create new vehicle listings'],
            ['name' => 'edit_vehicles', 'display_name' => 'Edit Vehicles', 'description' => 'Edit vehicle information'],
            ['name' => 'delete_vehicles', 'display_name' => 'Delete Vehicles', 'description' => 'Delete vehicle listings'],
            ['name' => 'verify_vehicles', 'display_name' => 'Verify Vehicles', 'description' => 'Verify vehicle listings'],

            // Vendor Management
            ['name' => 'view_vendors', 'display_name' => 'View Vendors', 'description' => 'View vendor accounts'],
            ['name' => 'create_vendors', 'display_name' => 'Create Vendors', 'description' => 'Create new vendor accounts'],
            ['name' => 'edit_vendors', 'display_name' => 'Edit Vendors', 'description' => 'Edit vendor information'],
            ['name' => 'delete_vendors', 'display_name' => 'Delete Vendors', 'description' => 'Delete vendor accounts'],
            ['name' => 'approve_vendors', 'display_name' => 'Approve Vendors', 'description' => 'Approve vendor applications'],
            ['name' => 'manage_vendor_commissions', 'display_name' => 'Manage Vendor Commissions', 'description' => 'Set and manage vendor commission rates'],

            // Content Management
            ['name' => 'view_blogs', 'display_name' => 'View Blogs', 'description' => 'View blog posts'],
            ['name' => 'create_blogs', 'display_name' => 'Create Blogs', 'description' => 'Create new blog posts'],
            ['name' => 'edit_blogs', 'display_name' => 'Edit Blogs', 'description' => 'Edit blog posts'],
            ['name' => 'delete_blogs', 'display_name' => 'Delete Blogs', 'description' => 'Delete blog posts'],
            ['name' => 'manage_blog_categories', 'display_name' => 'Manage Blog Categories', 'description' => 'Create and manage blog categories'],
            ['name' => 'view_pages', 'display_name' => 'View Pages', 'description' => 'View static pages'],
            ['name' => 'create_pages', 'display_name' => 'Create Pages', 'description' => 'Create new static pages'],
            ['name' => 'edit_pages', 'display_name' => 'Edit Pages', 'description' => 'Edit static pages'],
            ['name' => 'delete_pages', 'display_name' => 'Delete Pages', 'description' => 'Delete static pages'],

            // Financial Management
            ['name' => 'view_financial_reports', 'display_name' => 'View Financial Reports', 'description' => 'View financial reports and analytics'],
            ['name' => 'manage_payments', 'display_name' => 'Manage Payments', 'description' => 'Manage payment processing'],
            ['name' => 'view_transactions', 'display_name' => 'View Transactions', 'description' => 'View transaction history'],
            ['name' => 'manage_commissions', 'display_name' => 'Manage Commissions', 'description' => 'Manage commission structures'],
            ['name' => 'process_payouts', 'display_name' => 'Process Payouts', 'description' => 'Process vendor payouts'],

            // System Administration
            ['name' => 'view_system_settings', 'display_name' => 'View System Settings', 'description' => 'View system configuration'],
            ['name' => 'edit_system_settings', 'display_name' => 'Edit System Settings', 'description' => 'Edit system configuration'],
            ['name' => 'manage_roles_permissions', 'display_name' => 'Manage Roles & Permissions', 'description' => 'Manage user roles and permissions'],
            ['name' => 'view_audit_logs', 'display_name' => 'View Audit Logs', 'description' => 'View system audit logs'],
            ['name' => 'manage_api_keys', 'display_name' => 'Manage API Keys', 'description' => 'Manage API access keys'],
            ['name' => 'backup_system', 'display_name' => 'Backup System', 'description' => 'Create system backups'],
            ['name' => 'restore_system', 'display_name' => 'Restore System', 'description' => 'Restore system from backups'],

            // Customer Support
            ['name' => 'view_support_tickets', 'display_name' => 'View Support Tickets', 'description' => 'View customer support tickets'],
            ['name' => 'respond_support_tickets', 'display_name' => 'Respond to Support Tickets', 'description' => 'Respond to customer support tickets'],
            ['name' => 'resolve_support_tickets', 'display_name' => 'Resolve Support Tickets', 'description' => 'Resolve customer support tickets'],
            ['name' => 'view_customer_feedback', 'display_name' => 'View Customer Feedback', 'description' => 'View customer feedback and reviews'],

            // Marketing & Promotions
            ['name' => 'create_promotions', 'display_name' => 'Create Promotions', 'description' => 'Create promotional campaigns'],
            ['name' => 'manage_coupons', 'display_name' => 'Manage Coupons', 'description' => 'Create and manage discount coupons'],
            ['name' => 'send_newsletters', 'display_name' => 'Send Newsletters', 'description' => 'Send marketing newsletters'],
            ['name' => 'manage_banners', 'display_name' => 'Manage Banners', 'description' => 'Manage promotional banners'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permissionData) {
            $existing = DB::table('permissions')->where('name', $permissionData['name'])->first();
            if (!$existing) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => $permissionData['name'],
                    'display_name' => $permissionData['display_name'],
                    'description' => $permissionData['description'],
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

        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => array_keys($permissions), // All permissions
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access to most system features',
                'permissions' => [
                    'view_users', 'create_users', 'edit_users', 'delete_users', 'manage_user_roles',
                    'view_products', 'create_products', 'edit_products', 'delete_products', 'manage_product_categories', 'approve_products',
                    'view_orders', 'edit_orders', 'cancel_orders', 'process_refunds', 'manage_shipping',
                    'view_auctions', 'create_auctions', 'edit_auctions', 'delete_auctions', 'manage_auction_bids',
                    'view_properties', 'create_properties', 'edit_properties', 'delete_properties', 'verify_properties',
                    'view_vehicles', 'create_vehicles', 'edit_vehicles', 'delete_vehicles', 'verify_vehicles',
                    'view_vendors', 'create_vendors', 'edit_vendors', 'delete_vendors', 'approve_vendors', 'manage_vendor_commissions',
                    'view_blogs', 'create_blogs', 'edit_blogs', 'delete_blogs', 'manage_blog_categories',
                    'view_pages', 'create_pages', 'edit_pages', 'delete_pages',
                    'view_financial_reports', 'manage_payments', 'view_transactions', 'manage_commissions', 'process_payouts',
                    'view_system_settings', 'edit_system_settings', 'manage_roles_permissions', 'view_audit_logs',
                    'view_support_tickets', 'respond_support_tickets', 'resolve_support_tickets', 'view_customer_feedback',
                    'create_promotions', 'manage_coupons', 'send_newsletters', 'manage_banners',
                ],
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'description' => 'Content moderation and user support',
                'permissions' => [
                    'view_users', 'edit_users', 'ban_users',
                    'view_products', 'edit_products', 'approve_products',
                    'view_orders', 'edit_orders', 'process_refunds',
                    'view_auctions', 'edit_auctions', 'manage_auction_bids', 'resolve_auction_disputes',
                    'view_properties', 'edit_properties', 'verify_properties',
                    'view_vehicles', 'edit_vehicles', 'verify_vehicles',
                    'view_vendors', 'edit_vendors',
                    'view_blogs', 'create_blogs', 'edit_blogs', 'delete_blogs',
                    'view_pages', 'edit_pages',
                    'view_transactions',
                    'view_support_tickets', 'respond_support_tickets', 'resolve_support_tickets', 'view_customer_feedback',
                ],
            ],
            [
                'name' => 'vendor',
                'display_name' => 'Vendor',
                'description' => 'Vendor account with selling permissions',
                'permissions' => [
                    'view_products', 'create_products', 'edit_products', 'delete_products',
                    'view_orders',
                    'view_auctions', 'create_auctions', 'edit_auctions',
                    'view_properties', 'create_properties', 'edit_properties',
                    'view_vehicles', 'create_vehicles', 'edit_vehicles',
                    'view_blogs', 'create_blogs', 'edit_blogs',
                    'view_transactions',
                    'view_support_tickets', 'respond_support_tickets',
                ],
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Standard customer account',
                'permissions' => [
                    'view_products',
                    'view_orders',
                    'view_auctions',
                    'view_properties',
                    'view_vehicles',
                    'view_blogs',
                    'view_support_tickets', 'respond_support_tickets',
                ],
            ],
            [
                'name' => 'support_agent',
                'display_name' => 'Support Agent',
                'description' => 'Customer support representative',
                'permissions' => [
                    'view_users',
                    'view_products',
                    'view_orders', 'edit_orders', 'process_refunds',
                    'view_auctions',
                    'view_properties',
                    'view_vehicles',
                    'view_vendors',
                    'view_transactions',
                    'view_support_tickets', 'respond_support_tickets', 'resolve_support_tickets', 'view_customer_feedback',
                ],
            ],
            [
                'name' => 'content_manager',
                'display_name' => 'Content Manager',
                'description' => 'Manages content including blogs and pages',
                'permissions' => [
                    'view_blogs', 'create_blogs', 'edit_blogs', 'delete_blogs', 'manage_blog_categories',
                    'view_pages', 'create_pages', 'edit_pages', 'delete_pages',
                    'create_promotions', 'manage_coupons', 'send_newsletters', 'manage_banners',
                ],
            ],
            [
                'name' => 'financial_manager',
                'display_name' => 'Financial Manager',
                'description' => 'Manages financial aspects of the platform',
                'permissions' => [
                    'view_orders', 'edit_orders', 'process_refunds',
                    'view_vendors', 'manage_vendor_commissions',
                    'view_financial_reports', 'manage_payments', 'view_transactions', 'manage_commissions', 'process_payouts',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $existing = DB::table('roles')->where('name', $roleData['name'])->first();
            if (!$existing) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $roleData['name'],
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
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

        $this->command->info('Role and permission seeding completed!');
    }
}