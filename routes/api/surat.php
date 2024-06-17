<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\Surat\KategoriController as KategoriSuratController;

Route::prefix('/surat')
    ->middleware('auth.jwt')
    ->group(function () {
        // ? admin - kategori
        Route::controller(KategoriSuratController::class)
            ->middleware('auth.admin')
            ->prefix('/kategori')
            ->group(function () {
                Route::get('/list', 'getListKategori')
                    ->withoutMiddleware('auth.admin')
                    ->middleware('auth.surat.users'); // seluruh pengguna sistem surat

                Route::post('/add', 'addKategori');
                Route::put('/update', 'updateKategori');
                Route::delete('/delete', 'deleteKategori');
            });
    });
