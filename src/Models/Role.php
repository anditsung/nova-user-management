<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Nova\Actions\Actionable;
use Spatie\Permission\Models\Role as SpatieRoleModel;
use Spatie\Permission\PermissionRegistrar;
use Tsung\NovaUserManagement\Traits\GlobalScopes;

class Role extends SpatieRoleModel
{
    use Actionable,
        GlobalScopes;

    protected $fillable = [
        'name',
        'guard_name',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function($user) {
            if( !$user->user_id ) {
                $user->user_id = auth()->user()->id;
            }
        });
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * adding using to catch attach and detach event
     *
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        )->using(PermissionRole::class);
    }
}
