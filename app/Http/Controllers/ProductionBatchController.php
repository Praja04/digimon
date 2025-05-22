<?php

namespace App\Http\Controllers;

use App\Models\BlendingAwalModel;
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

    public function data_po_blending_awal()
    {
        $productionBatches = ProductionBatch::orderBy('created_at', 'desc')->get();
        // Ambil semua revisi > 0 dan buat key gabungan batch_id|batch_range
        $revisiData = BlendingAwalModel::where('revisi', '>', 0)
        ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.data_po_blending_awal', compact('productionBatches', 'revisiData'));
    }

    public function data_po_blending_after_adjust()
    {
        $productionBatches = ProductionBatch::orderBy('created_at', 'desc')->get();
        // Ambil semua revisi > 0 dan buat key gabungan batch_id|batch_range
        $revisiData = BlendingAwalModel::where('revisi', '>', 0)
        ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.data_po_blending_after_adjust', compact('productionBatches', 'revisiData'));
    }

    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('productionbatch.menu');
    }

    // Menyimpan data master (ProductionBatch)
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'po_number' => 'required|string|max:255',
    //         'variant' => 'required|string|max:255',
    //         'production_date' => 'required|date',
    //         'batch_range' => 'required|string|max:255',
    //         'storage' => 'required|string|max:255',
    //         'description' => 'nullable|string|max:255',
    //     ]);

    //     ProductionBatch::create($validatedData);
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Data berhasil disimpan.'
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255',
            'variant' => 'required|string|max:255',
            'production_date' => 'required|date',
            'batch_range' => 'required|string|max:255',
            'storage' => 'required|array', // ubah ke array
            'storage.*' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Ambil range batch dari inputan
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $validatedData['batch_range'], $matches)) {
            $start = (int) $matches[1];
            $end = (int) $matches[2];
        } else {
            $start = $end = (int) $validatedData['batch_range'];
        }

        $allBatches = range($start, $end);
        $chunks = array_chunk($allBatches, 10); // bagi setiap 10 batch

        foreach ($chunks as $index => $batchGroup) {
            $batchMin = min($batchGroup);
            $batchMax = max($batchGroup);

            ProductionBatch::create([
                'po_number' => $validatedData['po_number'],
                'variant' => $validatedData['variant'],
                'production_date' => $validatedData['production_date'],
                'batch_range' => $batchMin . '-' . $batchMax,
                'storage' => $validatedData['storage'][$index] ?? 'STORAGE ' . ($index + 1),
                'description' => $validatedData['description'] ?? null,
            ]);
        }

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
        $batches = $productionBatch->batch_range_array; // Misalnya [1,2,3,4,...]

        // Ambil semua batch_number yang sudah digunakan di GGA
        $usedBatches = $productionBatch->GgaProcesses->pluck('batch_number')->map(function ($batch) {
            return (int) $batch; // Ubah ke integer agar bisa dibandingkan dengan batch_range_array
        })->toArray();

        // Bandingkan apakah semua batch sudah ter-cover
        $allCovered = empty(array_diff($batches, $usedBatches));

        return view('productionbatch.detail', compact('productionBatch', 'batches', 'allCovered'));
    }


    public function getLastRevisiGGA(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_number' => 'required|string',
        ]);

        $lastRevisi = GgaProcess::where('production_batch_id', $request->production_batch_id)
            ->where('batch_number', $request->batch_number)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }

    public function generateRevisiGGA(Request $request)
    {
        $validated = $request->validate([
            'id_old_gga' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_number' => 'required|string',
            'dissolver_number' => 'required|string',
            'revisi_gga' => 'required|integer|min:1',
        ]);

        // Pastikan tidak duplikasi revisi sama
        $exists = GgaProcess::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_number', $validated['batch_number'])
            ->where('revisi', $validated['revisi_gga'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }
        $productionBatch = GgaProcess::findOrFail($validated['id_old_gga']);
        $productionBatch->update([
            'not_standar' => false,
        ]);

        // Buat data baru revisi
        GgaProcess::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_number' => $validated['batch_number'],
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
            'batch_number' => 'required|string',
        ]);

        $lastRevisi = GgasProcess::where('production_batch_id', $request->production_batch_id)
            ->where('batch_number', $request->batch_number)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }

    public function generateRevisiGGAS(Request $request)
    {
        $validated = $request->validate([
            'id_old_ggas' => 'required|integer',
            'production_batch_id_ggas' => 'required|integer|exists:production_batches,id',
            'batch_number_ggas' => 'required|string',
            'dissolver_number_ggas' => 'required|string',
            'revisi_ggas' => 'required|integer|min:1',
        ]);
        // Pastikan tidak duplikasi revisi sama
        $exists = GgasProcess::where('production_batch_id', $validated['production_batch_id_ggas'])
            ->where('batch_number', $validated['batch_number_ggas'])
            ->where('revisi', $validated['revisi_ggas'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }
        // buat update dahulu untuk id dari model ProductionBatch yang sama dengan production_batch_id
        $productionBatch = ProductionBatch::findOrFail($validated['id_old_ggas']);
        $productionBatch->update([
            'not_standar' =>false,
        ]);

        // Buat data baru revisi
        GgasProcess::create([
            'production_batch_id' => $validated['production_batch_id_ggas'],
            'batch_number' => $validated['batch_number_ggas'],
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

    //blending awal
    public function show_blending_awal($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);
        $batches = $productionBatch->batch_range_array; // Misalnya [1,2,3,4,...]

        // Ambil semua batch_number yang sudah digunakan di GGA
        $usedBatches = $productionBatch->BlendingAwal->pluck('batch_range')->map(function ($batch) {
            return (int) $batch; // Ubah ke integer agar bisa dibandingkan dengan batch_range_array
        })->toArray();

        // Bandingkan apakah semua batch sudah ter-cover
        $allCovered = empty(array_diff($batches, $usedBatches));

        return view('productionbatch.detail_blending_awal', compact('productionBatch', 'batches', 'allCovered'));
    }
}
