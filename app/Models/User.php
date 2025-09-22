<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'provider',
        'provider_id',
        'tenant_id',
        'user_type',
        'phone',
        'avatar',
        'status',
        'role',
        'email_verified_at',
        'last_login_at',
        'profile_data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'profile_data' => 'array',
        ];
    }

    /**
     * Get the tenant that owns the user.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the roles for this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot(['tenant_id', 'assigned_at', 'assigned_by', 'expires_at', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Get the user roles for this user.
     */
    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get current active roles for this user.
     */
    public function currentRoles()
    {
        return $this->roles()
                    ->wherePivot('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('user_roles.expires_at')
                              ->orWhere('user_roles.expires_at', '>', now());
                    });
    }

    /**
     * Get all permissions for this user through their roles.
     */
    public function permissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->whereHas('users', function ($userQuery) {
                $userQuery->where('users.id', $this->id)
                         ->where('user_roles.is_active', true)
                         ->where(function ($expireQuery) {
                             $expireQuery->whereNull('user_roles.expires_at')
                                        ->orWhere('user_roles.expires_at', '>', now());
                         });
            });
        });
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role, $tenantId = null): bool
    {
        $query = $this->currentRoles();
        
        if ($tenantId) {
            $query->wherePivot('tenant_id', $tenantId);
        }

        if (is_string($role)) {
            return $query->where('name', $role)->exists();
        }

        if ($role instanceof Role) {
            return $query->where('roles.id', $role->id)->exists();
        }

        return false;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles, $tenantId = null): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role, $tenantId)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(array $roles, $tenantId = null): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role, $tenantId)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission, $tenantId = null): bool
    {
        $query = $this->permissions();
        
        if ($tenantId) {
            $query->whereHas('roles', function ($roleQuery) use ($tenantId) {
                $roleQuery->whereHas('users', function ($userQuery) use ($tenantId) {
                    $userQuery->where('users.id', $this->id)
                             ->where('user_roles.tenant_id', $tenantId);
                });
            });
        }

        if (is_string($permission)) {
            return $query->where('name', $permission)->exists();
        }

        if ($permission instanceof Permission) {
            return $query->where('permissions.id', $permission->id)->exists();
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions, $tenantId = null): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission, $tenantId)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions, $tenantId = null): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission, $tenantId)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole($role, $tenantId = null, $assignedBy = null, $expiresAt = null): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return;
        }

        $tenantId = $tenantId ?? $this->tenant_id;

        $this->roles()->syncWithoutDetaching([
            $role->id => [
                'tenant_id' => $tenantId,
                'assigned_at' => now(),
                'assigned_by' => $assignedBy,
                'expires_at' => $expiresAt,
                'is_active' => true,
            ]
        ]);
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($role, $tenantId = null): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return;
        }

        $tenantId = $tenantId ?? $this->tenant_id;

        $this->roles()->wherePivot('tenant_id', $tenantId)->detach($role->id);
    }

    /**
     * Sync roles for the user.
     */
    public function syncRoles(array $roles, $tenantId = null): void
    {
        $tenantId = $tenantId ?? $this->tenant_id;
        
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('name', $role)->first()?->id;
            }
            return $role instanceof Role ? $role->id : $role;
        })->filter()->toArray();

        $syncData = [];
        foreach ($roleIds as $roleId) {
            $syncData[$roleId] = [
                'tenant_id' => $tenantId,
                'assigned_at' => now(),
                'is_active' => true,
            ];
        }

        $this->roles()->wherePivot('tenant_id', $tenantId)->sync($syncData);
    }

    /**
     * Check if user is an individual.
     */
    public function isIndividual($tenantId = null): bool
    {
        return $this->hasRole('individual', $tenantId);
    }

    /**
     * Check if user is a vendor.
     */
    public function isVendor($tenantId = null): bool
    {
        return $this->hasAnyRole(['vendor', 'vendor_team_member'], $tenantId);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin($tenantId = null): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a team member.
     */
    public function isTeamMember($tenantId = null): bool
    {
        return $this->hasAnyRole(['vendor_team_member', 'admin_team_member'], $tenantId);
    }

    /**
     * Get user's primary role for a tenant.
     */
    public function getPrimaryRole($tenantId = null)
    {
        $tenantId = $tenantId ?? $this->tenant_id;
        
        return $this->currentRoles()
                    ->wherePivot('tenant_id', $tenantId)
                    ->orderBy('level', 'desc')
                    ->orderBy('sort_order')
                    ->first();
    }

    /**
     * Get user's user type based on their roles.
     */
    public function getUserType($tenantId = null): string
    {
        $primaryRole = $this->getPrimaryRole($tenantId);
        
        if (!$primaryRole) {
            return 'individual';
        }

        return $primaryRole->user_type;
    }
}
