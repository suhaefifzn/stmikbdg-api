<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Antrian\Dosen\AdminController as DosenAdminController;
use App\Http\Controllers\Antrian\Bimbingan\AdminController as BimbinganAdminController;
use App\Http\Controllers\Antrian\Tamu\AdminController as TamuAdminController;
use App\Http\Controllers\Antrian\Sidang\AdminController as SidangAdminController;

Route::prefix('/antrian')
    ->middleware(['auth.jwt', 'auth.admin'])
    ->group(function () {
        // mengelola data dosen
        Route::controller(DosenAdminController::class)
            ->prefix('/dosen')
            ->group(function () {
                Route::get('/list', 'getAllDosen');
                Route::post('/add', 'addDosen');
                Route::delete('/delete', 'deleteDosen');
                Route::put('/update', 'updateDosen');
                Route::get('/detail/{dosen_id}', 'getDosen');
            });

        // mengelola antrian bimbingan
        Route::controller(BimbinganAdminController::class)
            ->prefix('/bimbingan')
            ->group(function () {
                Route::get('/list', 'getAllAntrianBimbingan');
                Route::post('/add', 'addAntrianBimbingan');
                Route::delete('/delete', 'deleteAntrianBimbingan');
                Route::put('/update', 'updateAntrianBimbingan');
                Route::get('/detail/{bimbingan_id}', 'getAntrianBimbingan');
                Route::put('/status/update', 'updateStatusAntrianBimbingan');
            });

        // mengelola antrian tamu
        Route::controller(TamuAdminController::class)
            ->prefix('/tamu')
            ->group(function () {
                Route::get('/list', 'getAllAntrianTamu');
                Route::get('/detail/{tamu_id}', 'getAntrianTamu');
                Route::post('/add', 'addAntrianTamu');
                Route::put('/update', 'updateAntrianTamu');
                Route::delete('/delete', 'deleteAntrianTamu');
            });

        // mengelola antrian sidang
        Route::controller(SidangAdminController::class)
            ->prefix('/sidang')
            ->group(function () {
                Route::get('/list', 'getAllAntrianSidang');
                Route::get('/detail/{sidang_id}', 'getAntrianSidang');
                Route::post('/add', 'addAntrianSidang');
                Route::put('/update', 'updateAntrianSidang');
                Route::delete('/delete', 'deleteAntrianSidang');
            });
    });

