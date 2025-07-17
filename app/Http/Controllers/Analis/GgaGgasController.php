<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\GgaProcess;
use App\Models\GgasProcess;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GgaGgasController extends Controller
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
            'batch_number' => 'required|integer',
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

        $batchNumber = (int) $request->batch_number;
        $type = strtoupper($request->type);

        $exists = false;

        if ($type === 'GGA') {
            $exists = GgaProcess::where('production_batch_id', $request->production_batch_id)
                ->where('batch_number', $batchNumber)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Batch $batchNumber sudah diinput untuk GGA."
                ], 422);
            }

            GgaProcess::create([
                'production_batch_id' => $request->production_batch_id,
                'batch_number' => $batchNumber,
                'dissolver_number' => $request->dissolver_number,
            ]);
        } elseif ($type === 'GGAS') {
            $exists = GgasProcess::where('production_batch_id', $request->production_batch_id)
                ->where('batch_number', $batchNumber)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Batch $batchNumber sudah diinput untuk GGAS."
                ], 422);
            }

            GgasProcess::create([
                'production_batch_id' => $request->production_batch_id,
                'batch_number' => $batchNumber,
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
        $productionBatches = ProductionBatch::has('GgaProcesses')->with('GgaProcesses')->orderby('created_at','desc')->get();

        return view('analis.ggaggas.gga', compact('productionBatches'));
    }
    public function GGAS_data()
    {
        // Ambil semua PO yang memiliki data GGA
        $productionBatches = ProductionBatch::has('GgasProcesses')->with('GgasProcesses')->orderby('created_at','desc')->get();

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

    public function checkBatchNumberGGA(Request $request)
    {
        $batchNumber = (int) $request->batch_number;
        $type = $request->type;
        $productionBatchId = $request->production_batch_id;

        if ($type === 'GGA') {
            $exists = GgaProcess::where('production_batch_id', $productionBatchId)
                ->where('batch_number', $batchNumber)
                ->exists();
        } elseif ($type === 'GGAS') {
            $exists = GgasProcess::where('production_batch_id', $productionBatchId)
                ->where('batch_number', $batchNumber)
                ->exists();
        } else {
            return response()->json(['status' => 'error', 'message' => 'Jenis tidak valid']);
        }

        if ($exists) {
            return response()->json(['status' => 'error', 'message' => "Batch $batchNumber sudah diinput."]);
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
        if ($gga->disposition) {
            return response()->json([
                'errors' => ['Data dengan ID ini sudah memiliki disposisi .']
            ], 422);
        }
    
        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi disposition_remarks sesuai ketentuan
        if (in_array($disposition, ['Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment']) && empty($remarks)) {
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
                'not_standar' => true,
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
                'not_standar' => true,
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
        if ($ggas->disposition) {
            return response()->json([
                'errors' => ['Data dengan ID ini sudah memiliki disposisi .']
            ], 422);
        }
    
        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi disposition_remarks sesuai ketentuan
        if (in_array($disposition, ['Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment']) && empty($remarks)) {
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
                'not_standar' => true,
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
                'not_standar' => true,
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
