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
        Route::post('/authenticate', 'authenticate')->withoutMiddleware('auth.session');
        Route::get('/login', 'login')->withoutMiddleware('auth.session')->name('docs_login');
        Route::get('/logout', 'logout')->name('docs_logout');

        // ? Routes sesudah login
        Route::get('/authentications', 'authentications');
        Route::get('/users', 'users');
        Route::get('/acl', 'acl');
        Route::get('/kelas-mahasiswa', 'kelasKuliahMahasiswa');
        Route::get('/kelas-dosen', 'kelasKuliahDosen');
        Route::get('/kamus', 'kamus');
        Route::get('/additional', 'additionalRoutes');
        Route::get('/marketing', 'marketing');
        Route::get('/berita-acara', 'beritaAcara');
        Route::get('/penomoran-surat', 'surat');

        // ? 27-05-2024 - New design
        //* Home
        Route::get('/', 'home')->name('docs_home');
        Route::get('/home/tabs/{name}', 'homeTabs');

        // * Antrian
        Route::get('/antrian', 'antrian');
        Route::get('/antrian/tabs/{name}', 'antrianTabs');

        // * Pengajuan Wisuda
        Route::get('/pengajuan-wisuda', 'pengajuanWisuda');
        Route::get('/pengajuan-wisuda/tabs/{name}', 'pengajuanWisudaTabs');

        // * Kuesioner
        Route::get('/kuesioner', 'kuesioner');
        Route::get('/kuesioner/tabs/{name}', 'kuesionerTabs');

        // * SIKPS - Deteksi Proposal
        Route::get('/sikps', 'sikps');
        Route::get('/sikps/tabs/{name}', 'sikpsTabs');

        // * Android - KRS
        Route::get('/android/krs', 'androidKrs');
        Route::get('/android/krs/tabs/{name}', 'androidKrsTabs');
    });

// ? Auth
Route::get('/', function () {
    return redirect()->route('docs_home');
});
