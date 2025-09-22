<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'display_name',
        'description',
        'user_type',
        'level',
        'restrictions',
        'is_active',
        'is_system',
        'sort_order',
    ];

    protected $casts = [
        'restrictions' => 'array',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Get the tenant that owns the role.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the permissions for this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Get the users with this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withPivot(['tenant_id', 'assigned_at', 'assigned_by', 'expires_at', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Get the user roles for this role.
     */
    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Scope to get only active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get roles by user type.
     */
    public function scopeByUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    /**
     * Scope to get roles by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get system roles.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope to get custom roles.
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            return $this->permissions()->where('name', $permission)->exists();
        }

        if ($permission instanceof Permission) {
            return $this->permissions()->where('permission_id', $permission->id)->exists();
        }

        return false;
    }

    /**
     * Give permission to the role.
     */
    public function givePermissionTo($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Revoke permission from the role.
     */
    public function revokePermissionTo($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * Sync permissions for the role.
     */
    public function syncPermissions(array $permissions): void
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('name', $permission)->first()?->id;
            }
            return $permission instanceof Permission ? $permission->id : $permission;
        })->filter()->toArray();

        $this->permissions()->sync($permissionIds);
    }

    /**
     * Get predefined role names.
     */
    public static function getPredefinedRoles(): array
    {
        return [
            'individual' => [
                'name' => 'individual',
                'display_name' => 'Individual',
                'user_type' => 'individual',
                'level' => 'standard',
                'description' => 'Regular individual user who can bid on auctions and purchase products',
            ],
            'vendor' => [
                'name' => 'vendor',
                'display_name' => 'Vendor',
                'user_type' => 'vendor',
                'level' => 'standard',
                'description' => 'Vendor who can create and manage auctions and products',
            ],
            'vendor_team_member' => [
                'name' => 'vendor_team_member',
                'display_name' => 'Vendor Team Member',
                'user_type' => 'vendor',
                'level' => 'team_member',
                'description' => 'Team member of a vendor with limited vendor permissions',
            ],
            'admin' => [
                'name' => 'admin',
                'display_name' => 'Admin',
                'user_type' => 'admin',
                'level' => 'standard',
                'description' => 'Full admin with all permissions',
            ],
            'admin_team_member' => [
                'name' => 'admin_team_member',
                'display_name' => 'Admin Team Member',
                'user_type' => 'admin',
                'level' => 'team_member',
                'description' => 'Admin team member with specific permissions (e.g., auction checker, KYC manager)',
            ],
        ];
    }
}