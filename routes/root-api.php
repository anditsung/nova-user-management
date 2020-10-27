<?php

use Illuminate\Support\Facades\Route;
use Tsung\NovaUserManagement\Http\Controllers\PassportController;

//https://dev.to/azibom/create-api-rest-with-laravel-7-x-passport-authentication-and-implement-refresh-token-part-1-43ja
//https://dev.to/azibom/create-api-rest-with-laravel-7-x-passport-authentication-and-implement-refresh-token-part-2-d91
//https://dev.to/azibom/create-api-rest-with-laravel-7-x-passport-authentication-and-implement-refresh-token-part-3-1b4d

Route::post('/login', [PassportController::class, 'login']);
Route::post('/refresh-token', [PassportController::class, 'refreshToken']);

Route::middleware('auth:api')->group( function () {
    Route::post('/logout', [PassportController::class, 'logout']);
});
