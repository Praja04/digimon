<?php

namespace App\Http\Controllers;

use App\Models\BlendingAwalModel;
use App\Models\BlendingAfterAdjustModel;
use App\Models\ProductionBatch;
use App\Models\GgaGgasProcess;
use App\Models\GgaProcess;
use App\Models\GgasProcess;
use App\Models\BlendingBatchRelation;
use App\Models\BlendingAfterAdjustBatchRelation;
use App\Models\MonitoringTurunBlending;
use App\Models\MonitoringTurunBlendingRelation;
use App\Models\MonitoringStorageModel;
use App\Models\MonitoringStorageMikroModel;
use App\Models\MonitoringStorageRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionBatchController extends Controller
{
    public function index()
    {

        return view('productionbatch.index');
    }
    public function scan()
    {

        return view('analis.scan');
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

    public function data_po_monitoring()
    {
        $productionBatches = ProductionBatch::orderBy('created_at', 'desc')->get();
        // Ambil semua revisi > 0 dan buat key gabungan batch_id|batch_range
        $revisiData = MonitoringTurunBlending::where('revisi', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.monitoring.data_po_monitoring', compact('productionBatches', 'revisiData'));
    }

    public function data_po_monitoring_storage()
    {
        $productionBatches = ProductionBatch::orderBy('created_at', 'desc')->get();
        // Ambil semua revisi > 0 dan buat key gabungan batch_id|batch_range
        $revisiData = MonitoringStorageModel::where('revisi', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.monitoring_storage.data_po_monitoring_storage', compact('productionBatches', 'revisiData'));
    }

    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('productionbatch.menu');
    }


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
    public function edit($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);
        return response()->json($productionBatch);
    }

    public function show($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);



        $batches = $productionBatch->batch_range_array; // Misalnya [1,2,3,4,...]

        $validGgaBatches = $productionBatch->GgaProcesses
            ->whereNotIn('disposition', ['Resampling', 'Reject', 'Repro', 'Adjustment', Null])
            ->pluck('batch_number')
            ->map(fn ($b) => (int)$b)
            ->toArray();

        // Cek apakah semua batch sudah masuk GGA
        $allCovered = count(
            collect($batches)->intersect(
                $productionBatch->GgaProcesses->pluck('batch_number')->map(fn ($b) => (int)$b)
            )
        ) === count($batches);

        return view('productionbatch.detail', compact(
            'productionBatch',
            'batches',
            'validGgaBatches',
            //'allCovered'
        ));
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
        $ggas_old = GgasProcess::findOrFail($validated['id_old_ggas']);
        $ggas_old->update([
            'not_standar' => false,
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
        $productionBatch = ProductionBatch::with(['BlendingAwal' => function ($query) {
            $query->with('additionalBatches');
        }])->findOrFail($id);

        $batches = $productionBatch->batch_range_array; // e.g. [1, 2, 3, 4, ...]

        // Hanya ambil disposisi tertentu dari GGAS
        $validDispositions = ['Release', 'Release Bersyarat'];

        // Ambil batch_number dari GGAS yang disposisinya valid
        $validGgasBatches = $productionBatch->GgasProcesses()
            ->whereIn('disposition', $validDispositions)
            ->pluck('batch_number')
            ->map(fn ($b) => (int) $b)
            ->unique()
            ->toArray();

        // Ambil batch dari tabel relasi tambahan (blending_batch_relations)

        $blendingAwalIds = $productionBatch->BlendingAwal->pluck('id')->toArray();

        // Ambil batch dari tabel relasi tambahan berdasarkan blending_awal_id
        $usedInRelations = BlendingBatchRelation::whereIn('blending_awal_id', $blendingAwalIds)
            ->pluck('batch') // format bisa "3-4"
            ->flatMap(function ($batch) {
                if (str_contains($batch, '-')) {
                    [$start, $end] = explode('-', $batch);
                    return range((int) $start, (int) $end);
                }
                return [(int) $batch];
            })
            ->unique()
            ->toArray();

        // Ambil hanya batch dari GGAS valid yang termasuk di range batch dari PO ini
        $batchesGgasValid = array_intersect($batches, $validGgasBatches);

        // Hilangkan batch yang sudah ada di relasi
        $availableBatches = array_values(array_diff($batchesGgasValid, $usedInRelations));

        // Cek apakah semua batch GGAS valid sudah terpakai
        $allCovered = empty($availableBatches);

        // Tandai apakah setiap BlendingAwal punya relasi
        foreach ($productionBatch->BlendingAwal as $blending) {
            $blending->has_relation = $blending->additionalBatches && $blending->additionalBatches->isNotEmpty();
            $blending->related_batches = $blending->has_relation
                ? $blending->additionalBatches->pluck('batch')->implode(', ')
                : null;

            // Tambahkan po_number ke setiap additionalBatch
            foreach ($blending->additionalBatches as $addBatch) {
                $po = ProductionBatch::find($addBatch->production_batch_id);
                $addBatch->po_number = $po->po_number;
            }
        }


        return view('productionbatch.detail_blending_awal', compact(
            'productionBatch',
            'batches',
            'availableBatches',
            'allCovered'
        ));

        // return response()->json([
        //     'productionBatch' => $productionBatch,
        //     'batches'=>$batches,
        //     'availableBatches' => $availableBatches,
        //     'allCovered' => $allCovered,
        // ]);
    }



    public function getLastRevisiBlendingAwal(Request $request)
    {

        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
        ]);

        $lastRevisi = BlendingAwalModel::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch_range)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }



    public function generateRevisiBlendingAwal(Request $request)
    {
        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
        ]);

        $old = BlendingAwalModel::findOrFail($validated['id_old_blending']);
        $old->update([
            'not_standar' => false, // atau logika lain sesuai kebutuhanmu
        ]);
        // Ambil disposisi data lama
        $oldDisposition = $old->disposition;

        // Hanya jika disposisi Jalan Bareng atau Leveling, additional_batch wajib dan diproses
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling'])) {
            if (empty($validated['additional_batch'])) {
                return response()->json([
                    'message' => 'Batch tambahan wajib diisi untuk disposisi "Jalan Bareng" atau "Leveling".'
                ], 422);
            }
        } else {
            // Jika bukan kedua disposisi tsb, pastikan additional_batch tidak diproses
            $validated['additional_batch'] = null;
        }

        // Cek revisi sudah ada atau belum
        $exists = BlendingAwalModel::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_range', $validated['batch_range'])
            ->where('revisi', $validated['revisi'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }



        // Buat revisi baru
        $new = BlendingAwalModel::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $validated['batch_range'],
            'nomor_blending' => $validated['no_blending'],
            'volume' => $validated['volume'],
            'brix' => null,
            'nacl' => null,
            'bj' => null,
            'visco' => null,
            'aw' => null,
            'buih' => null,
            'organo' => null,
            'ph' => null,
            'endapan' => null,
            'warna' => null,
            'disposition' => null,
            'disposition_remarks' => null,
            'adjusment_qty' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi'],
            'not_standar' => false
        ]);

        // Simpan batch tambahan hanya jika disposisi Jalan Bareng atau Leveling dan ada input tambahan_batch
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling']) && !empty($validated['additional_batch'])) {
            DB::table('blending_batch_relations')->insert([
                'blending_awal_id' => $new->id,
                'batch' => $validated['additional_batch'],
                'created_at' => now(),
                'updated_at' => now(),
                'production_batch_id' => $request->input('production_batch_id_leveling')
            ]);
        }

        return response()->json(['message' => 'Revisi Blending Awal berhasil dibuat.']);
    }



    // public function getAvailableAdditionalBatch(Request $request)
    // {
    //     $request->validate([
    //         'production_batch_id' => 'required|integer|exists:production_batches,id',
    //         'exclude_batch' => 'required|string'
    //     ]);

    //     $productionBatch = ProductionBatch::findOrFail($request->production_batch_id);

    //     $validDispositions = ['Release', 'Release Bersyarat', 'Adjustment', 'Resampling'];

    //     // Fungsi ambil batch valid beserta PO id-nya
    //     $getAvailableBatchesByPo = function ($poId, $exclude) use ($validDispositions) {
    //         $po = ProductionBatch::findOrFail($poId);

    //         $validGgasBatches = $po->GgasProcesses()
    //             ->whereIn('disposition', $validDispositions)
    //             ->pluck('batch_number')
    //             ->map(fn ($b) => (int)$b)
    //             ->unique()
    //             ->toArray();

    //         $usedInBlending = $po->BlendingAwal->flatMap(function ($item) {
    //             if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
    //                 return range((int)$matches[1], (int)$matches[2]);
    //             }
    //             return [(int)$item->batch_range];
    //         })->toArray();

    //         $usedInRelation = DB::table('blending_batch_relations')
    //             ->join('blending_awal', 'blending_batch_relations.blending_awal_id', '=', 'blending_awal.id')
    //             ->where('blending_awal.production_batch_id', $po->id)
    //             ->pluck('batch')
    //             ->map(fn ($b) => (int)$b)
    //             ->toArray();

    //         $availableBatches = array_values(array_diff($validGgasBatches, $usedInBlending, $usedInRelation, $exclude));

    //         // Return array dengan struktur: ['po_id' => ..., 'batch_number' => ...]
    //         return array_map(fn ($batch) => ['po_id' => $poId, 'batch_number' => $batch, 'po_number' => $po->po_number,], $availableBatches);
    //     };

    //     $exclude = explode('-', $request->exclude_batch);
    //     $exclude = array_map('intval', $exclude);

    //     $available = $getAvailableBatchesByPo($productionBatch->id, $exclude);

    //     if (empty($available)) {
    //         $otherPOs = ProductionBatch::where('id', '!=', $productionBatch->id)->get();
    //         //$otherPOs = ProductionBatch::get();

    //         foreach ($otherPOs as $otherPO) {
    //             $batchesFromOtherPo = $getAvailableBatchesByPo($otherPO->id, $exclude);
    //             if (!empty($batchesFromOtherPo)) {
    //                 $available = $batchesFromOtherPo;
    //                 break;
    //             }
    //         }
    //     }

    //     return response()->json(['data' => $available]);
    // }
    public function getAvailableAdditionalBatch(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'exclude_batch' => 'required|string',
        ]);

        $validDispositions = ['Release', 'Release Bersyarat'];

        // Ambil batch yang ingin dicari tambahan batch-nya
        $selectedBatch = ProductionBatch::findOrFail($request->production_batch_id);
        $poNumber = $selectedBatch->po_number;

        // Convert exclude_batch dari format "1-2" menjadi array [1, 2]
        $exclude = explode('-', $request->exclude_batch);
        $exclude = array_map('intval', $exclude);

        // Helper untuk ambil batch yang valid dari satu PO
        $getAvailableBatchesByPo = function ($po) use ($validDispositions, $exclude) {
            $validGgasBatches = $po->GgasProcesses()
                ->whereIn('disposition', $validDispositions)
                ->pluck('batch_number')
                ->map(fn ($b) => (int) $b)
                ->unique()
                ->toArray();

            $usedInBlending = $po->BlendingAwal->flatMap(function ($item) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
                    return range((int) $matches[1], (int) $matches[2]);
                }
                return [(int) $item->batch_range];
            })->toArray();

            $usedInRelation = DB::table('blending_batch_relations')
                ->join('blending_awal', 'blending_batch_relations.blending_awal_id', '=', 'blending_awal.id')
                ->where('blending_awal.production_batch_id', $po->id)
                ->pluck('batch')
                ->map(fn ($b) => (int) $b)
                ->toArray();

            $availableBatches = array_values(array_diff($validGgasBatches, $usedInBlending, $usedInRelation, $exclude));

            return array_map(fn ($batch) => [
                'po_id' => $po->id,
                'po_number' => $po->po_number,
                'batch_number' => $batch,
            ], $availableBatches);
        };

        $available = [];

        // Cari batch dari semua production_batch dengan po_number yang sama
        $poGroup = ProductionBatch::where('po_number', $poNumber)->get();

        foreach ($poGroup as $batch) {
            $result = $getAvailableBatchesByPo($batch);
            if (!empty($result)) {
                $available = array_merge($available, $result);
            }
        }

        // Jika belum ketemu batch yang available, cari dari PO lain
        if (empty($available)) {
            $otherPOs = ProductionBatch::where('po_number', '!=', $poNumber)->get();

            foreach ($otherPOs as $po) {
                $result = $getAvailableBatchesByPo($po);
                if (!empty($result)) {
                    $available = $result;
                    break;
                }
            }
        }

        return response()->json(['data' => $available]);
    }


    public function getMainBlendingAwalJalanBareng(Request $request)

    {
        $request->validate([
            'production_batch_id' => 'required|exists:production_batches,id'
        ]);

        $productionBatchId = $request->production_batch_id;
        $excludedDispositions = ['Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'];

        $usedBatchIds = BlendingBatchRelation::pluck('blending_awal_id')->toArray();

        // Ambil dulu dari PO yang sama
        $mainBlending = BlendingAwalModel::where('production_batch_id', $productionBatchId)
            ->whereNotIn('disposition', $excludedDispositions)
            ->whereNotIn('id', $usedBatchIds)
            ->with('productionBatch')
            ->orderByDesc('id') // atau 'nomor_blending' kalau itu numeric
            ->get();

        // Kalau kosong, ambil dari PO lain
        if ($mainBlending->isEmpty()) {
            $mainBlending = BlendingAwalModel::where('production_batch_id', '!=', $productionBatchId)
                ->whereNotIn('disposition', $excludedDispositions)
                ->whereNotIn('id', $usedBatchIds)
                ->with('productionBatch')
                ->orderByDesc('id') // atau 'nomor_blending'
                ->get();
        }

        $result = $mainBlending->map(function ($item) {
            return [
                'id' => $item->id,
                'batch_range' => $item->batch_range,
                'po_id' => $item->production_batch_id,
                'po_number' => $item->productionBatch?->po_number ?? null,
                'nomor_blending' => $item->nomor_blending,
            ];
        });

        return response()->json(['data' => $result]);
    }



    //Blending After Adjust



    public function show_blending_after_adjust($id)
    {
        $productionBatch = ProductionBatch::with([
            'blendingAfterAdjust' => fn ($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        $all = BlendingAwalModel::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $grouped = $all->groupBy('batch_range');
        $batchGroups = [];

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn ($item) =>
                is_numeric($item->revisi) ? (int) $item->revisi : 0
            )->first();

            $fullRange = $chosen->batch_range;

            $relatedBatches = DB::table('blending_batch_relations')
                ->where('blending_awal_id', $chosen->id)
                ->pluck('batch'); // contoh: ['8-9', '12-13']

            foreach ($relatedBatches as $relRange) {
                $fullRange .= '-' . $relRange;
            }

            $batchGroups[] = $fullRange;
        }

        // Filter supaya tidak ada range yang merupakan bagian dari yang lain
        $filteredBatchGroups = [];
        foreach ($batchGroups as $i => $range) {
            $isSubset = false;
            foreach ($batchGroups as $j => $otherRange) {
                if ($i !== $j && strpos($otherRange, $range) !== false) {
                    $isSubset = true;
                    break;
                }
            }
            if (!$isSubset) {
                $filteredBatchGroups[] = $range;
            }
        }

        foreach ($productionBatch->blendingAfterAdjust as $data) {
            $data->has_relation = $data->additionalBatches && $data->additionalBatches->isNotEmpty();
            $data->related_batches = $data->has_relation
                ? $data->additionalBatches->pluck('batch')->implode(', ')
                : null;

            foreach ($data->additionalBatches as $addBatch) {
                $po = ProductionBatch::find($addBatch->production_batch_id);
                $addBatch->po_number = $po->po_number ?? null;
            }
        }

        // return response()->json([
        //     'productionBatch' => $productionBatch,
        //     'batchGroups' => $filteredBatchGroups,
        // ]);
        return view('productionbatch.detail_blending_after_adjust', compact(
            'productionBatch',
            'filteredBatchGroups',

        ));
    }


    public function getLastRevisiBlendingAdjust(Request $request)
    {

        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
        ]);

        $lastRevisi = BlendingAfterAdjustModel::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch_range)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }


    // public function getAvailableAdditionalBatchAfterAdjust(Request $request)
    // {
    //     $request->validate([
    //         'production_batch_id' => 'required|integer|exists:production_batches,id',
    //         'exclude_batch' => 'required|string'
    //     ]);

    //     $productionBatch = ProductionBatch::findOrFail($request->production_batch_id);

    //     $validDispositions = ['Release', 'Release Bersyarat', 'Adjustment', 'Resampling'];

    //     // Fungsi ambil batch valid beserta PO id-nya
    //     $getAvailableBatchesByPo = function ($poId, $exclude) use ($validDispositions) {
    //         $po = ProductionBatch::findOrFail($poId);

    //         $validGgasBatches = $po->GgasProcesses()
    //             ->whereIn('disposition', $validDispositions)
    //             ->pluck('batch_number')
    //             ->map(fn ($b) => (int)$b)
    //             ->unique()
    //             ->toArray();

    //         $usedInBlending = $po->blendingAfterAdjust->flatMap(function ($item) {
    //             if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
    //                 return range((int)$matches[1], (int)$matches[2]);
    //             }
    //             return [(int)$item->batch_range];
    //         })->toArray();

    //         $usedInRelation = DB::table('blending_after_adjust_batch_relations')
    //             ->join('blending_adjust', 'blending_after_adjust_batch_relations.blending_after_adjust_id', '=', 'blending_after_adjust_id')
    //             ->where('blending_adjust.production_batch_id', $po->id)
    //             ->pluck('batch')
    //             ->map(fn ($b) => (int)$b)
    //             ->toArray();

    //         $availableBatches = array_values(array_diff($validGgasBatches, $usedInBlending, $usedInRelation, $exclude));

    //         // Return array dengan struktur: ['po_id' => ..., 'batch_number' => ...]
    //         return array_map(fn ($batch) => ['po_id' => $poId, 'batch_number' => $batch, 'po_number' => $po->po_number,], $availableBatches);
    //     };

    //     $exclude = explode('-', $request->exclude_batch);
    //     $exclude = array_map('intval', $exclude);

    //     $available = $getAvailableBatchesByPo($productionBatch->id, $exclude);

    //     if (empty($available)) {
    //         $otherPOs = ProductionBatch::where('id', '!=', $productionBatch->id)->get();

    //         foreach ($otherPOs as $otherPO) {
    //             $batchesFromOtherPo = $getAvailableBatchesByPo($otherPO->id, $exclude);
    //             if (!empty($batchesFromOtherPo)) {
    //                 $available = $batchesFromOtherPo;
    //                 break;
    //             }
    //         }
    //     }

    //     return response()->json(['data' => $available]);
    // }

    public function getAvailableAdditionalBatchAfterAdjust(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'exclude_batch' => 'required|string',
        ]);

        $validDispositions = ['Release', 'Release Bersyarat'];

        // Ambil batch yang ingin dicari tambahan batch-nya
        $selectedBatch = ProductionBatch::findOrFail($request->production_batch_id);
        $poNumber = $selectedBatch->po_number;

        // Convert exclude_batch dari format "1-2" menjadi array [1, 2]
        $exclude = explode('-', $request->exclude_batch);
        $exclude = array_map('intval', $exclude);

        // Helper untuk ambil batch yang valid dari satu PO
        $getAvailableBatchesByPo = function ($po) use ($validDispositions, $exclude) {
            $validGgasBatches = $po->GgasProcesses()
                ->whereIn('disposition', $validDispositions)
                ->pluck('batch_number')
                ->map(fn ($b) => (int) $b)
                ->unique()
                ->toArray();

            $usedInBlending = $po->BlendingAwal->flatMap(function ($item) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
                    return range((int) $matches[1], (int) $matches[2]);
                }
                return [(int) $item->batch_range];
            })->toArray();

            $usedInRelation = DB::table('blending_after_adjust_batch_relations')
                ->join('blending_adjust', 'blending_after_adjust_batch_relations.blending_after_ adjust_id', '=', 'blending_adjust.id')
                ->where('blending_adjust.production_batch_id', $po->id)
                ->pluck('batch')
                ->map(fn ($b) => (int) $b)
                ->toArray();

            $availableBatches = array_values(array_diff($validGgasBatches, $usedInBlending, $usedInRelation, $exclude));

            return array_map(fn ($batch) => [
                'po_id' => $po->id,
                'po_number' => $po->po_number,
                'batch_number' => $batch,
            ], $availableBatches);
        };

        $available = [];

        // Cari batch dari semua production_batch dengan po_number yang sama
        $poGroup = ProductionBatch::where('po_number', $poNumber)->get();

        foreach ($poGroup as $batch) {
            $result = $getAvailableBatchesByPo($batch);
            if (!empty($result)) {
                $available = array_merge($available, $result);
            }
        }

        // Jika belum ketemu batch yang available, cari dari PO lain
        if (empty($available)) {
            $otherPOs = ProductionBatch::where('po_number', '!=', $poNumber)->get();

            foreach ($otherPOs as $po) {
                $result = $getAvailableBatchesByPo($po);
                if (!empty($result)) {
                    $available = $result;
                    break;
                }
            }
        }

        return response()->json(['data' => $available]);
    }



    public function getMainBlendingAdjustJalanBareng(Request $request)
    {


        $request->validate([
            'production_batch_id' => 'required|exists:production_batches,id'
        ]);

        $productionBatchId = $request->production_batch_id;
        $excludedDispositions = ['Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'];

        $usedBatchIds = BlendingAfterAdjustBatchRelation::pluck('blending_after_adjust_id')->toArray();

        // Ambil dulu dari PO yang sama
        $mainBlending = BlendingAfterAdjustModel::where('production_batch_id', $productionBatchId)
            ->whereNotIn('disposition', $excludedDispositions)
            ->whereNotIn('id', $usedBatchIds)
            ->with('productionBatch')
            ->orderByDesc('id') // atau 'nomor_blending' kalau itu numeric
            ->get();

        // Kalau kosong, ambil dari PO lain
        if ($mainBlending->isEmpty()) {
            $mainBlending = BlendingAfterAdjustModel::where('production_batch_id', '!=', $productionBatchId)
                ->whereNotIn('disposition', $excludedDispositions)
                ->whereNotIn('id', $usedBatchIds)
                ->with('productionBatch')
                ->orderByDesc('id') // atau 'nomor_blending'
                ->get();
        }

        $result = $mainBlending->map(function ($item) {
            return [
                'id' => $item->id,
                'batch_range' => $item->batch_range,
                'po_id' => $item->production_batch_id,
                'po_number' => $item->productionBatch?->po_number ?? null,
                'nomor_blending' => $item->nomor_blending,
            ];
        });

        return response()->json(['data' => $result]);
    }

    public function generateRevisiBlendingAdjust(Request $request)
    {
        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
        ]);

        $old = BlendingAfterAdjustModel::findOrFail($validated['id_old_blending']);
        $old->update([
            'not_standar' => false, // atau logika lain sesuai kebutuhanmu
        ]);
        // Ambil disposisi data lama
        $oldDisposition = $old->disposition;

        // Hanya jika disposisi Jalan Bareng atau Leveling, additional_batch wajib dan diproses
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling'])) {
            if (empty($validated['additional_batch'])) {
                return response()->json([
                    'message' => 'Batch tambahan wajib diisi untuk disposisi "Jalan Bareng" atau "Leveling".'
                ], 422);
            }
        } else {
            // Jika bukan kedua disposisi tsb, pastikan additional_batch tidak diproses
            $validated['additional_batch'] = null;
        }

        // Cek revisi sudah ada atau belum
        $exists = BlendingAfterAdjustModel::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_range', $validated['batch_range'])
            ->where('revisi', $validated['revisi'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }

        // Tandai data lama sebagai tidak standar (not_standar = true)
        $old->update(['not_standar' => true]);

        // Buat revisi baru
        $new = BlendingAfterAdjustModel::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $validated['batch_range'],
            'nomor_blending' => $validated['no_blending'],
            'volume_blending' => $validated['volume'],
            'brix' => null,
            'nacl' => null,
            'bj' => null,
            'visco' => null,
            'aw' => null,
            'buih' => null,
            'organo' => null,
            'ph' => null,
            'endapan' => null,
            'warna' => null,
            'disposition' => null,
            'disposition_remarks' => null,
            'adjusment_qty' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi'],
            'not_standar' => false
        ]);

        // Simpan batch tambahan hanya jika disposisi Jalan Bareng atau Leveling dan ada input tambahan_batch
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling']) && !empty($validated['additional_batch'])) {
            DB::table('blending_after_adjust_batch_relations')->insert([
                'blending_after_adjust_id' => $new->id,
                'batch' => $validated['additional_batch'],
                'created_at' => now(),
                'updated_at' => now(),
                'production_batch_id' => $validated['production_batch_id']
            ]);
        }

        return response()->json(['message' => 'Revisi Blending Awal berhasil dibuat.']);
    }


    //Blending Monitoring

    // public function show_monitoring_blending($id)
    // {
    //     $productionBatch = ProductionBatch::with(['MonitoringTurunBlending' => function ($query) {
    //         $query->with('additionalBatches');
    //     }])->findOrFail($id);
    //     //$productionBatch = ProductionBatch::findOrFail($id);
    //     $validDispositions = ['Release', 'Release Bersyarat'];

    //     $all = BlendingAfterAdjustModel::where('production_batch_id', $id)
    //         ->whereIn('disposition', $validDispositions)
    //         ->get();

    //     $grouped = $all->groupBy('batch_range');
    //     $batchGroups = [];

    //     foreach ($grouped as $batchRange => $items) {
    //         $chosen = $items->sortByDesc(function ($item) {
    //             return is_numeric($item->revisi) ? (int)$item->revisi : 0;
    //         })->first();

    //         $fullRange = $chosen->batch_range;

    //         // Cek jika ada relasi
    //         $relatedBatches = DB::table('blending_after_adjust_batch_relations')
    //             ->where('blending_after_adjust_id', $chosen->id)
    //             ->pluck('batch'); // contoh: ['8-9', '12-13']

    //         foreach ($relatedBatches as $relRange) {
    //             $fullRange .= '-' . $relRange;
    //         }

    //         $batchGroups[] = $fullRange;
    //     }

    //     foreach ($productionBatch->MonitoringTurunBlending as $data) {
    //         $data->has_relation = $data->additionalBatches && $data->additionalBatches->isNotEmpty();
    //         $data->related_batches = $data->has_relation
    //             ? $data->additionalBatches->pluck('batch')->implode(', ')
    //             : null;

    //         // Tambahkan po_number ke setiap additionalBatch
    //         foreach ($data->additionalBatches as $addBatch) {
    //             $po = ProductionBatch::find($addBatch->production_batch_id);
    //             $addBatch->po_number = $po->po_number;
    //         }
    //     }
    //     return view('productionbatch.monitoring.detail_monitoring', compact(
    //         'productionBatch',
    //         'batchGroups',

    //     ));
    // }

    public function show_monitoring_blending($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringTurunBlending' => fn ($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        $all = BlendingAfterAdjustModel::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $grouped = $all->groupBy('batch_range');
        $rawBatchGroups = [];

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn ($item) =>
                is_numeric($item->revisi) ? (int)$item->revisi : 0
            )->first();

            $fullRange = $chosen->batch_range;

            $relatedBatches = DB::table('blending_after_adjust_batch_relations')
                ->where('blending_after_adjust_id', $chosen->id)
                ->pluck('batch');

            foreach ($relatedBatches as $relRange) {
                $fullRange .= '-' . $relRange;
            }

            $rawBatchGroups[] = $fullRange;
        }

        // Hilangkan batch-range yang merupakan bagian dari string lain
        $filteredBatchGroups = [];
        foreach ($rawBatchGroups as $i => $range) {
            $isSubset = false;
            foreach ($rawBatchGroups as $j => $compare) {
                if (
                    $i !== $j && strpos($compare, $range) !== false
                ) {
                    $isSubset = true;
                    break;
                }
            }
            if (!$isSubset) {
                $filteredBatchGroups[] = $range;
            }
        }

        foreach ($productionBatch->MonitoringTurunBlending as $data) {
            $data->has_relation = $data->additionalBatches && $data->additionalBatches->isNotEmpty();
            $data->related_batches = $data->has_relation
                ? $data->additionalBatches->pluck('batch')->implode(', ')
                : null;

            foreach ($data->additionalBatches as $addBatch) {
                $po = ProductionBatch::find($addBatch->production_batch_id);
                $addBatch->po_number = $po->po_number ?? null;
            }
        }

        return view('productionbatch.monitoring.detail_monitoring', compact(
            'productionBatch',
            'filteredBatchGroups'
        ));
    }

    public function getLastRevisiMonitoring(Request $request)
    {

        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
        ]);

        $lastRevisi = MonitoringTurunBlending::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch_range)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }


    public function getAvailableAdditionalBatchMonitoring(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'exclude_batch' => 'required|string'
        ]);

        $productionBatch = ProductionBatch::findOrFail($request->production_batch_id);

        $validDispositions = ['Release', 'Release Bersyarat', 'Adjustment', 'Resampling'];

        // Fungsi ambil batch valid beserta PO id-nya
        $getAvailableBatchesByPo = function ($poId, $exclude) use ($validDispositions) {
            $po = ProductionBatch::findOrFail($poId);

            // $validGgasBatches = $po->GgasProcesses()
            //     ->whereIn('disposition', $validDispositions)
            //     ->pluck('batch_number')
            //     ->map(fn ($b) => (int)$b)
            //     ->unique()
            //     ->toArray();

            $usedInBlending = $po->MonitoringTurunBlending->flatMap(function ($item) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
                    return range((int)$matches[1], (int)$matches[2]);
                }
                return [(int)$item->batch_range];
            })->toArray();

            $usedInRelation = DB::table('monitoring_turun_blending_relations')
                ->join('monitoring_turun_blending', 'monitoring_turun_blending_relations.monitoring_turun_blending_id', '=', 'monitoring_turun_blending_id')
                ->where('monitoring_turun_blending.production_batch_id', $po->id)
                ->pluck('batch')
                ->map(fn ($b) => (int)$b)
                ->toArray();

            $availableBatches = array_values(array_diff($usedInBlending, $usedInRelation, $exclude));

            // Return array dengan struktur: ['po_id' => ..., 'batch_number' => ...]
            return array_map(fn ($batch) => ['po_id' => $poId, 'batch_number' => $batch, 'po_number' => $po->po_number,], $availableBatches);
        };

        $exclude = explode('-', $request->exclude_batch);
        $exclude = array_map('intval', $exclude);

        $available = $getAvailableBatchesByPo($productionBatch->id, $exclude);

        if (empty($available)) {
            $otherPOs = ProductionBatch::where('id', '!=', $productionBatch->id)->get();

            foreach ($otherPOs as $otherPO) {
                $batchesFromOtherPo = $getAvailableBatchesByPo($otherPO->id, $exclude);
                if (!empty($batchesFromOtherPo)) {
                    $available = $batchesFromOtherPo;
                    break;
                }
            }
        }

        return response()->json(['data' => $available]);
    }



    public function getMainMonitoringJalanBareng(Request $request)
    {


        $request->validate([
            'production_batch_id' => 'required|exists:production_batches,id'
        ]);

        $productionBatchId = $request->production_batch_id;
        $excludedDispositions = ['Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'];

        $usedBatchIds = MonitoringTurunBlendingRelation::pluck('monitoring_turun_blending_id')->toArray();

        // Ambil dulu dari PO yang sama
        $mainBlending = MonitoringTurunBlending::where('production_batch_id', $productionBatchId)
            ->whereNotIn('disposition', $excludedDispositions)
            ->whereNotIn('id', $usedBatchIds)
            ->with('productionBatch')
            ->orderByDesc('id') // atau 'nomor_blending' kalau itu numeric
            ->get();

        // Kalau kosong, ambil dari PO lain
        if ($mainBlending->isEmpty()) {
            $mainBlending = MonitoringTurunBlending::where('production_batch_id', '!=', $productionBatchId)
                ->whereNotIn('disposition', $excludedDispositions)
                ->whereNotIn('id', $usedBatchIds)
                ->with('productionBatch')
                ->orderByDesc('id') // atau 'nomor_blending'
                ->get();
        }

        $result = $mainBlending->map(function ($item) {
            return [
                'id' => $item->id,
                'batch_range' => $item->batch_range,
                'po_id' => $item->production_batch_id,
                'po_number' => $item->productionBatch?->po_number ?? null,
                'nomor_blending' => $item->nomor_blending,
            ];
        });

        return response()->json(['data' => $result]);
    }

    public function generateRevisiMonitoring(Request $request)
    {
        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
        ]);

        $old = MonitoringTurunBlending::findOrFail($validated['id_old_blending']);
        $old->update([
            'not_standar' => false, // atau logika lain sesuai kebutuhanmu
        ]);
        // Ambil disposisi data lama
        $oldDisposition = $old->disposition;

        // Hanya jika disposisi Jalan Bareng atau Leveling, additional_batch wajib dan diproses
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling'])) {
            if (empty($validated['additional_batch'])) {
                return response()->json([
                    'message' => 'Batch tambahan wajib diisi untuk disposisi "Jalan Bareng" atau "Leveling".'
                ], 422);
            }
        } else {
            // Jika bukan kedua disposisi tsb, pastikan additional_batch tidak diproses
            $validated['additional_batch'] = null;
        }

        // Cek revisi sudah ada atau belum
        $exists = MonitoringTurunBlending::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_range', $validated['batch_range'])
            ->where('revisi', $validated['revisi'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }

        // Tandai data lama sebagai tidak standar (not_standar = true)
        //$old->update(['not_standar' => true]);

        // Buat revisi baru
        $new = MonitoringTurunBlending::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $validated['batch_range'],
            'nomor_blending' => $validated['no_blending'],
            'volume_blending' => $validated['volume'],
            'disposition' => null,
            'disposition_remarks' => null,
            'adjusment_qty' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi'],
            'not_standar' => false
        ]);

        // Simpan batch tambahan hanya jika disposisi Jalan Bareng atau Leveling dan ada input tambahan_batch
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling']) && !empty($validated['additional_batch'])) {
            DB::table('monitoring_turun_blending_relations')->insert([
                'monitoring_turun_blending_id' => $new->id,
                'batch' => $validated['additional_batch'],
                'created_at' => now(),
                'updated_at' => now(),
                'production_batch_id' => $validated['production_batch_id']
            ]);
        }

        return response()->json(['message' => 'Revisi Blending Awal berhasil dibuat.']);
    }


    //Monitoring Storage

    // public function show_monitoring_storage($id)
    // {
    //     $productionBatch = ProductionBatch::with(['MonitoringStorage' => function ($query) {
    //         $query->with('additionalBatches');
    //     }])->findOrFail($id);
    //     // $productionBatch = ProductionBatch::findOrFail($id);
    //     $validDispositions = ['Release', 'Release Bersyarat'];

    //     $all = MonitoringStorageModel::where('production_batch_id', $id)
    //         ->whereIn('disposition', $validDispositions)
    //         ->get();

    //     $grouped = $all->groupBy('batch_range');
    //     $batchGroups = [];

    //     foreach ($grouped as $batchRange => $items) {
    //         $chosen = $items->sortByDesc(function ($item) {
    //             return is_numeric($item->revisi) ? (int)$item->revisi : 0;
    //         })->first();

    //         $fullRange = $chosen->batch_range;

    //         // Cek jika ada relasi
    //         $relatedBatches = DB::table('monitoring_storage_relations')
    //             ->where('monitoring_storage_id', $chosen->id)
    //             ->pluck('batch'); // contoh: ['8-9', '12-13']

    //         foreach ($relatedBatches as $relRange) {
    //             $fullRange .= '-' . $relRange;
    //         }

    //         $batchGroups[] = $fullRange;
    //     }
    //     foreach ($productionBatch->MonitoringStorage as $data) {
    //         $data->has_relation = $data->additionalBatches && $data->additionalBatches->isNotEmpty();
    //         $data->related_batches = $data->has_relation
    //             ? $data->additionalBatches->pluck('batch')->implode(', ')
    //             : null;

    //         // Tambahkan po_number ke setiap additionalBatch
    //         foreach ($data->additionalBatches as $addBatch) {
    //             $po = ProductionBatch::find($addBatch->production_batch_id);
    //             $addBatch->po_number = $po->po_number;
    //         }
    //     }

    //     // return response()->json([
    //     //     'productionBatch' => $productionBatch,
    //     //     'batchGroups' => $batchGroups
    //     // ]);
    //     return view('productionbatch.monitoring_storage.detail_monitoring_storage', compact(
    //         'productionBatch',
    //         'batchGroups',

    //     ));
    // }

    public function show_monitoring_storage($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringTurunBlending' => fn ($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = [
            'Release', 'Release Bersyarat'
        ];

        $all = MonitoringTurunBlending::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $grouped = $all->groupBy('batch_range');
        $rawBatchGroups = [];

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn ($item) =>
                is_numeric($item->revisi) ? (int)$item->revisi : 0
            )->first();

            $fullRange = $chosen->batch_range;

            $relatedBatches = DB::table('monitoring_turun_blending_relations')
                ->where('monitoring_turun_blending_id', $chosen->id)
                ->pluck('batch');

            foreach ($relatedBatches as $relRange) {
                $fullRange .= '-' . $relRange;
            }

            $rawBatchGroups[] = $fullRange;
        }

        // Filter untuk hapus range yang sudah tercakup dalam yang lebih besar
        $filteredBatchGroups = [];
        foreach ($rawBatchGroups as $i => $range) {
            $isSubset = false;
            foreach ($rawBatchGroups as $j => $other) {
                if ($i !== $j && strpos($other, $range) !== false) {
                    $isSubset = true;
                    break;
                }
            }
            if (!$isSubset) {
                $filteredBatchGroups[] = $range;
            }
        }

        foreach ($productionBatch->MonitoringStorage as $data) {
            $data->has_relation = $data->additionalBatches && $data->additionalBatches->isNotEmpty();
            $data->related_batches = $data->has_relation
                ? $data->additionalBatches->pluck('batch')->implode(', ')
                : null;

            foreach ($data->additionalBatches as $addBatch) {
                $po = ProductionBatch::find($addBatch->production_batch_id);
                $addBatch->po_number = $po->po_number ?? null;
            }
        }

        return view('productionbatch.monitoring_storage.detail_monitoring_storage', compact(
            'productionBatch',
            'filteredBatchGroups'
        ));
    }

    public function getLastRevisiMonitoringStorage(Request $request)
    {

        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
        ]);

        $lastRevisi = MonitoringStorageModel::where('production_batch_id', $request->production_batch_id)
            ->where('batch_range', $request->batch_range)
            ->max('revisi');

        // Jika belum ada, revisi dimulai dari 1
        $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        return response()->json(['revisi' => $nextRevisi]);
    }


    public function getAvailableAdditionalMonitoringStorage(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'exclude_batch' => 'required|string'
        ]);

        $productionBatch = ProductionBatch::findOrFail($request->production_batch_id);

        $validDispositions = ['Release', 'Release Bersyarat', 'Adjustment', 'Resampling'];

        // Fungsi ambil batch valid beserta PO id-nya
        $getAvailableBatchesByPo = function ($poId, $exclude) use ($validDispositions) {
            $po = ProductionBatch::findOrFail($poId);

            // $validGgasBatches = $po->GgasProcesses()
            //     ->whereIn('disposition', $validDispositions)
            //     ->pluck('batch_number')
            //     ->map(fn ($b) => (int)$b)
            //     ->unique()
            //     ->toArray();

            $usedInBlending = $po->MonitoringStorage->flatMap(function ($item) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
                    return range((int)$matches[1], (int)$matches[2]);
                }
                return [(int)$item->batch_range];
            })->toArray();

            $usedInRelation = DB::table('monitoring_storage_relations')
                ->join('monitoring_storage', 'monitoring_storage_relations.monitoring_storage_id', '=', 'monitoring_storage_id')
                ->where('monitoring_storage.production_batch_id', $po->id)
                ->pluck('batch')
                ->map(fn ($b) => (int)$b)
                ->toArray();

            $availableBatches = array_values(array_diff($usedInBlending, $usedInRelation, $exclude));

            // Return array dengan struktur: ['po_id' => ..., 'batch_number' => ...]
            return array_map(fn ($batch) => ['po_id' => $poId, 'batch_number' => $batch, 'po_number' => $po->po_number,], $availableBatches);
        };

        $exclude = explode('-', $request->exclude_batch);
        $exclude = array_map('intval', $exclude);

        $available = $getAvailableBatchesByPo($productionBatch->id, $exclude);

        if (empty($available)) {
            $otherPOs = ProductionBatch::where('id', '!=', $productionBatch->id)->get();

            foreach ($otherPOs as $otherPO) {
                $batchesFromOtherPo = $getAvailableBatchesByPo($otherPO->id, $exclude);
                if (!empty($batchesFromOtherPo)) {
                    $available = $batchesFromOtherPo;
                    break;
                }
            }
        }

        return response()->json(['data' => $available]);
    }



    public function getMainMonitoringStorageJalanBareng(Request $request)
    {

        $request->validate([
            'production_batch_id' => 'required|exists:production_batches,id'
        ]);

        $productionBatchId = $request->production_batch_id;
        $excludedDispositions = ['Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'];

        $usedBatchIds = MonitoringStorageRelation::pluck('monitoring_storage_id')->toArray();

        // Ambil dulu dari PO yang sama
        $mainBlending = MonitoringStorageModel::where('production_batch_id', $productionBatchId)
            ->whereNotIn('disposition', $excludedDispositions)
            ->whereNotIn('id', $usedBatchIds)
            ->with('productionBatch')
            ->orderByDesc('id') // atau 'nomor_blending' kalau itu numeric
            ->get();

        // Kalau kosong, ambil dari PO lain
        if ($mainBlending->isEmpty()) {
            $mainBlending = MonitoringStorageModel::where('production_batch_id', '!=', $productionBatchId)
                ->whereNotIn('disposition', $excludedDispositions)
                ->whereNotIn('id', $usedBatchIds)
                ->with('productionBatch')
                ->orderByDesc('id') // atau 'nomor_blending'
                ->get();
        }

        $result = $mainBlending->map(function ($item) {
            return [
                'id' => $item->id,
                'batch_range' => $item->batch_range,
                'po_id' => $item->production_batch_id,
                'po_number' => $item->productionBatch?->po_number ?? null,
                'nomor_blending' => $item->nomor_blending,
            ];
        });

        return response()->json(['data' => $result]);
    }

    public function generateRevisiMonitoringStorage(Request $request)
    {
        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
        ]);

        $old = MonitoringStorageModel::findOrFail($validated['id_old_blending']);
        $old->update([
            'not_standar' => false, // atau logika lain sesuai kebutuhanmu
        ]);
        // Ambil disposisi data lama
        $oldDisposition = $old->disposition;

        // Hanya jika disposisi Jalan Bareng atau Leveling, additional_batch wajib dan diproses
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling'])) {
            if (empty($validated['additional_batch'])) {
                return response()->json([
                    'message' => 'Batch tambahan wajib diisi untuk disposisi "Jalan Bareng" atau "Leveling".'
                ], 422);
            }
        } else {
            // Jika bukan kedua disposisi tsb, pastikan additional_batch tidak diproses
            $validated['additional_batch'] = null;
        }

        // Cek revisi sudah ada atau belum
        $exists = MonitoringStorageModel::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_range', $validated['batch_range'])
            ->where('revisi', $validated['revisi'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data revisi sudah ada, coba generate ulang.'
            ], 422);
        }

        // Tandai data lama sebagai tidak standar (not_standar = true)
        // $old->update(['not_standar' => true]);

        // Buat revisi baru
        $new = MonitoringStorageModel::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $validated['batch_range'],
            'nomor_blending' => $validated['no_blending'],
            'volume_blending' => $validated['volume'],
            'brix' => null,
            'nacl' => null,
            'bj' => null,
            'visco' => null,
            'aw' => null,
            'buih' => null,
            'organo' => null,
            'ph' => null,
            'endapan' => null,
            'warna' => null,
            'disposition' => null,
            'disposition_remarks' => null,
            'adjusment_qty' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi'],
            'not_standar' => false
        ]);
        $range = trim($validated['batch_range'] ?? '');
        $additional = trim($validated['additional_batch'] ?? '');
        $batchmikronew = $range . "-" . $additional;
        MonitoringStorageMikroModel::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $batchmikronew,
            'nomor_blending' => $request->no_blending,
            'volume_blending' => $request->volume,
            'revisi' => true,
        ]);

        // Simpan batch tambahan hanya jika disposisi Jalan Bareng atau Leveling dan ada input tambahan_batch
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling']) && !empty($validated['additional_batch'])) {
            DB::table('monitoring_storage_relations')->insert([
                'monitoring_storage_id' => $new->id,
                'batch' => $validated['additional_batch'],
                'created_at' => now(),
                'updated_at' => now(),
                'production_batch_id' => $validated['production_batch_id']
            ]);
        }

        return response()->json(['message' => 'Revisi Blending Awal berhasil dibuat.']);
    }

    public function getCompletedGga($id)
    {
       
        $identitas = ProductionBatch::with([
            'GgaProcesses'
        ])->findOrFail($id);
        return response()->json([
            'gga_complete' => $identitas->isGgaComplete()
        ]);
    }
    public function getCompletedGgas($id)
    {
       
        $identitas = ProductionBatch::with([
            'GgasProcesses'
        ])->findOrFail($id);
        return response()->json([
            'ggas_complete' => $identitas->isGgasComplete()
        ]);
    }
}
