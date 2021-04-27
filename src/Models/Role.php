<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Nova\Actions\Actionable;
use Spatie\Permission\Models\Role as SpatieRoleModel;
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

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
