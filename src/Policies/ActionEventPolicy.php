<?php


namespace Tsung\NovaUserManagement\Policies;


use App\Policies\BasePolicy;

class ActionEventPolicy extends BasePolicy
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
