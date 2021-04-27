<?php


namespace Tsung\NovaUserManagement\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Nova\Actions\Actionable;
use Spatie\Permission\Models\Permission as SpatiePermissionModel;

class Permission extends SpatiePermissionModel
{
    use Actionable;

    protected $fillable = [
        'name',
        'group',
        'guard_name',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
