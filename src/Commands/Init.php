<?php


namespace Tsung\NovaUserManagement\Commands;


use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Tsung\NovaUserManagement\Models\Permission;
use Tsung\NovaUserManagement\Models\Role;

class Init extends Command
{
    protected $signature = 'novauser:init';

    protected $description = 'create user, role and permission data for first use';

    public function handle()
    {
        $guard = config('nova.guard') ?: config('auth.defaults.guard');

        $this->info("Create User");
        $user = $this->createUser();
        $this->info("Done");

        $this->info("Create administrator Role");
        $role = $this->createAdministratorRole($user, $guard);
        $this->info("Done");

        $this->info("Create default permissions");
        $this->initDefaultPermissions($user, Permission::class, $guard);
        $this->info("Done");

        $this->info("Set default permissions to {$role->name}");
        $role->givePermissionTo(Permission::all());
        $this->info("Done");

        $this->info("Set {$role->name} to {$user->name}");
        $user->assignRole($role);
        $this->info("Done");

        $this->info("Finish");
    }

    private function createAdministratorRole($user, $guard)
    {
        $role = Role::where('name', 'administrator')->first();
        if(!$role) {
            $role = Role::create([
                'name' => 'administrator',
                'guard_name' => $guard,
                'user_id' => $user->id,
            ]);
        }
        return $role;
    }

    private function createUser()
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email Address');
        $password = $this->ask('Password');
        $user = User::where('email', $email)->first();
        if(!$user) {
            $user = User::create([
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
        $models = [
            'Users',
            'Roles',
            'Permissions',
        ];

        $systemPermissions = $this->systemPermissions();
        foreach($systemPermissions as $name => $group) {
            $this->addPermissions($user, $permissionModel, $name, $group, $guard);
        }

        foreach($models as $model) {
            $modelPermissions = $this->defaultPermissions($model);
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
            'backend' => 'System',
            'view actions' => 'ActionResource',
        ];
    }

    private function defaultPermissions($model)
    {
        $additional = [
            'users' => [
                'attachRole ' . strtolower($model) => $model,
                'detachRole ' . strtolower($model) => $model,
                'attachPermission ' . strtolower($model) => $model,
                'detachPermission ' . strtolower($model) => $model,
            ],
            'roles' => [
                'attachUser ' . strtolower($model) => $model,
                'detachUser ' . strtolower($model) => $model,
                'attachPermission ' . strtolower($model) => $model,
                'detachPermission ' . strtolower($model) => $model,
            ],
            'permissions' => [
                'attachRole ' . strtolower($model) => $model,
                'detachRole ' . strtolower($model) => $model,
                'attachUser ' . strtolower($model) => $model,
                'detachUser ' . strtolower($model) => $model,
            ]
        ];

        $permissions = [
            'viewAny ' . strtolower($model) => $model,
            'view ' . strtolower($model) => $model,
            'create ' . strtolower($model) => $model,
            'update ' . strtolower($model) => $model,
            'delete ' . strtolower($model) => $model,
            'restore ' . strtolower($model) => $model,
            'forceDelete ' . strtolower($model) => $model,
        ];

        $permissions = array_merge($permissions, isset($additional[strtolower($model)]) ? $additional[strtolower($model)] : []);
        return $permissions;
    }
}
