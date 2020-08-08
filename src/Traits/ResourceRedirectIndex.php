<?php


namespace Tsung\NovaUserManagement\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

trait ResourceRedirectIndex
{
    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        if ( $request->viaResource ) {

            return '/resources/' . $request->viaResource . '/' . $request->viaResourceId;

        }

        $model = $request->findModelOrFail();

        $morphModel = $model->{$resource::uriKey()};

        if ( $morphModel instanceof Model ) {

            $morphResource = Nova::resourceForModel($morphModel);

            return '/resources/' . $morphResource::uriKey() . '/' . $morphModel->id;
        }

        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        if ( $request->viaResource ) {

            return '/resources/' . $request->viaResource . '/' . $request->viaResourceId;

        }

        $model = $request->findModelOrFail();

        $morphModel = $model->{$resource::uriKey()};

        if ( $morphModel instanceof Model ) {

            $morphResource = Nova::resourceForModel($morphModel);

            return '/resources/' . $morphResource::uriKey() . '/' . $morphModel->id;
        }

        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after deletion.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return string
     */
    public static function redirectAfterDelete(NovaRequest $request, $deletedModel)
    {
        $morphKey = Str::singular(static::uriKey());

        $morphType = "{$morphKey}_type";
        $morphId = "{$morphKey}_id";

        if ( $deletedModel->{$morphType} ) {

            $morphModel = $deletedModel->{$morphType};

            $morphResource = Nova::resourceForModel($morphModel);

            return '/resources/' . $morphResource::uriKey() . '/' . $deletedModel->{$morphId};

        }

        if ( $request->viaResource ) {

            return '/resources/' . $request->viaResource . '/' . $request->viaResourceId;

        }
        return '/resources/'.static::uriKey();
    }
}
