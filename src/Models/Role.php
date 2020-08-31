<?php

namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Nova\Actions\Actionable;
use Spatie\Permission\Models\Role as SpatieRoleModel;

class Role extends SpatieRoleModel
{
    use Actionable;

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
}
