<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\GgaProcess;
use App\Models\GgasProcess;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiGGAGGASController extends Controller
{
    //
    public function summarizeParameterPerBatch()
    {
        $batches = ProductionBatch::with(['GgaProcesses', 'GgasProcesses'])->get();

        $variantSummary = $batches->groupBy('variant')->map(function ($groupedBatches, $variant) {
            $gga = $groupedBatches->flatMap->GgaProcesses;
            $ggas = $groupedBatches->flatMap->GgasProcesses;

            return [
                'variant' => $variant,
                'gga' => [
                    'brix_avg' => round($gga->avg('brix'), 2),
                    'nacl_avg' => round($gga->avg('nacl'), 2),
                    'warna' => $gga->pluck('warna')->unique()->values(),
                    'not_standar_count' => $gga->where('not_standar', true)->count(),
                    'revisi_count' => $gga->whereNotNull('revisi')->count(),
                ],
                'ggas' => [
                    'brix_avg' => round($ggas->avg('brix'), 2),
                    'nacl_avg' => round($ggas->avg('nacl'), 2),
                    'warna' => $ggas->pluck('warna')->unique()->values(),
                    'not_standar_count' => $ggas->where('not_standar', true)->count(),
                    'revisi_count' => $ggas->whereNotNull('revisi')->count(),
                ],
            ];
        })->values(); // reset to indexed array

        return response()->json($variantSummary);

    }

    public function analyzeQCIssues()
    {
        $gga = GgaProcess::all();
        $ggas = GgasProcess::all();

        return response()->json([
            'gga' => [
                'total_revisi' => $gga->whereNotNull('revisi')->count(),
                'disposition_abnormal' => $gga->whereNotIn('disposition', ['Release','Release Bersyarat'])->count(),
                'top_reasons' => $gga->pluck('disposition_remarks')
                ->filter()
                    ->countBy()
                    ->sortDesc()
                    ->take(5),
            ],
            'ggas' => [
                'total_revisi' => $ggas->whereNotNull('revisi')->count(),
                'disposition_abnormal' => $ggas->whereNotIn('disposition', ['Release','Release Bersyarat'])->count(),
                'top_reasons' => $ggas->pluck('disposition_remarks')
                ->filter()
                    ->countBy()
                    ->sortDesc()
                    ->take(5),
            ]
        ]);
    }

    public function compareGgaAndGgasProcesses()
    {
        $gga = GgaProcess::all();
        $ggas = GgasProcess::all();

        return response()->json([
            'gga' => [
                'label' => 'GGA',
                'total' => $gga->count(),
                'avg_brix' => round($gga->avg('brix'), 2),
                'avg_nacl' => round($gga->avg('nacl'), 2),
                'revisi_count' => $gga->whereNotNull('revisi')->count(),
                'not_standar_count' =>  $gga->whereNotIn('disposition', ['Release', 'Release Bersyarat'])->count(),
            ],
            'ggas' => [
                'label' => 'GGAS',
                'total' => $ggas->count(),
                'avg_brix' => round($ggas->avg('brix'), 2),
                'avg_nacl' => round($ggas->avg('nacl'), 2),
                'revisi_count' => $ggas->whereNotNull('revisi')->count(),
                'not_standar_count' => $ggas->whereNotIn('disposition', ['Release', 'Release Bersyarat'])->count(),
            ],
        ]);
    }


    public function trackParameterTrendsOverTime()
    {
        $gga = GgaProcess::selectRaw('DATE(created_at) as date')
        ->selectRaw('AVG(brix) as avg_brix')
        ->selectRaw('AVG(nacl) as avg_nacl')
        ->groupBy('date')
            ->orderBy('date')
            ->get();

        $ggas = GgasProcess::selectRaw('DATE(created_at) as date')
        ->selectRaw('AVG(brix) as avg_brix')
        ->selectRaw('AVG(nacl) as avg_nacl')
        ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'gga' => $gga,
            'ggas' => $ggas,
        ]);
    }
    public function analyzeVariantAndDissolverPerformance()
    {
        // Variant - GGA
        $variantGGA = ProductionBatch::with('GgaProcesses')
        ->get()
            ->groupBy('variant')
            ->map(function ($batches, $variant) {
                $gga = $batches->flatMap->GgaProcesses;

                return [
                    'variant' => $variant,
                    'not_standar_count' => $gga->whereNotIn('disposition', ['Release', 'Release Bersyarat'])->count(),
                ];
            })
            ->values();

        // Variant - GGAS
        $variantGGAS = ProductionBatch::with('GgasProcesses')
        ->get()
            ->groupBy('variant')
            ->map(function ($batches, $variant) {
                $ggas = $batches->flatMap->GgasProcesses;

                return [
                    'variant' => $variant,
                    'not_standar_count' => $ggas->whereNotIn('disposition', ['Release', 'Release Bersyarat'])->count(),
                ];
            })
            ->values();

        // Dissolver - hanya GGA yang punya
        $dissolverGGA = GgaProcess::select('dissolver_number')
        ->selectRaw('COUNT(*) as total')
        ->selectRaw("SUM(CASE WHEN disposition NOT IN ('Release','Release Bersyarat') THEN 1 ELSE 0 END) as not_standar_count")
        ->groupBy('dissolver_number')
        ->orderByDesc('not_standar_count')
        ->get();

        return response()->json([
            'variant_summary' => [
                'gga' => $variantGGA,
                'ggas' => $variantGGAS,
            ],
            'dissolver_summary' => $dissolverGGA, // GGA only
        ]);
    }

    public function evaluateBatchSuccessRate()
    {
        $batches = ProductionBatch::with(['GgaProcesses', 'GgasProcesses'])->get();

        // GGA — Per Proses
        $ggaProcesses = $batches->flatMap->GgaProcesses;
        $ggaSuccessProcesses = $ggaProcesses->whereIn('disposition', ['Release', 'Release Bersyarat']);

        // GGA — Per Batch
        $ggaBatches = $batches->filter(fn ($b) => $b->GgaProcesses->isNotEmpty());
        $ggaSuccessBatches = $ggaBatches->filter(function ($batch) {
            return $batch->GgaProcesses->every(fn ($p) =>
            in_array($p->disposition, ['Release', 'Release Bersyarat']));
        });

        // GGAS — Per Proses
        $ggasProcesses = $batches->flatMap->GgasProcesses;
        $ggasSuccessProcesses = $ggasProcesses->whereIn('disposition', ['Release', 'Release Bersyarat']);

        // GGAS — Per Batch
        $ggasBatches = $batches->filter(fn ($b) => $b->GgasProcesses->isNotEmpty());
        $ggasSuccessBatches = $ggasBatches->filter(function ($batch) {
            return $batch->GgasProcesses->every(fn ($p) =>
            in_array($p->disposition, ['Release', 'Release Bersyarat']));
        });

        return response()->json([
            'gga' => [
                'process' => [
                    'total' => $ggaProcesses->count(),
                    'success' => $ggaSuccessProcesses->count(),
                    'rate' => $ggaProcesses->count() > 0
                        ? round($ggaSuccessProcesses->count() / $ggaProcesses->count() * 100, 2)
                        : 0,
                ],
                'batch' => [
                    'total' => $ggaBatches->count(),
                    'success' => $ggaSuccessBatches->count(),
                    'rate' => $ggaBatches->count() > 0
                        ? round($ggaSuccessBatches->count() / $ggaBatches->count() * 100, 2)
                        : 0,
                ],
            ],
            'ggas' => [
                'process' => [
                    'total' => $ggasProcesses->count(),
                    'success' => $ggasSuccessProcesses->count(),
                    'rate' => $ggasProcesses->count() > 0
                        ? round($ggasSuccessProcesses->count() / $ggasProcesses->count() * 100, 2)
                        : 0,
                ],
                'batch' => [
                    'total' => $ggasBatches->count(),
                    'success' => $ggasSuccessBatches->count(),
                    'rate' => $ggasBatches->count() > 0
                        ? round($ggasSuccessBatches->count() / $ggasBatches->count() * 100, 2)
                        : 0,
                ],
            ]
        ]);
    }


    ///dipakaii
    public function analisaGGAGGAS(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $variant   = $request->input('variant'); // 👈 tangkap variant

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate   = $endDate ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        // === GGA Data ===
        $ggaData = GgaProcess::with(['productionBatch:id,po_number,variant'])
        ->select('id', 'batch_number', 'brix', 'nacl', 'production_batch_id', 'created_at')
        ->whereNotNull('brix')
        ->whereNotNull('nacl')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->when($variant, function ($q) use ($variant) {
            $q->whereHas('productionBatch', function ($sub) use ($variant) {
                $sub->where('variant', $variant);
            });
        }) // 👈 filter by variant jika ada
            ->orderBy('created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'batch_number'        => $item->batch_number,
                    'brix'                => $item->brix,
                    'nacl'                => $item->nacl,
                    'production_batch_id' => $item->production_batch_id,
                    'po_number'           => optional($item->productionBatch)->po_number,
                    'variant'             => optional($item->productionBatch)->variant,
                    'created_at'          => $item->created_at->toDateTimeString(),
                ];
            });

        // === GGAS Data ===
        $ggasData = GgasProcess::with(['productionBatch:id,po_number,variant'])
        ->select('id', 'batch_number', 'brix', 'nacl', 'production_batch_id', 'created_at')
        ->whereNotNull('brix')
        ->whereNotNull('nacl')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->when($variant, function ($q) use ($variant) {
            $q->whereHas('productionBatch', function ($sub) use ($variant) {
                $sub->where('variant', $variant);
            });
        }) // 👈 filter by variant juga
            ->orderBy('created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'batch_number'        => $item->batch_number,
                    'brix'                => $item->brix,
                    'nacl'                => $item->nacl,
                    'production_batch_id' => $item->production_batch_id,
                    'po_number'           => optional($item->productionBatch)->po_number,
                    'variant'             => optional($item->productionBatch)->variant,
                    'created_at'          => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
                'gga'  => $ggaData,
                'ggas' => $ggasData,
            ]);
    }


    public function analisaDisposisi(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $variant   = $request->input('variant');

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate   = $endDate ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        // GGA analysis
        $ggaDispositions = GgaProcess::whereNotNull('disposition')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($variant, function ($q) use ($variant) {
                $q->whereHas('productionBatch', function ($sub) use ($variant) {
                    $sub->where('variant', $variant);
                });
            })
            ->get()
            ->groupBy('disposition')
            ->map(fn ($group) => $group->count());

        // GGAS analysis
        $ggasDispositions = GgasProcess::whereNotNull('disposition')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($variant, function ($q) use ($variant) {
                $q->whereHas('productionBatch', function ($sub) use ($variant) {
                    $sub->where('variant', $variant);
                });
            })
            ->get()
            ->groupBy('disposition')
            ->map(fn ($group) => $group->count());

        return response()->json([
            'gga'  => $ggaDispositions,
            'ggas' => $ggasDispositions,
        ]);
    }


}
