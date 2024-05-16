<?php

use App\Http\Controllers\SIKPS\SIKPSController;
use Illuminate\Support\Facades\Route;

/**
 * Routes yang ada di sini digunakan pada SIKPS.
 */

// ? Get all data skripsi mahasiswa
Route::prefix('/sikps')
    ->middleware('auth.jwt')
    ->group(function () {
        // mahasiswa
        Route::controller(SIKPSController::class)
            ->prefix('/mahasiswa')
            ->middleware('auth.mahasiswa')
            ->group(function () {
                Route::get('/pengajuan/all', 'getAllPengajuanSkripsiDiterima');
                Route::post('/pengajuan/deteksi/add', 'addHasilDeteksi');
            });
    });
