<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\SiteController;

/**
 * Daftar route yang ada di sini digunakan untuk mengelola akses user oleh admin, meliputi:
 * 1.) Menambah user baru
 * 2.) Mengatur akses user ke alamat sistem web STMIK Bandung yang telah ada
 */

// ? ACL - Routes
Route::controller(SiteController::class)
    ->prefix('/sites')
    ->middleware(['auth.jwt', 'auth.admin'])
    ->group(function () {
        Route::get('/', 'getAllSites');
        Route::post('/', 'postNewSite');
        Route::post('/user-access', 'postUserSite');
        Route::delete('/user-access', 'deleteUserSiteAccess');
    });
