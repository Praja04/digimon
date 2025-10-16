<?php

namespace App\Http\Controllers\Foreman;

use App\Http\Controllers\Controller;
use App\Models\ManageWarnaModel;
use Illuminate\Http\Request;
use App\Models\MonitoringTurunBlending;
use App\Models\MonitoringTurunBlendingData;
use App\Models\ProductionBatch;
use App\Models\MonitoringTurunBlendingRelation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class MonitoringTurunBlendingControllerForeman extends Controller
{
    //
    public function dashboard()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        // Menampilkan view 'productionbatch.index' dengan data
        return view('foreman.monitoring.dashboard_turun');
    }

    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('foreman.monitoring.menu');
    }

    public function Monitoring_Blending_data()
    {
        $productionBatches = ProductionBatch::with('MonitoringTurunBlending')
            ->has('MonitoringTurunBlending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return ($batch->isMonitoringBlendingComplete()) ? 1 : 0;
            })
            ->values();
        //return json
        //return response()->json($productionBatches);

        return view('foreman.monitoring.turun_blending', compact('productionBatches'));
    }

    public function Monitoring_Blending_detail($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringTurunBlending.additionalBatches',
            'MonitoringTurunBlending.monitoringData',
        ])->findOrFail($id);

        // Kelompokkan berdasarkan batch_range
        $grouped = $productionBatch->MonitoringTurunBlending;

        $filtered = collect();

        $filtered = $productionBatch->MonitoringTurunBlending->map(function ($item) use ($productionBatch) {
            $item->additional_batch_info = $item->additionalBatches->isNotEmpty()
                ? $item->additionalBatches
                : null;
            $item->po_number = $productionBatch->po_number;
            $item->data_count = $item->monitoringData->count();
            return $item;
        });

        $manageWarna = ManageWarnaModel::orderBy('nama_warna', 'asc')->get();
        // return response()->json($filtered->values());
        return view('foreman.monitoring.detail_data', [
            'productionBatch' => $productionBatch,
            'filteredMonitoring' => $filtered->values(),
            'manageWarna' => $manageWarna
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


    public function store_data_foreman(Request $request)
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
            'production_time' => 'required',
            // 'shift' => 'required|in:1,2,3',
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

        // Tentukan shift otomatis berdasarkan waktu saat ini
        $currentHour = (int) now()->format('H');
        if ($currentHour >= 6 && $currentHour < 14) {
            $shift = 1;
        } elseif ($currentHour >= 14 && $currentHour < 22) {
            $shift = 2;
        } else {
            $shift = 3;
        }

        try {
            $username = session('username');
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
                'shift' => $shift,
                'production_time' =>  Carbon::parse($request->production_time)->format('Y-m-d H:i:s'),
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
        $Data = MonitoringTurunBlendingData::findOrFail($request->id_edit);

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

    public function showInputFormMonitoringTurunBlending($id)
    {
        $monitoring = MonitoringTurunBlending::with('monitoringData', 'productionBatch')->find($id);
        return view('foreman.monitoring.analisis_data_detail', compact('monitoring'));
    }

    public function showDataDetail($id)
    {
        $monitoring = MonitoringTurunBlending::findOrFail($id);
        $dataforeman = MonitoringTurunBlendingData::where('monitoring_turun_blending_id', $id)->get();

        return response()->json([
            'monitoring' => $monitoring,
            'dataforeman' => $dataforeman
        ]);
    }

    public function updateMonitoringBlending(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'monitoring_id' => 'required|exists:monitoring_turun_blending,id',
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

        $blending = MonitoringTurunBlending::findOrFail($request->monitoring_id);
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
}
