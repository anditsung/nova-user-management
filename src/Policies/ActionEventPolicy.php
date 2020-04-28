<?php


namespace Tsung\NovaUserManagement\Policies;


class ActionEventPolicy
{
    public function viewAny($user)
    {
        try {
            return $user->hasPermissionTo('view actions');
        } catch (\Exception $exception) {
            return true;
        }
    }

    public function view($user)
    {
        try {
            return $user->hasPermissionTo('view actions');
        } catch (\Exception $exception) {
            return true;
        }
    }
}
