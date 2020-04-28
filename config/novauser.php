<?php

return [
    "resources" => [

        \App\Nova\User::class,
        \Tsung\NovaUserManagement\Nova\Role::class,
        \Tsung\NovaUserManagement\Nova\Permission::class,

    ],

    "gates" => [

        "action" => [

            "model" => \Laravel\Nova\Actions\ActionEvent::class,
            "policy" => \Tsung\NovaUserManagement\Policies\ActionEventPolicy::class,

        ],

        "user" => [

            "model" => config('auth.providers.users.model'),
            "policy" => \Tsung\NovaUserManagement\Policies\UserPolicy::class,

        ],

        "role" => [

            "model" => \Tsung\NovaUserManagement\Models\Role::class,
            "policy" => \Tsung\NovaUserManagement\Policies\RolePolicy::class,

        ],

        "permission" => [

            "model" => \Tsung\NovaUserManagement\Models\Permission::class,
            "policy" => \Tsung\NovaUserManagement\Policies\PermissionPolicy::class,

        ]

    ],

    'fields' => [
        'permission-checkbox' => [
            /*
             * list => 1
             * list_dropbox => 2
             */
            'display_type' => 1,
        ]
    ]
];
