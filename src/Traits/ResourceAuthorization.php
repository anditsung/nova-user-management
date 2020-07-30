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

        // jika permission tidak terdaftar return true
        try {
            return $user->hasPermissionTo( $permission_name );
        } catch ( PermissionDoesNotExist $e ) {
            return false;
        }
    }

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation( Request $request )
    {
        return self::hasPermission( $request, 'viewAny ' . parent::uriKey() );
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny( Request $request )
    {
        if ( $request instanceof ResourceIndexRequest ) {

            // jika resource di akses dari resource yang lain
            if ($request->viaResource ) {

                // disini menentukan resource bisa diakses dari tempat lain
                return true;

            }

            // jika resource di akses langsung
            return self::hasPermission($request, 'viewAny ' . parent::uriKey());

        } else if ( $request instanceof ResourceDetailRequest ) {
            // disini menentukan detail bisa dilihat
            // disini menentukan field di resource tersebut bisa dilihat dari resource lain
            if ( self::hasPermission( $request, 'view ' . parent::uriKey() ) ) {

                return true;

            } else {
                // tidak bisa melihat resource yang bukan milik user tersebut

                $model = parent::newModel()->find($request->resourceId);
                if($model) {
                    return true;
                    if ( $model->user_id == $request->user()->id ) {
                        return true;
                    }
                    return true;
                }
                return false;
            }
        }
        // disini menentukan resource bisa diakses dari resource lain
        return true;
        // jika resource di akses langsung
        //return self::hasPermission( $request, 'viewAny ' . parent::uriKey() );
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
//        $user = $request->user();
//
//        if ( self::hasPermission($request, 'viewOwn ' . parent::uriKey()) ) {
//
//            if ( parent::model()->user_id == $user->id ) {
//
//                return true;
//
//            }
//
//            return false;
//        }
//        return self::hasPermission($request, 'viewAny ' . parent::uriKey());
        $user = $request->user();

        // jika user tidak ada permission untuk update
        // maka user tersebut hanya bisa update data yang dia create sendiri
        if( self::hasPermission($request, 'view ' . parent::uriKey()) ) {
            return true;
        } else {
            if ( parent::model()->user_id == $user->id ) {
                return true;
            }
            return false;
        }
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
        $user = $request->user();

        // jika user tidak ada permission untuk update
        // maka user tersebut hanya bisa update data yang dia create sendiri
        if( self::hasPermission( $request, 'update ' . parent::uriKey() ) ) {
            return true;
        } else {
            return self::hasPermission( $request, 'updateOwn ' . parent::uriKey() );
        }
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        $user = $request->user();

        // jika user tidak ada permission untuk update
        // maka user tersebut hanya bisa update data yang dia create sendiri
        if( self::hasPermission($request, 'delete ' . parent::uriKey()) ) {
            return true;
        } else {
            return self::hasPermission( $request, 'deleteOwn ' . parent::uriKey() );
        }
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
