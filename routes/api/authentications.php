<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentications\AuthController;

/**
 * Route yang ada di sini digunakan untuk mengelola autentikasi user:
 * 1.) Generate access token jika user terautentikasi
 * 2.) Cek apakah token masih valid atau tidak
 * 3.) Cek daftar alamat sistem web yang dapat diakses user
 */

// ? Auth Routes
Route::controller(AuthController::class)
    ->prefix('authentications')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::post('/', 'userLogin')->withoutMiddleware('auth.jwt');
        Route::delete('/', 'userLogout');
        // Route::get('/', 'getNewToken');

        // * check token and site access
        Route::get('/check', 'validateToken');
        Route::get('/check/site', 'validateUserSiteAccess');
    });