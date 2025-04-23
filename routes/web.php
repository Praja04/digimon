<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\RMPMController;
use App\Http\Controllers\SamplingController;
use App\Http\Controllers\ProductionBatchController;


// Login & Logout
Route::get('/', [AuthController::class, 'loginForm']);
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Dashboard umum
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Dashboard per-role
Route::middleware(['auth', RoleMiddleware::class . ':analis'])->get('/analis', function () {
    return view('analis.rmpm.pilih_jenis_gula');
})->name('analis.dashboard');

Route::middleware(['auth', RoleMiddleware::class . ':foreman'])->get('/foreman', function () {
    return view('roles.foreman');
})->name('foreman.dashboard');

Route::middleware(['auth', RoleMiddleware::class . ':supervisor'])->get('/supervisor', function () {
    return view('roles.supervisor');
})->name('supervisor.dashboard');

Route::middleware(['auth', RoleMiddleware::class . ':dept_head'])->get('/dept_head', function () {
    return view('roles.dept_head');
})->name('dept_head.dashboard');


//RMPM
Route::prefix('rmpm')->group(function () {
    Route::get('/', [RMPMController::class, 'pilihJenisGula'])->name('rmpm.pilihJenisGula');
    Route::get('/identitas/{jenis}', [RMPMController::class, 'formIdentitas'])->name('rmpm.formIdentitas');
    Route::post('/identitas/simpan', [RMPMController::class, 'simpanIdentitas'])->name('rmpm.simpanIdentitas');
    Route::get('/list/{jenis}', [RMPMController::class, 'listIdentitas'])->name('rmpm.listIdentitas');

    Route::get('/detail-identitas/{id}', [RMPMController::class, 'detailIdentitas'])->name('rmpm.detailIdentitas');
});


Route::prefix('sampling')->group(function () {
    // Sampling Kondisi Mobil
    Route::get('/kondisi-mobil/{id}', [SamplingController::class, 'showKondisiMobil'])->name('sampling.kondisi_mobil');
    Route::post('/kondisi-mobil', [SamplingController::class, 'storeKondisiMobil'])->name('sampling.kondisi_mobil.store');


    // Sampling Dokumen
    Route::get('/dokumen/{id}', [SamplingController::class, 'showDokumen'])->name('sampling.dokumen');
    Route::post('/dokumen', [SamplingController::class, 'storeDokumen'])->name('sampling.dokumen.store');

    // Sampling Fisik Kemasan
    Route::get('/fisik-kemasan/{id}', [SamplingController::class, 'showFisikKemasan'])->name('sampling.fisik_kemasan');
    Route::post('/fisik-kemasan', [SamplingController::class, 'storeFisikKemasan'])->name('sampling.fisik_kemasan.store');

    // Sampling Fisik Raw (Hanya untuk Gula, Tidak untuk Garam)
    Route::get('/fisik-raw/{id}', [SamplingController::class, 'showFisikRaw'])->name('sampling.fisik_raw');
    Route::post('/fisik-raw', [SamplingController::class, 'storeFisikRaw'])->name('sampling.fisik_raw.store');
});

Route::prefix('analisa')->group(function () {
    Route::post('/garam-gula', [RMPMController::class, 'storeGaramGula']);
    Route::post('/long-term', [RMPMController::class, 'storeLongTerm']);
    Route::post('/short-term', [RMPMController::class, 'storeShortTerm']);

    Route::get('/garam-gula/{id_identitas}', [RMPMController::class, 'showGaramGula']);
    Route::get('/long-term/{id_identitas}', [RMPMController::class, 'showLongTerm']);
    Route::get('/short-term/{id_identitas}', [RMPMController::class, 'showShortTerm']);


    Route::put('/garam-gula/{id}', [RMPMController::class, 'updateGaramGula']);
    Route::put('/long-term/{id}', [RMPMController::class, 'updateLongTerm']);
    Route::put('/short-term/{id}', [RMPMController::class, 'updateShortTerm']);

    Route::delete('/garam-gula/{id}', [RMPMController::class, 'destroyGaramGula']);
    Route::delete('/long-term/{id}', [RMPMController::class, 'destroyLongTerm']);
    Route::delete('/short-term/{id}', [RMPMController::class, 'destroyShortTerm']);
});


//gga ggas


Route::resource('productionbatch', ProductionBatchController::class);
Route::post('productionbatch/{id}/storeGgaGgas', [ProductionBatchController::class, 'storeGgaGgas'])->name('productionbatch.storeGgaGgas');
Route::post('ggaggas/{id}/storeAnalysis', [ProductionBatchController::class, 'storeAnalysis'])->name('ggaggas.storeAnalysis');

// Jika Anda belum memiliki rute 'createGgaGgas', tambahkan seperti ini:
Route::get('productionbatch/{id}/createGgaGgas', [ProductionBatchController::class, 'createGgaGgas'])->name('productionbatch.createGgaGgas');

Route::get('ggaggas/select', [ProductionBatchController::class, 'selectGgaGgas'])->name('productionbatch.selectGgaGgas');

// Rute untuk menampilkan detail data GGA/GGAS berdasarkan ID
Route::get('ggaggas/{id}', [ProductionBatchController::class, 'show'])->name('ggaggas.show');
