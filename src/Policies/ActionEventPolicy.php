<?php


namespace Tsung\NovaUserManagement\Policies;


use App\User;

class ActionEventPolicy
{
    public function viewAny(User $user)
    {
        try {
            return $user->hasPermissionTo('view actions');
        } catch (\Exception $exception) {
            return true;
        }
    }

    public function view(User $user)
    {
        try {
            return $user->hasPermissionTo('view actions');
        } catch (\Exception $exception) {
            return true;
        }
    }
}
