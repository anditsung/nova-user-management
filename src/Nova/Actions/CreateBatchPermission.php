<?php

namespace Tsung\NovaUserManagement\Nova\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Spatie\Permission\PermissionRegistrar;
use Tsung\NovaUserManagement\Models\Permission;

class CreateBatchPermission extends Action
{
    public $standalone = true;

    private array $basePermissions = [
        'viewAny ',
        'view ',
        'create ',
        'update ',
        'replicate ',
        'delete ',
        'restore ',
        'forceDelete ',
    ];

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($this->basePermissions as $permission) {
            $permissionName = $permission . $fields->resource_name;

            Permission::updateOrCreate(
                [
                    'name' => $permissionName,
                    'guard_name' => $fields->guard_name,
                ],
                [
                    'group' => $fields->group,
                    'user_id' => Auth::id(),
                ]
            );
        }
        // update cache agar langsung bisa dipakai
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function fields()
    {
        $guardOptions = collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });

        return [
            Text::make(__('Resource Name'), 'resource_name')
                ->rules([
                    'required',
                    'string',
                    'max:255',
                ]),

            Text::make(__('Group'), 'group')
                ->rules([
                    'required',
                    'string',
                    'max:255',
                ]),

            Select::make(__('Guard Name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardOptions)])
                ->default($guardOptions->first()),
        ];
    }
}
