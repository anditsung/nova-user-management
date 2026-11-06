<?php


namespace Tsung\NovaUserManagement\Nova;


use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use NovaAttachMany\AttachMany;
use Tsung\NovaUserManagement\Models\Permission as PermissionModel;
use Tsung\NovaUserManagement\Nova\Actions\CreateBatchPermission;
use Tsung\NovaUserManagement\Nova\Actions\ForgetCachePermission;
use Tsung\NovaUserManagement\Traits\ResourceRedirectIndex;

class Permission extends Resource
{
    use ResourceRedirectIndex;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = PermissionModel::class;

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
        'name', 'group'
    ];

    public static $group = 'User Management';

    public static $displayInNavigation = false;

    public function fieldsForIndex(Request $request): array
    {
        return [
            Text::make(__('Name'), 'name'),
            Select::make(__('Guard Name'), 'guard_name'),
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
        $guardOptions = collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });

        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->rules(['required', 'string', 'max:255'])
                ->creationRules('unique:' . config('permission.table_names.permissions'))
                ->updateRules('unique:' . config('permission.table_names.permissions') . ',name,{{resourceId}}'),

            Text::make(__('Group'), 'group')
                ->rules([
                    'required',
                    'max:255',
                    'string',
                ]),

            Select::make(__('Guard Name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardOptions)]),

            Hidden::make('User', 'user_id')->default(function($request) {
                return $request->user()->id;
            }),

            DateTime::make('Created At')
                ->format('DD MMMM Y, hh:mm:ss A')
                ->onlyOnDetail(),

            DateTime::make('Updated At')
                ->format('DD MMMM Y, hh:mm:ss A')
                ->onlyOnDetail(),

            BelongsTo::make(__('Created By'), 'user', User::class)
                ->onlyOnDetail(),

            Text::make(__('Roles'), function () {
                return $this->roles->count();
            })
                ->exceptOnForms(),

            Text::make(__('Users'), function () {
                return $this->users->count();
            })->exceptOnForms(),

            MorphToMany::make(__('Users'), 'users', User::class)
                ->withSubtitles()
                ->searchable(),

            AttachMany::make(__('Users'), 'users', User::class)
                ->showCounts(),

            BelongsToMany::make(__('Roles'), 'roles', Role::class)
                ->withSubtitles()
                ->searchable(),

            AttachMany::make(__('Roles'), 'roles', Role::class)
                ->showCounts(),
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
        return [
            CreateBatchPermission::make(),
            ForgetCachePermission::make(),
        ];
    }
}
