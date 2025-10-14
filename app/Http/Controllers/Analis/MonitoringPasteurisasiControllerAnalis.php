<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use App\Models\MonitoringPasteurisasi;
use App\Models\MonitoringPasteurisasiData;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonitoringPasteurisasiControllerAnalis extends Controller
{
    public function Monitoring_Pasteurisasi_data()
    {

        $productionBatches = ProductionBatch::orderby('created_at', 'desc')->with('MonitoringPasteurisasi')->has('MonitoringPasteurisasi')->get();

        return view('analis.monitoring.pasteurisasi.data', compact('productionBatches'));
    }

    public function Monitoring_Pasteurisasi_detail($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringPasteurisasi.additionalBatches',
            'MonitoringPasteurisasi.monitoringPasteurisasiData',
        ])->findOrFail($id);

        // Kelompokkan berdasarkan batch_range
        $grouped = $productionBatch->MonitoringPasteurisasi;

        $filtered = collect();

        $filtered = $productionBatch->MonitoringPasteurisasi->map(function ($item) use ($productionBatch) {
            $item->additional_batch_info = $item->additionalBatches->isNotEmpty()
                ? $item->additionalBatches
                : null;
            $item->po_number = $productionBatch->po_number;
            $item->data_count = $item->monitoringPasteurisasiData->count();
            return $item;
        });

        // return response()->json($filtered->values());
        return view('analis.monitoring.pasteurisasi.detail_data', [
            'productionBatch' => $productionBatch,
            'filteredMonitoring' => $filtered->values()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'production_batch_id' => 'required|exists:production_batches,id',
            'batch' => 'required',
            // 'batch_end' => 'required',
            'storage' => 'nullable|string', // ← ubah jadi nullable
            'no_pasteurisasi' => 'required',
            //required colume harus decimal
            'volume' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = MonitoringPasteurisasi::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch range ini sudah digunakan untuk Production Batch yang sama.',
            ], 409);
        }

        // Simpan data Pasterisasi After Adjust
        MonitoringPasteurisasi::create([
            'production_batch_id' => $request->production_batch_id,
            'batch_range' => $request->batch,
            'nomor_pasteurisasi' => $request->no_pasteurisasi,
            'volume_pasteurisasi' => $request->volume
        ]);

        // Jika ada input 'storage', update di tabel ProductionBatch
        if ($request->filled('storage')) {
            $productionBatch = ProductionBatch::find($request->production_batch_id);
            $productionBatch->storage = $request->storage;
            $productionBatch->save();
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function store_data_pasteurisasi(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'monitoring_pasteurisasi_id' => 'required|exists:monitoring_pasteurisasi,id',
            'brix' => 'required|numeric',
            'nacl' => 'required|numeric',
            'bj' => 'required|numeric',
            'visco' => 'nullable|string',
            'aw' => 'nullable|string',
            'buih' => 'nullable|string',
            'organo' => 'nullable|string',
            'ph' => 'nullable|numeric',
            'endapan' => 'nullable|string',
            'warna' => 'nullable|string',
            'shift' => 'required|in:1,2,3',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $existingData = MonitoringPasteurisasiData::where('monitoring_pasteurisasi_id', $request->monitoring_pasteurisasi_id)->get();

        // Jika shift sudah ada
        $existingShift = $existingData->where('shift', $request->shift)->first();
        if ($existingShift) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data untuk shift ini sudah ada.'
            ], 409); // 409 Conflict
        }

        // Jika data sudah lebih dari 3
        if ($existingData->count() >= 3) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data hanya boleh maksimal 3 shift.'
            ], 409); // 409 Conflict
        }

        try {
            $username = session('username');
            // Simpan data ke database
            MonitoringPasteurisasiData::create([
                'monitoring_pasteurisasi_id' => $request->monitoring_pasteurisasi_id,
                'brix' => $request->brix,
                'nacl' => $request->nacl,
                'bj' => $request->bj,
                'visco' => $request->visco,
                'aw' => $request->aw,
                'buih' => $request->buih,
                'organo' => $request->organo,
                'ph' => $request->ph,
                'endapan' => $request->endapan,
                'warna' => $request->warna,
                'shift' => $request->shift,
                'created_by' => $username,
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Data berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function showDataDetail($id)
    {
        $monitoring = MonitoringPasteurisasi::findOrFail($id);
        $dataanalis = MonitoringPasteurisasiData::where('monitoring_pasteurisasi_id', $id)->get();

        return response()->json([
            'monitoring' => $monitoring,
            'dataanalis' => $dataanalis
        ]);
    }

    public function edit_data(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_edit' => 'required',
            'brix_edit' => 'required|numeric',
            'nacl_edit' => 'required|numeric',
            'bj_edit' => 'required|numeric',
            'visco_edit' => 'nullable|string',
            'aw_edit' => 'nullable|string',
            'buih_edit' => 'nullable|string',
            'organo_edit' => 'nullable|string',
            'ph_edit' => 'nullable|numeric',
            'endapan_edit' => 'nullable|string',
            'warna_edit' => 'nullable|string',

        ]);
        $Data = MonitoringPasteurisasiData::findOrFail($request->id_edit);

        try {
            // Simpan data ke database
            $username = session('username');
            $dataUpdate = [
                'brix' => $request->brix_edit,
                'nacl' => $request->nacl_edit,
                'bj' => $request->bj_edit,
                'visco' => $request->visco_edit,
                'aw' => $request->aw_edit,
                'buih' => $request->buih_edit,
                'organo' => $request->organo_edit,
                'ph' => $request->ph_edit,
                'endapan' => $request->endapan_edit,
                'warna' => $request->warna_edit,
                'created_by' => $username,
            ];


            $Data->update($dataUpdate);

            return response()->json([
                'status' => 'ok',
                'message' => 'Data berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function updateMonitoringPasteurisasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monitoring_id' => 'required|exists:monitoring_pasteurisasi,id',
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
            'disposition_remarks' => 'nullable|string|max:255',
            'adjustment_qty_air' => 'nullable',
            'adjustment_qty_garam' => 'nullable',
            'adjustment_qty_gula' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = MonitoringPasteurisasi::findOrFail($request->monitoring_id);
        if ($blending->disposition) {
            return response()->json([
                'errors' => ['Data dengan ID ini sudah memiliki disposisi .']
            ], 422);
        }


        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi remark wajib isi untuk kondisi tertentu
        if (!in_array($disposition, ['Release']) && empty(trim($remarks))) {
            return response()->json([
                'errors' => ['Kolom keterangan (remarks) wajib diisi untuk disposition ini.']
            ], 422);
        }

        if ($disposition === 'Release') {
            $remarks = '-';
        }
        $username = session('username');
        $dataUpdate = [

            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
            'created_by' => $username,
        ];

        // Jika adjustment
        if ($disposition === 'Adjustment') {
            $dataUpdate['adjustment_qty_air'] = $request->adjustment_qty_air;
            $dataUpdate['adjustment_qty_garam'] = $request->adjustment_qty_garam;
            $dataUpdate['adjustment_qty_gula'] = $request->adjustment_qty_gula;
            $dataUpdate['not_standar'] = true;
        }
        if ($disposition === 'Jalan Bareng') {

            $dataUpdate['not_standar'] = true;
        }
        if ($disposition === 'Leveling') {

            $dataUpdate['not_standar'] = true;
        }

        // Jika resampling
        if ($disposition === 'Resampling') {
            // $dataUpdate['disposition_remarks'] = $disposition;
            $dataUpdate['not_standar'] = true;
        }

        $blending->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function showInputFormMonitoringPasteurisasi($id)
    {
        $monitoring = MonitoringPasteurisasi::with('monitoringPasteurisasiData', 'productionBatch')->find($id);
        return view('analis.monitoring.pasteurisasi.analisis_data_detail', compact('monitoring'));
    }
}
