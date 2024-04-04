<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wisuda\AdminPengajuanWisudaController;
use App\Http\Controllers\Wisuda\MahasiswaPengajuanWisudaController;

Route::prefix('/wisuda')
    ->middleware('auth.jwt')
    ->group(function () {

        // ? Mahasiswa
        Route::controller(MahasiswaPengajuanWisudaController::class)
            ->middleware('auth.mahasiswa')
            ->group(function () {
                Route::post('/pengajuan', 'addPengajuan');
                Route::put('/pengajuan/{nim}/update', 'updatePengajuan');
                Route::get('/pengajuan/{nim}', 'getDetailPengajuan');
                Route::get('/pengajuan/{nim}/status', 'getStatusPengajuan');
                Route::get('/pengajuan/skripsi/{nim}', 'getSkripsiDiajukanPadaSIKPS');
                Route::get('/pengajuan/jadwal/aktif', 'getJadwalWisudaAktif');
            });

        // ? Admin
        Route::controller(AdminPengajuanWisudaController::class)
            ->middleware('auth.admin')
            ->group(function () {
                Route::get('/pengajuan/list/pendaftar', 'getListPengajuan');
                Route::get('/pengajuan/list/status-tersedia', 'getListStatus');
                Route::get('/pengajuan/detail/{nim}', 'getDetailPengajuan');
                Route::put('/pengajuan/{nim}/update-by-admin', 'updatePengajuan');
                Route::get('/pengajuan/statistik/pendaftaran', 'getStatistikPengajuan');
                Route::post('/pengajuan/{nim}/verifikasi', 'verifikasiPengajuan');
                Route::get('/pengajuan/list/jadwal', 'getJadwalWisuda');
                Route::post('/pengajuan/jadwal/add', 'addJadwalWisuda');
                Route::put('/pengajuan/jadwal/{tahun}/update', 'updateJadwalWisuda');
            });
    });
