<?php

// ? Controller
use App\Http\Controllers\Authentications\AuthController;
use App\Http\Controllers\KRS\KRSController;
use App\Http\Controllers\KRS\MatKulController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\Users\UserController;

use Illuminate\Http\Request;
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
        Route::post('/', 'addNewUser')->withoutMiddleware('auth.jwt'); // buat awalan
        Route::get('/me', 'getMyProfile');
        Route::put('/me', 'putMyEmail');
        Route::put('/me/password', 'putMyPassword');

        // route untuk admin
        Route::get('/', 'getUserList')->middleware('auth.admin');
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

// ? Tahun Ajaran routes
Route::controller(TahunAjaranController::class)
    ->prefix('tahun-ajaran')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::get('/', 'getTahunAjaran');
    });

// ? KRS routes
Route::prefix('krs')
    ->middleware('auth.jwt')
    ->group(function () {
        // * MatKul Controller
        Route::get('/mata-kuliah', [MatKulController::class, 'getMataKuliah']); // get list mata kuliah

        // * KRS controller
        Route::post('/', [KRSController::class, 'addKRSMahasiswa'])->middleware('auth.mahasiswa');
    });
