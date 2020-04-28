#User Management for Laravel Nova

##Install guide
Install Laravel
>composer create-project laravel/laravel=6 [PROJECT_NAME]

Install Laravel Nova
>composer config repositories.nova path [NOVA_PATH]
>composer require laravel/nova
>php artisan nova:install

Install User Management
>composer require tsung/nova-user-management
>php artisan migrate
>php artisan novauser:install
>php artisan novauser:init

Done
