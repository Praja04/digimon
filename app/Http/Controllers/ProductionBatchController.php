<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use App\Models\GgaGgasProcess;
use App\Models\GgaProcess;
use App\Models\GgasProcess;
use Illuminate\Http\Request;

class ProductionBatchController extends Controller
{
    public function index()
    {

        return view('productionbatch.index');
    }

    public function data_po()
    {
        $productionBatches = ProductionBatch::orderBy('created_at', 'desc')->get();
        // Ambil semua revisi > 0 dan buat key gabungan batch_id|batch_range
        $revisiData = GgaProcess::where('revisi', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.data_po', compact('productionBatches', 'revisiData'));
    }


    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('productionbatch.menu');
    }

    // Menyimpan data master (ProductionBatch)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255',
            'variant' => 'required|string|max:255',
            'production_date' => 'required|date',
            'batch_range' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        ProductionBatch::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255',
            'variant' => 'required|string|max:255',
            'production_date' => 'required|date',
            'batch_range' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $productionBatch = ProductionBatch::findOrFail($id);
        $productionBatch->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.'
        ], 200);
    }

    public function destroy($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);
        $productionBatch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ], 200);
    }

    public function show($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);
        $batches = $productionBatch->batch_range_array; // ini array hasil accessor di atas
        // Ambil semua batch yang sudah digunakan di GGA
        $usedBatches = [];
        foreach ($productionBatch->GgaProcesses as $gga) {
            // Asumsikan batch_range disimpan format seperti "1-2"
            if (preg_match('/(\d+)\s*-\s*(\d+)/', $gga->batch_range, $match)) {
                $range = range((int)$match[1], (int)$match[2]);
                $usedBatches = array_merge($usedBatches, $range);
            } else {
                $usedBatches[] = (int) $gga->batch_range;
            }
        }

        // Bandingkan apakah semua batch sudah ter-cover
        $allCovered = empty(array_diff($batches, $usedBatches));

        return view('productionbatch.detail', compact('productionBatch', 'batches', 'allCovered'));
    }


    public function getLastRevisiGGA(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
        ]);

        $lastRevisi = GgaProcess::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch_range)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }

    public function generateRevisiGGA(Request $request)
    {
        $validated = $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'dissolver_number' => 'required|string',
            'revisi_gga' => 'required|integer|min:1',
        ]);

        // Pastikan tidak duplikasi revisi sama
        $exists = GgaProcess::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_range', $validated['batch_range'])
            ->where('revisi', $validated['revisi_gga'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }

        // Buat data baru revisi
        GgaProcess::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $validated['batch_range'],
            'dissolver_number' => $validated['dissolver_number'],
            'barcode' => null,
            'adjusment_qty' => null,
            'brix' => null,
            'nacl' => null,
            'warna' => null,
            'disposition' => null,
            'disposition_remarks' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi_gga']
        ]);

        return response()->json(['message' => 'Revisi berhasil dibuat']);
    }

    public function getLastRevisiGGAS(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
        ]);

        $lastRevisi = GgasProcess::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch_range)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }

    public function generateRevisiGGAS(Request $request)
    {
        $validated = $request->validate([
            'production_batch_id_ggas' => 'required|integer|exists:production_batches,id',
            'batch_range_ggas' => 'required|string',
            'dissolver_number_ggas' => 'required|string',
            'revisi_ggas' => 'required|integer|min:1',
        ]);
        // Pastikan tidak duplikasi revisi sama
        $exists = GgasProcess::where('production_batch_id', $validated['production_batch_id_ggas'])
            ->where('batch_range', $validated['batch_range_ggas'])
            ->where('revisi', $validated['revisi_ggas'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }

        // Buat data baru revisi
        GgasProcess::create([
            'production_batch_id' => $validated['production_batch_id_ggas'],
            'batch_range' => $validated['batch_range_ggas'],
            'dissolver_number' => $validated['dissolver_number_ggas'],
            'barcode' => null,
            'adjusment_qty' => null,
            'brix' => null,
            'nacl' => null,
            'warna' => null,
            'disposition' => null,
            'disposition_remarks' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi_ggas'] 
        ]);

        return response()->json(['message' => 'Revisi berhasil dibuat']);
    }
}
