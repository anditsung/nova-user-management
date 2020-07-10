<?php


namespace Tsung\NovaUserManagement\Http\Middleware;


use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use Spatie\Permission\PermissionRegistrar;

class NovauserAuthorize
{
    public function handle(Request $request, $next)
    {
        if(Nova::check($request)) {

            //$this->logonUserStillActive($request);

            $this->forgetCachedPermissions($request);

            return $next($request);
        }
        else {
            // allow user to logout if dont have viewNova permission
            if ($request->getPathInfo() == '/nova/logout') {

                return $next($request);

            }
            // if the user dont have viewNova permissions then redirect to '/'
            // return abort(403);
            return redirect('/');
        }
    }

    /**
     * @param $request
     *
     * this method will check user is still active, if not logout the user,
     * note: nova will redirect the user if dont have viewNova permission but the user still login
     * dont need this cause web user and admin user using the same model
     */
    private function logonUserStillActive($request)
    {
        $user = $request->user();
        if ($user) {
            if( ! $user = auth()->user()->is_active) {
                auth()->logout();
            }
        }
    }

    /**
     * @param $request
     *
     * this method will reset cache for permission after adding
     * laravel nova using cache permission, so it need to be reset before useable
     */
    private function forgetCachedPermissions($request)
    {
        if ( $request->is('nova-api/*/detach') || $request->is('nova-api/*/*/attach*/*') ) {
//            $permissionKey = Nova::resourceForModel(app(PermissionRegistrar::class)->getPermissionClass())::uriKey();
//
//            if ($request->viaRelationship === $permissionKey) {
//                app(PermissionRegistrar::class)->forgetCachedPermissions();
//            }

            /*
             * if the request->viaRelationship is roles / permissions will reset permission cache
             */
            if( $request->viaRelationship === "roles" || $request->viaRelationship === "permissions" ) {
                app(PermissionRegistrar::class)->forgetCachedPermissions();
            }
        }
    }

}
