<?php


namespace Tsung\NovaUserManagement\Policies;


use App\User;

class UserPolicy extends Policy
{
    public static $key = 'users';

    /*
     * attach button visible
     */
    public function attachAnyRole(User $user)
    {
        return $user->hasPermissionTo('attachRole ' . static::$key);
    }

    /*
     * able to add and update role
     */
    public function attachRole(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachRole ' . static::$key);
    }

    public function detachRole(User $user)
    {
        return $user->hasPermissionTo('detachRole ' . static::$key);
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
