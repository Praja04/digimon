<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use App\Models\MonitoringStorageBeforeUse;
use App\Models\ProductionBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonitoringStorageBeforeUseController extends Controller
{
    public function index()
    {
        $productionBatches = ProductionBatch::with('MonitoringStorageBeforeUse')
            ->has('MonitoringStorageBeforeUse')
            ->orderBy('created_at', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return ($batch->isMonitoringStorageBeforeUseComplete()) ? 1 : 0;
            })
            ->values();

        return view('analis.monitoring.monitoring_storage_before_use.index', compact('productionBatches'));
    }

    public function show($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringStorageBeforeUse'
        ])->findOrFail($id);

        return view('analis.monitoring.monitoring_storage_before_use.show', compact('productionBatch'));
    }

    public function show_batch($id)
    {
        $monitoringStorageBeforeUse = MonitoringStorageBeforeUse::with([
            'productionBatch'
        ])->findOrFail($id);

        return view('analis.monitoring.monitoring_storage_before_use.show_batch', compact('monitoringStorageBeforeUse'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'volume' => str_replace(',', '.', $request->volume),
        ]);

        $validator = Validator::make($request->all(), [
            'production_batch_id' => 'required|exists:production_batches,id',
            'no_blending' => 'required',
            'volume' => 'required|numeric',
            'batch' => 'required',
            'storage' => 'nullable|string', // ← ubah jadi nullable
            'jenis_sample' => 'required|in:Before Tiban,Flushing',
            'waktu_sample' => 'required|date',
            'waktu_selesai_pemakaian' => 'required',
            'estimasi_kadaluarsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = MonitoringStorageBeforeUse::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch range ini sudah digunakan untuk Production Batch yang sama.',
            ], 409);
        }

        // Simpan data Pasterisasi After Adjust
        MonitoringStorageBeforeUse::create([
            'production_batch_id' => $request->production_batch_id,
            'nomor_blending' => $request->no_blending,
            'volume' => $request->volume,
            'batch_range' => $request->batch,
            'storage' => $request->storage,
            'jenis_sample' => $request->jenis_sample,
            'waktu_sample' => Carbon::parse($request->waktu_sample),
            'waktu_selesai_pemakaian' => Carbon::parse($request->waktu_selesai_pemakaian),
            'estimasi_kadaluarsa' => Carbon::parse($request->estimasi_kadaluarsa),
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function update(Request $request)
    {
        $request->merge([
            'visco' => str_replace(',', '.', $request->visco),
            'brix' => str_replace(',', '.', $request->brix),
            'aw' => str_replace(',', '.', $request->aw),
        ]);

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:monitoring_storage_before_uses,id',
            'visco' => 'required|numeric',
            'brix' => 'required|numeric',
            'aw' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = MonitoringStorageBeforeUse::findOrFail($request->id);
        if ($blending->disposition) {
            return response()->json([
                'errors' => ['Data dengan ID ini sudah memiliki disposisi .']
            ], 422);
        }

        $visco  = $request->visco;
        $brix = $request->brix;
        $aw  = $request->aw;

        // Standar
        $standard_visco = 30;
        $standard_brix = 0;
        $standard_aw = 0;

        // Hitung status
        if (
            ($visco !== null && $visco > $standard_visco) ||
            ($brix !== null && $brix > $standard_brix) ||
            ($aw !== null && $aw > $standard_aw)
        ) {
            $hasil = 'NOT OK';
        } elseif ($visco === null || $brix === null || $aw === null) {
            $hasil = 'PENDING'; // menunggu parameter lain
        } else {
            $hasil = 'OK';
        }



        $blending->update([
            'visco' => $request->visco,
            'brix' => $request->brix,
            'aw' => $request->aw,
            'hasil' => $hasil,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
