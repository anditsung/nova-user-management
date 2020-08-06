#User Management for Laravel Nova

##Install guide

Install Laravel
```
composer create-project laravel/laravel=7 [PROJECT_NAME]
```

Install Laravel Nova
```
composer config repositories.nova path [NOVA_PATH]
composer require laravel/nova
php artisan nova:install
```

Install User Management
if install using folder run this first
```
composer config repositories.nova-user-management path [PACKAGE_PATH]
```
```
composer require tsung/nova-user-management
php artisan migrate
php artisan novauser:install
php artisan novauser:init
```
Done

====================

>already add function to patch this when execute install command ( 13-05-2020 )

On production env, need to change gate method on NovaServiceProvider
this method will check if the user can access nova, on this method cannot use "can" but "hasPermissionTo" will work fine
```
Gate::define('viewNova', function($user) {
    return $user->hasPermissionTo('viewNova');
});
```

====================

preview

User Index
![user index](https://github.com/anditsung/nova-user-management/blob/nova2/preview/user-index.png?raw=true)

User Form
![user form](https://github.com/anditsung/nova-user-management/blob/nova2/preview/user-form.png?raw=true)

Role Index
![role index](https://github.com/anditsung/nova-user-management/blob/nova2/preview/role-index.png?raw=true)

Role Form
![role form](https://github.com/anditsung/nova-user-management/blob/nova2/preview/role-form.png?raw=true)

Role Detail
![role detail](https://github.com/anditsung/nova-user-management/blob/nova2/preview/role-detail.png?raw=true)

Permission Index
![permission index](https://github.com/anditsung/nova-user-management/blob/nova2/preview/permission-index.png?raw=true)
