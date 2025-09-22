<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions first
        $this->createPermissions();
        
        // Create roles for each tenant
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            // Create a default tenant if none exists
            $tenant = Tenant::create([
                'name' => 'Default Tenant',
                'domain' => 'localhost',
                'status' => 'active',
            ]);
            $tenants = collect([$tenant]);
        }
        
        foreach ($tenants as $tenant) {
            $this->createRolesForTenant($tenant);
        }
    }

    private function createPermissions(): void
    {
        $permissions = Permission::getPredefinedPermissions();
        
        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                array_merge($permissionData, ['is_system' => true])
            );
        }
    }

    private function createRolesForTenant($tenant): void
    {
        $predefinedRoles = Role::getPredefinedRoles();
        
        foreach ($predefinedRoles as $roleData) {
            $role = Role::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $roleData['name']
                ],
                array_merge($roleData, ['is_system' => true])
            );
            
            // Assign permissions to roles
            $this->assignPermissionsToRole($role);
        }
    }

    private function assignPermissionsToRole(Role $role): void
    {
        $permissions = [];
        
        switch ($role->name) {
            case 'individual':
                $permissions = [
                    'view_dashboard',
                    'view_auctions',
                    'view_products',
                    'view_properties',
                    'view_vehicles',
                ];
                break;
                
            case 'vendor':
                $permissions = [
                    'view_dashboard',
                    'view_auctions',
                    'create_auctions',
                    'edit_auctions',
                    'view_products',
                    'create_products',
                    'edit_products',
                    'view_properties',
                    'create_properties',
                    'edit_properties',
                    'view_vehicles',
                    'create_vehicles',
                    'edit_vehicles',
                    'view_orders',
                    'manage_orders',
                    'view_payments',
                    'manage_payments',
                ];
                break;
                
            case 'vendor_team_member':
                $permissions = [
                    'view_dashboard',
                    'view_auctions',
                    'view_products',
                    'edit_products',
                    'view_properties',
                    'edit_properties',
                    'view_vehicles',
                    'edit_vehicles',
                    'view_orders',
                    'view_payments',
                ];
                break;
                
            case 'admin':
                $permissions = Permission::pluck('name')->toArray(); // All permissions
                break;
                
            case 'admin_team_member':
                $permissions = [
                    'view_dashboard',
                    'view_users',
                    'view_vendors',
                    'approve_vendors',
                    'reject_vendors',
                    'view_products',
                    'approve_products',
                    'view_auctions',
                    'check_auctions',
                    'approve_auctions',
                    'view_properties',
                    'view_vehicles',
                    'view_orders',
                    'manage_orders',
                    'view_payments',
                    'manage_payments',
                    'view_kyc',
                    'manage_kyc',
                    'approve_kyc',
                    'reject_kyc',
                    'view_reports',
                    'export_reports',
                ];
                break;
        }
        
        // Get permission IDs
        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();
        
        // Sync permissions to role
        $role->permissions()->sync($permissionIds);
    }
}