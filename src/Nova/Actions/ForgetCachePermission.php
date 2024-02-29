<?php

namespace Tsung\NovaUserManagement\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Spatie\Permission\PermissionRegistrar;

class ForgetCachePermission extends Action
{
    public $standalone = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        return Action::message('Permission cache reset');
    }
}
