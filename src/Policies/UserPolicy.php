<?php


namespace Tsung\NovaUserManagement\Policies;


use App\Policies\BasePolicy;
use App\User;

class UserPolicy extends BasePolicy
{
    public function __construct()
    {
        $this->uriKey = 'users';
    }

    /*
     * attach button visible
     */
    public function attachAnyRole(User $user)
    {
        return $user->hasPermissionTo('attachRole ' . $this->uriKey);
    }

    /*
     * able to add and update role
     */
    public function attachRole(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachRole ' . $this->uriKey);
    }

    public function detachRole(User $user)
    {
        return $user->hasPermissionTo('detachRole ' . $this->uriKey);
    }

    public function attachAnyPermission(User $user)
    {
        return $user->hasPermissionTo('attachPermission ' . $this->uriKey);
    }

    public function attachPermission(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachPermission ' . $this->uriKey);
    }

    public function detachPermission(User $user)
    {
        return $user->hasPermissionTo('detachPermission ' . $this->uriKey);
    }
}
