<?php


namespace Tsung\NovaUserManagement\Nova;


use App\Nova\Resource;
use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use NovaAttachMany\AttachMany;
use Tsung\NovaUserManagement\Models\Role as RoleModel;
use Laravel\Nova\Nova;
use Tsung\NovaUserManagement\Nova\Actions\ForgetCachePermission;
use Tsung\NovaUserManagement\Nova\Filters\Active as ActiveFilter;
use Tsung\NovaUserManagement\Traits\ResourceQueries;

class Role extends Resource
{
    use ResourceQueries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = RoleModel::class;

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
        'name',
    ];

    public static $group = 'User Management';

    public static $displayInNavigation = false;

    public function fieldsForIndex(Request $request): array
    {
        return [
            Text::make(__('Name'), 'name'),
            Select::make(__('Guard Name'), 'guard_name'),
            Boolean::make('Active', 'is_active'),
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

        $userResource = Nova::resourceForModel(getModelForGuard($this->guard_name));

        return [
            Boolean::make('Active', 'is_active')
                ->default(true),

            Text::make(__('Name'), 'name')
                ->rules(['required', 'string', 'max:255'])
                ->creationRules('unique:' . config('permission.table_names.roles'))
                ->updateRules('unique:' . config('permission.table_names.roles') . ',name,{{resourceId}}'),

            Select::make(__('Guard Name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardOptions)])
                ->searchable(),

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

            MorphToMany::make(__('Users'), 'users', User::class)
                ->withSubtitles()
                ->searchable(),

            AttachMany::make(__('Users'), 'users', User::class)
                ->showCounts(),

            BelongsToMany::make(__('Permissions'), 'permissions', Permission::class)
                ->withSubtitles()
                ->searchable(),

            AttachMany::make(__('Permissions'), 'permissions', Permission::class)
                ->showCounts(),

            Text::make(__('Permissions'), function () {
                return $this->permissions->count();
            })
                ->exceptOnForms(),

            Text::make(__('Users'), function () {
                return $this->users->count();
            })->exceptOnForms(),
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
        return [
            ForgetCachePermission::make(),
        ];
    }
}
