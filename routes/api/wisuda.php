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
                Route::get('/pengajuan/{nim}', 'getDetailPengajuan');
                Route::get('/pengajuan/{nim}/status', 'getStatusPengajuan');
            });

        // ? Admin
        Route::controller(AdminPengajuanWisudaController::class)
            ->middleware('auth.admin')
            ->group(function () {
                Route::get('/pengajuan/list/pendaftar', 'getListPengajuan');
                Route::get('/pengajuan/list/status-tersedia', 'getListStatus');
                Route::get('/pengajuan/detail/{nim}', 'getDetailPengajuan');
                Route::get('/pengajuan/statistik/pendaftaran', 'getStatistikPengajuan');
                Route::put('/pengajuan/detail/{nim}', 'updatePengajuan');
                Route::post('/pengajuan/add-by-admin', 'addPengajuan');
                Route::delete('/pengajuan/{nim}/delete', 'deletePengajuan');
            });
    });
