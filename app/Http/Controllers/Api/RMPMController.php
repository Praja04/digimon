<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RMPM\IdentitasRM;
use Illuminate\Support\Facades\DB;

class RMPMController extends Controller
{
    private function resolveDateRange(Request $request): array
    {
        $startDate = $request->filled('start_date')
            ? now()->parse($request->start_date)->startOfDay()
            : now()->subMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? now()->parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        return [$startDate, $endDate];
    }

    public function analisaUmum(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $baseQuery = IdentitasRM::whereBetween('tanggal_kedatangan', [$startDate, $endDate]);

        $totalIdentitas = $baseQuery->count();

        $jenisGulaStat = (clone $baseQuery)
            ->select('jenis_gula')
            ->groupBy('jenis_gula')
            ->selectRaw('jenis_gula, COUNT(*) as jumlah')
            ->pluck('jumlah', 'jenis_gula');

        $topSuppliers = (clone $baseQuery)
            ->select('suplier_manufactur')
            ->groupBy('suplier_manufactur')
            ->selectRaw('suplier_manufactur, COUNT(*) as total')
            ->orderByDesc('total')
            ->take(5)
            ->pluck('total', 'suplier_manufactur');

        $samplingCompletion = (clone $baseQuery)
            ->with(['samplingMobil', 'samplingDokumen', 'samplingFisikKemasan', 'samplingFisikRaw'])
            ->get()
            ->map(function ($item) {
                return ($item->samplingMobil && $item->samplingDokumen && $item->samplingFisikKemasan && $item->samplingFisikRaw)
                    ? 'complete' : 'incomplete';
            })->countBy();

        $trendKedatangan = (clone $baseQuery)
            ->selectRaw('DATE(tanggal_kedatangan) as tanggal, COUNT(*) as jumlah')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get();

        return response()->json([
            'total_identitas' => $totalIdentitas,
            'jenis_gula' => $jenisGulaStat,
            'top_supplier' => $topSuppliers,
            'sampling_completion' => $samplingCompletion,
            'tanggal_kedatangan_trend' => $trendKedatangan
        ]);
    }

    private function buildFilteredQuery(string $table, Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $query = DB::table($table)
            ->join('identitas_rm_master', "$table.id_identitas", '=', 'identitas_rm_master.id')
            ->whereBetween('identitas_rm_master.tanggal_kedatangan', [$startDate, $endDate]);

        if ($request->filled('jenis_gula')) {
            $query->where('identitas_rm_master.jenis_gula', $request->jenis_gula);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('identitas_rm_master.tanggal_kedatangan', $request->tanggal);
        }

        return $query;
    }

    private function buildResponse(array $fields, $query, array $specialRules = [])
    {
        $total = $query->count();
        $result = [];

        foreach ($fields as $field) {
            if (isset($specialRules[$field]) && $specialRules[$field] === 'not_null') {
                $yes = (clone $query)->whereNotNull($field)->count();
            } elseif (isset($specialRules[$field]) && $specialRules[$field] === 'equals_1') {
                $yes = (clone $query)->where($field, 1)->count();
            } else {
                $yes = (clone $query)->where(DB::raw("LOWER(TRIM(SUBSTRING_INDEX($field, ',', 1)))"), 'yes')->count();
            }

            $result[$field] = [
                'yes' => $yes,
                'no' => $total - $yes
            ];
        }

        return $result;
    }

    public function kondisiMobil(Request $request)
    {
        $query = $this->buildFilteredQuery('sampling_kondisi_mobil', $request);
        $fields = ['bersih', 'kering', 'benda_asing', 'cacat', 'segel', 'berbau'];

        return response()->json(['mobil' => $this->buildResponse($fields, $query)]);
    }

