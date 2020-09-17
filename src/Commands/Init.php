<?php


namespace Tsung\NovaUserManagement\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Init extends Command
{
    protected $signature = 'novauser:init';

    protected $description = 'create user, role and permission data for first use';

    public function handle()
    {
        $guard = config('nova.guard') ?: config('auth.defaults.guard');
        $userModel = config('auth.providers.users.model');
        $roleModel = config('novauser.models.role');
        $permissionModel = config('novauser.models.permission');

        $this->info($roleModel);

        $user = $userModel::first();

        if (!$user) {
            $this->info("Create User");
            $user = $this->createUser($userModel);
            $this->info("Done");
        }

        $this->info("Create administrator Role");
        $role = $this->createAdministratorRole($user, $guard, $roleModel);
        $this->info("Done");

        $this->info("Create default permissions");
        $this->initDefaultPermissions($user, $permissionModel, $guard);
        $this->info("Done");

        $this->info("Set default permissions to {$role->name}");
        $role->givePermissionTo($permissionModel::all());
        $this->info("Done");

        $this->info("Set {$role->name} to {$user->name}");
        $user->assignRole($role);
        $this->info("Done");

        $this->info("Finish");
    }

    private function createAdministratorRole($user, $guard, $roleModel)
    {
        $role = $roleModel::where('name', 'administrator')->first();
        if(!$role) {
            $role = $roleModel::create([
                'name' => 'administrator',
                'guard_name' => $guard,
                'is_active' => true,
                'user_id' => $user->id,
            ]);
        }
        return $role;
    }

    private function createUser($userModel)
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email Address');
        $password = $this->ask('Password');
        $user = $userModel::where('email', $email)->first();
        if(!$user) {
            $user = $userModel::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'user_id' => 1,
                'is_active' => true,
            ]);
        }
        return $user;
    }

    private function initDefaultPermissions($user, $permissionModel, $guard)
    {
        $resources = config('novauser.resources');

        $systemPermissions = $this->systemPermissions();
        foreach($systemPermissions as $name => $group) {
            $this->addPermissions($user, $permissionModel, $name, $group, $guard);
        }

        foreach($resources as $resource) {
            $modelPermissions = $this->defaultPermissions($resource::label());
            foreach($modelPermissions as $name => $group) {
                $this->addPermissions($user, $permissionModel, $name, $group, $guard);
            }
        }
    }

    private function addPermissions($user, $permissionModel, $name, $group, $guard)
    {
        if(!$permissionModel::where('name', $name)->first()) {
            $permissionModel::create([
                'name' => $name,
                'group' => $group,
                'guard_name' => $guard,
                'user_id' => $user->id,
            ]);
        }
    }

    private function systemPermissions()
    {
        return [
            'viewNova' => 'System',
            'viewTelescope' => 'System',
            'viewAny action-resources' => 'Actions',
            'view action-resources' => 'Actions',
        ];
    }

    private function defaultPermissions($model)
    {
        $additional = [
            'users' => [
                'attachRole ' . Str::slug($model) => $model,
                'detachRole ' . Str::slug($model) => $model,
                'attachPermission ' . Str::slug($model) => $model,
                'detachPermission ' . Str::slug($model) => $model,
            ],
            'roles' => [
                'attachUser ' . Str::slug($model) => $model,
                'detachUser ' . Str::slug($model) => $model,
                'attachPermission ' . Str::slug($model) => $model,
                'detachPermission ' . Str::slug($model) => $model,
            ],
            'permissions' => [
                'attachRole ' . Str::slug($model) => $model,
                'detachRole ' . Str::slug($model) => $model,
                'attachUser ' . Str::slug($model) => $model,
                'detachUser ' . Str::slug($model) => $model,
            ]
        ];

        $permissions = [
            'viewAny ' . Str::slug($model) => $model,
            'view ' . Str::slug($model) => $model,
            'create ' . Str::slug($model) => $model,
            'update ' . Str::slug($model) => $model,
            'delete ' . Str::slug($model) => $model,
            'restore ' . Str::slug($model) => $model,
            'forceDelete ' . Str::slug($model) => $model,
        ];

        $permissions = array_merge($permissions, isset($additional[Str::slug($model)]) ? $additional[Str::slug($model)] : []);
        return $permissions;
    }
}
