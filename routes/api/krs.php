<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KRS\KRSController;
use App\Http\Controllers\KRS\KRSDosenController;
use App\Http\Controllers\KRS\MatKulController;
use App\Http\Controllers\TahunAjaranController;

/**
 * Routes yang ada di sini digunakan untuk mengelola KRS, meliputi:
 * Sisi mahasiswa:
 * 1.) Melihat pengajuan KRS dibuka atau tidak berdasarkan pada tahun ajaran aktif
 * 2.) Melihat daftar kuliah yang dibuka pada pengisian krs berdasarkan tahun ajaran aktif
 * 3.) Mengajukan KRS berisi matkul yang dipilihnya, sebagai draft atau diajukan
 *
 * Sisi dosen wali:
 * 1.) Melihat daftar mahasiswa yang mengajukan KRS berdasarkan pengajuan paling baru
 * 2.) Melihat detail pengajuan KRS dari satu mahasiswa
 * 3.) Memperbaharui status KRS mahasiswa, apakah disetujui/sah atau ditolak/kembali ke draft
 */

// ? KRS Routes - mahasiswa
Route::prefix('krs')
    ->middleware(['auth.jwt', 'auth.mahasiswa'])
    ->group(function () {
        // * Tahun Ajaran
        Route::controller(TahunAjaranController::class)
            ->group(function() {
                Route::get('/tahun-ajaran', 'getTahunAjaran');
            });

        // * MatKul Controller
        Route::controller(MatKulController::class)
            ->group(function() {
                Route::get('/mata-kuliah', 'getMataKuliah');
            });

        // * KRS Controller
        Route::controller(KRSController::class)
            ->group(function() {
                Route::get('/check', 'checkKRS');
                Route::post('/mata-kuliah/pengajuan', 'addKRSMahasiswa');
                Route::post('/mata-kuliah/draft', 'addDraftKRSMahasiswa');
                Route::get('/mata-kuliah/draft', 'getDraftKRSMatkul');
            });
    });

// ? KRS Routes - dosen wali memerika krs mahasiswa
Route::controller(KRSDosenController::class)
    ->prefix('/krs/mahasiswa')
    ->middleware(['auth.jwt', 'auth.dosen_wali'])
    ->group(function () {
        Route::get('/', 'getKRSMahasiswa');
        Route::put('/', 'updateStatusKRSMahasiswa');
        Route::get('/list', 'getListKRSMahasiswa');
    });
