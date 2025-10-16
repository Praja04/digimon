<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use App\Models\MonitoringStorageModel;
use App\Models\MonitoringStorageMikroModel;
use App\Models\KonfirmasiMonitoringStorageMikroModel;
use App\Models\ManageWarnaModel;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonitoringStorageController extends Controller
{
    //
    public function Monitoring_Storage_data()
    {
        $productionBatches = ProductionBatch::orderby('created_at', 'desc')->with('MonitoringStorage')->has('MonitoringStorage')->get();
        return view('analis.monitoring.monitoring_storage.monitoring_storage', compact('productionBatches'));
    }

    public function Monitoring_Storage_data_mikro()
    {
        $productionBatches = ProductionBatch::orderby('created_at', 'desc')->with('MonitoringStorageMikro')->has('MonitoringStorageMikro')->get();
        return view('analis.monitoring.monitoring_storage.monitoring_storage_mikro', compact('productionBatches'));
    }


    public function Monitoring_Storage_detail($id)
    {

        $productionBatch = ProductionBatch::findOrFail($id);

        foreach ($productionBatch->MonitoringStorage as $data) {
            // Tambahkan properti custom 'additional_batch_info' ke setiap data
            $data->additional_batch_info = $data->additionalBatches->isNotEmpty()
                ? $data->additionalBatches
                : null;

            $data->po_number = $productionBatch->po_number;
        }

        $manageWarna = ManageWarnaModel::orderBy('nama_warna', 'asc')->get();
        //    return response()->json($productionBatch->MonitoringStorage);
        return view('analis.monitoring.monitoring_storage.detail_data', [
            'productionBatch' => $productionBatch,
            'filteredMonitoringStorage' => $productionBatch->MonitoringStorage,
            'manageWarna' => $manageWarna,
        ]);
    }

    public function Monitoring_Storage_detail_mikro($id)
    {

        $productionBatch = ProductionBatch::findOrFail($id);

        // Kelompokkan berdasarkan batch_range
        foreach ($productionBatch->MonitoringStorageMikro as $data) {


            $data->po_number = $productionBatch->po_number;
        }


        // return response()->json($filtered->values());
        return view('analis.monitoring.monitoring_storage.detail_data_mikro', [
            'productionBatch' => $productionBatch,
            'filteredMonitoringStorage' => $productionBatch
        ]);
    }


    public function Monitoring_Storage_detail_id($id)
    {

        $data = MonitoringStorageModel::find($id);
        return view('analis.monitoring.monitoring_storage.analisis_data_detail_id', compact('data'));
    }

    public function Monitoring_Storage_detail_mikro_id($id)
    {

        $data = MonitoringStorageModel::find($id);
        return view('analis.monitoring.monitoring_storage.analisis_data_detail_mikro_id', compact('data'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'production_batch_id' => 'required|exists:production_batches,id',
            'batch' => 'required',
            'storage' => 'nullable|string', // ← ubah jadi nullable
            'no_blending' => 'required',
            'volume' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = MonitoringStorageModel::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch range ini sudah digunakan untuk Production Batch yang sama.',
            ], 409);
        }

        // Simpan data Blending After Adjust
        MonitoringStorageModel::create([
            'production_batch_id' => $request->production_batch_id,
            'batch_range' => $request->batch,
            'nomor_blending' => $request->no_blending,
            'volume_blending' => $request->volume
        ]);
        MonitoringStorageMikroModel::create([
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

    public function update_monitoring_storage_makro(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brix' => 'required|numeric|min:0|max:100',
            'nacl' => 'required|numeric|min:0|max:100',
            'bj' => 'required|string|max:20',
            'visco' => 'required|string|max:20',
            'aw' => 'required|string|max:20',
            'buih' => 'required|string|max:20',
            'organo' => 'required|string|max:20',
            'endapan' => 'required|string|max:20',
            'warna' => 'required|string|max:20',
            'disposition' => 'required',
            'disposition_remarks' => 'nullable|string|max:255',
            'adjustment_qty' => 'nullable|integer|min:1',
        ], [
            'brix.max' => 'Nilai brix melebihi batas input yaitu 100.',
            'nacl.max' => 'Nilai NaCl melebihi batas input yaitu 100.',
            'brix.min' => 'Nilai brix tidak boleh negatif.',
            'nacl.min' => 'Nilai NaCl tidak boleh negatif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $data = MonitoringStorageModel::findOrFail($id);
        if (
            $data->disposition
        ) {
            return response()->json([
                'errors' => ['Data id sudah ada sudah ada dan tidak ada perubahan.']
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
            'brix' => $request->brix,
            'nacl' => $request->nacl,
            'bj' => $request->bj,
            'visco' => $request->visco,
            'aw' => $request->aw,
            'buih' => $request->buih,
            'ph' => $request->ph,
            'organo' => $request->organo,
            'endapan' => $request->endapan,
            'warna' => $request->warna,
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
            'created_by' => $username,
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

        $data->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function showInputFormMonitoringStorage($id)
    {
        $monitoring_storage = MonitoringStorageModel::find($id);
        return view('analis.monitoring_storage.monitoring_storage_detail_id', compact('monitoring_storage'));
    }

    public function showInputFormMonitoringStorageMikro($id)
    {
        $monitoring_storage = MonitoringStorageMikroModel::find($id);
        return view('analis.monitoring_storage.monitoring_storage_detail_mikro_id', compact('monitoring_storage'));
    }

    public function update_monitoring_storage_mikro(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'eb' => 'nullable|numeric|min:0|max:100',
            'tpc' => 'nullable|numeric|min:0|max:100',
            'ym' => 'nullable|string|max:20',
            'nama_analis' => 'string',
            'shift' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $data = MonitoringStorageMikroModel::findOrFail($id);

        // 🛡️ Validasi agar data tidak bisa diisi ulang
        if (
            ($request->has('eb') && $request->eb !== null && $data->eb !== null) ||
            ($request->has('tpc') && $request->tpc !== null && $data->tpc !== null) ||
            ($request->has('ym') && $request->ym !== null && $data->ym !== null)
        ) {
            return response()->json([
                'error' => 'Data EB/TPC/YM sudah diisi sebelumnya dan tidak bisa diubah ulang.'
            ], 422);
        }

        // 📝 Buat konfirmasi
        KonfirmasiMonitoringStorageMikroModel::create([
            'blending_after_adjust_mikro_id' => $data->id,
            'nama_analis' => $request->nama_analis,
            'shift' => $request->shift,
        ]);

        // 🔄 Update hanya field yang dikirim dan tidak null
        $dataUpdate = collect(['eb', 'tpc', 'ym'])->mapWithKeys(function ($field) use ($request, $data) {
            return [$field => $request->has($field) && $request->$field !== null ? $request->$field : $data->$field];
        })->toArray();

        $data->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
