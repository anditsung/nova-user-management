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

//        \Debugbar::info(">>>>>>>>>>>>");
//        \Debugbar::info($permission_name);

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
        /**
         * jika $request instanceof ResourceIndexRequest
         * berarti resource awal sedang mengakses request lain agar bisa ditampilkan di detail resource tersebut
         *
         * jika $request instanceof ResourceDetailRequest
         * ini yang menentukan field yang bisa ditampilkan pada detail view pada resource tersebut
         *
         * jika $request intanceof NovaRequest
         * disini menentukan resource tersebut bisa diakses
         */

        if ( $request instanceof ResourceIndexRequest ) {
//            \Debugbar::info("RESOURCE INDEX REQUEST");
//            \Debugbar::info(parent::uriKey());
//            return true;

            if ( $request->resource === parent::uriKey() ) {

                if ( $request->viaResource ) {

                    return true;

                }

                return self::hasPermission($request, 'viewAny ' . parent::uriKey());

            }
            /**
             * return true jika ingin selalu tampilkan field di index walau user tersebut tidak ada akses ke resource yang dituju
             * tp tidak ada link ke resource tersebut jika tidak view permission untuk resource tersebut
             */
            return self::hasPermission($request, 'view ' . parent::uriKey());

        } else if ( $request instanceof ResourceDetailRequest ) {
//            \Debugbar::info("RESOURCE DETAIL REQUEST");
//            \Debugbar::info(parent::uriKey());
//            return true;

            if ( $request->resource === parent::uriKey() ) {

                return self::hasPermission($request, 'view ' . parent::uriKey());

            }
            /**
             * return true jika ingin selalu tampilkan field di index walau user tersebut tidak ada akses ke resource yang dituju
             * dan tidak ada link ke resource tersebut
             */
            return self::hasPermission($request, 'view ' . parent::uriKey())
                || self::hasPermission($request, 'viewAny ' . parent::uriKey());

        } else if ( $request instanceof NovaRequest ) {

            /**
             * lens, scope, search, actions
             */
            if ($request->resource) {

                return true;

            } else if ($request->segment(2) === 'search') { // /nova-api/search

                return self::hasPermission($request, 'viewAny ' . parent::uriKey());

            }
        } else if ( $request instanceof Request ) {

            /**
             * disini mementukan resource tersebut bisa tampil di sidebar laravel nova
             */
            return self::hasPermission($request, 'viewAny ' . parent::uriKey());

        }
    }

    private function hasOwnPermission( Request $request, $permission_name )
    {
        if ( config('novauser.own-permission') ) {
            if ( self::hasPermission( $request, $permission_name ) ) {
                return true;
            } else {
                if ( parent::model()->user_id == $request->user()->id) {
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
        return $this->hasOwnPermission($request, 'view ' . parent::uriKey());
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return self::hasPermission($request, 'create ' . parent::uriKey());
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $this->hasOwnPermission($request, 'update ' . parent::uriKey());
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $this->hasOwnPermission($request, 'delete ' . parent::uriKey());
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
        $permission_name = 'attach'.Str::singular(class_basename($model)) . " " . parent::uriKey();

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
        $permission_name = 'detach'.Str::singular(class_basename($model)) . " " . parent::uriKey();

        return self::hasPermission($request, $permission_name);
    }
}
