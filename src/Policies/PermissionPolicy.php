<?php


namespace Tsung\NovaUserManagement\Policies;


class PermissionPolicy extends Policy
{
    public static $key = "permissions";

    /*
     * attach button visible
     */
    public function attachAnyRole($user)
    {
        return $user->hasPermissionTo('attachRole permissions');
    }

    /*
     * able to add and update role
     */
    public function attachRole($user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachRole permissions');
    }

    public function detachRole($user)
    {
        return $user->hasPermissionTo('detachRole permissions');
    }

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
}
