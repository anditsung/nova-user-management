<?php


namespace Tsung\NovaUserManagement\Policies;


class UserPolicy extends Policy
{
    public static $key = 'users';

    /*
     * attach button visible
     */
    public function attachAnyRole($user)
    {
        return $user->hasPermissionTo('attachRole ' . static::$key);
    }

    /*
     * able to add and update role
     */
    public function attachRole($user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachRole ' . static::$key);
    }

    public function detachRole($user)
    {
        return $user->hasPermissionTo('detachRole ' . static::$key);
    }

    public function attachAnyPermission($user)
    {
        return $user->hasPermissionTo('attachPermission ' . static::$key);
    }

    public function attachPermission($user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachPermission ' . static::$key);
    }

    public function detachPermission($user)
    {
        return $user->hasPermissionTo('detachPermission ' . static::$key);
    }
}
