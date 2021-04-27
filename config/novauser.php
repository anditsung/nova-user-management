<?php

return [
    "resources" => [

        \App\Nova\User::class,
        \Tsung\NovaUserManagement\Nova\Role::class,
        \Tsung\NovaUserManagement\Nova\Permission::class,
        \Tsung\NovaUserManagement\Nova\Address::class,
        \Tsung\NovaUserManagement\Nova\Bank::class,
        \Tsung\NovaUserManagement\Nova\Configuration::class,
        \Tsung\NovaUserManagement\Nova\Document::class,
        \Tsung\NovaUserManagement\Nova\Holiday::class,
        \Tsung\NovaUserManagement\Nova\Note::class,
        \Tsung\NovaUserManagement\Nova\Phone::class,
        \Tsung\NovaUserManagement\Nova\Unit::class,
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

    /*
     * set have own permissions
     */
    'own-permission' => false,

    /**
     * config for resource document
     */
    "document" => [

        "morph" => [
            //\Tsung\NovaHumanResource\Nova\Person::class,
            //\Tsung\NovaHumanResource\Nova\Employee::class,
        ],

        'accepted_type' => [
            'image/apng',
            'image/bmp',
            'image/x-ms-bmp',
            'image/gif',
            'image/x-icon',
            'image/jpeg',
            'image/png',
            'image/svg+xml',
            'image/tiff',
            'image/webp',
            //'application/pdf',
        ],
    ],

    /**
     * config for resource note
     */
    'note' => [

        'morph' => [
            //\Tsung\NovaHumanResource\Nova\Person::class,
            //\Tsung\NovaHumanResource\Nova\Employee::class,
        ],
    ],

    /**
     * config for resource phone
     */
    'phone' => [

        'morph' => [
            //\Tsung\NovaHumanResource\Nova\Person::class,
            //\Tsung\NovaHumanResource\Nova\Employee::class,
        ],

        'types' => [
            1 => 'HOME',
            2 => 'OFFICE',
            3 => 'MOBILE',
        ],
    ],

    /**
     * config for resource bank
     */
    'bank' => [

        'morph' => [
            //\Tsung\NovaHumanResource\Nova\Person::class,
        ],
    ],

    /**
     * config for resource address
     */
    'address' => [

        'morph' => [
            //\Tsung\NovaHumanResource\Nova\Person::class,
        ],

        'types' => [
            1 => 'HOME',
            2 => 'OFFICE',
            3 => 'BRANCH',
        ]
    ],

];
