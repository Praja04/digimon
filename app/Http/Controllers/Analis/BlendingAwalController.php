<?php

namespace App\Http\Controllers\Analis;

use App\Events\ProcessOutsideDisposition;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BlendingAwalModel;
use App\Models\ManageWarnaModel;
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
        $request->merge([
            'volume' => str_replace(',', '.', $request->volume),
        ]);

        $validator = Validator::make($request->all(), [
            'production_batch_id' => 'required|exists:production_batches,id',
            'batch_start' => 'required|integer|different:batch_end',
            'batch_end' => 'required|integer',
            'storage' => 'nullable|string',
            'no_blending' => 'required',
            'volume' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ],
                422
            );
        }

        $start = (int) $request->batch_start;
        $end = (int) $request->batch_end;

        if ($start > $end) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Batch start tidak boleh lebih besar dari batch end.'
                ],
                422
            );
        }

        // Ambil angka-angka batch yang sudah dipakai
        $usedNumbers = [];

        $existingRanges = BlendingAwalModel::where('production_batch_id', $request->production_batch_id)
            ->pluck('batch_range');

        foreach ($existingRanges as $range) {
            [$existingStart, $existingEnd] = explode('-', $range);
            $existingStart = (int) $existingStart;
            $existingEnd = (int) $existingEnd;

            for ($i = $existingStart; $i <= $existingEnd; $i++) {
                $usedNumbers[] = $i;
            }
        }

        // Validasi angka yang akan digunakan
        for ($i = $start; $i <= $end; $i++) {
            if (in_array($i, $usedNumbers)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Angka batch $i sudah digunakan sebelumnya dan tidak boleh dipakai lagi."
                ], 422);
            }
        }

        $batchRange = "$start-$end";

        BlendingAwalModel::create([
            'production_batch_id' => $request->production_batch_id,
            'batch_range' => $batchRange,
            'nomor_blending' => $request->no_blending,
            'volume' => $request->volume,
            'storage' => $request->storage,
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function Blending_data()
    {
        // Ambil semua PO yang memiliki data GGA
        $productionBatches = ProductionBatch::with('BlendingAwal')
            ->has('BlendingAwal')
            ->orderBy('created_at', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return ($batch->isBlendingAwalComplete()) ? 1 : 0;
            })
            ->values();

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
        $manageWarna = ManageWarnaModel::orderBy('nama_warna', 'asc')->get();

        return view('analis.blending.blending_awal_detail', compact('productionBatch', 'manageWarna'));
    }

    public function showInputFormBlendingAwal($id)
    {
        $blending = BlendingAwalModel::find($id);
        $manageWarna = ManageWarnaModel::orderBy('nama_warna', 'asc')->get();
        return view('analis.blending.blending_awal_detail_id', compact('blending', 'manageWarna'));
    }
    public function updateAjaxBlending(Request $request, $id)
    {
        $request->merge([
            'brix' => str_replace(',', '.', $request->brix),
            'nacl' => str_replace(',', '.', $request->nacl),
            'bj' => str_replace(',', '.', $request->bj),
            'visco' => str_replace(',', '.', $request->visco),
            'aw' => str_replace(',', '.', $request->aw),
            'ph' => str_replace(',', '.', $request->ph),
            'buih' => str_replace(',', '.', $request->buih),
            'adjustment_qty_air' => str_replace(',', '.', $request->adjustment_qty_air),
            'adjustment_qty_garam' => str_replace(',', '.', $request->adjustment_qty_garam),
            'adjustment_qty_gula' => str_replace(',', '.', $request->adjustment_qty_gula),
        ]);

        $validator = Validator::make($request->all(), [
            'brix' => 'required|numeric|min:0|max:100',
            'nacl' => 'required|numeric|min:0|max:100',
            'bj' => 'required|string|max:20',
            'visco' => 'required|string|max:20',
            'aw' => 'required|string|max:20',
            'ph' => 'nullable',
            'buih' => 'nullable|string|max:20',
            'organo' => 'required|string|max:20',
            'endapan' => 'nullable|string|max:20',
            'warna' => 'required|string|max:20',
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
            'disposition_remarks' => 'nullable|string|max:255',
            'adjustment_qty_air' => 'nullable',
            'adjustment_qty_garam' => 'nullable',
            'adjustment_qty_gula' => 'nullable',
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
        $username = session('username');
        $dataUpdate = [
            'brix' => $request->brix,
            'nacl' => $request->nacl,
            'bj' => $request->bj,
            'visco' => $request->visco,
            'aw' => $request->aw,
            'buih' => $request->buih,
            'organo' => $request->organo,
            'endapan' => $request->endapan,
            'ph' => $request->ph,
            'warna' => $request->warna,
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
            $dataUpdate['disposition_remarks'] = $disposition;
            $dataUpdate['not_standar'] = true;
        }

        $blending->update($dataUpdate);

        if (in_array($disposition, ['Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'])) {
            event(new ProcessOutsideDisposition(
                "Blending Awal - Batch " . $blending->batch_range,
                $blending->production_batch_id,
                $disposition,
                $remarks
            ));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
