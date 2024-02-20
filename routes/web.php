<?php

use App\Http\Controllers\Docs\DocsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(DocsController::class)
    ->prefix('/docs/api')
    ->middleware('auth.session')
    ->group(function () {
        Route::get('/', 'home')->name('docs_home');
        Route::post('/authenticate', 'authenticate')->withoutMiddleware('auth.session');
        Route::get('/login', 'login')->withoutMiddleware('auth.session')->name('docs_login');
        Route::get('/logout', 'logout')->name('docs_logout');
    });

// ? Auth
Route::get('/', function () {
    return redirect()->route('docs_home');
});
