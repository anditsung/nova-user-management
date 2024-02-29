<?php


namespace Tsung\NovaUserManagement\Policies;


use App\Policies\BasePolicy;
use App\User;

class RolePolicy extends BasePolicy
{
    public function __construct()
    {
        $this->uriKey = 'roles';
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
