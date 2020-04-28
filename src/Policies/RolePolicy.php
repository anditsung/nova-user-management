<?php


namespace Tsung\NovaUserManagement\Policies;


use App\User;

class RolePolicy extends Policy
{
    public static $key = 'roles';

    /*
     * attach button visible
     */
    public function attachAnyUser(User $user)
    {
        return $user->hasPermissionTo('attachUser ' . static::$key);
    }

    /*
     * able to add and update role
     */
    public function attachUser(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachUser ' . static::$key);
    }

    public function detachUser(User $user)
    {
        return $user->hasPermissionTo('detachUser ' . static::$key);
    }

    public function attachAnyPermission(User $user)
    {
        return $user->hasPermissionTo('attachPermission ' . static::$key);
    }

    public function attachPermission(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachPermission ' . static::$key);
    }

    public function detachPermission(User $user)
    {
        return $user->hasPermissionTo('detachPermission ' . static::$key);
    }
}
