<?php


namespace Tsung\NovaUserManagement\Policies;


use App\Policies\BasePolicy;
use App\User;

class PermissionPolicy extends BasePolicy
{
    public function __construct()
    {
        $this->uriKey = 'permissions';
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

    /*
     * attach button visible
     */
    public function attachAnyUser(User $user)
    {
        return $user->hasPermissionTo('attachUser ' . $this->uriKey);
    }

    /*
     * able to add and update role
     */
    public function attachUser(User $user)
    {
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachUser ' . $this->uriKey);
    }

    public function detachUser(User $user)
    {
        return $user->hasPermissionTo('detachUser ' . $this->uriKey);
    }
}
