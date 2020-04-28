<?php


namespace Tsung\NovaUserManagement\Policies;


class RolePolicy extends Policy
{
    public static $key = 'roles';

    /*
     * attach button visible
     */
    public function attachAnyUser($user)
    {
        return $user->hasPermissionTo('attachUser ' . static::$key);
    }

    /*
     * able to add and update role
     */
    public function attachUser($user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachUser ' . static::$key);
    }

    public function detachUser($user)
    {
        return $user->hasPermissionTo('detachUser ' . static::$key);
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
