<?php

use App\Http\Controllers\Kuesioner\KuesionerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TahunAjaranController;

/**
 * List route yang ada di sini digunakan untuk sistem kuesioner
 * jadi prefix atau awalan dari path dibuat bernama /kuesioner
 */

// ? Kuesioner Routes
Route::prefix('/kuesioner')
    ->middleware('auth.jwt')
    ->group(function () {
        // untuk mahasiswa
        Route::prefix('/mahasiswa')
            ->middleware('auth.mahasiswa')
            ->group(function () {
                Route::get('/mata-kuliah', [KuesionerController::class, 'getMatkulByLastKRSMahasiswa']);
            });

        // admin
        Route::middleware('auth.admin')
            ->group(function () {
                Route::get('/tahun-ajaran', [TahunAjaranController::class, 'getTahunAjaranAktifForKuesioner']);
                Route::get('/mata-kuliah', [KuesionerController::class, 'getMatkulByTahunAjaran']);
            });
    });
