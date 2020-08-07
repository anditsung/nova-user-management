<?php


namespace Tsung\NovaUserManagement\Traits;


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

        if ( $morphModel ) {

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
        /**
         * install laravel
        composer create-project laravel/laravel=7

        install laravel nova
        composer config repositories.nova path [PACKAGE_PATH]
        composer require laravel/nova

        install nova-user-management
        composer config repositories.nova-user-management path [PACKAGE_PATH]
        composer require tsung/nova-user-management

        php artisan migrate
        php artisan nova:install
        php artisan novaweb:install
        php artisan novauser:install
        php artisan novauser:init

        install nova-master
        composer config repositories.nova-master path [PACKAGE_PATH]
        composer require tsung/nova-master

        php artisan migrate
        php artisan novamaster:install
         *
         *
         */

        if ( $request->viaResource ) {

            return '/resources/' . $request->viaResource . '/' . $request->viaResourceId;

        }

        $model = $request->findModelOrFail();

        $morphModel = $model->{$resource::uriKey()};

        if ( $morphModel ) {

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
    public static function redirectAfterDelete(NovaRequest $request)
    {
        return '/resources/'.static::uriKey();
    }
}
