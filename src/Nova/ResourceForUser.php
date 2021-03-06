<?php


namespace Tsung\NovaUserManagement\Nova;


use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Http\Requests\NovaRequest;

abstract class ResourceForUser extends NovaResource
{
    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder   $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        // if the item access from viaResource show the data
        if($request->viaResource) {
            return $query;
        }
        else if ( $request->is('nova-api/*/detach') || $request->is('nova-api/*/*/attach*/*') ) {
            return $query;
        }
        else {
            if ($user->administrator()) {

                return parent::relatableQuery($request, $query);
            }

            if ($user->hasPermissionTo('view ' . parent::uriKey())) {

                return parent::relatableQuery($request, $query);
            }

            return parent::relatableQuery($request, $query->where('user_id', $user->id));
        }
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder   $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        // if the item access from viaResource show the data
        if($request->viaResource) {
            return $query;
        }
        else if ( $request->is('nova-api/*/detach') || $request->is('nova-api/*/*/attach*/*') ) {
            return $query;
        }
        else {
            if ($user->administrator()) {

                return parent::relatableQuery($request, $query);
            }

            if ($user->hasPermissionTo('view ' . parent::uriKey())) {
                return parent::relatableQuery($request, $query);
            }

            return parent::relatableQuery($request, $query->where('user_id', $user->id));
        }
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder   $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return $query;
        $user = $request->user();

        if ($user->administrator()) {

            //$query = $query->where('is_active', true);
            return parent::relatableQuery($request, $query);
        }

        if ($user->hasPermissionTo('view ' . parent::uriKey())) {
            return parent::relatableQuery($request, $query);
        }

        return parent::relatableQuery($request, $query->where('user_id', $user->id));
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Laravel\Scout\Builder                  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        if ($user->administrator()) {
            return parent::relatableQuery($request, $query);
        }

        if ($user->hasPermissionTo('view ' . parent::uriKey())) {
            return parent::relatableQuery($request, $query);
        }

        return parent::relatableQuery($request, $query->where('user_id', $user->id));
    }
}
