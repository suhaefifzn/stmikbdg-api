<?php

// ? Controller
use App\Http\Controllers\Authentications\AuthController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KRS\KRSController;
use App\Http\Controllers\KRS\MatKulController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\Users\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ? User routes
Route::controller(UserController::class)
    ->prefix('/users')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::post('/', 'addNewUser'); // buat awalan tambahin withoutMiddleware('auth.jwt')
        Route::get('/me', 'getMyProfile');
        Route::put('/me', 'putMyEmail');
        Route::put('/me/password', 'putMyPassword');

        // * route untuk admin
        Route::get('/', 'getUserList')->middleware('auth.admin');

        // * route untuk wali dosen
        Route::get('/my/mahasiswa', 'getMyMahasiswa')->middleware('auth.dosen');
    });


// ? Auth routes
Route::controller(AuthController::class)
    ->prefix('authentications')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::post('/', 'userLogin')->withoutMiddleware('auth.jwt');
        Route::delete('/', 'userLogout');
        Route::get('/', 'getNewToken');
    });

// ? KRS routes
Route::prefix('krs')
    ->middleware(['auth.jwt', 'auth.mahasiswa'])
    ->group(function () {
        // * Tahun Ajaran
        Route::controller(TahunAjaranController::class)
            ->group(function() {
                Route::get('/tahun-ajaran', 'getTahunAjaran')->withoutMiddleware('auth.mahasiswa');
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

                // Route wali dosen
                Route::get('/mahasiswa', 'getKRSMahasiswa')
                    ->middleware('auth.dosen')
                    ->withoutMiddleware('auth.mahasiswa');
            });
    });

// ? Additional
Route::get('/current-semester', [TahunAjaranController::class, 'getSemesterMahasiswaSekarang'])
        ->middleware(['auth.jwt', 'auth.mahasiswa']);
Route::get('/jurusan', [JurusanController::class, 'getJurusanAktif'])
        ->middleware(['auth.jwt', 'auth.dosen']);
