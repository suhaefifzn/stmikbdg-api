<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kuliah\KelasKuliahController;

/**
 * Route yang ada di sini digunakan untuk mengelola kelas kuliah, meliputi:
 * 1.) Mendapatkan jadwal kuliah untuk dosen dan mahasiswa
 * 2.) Membuka kelas atau presensi oleh dosen yang mengampu matkul
 * 3.) Mengisi presensi oleh mahasiswa pada matkul yang dibuka
 * 4.) Melihat daftar mahasiswa yang mengisi kehadiran saat kelas dibuka oleh dosen
 * 5.) Melihat total kehadiran untuk mahasiswa
 * 6.) Mengisi kehadiran dilakukan dengan mengirim pin oleh mahasiswa
 * 7.) Pin kehadiran dapat bersifat single pin/statis atau random pin
 */

// ? Kelas Kuliah Routes
Route::controller(KelasKuliahController::class)
    ->prefix('kelas-kuliah')
    ->middleware('auth.jwt')
    ->group(function () {
        // * Routes untuk Dosen
        Route::get('/dosen', 'getKelasKuliahByDosen')->middleware('auth.dosen');

        // * Routes untuk Mahasiswa
        Route::get('/mahasiswa', 'getKelasKuliahByMahasiswa')->middleware('auth.mahasiswa');
    });
