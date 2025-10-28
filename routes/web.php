<?php

use App\Events\ProcessOutsideDisposition;
use App\Http\Controllers\Analis\BlendingAdjustController;
use App\Http\Controllers\Analis\MonitoringTurunBlendingController;
use Milon\Barcode\DNS2D;
use App\Http\Controllers\Analis\MonitoringStorageController;
use App\Http\Controllers\Analis\GgaGgasController;
use App\Http\Controllers\Analis\RMPMController;
use App\Http\Controllers\Analis\SamplingController;
use App\Http\Controllers\Analis\BlendingAwalController;
use App\Http\Controllers\Analis\MonitoringPasteurisasiControllerAnalis;
use App\Http\Controllers\Analis\MonitoringStorageBeforeUseController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageStandarDataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Foreman\RMPMControllerForeman;
use App\Http\Controllers\Foreman\BlendingAdjustControllerForeman;
use App\Http\Controllers\Foreman\MonitoringTurunBlendingControllerForeman;
use App\Http\Controllers\Foreman\MonitoringStorageControllerForeman;
use App\Http\Controllers\Foreman\GgaGgasControllerForeman;
use App\Http\Controllers\Foreman\SamplingControllerForeman;
use App\Http\Controllers\Foreman\BlendingAwalControllerForeman;
use App\Http\Controllers\Supervisor\DashboardMakro;
use App\Http\Controllers\Supervisor\RMPMControllerSupervisor;
use App\Http\Controllers\Supervisor\BlendingAdjustControllerSupervisor;
use App\Http\Controllers\Supervisor\MonitoringTurunBlendingControllerSupervisor;
use App\Http\Controllers\Supervisor\MonitoringStorageControllerSupervisor;
use App\Http\Controllers\Supervisor\GgaGgasControllerSupervisor;
use App\Http\Controllers\Supervisor\SamplingControllerSupervisor;
use App\Http\Controllers\Supervisor\BlendingAwalControllerSupervisor;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Foreman\MonitoringPasteurisasiControllerForeman;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Supervisor\MonitoringPasteurisasiControllerSupervisor;
use App\Models\MonitoringStorageBeforeUse;
use App\Models\Notification;
use Illuminate\Support\Facades\Session;

// Login & Logout
Route::get('/', [AuthController::class, 'loginForm']);
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/users', [AuthController::class, 'getUsers'])->name('users.get');
Route::post('users/{id}', [AuthController::class, 'update'])->name('users.update'); // Update user
Route::delete('users/{id}', [AuthController::class, 'destroy'])->name('users.destroy'); // Hapus user

Route::prefix('dashboard')->group(function () {
    Route::get('/gga-ggas', [DashboardController::class, 'dashboard_gga_ggas'])->name('dashboard.gga_ggas');
    Route::get('/blending/awal', [DashboardController::class, 'dashboard_blending_awal'])->name('dashboard.blending_awal');
    Route::get('/blending/after', [DashboardController::class, 'dashboard_blending_after'])->name('dashboard.blending_after');
    Route::get('/rm', [DashboardController::class, 'dashboard_rm'])->name('dashboard.rm');
    Route::get('/monitoring/turun', [DashboardController::class, 'dashboard_monitoring_turun'])->name('dashboard.monitoring_turun');
    Route::get('/monitoring/storage', [DashboardController::class, 'dashboard_monitoring_storage'])->name('dashboard.monitoring_storage');
    Route::get('/mikro/blending/after', [DashboardController::class, 'dashboard_blending_after_mikro'])->name('dashboard.mikro_blending');
    Route::get('/mikro/monitoring/storage', [DashboardController::class, 'dashboard_monitoring_storage_mikro'])->name('dashboard.mikro_monitoring_storage');
});

