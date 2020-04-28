<?php

namespace Tsung\NovaUserManagement\Fields;


use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tsung\NovaUserManagement\Models\Permission;

class PermissionCheckbox extends Field
{
    public $component = 'permission-checkbox-field';

    public static $LIST = 1;
    public static $LIST_DROPDOWN = 2;
    public static $DROPDOWN = 3;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->showOnIndex(false);
        $this->allPermissions();
        $this->displayType(2);
    }

    public function displayType($displayType)
    {
        return $this->withMeta(['displayType' => $displayType]);
    }

    private function allPermissions()
    {
        $allPermissions = Permission::all()->map(function ($permission) {
            return [
                'group' => $permission->group,
                'name' => $permission->name,
                'label' => explode(' ', $permission->name)[0],
            ];
        })->groupBy('group');
        return $this->withMeta(['permissions' => $allPermissions]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {

            $value = $request[$requestAttribute];

            /*
             * requestAttribute value will always return comma separated value.
             */
            if (!is_array($value)) {
                $value = collect(explode(',', $value));
            }

            /*
             * sync permissions to model
             * but will not be recorded on ActionEvent
             */
            $model->syncPermissions($value);
        }
    }
}
