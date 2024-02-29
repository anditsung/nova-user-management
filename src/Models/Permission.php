<?php


namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Nova\Actions\Actionable;
use Spatie\Permission\Models\Permission as SpatiePermissionModel;
use Spatie\Permission\PermissionRegistrar;

class Permission extends SpatiePermissionModel
{
    use Actionable;

    protected $fillable = [
        'name',
        'group',
        'guard_name',
        'user_id',
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
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            PermissionRegistrar::$pivotRole
        )->using(PermissionRole::class);
    }
}