Route::prefix('analis')->group(function () {
    Route::get('/analisa/temp/{identitasId}', [AuthController::class, 'getTemporary']);
    Route::post('/analisa/temp/save', [AuthController::class, 'saveTemporary']);
    Route::prefix('rmpm')->group(function () {
        Route::get('/', [RMPMController::class, 'pilihJenisGula'])->name('rmpm.pilihJenisGula');
        // Route::get('/identitas/{jenis}', [RMPMController::class, 'formIdentitas'])->name('rmpm.formIdentitas');
        Route::get('/list/rm', [RMPMController::class, 'dataRM'])->name('dataRM_analis');
        Route::get('/data/rm', [RMPMController::class, 'getDataRM']);
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
        Route::get('/scan', [ProductionBatchController::class, 'scan']);
        Route::get('/menu', [ProductionBatchController::class, 'menu'])->name('productionbatch.menu');
        Route::get('/data_po', [ProductionBatchController::class, 'data_po'])->name('productionbatch.data_po');
        Route::get('/data_po/blending/awal', [ProductionBatchController::class, 'data_po_blending_awal'])->name('productionbatch.data_po_blending_awal');
        Route::get('/data_po/blending/adjust', [ProductionBatchController::class, 'data_po_blending_after_adjust'])->name('productionbatch.data_po_blending_adjust');
        Route::get('/data_po/monitoring/blending', [ProductionBatchController::class, 'data_po_monitoring'])->name('productionbatch.data_po_monitoring');
        Route::get('/data_po/monitoring/pasteurisasi', [ProductionBatchController::class, 'data_po_monitoring_pasteurisasi'])->name('productionbatch.data_po_monitoring_pasteurisasi');
        Route::get('/data_po/monitoring/storage', [ProductionBatchController::class, 'data_po_monitoring_storage'])->name('productionbatch.data_po_monitoring_storage');
        Route::get('/data_po/monitoring/storage/before-use', [ProductionBatchController::class, 'data_po_monitoring_storage_before_use'])->name('productionbatch.data_po_monitoring_storage_before_use');
        Route::get('/po_masak/monitoring/storage/before-use/{id}', [ProductionBatchController::class, 'show_monitoring_storage_before_use'])->name('productionbatch.show_monitoring_storage_before_use');
        Route::get('/po_masak/blending/awal/{id}', [ProductionBatchController::class, 'show_blending_awal'])->name('productionbatch.show_blending_awal');
        Route::get('/po_masak/blending/adjust/{id}', [ProductionBatchController::class, 'show_blending_after_adjust'])->name('productionbatch.show_blending_adjust');
        Route::get('/po_masak/monitoring/blending/{id}', [ProductionBatchController::class, 'show_monitoring_blending'])->name('productionbatch.show_monitoring_blending');
        Route::get('/po_masak/monitoring/storage/{id}', [ProductionBatchController::class, 'show_monitoring_storage'])->name('productionbatch.show_monitoring_storage');
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

        Route::post('processmonitoring/generate-revisi', [ProductionBatchController::class, 'generateRevisiMonitoring']);
        Route::get('/processmonitoring/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiMonitoring']);
        Route::get('/processmonitoring/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalBatchMonitoring']);
        Route::get('/processmonitoring/get-jalan-bareng', [ProductionBatchController::class, 'getMainMonitoringJalanBareng']);

        Route::post('processmonitoringpasteurisasi/generate-revisi', [ProductionBatchController::class, 'generateRevisiMonitoringPasteurisasi']);
        Route::get('/processmonitoringpasteurisasi/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiMonitoringPasteurisasi']);
        Route::get('/processmonitoringpasteurisasi/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalBatchMonitoringPasteurisasi']);
        Route::get('/processmonitoringpasteurisasi/get-jalan-bareng', [ProductionBatchController::class, 'getMainMonitoringPasteurisasiJalanBareng']);

        Route::post('processmonitoringstorage/generate-revisi', [ProductionBatchController::class, 'generateRevisiMonitoringStorage']);
        Route::get('/processmonitoringstorage/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiMonitoringStorage']);
        Route::get('/processmonitoringstorage/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalMonitoringStorage']);
        Route::get('/processmonitoringstorage/get-jalan-bareng', [ProductionBatchController::class, 'getMainMonitoringStorageJalanBareng']);

        //status complete
        Route::get('/gga/complete/{id}', [ProductionBatchController::class, 'getCompletedGga']);
        Route::get('/ggas/complete/{id}', [ProductionBatchController::class, 'getCompletedGgas']);
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

    Route::prefix('blending')->group(function () {
        Route::get('/menu', [BlendingAwalController::class, 'menu'])->name('blending_analis.menu');
        Route::get('/awal/detail/{id}', [BlendingAwalController::class, 'Blending_detail']);
        Route::get('/awal/detail/form/{id}', [BlendingAwalController::class, 'showInputFormBlendingAwal']);
        Route::post('/store', [BlendingAwalController::class, 'store'])->name('blending.store');
        Route::post('/update/{id}', [BlendingAwalController::class, 'updateAjaxBlending'])->name('blending.update');
        Route::get('/awal', [BlendingAwalController::class, 'Blending_data'])->name('blending.awal_data');
    });

    Route::prefix('blending/adjust')->group(function () {
        Route::get('/detail/form/{id}', [BlendingAdjustController::class, 'showInputFormBlendingAdjust']);
        Route::get('/detail/{id}', [BlendingAdjustController::class, 'Blending_detail']);
        Route::post('/store', [BlendingAdjustController::class, 'store'])->name('blending_adjust.store');
        Route::post('/update/{id}', [BlendingAdjustController::class, 'updateAjaxBlending'])->name('blending_adjust.update');
        Route::get('/data', [BlendingAdjustController::class, 'Blending_adjust_data']);
    });

    Route::prefix('blending/mikro')->group(function () {
        Route::get('/detail/form/{id}', [BlendingAdjustController::class, 'showInputFormBlendingAdjustMikro']);
        Route::get('/data', [BlendingAdjustController::class, 'Blending_adjust_data_mikro']);
        Route::get('/detail/{id}', [BlendingAdjustController::class, 'Blending_detail_mikro']);
        Route::post('/update/{id}', [BlendingAdjustController::class, 'updateAjaxBlendingMikro'])->name('blending_adjust_mikro.update');
    });

    Route::prefix('monitoring/blending')->group(function () {
        Route::get('/menu', [MonitoringTurunBlendingController::class, 'menu']);
        Route::post('/store', [MonitoringTurunBlendingController::class, 'store'])->name('monitoring_blending.store');
        Route::post('/update/data', [MonitoringTurunBlendingController::class, 'updateMonitoringBlending']);
        Route::post('/detail/data', [MonitoringTurunBlendingController::class, 'store_data_analisa']);
        Route::get('/detail/data/{id}', [MonitoringTurunBlendingController::class, 'showDataDetail']);
        Route::get('/detail/data/id/{id}', [MonitoringTurunBlendingController::class, 'showInputFormMonitoringTurunBlending']);
        Route::get('/data', [MonitoringTurunBlendingController::class, 'Monitoring_Blending_data']);
        Route::get('/detail/{id}', [MonitoringTurunBlendingController::class, 'Monitoring_Blending_detail']);
    });

    Route::prefix('monitoring/pasteurisasi')->group(function () {
        Route::get('/dashboard', [MonitoringPasteurisasiControllerAnalis::class, 'dashboard']);
        Route::get('/menu', [MonitoringPasteurisasiControllerAnalis::class, 'menu']);
        Route::post('/store', [MonitoringPasteurisasiControllerAnalis::class, 'store'])->name('monitoring_pasteurisasi.store');
        Route::post('/update/data', [MonitoringPasteurisasiControllerAnalis::class, 'updateMonitoringPasteurisasi']);
        Route::post('/detail/data', [MonitoringPasteurisasiControllerAnalis::class, 'store_data_pasteurisasi']);
        Route::post('/detail/edit', [MonitoringPasteurisasiControllerAnalis::class, 'edit_data']);
        Route::get('/detail/data/{id}', [MonitoringPasteurisasiControllerAnalis::class, 'showDataDetail']);
        Route::get('/detail/data/id/{id}', [MonitoringPasteurisasiControllerAnalis::class, 'showInputFormMonitoringPasteurisasi']);
        Route::get('/data', [MonitoringPasteurisasiControllerAnalis::class, 'Monitoring_Pasteurisasi_data']);
        Route::get('/detail/{id}', [MonitoringPasteurisasiControllerAnalis::class, 'Monitoring_Pasteurisasi_detail']);
        Route::get('/menu', [MonitoringPasteurisasiControllerAnalis::class, 'Monitoring_Pasteurisasi_menu']);
    });

    Route::prefix('monitoring/storage')->group(function () {
        Route::post('/store', [MonitoringStorageController::class, 'store'])->name('monitoring_storage.store');
        Route::get('/data', [MonitoringStorageController::class, 'Monitoring_Storage_data']);
        Route::get('/detail/{id}', [MonitoringStorageController::class, 'Monitoring_Storage_detail']);
        Route::get('/detail/data/{id}', [MonitoringStorageController::class, 'Monitoring_Storage_detail_id']);
        Route::post('/update/data/{id}', [MonitoringStorageController::class, 'update_monitoring_storage_makro']);

        //mikro
        Route::get('/data/mikro', [MonitoringStorageController::class, 'Monitoring_Storage_data_mikro']);
        Route::get('/detail/mikro/{id}', [MonitoringStorageController::class, 'Monitoring_Storage_detail_mikro']);
        Route::get('/detail/data/mikro/{id}', [MonitoringStorageController::class, 'Monitoring_Storage_detail_mikro_id']);
        Route::post('/update/data/mikro/{id}', [MonitoringStorageController::class, 'update_monitoring_storage_mikro']);

        //storage before use
        Route::get('/data/before-use', [MonitoringStorageBeforeUseController::class, 'index'])->name('analis.monitoring_storage_before_use.index');
        Route::post('/store/before-use', [MonitoringStorageBeforeUseController::class, 'store'])->name('analis.monitoring_storage_before_use.store');
        Route::get('/detail/before-use/{id}', [MonitoringStorageBeforeUseController::class, 'show'])->name('analis.monitoring_storage_before_use.show');
        Route::get('/detail/before-use/batch/{id}', [MonitoringStorageBeforeUseController::class, 'show_batch'])->name('analis.monitoring_storage_before_use.show_batch');
        Route::post('/update/data/before-use/{id}', [MonitoringStorageBeforeUseController::class, 'update'])->name('analis.monitoring_storage_before_use.update');
    });
});

