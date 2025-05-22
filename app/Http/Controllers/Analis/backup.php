<?php

//namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\GgaProcess;
use App\Models\GgasProcess;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GgaGgasControllera extends Controller
{

    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('analis.ggaggas.menu');
    }
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'production_batch_id' => 'required|exists:production_batches,id',
            'batch_start' => 'required|integer|different:batch_end',
            'batch_end' => 'required|integer',
            'dissolver_number' => 'required',
            'type' => 'required|in:GGA,GGAS',
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

        $type = strtoupper($request->type);
        $existingBatches = collect();

        if ($type === 'GGA') {
            $ggaBatches = GgaProcess::where('production_batch_id', $request->production_batch_id)->get();
            foreach ($ggaBatches as $record) {
                [$existStart, $existEnd] = explode('-', $record->batch_range);
                if ($start <= (int)$existEnd && $end >= (int)$existStart) {
                    $existingBatches->push("GGA ($existStart - $existEnd)");
                }
            }
            if ($existingBatches->isNotEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Batch range tumpang tindih dengan: ' . $existingBatches->implode(', ')
                ], 422);
            }

            GgaProcess::create([
                'production_batch_id' => $request->production_batch_id,
                'batch_range' => "$start-$end",
                'dissolver_number' => $request->dissolver_number,
            ]);
        } elseif ($type === 'GGAS') {
            $ggasBatches = GgasProcess::where('production_batch_id', $request->production_batch_id)->get();
            foreach ($ggasBatches as $record) {
                [$existStart, $existEnd] = explode('-', $record->batch_range);
                if ($start <= (int)$existEnd && $end >= (int)$existStart) {
                    $existingBatches->push("GGAS ($existStart - $existEnd)");
                }
            }
            if ($existingBatches->isNotEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Batch range tumpang tindih dengan: ' . $existingBatches->implode(', ')
                ], 422);
            }

            GgasProcess::create([
                'production_batch_id' => $request->production_batch_id,
                'batch_range' => "$start-$end",
                'dissolver_number' => $request->dissolver_number,
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Data berhasil disimpan.'
        ]);
    }



    public function GGA_data()
    {
        // Ambil semua PO yang memiliki data GGA
        $productionBatches = ProductionBatch::has('GgaProcesses')->with('GgaProcesses')->get();

        return view('analis.ggaggas.gga', compact('productionBatches'));
    }
    public function GGAS_data()
    {
        // Ambil semua PO yang memiliki data GGA
        $productionBatches = ProductionBatch::has('GgasProcesses')->with('GgasProcesses')->get();

        return view('analis.ggaggas.ggas', compact('productionBatches'));
    }

    public function GGA_detail($id)
    {
        // Ambil PO dengan GGA yang belum lengkap
        $productionBatch = ProductionBatch::with('GgaProcesses')->findOrFail($id);


        return view('analis.ggaggas.gga_detail', compact('productionBatch'));
    }

    public function GGAS_detail($id)
    {
        // Ambil PO dengan GGA yang belum lengkap
        $productionBatch = ProductionBatch::with('GgasProcesses')->findOrFail($id);


        return view('analis.ggaggas.ggas_detail', compact('productionBatch'));
    }

    public function checkBatchRangeGGA(Request $request)
    {
        $start = (int) $request->batch_start;
        $end = (int) $request->batch_end;
        $type = $request->type;
        $productionBatchId = $request->production_batch_id;

        if ($start > $end) {
            [$start, $end] = [$end, $start]; // urutkan jika terbalik
        }

        $existingRanges = collect();

        if ($type === 'GGA') {
            $existingRanges = \App\Models\GgaProcess::where('production_batch_id', $productionBatchId)->pluck('batch_range');
        } elseif ($type === 'GGAS') {
            $existingRanges = \App\Models\GgasProcess::where('production_batch_id', $productionBatchId)->pluck('batch_range');
        }

        $requestedRange = range($start, $end);
        foreach ($existingRanges as $range) {
            [$existingStart, $existingEnd] = explode('-', $range);
            $existingRangeArray = range((int) $existingStart, (int) $existingEnd);
            if (array_intersect($requestedRange, $existingRangeArray)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Batch range $start-$end sudah dipakai dalam $range"
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
    public function updateAjaxGGA(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brix' => 'required|numeric|min:0|max:100',
            'nacl' => 'required|numeric|min:0|max:100',
            'warna' => 'required|string|max:20',
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment',
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

        $gga = GgaProcess::findOrFail($id);

        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi disposition_remarks sesuai ketentuan
        if (in_array($disposition, ['Release Bersyarat', 'Reject', 'Repro']) && empty($remarks)) {
            return response()->json([
                'errors' => ['Kolom keterangan (remarks) wajib diisi untuk disposition ini.']
            ], 422);
        }
        if ($disposition === 'Release') {
            $remarks = '-';
        }

        // Update data GGA utama (brix, nacl, warna, disposition, remarks)
        $gga->update([
            'brix' => $request->brix,
            'nacl' => $request->nacl,
            'warna' => $request->warna,
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
        ]);

        // Jika disposition Adjustment, update adjustment_qty pada data adjustment yang sudah ada
        if ($disposition === 'Adjustment') {
            $adjustmentQty = $request->adjustment_qty;

            $gga->update([
                'brix' => $request->brix,
                'nacl' => $request->nacl,
                'warna' => $request->warna,
                'disposition' => $disposition,
                'disposition_remarks' => $remarks,
                'adjusment_qty' => $adjustmentQty,
            ]);
        }

        // Jika disposition Resampling, update remarks untuk menandai resampling
        if ($disposition === 'Resampling') {
            $gga->update([
                'brix' => $request->brix,
                'nacl' => $request->nacl,
                'warna' => $request->warna,
                'disposition' => $disposition,
                'disposition_remarks' => $remarks ? $remarks . ' (Resampling)' : 'Resampling',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }




    public function showInputFormGGA($id)
    {
        $gga = GgaProcess::find($id);
        return view('analis.ggaggas.gga_detail_id', compact('gga'));
    }

    public function showInputFormGGAS($id)
    {
        $ggas = GgasProcess::find($id);
        return view('analis.ggaggas.ggas_detail_id', compact('ggas'));
    }

    public function updateAjaxGGAS(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brix' => 'required|numeric|min:0|max:100',
            'nacl' => 'required|numeric|min:0|max:100',
            'warna' => 'required|string|max:20',
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment',
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

        $ggas = GgasProcess::findOrFail($id);

        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi disposition_remarks sesuai ketentuan
        if (in_array($disposition, ['Release Bersyarat', 'Reject', 'Repro']) && empty($remarks)) {
            return response()->json([
                'errors' => ['Kolom keterangan (remarks) wajib diisi untuk disposition ini.']
            ], 422);
        }
        if ($disposition === 'Release') {
            $remarks = '-';
        }

        // Update data GGA utama (brix, nacl, warna, disposition, remarks)
        $ggas->update([
            'brix' => $request->brix,
            'nacl' => $request->nacl,
            'warna' => $request->warna,
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
        ]);

        // Jika disposition Adjustment, update adjustment_qty pada data adjustment yang sudah ada
        if ($disposition === 'Adjustment') {
            $adjustmentQty = $request->adjustment_qty;

            $ggas->update([
                'brix' => $request->brix,
                'nacl' => $request->nacl,
                'warna' => $request->warna,
                'disposition' => $disposition,
                'disposition_remarks' => $remarks,
                'adjusment_qty' => $adjustmentQty,
            ]);
        }

        // Jika disposition Resampling, update remarks untuk menandai resampling
        if ($disposition === 'Resampling') {
            $ggas->update([
                'brix' => $request->brix,
                'nacl' => $request->nacl,
                'warna' => $request->warna,
                'disposition' => $disposition,
                'disposition_remarks' => $remarks ? $remarks . ' (Resampling)' : 'Resampling',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
