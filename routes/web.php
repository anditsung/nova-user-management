<?php

//Route::group(['middleware' => 'web'], function() {
//    Route::get('/login', \Tsung\NovaUserManagement\Http\Controllers\Auth\LoginController::class . '@showLoginForm');
//        Route::post('/login', \Tsung\NovaUserManagement\Http\Controllers\Auth\LoginController::class . '@login')->name('login');
//        Route::get('/logout', \Tsung\NovaUserManagement\Http\Controllers\Auth\LoginController::class . '@logout')->name('logout');
//        Route::get('/password/reset', \Laravel\Nova\Http\Controllers\ForgotPasswordController::class . '@showLinkRequestForm')->name('password.request');
//        Route::post('/password/email', \Laravel\Nova\Http\Controllers\ForgotPasswordController::class . '@sendResetLinkEmail')->name('password.email');
//        Route::get('/password/reset/{token}', \Laravel\Nova\Http\Controllers\ResetPasswordController::class . '@showResetForm')->name('password.reset');
//        Route::post('/password/reset', \Laravel\Nova\Http\Controllers\ResetPasswordController::class . '@reset');
//});

Route::group(['middleware'=> 'web'], function() {

    // to check user permissions
    if(app()->environment('local')) {
        Route::get('/perm', function() {
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
        });
    }
});


