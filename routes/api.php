<?php

use App\Http\Controllers\Authentications\AuthController;
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
            Route::post('/', 'addNewUser')->withoutMiddleware('auth.jwt'); // buat nyoba
            Route::get('/me', 'getMyProfile');
        });


// ? Auth routes
Route::controller(AuthController::class)
        ->prefix('authentications')
        ->middleware('auth.jwt')
        ->group(function() {
            Route::post('/', 'userLogin')->withoutMiddleware('auth.jwt');
            Route::delete('/', 'userLogout');
            Route::get('/', 'getNewToken');
        });

// ? Tahun ajaran routes
Route::controller(TahunAjaranController::class)
        ->prefix('tahun-ajaran')
        ->middleware('auth.jwt')
        ->group(function() {
            Route::get('/', 'getTahunAjaran');
        });
