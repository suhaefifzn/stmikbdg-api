<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\FileController;

Route::controller(FileController::class)
    ->middleware('auth.jwt')
    ->prefix('/file')
    ->group(function () {
        Route::post('/image/add', 'uploadImage');
    });
