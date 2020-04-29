<?php


namespace Tsung\NovaUserManagement\Http\Middleware;


use Laravel\Nova\Nova;

class CustomAuthorize
{
    public function handle($request, $next)
    {
        // if the user dont have viewNova permissions then redirect to '/'
        //return Nova::check($request) ? $next($request) : abort(404);
        return Nova::check($request) ? $next($request) : redirect('/');
    }

}
