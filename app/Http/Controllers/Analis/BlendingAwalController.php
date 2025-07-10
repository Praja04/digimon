<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BlendingAwalModel;
use App\Models\ProductionBatch;

class BlendingAwalController extends Controller
{
    //
    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('analis.blending.menu');
    }
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'production_batch_id' => 'required|exists:production_batches,id',
            'batch_start' => 'required|integer|different:batch_end',
            'batch_end' => 'required|integer',
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

        $start = (int) $request->batch_start;
        $end = (int) $request->batch_end;

        if ($start > $end) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch start tidak boleh lebih besar dari batch end.'
            ], 422);
        }

     

       $batchRange = $start . '-' . $end;

        // Cek apakah batch_range persis sama sudah ada
        $exists = BlendingAwalModel::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $batchRange)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch range tersebut sudah pernah digunakan.'
            ], 422);
        }
        

        // Simpan data Blending Awal
        BlendingAwalModel::create([
            'production_batch_id' => $request->production_batch_id,
            'batch_range' => "$start-$end",
            'nomor_blending' => $request->no_blending,
            'volume' => $request->volume
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

    public function Blending_data()
    {
        // Ambil semua PO yang memiliki data GGA
        $productionBatches = ProductionBatch::has('BlendingAwal')->with('BlendingAwal')->get();

        return view('analis.blending.blending_awal', compact('productionBatches'));
    }


    public function Blending_detail($id)
    {
        // // Ambil PO dengan GGA yang belum lengkap
        // $productionBatch = ProductionBatch::with('BlendingAwal')->findOrFail($id);


        // return view('analis.blending.blending_awal_detail', compact('productionBatch'));

        // Ambil data ProductionBatch beserta BlendingAwal dan BlendingBatchRelation
        $productionBatch = ProductionBatch::with([
            'BlendingAwal.additionalBatches' // nested eager loading
        ])->findOrFail($id);

        // Loop setiap blending_awal untuk cek apakah ada additional batch
        foreach ($productionBatch->BlendingAwal as $blending) {
            // Tambahkan properti custom 'additional_batch_info' ke setiap data
            $blending->additional_batch_info = $blending->additionalBatches->isNotEmpty()
                ? $blending->additionalBatches
                : null;

            $blending->po_number = $productionBatch->po_number;
        }
        // return json response untuk debugging
        //return response()->json($productionBatch->BlendingAwal);

        return view('analis.blending.blending_awal_detail', compact('productionBatch'));
    }

    public function showInputFormBlendingAwal($id)
    {
        $blending = BlendingAwalModel::find($id);
        return view('analis.blending.blending_awal_detail_id', compact('blending'));
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
            'ph' => 'required',
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

        $blending = BlendingAwalModel::findOrFail($id);
        if ($blending->disposition) {
            return response()->json([
                'errors' => ['Data dengan ID ini sudah memiliki disposisi .']
            ], 422);
        }
    
        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi remark wajib isi untuk kondisi tertentu
        if (in_array($disposition, ['Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling']) && empty($remarks)) {
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
            'ph' => $request->ph,
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
        if ($disposition === 'Resampling' ) {
            $dataUpdate['disposition_remarks'] = $disposition;
            $dataUpdate['not_standar'] = true;
        }

        $blending->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
