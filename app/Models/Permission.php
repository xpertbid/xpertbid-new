<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'resource',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Scope to get permissions by module.
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to get permissions by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to get permissions by resource.
     */
    public function scopeByResource($query, $resource)
    {
        return $query->where('resource', $resource);
    }

    /**
     * Scope to get system permissions.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Get predefined permissions.
     */
    public static function getPredefinedPermissions(): array
    {
        return [
            // Dashboard permissions
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'module' => 'dashboard', 'action' => 'view', 'resource' => 'dashboard'],
            
            // User management permissions
            ['name' => 'view_users', 'display_name' => 'View Users', 'module' => 'users', 'action' => 'view', 'resource' => 'user'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'module' => 'users', 'action' => 'create', 'resource' => 'user'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'module' => 'users', 'action' => 'edit', 'resource' => 'user'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'module' => 'users', 'action' => 'delete', 'resource' => 'user'],
            ['name' => 'manage_user_roles', 'display_name' => 'Manage User Roles', 'module' => 'users', 'action' => 'manage', 'resource' => 'user_role'],
            
            // Vendor management permissions
            ['name' => 'view_vendors', 'display_name' => 'View Vendors', 'module' => 'vendors', 'action' => 'view', 'resource' => 'vendor'],
            ['name' => 'create_vendors', 'display_name' => 'Create Vendors', 'module' => 'vendors', 'action' => 'create', 'resource' => 'vendor'],
            ['name' => 'edit_vendors', 'display_name' => 'Edit Vendors', 'module' => 'vendors', 'action' => 'edit', 'resource' => 'vendor'],
            ['name' => 'delete_vendors', 'display_name' => 'Delete Vendors', 'module' => 'vendors', 'action' => 'delete', 'resource' => 'vendor'],
            ['name' => 'approve_vendors', 'display_name' => 'Approve Vendors', 'module' => 'vendors', 'action' => 'approve', 'resource' => 'vendor'],
            ['name' => 'reject_vendors', 'display_name' => 'Reject Vendors', 'module' => 'vendors', 'action' => 'reject', 'resource' => 'vendor'],
            
            // Product management permissions
            ['name' => 'view_products', 'display_name' => 'View Products', 'module' => 'products', 'action' => 'view', 'resource' => 'product'],
            ['name' => 'create_products', 'display_name' => 'Create Products', 'module' => 'products', 'action' => 'create', 'resource' => 'product'],
            ['name' => 'edit_products', 'display_name' => 'Edit Products', 'module' => 'products', 'action' => 'edit', 'resource' => 'product'],
            ['name' => 'delete_products', 'display_name' => 'Delete Products', 'module' => 'products', 'action' => 'delete', 'resource' => 'product'],
            ['name' => 'approve_products', 'display_name' => 'Approve Products', 'module' => 'products', 'action' => 'approve', 'resource' => 'product'],
            
            // Auction management permissions
            ['name' => 'view_auctions', 'display_name' => 'View Auctions', 'module' => 'auctions', 'action' => 'view', 'resource' => 'auction'],
            ['name' => 'create_auctions', 'display_name' => 'Create Auctions', 'module' => 'auctions', 'action' => 'create', 'resource' => 'auction'],
            ['name' => 'edit_auctions', 'display_name' => 'Edit Auctions', 'module' => 'auctions', 'action' => 'edit', 'resource' => 'auction'],
            ['name' => 'delete_auctions', 'display_name' => 'Delete Auctions', 'module' => 'auctions', 'action' => 'delete', 'resource' => 'auction'],
            ['name' => 'approve_auctions', 'display_name' => 'Approve Auctions', 'module' => 'auctions', 'action' => 'approve', 'resource' => 'auction'],
            ['name' => 'check_auctions', 'display_name' => 'Check Auctions', 'module' => 'auctions', 'action' => 'check', 'resource' => 'auction'],
            ['name' => 'manage_auction_bids', 'display_name' => 'Manage Auction Bids', 'module' => 'auctions', 'action' => 'manage', 'resource' => 'bid'],
            
            // Property management permissions
            ['name' => 'view_properties', 'display_name' => 'View Properties', 'module' => 'properties', 'action' => 'view', 'resource' => 'property'],
            ['name' => 'create_properties', 'display_name' => 'Create Properties', 'module' => 'properties', 'action' => 'create', 'resource' => 'property'],
            ['name' => 'edit_properties', 'display_name' => 'Edit Properties', 'module' => 'properties', 'action' => 'edit', 'resource' => 'property'],
            ['name' => 'delete_properties', 'display_name' => 'Delete Properties', 'module' => 'properties', 'action' => 'delete', 'resource' => 'property'],
            
            // Vehicle management permissions
            ['name' => 'view_vehicles', 'display_name' => 'View Vehicles', 'module' => 'vehicles', 'action' => 'view', 'resource' => 'vehicle'],
            ['name' => 'create_vehicles', 'display_name' => 'Create Vehicles', 'module' => 'vehicles', 'action' => 'create', 'resource' => 'vehicle'],
            ['name' => 'edit_vehicles', 'display_name' => 'Edit Vehicles', 'module' => 'vehicles', 'action' => 'edit', 'resource' => 'vehicle'],
            ['name' => 'delete_vehicles', 'display_name' => 'Delete Vehicles', 'module' => 'vehicles', 'action' => 'delete', 'resource' => 'vehicle'],
            
            // Order management permissions
            ['name' => 'view_orders', 'display_name' => 'View Orders', 'module' => 'orders', 'action' => 'view', 'resource' => 'order'],
            ['name' => 'manage_orders', 'display_name' => 'Manage Orders', 'module' => 'orders', 'action' => 'manage', 'resource' => 'order'],
            ['name' => 'process_orders', 'display_name' => 'Process Orders', 'module' => 'orders', 'action' => 'process', 'resource' => 'order'],
            
            // Payment management permissions
            ['name' => 'view_payments', 'display_name' => 'View Payments', 'module' => 'payments', 'action' => 'view', 'resource' => 'payment'],
            ['name' => 'manage_payments', 'display_name' => 'Manage Payments', 'module' => 'payments', 'action' => 'manage', 'resource' => 'payment'],
            ['name' => 'process_payments', 'display_name' => 'Process Payments', 'module' => 'payments', 'action' => 'process', 'resource' => 'payment'],
            
            // KYC management permissions
            ['name' => 'view_kyc', 'display_name' => 'View KYC', 'module' => 'kyc', 'action' => 'view', 'resource' => 'kyc'],
            ['name' => 'manage_kyc', 'display_name' => 'Manage KYC', 'module' => 'kyc', 'action' => 'manage', 'resource' => 'kyc'],
            ['name' => 'approve_kyc', 'display_name' => 'Approve KYC', 'module' => 'kyc', 'action' => 'approve', 'resource' => 'kyc'],
            ['name' => 'reject_kyc', 'display_name' => 'Reject KYC', 'module' => 'kyc', 'action' => 'reject', 'resource' => 'kyc'],
            
            // Settings permissions
            ['name' => 'view_settings', 'display_name' => 'View Settings', 'module' => 'settings', 'action' => 'view', 'resource' => 'setting'],
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'module' => 'settings', 'action' => 'manage', 'resource' => 'setting'],
            ['name' => 'manage_languages', 'display_name' => 'Manage Languages', 'module' => 'settings', 'action' => 'manage', 'resource' => 'language'],
            ['name' => 'manage_currencies', 'display_name' => 'Manage Currencies', 'module' => 'settings', 'action' => 'manage', 'resource' => 'currency'],
            ['name' => 'manage_shipping', 'display_name' => 'Manage Shipping', 'module' => 'settings', 'action' => 'manage', 'resource' => 'shipping'],
            ['name' => 'manage_tax', 'display_name' => 'Manage Tax', 'module' => 'settings', 'action' => 'manage', 'resource' => 'tax'],
            ['name' => 'manage_payment_gateways', 'display_name' => 'Manage Payment Gateways', 'module' => 'settings', 'action' => 'manage', 'resource' => 'payment_gateway'],
            ['name' => 'manage_affiliates', 'display_name' => 'Manage Affiliates', 'module' => 'settings', 'action' => 'manage', 'resource' => 'affiliate'],
            
            // Reports permissions
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'module' => 'reports', 'action' => 'view', 'resource' => 'report'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'module' => 'reports', 'action' => 'export', 'resource' => 'report'],
            
            // Tenant management permissions
            ['name' => 'view_tenants', 'display_name' => 'View Tenants', 'module' => 'tenants', 'action' => 'view', 'resource' => 'tenant'],
            ['name' => 'create_tenants', 'display_name' => 'Create Tenants', 'module' => 'tenants', 'action' => 'create', 'resource' => 'tenant'],
            ['name' => 'edit_tenants', 'display_name' => 'Edit Tenants', 'module' => 'tenants', 'action' => 'edit', 'resource' => 'tenant'],
            ['name' => 'delete_tenants', 'display_name' => 'Delete Tenants', 'module' => 'tenants', 'action' => 'delete', 'resource' => 'tenant'],
        ];
    }
}