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
use App\Models\MonitoringPasteurisasi;
use App\Models\MonitoringPasteurisasiRelation;
use App\Models\MonitoringStorageBeforeUse;
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
        $productionBatches = ProductionBatch::with('GgaProcesses')
            ->orderBy('created_at', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return ($batch->isGGaComplete() && $batch->isGGasComplete()) ? 1 : 0;
            })
            ->values();

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
        $productionBatches = ProductionBatch::with('BlendingAwal')
            ->orderBy('production_date', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return $batch->isBlendingAwalComplete() ? 1 : 0;
            })
            ->values();


        return view('productionbatch.data_po_blending_awal', compact('productionBatches'));
    }

    public function data_po_blending_after_adjust()
    {
        $productionBatches = ProductionBatch::whereHas('blendingAwal', function ($query) {
            $query->where('disposition', 'Adjustment');
        })
            ->with(['blendingAwal' => function ($query) {
                $query->where('disposition', 'Adjustment');
            }])
            ->orderBy('created_at', 'desc')
            ->get();


        return view('productionbatch.data_po_blending_after_adjust', compact('productionBatches'));
    }


    public function data_po_monitoring()
    {
        $productionBatches = ProductionBatch::orderBy('production_date', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return $batch->isMonitoringBlendingComplete() ? 1 : 0;
            })
            ->values();

        $revisiData = MonitoringTurunBlending::where('revisi', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.monitoring.data_po_monitoring', compact('productionBatches', 'revisiData'));
    }

    public function data_po_monitoring_pasteurisasi()
    {
        $productionBatches = ProductionBatch::orderBy('production_date', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return $batch->isMonitoringPasteurisasiComplete() ? 1 : 0;
            })
            ->values();

        $revisiData = MonitoringPasteurisasi::where('revisi', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.monitoring_pasteurisasi.data_po_monitoring_pasteurisasi', compact('productionBatches', 'revisiData'));
    }

    public function data_po_monitoring_storage()
    {
        $productionBatches = ProductionBatch::orderBy('production_date', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return $batch->isMonitoringStorageMakroComplete() ? 1 : 0;
            })
            ->values();

        // Ambil semua revisi > 0 dan buat key gabungan batch_id|batch_range
        $revisiData = MonitoringStorageModel::where('revisi', '>', 0)
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.monitoring_storage.data_po_monitoring_storage', compact('productionBatches', 'revisiData'));
    }

    public function data_po_monitoring_storage_before_use()
    {
        $productionBatches = ProductionBatch::orderBy('production_date', 'desc')
            ->get()
            ->sortBy(function ($batch) {
                return $batch->isMonitoringStorageBeforeUseComplete() ? 1 : 0;
            })
            ->values();

        $revisiData = MonitoringStorageBeforeUse::get()
            ->mapWithKeys(function ($item) {
                $key = $item->production_batch_id . '|' . $item->batch_range;
                return [$key => true];
            });

        return view('productionbatch.monitoring_storage_before_use.data_po_monitoring_storage_before_use', compact('productionBatches', 'revisiData'));
    }

    public function show_monitoring_storage_before_use($id)
    {
        $productionBatch = ProductionBatch::with('MonitoringStorageBeforeUse')->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        // Ambil data dari MonitoringPasteurisasi yang sudah Release
        $monitoringPasteurisasi = MonitoringPasteurisasi::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $all = $monitoringPasteurisasi;

        // Kelompokkan berdasarkan batch_range
        $grouped = $all->groupBy('batch_range');

        $rawBatchGroups = [];

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn($item) => is_numeric($item->revisi) ? (int) $item->revisi : 0
            )->first();

            if (!$chosen) {
                continue;
            }

            $numbers = [];

            // Ekspansi batch_range utama
            $br = trim($chosen->batch_range ?? '');
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $br, $m)) {
                $numbers = range((int)$m[1], (int)$m[2]);
            } elseif (strpos($br, '-') !== false) {
                $parts = array_filter(array_map('trim', explode('-', $br)), fn($p) => $p !== '');
                $numbers = array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
            } else {
                $numbers = [(int) filter_var($br, FILTER_SANITIZE_NUMBER_INT)];
            }

            // Ekspansi batch terkait dari tabel relasi
            $relatedBatches = DB::table('monitoring_pasteurisasi_relations')
                ->where('monitoring_pasteurisasi_id', $chosen->id)
                ->pluck('batch')
                ->toArray();

            foreach ($relatedBatches as $relRange) {
                $relRange = trim($relRange);
                if ($relRange === '') continue;

                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $relRange, $rm)) {
                    $numbers = array_merge($numbers, range((int)$rm[1], (int)$rm[2]));
                } elseif (strpos($relRange, '-') !== false) {
                    $parts = array_filter(array_map('trim', explode('-', $relRange)), fn($p) => $p !== '');
                    $partsNums = array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
                    $numbers = array_merge($numbers, $partsNums);
                } else {
                    $numbers[] = (int) filter_var($relRange, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            $numbers = array_values(array_unique($numbers));
            sort($numbers, SORT_NUMERIC);

            $rawBatchGroups[] = [
                'numbers' => $numbers,
                'source_id' => $chosen->id,
                'production_batch_id' => $chosen->production_batch_id,
            ];
        }

        // Ambil nomor batch yang sudah digunakan di MonitoringStorageBeforeUse
        $usedMonitoringNumbers = [];
        foreach ($productionBatch->MonitoringStorageBeforeUse as $mEntry) {
            $mbr = trim($mEntry->batch_range ?? '');
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mbr, $mm)) {
                $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$mm[1], (int)$mm[2]));
            } elseif (strpos($mbr, '-') !== false) {
                $parts = array_filter(array_map('trim', explode('-', $mbr)), fn($p) => $p !== '');
                $usedMonitoringNumbers = array_merge(
                    $usedMonitoringNumbers,
                    array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts)
                );
            } else {
                $usedMonitoringNumbers[] = (int) filter_var($mbr, FILTER_SANITIZE_NUMBER_INT);
            }
        }
        $usedMonitoringNumbers = array_values(array_unique($usedMonitoringNumbers));

        // Filter kandidat agar tidak duplikat dengan batch yang sudah dipakai
        $candidates = array_filter($rawBatchGroups, function ($grp) use ($usedMonitoringNumbers) {
            if (empty($grp['numbers'])) return false;
            return empty(array_intersect($grp['numbers'], $usedMonitoringNumbers));
        });

        // Hapus kandidat yang subset dari kandidat lain
        $finalCandidates = [];
        foreach ($candidates as $i => $cand) {
            $isSubset = false;
            foreach ($candidates as $j => $other) {
                if ($i === $j) continue;
                if (empty(array_diff($cand['numbers'], $other['numbers']))) {
                    $isSubset = true;
                    break;
                }
            }
            if (!$isSubset) {
                $finalCandidates[] = $cand;
            }
        }

        // Konversi ke string agar mudah ditampilkan di view
        $filteredBatchGroups = array_map(function ($grp) {
            return implode('-', $grp['numbers']);
        }, $finalCandidates);

        // Tambahkan properti tambahan agar view tidak error
        foreach ($productionBatch->MonitoringStorageBeforeUse as $data) {
            $data->has_relation = false;
            $data->related_batches = null;
        }

        return view('productionbatch.monitoring_storage_before_use.detail_monitoring_storage_before_use', compact(
            'productionBatch',
            'filteredBatchGroups'
        ));
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
            'description' => 'nullable|string|max:255',
        ]);

        // Parsing batch range
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $validatedData['batch_range'], $match)) {
            $start = (int) $match[1];
            $end = (int) $match[2];
        } else {
            $start = $end = (int) $validatedData['batch_range'];
        }

        $batches = range($start, $end);
        $chunks = array_chunk($batches, 10);

        foreach ($chunks as $group) {
            ProductionBatch::create([
                'po_number' => $validatedData['po_number'],
                'variant' => $validatedData['variant'],
                'production_date' => $validatedData['production_date'],
                'batch_range' => min($group) . '-' . max($group),
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
            ->map(fn($b) => (int)$b)
            ->toArray();

        // Cek apakah semua batch sudah masuk GGA
        $allCovered = count(
            collect($batches)->intersect(
                $productionBatch->GgaProcesses->pluck('batch_number')->map(fn($b) => (int)$b)
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

        // Ambil semua batch dari range PO
        $batches = $productionBatch->batch_range_array; // e.g. [1, 2, 3, 4, ...]

        // Ambil batch yang sudah digunakan di BlendingAwal (batch_range)
        $usedInBlendingAwal = $productionBatch->BlendingAwal
            ->flatMap(function ($item) {
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $item->batch_range, $matches)) {
                    return range((int) $matches[1], (int) $matches[2]);
                }
                return [(int) $item->batch_range];
            })
            ->unique()
            ->toArray();

        // Ambil batch yang sudah digunakan di relasi tambahan (blending_batch_relations)
        $blendingAwalIds = $productionBatch->BlendingAwal->pluck('id')->toArray();
        $usedInRelation = \App\Models\BlendingBatchRelation::whereIn('blending_awal_id', $blendingAwalIds)
            ->pluck('batch')
            ->flatMap(function ($batch) {
                if (str_contains($batch, '-')) {
                    [$start, $end] = explode('-', $batch);
                    return range((int) $start, (int) $end);
                }
                return [(int) $batch];
            })
            ->unique()
            ->toArray();

        // Filter hanya batch yang belum terpakai
        $batches = array_values(array_diff($batches, $usedInBlendingAwal, $usedInRelation));

        // Hanya ambil disposisi tertentu dari GGAS
        $validDispositions = ['Release', 'Release Bersyarat'];

        // Ambil batch_number dari GGAS yang disposisinya valid
        $validGgasBatches = $productionBatch->GgasProcesses()
            ->whereIn('disposition', $validDispositions)
            ->pluck('batch_number')
            ->map(fn($b) => (int) $b)
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
        $request->merge([
            'volume' => str_replace(',', '.', $request->volume),
        ]);
        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable|array',
            'production_batch_id_leveling' => 'nullable|array',
            'no_blending' => 'required|string',
            'volume' => 'required|string',
            'storage' => 'nullable|string',
        ]);

        $old = BlendingAwalModel::findOrFail($validated['id_old_blending']);
        $old->update(['not_standar' => false]);

        $oldDisposition = $old->disposition;

        // Validasi batch tambahan
        if ($oldDisposition === 'Leveling') {
            if (empty($validated['additional_batch']) || count(array_filter($validated['additional_batch'])) < 1) {
                return response()->json(['message' => 'Minimal 1 batch tambahan wajib diisi untuk disposisi "Leveling".'], 422);
            }
        } elseif ($oldDisposition === 'Jalan Bareng') {
            if (empty($validated['additional_batch']) || count(array_filter($validated['additional_batch'])) < 1) {
                return response()->json(['message' => 'Batch tambahan wajib diisi untuk disposisi "Jalan Bareng".'], 422);
            }
        }

        // Cek apakah revisi sudah ada
        $exists = BlendingAwalModel::where('production_batch_id', $validated['production_batch_id'])
            ->where('batch_range', $validated['batch_range'])
            ->where('revisi', $validated['revisi'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Data revisi sudah ada, coba generate ulang.'], 422);
        }

        // Normalisasi tambahan batch dan production_batch_id_leveling jadi array
        $additionalBatches = is_array($validated['additional_batch']) ? array_filter($validated['additional_batch']) : [];
        $poLevelings = is_array($validated['production_batch_id_leveling']) ? array_filter($validated['production_batch_id_leveling']) : [];

        // Loop untuk Leveling atau Jalan Bareng
        if (in_array($oldDisposition, ['Leveling'])) {
            foreach ($additionalBatches as $index => $batchAdditional) {
                $new = BlendingAwalModel::create([
                    'production_batch_id' => $validated['production_batch_id'],
                    'batch_range' => $validated['batch_range'],
                    'nomor_blending' => $validated['no_blending'],
                    'volume' => $validated['volume'],
                    'storage' => $validated['storage'],
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
                    'adjustment_qty_air' => null,
                    'adjustment_qty_garam' => null,
                    'adjustment_qty_gula' => null,
                    'is_adjustment' => true,
                    'revisi' => $validated['revisi'],
                    'not_standar' => false,
                ]);

                DB::table('blending_batch_relations')->insert([
                    'blending_awal_id' => $new->id,
                    'batch' => $batchAdditional,
                    'production_batch_id' => $poLevelings[$index] ?? $validated['production_batch_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($oldDisposition === 'Jalan Bareng') {
            $batchAdditional = is_array($validated['additional_batch']) ? $validated['additional_batch'][0] : $validated['additional_batch'];
            $poLeveling = is_array($validated['production_batch_id_leveling']) ? $validated['production_batch_id_leveling'][0] : $validated['production_batch_id_leveling'];
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
                'adjustment_qty_air' => null,
                'adjustment_qty_garam' => null,
                'adjustment_qty_gula' => null,
                'is_adjustment' => false,
                'revisi' => $validated['revisi'],
                'not_standar' => false,
            ]);

            DB::table('blending_batch_relations')->insert([
                'blending_awal_id' => $new->id,
                'batch' => $batchAdditional,
                'production_batch_id' => $poLeveling,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($oldDisposition === 'Adjustment') {
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
                'adjustment_qty_air' => null,
                'adjustment_qty_garam' => null,
                'adjustment_qty_gula' => null,
                'is_adjustment' => false,
                'revisi' => $validated['revisi'],
                'not_standar' => false,
            ]);

            DB::table('blending_after_adjust_mikro')->insert([
                'production_batch_id' => $validated['production_batch_id'],
                'batch_range' => $validated['batch_range'],
                'nomor_blending' => $validated['no_blending'],
                'volume_blending' => $validated['volume']
            ]);
        } else {
            // Disposisi biasa, tanpa batch tambahan
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
                'adjustment_qty_air' => null,
                'adjustment_qty_garam' => null,
                'adjustment_qty_gula' => null,
                'is_adjustment' => false,
                'revisi' => $validated['revisi'],
                'not_standar' => false,
            ]);
        }

        return response()->json(['message' => 'Revisi Blending Awal berhasil dibuat.']);
    }


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
                ->map(fn($b) => (int) $b)
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
                ->map(fn($b) => (int) $b)
                ->toArray();

            $availableBatches = array_values(array_diff($validGgasBatches, $usedInBlending, $usedInRelation, $exclude));

            return array_map(fn($batch) => [
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
                    $available = array_merge($available, $result);
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

        // Ambil dari PO yang sama dulu
        $mainSame = BlendingAwalModel::where('production_batch_id', $productionBatchId)
            ->whereNotIn('disposition', $excludedDispositions)
            ->whereNotIn('id', $usedBatchIds)
            ->with('productionBatch')
            ->orderByDesc('id')
            ->get();

        if ($mainSame->isNotEmpty()) {
            $mainBlending = $mainSame;
        } else {
            // Jika tidak ada di PO yang sama, ambil dari PO lain
            $mainBlending = BlendingAwalModel::where('production_batch_id', '!=', $productionBatchId)
                ->whereNotIn('disposition', $excludedDispositions)
                ->whereNotIn('id', $usedBatchIds)
                ->with('productionBatch')
                ->orderByDesc('id')
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
        })->values();

        return response()->json(['data' => $result]);
    }

    //Blending After Adjust
    public function show_blending_after_adjust($id)
    {
        $productionBatch = ProductionBatch::with([
            'blendingAfterAdjust' => fn($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        $all = BlendingAwalModel::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $grouped = $all->groupBy('batch_range');
        $batchGroups = [];

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn($item) =>
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
                ->map(fn($b) => (int) $b)
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
                ->map(fn($b) => (int) $b)
                ->toArray();

            $availableBatches = array_values(array_diff($validGgasBatches, $usedInBlending, $usedInRelation, $exclude));

            return array_map(fn($batch) => [
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

    public function show_monitoring_blending($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringTurunBlending' => fn($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        $blendingAwal = BlendingAwalModel::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $all = $blendingAwal;

        // helper: expand a range string into array of integers
        $expandToNumbers = function (?string $str) {
            $str = trim((string)$str);
            if ($str === '') {
                return [];
            }
            // canonical "start-end"
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $str, $m)) {
                return range((int)$m[1], (int)$m[2]);
            }
            // chained like "1-2-3" or single number "5"
            if (strpos($str, '-') !== false) {
                $parts = array_filter(array_map('trim', explode('-', $str)), fn($p) => $p !== '');
                return array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
            }
            return [(int) filter_var($str, FILTER_SANITIZE_NUMBER_INT)];
        };

        // Build raw groups with numeric expansions
        $grouped = $all->groupBy('batch_range');
        $rawBatchGroups = []; // each item: ['string' => '1-2-3', 'numbers' => [1,2,3]]

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn($item) =>
                is_numeric($item->revisi) ? (int)$item->revisi : 0
            )->first();

            if (! $chosen) continue;

            $fullStringParts = [];
            $numbers = [];

            // main range numbers
            $mainNums = $expandToNumbers($chosen->batch_range);
            $numbers = array_merge($numbers, $mainNums);
            $fullStringParts[] = $chosen->batch_range;

            // related batches from blending_batch_relations
            $relatedBatches = DB::table('blending_batch_relations')
                ->where('blending_awal_id', $chosen->id)
                ->pluck('batch')
                ->toArray();

            foreach ($relatedBatches as $relRange) {
                $relRange = trim((string)$relRange);
                if ($relRange === '') continue;
                $relNums = $expandToNumbers($relRange);
                $numbers = array_merge($numbers, $relNums);
                $fullStringParts[] = $relRange;
            }

            $numbers = array_values(array_unique($numbers));
            sort($numbers, SORT_NUMERIC);

            $rawBatchGroups[] = [
                'string' => implode('-', $fullStringParts),
                'numbers' => $numbers,
            ];
        }

        // Collect numbers already used in MonitoringTurunBlending (for this production batch)
        $usedMonitoringNumbers = [];
        foreach ($productionBatch->MonitoringTurunBlending as $mEntry) {
            $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, $expandToNumbers($mEntry->batch_range));

            $mRelated = DB::table('monitoring_turun_blending_relations')
                ->where('monitoring_turun_blending_id', $mEntry->id)
                ->pluck('batch')
                ->toArray();

            foreach ($mRelated as $mr) {
                $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, $expandToNumbers($mr));
            }
        }
        $usedMonitoringNumbers = array_values(array_unique($usedMonitoringNumbers));

        // Filter out any candidate that overlaps with already used numbers
        $candidates = array_filter($rawBatchGroups, function ($grp) use ($usedMonitoringNumbers) {
            if (empty($grp['numbers'])) return false;
            return empty(array_intersect($grp['numbers'], $usedMonitoringNumbers));
        });

        // Remove candidates that are subsets of another candidate (keep only maximal groups)
        $finalCandidates = [];
        foreach ($candidates as $i => $cand) {
            $isSubset = false;
            foreach ($candidates as $j => $other) {
                if ($i === $j) continue;
                if (empty(array_diff($cand['numbers'], $other['numbers']))) {
                    $isSubset = true;
                    break;
                }
            }
            if (! $isSubset) {
                $finalCandidates[] = $cand;
            }
        }

        // Convert to strings for view
        $filteredBatchGroups = array_map(fn($g) => $g['string'], $finalCandidates);

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

    public function show_monitoring_pasteurisasi($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringPasteurisasi' => fn($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        $monitoringTurunBlending = MonitoringTurunBlending::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $all = $monitoringTurunBlending;

        // Build candidate groups as arrays of numbers (not ambiguous strings)
        $grouped = $all->groupBy('batch_range');
        $rawBatchGroups = []; // each entry: ['numbers' => [1,2,3], 'source_id' => id, 'po_id' => production_batch_id]

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn($item) => is_numeric($item->revisi) ? (int)$item->revisi : 0
            )->first();

            if (! $chosen) {
                continue;
            }

            $numbers = [];

            // expand main batch_range
            $br = trim($chosen->batch_range ?? '');
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $br, $m)) {
                // classic range "1-4" => [1,2,3,4]
                $numbers = range((int)$m[1], (int)$m[2]);
            } elseif (strpos($br, '-') !== false) {
                // chained list like "1-2-3-4" => [1,2,3,4]
                $parts = array_filter(array_map('trim', explode('-', $br)), fn($p) => $p !== '');
                $numbers = array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
            } else {
                $numbers = [(int) filter_var($br, FILTER_SANITIZE_NUMBER_INT)];
            }

            // expand related batches from monitoring_turun_blending_relations
            $relatedBatches = DB::table('monitoring_turun_blending_relations')
                ->where('monitoring_turun_blending_id', $chosen->id)
                ->pluck('batch')
                ->toArray();

            foreach ($relatedBatches as $relRange) {
                $relRange = trim($relRange);
                if ($relRange === '') continue;

                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $relRange, $rm)) {
                    $numbers = array_merge($numbers, range((int)$rm[1], (int)$rm[2]));
                } elseif (strpos($relRange, '-') !== false) {
                    $parts = array_filter(array_map('trim', explode('-', $relRange)), fn($p) => $p !== '');
                    $partsNums = array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
                    $numbers = array_merge($numbers, $partsNums);
                } else {
                    $numbers[] = (int) filter_var($relRange, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            $numbers = array_values(array_unique($numbers));
            sort($numbers, SORT_NUMERIC);

            $rawBatchGroups[] = [
                'numbers' => $numbers,
                'source_id' => $chosen->id,
                'production_batch_id' => $chosen->production_batch_id,
            ];
        }

        // Collect numbers already used in MonitoringPasteurisasi (for this production batch)
        $usedMonitoringNumbers = [];
        foreach ($productionBatch->MonitoringPasteurisasi as $mEntry) {
            $mbr = trim($mEntry->batch_range ?? '');
            // expand main range
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mbr, $mm)) {
                $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$mm[1], (int)$mm[2]));
            } elseif (strpos($mbr, '-') !== false) {
                $parts = array_filter(array_map('trim', explode('-', $mbr)), fn($p) => $p !== '');
                $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts));
            } else {
                $usedMonitoringNumbers[] = (int) filter_var($mbr, FILTER_SANITIZE_NUMBER_INT);
            }

            // expand related monitoring_pasteurisasi_relations
            $mRelated = DB::table('monitoring_pasteurisasi_relations')
                ->where('monitoring_pasteurisasi_id', $mEntry->id)
                ->pluck('batch')
                ->toArray();

            foreach ($mRelated as $mr) {
                $mr = trim($mr);
                if ($mr === '') continue;
                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mr, $rmr)) {
                    $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$rmr[1], (int)$rmr[2]));
                } elseif (strpos($mr, '-') !== false) {
                    $parts = array_filter(array_map('trim', explode('-', $mr)), fn($p) => $p !== '');
                    $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts));
                } else {
                    $usedMonitoringNumbers[] = (int) filter_var($mr, FILTER_SANITIZE_NUMBER_INT);
                }
            }
        }
        $usedMonitoringNumbers = array_values(array_unique($usedMonitoringNumbers));

        // Filter candidates: remove any candidate that overlaps with already used numbers
        $candidates = array_filter($rawBatchGroups, function ($grp) use ($usedMonitoringNumbers) {
            if (empty($grp['numbers'])) return false;
            return empty(array_intersect($grp['numbers'], $usedMonitoringNumbers));
        });

        // Remove candidates that are subsets of another candidate (keep only maximal groups)
        $finalCandidates = [];
        foreach ($candidates as $i => $cand) {
            $isSubset = false;
            foreach ($candidates as $j => $other) {
                if ($i === $j) continue;
                // if all numbers in cand are present in other => cand is subset
                if (empty(array_diff($cand['numbers'], $other['numbers']))) {
                    $isSubset = true;
                    break;
                }
            }
            if (!$isSubset) {
                $finalCandidates[] = $cand;
            }
        }

        // Convert to strings for view (e.g. "1-2-3-4" or "5" depending on numbers)
        $filteredBatchGroups = array_map(function ($grp) {
            return implode('-', $grp['numbers']);
        }, $finalCandidates);

        foreach ($productionBatch->MonitoringPasteurisasi as $data) {
            $data->has_relation = $data->additionalBatches && $data->additionalBatches->isNotEmpty();
            $data->related_batches = $data->has_relation
                ? $data->additionalBatches->pluck('batch')->implode(', ')
                : null;

            foreach ($data->additionalBatches as $addBatch) {
                $po = ProductionBatch::find($addBatch->production_batch_id);
                $addBatch->po_number = $po->po_number ?? null;
            }
        }

        return view('productionbatch.monitoring_pasteurisasi.detail_monitoring', compact(
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
        $request->merge([
            'volume' => str_replace(',', '.', $request->volume),
        ]);

        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
            'storage' => 'nullable|string',
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
            'storage' => $validated['storage'],
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

    public function generateRevisiMonitoringPasteurisasi(Request $request)
    {
        $request->merge([
            'volume' => str_replace(',', '.', $request->volume),
        ]);

        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
            'storage' => 'nullable|string',
        ]);

        $old = MonitoringPasteurisasi::findOrFail($validated['id_old_blending']);
        $old->update([
            'not_standard' => false, // atau logika lain sesuai kebutuhanmu
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
        $exists = MonitoringPasteurisasi::where('production_batch_id', $validated['production_batch_id'])
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
        $new = MonitoringPasteurisasi::create([
            'production_batch_id' => $validated['production_batch_id'],
            'batch_range' => $validated['batch_range'],
            'nomor_blending' => $validated['no_blending'],
            'volume_blending' => $validated['volume'],
            'storage' => $validated['storage'],
            'disposition' => null,
            'disposition_remarks' => null,
            'adjusment_qty' => null,
            'is_adjustment' => false,
            'revisi' => $validated['revisi'],
            'not_standard' => false
        ]);

        // Simpan batch tambahan hanya jika disposisi Jalan Bareng atau Leveling dan ada input tambahan_batch
        if (in_array($oldDisposition, ['Jalan Bareng', 'Leveling']) && !empty($validated['additional_batch'])) {
            DB::table('monitoring_pasteurisasi_relations')->insert([
                'monitoring_pasteurisasi_id' => $new->id,
                'batch' => $validated['additional_batch'],
                'created_at' => now(),
                'updated_at' => now(),
                'production_batch_id' => $validated['production_batch_id']
            ]);
        }

        return response()->json(['message' => 'Revisi Blending Awal berhasil dibuat.']);
    }

    public function getLastRevisiMonitoringPasteurisasi(Request $request)
    {
        // $request->validate([
        //     'production_batch_id' => 'required|integer|exists:production_batches,id',
        //     'batch_range' => 'required|string',
        // ]);

        // $lastRevisi = MonitoringPasteurisasi::where('production_batch_id', $request->production_batch_id)
        //     ->where('batch_range', $request->batch_range)
        //     ->max('revisi');

        // // Jika belum ada, revisi dimulai dari 1
        // $nextRevisi = is_null($lastRevisi) ? 1 : $lastRevisi + 1;

        // return response()->json(['revisi' => $nextRevisi]);
    }

    public function getAvailableAdditionalBatchMonitoringPasteurisasi(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string'
        ]);

        // parse exclude into integer list
        $exclude = array_map('intval', explode('-', $request->exclude_batch));

        $validDispositions = ['Release', 'Release Bersyarat', 'Adjustment', 'Resampling'];

        $currentPo = ProductionBatch::findOrFail($request->production_batch_id);
        $poNumber = $currentPo->po_number;

        // helper: proses kandidat untuk daftar production_batch_id tertentu
        $processCandidates = function (array $productionBatchIds) use ($validDispositions, $exclude) {
            $all = MonitoringTurunBlending::whereIn('production_batch_id', $productionBatchIds)
                ->whereIn('disposition', $validDispositions)
                ->get();

            // group by composite key production_batch_id|batch_range to avoid mixing across PO
            $grouped = $all->groupBy(function ($item) {
                return $item->production_batch_id . '|' . $item->batch_range;
            });

            $result = [];

            foreach ($grouped as $key => $items) {
                $chosen = $items->sortByDesc(function ($item) {
                    return is_numeric($item->revisi) ? (int) $item->revisi : 0;
                })->first();

                if (! $chosen) continue;

                $numbers = [];

                // expand main batch_range (contoh "1-3" => [1,2,3])
                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $chosen->batch_range, $m)) {
                    $numbers = range((int)$m[1], (int)$m[2]);
                } else {
                    $numbers = [(int) filter_var($chosen->batch_range, FILTER_SANITIZE_NUMBER_INT)];
                }

                // ambil relasi tambahan dan expand tiap entry (bisa "5" atau "7-8")
                $related = DB::table('monitoring_turun_blending_relations')
                    ->where('monitoring_turun_blending_id', $chosen->id)
                    ->pluck('batch')
                    ->toArray();

                foreach ($related as $rel) {
                    $rel = trim($rel);
                    if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $rel, $rm)) {
                        $numbers = array_merge($numbers, range((int)$rm[1], (int)$rm[2]));
                    } elseif ($rel !== '') {
                        $numbers[] = (int) filter_var($rel, FILTER_SANITIZE_NUMBER_INT);
                    }
                }

                // unique dan sort ascending
                $numbers = array_values(array_unique($numbers));
                sort($numbers, SORT_NUMERIC);

                // jika ada irisan dengan exclude, skip
                if (!empty(array_intersect($numbers, $exclude))) {
                    continue;
                }

                // jika batch sudah ada di monitoring_turun_blending untuk PO yang sama, skip --
                $monitoringEntries = MonitoringPasteurisasi::where('production_batch_id', $chosen->production_batch_id)->get();

                $usedMonitoringNumbers = [];
                foreach ($monitoringEntries as $mEntry) {
                    // expand monitoring main batch_range
                    if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mEntry->batch_range, $mm)) {
                        $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$mm[1], (int)$mm[2]));
                    } else {
                        $usedMonitoringNumbers[] = (int) filter_var($mEntry->batch_range, FILTER_SANITIZE_NUMBER_INT);
                    }

                    // expand related monitoring batches
                    $mRelated = DB::table('monitoring_pasteurisasi_relations')
                        ->where('monitoring_pasteurisasi_id', $mEntry->id)
                        ->pluck('batch')
                        ->toArray();

                    foreach ($mRelated as $mr) {
                        $mr = trim($mr);
                        if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mr, $rmr)) {
                            $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$rmr[1], (int)$rmr[2]));
                        } elseif ($mr !== '') {
                            $usedMonitoringNumbers[] = (int) filter_var($mr, FILTER_SANITIZE_NUMBER_INT);
                        }
                    }
                }

                $usedMonitoringNumbers = array_values(array_unique($usedMonitoringNumbers));

                // jika ada overlap dengan monitoring yang sudah ada, skip
                if (!empty(array_intersect($numbers, $usedMonitoringNumbers))) {
                    continue;
                }

                $result[] = [
                    'batch_range' => implode('-', $numbers),
                    'source_blending_id' => $chosen->id,
                    'production_batch_id' => $chosen->production_batch_id,
                ];
            }

            return $result;
        };

        // Prioritas: semua production_batch dengan po_number yang sama
        $poGroupIds = ProductionBatch::where('po_number', $poNumber)->pluck('id')->toArray();
        $available = $processCandidates($poGroupIds);

        // Jika tidak ada di PO yang sama, cari dari PO lain (semua production_batch yang po_number berbeda)
        if (empty($available)) {
            $otherPoIds = ProductionBatch::where('po_number', '!=', $poNumber)->pluck('id')->toArray();
            $available = $processCandidates($otherPoIds);
        }

        // lampirkan po_number ke setiap hasil
        if (!empty($available)) {
            $poIds = array_unique(array_column($available, 'production_batch_id'));
            $poMap = ProductionBatch::whereIn('id', $poIds)->pluck('po_number', 'id')->toArray();

            foreach ($available as &$row) {
                $row['po_number'] = $poMap[$row['production_batch_id']] ?? null;
            }
            unset($row);
        }

        return response()->json(['data' => $available]);
    }

    public function getAvailableAdditionalBatchMonitoring(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'exclude_batch' => 'required|string'
        ]);

        // parse exclude into integer list
        $exclude = array_map('intval', explode('-', $request->exclude_batch));

        $validDispositions = ['Release', 'Release Bersyarat', 'Adjustment', 'Resampling'];

        $currentPo = ProductionBatch::findOrFail($request->production_batch_id);
        $poNumber = $currentPo->po_number;

        // helper: proses kandidat untuk daftar production_batch_id tertentu
        $processCandidates = function (array $productionBatchIds) use ($validDispositions, $exclude) {
            $all = BlendingAwalModel::whereIn('production_batch_id', $productionBatchIds)
                ->whereIn('disposition', $validDispositions)
                ->get();

            // group by composite key production_batch_id|batch_range to avoid mixing across PO
            $grouped = $all->groupBy(function ($item) {
                return $item->production_batch_id . '|' . $item->batch_range;
            });

            $result = [];

            foreach ($grouped as $key => $items) {
                $chosen = $items->sortByDesc(function ($item) {
                    return is_numeric($item->revisi) ? (int) $item->revisi : 0;
                })->first();

                if (! $chosen) continue;

                $numbers = [];

                // expand main batch_range (contoh "1-3" => [1,2,3])
                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $chosen->batch_range, $m)) {
                    $numbers = range((int)$m[1], (int)$m[2]);
                } else {
                    $numbers = [(int) filter_var($chosen->batch_range, FILTER_SANITIZE_NUMBER_INT)];
                }

                // ambil relasi tambahan dan expand tiap entry (bisa "5" atau "7-8")
                $related = DB::table('blending_batch_relations')
                    ->where('blending_awal_id', $chosen->id)
                    ->pluck('batch')
                    ->toArray();

                foreach ($related as $rel) {
                    $rel = trim($rel);
                    if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $rel, $rm)) {
                        $numbers = array_merge($numbers, range((int)$rm[1], (int)$rm[2]));
                    } elseif ($rel !== '') {
                        $numbers[] = (int) filter_var($rel, FILTER_SANITIZE_NUMBER_INT);
                    }
                }

                // unique dan sort ascending
                $numbers = array_values(array_unique($numbers));
                sort($numbers, SORT_NUMERIC);

                // jika ada irisan dengan exclude, skip
                if (!empty(array_intersect($numbers, $exclude))) {
                    continue;
                }

                // jika batch sudah ada di monitoring_turun_blending untuk PO yang sama, skip --
                $monitoringEntries = MonitoringTurunBlending::where('production_batch_id', $chosen->production_batch_id)->get();

                $usedMonitoringNumbers = [];
                foreach ($monitoringEntries as $mEntry) {
                    // expand monitoring main batch_range
                    if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mEntry->batch_range, $mm)) {
                        $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$mm[1], (int)$mm[2]));
                    } else {
                        $usedMonitoringNumbers[] = (int) filter_var($mEntry->batch_range, FILTER_SANITIZE_NUMBER_INT);
                    }

                    // expand related monitoring batches
                    $mRelated = DB::table('monitoring_turun_blending_relations')
                        ->where('monitoring_turun_blending_id', $mEntry->id)
                        ->pluck('batch')
                        ->toArray();

                    foreach ($mRelated as $mr) {
                        $mr = trim($mr);
                        if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mr, $rmr)) {
                            $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$rmr[1], (int)$rmr[2]));
                        } elseif ($mr !== '') {
                            $usedMonitoringNumbers[] = (int) filter_var($mr, FILTER_SANITIZE_NUMBER_INT);
                        }
                    }
                }

                $usedMonitoringNumbers = array_values(array_unique($usedMonitoringNumbers));

                // jika ada overlap dengan monitoring yang sudah ada, skip
                if (!empty(array_intersect($numbers, $usedMonitoringNumbers))) {
                    continue;
                }

                $result[] = [
                    'batch_range' => implode('-', $numbers),
                    'source_blending_id' => $chosen->id,
                    'production_batch_id' => $chosen->production_batch_id,
                ];
            }

            return $result;
        };

        // Prioritas: semua production_batch dengan po_number yang sama
        $poGroupIds = ProductionBatch::where('po_number', $poNumber)->pluck('id')->toArray();
        $available = $processCandidates($poGroupIds);

        // Jika tidak ada di PO yang sama, cari dari PO lain (semua production_batch yang po_number berbeda)
        if (empty($available)) {
            $otherPoIds = ProductionBatch::where('po_number', '!=', $poNumber)->pluck('id')->toArray();
            $available = $processCandidates($otherPoIds);
        }

        // lampirkan po_number ke setiap hasil
        if (!empty($available)) {
            $poIds = array_unique(array_column($available, 'production_batch_id'));
            $poMap = ProductionBatch::whereIn('id', $poIds)->pluck('po_number', 'id')->toArray();

            foreach ($available as &$row) {
                $row['po_number'] = $poMap[$row['production_batch_id']] ?? null;
            }
            unset($row);
        }

        return response()->json(['data' => $available]);
    }

    public function getMainMonitoringPasteurisasiJalanBareng(Request $request)
    {
        $request->validate([
            'production_batch_id' => 'required|exists:production_batches,id'
        ]);

        $productionBatchId = $request->production_batch_id;
        $excludedDispositions = ['Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'];

        $usedBatchIds = MonitoringPasteurisasiRelation::pluck('monitoring_pasteurisasi_id')->toArray();

        // Ambil dulu dari PO yang sama
        $mainBlending = MonitoringPasteurisasi::where('production_batch_id', $productionBatchId)
            ->whereNotIn('disposition', $excludedDispositions)
            ->whereNotIn('id', $usedBatchIds)
            ->with('productionBatch')
            ->orderByDesc('id') // atau 'nomor_blending' kalau itu numeric
            ->get();

        // Kalau kosong, ambil dari PO lain
        if ($mainBlending->isEmpty()) {
            $mainBlending = MonitoringPasteurisasi::where('production_batch_id', '!=', $productionBatchId)
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

    public function show_monitoring_storage($id)
    {
        $productionBatch = ProductionBatch::with([
            'MonitoringStorage' => fn($query) => $query->with('additionalBatches')
        ])->findOrFail($id);

        $validDispositions = ['Release', 'Release Bersyarat'];

        // Ambil data dari MonitoringPasteurisasi yang sudah Release
        $monitoringPasteurisasi = MonitoringPasteurisasi::where('production_batch_id', $id)
            ->whereIn('disposition', $validDispositions)
            ->get();

        $all = $monitoringPasteurisasi;

        // Build candidate groups as arrays of numbers
        $grouped = $all->groupBy('batch_range');

        // DEBUG: Cek grouped
        // dd($grouped);

        $rawBatchGroups = [];

        foreach ($grouped as $batchRange => $items) {
            $chosen = $items->sortByDesc(
                fn($item) => is_numeric($item->revisi) ? (int)$item->revisi : 0
            )->first();

            if (! $chosen) {
                continue;
            }

            $numbers = [];

            // expand main batch_range
            $br = trim($chosen->batch_range ?? '');
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $br, $m)) {
                // classic range "1-4" => [1,2,3,4]
                $numbers = range((int)$m[1], (int)$m[2]);
            } elseif (strpos($br, '-') !== false) {
                // chained list like "1-2-5-6" => [1,2,5,6]
                $parts = array_filter(array_map('trim', explode('-', $br)), fn($p) => $p !== '');
                $numbers = array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
            } else {
                $numbers = [(int) filter_var($br, FILTER_SANITIZE_NUMBER_INT)];
            }

            // expand related batches from monitoring_pasteurisasi_relations
            $relatedBatches = DB::table('monitoring_pasteurisasi_relations')
                ->where('monitoring_pasteurisasi_id', $chosen->id)
                ->pluck('batch')
                ->toArray();

            foreach ($relatedBatches as $relRange) {
                $relRange = trim($relRange);
                if ($relRange === '') continue;

                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $relRange, $rm)) {
                    $numbers = array_merge($numbers, range((int)$rm[1], (int)$rm[2]));
                } elseif (strpos($relRange, '-') !== false) {
                    $parts = array_filter(array_map('trim', explode('-', $relRange)), fn($p) => $p !== '');
                    $partsNums = array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts);
                    $numbers = array_merge($numbers, $partsNums);
                } else {
                    $numbers[] = (int) filter_var($relRange, FILTER_SANITIZE_NUMBER_INT);
                }
            }

            $numbers = array_values(array_unique($numbers));
            sort($numbers, SORT_NUMERIC);

            $rawBatchGroups[] = [
                'numbers' => $numbers,
                'source_id' => $chosen->id,
                'production_batch_id' => $chosen->production_batch_id,
            ];
        }

        // DEBUG: Cek rawBatchGroups setelah parsing
        // dd('rawBatchGroups', $rawBatchGroups);

        // Collect numbers already used in MonitoringStorage (for this production batch)
        $usedMonitoringNumbers = [];
        foreach ($productionBatch->MonitoringStorage as $mEntry) {
            $mbr = trim($mEntry->batch_range ?? '');
            // expand main range
            if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mbr, $mm)) {
                $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$mm[1], (int)$mm[2]));
            } elseif (strpos($mbr, '-') !== false) {
                $parts = array_filter(array_map('trim', explode('-', $mbr)), fn($p) => $p !== '');
                $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts));
            } else {
                $usedMonitoringNumbers[] = (int) filter_var($mbr, FILTER_SANITIZE_NUMBER_INT);
            }

            // expand related monitoring_storage_relations
            $mRelated = DB::table('monitoring_storage_relations')
                ->where('monitoring_storage_id', $mEntry->id)
                ->pluck('batch')
                ->toArray();

            foreach ($mRelated as $mr) {
                $mr = trim($mr);
                if ($mr === '') continue;
                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $mr, $rmr)) {
                    $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, range((int)$rmr[1], (int)$rmr[2]));
                } elseif (strpos($mr, '-') !== false) {
                    $parts = array_filter(array_map('trim', explode('-', $mr)), fn($p) => $p !== '');
                    $usedMonitoringNumbers = array_merge($usedMonitoringNumbers, array_map(fn($p) => (int) filter_var($p, FILTER_SANITIZE_NUMBER_INT), $parts));
                } else {
                    $usedMonitoringNumbers[] = (int) filter_var($mr, FILTER_SANITIZE_NUMBER_INT);
                }
            }
        }
        $usedMonitoringNumbers = array_values(array_unique($usedMonitoringNumbers));

        // DEBUG: Cek used numbers
        // dd('usedMonitoringNumbers', $usedMonitoringNumbers);

        // Filter candidates: remove any candidate that overlaps with already used numbers
        $candidates = array_filter($rawBatchGroups, function ($grp) use ($usedMonitoringNumbers) {
            if (empty($grp['numbers'])) return false;
            return empty(array_intersect($grp['numbers'], $usedMonitoringNumbers));
        });

        // DEBUG: Cek candidates setelah filter
        // dd('candidates after filter', $candidates);

        // Remove candidates that are subsets of another candidate (keep only maximal groups)
        $finalCandidates = [];
        foreach ($candidates as $i => $cand) {
            $isSubset = false;
            foreach ($candidates as $j => $other) {
                if ($i === $j) continue;
                // if all numbers in cand are present in other => cand is subset
                if (empty(array_diff($cand['numbers'], $other['numbers']))) {
                    $isSubset = true;
                    break;
                }
            }
            if (!$isSubset) {
                $finalCandidates[] = $cand;
            }
        }

        // Convert to strings for view
        $filteredBatchGroups = array_map(function ($grp) {
            return implode('-', $grp['numbers']);
        }, $finalCandidates);

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
                ->map(fn($b) => (int)$b)
                ->toArray();

            $availableBatches = array_values(array_diff($usedInBlending, $usedInRelation, $exclude));

            // Return array dengan struktur: ['po_id' => ..., 'batch_number' => ...]
            return array_map(fn($batch) => ['po_id' => $poId, 'batch_number' => $batch, 'po_number' => $po->po_number,], $availableBatches);
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
        $request->merge([
            'volume' => str_replace(',', '.', $request->volume),
        ]);

        $validated = $request->validate([
            'id_old_blending' => 'required|integer',
            'production_batch_id' => 'required|integer|exists:production_batches,id',
            'batch_range' => 'required|string',
            'revisi' => 'required|integer|min:1',
            'additional_batch' => 'nullable',
            'no_blending' => 'required',
            'volume' => 'required',
            'storage' => 'nullable|string',
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
            'storage' => $validated['storage'],
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
