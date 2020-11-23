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

use Tsung\NovaUserManagement\Http\Controllers\DevController;

Route::group(['middleware'=> 'web'], function() {

    // to check user permissions
    if(app()->environment('local')) {

        Route::get('/perm', [DevController::class, 'testPermission']);

        Route::get('phpinfo',[DevController::class, 'phpinfo']);
    }
});


