<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Users\AdminController;
use App\Http\Controllers\Users\DosenController;
use App\Http\Controllers\Users\MahasiswaController;
use App\Http\Controllers\Users\SiteController;
use App\Http\Controllers\Users\StaffController;
use App\Http\Controllers\Users\UserController;

Route::prefix('/sso')
    ->middleware(['auth.jwt', 'auth.admin'])
    ->group(function () {
        Route::get('/users/statistik', [UserController::class, 'getStatistik']);

       // staff
       Route::controller(StaffController::class)
        ->prefix('/staff')
        ->group(function () {
            Route::post('/add', 'addStaff');
            Route::get('/list', 'getAllStaff');
            Route::get('/detail', 'getAccount');
            Route::delete('/delete', 'delete');
            Route::put('/update', 'update');
        });

        // mahasiswa
        Route::controller(MahasiswaController::class)
            ->prefix('/mahasiswa')
            ->group(function () {
                Route::get('/list', 'getAll');
                Route::get('/detail', 'getDetail');
                Route::post('/add', 'add');
                Route::put('/update', 'update');
                Route::delete('/delete', 'delete');
            });

        // dosen
        Route::controller(DosenController::class)
            ->prefix('/dosen')
            ->group(function () {
                Route::get('/list', 'getAll');
                Route::get('/detail', 'getDetail');
                Route::post('/add', 'add');
                Route::put('/update', 'update');
                Route::delete('/delete', 'delete');
            });

        // admin
        Route::controller(AdminController::class)
            ->prefix('/admin')
            ->group(function () {
                Route::get('/list', 'getAll');
                Route::get('/detail', 'getDetail');
                Route::post('/add', 'add');
                Route::put('/update', 'update');
                Route::delete('/delete', 'delete');
                Route::get('/sites/list', 'getSites');
            });

        Route::controller(SiteController::class)
            ->prefix('/sites')
            ->middleware(['auth.jwt', 'auth.admin'])
            ->group(function () {
                Route::get('/list', 'getAll');
                Route::post('/add', 'addSite');
                Route::post('/user-access', 'addAccess');
                Route::delete('/user-access', 'deleteAccess');
            });
        });

