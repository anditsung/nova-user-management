#User Management for Laravel Nova

##Install guide

Install Laravel
```
composer create-project laravel/laravel=6 [PROJECT_NAME]
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

preview

User Index
![user index](https://github.com/anditsung/nova-user-management/blob/master/preview/user-index.png?raw=true)

User Form
![user form](https://github.com/anditsung/nova-user-management/blob/master/preview/user-form.png?raw=true)

Role Index
![role index](https://github.com/anditsung/nova-user-management/blob/master/preview/role-index.png?raw=true)

Role Form
![role form](https://github.com/anditsung/nova-user-management/blob/master/preview/role-form.png?raw=true)

Role Detail
![role detail](https://github.com/anditsung/nova-user-management/blob/master/preview/role-detail.png?raw=true)

Permission Index
![permission index](https://github.com/anditsung/nova-user-management/blob/master/preview/permission-index.png?raw=true)
