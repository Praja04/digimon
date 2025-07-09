<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\BlendingAwalModel;

class BlendingAwalController extends Controller
{
    //
    public function analisaBlendingAwal(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $rawData = BlendingAwalModel::with(['productionBatch:id,po_number,variant'])
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
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        $filtered = $rawData
            ->groupBy('batch_range')
            ->map(function ($group) {
                return $group
                    ->filter(fn ($item) => $item->revisi !== null)
                    ->sortByDesc('revisi')
                    ->first() ?? $group->first();
            })
            ->values()
            ->map(function ($item) {
                return [
                    'batch_range' => $item->batch_range,
                    'nomor_blending' => $item->nomor_blending,
                    'brix' => $item->brix,
                    'nacl' => $item->nacl,
                    'bj' => $item->bj,
                    'visco' => $item->visco,
                    'aw' => $item->aw,
                    'buih' => $item->buih,
                    'organo' => $item->organo,
                    'ph' => $item->ph,
                    'created_at' => $item->created_at,
                    'revisi' => $item->revisi,
                    'po_number' => optional($item->productionBatch)->po_number,
                    'variant' => optional($item->productionBatch)->variant
                ];
            });

        return response()->json(['blending_awal' => $filtered]);
    }

    public function analisaDisposisi(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $dispositions = BlendingAwalModel::whereNotNull('disposition')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('disposition')
            ->map(fn ($group) => $group->count());

        return response()->json([
            'disposition_summary' => $dispositions,
        ]);
    }



}
