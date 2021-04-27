<?php

namespace App\Nova;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tsung\NovaMaster\Nova\Company;
use Tsung\NovaUserManagement\Nova\Filters\Active as ActiveFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Tsung\NovaUserManagement\Nova\Role;
use Tsung\NovaUserManagement\Nova\Permission;
use Tsung\NovaUserManagement\Traits\ResourceAuthorization;

class User extends Resource
{
    use ResourceAuthorization;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email',
    ];

    public static $group = 'User Management';

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            Text::make(__('Name')),
            Text::make(__('Email')),
            Boolean::make(__('Active'), 'is_active'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Boolean::make(__('Active'), 'is_active')
                ->default(true),

            Text::make(__('Name'))
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('Email'))
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make(__('Password'))
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            Hidden::make(__('User'), 'user_id')
                ->default($request->user()->id),

            DateTime::make(__('Created At'))
                ->format('DD MMMM Y, hh:mm:ss A')
                ->onlyOnDetail(),

            DateTime::make(__('Updated At'))
                ->format('DD MMMM Y, hh:mm:ss A')
                ->onlyOnDetail(),

            BelongsTo::make(__('Created By'), 'user', User::class)
                ->onlyOnDetail(),

            /** RELATION */

            BelongsToMany::make(__('Company'), 'companies', Company::class),

            MorphToMany::make(__('Roles'), 'roles', Role::class ),

            MorphToMany::make(__('Permissions'), 'permissions', Permission::class)
                ->searchable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            (new ActiveFilter),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    // fungsi ini akan menghilangkan tombol edit attached dan ada di attach bisa terisi dengan data
    public function authorizedToAttach(NovaRequest $request, $model)
    {
        if ($request->viaResource) {
            return false;
        }

        if (! static::authorizable()) {
            return true;
        }

        $method = 'attach'.Str::singular(class_basename($model));

        return method_exists(Gate::getPolicyFor($this->model()), $method)
            ? Gate::check($method, [$this->model(), $model])
            : true;
    }
}
