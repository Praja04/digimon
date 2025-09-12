<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MonitoringStorageMikroModel;

class MonitoringStorageMikroController extends Controller
{
    // Analisa Monitoring Storage Mikro
    public function analisaStorageMikro(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $variant   = $request->input('variant');

        // Default date
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        // Query data monitoring_storage_mikro
        $query = MonitoringStorageMikroModel::with(['productionBatch:id,po_number,variant'])
            ->select(
                'production_batch_id',
                'batch_range',
                'nomor_blending',
                'volume_blending',
                'eb',
                'tpc',
                'ym',
                'hasil',
                'revisi',
                'created_at'
            )
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter variant jika bukan all
        if ($variant && !in_array(strtolower($variant), ['', 'all', 'all variants'])) {
            $query->whereHas('productionBatch', function ($q) use ($variant) {
                $q->where('variant', $variant);
            });
        }

        $rawData = $query->orderBy('created_at')->get();

        // Ambil data unik per batch_range + production_batch_id
        // pilih revisi tertinggi
        $filteredData = $rawData
            ->groupBy(fn ($item) => $item->batch_range . '-' . $item->production_batch_id)
            ->map(function ($items) {
                return $items->sortByDesc('revisi')->first(); // ambil revisi tertinggi
            })
            ->values()
            ->map(function ($item) {
                return [
                    'batch_range'    => $item->batch_range,
                    'nomor_blending' => $item->nomor_blending,
                    'volume_blending' => $item->volume_blending,
                    'eb'             => $item->eb,
                    'tpc'            => $item->tpc,
                    'ym'             => $item->ym,
                    'hasil'          => $item->hasil,
                    'revisi'         => $item->revisi,
                    'created_at'     => $item->created_at,
                    'po_number'      => optional($item->productionBatch)->po_number,
                    'variant'        => optional($item->productionBatch)->variant,
                ];
            });

        return response()->json([
            'monitoring_storage_mikro' => $filteredData,
            'filter_applied' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date'   => $endDate->format('Y-m-d'),
                'variant'    => $variant ?: 'all',
                'total_records' => $filteredData->count()
            ]
        ]);
    }
}
