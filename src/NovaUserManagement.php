<?php

namespace Tsung\NovaUserManagement;

use App\Nova\User;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Tsung\NovaUserManagement\Nova\Permission;
use Tsung\NovaUserManagement\Nova\Role;

class NovaUserManagement extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-user-management', __DIR__.'/../dist/js/tool.js');
        Nova::style('nova-user-management', __DIR__.'/../dist/css/tool.css');

        Nova::resources([
            User::class,
            Role::class,
            Permission::class,
        ]);
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-user-management::navigation');
    }
}
