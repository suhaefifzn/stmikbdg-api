<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\Users\MahasiswaController;

/**
 * Daftar routes yang ada di sini digunakan hanya sebagai tambahan
 * dengan harapan dapat mempermudah beberapa keterbacaan dan akses ke sumber daya tertentu
 */

// ? Get tahun ajaran for common role (admin, dosen, dev)
Route::middleware('auth.jwt')
    ->group(function () {
        Route::get('/tahun-ajaran', [TahunAjaranController::class, 'getTahunAjaranByQueries']);
        Route::get('/jurusan', [JurusanController::class, 'getJurusanAktif']);
        Route::get('/jenis-mahasiswa', [MahasiswaController::class, 'getAllJenisMahasiswa']);
        Route::get('/list-kampus', [KampusController::class, 'getListKampus']);
    });

// ? Get Current Semester
Route::get('/current-semester', [TahunAjaranController::class, 'getSemesterMahasiswaSekarang'])
    ->middleware(['auth.jwt', 'auth.mahasiswa']);
