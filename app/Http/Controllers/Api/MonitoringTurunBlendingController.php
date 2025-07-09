<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonitoringTurunBlending;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class MonitoringTurunBlendingController extends Controller
{
    //
    public function analisaMonitoringTurun(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        // Ambil semua MonitoringTurunBlending
        $rawMonitorings = MonitoringTurunBlending::with([
            'monitoringData',
            'productionBatch:id,po_number,variant'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('revisi', 'desc') // revisi tertinggi dulu
        ->get();

        // Group berdasarkan batch_range dan nomor_blending
        $filteredMonitorings = $rawMonitorings
        ->groupBy(fn ($item) => $item->batch_range . '__' . $item->nomor_blending)
        ->map(fn ($group) => $group->first())
        ->values();

        // Flatten monitoringData
        $flattened = [];

        foreach ($filteredMonitorings as $monitoring) {
            foreach ($monitoring->monitoringData as $data) {
                $flattened[] = [
                    'nomor_blending' => $monitoring->nomor_blending,
                    'batch_range' => $monitoring->batch_range,
                    'shift' => $data->shift,
                    'brix' => $data->brix,
                    'nacl' => $data->nacl,
                    'bj' => $data->bj,
                    'visco' => $data->visco,
                    'aw' => $data->aw,
                    'buih' => $data->buih,
                    'organo' => $data->organo,
                    'ph' => $data->ph,
                    'revisi' => $monitoring->revisi,
                    'created_at' => $monitoring->created_at,
                    'po_number' => optional($monitoring->productionBatch)->po_number,
                    'variant' => optional($monitoring->productionBatch)->variant
                ];
            }
        }

        return response()->json([
                'monitoring_turun_blending' => $flattened
            ]);
    }
    public function analisaDisposisi(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $dispositions = MonitoringTurunBlending::whereNotNull('disposition')
        ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('disposition')
            ->map(fn ($group) => $group->count());

        return response()->json([
            'disposition_summary' => $dispositions,
        ]);
    }
}
