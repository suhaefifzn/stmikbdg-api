<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\DosenController;
use App\Http\Controllers\Kuesioner\MatkulDiampuController;

/**
 * List route yang ada di sini digunakan untuk sistem kuesioner
 * jadi prefix atau awalan dari path dibuat bernama /kuesioner
 */

// ? Kuesioner Routes
Route::prefix('/kuesioner')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::get('/dosen-aktif', [DosenController::class, 'getAllDosenAktif'])
            ->middleware('auth.mahasiswa');
        Route::get('/dosen-aktif/{id}/matkul-diampu', [
            MatkulDiampuController::class, 'getMatkulByDosenIdInKelasKuliah'
        ])->middleware('auth.mahasiswa');
    });
