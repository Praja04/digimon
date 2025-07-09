<?php

use App\Http\Controllers\Foreman\RMPMControllerForeman;
use App\Http\Controllers\Api\ApiGGAGGASController;
use App\Http\Controllers\Api\BlendingAwalController;
use App\Http\Controllers\Api\BlendingAfterAdjustController;
use App\Http\Controllers\Api\MonitoringTurunBlendingController;
use App\Http\Controllers\Api\MonitoringStorageController;
use App\Http\Controllers\Api\RMPMController;
use App\Models\BlendingAfterAdjustModel;
use Illuminate\Support\Facades\Route;

Route::prefix('foreman')->group(function () {
    

    Route::prefix('dashboard-rmpm')->controller(RMPMControllerForeman::class)->group(function () {
        Route::get('/summary', 'summary'); // statistik agregat
        Route::get('/kedatangan', 'kedatangan'); // daftar bahan masuk
        Route::get('/grafik-kedatangan', 'grafikKedatangan'); // tren grafik
        Route::get('/disposisi-pie', 'disposisiPie'); // pie chart disposisi
        Route::get('/kristal-positif', 'kristalPositif'); // daftar hasil uji kristal positif
        Route::get('/progress-sampling', 'progressSampling'); // status per identitas

        Route::get('/total-kedatangan', [RMPMControllerForeman::class, 'getTotalKedatangan']);
        Route::get('/sampling-lengkap', [RMPMControllerForeman::class, 'getSamplingLengkap']);
        Route::get('/sudah-analisa', [RMPMControllerForeman::class, 'getSudahAnalisa']);
        Route::get('/disposisi-summary', [RMPMControllerForeman::class, 'getDisposisiCount']);
        Route::get('/list-identitas', [RMPMControllerForeman::class, 'getListIdentitas']);


        Route::get('/mobil', [RMPMController::class, 'kondisiMobil']);
        Route::get('/dokumen', [RMPMController::class, 'dokumen']);
        Route::get('/kemasan', [RMPMController::class, 'fisikKemasan']);
        Route::get('/raw', [RMPMController::class, 'fisikRaw']);
    
    });

    
});

Route::prefix('dashboard-rmpm')->controller(RMPMControllerForeman::class)->group(function () {
    Route::get('/umum', [RMPMController::class, 'analisaUmum']);
    Route::get('/mobil', [RMPMController::class, 'kondisiMobil']);
    Route::get('/dokumen', [RMPMController::class, 'dokumen']);
    Route::get('/kemasan', [RMPMController::class, 'fisikKemasan']);
    Route::get('/raw', [RMPMController::class, 'fisikRaw']);
    Route::get('/parameter-kualitas-per-jenis', [RMPMController::class, 'analisaParameterKualitasPerJenisGula']);
    Route::get('/disposisi', [RMPMController::class, 'rekapDisposisiTotal']);
    
});

Route::prefix('ggas/gga')->group(function () {
    Route::get('/summary', [ApiGGAGGASController::class, 'summarizeParameterPerBatch']);
    Route::get('/issues', [ApiGGAGGASController::class, 'analyzeQCIssues']);
    Route::get('/comparison', [ApiGGAGGASController::class, 'compareGgaAndGgasProcesses']);
    Route::get('/trends', [ApiGGAGGASController::class, 'trackParameterTrendsOverTime']);
    Route::get('/variant-dissolver', [ApiGGAGGASController::class, 'analyzeVariantAndDissolverPerformance']);
    Route::get('/success-rate', [ApiGGAGGASController::class, 'evaluateBatchSuccessRate']);
    ////dipakai
    Route::get('/analysis', [ApiGGAGGASController::class, 'analisaGGAGGAS']);
    Route::get('/disposition-analysis', [ApiGGAGGASController::class, 'analisaDisposisi']);

});
Route::prefix('blending/awal')->group(function () {
    Route::get('/analysis', [BlendingAwalController::class, 'analisaBlendingAwal']);
    Route::get('/disposition-analysis', [BlendingAwalController::class, 'analisaDisposisi']);

});
Route::prefix('blending/after')->group(function () {
    Route::get('/analysis', [BlendingAfterAdjustController::class, 'analisaBlendingAfter']);
    Route::get('/disposition-analysis', [BlendingAfterAdjustController::class, 'analisaDisposisi']);
});
Route::prefix('monitoring/turun')->group(function () {
    Route::get('/analysis', [MonitoringTurunBlendingController::class, 'analisaMonitoringTurun']);
    Route::get('/disposition-analysis', [MonitoringTurunBlendingController::class, 'analisaDisposisi']);
});

Route::prefix('monitoring/storage')->group(function () {
    Route::get('/analysis', [MonitoringStorageController::class, 'analisaMonitoringStorage']);
    Route::get('/disposition-analysis', [MonitoringStorageController::class, 'analisaDisposisi']);
});