<?php


namespace Tsung\NovaUserManagement\Models;


use App\User;
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
        return $this->belongsTo(User::class);
    }
}
