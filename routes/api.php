<?php

use App\Http\Controllers\Foreman\RMPMControllerForeman;
use App\Http\Controllers\Api\ApiGGAGGASController;
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
    });

    
});

Route::prefix('ggas/gga')->group(function () {
    Route::get('/summary', [ApiGGAGGASController::class, 'summarizeParameterPerBatch']);
    Route::get('/issues', [ApiGGAGGASController::class, 'analyzeQCIssues']);
    Route::get('/comparison', [ApiGGAGGASController::class, 'compareGgaAndGgasProcesses']);
    Route::get('/trends', [ApiGGAGGASController::class, 'trackParameterTrendsOverTime']);
    Route::get('/variant-dissolver', [ApiGGAGGASController::class, 'analyzeVariantAndDissolverPerformance']);
    Route::get('/success-rate', [ApiGGAGGASController::class, 'evaluateBatchSuccessRate']);

});
