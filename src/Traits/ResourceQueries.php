<?php


namespace Tsung\NovaUserManagement\Traits;


use Laravel\Nova\Http\Requests\NovaRequest;

trait ResourceQueries
{

    private static function defaultQuery( NovaRequest $request, $query )
    {
        $user = $request->user();

        if ( $request->viaResource ) {

            return $query;

        } else if ( $request->is( 'nova-api/*/detach' )
            || $request->is( 'nova-api/*/*/attach*/*' )
        ) {

            $model = $request->findModelOrFail();
            // cari model yang sudah di attach ke resource ini
            $currentAttach = collect($model->{parent::uriKey()})->map(function($value) {
                return $value->id;
            });
            // filter query dari model yang sudah di attach
            return $query->whereNotIn('id', $currentAttach);


        } else {

            if ( $user->administrator() ) {

                return $query;

            }

            if ( $user->can('view ' . parent::uriKey() ) ) {

                return $query;

            }

            return $query->where('user_id', $user->id);
        }
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder   $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery( NovaRequest $request, $query )
    {
        return self::defaultQuery($request, $query);
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
        return self::defaultQuery($request, $query);
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
        $user = $request->user();

        if ( $user->administrator() ) {

            return $query;

        }

        if ( $user->can('view ' . parent::uriKey() ) ) {

            return $query;

        }

        return $query->where('user_id', $user->id);
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

        if ( $user->administrator() ) {

            return $query;

        }

        if ( $user->can('view ' . parent::uriKey() ) ) {

            return $query;

        }

        return $query->where('user_id', $user->id);
    }
}
