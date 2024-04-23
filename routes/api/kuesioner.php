<?php

use App\Http\Controllers\Kuesioner\KuesionerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\Kuesioner\JenisPertanyaanController;
use App\Http\Controllers\Kuesioner\KelompokPertanyaanController;
use App\Http\Controllers\Kuesioner\PertanyaanController;

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
                Route::get('/perkuliahan', [KuesionerController::class, 'getMatkulByTahunAjaran']);

                // buka kuesioner perkuliahan by tahun ajaran
                Route::post('/perkuliahan/open', [KuesionerController::class, 'openKuesionerPerkuliahan']);

                // pertanyaan
                Route::prefix('/pertanyaan')
                    ->group(function () {
                        // jenis pertanyaan
                        Route::controller(JenisPertanyaanController::class)
                            ->group(function () {
                                Route::get('/jenis', 'getAllJenisPertanyaan');
                            });

                        // kelompok pertanyaan
                        Route::controller(KelompokPertanyaanController::class)
                            ->group(function () {
                                Route::get('/kelompok', 'getKelompokPertanyaan');
                            });

                        // pertanyaan
                        Route::controller(PertanyaanController::class)
                            ->group(function () {
                                Route::post('/add', 'addPertanyaan');
                                Route::put('/edit', 'editPertanyaan');
                                Route::get('/', 'getPertanyaanByJenisId');
                                Route::get('/detail/{pertanyaan_id}', 'getOnePertanyaanById');
                            });
                    });
            });
    });
