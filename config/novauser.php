<?php

return [
    "resources" => [

        \App\Nova\User::class,
        \Tsung\NovaUserManagement\Nova\Role::class,
        \Tsung\NovaUserManagement\Nova\Permission::class,

    ],

    "models" => [
        "role" => \Tsung\NovaUserManagement\Models\Role::class,
        "permission" => \Tsung\NovaUserManagement\Models\Permission::class,
    ],

    'fields' => [
        'permission-checkbox' => [
            /*
             * list => 1
             * list_dropbox => 2
             */
            'display_type' => 1,
        ]
    ],

    'binds' => [
        'login' => \Tsung\NovaUserManagement\Http\Controllers\Auth\LoginController::class,

        'authorize' => \Tsung\NovaUserManagement\Http\Middleware\NovauserAuthorize::class,
    ],

    /*
     * set true to show actions resource on navigation
     */
    'show-actions' => false,
];
