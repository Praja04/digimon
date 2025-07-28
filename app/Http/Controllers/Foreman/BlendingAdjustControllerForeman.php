<?php

namespace App\Http\Controllers\Foreman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlendingAwalModel;
use App\Models\BlendingAfterAdjustModel;
use App\Models\BlendingAfterAdjustMikroModel;
use App\Models\KonfirmasiBlendingAdjustMikroModel;
use App\Models\ProductionBatch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class BlendingAdjustControllerForeman extends Controller
{
    //
    public function dashboard()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }


        return view('foreman.blending.dashboard_blending_after');
    }
    public function Blending_adjust_data()
    {

        $productionBatches = ProductionBatch::with('blendingAfterAdjust')->has('blendingAfterAdjust')->get();

        return view('foreman.blending.blending_adjust', compact('productionBatches'));
    }

    //
    public function Blending_adjust_data_mikro()
    {

        $productionBatches = ProductionBatch::with('blendingAfterAdjust')->has('blendingAfterAdjust')->get();

        return view('foreman.blending.blending_adjust_mikro', compact('productionBatches'));
    }

    public function Blending_detail($id)
    {
        $productionBatch = ProductionBatch::with([
            'blendingAfterAdjust.additionalBatches'
        ])->findOrFail($id);

        // Kelompokkan berdasarkan batch_range
        $grouped = $productionBatch->blendingAfterAdjust->groupBy('batch_range');

        $filteredblendingAfterAdjust = collect();

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

            $filteredblendingAfterAdjust->push($selected);
        }

       // return response()->json($filteredblendingAfterAdjust->values());
        return view('foreman.blending.blending_adjust_detail', [
            'productionBatch' => $productionBatch,
            'filteredBlendingAwal' => $filteredblendingAfterAdjust->values()
        ]);
    }

    public function Blending_detail_mikro($id)
    {
        $productionBatch = ProductionBatch::with('blendingAfterAdjustMikro')->findOrFail($id);

        //return response()->json($productionBatch);
        return view('foreman.blending.blending_adjust_detail_mikro', [
            'productionBatch' => $productionBatch,
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

        $exists = BlendingAfterAdjustModel::where('production_batch_id', $request->production_batch_id)
        ->where('batch_range', $request->batch)
        ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch range ini sudah digunakan untuk Production Batch yang sama.',
            ], 409);
        }

        // Simpan data Blending After Adjust
        BlendingAfterAdjustModel::create([
            'production_batch_id' => $request->production_batch_id,
            'batch_range' => $request->batch,
            'nomor_blending' => $request->no_blending,
            'volume_blending' => $request->volume
        ]);
        BlendingAfterAdjustMikroModel::create([
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


    public function updateAjaxBlending(Request $request, $id)
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
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
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

        $blending = BlendingAfterAdjustModel::findOrFail($id);
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

        $dataUpdate = [
            'brix' => $request->brix,
            'nacl' => $request->nacl,
            'bj' => $request->bj,
            'visco' => $request->visco,
            'aw' => $request->aw,
            'buih' => $request->buih,
            'organo' => $request->organo,
            'endapan' => $request->endapan,
            'warna' => $request->warna,
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

    public function editBlending(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brix_edit' => 'required|numeric|min:0|max:100',
            'nacl_edit' => 'required|numeric|min:0|max:100',
            'bj_edit' => 'required|string|max:20',
            'visco_edit' => 'required|string|max:20',
            'aw_edit' => 'required|string|max:20',
            'buih_edit' => 'required|string|max:20',
            'organo_edit' => 'required|string|max:20',
            'endapan_edit' => 'required|string|max:20',
            'warna_edit' => 'required|string|max:20',
            'disposition_edit' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
            'disposition_remarks_edit' => 'nullable|string|max:255',
            'adjustment_qty_edit' => 'nullable|integer|min:1',
        ], [
            'brix_edit.max' => 'Nilai brix melebihi batas input yaitu 100.',
            'nacl_edit.max' => 'Nilai NaCl melebihi batas input yaitu 100.',
            'brix_edit.min' => 'Nilai brix tidak boleh negatif.',
            'nacl_edit.min' => 'Nilai NaCl tidak boleh negatif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = BlendingAfterAdjustModel::findOrFail($id);
     
        $disposition = $request->disposition_edit;
        $remarks = $request->disposition_remarks_edit ?? null;

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
            'brix' => $request->brix_edit,
            'nacl' => $request->nacl_edit,
            'bj' => $request->bj_edit,
            'visco' => $request->visco_edit,
            'aw' => $request->aw_edit,
            'buih' => $request->buih_edit,
            'organo' => $request->organo_edit,
            'endapan' => $request->endapan_edit,
            'warna' => $request->warna_edit,
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
        ];

        // Jika adjustment
        if ($disposition === 'Adjustment') {
            $dataUpdate['adjusment_qty'] = $request->adjustment_qty_edit;
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


    public function showInputFormBlendingAdjust($id)
    {
        $blending = BlendingAfterAdjustModel::find($id);
        return view('foreman.blending.blending_adjust_detail_id', compact('blending'));
    }

    public function showInputFormBlendingAdjustMikro($id)
    {
        $blending = BlendingAfterAdjustMikroModel::find($id);
        return view('foreman.blending.blending_adjust_detail_mikro_id', compact('blending'));
    }

    public function updateAjaxBlendingMikro(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'eb' => 'nullable|numeric|min:0|max:100',
            'tpc' => 'nullable|numeric|min:0|max:100',
            'ym' => 'nullable |string|max:20', 
            'nama_foreman' => 'string', 
            'shift' => 'string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = BlendingAfterAdjustMikroModel::findOrFail($id);
        KonfirmasiBlendingAdjustMikroModel::create([
            'blending_after_adjust_mikro_id' => $blending->id, // Ambil ID dari blending yang baru diupdate
            'nama_foreman' => $request->nama_foreman,
            'shift' => $request->shift,
        ]);
        
        $dataUpdate = [
            'eb' => $request->eb,
            'tpc' => $request->tpc,
            'ym' => $request->ym,       
        ];

       
        $blending->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function editBlendingMikro(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'eb_edit' => 'nullable|numeric|min:0|max:100',
            'tpc_edit' => 'nullable|numeric|min:0|max:100',
            'ym_edit' => 'nullable |string|max:20',
           
            'shift_edit' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = BlendingAfterAdjustMikroModel::findOrFail($id);
       

        $dataUpdate = [
            'eb' => $request->eb_edit,
            'tpc' => $request->tpc_edit,
            'ym' => $request->ym_edit,
        ];


        $blending->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
