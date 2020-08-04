<?php


namespace Tsung\NovaUserManagement\Traits;


use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;

trait ResourceOrderById
{
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->when(empty($request->get('orderBy')), function (Builder $query) {
            $query->getQuery()->orders = [];

            return $query->orderBy('id', 'asc');
        });

        return parent::indexQuery($request, $query);
    }
}
