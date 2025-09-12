<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MonitoringStorageModel;

class MonitoringStorageController extends Controller
{
    // Analisa Monitoring Storage
    public function analisaMonitoringStorage(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $variant   = $request->input('variant');

        // Set default date jika tidak ada input
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        // Query monitoring storage
        $query = MonitoringStorageModel::with(['productionBatch:id,po_number,variant'])
            ->select(
                'batch_range',
                'nomor_blending',
                'brix',
                'nacl',
                'bj',
                'visco',
                'aw',
                'buih',
                'organo',
                'ph',
                'revisi',
                'production_batch_id',
                'created_at'
            )
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter variant jika ada
        if ($variant && !in_array(strtolower($variant), ['', 'all', 'all variants'])) {
            $query->whereHas('productionBatch', function ($q) use ($variant) {
                $q->where('variant', $variant);
            });
        }

        $rawStorage = $query->orderBy('created_at')->get();

        // Ambil data unik per batch_range + production_batch_id dengan revisi tertinggi
        $filteredStorage = $rawStorage
            ->groupBy(function ($item) {
                return $item->batch_range . '-' . $item->production_batch_id;
            })
            ->map(function ($items) {
                return $items->sortByDesc(function ($item) {
                    return $item->revisi ?? 0;
                })->first();
            })
            ->values()
            ->map(function ($item) {
                return [
                    'batch_range'    => $item->batch_range,
                    'nomor_blending' => $item->nomor_blending,
                    'brix'           => $item->brix,
                    'nacl'           => $item->nacl,
                    'bj'             => $item->bj,
                    'visco'          => $item->visco,
                    'aw'             => $item->aw,
                    'buih'           => $item->buih,
                    'organo'         => $item->organo,
                    'ph'             => $item->ph,
                    'created_at'     => $item->created_at,
                    'revisi'         => $item->revisi,
                    'po_number'      => optional($item->productionBatch)->po_number,
                    'variant'        => optional($item->productionBatch)->variant,
                ];
            });

        return response()->json([
            'monitoring_storage' => $filteredStorage,
            'filter_applied' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'variant' => $variant ?: 'all',
                'total_records' => $filteredStorage->count()
            ]
        ]);
    }

    // Analisa Disposisi Storage
    public function analisaDisposisi(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $variant   = $request->input('variant');

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        $query = MonitoringStorageModel::with(['productionBatch:id,po_number,variant'])
            ->whereNotNull('disposition')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Perbaiki filter variant - hanya filter jika variant tidak kosong dan bukan 'all'
        if ($variant && !in_array(strtolower($variant), ['', 'all', 'all variants'])) {
            $query->whereHas('productionBatch', function ($q) use ($variant) {
                $q->where('variant', $variant);
            });
        }

        $rawDispositions = $query->get();

        // Sama: ambil revisi tertinggi per kombinasi batch_range + production_batch_id
        $filteredDispositions = $rawDispositions
            ->groupBy(function ($item) {
                return $item->batch_range . '_' . $item->production_batch_id;
            })
            ->map(function ($items) {
                return $items->sortByDesc(function ($item) {
                    return $item->revisi ?? 0;
                })->first();
            })
            ->values();

        $summary = $filteredDispositions
            ->groupBy('disposition')
            ->map(fn ($group) => $group->count());

        return response()->json([
            'disposition_summary' => $summary,
            'filter_applied' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'variant' => $variant ?: 'all',
                'total_records' => $filteredDispositions->count()
            ]
        ]);
    }
}
