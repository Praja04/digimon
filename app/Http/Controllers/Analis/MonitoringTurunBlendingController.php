<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonitoringTurunBlending;
use App\Models\MonitoringTurunBlendingData;
use App\Models\ProductionBatch;
use App\Models\MonitoringTurunBlendingRelation;
use Illuminate\Support\Facades\Validator;

class MonitoringTurunBlendingController extends Controller
{
    //
    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('analis.monitoring.menu');
    }
    public function Monitoring_Blending_data()
    {

        $productionBatches = ProductionBatch::with('MonitoringTurunBlending')->has('MonitoringTurunBlending')->get();
        //return json
        //return response()->json($productionBatches);

         return view('analis.monitoring.turun_blending', compact('productionBatches'));
    }

    public function Monitoring_Blending_detail($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringTurunBlending.additionalBatches', 'MonitoringTurunBlending.monitoringData',
        ])->findOrFail($id);

        // Kelompokkan berdasarkan batch_range
        $grouped = $productionBatch->MonitoringTurunBlending->groupBy('batch_range');

        $filtered = collect();

        foreach ($grouped as $batchRange => $items) {
            // Prioritaskan yang disposition-nya 'Release' atau 'Release Bersyarat'
            $preferred = $items->first(function ($item) {
                return in_array($item->disposition, ['Release', 'Release Bersyarat']);
            });

            // Jika tidak ada, ambil yang pertama saja sebagai fallback
            $selected = $preferred ?: $items->first();

            // Tambahkan additional_batch_info dan po_number
            $selected->additional_batch_info = $selected->additionalBatches->isNotEmpty()
                ? $selected->additionalBatches
                : null;
            $selected->po_number = $productionBatch->po_number;
            $selected->data_count = $selected->monitoringData->count();
            $filtered->push($selected);
        }

        // return response()->json($filtered->values());
        return view('analis.monitoring.detail_data', [
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
            'no_blending' => 'required',
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

        $exists = MonitoringTurunBlending::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch range ini sudah digunakan untuk Production Batch yang sama.',
            ], 409);
        }

        // Simpan data Blending After Adjust
        MonitoringTurunBlending::create([
            'production_batch_id' => $request->production_batch_id,
            'batch_range' => $request->batch,
            'nomor_blending' => $request->no_blending,
            'volume_blending' => $request->volume
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


    public function store_data_analisa(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'monitoring_turun_blending_id' => 'required|exists:monitoring_turun_blending,id',
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
        $existingData = MonitoringTurunBlendingData::where('monitoring_turun_blending_id', $request->monitoring_turun_blending_id)->get();

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
            // Simpan data ke database
            MonitoringTurunBlendingData::create([
                'monitoring_turun_blending_id' => $request->monitoring_turun_blending_id,
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


    public function showInputFormMonitoringTurunBlending($id)
    {
        $monitoring = MonitoringTurunBlending::with('monitoringData', 'productionBatch')->find($id);
        return view('analis.monitoring.analisis_data_detail', compact('monitoring'));
    }

    public function showDataDetail($id)
    {
        $monitoring = MonitoringTurunBlending::findOrFail($id);
        $dataAnalisa = MonitoringTurunBlendingData::where('monitoring_turun_blending_id', $id)->get();

        return response()->json([
            'monitoring' => $monitoring,
            'dataAnalisa' => $dataAnalisa
        ]);
    }

    public function updateMonitoringBlending(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monitoring_id' => 'required|exists:monitoring_turun_blending,id',
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
            'disposition_remarks' => 'nullable|string|max:255',
            'adjustment_qty' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = MonitoringTurunBlending::findOrFail($request->monitoring_id);

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

        $dataUpdate = [
           
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
        ];

        // Jika adjustment
        if ($disposition === 'Adjustment') {
            $dataUpdate['adjusment_qty'] = $request->adjustment_qty;
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

}
