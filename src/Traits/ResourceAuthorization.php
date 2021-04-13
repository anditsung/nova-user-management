<?php


namespace Tsung\NovaUserManagement\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

trait ResourceAuthorization
{

    private static function hasPermission( Request $request, $permission_name )
    {
        $user = $request->user();

        // jika user adalah administrator maka semua permission selalu true
        if( $user->administrator() ) {
            return true;
        }

        // jika permission tidak terdaftar
        try {
            return $user->hasPermissionTo( $permission_name );
        } catch ( PermissionDoesNotExist $e ) {
            return false;
        }
    }

//    /**
//     * Determine if this resource is available for navigation.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return bool
//     */
//    public static function availableForNavigation( Request $request )
//    {
//        if ( static::$displayInNavigation ) {
//            return self::hasPermission( $request, 'viewAny ' . parent::uriKey() );
//        }
//        return false;
//    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny( Request $request )
    {
        if ( $request instanceof ResourceIndexRequest ) {

            if ( $request->resource === self::uriKey() ) {

                if ( $request->viaResource ) {

                    return true;

                }

                return self::hasPermission($request, 'viewAny ' . self::uriKey());

            }
            /**
             * return true jika ingin selalu tampilkan field di index walau user tersebut tidak ada akses ke resource yang dituju
             * tp tidak ada link ke resource tersebut jika tidak view permission untuk resource tersebut
             */
            return self::hasPermission($request, 'view ' . self::uriKey());

        }
        else if ( $request instanceof ResourceDetailRequest ) {

            if ( $request->resource === self::uriKey() ) {

                return self::hasPermission($request, 'view ' . self::uriKey());

            }
            /**
             * return true jika ingin selalu tampilkan field di index walau user tersebut tidak ada akses ke resource yang dituju
             * dan tidak ada link ke resource tersebut
             */
            return self::hasPermission($request, 'view ' . self::uriKey())
                || self::hasPermission($request, 'viewAny ' . self::uriKey());

        }
        /** REQUEST FOR LENS SCOPE SEARCH AND ACTIONS */
        else if ( $request instanceof NovaRequest ) {

            /**
             * lens, scope, search, actions
             */
            if ($request->resource) {

                return true;

            }
            // IF THE REQUEST IS SEARCH AND THE USER HAVE PERMISSION FOR RESOURCE THEN DO SEARCH
            else if ($request->segment(2) === 'search') { // /nova-api/search

                return self::hasPermission($request, 'viewAny ' . self::uriKey());

            }
        }
        /** IF THE USER HAVE PERMISSION VIEWANY FOR RESOURCE THEN SHOW ON SIDEBAR  */
        else if ( $request instanceof Request ) {

            return self::hasPermission($request, 'viewAny ' . self::uriKey());

        }
    }

    private function hasOwnPermission( Request $request, $permission_name )
    {
        if ( config('novauser.own-permission') ) {
            if ( self::hasPermission( $request, $permission_name ) ) {
                return true;
            } else {
                if ( self::model()->user_id == $request->user()->id) {
                    return true;
                }
                return false;
            }
        }
        return self::hasPermission($request, $permission_name);
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return $this->hasOwnPermission($request, 'view ' . self::uriKey());
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return self::hasPermission($request, 'create ' . self::uriKey());
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $this->hasOwnPermission($request, 'update ' . self::uriKey());
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $this->hasOwnPermission($request, 'delete ' . self::uriKey());
    }

    /**
     * Determine if the user can attach any models of the given type to the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @return bool
     */
    public function authorizedToAttachAny(NovaRequest $request, $model)
    {
        $methodName = "attach" . Str::singular(class_basename($model));

        if ( method_exists($this, $methodName) ) {

            return $this->{$methodName};

        }

        $permission_name = 'attach'.Str::singular(class_basename($model)) . " " . self::uriKey();

        return self::hasPermission($request, $permission_name);
    }

    /**
     * Determine if the user can detach models of the given type to the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  string  $relationship
     * @return bool
     */
    public function authorizedToDetach(NovaRequest $request, $model, $relationship)
    {
        $methodName = "detach" . Str::singular(class_basename($model));

        if ( method_exists($this, $methodName) ) {

            return $this->{$methodName};

        }

        $permission_name = 'detach'.Str::singular(class_basename($model)) . " " . self::uriKey();

        return self::hasPermission($request, $permission_name);
    }
}
