<?php

namespace Tsung\NovaUserManagement\Nova;

use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tsung\NovaUserManagement\Traits\ResourceAuthorization;
use Tsung\NovaUserManagement\Traits\ResourceQueries;
use Tsung\NovaUserManagement\Traits\ResourceRedirectIndex;

class Unit extends Resource
{
    use ResourceAuthorization,
        ResourceRedirectIndex;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Tsung\NovaUserManagement\Models\Unit::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $group = "Master";

    public static $globallySearchable = false;

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            Text::make(__('Name')),

            Text::make(__('ABBR')),

            Boolean::make(__('Active'), 'is_active')
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
                ->rules('required'),

            Text::make(__('ABBR'))
                ->rules('required')
                ->creationRules('unique:master_units,abbr')
                ->updateRules('unique:master_units,abbr,{{resourceId}}'),

            Hidden::make('user_id')
                ->default($request->user()->id),

            DateTime::make('Created At')
                ->format('DD MMMM Y, hh:mm:ss A')
                ->onlyOnDetail(),

            DateTime::make('Updated At')
                ->format('DD MMMM Y, hh:mm:ss A')
                ->onlyOnDetail(),

            BelongsTo::make('Created By', 'user', User::class)
                ->onlyOnDetail(),
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
        return [];
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
}
