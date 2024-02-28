<?php

namespace Tsung\NovaUserManagement;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Controllers\LoginController as NovaLoginController;
use Laravel\Nova\Nova;
use Tsung\NovaUserManagement\Commands\Init;
use Tsung\NovaUserManagement\Commands\Install;
use Tsung\NovaUserManagement\Http\Middleware\Authorize;
use Laravel\Nova\Http\Middleware\Authorize as NovaAuthorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        // add this so the app can path defined for nova
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-user-management');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // this will bind nova login controller to our login controller to add some options
        $this->app->bind(NovaLoginController::class, config('novauser.binds.login'));
        // this will bind nova authorize to custom authorize
        $this->app->bind(NovaAuthorize::class, config('novauser.binds.authorize'));

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::tools($this->registerTools());
        });
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config' => config_path('/'),
        ], 'novauser-config');
    }

    protected function registerTools()
    {
        return [
            new NovaUserManagement()
        ];
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/nova-user-management')
                ->group(__DIR__.'/../routes/api.php');

        /**
         * mendaftarkan api ke root directory
         * localhost/api/
         */
        Route::prefix('api')
            ->middleware('api')
            ->group(__DIR__ . '/../routes/root-api.php');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Install::class,
            Init::class,
        ]);
    }
}
