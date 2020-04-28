<?php


namespace Tsung\NovaUserManagement\Policies;


use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    /*
     * User able to access the index
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('viewAny ' . static::$key);
    }

    /*
     * User able to view detail
     */
    public function view(User $user, $model)
    {
        if( $user->hasPermissionTo('view ' . static::$key) ) {
            return true;
        }

        return $user->id == $model->user_id;
    }

    /*
     * User able to create
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create ' . static::$key);
    }

    /*
     * User able to update
     */
    public function update(User $user, $model)
    {
        if( $user->hasPermissionTo('update ' . static::$key) ) {
            return true;
        }

        return false;
    }

    /*
     * User able to delete
     */
    public function delete(User $user, $model)
    {
        if( $user->hasPermissionTo('delete ' . static::$key) ) {
            return true;
        }

        return false;
    }

    /*
     * User able to restore
     */
    public function restore(User $user, $model)
    {

        if( $user->hasPermissionTo('restore ' . static::$key) ) {
            return true;
        }

        return false;
    }

    /*
     * User able to force delete
     */
    public function forceDelete(User $user, $model)
    {
        if( $user->hasPermissionTo('forceDelete ' . static::$key) ) {
            return true;
        }

        return false;
    }

    /*
     * if this model have comment and need policy for it
     * change ModelName to Model want to be add
     */
    public function addModelName(User $user, $model)
    {
        return true;
    }

    /*
     * if this model can attach comment
     * change ModelName to Model want to be attach
     */
    public function attachModelName(User $user)
    {
        // hide edit / update when access from viaResource
        if(request()->request->get('viaResource')) {
            return false;
        }
        return $user->hasPermissionTo('attachRole ' . static::$key);
    }

    /*
     * if this model can detach comment,
     * change ModelName to Model want to be detach
     */
    public function detachModelName(User $user)
    {
        return $user->hasPermissionTo('detachUser ' . static::$key);
    }

    /*
     *  this will prevent attach button displaying in the nova UI
     * change ModelName to Model that user cannot see attach button
     */
    public function attachAnyModelName(User $user, $model)
    {
        return $user->hasPermissionTo('attachRole ' . static::$key);
    }
}