Route::prefix('foreman')->group(function () {
    Route::prefix('rmpm')->group(function () {
        Route::get('/', [RMPMControllerForeman::class, 'menu'])->name('rmpm_foreman.menu');
        Route::get('/dashboard', [RMPMControllerForeman::class, 'dashboard'])->name('rmpm_foreman.dashboard');
        Route::get('/list/rm', [RMPMControllerForeman::class, 'dataRM'])->name('dataRM_foreman');
        Route::get('/list/data/{jenis}', [RMPMControllerForeman::class, 'list_data'])->name('rmpm_foreman.list_data');
        Route::get('/detail/data/{id}', [RMPMControllerForeman::class, 'detail_data'])->name('rmpm_foreman.detail_data');
        Route::post('/update/{id}', [RMPMControllerForeman::class, 'updateDisposisiLong'])->name('rmpm_foreman.updateDisposisiLong');
    });
    Route::prefix('dashboard-rmpm')->group(function () {
        Route::get('/total-kedatangan', [RMPMControllerForeman::class, 'getTotalKedatangan']);
        Route::get('/sampling-lengkap', [RMPMControllerForeman::class, 'getSamplingLengkap']);
        Route::get('/sudah-analisa', [RMPMControllerForeman::class, 'getSudahAnalisa']);
        Route::get('/disposisi-summary', [RMPMControllerForeman::class, 'getDisposisiCount']);
        Route::get('/list-identitas', [RMPMControllerForeman::class, 'getListIdentitas']);
    });

    Route::prefix('sampling')->group(function () {
        // Sampling Kondisi Mobil
        Route::get('/kondisi-mobil/{id}', [SamplingControllerForeman::class, 'showKondisiMobil'])->name('sampling.kondisi_mobil');
        Route::post('/kondisi-mobil', [SamplingControllerForeman::class, 'storeKondisiMobil']);
        Route::post('/edit/kondisi-mobil/{id}', [SamplingControllerForeman::class, 'updateKondisiMobil'])->name('sampling.kondisi_mobil.edit');


        // Sampling Dokumen
        Route::get('/dokumen/{id}', [SamplingControllerForeman::class, 'showDokumen'])->name('sampling.dokumen');
        Route::post('/dokumen', [SamplingControllerForeman::class, 'storeDokumen']);
        Route::post('/edit/dokumen/{id}', [SamplingControllerForeman::class, 'updateDokumen'])->name('sampling.dokumen.edit');

        // Sampling Fisik Kemasan
        Route::get('/fisik-kemasan/{id}', [SamplingControllerForeman::class, 'showFisikKemasan'])->name('sampling.fisik_kemasan');
        Route::post('/fisik-kemasan', [SamplingControllerForeman::class, 'storeFisikKemasan']);
        Route::post('/edit/fisik-kemasan/{id}', [SamplingControllerForeman::class, 'updateKemasan'])->name('sampling.fisik_kemasan.edit');

        // Sampling Fisik Raw (Hanya untuk Gula, Tidak untuk Garam)
        Route::get('/fisik-raw/{id}', [SamplingControllerForeman::class, 'showFisikRaw'])->name('sampling.fisik_raw');
        Route::post('/fisik-raw', [SamplingControllerForeman::class, 'storeFisikRaw']);
        Route::post('/edit/fisik-raw/{id}', [SamplingControllerForeman::class, 'updateFisikRaw'])->name('sampling.fisik_raw.edit');
    });

    Route::prefix('analisa')->group(function () {
        Route::post('/garam-gula', [RMPMControllerForeman::class, 'storeGaramGula']);
        Route::post('/long-term', [RMPMControllerForeman::class, 'storeLongTerm']);
        Route::post('/short-term', [RMPMControllerForeman::class, 'storeShortTerm']);

        Route::get('/garam-gula/{id_identitas}', [RMPMControllerForeman::class, 'showGaramGula']);
        Route::get('/long-term/{id_identitas}', [RMPMControllerForeman::class, 'showLongTerm']);
        Route::get('/short-term/{id_identitas}', [RMPMControllerForeman::class, 'showShortTerm']);


        Route::put('/garam-gula/{id}', [RMPMControllerForeman::class, 'updateGaramGula']);
        Route::put('/long-term/{id}', [RMPMControllerForeman::class, 'updateLongTerm']);
        Route::put('/short-term/{id}', [RMPMControllerForeman::class, 'updateShortTerm']);

        Route::delete('/garam-gula/{id}', [RMPMControllerForeman::class, 'destroyGaramGula']);
        Route::delete('/long-term/{id}', [RMPMControllerForeman::class, 'destroyLongTerm']);
        Route::delete('/short-term/{id}', [RMPMControllerForeman::class, 'destroyShortTerm']);
    });


    //Persiapan masak
    Route::prefix('productionbatch')->group(function () {
        Route::get('/scan', [ProductionBatchController::class, 'scan']);
        Route::get('/menu', [ProductionBatchController::class, 'menu'])->name('productionbatch.menu');
        Route::get('/data_po', [ProductionBatchController::class, 'data_po'])->name('productionbatch.data_po');
        Route::get('/data_po/blending/awal', [ProductionBatchController::class, 'data_po_blending_awal'])->name('productionbatch.data_po_blending_awal');
        Route::get('/data_po/blending/adjust', [ProductionBatchController::class, 'data_po_blending_after_adjust'])->name('productionbatch.data_po_blending_adjust');
        Route::get('/data_po/monitoring/blending', [ProductionBatchController::class, 'data_po_monitoring'])->name('productionbatch.data_po_monitoring');
        Route::get('/data_po/monitoring/storage', [ProductionBatchController::class, 'data_po_monitoring_storage'])->name('productionbatch.data_po_monitoring_storage');
        Route::get('/po_masak/blending/awal/{id}', [ProductionBatchController::class, 'show_blending_awal'])->name('productionbatch.show_blending_awal');
        Route::get('/po_masak/blending/adjust/{id}', [ProductionBatchController::class, 'show_blending_after_adjust'])->name('productionbatch.show_blending_adjust');
        Route::get('/po_masak/monitoring/blending/{id}', [ProductionBatchController::class, 'show_monitoring_blending'])->name('productionbatch.show_monitoring_blending');
        Route::get('/po_masak/monitoring/pasteurisasi/{id}', [ProductionBatchController::class, 'show_monitoring_pasteurisasi'])->name('productionbatch.show_monitoring_pasteurisasi');
        Route::get('/po_masak/monitoring/storage/{id}', [ProductionBatchController::class, 'show_monitoring_storage'])->name('productionbatch.show_monitoring_storage');
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

        Route::post('processmonitoring/generate-revisi', [ProductionBatchController::class, 'generateRevisiMonitoring']);
        Route::get('/processmonitoring/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiMonitoring']);
        Route::get('/processmonitoring/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalBatchMonitoring']);
        Route::get('/processmonitoring/get-jalan-bareng', [ProductionBatchController::class, 'getMainMonitoringJalanBareng']);

        Route::post('processmonitoringstorage/generate-revisi', [ProductionBatchController::class, 'generateRevisiMonitoringStorage']);
        Route::get('/processmonitoringstorage/get-last-revisi', [ProductionBatchController::class, 'getLastRevisiMonitoringStorage']);
        Route::get('/processmonitoringstorage/get-available-additional-batch', [ProductionBatchController::class, 'getAvailableAdditionalMonitoringStorage']);
        Route::get('/processmonitoringstorage/get-jalan-bareng', [ProductionBatchController::class, 'getMainMonitoringStorageJalanBareng']);
    });

    //gga ggas
    Route::prefix('ggaggas')->group(function () {
        Route::get('/menu', [GgaGgasControllerForeman::class, 'menu']);
        Route::get('/dashboard', [GgaGgasControllerForeman::class, 'dashboard']);
        Route::post('/process/store', [GgaGgasControllerForeman::class, 'store'])->name('process.store');

        Route::post('/check-batch-range', [GgaGgasControllerForeman::class, 'checkBatchRangeGGA'])->name('process.checkBatchRange');
        Route::get('/gga', [GgaGgasControllerForeman::class, 'GGA_data']);
        Route::get('/gga/{id}', [GgaGgasControllerForeman::class, 'GGA_detail']);
        Route::get('/gga/id/{id}', [GgaGgasControllerForeman::class, 'showInputFormGGA']);
        Route::post('/gga/update-ajax/{id}', [GgaGgasControllerForeman::class, 'updateAjaxGGA']);
        Route::post('/gga/edit/{id}', [GgaGgasControllerForeman::class, 'editGGA']);


        Route::get('/ggas', [GgaGgasControllerForeman::class, 'GGAS_data']);
        Route::get('/ggas/{id}', [GgaGgasControllerForeman::class, 'GGAS_detail']);
        Route::get('/ggas/id/{id}', [GgaGgasControllerForeman::class, 'showInputFormGGAS']);
        Route::post('/ggas/update-ajax/{id}', [GgaGgasControllerForeman::class, 'updateAjaxGGAS']);
        Route::post('/ggas/edit/{id}', [GgaGgasControllerForeman::class, 'editAjaxGGAS']);
    });

    Route::prefix('blending')->group(function () {
        Route::get('/menu', [BlendingAwalControllerForeman::class, 'menu']);
        Route::get('/awal/dashboard', [BlendingAwalControllerForeman::class, 'dashboard']);
        Route::get('/awal/detail/{id}', [BlendingAwalControllerForeman::class, 'Blending_detail']);
        Route::get('/awal/detail/form/{id}', [BlendingAwalControllerForeman::class, 'showInputFormBlendingAwal']);
        Route::post('/update/{id}', [BlendingAwalControllerForeman::class, 'updateAjaxBlending'])->name('blending.update');
        Route::post('/edit/{id}', [BlendingAwalControllerForeman::class, 'editBlending'])->name('blending.edit');
        Route::get('/awal', [BlendingAwalControllerForeman::class, 'Blending_data'])->name('blending.awal_data');
    });

    Route::prefix('blending/adjust')->group(function () {
        Route::get('/dashboard', [BlendingAdjustControllerForeman::class, 'dashboard']);
        Route::get('/detail/form/{id}', [BlendingAdjustControllerForeman::class, 'showInputFormBlendingAdjust']);
        Route::get('/detail/{id}', [BlendingAdjustControllerForeman::class, 'Blending_detail']);
        Route::post('/store', [BlendingAdjustControllerForeman::class, 'store'])->name('blending_adjust.store');
        Route::post('/update/{id}', [BlendingAdjustControllerForeman::class, 'updateAjaxBlending'])->name('blending_adjust.update');
        Route::post('/edit/{id}', [BlendingAdjustControllerForeman::class, 'editBlending'])->name('blending_adjust.edit');
        Route::get('/data', [BlendingAdjustControllerForeman::class, 'Blending_adjust_data']);
    });

    Route::prefix('blending/mikro')->group(function () {
        Route::get('/detail/form/{id}', [BlendingAdjustControllerForeman::class, 'showInputFormBlendingAdjustMikro']);
        Route::get('/data', [BlendingAdjustControllerForeman::class, 'Blending_adjust_data_mikro']);
        Route::get('/detail/{id}', [BlendingAdjustControllerForeman::class, 'Blending_detail_mikro']);
        Route::post('/update/{id}', [BlendingAdjustControllerForeman::class, 'updateAjaxBlendingMikro'])->name('blending_adjust_mikro.update');
        Route::post('/edit/{id}', [BlendingAdjustControllerForeman::class, 'editBlendingMikro'])->name('blending_adjust_mikro.edit');
    });

    Route::prefix('monitoring/blending')->group(function () {
        Route::get('/dashboard', [MonitoringTurunBlendingControllerForeman::class, 'dashboard']);
        Route::get('/menu', [MonitoringTurunBlendingControllerForeman::class, 'menu']);
        Route::post('/store', [MonitoringTurunBlendingControllerForeman::class, 'store'])->name('monitoring_blending.store');
        Route::post('/update/data', [MonitoringTurunBlendingControllerForeman::class, 'updateMonitoringBlending']);
        Route::post('/detail/data', [MonitoringTurunBlendingControllerForeman::class, 'store_data_foreman']);
        Route::post('/detail/edit', [MonitoringTurunBlendingControllerForeman::class, 'edit_data']);
        Route::get('/detail/data/{id}', [MonitoringTurunBlendingControllerForeman::class, 'showDataDetail']);
        Route::get('/detail/data/id/{id}', [MonitoringTurunBlendingControllerForeman::class, 'showInputFormMonitoringTurunBlending']);
        Route::get('/data', [MonitoringTurunBlendingControllerForeman::class, 'Monitoring_Blending_data']);
        Route::get('/detail/{id}', [MonitoringTurunBlendingControllerForeman::class, 'Monitoring_Blending_detail']);
    });

    Route::prefix('monitoring/pasteurisasi')->group(function () {
        Route::get('/dashboard', [MonitoringPasteurisasiControllerForeman::class, 'dashboard']);
        Route::get('/menu', [MonitoringPasteurisasiControllerForeman::class, 'menu']);
        Route::post('/store', [MonitoringPasteurisasiControllerForeman::class, 'store'])->name('monitoring_pasteurisasi_foreman.store');
        Route::post('/update/data', [MonitoringPasteurisasiControllerForeman::class, 'updateMonitoringPasteurisasi']);
        Route::post('/detail/data', [MonitoringPasteurisasiControllerForeman::class, 'store_data_pasteurisasi']);
        Route::post('/detail/edit', [MonitoringPasteurisasiControllerForeman::class, 'edit_data']);
        Route::get('/detail/data/{id}', [MonitoringPasteurisasiControllerForeman::class, 'showDataDetail']);
        Route::get('/detail/data/id/{id}', [MonitoringPasteurisasiControllerForeman::class, 'showInputFormMonitoringPasteurisasi']);
        Route::get('/data', [MonitoringPasteurisasiControllerForeman::class, 'Monitoring_Pasteurisasi_data']);
        Route::get('/detail/{id}', [MonitoringPasteurisasiControllerForeman::class, 'Monitoring_Pasteurisasi_detail']);
        Route::get('/menu', [MonitoringPasteurisasiControllerForeman::class, 'Monitoring_Pasteurisasi_menu']);
    });

    Route::prefix('monitoring/storage')->group(function () {
        Route::get('/dashboard', [MonitoringStorageControllerForeman::class, 'dashboard']);
        Route::post('/store', [MonitoringStorageControllerForeman::class, 'store'])->name('monitoring_storage_foreman.store');
        Route::get('/data', [MonitoringStorageControllerForeman::class, 'Monitoring_Storage_data']);
        Route::get('/detail/{id}', [MonitoringStorageControllerForeman::class, 'Monitoring_Storage_detail']);
        Route::get('/detail/data/{id}', [MonitoringStorageControllerForeman::class, 'Monitoring_Storage_detail_id']);
        Route::post('/update/data/{id}', [MonitoringStorageControllerForeman::class, 'update_monitoring_storage_makro']);
        Route::post('/edit/data/{id}', [MonitoringStorageControllerForeman::class, 'edit_monitoring_storage_makro']);

        //mikro
        Route::get('/data/mikro', [MonitoringStorageControllerForeman::class, 'Monitoring_Storage_data_mikro']);
        Route::get('/detail/mikro/{id}', [MonitoringStorageControllerForeman::class, 'Monitoring_Storage_detail_mikro']);
        Route::get('/detail/data/mikro/{id}', [MonitoringStorageControllerForeman::class, 'Monitoring_Storage_detail_mikro_id']);
        Route::post('/update/data/mikro/{id}', [MonitoringStorageControllerForeman::class, 'update_monitoring_storage_mikro']);
        Route::post('/edit/data/mikro/{id}', [MonitoringStorageControllerForeman::class, 'edit_monitoring_storage_mikro']);
    });
});

