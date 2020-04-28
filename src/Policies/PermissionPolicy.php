<?php


namespace Tsung\NovaUserManagement\Policies;


use App\User;

class PermissionPolicy extends Policy
{
    public static $key = "permissions";

    /*
     * attach button visible
     */
    public function attachAnyRole(User $user)
    {
        return $user->hasPermissionTo('attachRole permissions');
    }

    /*
     * able to add and update role
     */
    public function attachRole(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachRole permissions');
    }

    public function detachRole(User $user)
    {
        return $user->hasPermissionTo('detachRole permissions');
    }

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
}
