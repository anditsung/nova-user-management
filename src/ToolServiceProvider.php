<?php

namespace Tsung\NovaUserManagement;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Actions\ActionEvent;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Tsung\NovaUserManagement\Commands\Init;
use Tsung\NovaUserManagement\Commands\Install;
use Tsung\NovaUserManagement\Http\Middleware\Authorize;
use Tsung\NovaUserManagement\Models\Permission;
use Tsung\NovaUserManagement\Models\Role;
use Tsung\NovaUserManagement\Policies\ActionEventPolicy;
use Tsung\NovaUserManagement\Policies\PermissionPolicy;
use Tsung\NovaUserManagement\Policies\RolePolicy;
use Tsung\NovaUserManagement\Policies\UserPolicy;

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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-user-management');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            $this->registerPolicies();
            Nova::tools($this->registerTools());
        });
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config' => config_path('/'),
        ], 'novauser-config');
    }

    public function registerPolicies()
    {
        // administrator always have all permissions
        Gate::before( function($user) {
            if($user->administrator()) {
                return true;
            }
        });

        Gate::policy(config('novauser.gates.action.model'), config('novauser.gates.action.policy'));
        Gate::policy(config('novauser.gates.user.model'), config('novauser.gates.user.policy'));
        Gate::policy(config('novauser.gates.role.model'), config('novauser.gates.role.policy'));
        Gate::policy(config('novauser.gates.permission.model'), config('novauser.gates.permission.policy'));
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