Route::prefix('supervisor')->group(function () {
    Route::get('/manajemen_user', [AuthController::class, 'manage_user']);
    Route::prefix('makro')->group(function () {
        Route::get('/dashboard', [DashboardMakro::class, 'dashboard']);
    });
    Route::prefix('rmpm')->group(function () {
        Route::get('/', [RMPMControllerSupervisor::class, 'menu'])->name('rmpm_supervisor.menu');
        Route::get('/dashboard', [RMPMControllerSupervisor::class, 'dashboard'])->name('rmpm_supervisor.dashboard');
        Route::get('/list/rm', [RMPMControllerSupervisor::class, 'dataRM'])->name('dataRM_supervisor');
        Route::get('/list/data/{jenis}', [RMPMControllerSupervisor::class, 'list_data'])->name('rmpm_supervisor.list_data');
        Route::get('/detail/data/{id}', [RMPMControllerSupervisor::class, 'detail_data'])->name('rmpm_supervisor.detail_data');
        Route::post('/update/{id}', [RMPMControllerSupervisor::class, 'updateDisposisiLong'])->name('rmpm_supervisor.updateDisposisiLong');
    });

    Route::prefix('sampling')->group(function () {
        // Sampling Kondisi Mobil
        Route::get('/kondisi-mobil/{id}', [SamplingControllerSupervisor::class, 'showKondisiMobil']);
        Route::post('/kondisi-mobil', [SamplingControllerSupervisor::class, 'storeKondisiMobil']);
        Route::post('/edit/kondisi-mobil/{id}', [SamplingControllerSupervisor::class, 'updateKondisiMobil']);


        // Sampling Dokumen
        Route::get('/dokumen/{id}', [SamplingControllerSupervisor::class, 'showDokumen']);
        Route::post('/dokumen', [SamplingControllerSupervisor::class, 'storeDokumen']);
        Route::post('/edit/dokumen/{id}', [SamplingControllerSupervisor::class, 'updateDokumen']);

        // Sampling Fisik Kemasan
        Route::get('/fisik-kemasan/{id}', [SamplingControllerSupervisor::class, 'showFisikKemasan']);
        Route::post('/fisik-kemasan', [SamplingControllerSupervisor::class, 'storeFisikKemasan']);
        Route::post('/edit/fisik-kemasan/{id}', [SamplingControllerSupervisor::class, 'updateKemasan']);

        // Sampling Fisik Raw (Hanya untuk Gula, Tidak untuk Garam)
        Route::get('/fisik-raw/{id}', [SamplingControllerSupervisor::class, 'showFisikRaw']);
        Route::post('/fisik-raw', [SamplingControllerSupervisor::class, 'storeFisikRaw']);
        Route::post('/edit/fisik-raw/{id}', [SamplingControllerSupervisor::class, 'updateFisikRaw']);
    });
    //gga ggas
    Route::prefix('ggaggas')->group(function () {
        Route::get('/dashboard', [GgaGgasControllerSupervisor::class, 'dashboard']);
        Route::get('/menu', [GgaGgasControllerSupervisor::class, 'menu']);
        Route::post('/process/store', [GgaGgasControllerSupervisor::class, 'store']);

        Route::post('/check-batch-range', [GgaGgasControllerSupervisor::class, 'checkBatchRangeGGA']);
        Route::get('/gga', [GgaGgasControllerSupervisor::class, 'GGA_data']);
        Route::get('/gga/{id}', [GgaGgasControllerSupervisor::class, 'GGA_detail']);
        Route::get('/gga/id/{id}', [GgaGgasControllerSupervisor::class, 'showInputFormGGA']);
        Route::post('/gga/update-ajax/{id}', [GgaGgasControllerSupervisor::class, 'updateAjaxGGA']);
        Route::post('/gga/edit/{id}', [GgaGgasControllerSupervisor::class, 'editGGA']);


        Route::get('/ggas', [GgaGgasControllerSupervisor::class, 'GGAS_data']);
        Route::get('/ggas/{id}', [GgaGgasControllerSupervisor::class, 'GGAS_detail']);
        Route::get('/ggas/id/{id}', [GgaGgasControllerSupervisor::class, 'showInputFormGGAS']);
        Route::post('/ggas/update-ajax/{id}', [GgaGgasControllerSupervisor::class, 'updateAjaxGGAS']);
        Route::post('/ggas/edit/{id}', [GgaGgasControllerSupervisor::class, 'editAjaxGGAS']);
    });

    Route::prefix('blending')->group(function () {
        Route::get('awal/dashboard', [BlendingAwalControllerSupervisor::class, 'dashboard']);
        Route::get('/menu', [BlendingAwalControllerSupervisor::class, 'menu']);
        Route::get('/awal/detail/{id}', [BlendingAwalControllerSupervisor::class, 'Blending_detail']);
        Route::get('/awal/detail/form/{id}', [BlendingAwalControllerSupervisor::class, 'showInputFormBlendingAwal']);
        Route::post('/update/{id}', [BlendingAwalControllerSupervisor::class, 'updateAjaxBlending']);
        Route::post('/edit/{id}', [BlendingAwalControllerSupervisor::class, 'editBlending']);
        Route::get('/awal', [BlendingAwalControllerSupervisor::class, 'Blending_data']);
    });

    Route::prefix('blending/adjust')->group(function () {
        Route::get('/dashboard', [BlendingAdjustControllerSupervisor::class, 'dashboard']);
        Route::get('/detail/form/{id}', [BlendingAdjustControllerSupervisor::class, 'showInputFormBlendingAdjust']);
        Route::get('/detail/{id}', [BlendingAdjustControllerSupervisor::class, 'Blending_detail']);
        Route::post('/store', [BlendingAdjustControllerSupervisor::class, 'store'])->name('blending_adjust.store');
        Route::post('/update/{id}', [BlendingAdjustControllerSupervisor::class, 'updateAjaxBlending']);
        Route::post('/edit/{id}', [BlendingAdjustControllerSupervisor::class, 'editBlending']);
        Route::get('/data', [BlendingAdjustControllerSupervisor::class, 'Blending_adjust_data']);
    });

    Route::prefix('blending/mikro')->group(function () {
        Route::get('/detail/form/{id}', [BlendingAdjustControllerSupervisor::class, 'showInputFormBlendingAdjustMikro']);
        Route::get('/data', [BlendingAdjustControllerSupervisor::class, 'Blending_adjust_data_mikro']);
        Route::get('/detail/{id}', [BlendingAdjustControllerSupervisor::class, 'Blending_detail_mikro']);
        Route::post('/update/{id}', [BlendingAdjustControllerSupervisor::class, 'updateAjaxBlendingMikro']);
        Route::post('/edit/{id}', [BlendingAdjustControllerSupervisor::class, 'editBlendingMikro']);
    });

    Route::prefix('monitoring/blending')->group(function () {
        Route::get('/dashboard', [MonitoringTurunBlendingControllerSupervisor::class, 'dashboard']);
        Route::get('/menu', [MonitoringTurunBlendingControllerSupervisor::class, 'menu']);
        Route::post('/store', [MonitoringTurunBlendingControllerSupervisor::class, 'store']);
        Route::post('/update/data', [MonitoringTurunBlendingControllerSupervisor::class, 'updateMonitoringBlending']);
        Route::post('/detail/data', [MonitoringTurunBlendingControllerSupervisor::class, 'store_data_supervisor']);
        Route::post('/detail/edit', [MonitoringTurunBlendingControllerSupervisor::class, 'edit_data']);
        Route::get('/detail/data/{id}', [MonitoringTurunBlendingControllerSupervisor::class, 'showDataDetail']);
        Route::get('/detail/data/id/{id}', [MonitoringTurunBlendingControllerSupervisor::class, 'showInputFormMonitoringTurunBlending']);
        Route::get('/data', [MonitoringTurunBlendingControllerSupervisor::class, 'Monitoring_Blending_data']);
        Route::get('/detail/{id}', [MonitoringTurunBlendingControllerSupervisor::class, 'Monitoring_Blending_detail']);
    });

    Route::prefix('monitoring/pasteurisasi')->group(function () {
        Route::get('/dashboard', [MonitoringPasteurisasiControllerSupervisor::class, 'dashboard']);
        Route::get('/menu', [MonitoringPasteurisasiControllerSupervisor::class, 'menu']);
        Route::post('/store', [MonitoringPasteurisasiControllerSupervisor::class, 'store'])->name('monitoring_pasteurisasi.store');
        Route::post('/update/data', [MonitoringPasteurisasiControllerSupervisor::class, 'updateMonitoringPasteurisasi']);
        Route::post('/detail/data', [MonitoringPasteurisasiControllerSupervisor::class, 'store_data_pasteurisasi']);
        Route::post('/detail/edit', [MonitoringPasteurisasiControllerSupervisor::class, 'edit_data']);
        Route::get('/detail/data/{id}', [MonitoringPasteurisasiControllerSupervisor::class, 'showDataDetail']);
        Route::get('/detail/data/id/{id}', [MonitoringPasteurisasiControllerSupervisor::class, 'showInputFormMonitoringPasteurisasi']);
        Route::get('/data', [MonitoringPasteurisasiControllerSupervisor::class, 'Monitoring_Pasteurisasi_data']);
        Route::get('/detail/{id}', [MonitoringPasteurisasiControllerSupervisor::class, 'Monitoring_Pasteurisasi_detail']);
        Route::get('/menu', [MonitoringPasteurisasiControllerSupervisor::class, 'Monitoring_Pasteurisasi_menu']);
    });

    Route::prefix('monitoring/storage')->group(function () {
        Route::get('/dashboard', [MonitoringStorageControllerSupervisor::class, 'dashboard']);
        Route::post('/store', [MonitoringStorageControllerSupervisor::class, 'store'])->name('monitoring_storage.store');
        Route::get('/data', [MonitoringStorageControllerSupervisor::class, 'Monitoring_Storage_data']);
        Route::get('/detail/{id}', [MonitoringStorageControllerSupervisor::class, 'Monitoring_Storage_detail']);
        Route::get('/detail/data/{id}', [MonitoringStorageControllerSupervisor::class, 'Monitoring_Storage_detail_id']);
        Route::post('/update/data/{id}', [MonitoringStorageControllerSupervisor::class, 'update_monitoring_storage_makro']);
        Route::post('/edit/data/{id}', [MonitoringStorageControllerSupervisor::class, 'edit_monitoring_storage_makro']);

        //mikro
        Route::get('/data/mikro', [MonitoringStorageControllerSupervisor::class, 'Monitoring_Storage_data_mikro']);
        Route::get('/detail/mikro/{id}', [MonitoringStorageControllerSupervisor::class, 'Monitoring_Storage_detail_mikro']);
        Route::get('/detail/data/mikro/{id}', [MonitoringStorageControllerSupervisor::class, 'Monitoring_Storage_detail_mikro_id']);
        Route::post('/update/data/mikro/{id}', [MonitoringStorageControllerSupervisor::class, 'update_monitoring_storage_mikro']);
        Route::post('/edit/data/mikro/{id}', [MonitoringStorageControllerSupervisor::class, 'edit_monitoring_storage_mikro']);
    });
});
Route::get('/api/qr-code/{id}', function ($id) {
    $url = route('rmpm.detailIdentitas', ['id' => $id]);
    $qr = DNS2D::getBarcodePNG($url, 'QRCODE');
    return response($qr)->header('Content-Type', 'image/png');
});

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/unread', [NotificationController::class, 'unreadNotifications']);
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::post('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead']);

Route::prefix('data')->group(function () {
    Route::get('/', [ManageStandarDataController::class, 'tampilan']);          // 🔍 List semua warna
    Route::get('warna/', [ManageStandarDataController::class, 'index']);          // 🔍 List semua warna
    Route::post('warna/', [ManageStandarDataController::class, 'store']);         // ➕ Tambah warna baru
    Route::get('warna/{id}', [ManageStandarDataController::class, 'show']);       // 📄 Detail warna
    Route::put('warna/{id}', [ManageStandarDataController::class, 'update']);     // ✏️ Update warna
    Route::delete('warna/{id}', [ManageStandarDataController::class, 'destroy']); // ❌ Hapus warna
});