    public function dokumen(Request $request)
    {
        $query = $this->buildFilteredQuery('sampling_dokumen', $request);
        $fields = ['coa', 'suratjalan_vendor', 'packing_list', 'logo_halal', 'kesesuaian_matriks_bahan'];

        $specialRules = [
            'logo_halal' => 'equals_1',
            'coa' => 'not_null',
            'suratjalan_vendor' => 'not_null',
            'packing_list' => 'not_null',
            'kesesuaian_matriks_bahan' => 'not_null',
        ];

        return response()->json(['dokumen' => $this->buildResponse($fields, $query, $specialRules)]);
    }

    public function fisikKemasan(Request $request)
    {
        $query = $this->buildFilteredQuery('sampling_fisik_kemasan', $request);
        $fields = ['rusak', 'kotor', 'berair', 'basah', 'campuran', 'sesuai_std'];

        return response()->json(['kemasan' => $this->buildResponse($fields, $query)]);
    }

    public function fisikRaw(Request $request)
    {
        $query = $this->buildFilteredQuery('sampling_fisik_raw', $request);
        $fields = ['leleh', 'warna_std', 'aroma_std', 'campuran', 'sesuai_std'];

        return response()->json(['raw_material' => $this->buildResponse($fields, $query)]);
    }

    public function rekapDisposisiTotal(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $all = DB::table('disposisi_rm')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('disposisi', DB::raw('COUNT(*) as total'))
            ->groupBy('disposisi')
            ->get();

        $pendingShort = DB::table('analisa_short_term_gkt')
            ->whereNull('id_disposisi')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $pendingGaram = DB::table('analisa_garam_gula')
            ->whereNull('id_disposisi')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $pendingLong = DB::table('analisa_long_term_gkt')
            ->whereNull('disposisi')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return response()->json([
            'rekap_disposisi' => $all,
            'total_pending_disposisi' => $pendingShort + $pendingGaram + $pendingLong
        ]);
    }

    public function analisaParameterKualitasPerJenisGula(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);
        $jenisGula = $request->jenis_gula;

        if (!$jenisGula) {
            return response()->json(['error' => 'jenis_gula diperlukan'], 400);
        }

        if (in_array($jenisGula, ['Gula', 'Garam'])) {
            $query = DB::table('analisa_garam_gula')
                ->join('disposisi_rm', 'analisa_garam_gula.id_disposisi', '=', 'disposisi_rm.id')
                ->join('identitas_rm_master', 'analisa_garam_gula.id_identitas', '=', 'identitas_rm_master.id')
                ->where('identitas_rm_master.jenis_gula', $jenisGula)
                ->whereBetween('analisa_garam_gula.created_at', [$startDate, $endDate])
                ->selectRaw("
                    disposisi_rm.disposisi,
                    AVG(`%ka`) as avg_ka,
                    AVG(`%nacl`) as avg_nacl,
                    AVG(gross_weight) as avg_weight,
                    COUNT(*) as jumlah
                ")
                ->groupBy('disposisi_rm.disposisi')
                ->get();

            return response()->json([
                'jenis_gula' => $jenisGula,
                'analisa' => 'garam_gula',
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'data' => $query
            ]);
        }

        if (in_array($jenisGula, ['Gula Tebu', 'Gula Kelapa'])) {
            $query = DB::table('analisa_short_term_gkt')
                ->join('disposisi_rm', 'analisa_short_term_gkt.id_disposisi', '=', 'disposisi_rm.id')
                ->join('identitas_rm_master', 'analisa_short_term_gkt.id_identitas', '=', 'identitas_rm_master.id')
                ->where('identitas_rm_master.jenis_gula', $jenisGula)
                ->whereBetween('analisa_short_term_gkt.created_at', [$startDate, $endDate])
                ->selectRaw("
                    disposisi_rm.disposisi,
                    AVG(brix) as avg_brix,
                    AVG(ph) as avg_ph,
                    COUNT(*) as jumlah
                ")
                ->groupBy('disposisi_rm.disposisi')
                ->get();

            return response()->json([
                'jenis_gula' => $jenisGula,
                'analisa' => 'short_term_gkt',
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'data' => $query
            ]);
        }

        return response()->json(['error' => 'jenis_gula tidak dikenal'], 422);
    }
}
