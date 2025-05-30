<?php

use App\Http\Controllers\analis\BlendingAdjustController;
use App\Http\Controllers\Analis\GgaGgasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Analis\RMPMController;
use App\Http\Controllers\Analis\SamplingController;
use App\Http\Controllers\Analis\BlendingAwalController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\Foreman\RMPMControllerForeman;


// Login & Logout
Route::get('/', [AuthController::class, 'loginForm']);
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');



Route::prefix('analis')->group(function () {
    Route::prefix('rmpm')->group(function () {
        Route::get('/', [RMPMController::class, 'pilihJenisGula'])->name('rmpm.pilihJenisGula');
       // Route::get('/identitas/{jenis}', [RMPMController::class, 'formIdentitas'])->name('rmpm.formIdentitas');
        Route::post('/identitas/simpan', [RMPMController::class, 'simpanIdentitas'])->name('rmpm.simpanIdentitas');
        Route::get('/list/{jenis}', [RMPMController::class, 'listIdentitas'])->name('rmpm.listIdentitas');
        Route::get('/detail-identitas/{id}', [RMPMController::class, 'detailIdentitas'])->name('rmpm.detailIdentitas');
        Route::get('/konfirmasi/{id}', [RMPMController::class, 'getDataKedatangan']);
        Route::post('/simpan/konfirmasi/{id}', [RMPMController::class, 'updateJam']);
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


    //Persiapan masak
    Route::prefix('productionbatch')->group(function () {
        Route::get('/menu', [ProductionBatchController::class, 'menu'])->name('productionbatch.menu');
        Route::get('/data_po', [ProductionBatchController::class, 'data_po'])->name('productionbatch.data_po');
        Route::get('/data_po/blending/awal', [ProductionBatchController::class, 'data_po_blending_awal'])->name('productionbatch.data_po_blending_awal');
        Route::get('/data_po/blending/adjust', [ProductionBatchController::class, 'data_po_blending_after_adjust'])->name('productionbatch.data_po_blending_adjust');
        Route::get('/po_masak/blending/awal/{id}', [ProductionBatchController::class, 'show_blending_awal'])->name('productionbatch.show_blending_awal');
        Route::get('/po_masak/blending/adjust/{id}', [ProductionBatchController::class, 'show_blending_after_adjust'])->name('productionbatch.show_blending_adjust');
        Route::resource('/po_masak', ProductionBatchController::class)->names([
            'index' => 'productionbatch.index',
            'create' => 'productionbatch.create',
            'store' => 'productionbatch.store',
            'show' => 'productionbatch.show',
            'edit' => 'productionbatch.edit',
            'update' => 'productionbatch.update',
            'destroy' => 'productionbatch.destroy',
        ]);
        Route::get('/processgga/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiGGA']);
        Route::post('/processgga/generate-revisi', [ProductionBatchController::class, 'generateRevisiGGA']);
        Route::get('/processggas/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiGGAS']);
        Route::post('/processggas/generate-revisi', [ProductionBatchController::class, 'generateRevisiGGAS']);
        Route::get('/processblending/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiBlendingAwal']);
        Route::post('processblending/generate-revisi', [ProductionBatchController::class, 'generateRevisiBlendingAwal']);
        Route::get('/processblending/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalBatch']);
        Route::get('/processblending/get-jalan-bareng', [ProductionBatchController::class, 'getMainBlendingAwalJalanBareng']);
        
        Route::post('processblending/adjust/generate-revisi', [ProductionBatchController::class, 'generateRevisiBlendingAdjust']);
        Route::get('/processblending/adjust/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiBlendingAdjust']);
        Route::get('/processblending/adjust/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalBatchAfterAdjust']);
        Route::get('/processblending/adjust/get-jalan-bareng', [ProductionBatchController::class, 'getMainBlendingAdjustJalanBareng']);
    });

    //gga ggas
    Route::prefix('ggaggas')->group(function () {
        Route::get('/menu', [GgaGgasController::class, 'menu'])->name('ggaggas.menu');
        Route::post('/process/store', [GgaGgasController::class, 'store'])->name('process.store');

        Route::post('/check-batch-range', [GgaGgasController::class, 'checkBatchRangeGGA'])->name('process.checkBatchRange');
        Route::get('/gga', [GgaGgasController::class, 'GGA_data']);
        Route::get('/gga/{id}', [GgaGgasController::class, 'GGA_detail']);
        Route::get('/gga/id/{id}', [GgaGgasController::class, 'showInputFormGGA']);
        Route::post('/gga/update-ajax/{id}', [GgaGgasController::class, 'updateAjaxGGA']);


        Route::get('/ggas', [GgaGgasController::class, 'GGAS_data']);
        Route::get('/ggas/{id}', [GgaGgasController::class, 'GGAS_detail']);
        Route::get('/ggas/id/{id}', [GgaGgasController::class, 'showInputFormGGAS']);
        Route::post('/ggas/update-ajax/{id}', [GgaGgasController::class, 'updateAjaxGGAS']);
    });

    Route::prefix('blending')->group(function(){
        Route::get('/menu', [BlendingAwalController::class, 'menu']);
        Route::get('/awal/detail/{id}', [BlendingAwalController::class, 'Blending_detail']);
        Route::get('/awal/detail/form/{id}', [BlendingAwalController::class, 'showInputFormBlendingAwal']);
        Route::post('/store', [BlendingAwalController::class, 'store'])->name('blending.store');
        Route::post('/update/{id}', [BlendingAwalController::class, 'updateAjaxBlending'])->name('blending.update');
        Route::get('/awal', [BlendingAwalController::class, 'Blending_data'])->name('blending.awal_data');
        
    });
    
    Route::prefix('blending/adjust')->group(function () {
        Route::get('/detail/{id}', [BlendingAdjustController::class, 'Blending_detail']);
        Route::post('/store', [BlendingAdjustController::class, 'store'])->name('blending_adjust.store');
        Route::post('/update/{id}', [BlendingAdjustController::class, 'updateAjaxBlending'])->name('blending_adjust.update');
        Route::get('/data', [BlendingAdjustController::class, 'Blending_adjust_data']);
    });

    
});

Route::prefix('foreman')->group(function () {
    Route::prefix('rmpm')->group(function () {
        Route::get('/', [RMPMControllerForeman::class, 'menu'])->name('rmpm_foreman.menu');
        Route::get('/dashboard', [RMPMControllerForeman::class, 'dashboard'])->name('rmpm_foreman.dashboard');
        Route::get('/list/data/{jenis}', [RMPMControllerForeman::class, 'list_data'])->name('rmpm_foreman.list_data');
        Route::get('/detail/data/{id}', [RMPMControllerForeman::class, 'detail_data'])->name('rmpm_foreman.detail_data');
        Route::post('/update/{id}', [RMPMControllerForeman::class, 'updateDisposisiLong'])->name('rmpm_foreman.updateDisposisiLong');
    });

    
});
