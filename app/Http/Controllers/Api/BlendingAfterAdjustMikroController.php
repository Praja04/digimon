<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\BlendingAfterAdjustMikroModel;

class BlendingAfterAdjustMikroController extends Controller
{
    // Analisa Blending After Adjust Mikro
    public function analisaBlendingMikro(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $variant   = $request->input('variant');

        // Default date
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        // Query data blending_after_adjust_mikro
        $query = BlendingAfterAdjustMikroModel::with(['productionBatch:id,po_number,variant'])
            ->select(
                'production_batch_id',
                'batch_range',
                'nomor_blending',
                'volume_blending',
                'eb',
                'tpc',
                'ym',
                'hasil',
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
        $filteredData = $rawData
            ->groupBy(fn ($item) => $item->batch_range . '-' . $item->production_batch_id)
            ->map(fn ($items) => $items->sortByDesc('created_at')->first())
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
                    'created_at'     => $item->created_at,
                    'po_number'      => optional($item->productionBatch)->po_number,
                    'variant'        => optional($item->productionBatch)->variant,
                ];
            });

        return response()->json([
            'blending_after_adjust_mikro' => $filteredData,
            'filter_applied' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date'   => $endDate->format('Y-m-d'),
                'variant'    => $variant ?: 'all',
                'total_records' => $filteredData->count()
            ]
        ]);
    }
}
