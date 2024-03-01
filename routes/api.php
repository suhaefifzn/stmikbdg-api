<?php

// ? Controller
use App\Http\Controllers\Authentications\AuthController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\KRS\KRSController;
use App\Http\Controllers\KRS\KRSDosenController;
use App\Http\Controllers\KRS\MatKulController;
use App\Http\Controllers\Kuesioner\MatkulDiampuController;
use App\Http\Controllers\Kuliah\KelasKuliahController;
use App\Http\Controllers\Users\MahasiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\Users\DosenController;
use App\Http\Controllers\Users\SiteController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

// ? User Routes
Route::controller(UserController::class)
    ->prefix('/users')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::post('/', 'addNewUser'); // buat awalan tambahin withoutMiddleware('auth.jwt')
        Route::get('/me', 'getMyProfile');
        Route::put('/me', 'putMyEmail');
        Route::put('/me/password', 'putMyPassword');
        Route::post('/me/image', 'addProfileImage');

        // * route untuk admin
        Route::get('/', 'getUserList')->middleware('auth.admin');
    });


// ? Auth Routes
Route::controller(AuthController::class)
    ->prefix('authentications')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::post('/', 'userLogin')->withoutMiddleware('auth.jwt');
        Route::delete('/', 'userLogout');
        // Route::get('/', 'getNewToken');

        // * check token and site access
        Route::get('/check', 'validateToken');
        Route::get('/check/site', 'validateUserSiteAccess');
    });

// ? KRS Routes
Route::prefix('krs')
    ->middleware(['auth.jwt', 'auth.mahasiswa'])
    ->group(function () {
        // * Tahun Ajaran
        Route::controller(TahunAjaranController::class)
            ->group(function() {
                Route::get('/tahun-ajaran', 'getTahunAjaran');
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
            });
    });

// ? KRS Routes - dosen wali memerika krs mahasiswa
Route::controller(KRSDosenController::class)
    ->prefix('/krs/mahasiswa')
    ->middleware(['auth.jwt', 'auth.dosen'])
    ->group(function () {
        Route::get('/', 'getKRSMahasiswa');
        Route::put('/', 'updateStatusKRSMahasiswa');
        Route::get('/list', 'getListKRSMahasiswa');
    });

// ? Kelas Kuliah Routes
Route::controller(KelasKuliahController::class)
    ->prefix('kelas-kuliah')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::get('/', 'getKelasKuliahByDosen')->middleware('auth.dosen');
    });

// ? ACL - Routes
Route::controller(SiteController::class)
    ->prefix('/sites')
    ->middleware(['auth.jwt', 'auth.admin'])
    ->group(function () {
        Route::get('/', 'getAllSites');
        Route::post('/', 'postNewSite');
        Route::post('/user-access', 'postUserSite');
        Route::delete('/user-access', 'deleteUserSiteAccess');
    });

// ? Get tahun ajaran for common role (admin, dosen, dev)
Route::middleware('auth.jwt')
    ->group(function () {
        Route::get('/tahun-ajaran', [TahunAjaranController::class, 'getTahunAjaranByQueries']);
        Route::get('/jurusan', [JurusanController::class, 'getJurusanAktif']);
        Route::get('/jenis-mahasiswa', [MahasiswaController::class, 'getAllJenisMahasiswa']);
        Route::get('/list-kampus', [KampusController::class, 'getListKampus']);
    });

// ? Kuesioner Routes
Route::prefix('/kuesioner')
    ->middleware('auth.jwt')
    ->group(function () {
        Route::get('/dosen-aktif', [DosenController::class, 'getAllDosenAktif'])
            ->middleware('auth.mahasiswa');
        Route::get('/dosen-aktif/{id}/matkul-diampu', [
            MatkulDiampuController::class, 'getMatkulByDosenIdInKelasKuliah'
        ])->middleware('auth.mahasiswa');
    });

// ? Additional Routes
Route::get('/current-semester', [TahunAjaranController::class, 'getSemesterMahasiswaSekarang'])
    ->middleware(['auth.jwt', 'auth.mahasiswa']);

// ? Get all data mahasiswa
Route::get('/all/mahasiswa', [MahasiswaController::class, 'getAllMahasiswa'])
    ->middleware(['auth.jwt', 'auth.developer']);
