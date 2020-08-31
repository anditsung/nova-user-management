<?php


namespace Tsung\NovaUserManagement\Policies;


class ActionEventPolicy
{
    public function viewAny($user)
    {
        return $user->hasPermissionTo('viewAny actions');
    }

    public function view($user, $model)
    {
        if( $user->hasPermissionTo('view actions') ) {
            return true;
        }

        return $user->id == $model->user_id;
    }
}
