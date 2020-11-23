<?php


namespace Tsung\NovaUserManagement\Http\Controllers;


use App\Http\Controllers\Controller;

class DevController extends Controller
{
    public function testPermission()
    {
        $user = Auth()->user();
        if($user) {
            $permissionModel = config('novauser.gates.permission.model');
            $permissions = $permissionModel::all();
            echo "PERMISSION FOR {$user->name}<br>";
            foreach($permissions as $permission) {
                $text = "";
                if($user->hasPermissionTo($permission->name)) {
                    $text .= "<b style='color: green'>allow</b>";
                }
                else {
                    $text .= "<b style='color: red'>not allow</b>";
                }
                $text .= " to {$permission->name}<br>";
                echo $text;
            }
        }
        else {
            echo "NO USER DETECTED";
        }
        die();
    }

    public function phpinfo()
    {
        return phpinfo();
    }
}
