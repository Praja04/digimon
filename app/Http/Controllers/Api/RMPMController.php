<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RMPM\IdentitasRM;
use App\Models\RMPM\SamplingKondisiMobil;
use App\Models\RMPM\SamplingDokumen;
use App\Models\RMPM\SamplingFisikKemasan;
use App\Models\RMPM\SamplingFisikRaw;
use Illuminate\Support\Facades\DB;

class RMPMController extends Controller
{
    //
    public function analisaUmum()
    {
        $totalIdentitas = IdentitasRM::count();

        $jenisGulaStat = IdentitasRM::select('jenis_gula')
            ->groupBy('jenis_gula')
            ->selectRaw('jenis_gula, COUNT(*) as jumlah')
            ->pluck('jumlah', 'jenis_gula');

        $topSuppliers = IdentitasRM::select('suplier_manufactur')
            ->groupBy('suplier_manufactur')
            ->selectRaw('suplier_manufactur, COUNT(*) as total')
            ->orderByDesc('total')
            ->take(5)
            ->pluck('total', 'suplier_manufactur');

        $samplingCompletion = IdentitasRM::with([
            'samplingMobil', 'samplingDokumen',
            'samplingFisikKemasan', 'samplingFisikRaw'
        ])->get()->map(function ($item) {
            return ($item->samplingMobil && $item->samplingDokumen && $item->samplingFisikKemasan && $item->samplingFisikRaw) ? 'complete' : 'incomplete';
        })->countBy();

        $trendKedatangan = IdentitasRM::selectRaw('DATE(tanggal_kedatangan) as tanggal, COUNT(*) as jumlah')
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

    public function kondisiMobil(Request $request)
    {
        $query = DB::table('sampling_kondisi_mobil')
            ->join('identitas_rm_master', 'sampling_kondisi_mobil.id_identitas', '=', 'identitas_rm_master.id');

        if ($request->filled('jenis_gula')) {
            $query->where('identitas_rm_master.jenis_gula', $request->jenis_gula);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('identitas_rm_master.tanggal_kedatangan', $request->tanggal);
        }

        $total = $query->count();

        $fields = ['bersih', 'kering', 'benda_asing', 'cacat', 'segel', 'berbau'];
        $result = [];

        foreach ($fields as $field) {
            $yes = $query->where($field, 1)->count();
            $result[$field] = [
                'yes' => $yes,
                'no' => $total - $yes
            ];
        }

        return response()->json(['mobil' => $result]);
    }

    public function dokumen(Request $request)
    {
        $query = DB::table('sampling_dokumen')
            ->join('identitas_rm_master', 'sampling_dokumen.id_identitas', '=', 'identitas_rm_master.id');

        if ($request->filled('jenis_gula')) {
            $query->where('identitas_rm_master.jenis_gula', $request->jenis_gula);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('identitas_rm_master.tanggal_kedatangan', $request->tanggal);
        }

        $total = $query->count();

        $fields = ['coa', 'suratjalan_vendor', 'packing_list', 'logo_halal', 'kesesuaian_matriks_bahan'];
        $result = [];

        foreach ($fields as $field) {
            if ($field === 'logo_halal') {
                $yes = $query->where('logo_halal', 1)->count();
            } else {
                $yes = $query->whereNotNull($field)->count();
            }
            $result[$field] = [
                'yes' => $yes,
                'no' => $total - $yes
            ];
        }

        return response()->json(['dokumen' => $result]);
    }

    public function fisikKemasan(Request $request)
    {
        $query = DB::table('sampling_fisik_kemasan')
            ->join('identitas_rm_master', 'sampling_fisik_kemasan.id_identitas', '=', 'identitas_rm_master.id');

        if ($request->filled('jenis_gula')) {
            $query->where('identitas_rm_master.jenis_gula', $request->jenis_gula);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('identitas_rm_master.tanggal_kedatangan', $request->tanggal);
        }

        $total = $query->count();
        $fields = ['rusak', 'kotor', 'berair', 'basah', 'campuran', 'sesuai_std'];
        $result = [];

        foreach ($fields as $field) {
            $yes = $query->where($field, 1)->count();
            $result[$field] = [
                'yes' => $yes,
                'no' => $total - $yes
            ];
        }

        return response()->json(['kemasan' => $result]);
    }

    public function fisikRaw(Request $request)
    {
        $query = DB::table('sampling_fisik_raw')
            ->join('identitas_rm_master', 'sampling_fisik_raw.id_identitas', '=', 'identitas_rm_master.id');

        if ($request->filled('jenis_gula')) {
            $query->where('identitas_rm_master.jenis_gula', $request->jenis_gula);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('identitas_rm_master.tanggal_kedatangan', $request->tanggal);
        }

        $total = $query->count();
        $fields = ['leleh', 'warna_std', 'aroma_std', 'campuran', 'sesuai_std'];
        $result = [];

        foreach ($fields as $field) {
            $yes = $query->where($field, 1)->count();
            $result[$field] = [
                'yes' => $yes,
                'no' => $total - $yes
            ];
        }

        return response()->json(['raw_material' => $result]);
    }

    public function rekapDisposisiTotal()
    {
        $all = DB::table('disposisi_rm')
        ->select('disposisi', DB::raw('COUNT(*) as total'))
        ->groupBy('disposisi')
        ->get();

        $pendingShort = DB::table('analisa_short_term_gkt')
        ->whereNull('id_disposisi')->count();

        $pendingGaram = DB::table('analisa_garam_gula')
        ->whereNull('id_disposisi')->count();

        $pendingLong = DB::table('analisa_long_term_gkt')
        ->whereNull('disposisi')->count();

        return response()->json([
            'rekap_disposisi' => $all,
            'total_pending_disposisi' => $pendingShort + $pendingGaram + $pendingLong
        ]);
    }

    public function analisaParameterKualitasPerJenisGula(Request $request)
    {
        $jenisGula = $request->jenis_gula;

        if (!$jenisGula) {
            return response()->json(['error' => 'jenis_gula diperlukan'], 400);
        }

        if (in_array($jenisGula, ['Gula', 'Garam'])) {
            // Analisa Garam/Gula
            $query = DB::table('analisa_garam_gula')
            ->join('disposisi_rm', 'analisa_garam_gula.id_disposisi', '=', 'disposisi_rm.id')
            ->join('identitas_rm_master', 'analisa_garam_gula.id_identitas', '=', 'identitas_rm_master.id')
            ->where('identitas_rm_master.jenis_gula', $jenisGula)
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
                'data' => $query
            ]);
        }

        if (in_array($jenisGula, ['Gula Tebu', 'Gula Kelapa'])) {
            // Analisa Short-Term
            $query = DB::table('analisa_short_term_gkt')
            ->join('disposisi_rm', 'analisa_short_term_gkt.id_disposisi', '=', 'disposisi_rm.id')
            ->join('identitas_rm_master', 'analisa_short_term_gkt.id_identitas', '=', 'identitas_rm_master.id')
            ->where('identitas_rm_master.jenis_gula', $jenisGula)
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
                'data' => $query
            ]);
        }

        return response()->json(['error' => 'jenis_gula tidak dikenal'], 422);
    }
}
