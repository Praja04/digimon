<?php

namespace App\Http\Controllers\Foreman;

use App\Http\Controllers\Controller;
use App\Models\RMPM\AnalisaGaramGula;
use App\Models\RMPM\AnalisaLongTermGKT;
use App\Models\RMPM\AnalisaShortTermGKT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\RMPM\IdentitasRM;
use App\Models\RMPM\DisposisiRm;
use App\Models\RMPM\SamplingDokumen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RMPMControllerForeman extends Controller
{
    //
    public function dashboard()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }


        return view('foreman.rmpm.dashboard');
    }

    public function dataRM()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('foreman.rmpm.data_rm');
    }

    public function menu()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        $jenis_gula = [
            ['nama' => 'Gula Tebu', 'image' => 'gula-tebu.png', 'ribbon' => 'success'],
            ['nama' => 'Gula Kelapa', 'image' => 'gula_kelapa.png', 'ribbon' => 'warning'],
            ['nama' => 'Gula', 'image' => 'gula.png', 'ribbon' => 'dark'],
            ['nama' => 'Garam', 'image' => 'garam.png', 'ribbon' => 'info'],
        ];

        return view('foreman.rmpm.menu', compact('jenis_gula'));
    }

    // public function list_data($jenis)
    // {
    //     $identitasList = IdentitasRM::where('jenis_gula', $jenis)->with('disposisi')->get();

    //     // Buat ringkasan disposisi
    //     $dataSummary = [
    //         'Release' => $identitasList->filter(fn ($i) => $i->disposisi?->disposisi === 'Release')->count(),
    //         'Reject' => $identitasList->filter(fn ($i) => $i->disposisi?->disposisi === 'Reject')->count(),
    //         'Release Bersyarat' => $identitasList->filter(fn ($i) => $i->disposisi?->disposisi === 'Release Bersyarat')->count(),
    //         'Resampling' => $identitasList->filter(fn ($i) => $i->disposisi?->disposisi === 'Resampling')->count(),
    //     ];

    //     if (in_array($jenis, ['Gula Tebu', 'Gula Kelapa'])) {
    //         $data_detail2 = AnalisaLongTermGKT::whereIn('id_identitas', $identitasList->pluck('id'))->get()->keyBy('id_identitas');
    //         $data_detail3 = AnalisaShortTermGKT::whereIn('id_identitas', $identitasList->pluck('id'))->get()->keyBy('id_identitas');

    //         return view('foreman.rmpm.list_data', [
    //             'identitasList' => $identitasList,
    //             'jenis' => $jenis,
    //             'data_detail2' => $data_detail2,
    //             'data_detail3' => $data_detail3,
    //             'dataSummary' => $dataSummary, // tambahkan ini
    //         ]);
    //     }

    //     if (in_array($jenis, ['Gula', 'Garam'])) {
    //         $data_detail = AnalisaGaramGula::whereIn('id_identitas', $identitasList->pluck('id'))->get()->keyBy('id_identitas');

    //         return view('foreman.rmpm.list_data', [
    //             'identitasList' => $identitasList,
    //             'jenis' => $jenis,
    //             'data_detail' => $data_detail,
    //             'dataSummary' => $dataSummary, // tambahkan ini
    //         ]);
    //     }

    //     abort(404, 'Jenis tidak valid');
    // }

    public function list_data($jenis)
    {
        $identitasList = IdentitasRM::where('jenis_gula', $jenis)->orderby('tanggal_kedatangan', 'DESC')->get();
        $identitasIds = $identitasList->pluck('id');
        $dataSummary = [];

        if ($jenis === 'Gula Tebu') {
            $analisaLT = AnalisaLongTermGKT::whereIn('id_identitas', $identitasIds)->whereNotNull('disposisi')->get();
            $analisaST = AnalisaShortTermGKT::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $summaryST = $analisaST->groupBy(fn($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn($group) => $group->count());

            $summaryLT = $analisaLT->groupBy('disposisi')
                ->map(fn($group) => $group->count());

            $dataSummary = $summaryST->mergeRecursive($summaryLT)->map(function ($item) {
                return is_array($item) ? array_sum($item) : $item;
            });

            return view('foreman.rmpm.list_data', [
                'identitasList' => $identitasList,
                'jenis' => $jenis,
                'data_detail2' => $analisaLT->keyBy('id_identitas'),
                'data_detail3' => $analisaST->keyBy('id_identitas'),
                'dataSummary' => $dataSummary,
            ]);
        }

        if ($jenis === 'Gula Kelapa') {
            $analisaLT = AnalisaLongTermGKT::whereIn('id_identitas', $identitasIds)->whereNotNull('disposisi')->get();
            $analisaST = AnalisaShortTermGKT::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $summaryST = $analisaST->groupBy(fn($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn($group) => $group->count());

            $summaryLT = $analisaLT->groupBy('disposisi')
                ->map(fn($group) => $group->count());

            $dataSummary = $summaryST->mergeRecursive($summaryLT)->map(function ($item) {
                return is_array($item) ? array_sum($item) : $item;
            });

            return view('foreman.rmpm.list_data', [
                'identitasList' => $identitasList,
                'jenis' => $jenis,
                'data_detail2' => $analisaLT->keyBy('id_identitas'),
                'data_detail3' => $analisaST->keyBy('id_identitas'),
                'dataSummary' => $dataSummary,
            ]);
        }

        if ($jenis === 'Gula') {
            $analisaGG = AnalisaGaramGula::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $dataSummary = $analisaGG->groupBy(fn($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn($group) => $group->count());

            return view('foreman.rmpm.list_data', [
                'identitasList' => $identitasList,
                'jenis' => $jenis,
                'data_detail' => $analisaGG->keyBy('id_identitas'),
                'dataSummary' => $dataSummary,
            ]);
        }

        if ($jenis === 'Garam') {
            $analisaGG = AnalisaGaramGula::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $dataSummary = $analisaGG->groupBy(fn($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn($group) => $group->count());

            return view('foreman.rmpm.list_data', [
                'identitasList' => $identitasList,
                'jenis' => $jenis,
                'data_detail' => $analisaGG->keyBy('id_identitas'),
                'dataSummary' => $dataSummary,
            ]);
        }

        abort(404, 'Jenis tidak valid');
    }




    //4.detail identitas
    public function detail_data($id)
    {
        $identitas = IdentitasRM::findOrFail($id);
        // Ambil data analisa short term pertama berdasarkan id_identitas
        $data_id_disposisi = $identitas->analisaShortTerm()->first();

        // Cek apakah analisa short term ditemukan
        if ($data_id_disposisi && $data_id_disposisi->id_disposisi) {
            // Ambil data disposisi terkait berdasarkan id_disposisi
            $disposisi = DisposisiRm::find($data_id_disposisi->id_disposisi);
        } else {
            $disposisi = null; // Jika tidak ada analisa short term atau id_disposisi
        }

        $data_dokumen = $identitas->samplingDokumen;
        $data_mobil = $identitas->samplingMobil;
        $data_kemasan = $identitas->samplingFisikKemasan;
        $data_raw = $identitas->samplingFisikRaw;
        $analisa_garam_gula = $identitas->analisaGaramGula;
        $analisa_short_term = $identitas->analisaShortTerm;
        // $analisa_short_term = $identitas->analisaShortTerm()->with('disposisi')->get();
        // $analisa_garam_gula = $identitas->analisaGaramGula()->with('disposisi')->get();
        $analisa_long_term = $identitas->analisaLongTerm;

        return view('foreman.rmpm.detail_data', compact('identitas', 'disposisi', 'data_dokumen', 'data_mobil', 'data_kemasan', 'data_raw', 'analisa_garam_gula', 'analisa_short_term', 'analisa_long_term'));
    }


    public function updateDisposisiLong(Request $request, $id)
    {
        $request->validate([
            'disposisi' => 'required|in:release,reject',
        ]);

        $data = AnalisaLongTermGKT::findOrFail($id);
        $data->disposisi = $request->disposisi;
        $data->save();

        return response()->json([
            'message' => 'Disposisi berhasil diperbarui.',
            'data' => $data
        ]);
    }

    public function summary()
    {
        $today = Carbon::today();

        return response()->json([
            'total_hari_ini' => IdentitasRM::whereDate('tanggal_kedatangan', $today)->count(),
            'total_selesai_analisa' => IdentitasRM::whereHas('analisaShortTerm')->count(),
            'total_reject' => AnalisaShortTermGKT::whereHas('disposisi', function ($q) {
                $q->where('disposisi', 'Reject');
            })->count(),
            'total_release' => AnalisaShortTermGKT::whereHas('disposisi', function ($q) {
                $q->whereIn('disposisi', ['Release', 'Release Bersyarat']);
            })->count(),
        ]);
    }

    public function kedatangan(Request $request)
    {
        $query = IdentitasRM::with(['samplingMobil', 'samplingDokumen', 'konfirmasi'])
            ->orderBy('tanggal_kedatangan', 'desc');

        if ($request->filter === 'today') {
            $query->whereDate('tanggal_kedatangan', Carbon::today());
        } elseif ($request->filter === 'week') {
            $query->whereBetween('tanggal_kedatangan', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        }

        return response()->json($query->take(100)->get());
    }

    public function grafikKedatangan()
    {
        $data = IdentitasRM::selectRaw('DATE(tanggal_kedatangan) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(tanggal_kedatangan)')
            ->orderBy('tanggal')
            ->get();

        return response()->json($data);
    }

    public function disposisiPie()
    {
        $data = DB::table('disposisi_rm')
            ->select('disposisi', DB::raw('count(*) as total'))
            ->join('analisa_short_term_gkt', 'disposisi_rm.id', '=', 'analisa_short_term_gkt.id_disposisi')
            ->groupBy('disposisi')
            ->get();

        return response()->json($data);
    }

    public function kristalPositif()
    {
        $data = AnalisaLongTermGKT::with('identitasRmMaster')
            ->where('uji_kristal', 'like', '%positif%')
            ->latest()
            ->get();

        return response()->json($data);
    }

    public function progressSampling()
    {
        $data = IdentitasRM::with([
            'samplingMobil',
            'samplingDokumen',
            'samplingFisikKemasan',
            'samplingFisikRaw',
            'analisaShortTerm',
            'analisaLongTerm'
        ])->latest()->get();

        $result = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'no_spb' => $item->no_spb,
                'nama_bahan' => $item->nama_bahan,
                'status' => [
                    'mobil' => !is_null($item->samplingMobil),
                    'dokumen' => !is_null($item->samplingDokumen),
                    'kemasan' => !is_null($item->samplingFisikKemasan),
                    'raw' => !is_null($item->samplingFisikRaw),
                    'short_term' => $item->analisaShortTerm->isNotEmpty(),
                    'long_term' => $item->analisaLongTerm->isNotEmpty(),
                ]
            ];
        });

        return response()->json($result);
    }

    public function getTotalKedatangan(Request $request)
    {
        $query = IdentitasRM::query();

        if ($request->has('tanggal_awal')) {
            $query->whereDate('tanggal_kedatangan', '>=', $request->tanggal_awal);
        }

        if ($request->has('tanggal_akhir')) {
            $query->whereDate('tanggal_kedatangan', '<=', $request->tanggal_akhir);
        }

        if ($request->has('jenis_gula')) {
            $query->where('jenis_gula', $request->jenis_gula);
        }

        return response()->json([
            'total_kedatangan' => $query->count()
        ]);
    }

    public function getSamplingLengkap(Request $request)
    {
        $data = IdentitasRM::with(['samplingMobil', 'samplingDokumen', 'samplingFisikKemasan', 'samplingFisikRaw']);

        if ($request->has('tanggal_awal')) {
            $data->whereDate('tanggal_kedatangan', '>=', $request->tanggal_awal);
        }

        if ($request->has('tanggal_akhir')) {
            $data->whereDate('tanggal_kedatangan', '<=', $request->tanggal_akhir);
        }

        if ($request->has('jenis_gula')) {
            $data->where('jenis_gula', $request->jenis_gula);
        }

        $records = $data->get();

        $lengkap = $records->filter(function ($item) {
            return $item->samplingMobil && $item->samplingDokumen && $item->samplingFisikKemasan && $item->samplingFisikRaw;
        })->count();

        return response()->json([
            'sampling_lengkap' => $lengkap
        ]);
    }

    public function getSudahAnalisa(Request $request)
    {
        $data = IdentitasRM::with(['analisaShortTerm', 'analisaLongTerm', 'analisaGaramGula']);

        if ($request->has('tanggal_awal')) {
            $data->whereDate('tanggal_kedatangan', '>=', $request->tanggal_awal);
        }

        if ($request->has('tanggal_akhir')) {
            $data->whereDate('tanggal_kedatangan', '<=', $request->tanggal_akhir);
        }

        if ($request->has('jenis_gula')) {
            $data->where('jenis_gula', $request->jenis_gula);
        }

        $records = $data->get();

        $analisa = $records->filter(function ($item) {
            return $item->analisaShortTerm->isNotEmpty() ||
                $item->analisaLongTerm->isNotEmpty() ||
                $item->analisaGaramGula->isNotEmpty();
        })->count();

        return response()->json([
            'sudah_analisa' => $analisa
        ]);
    }

    public function getDisposisiCount(Request $request)
    {
        $data = IdentitasRM::with([
            'analisaShortTerm.disposisi',
            'analisaGaramGula.disposisi'
        ]);

        if ($request->has('tanggal_awal')) {
            $data->whereDate('tanggal_kedatangan', '>=', $request->tanggal_awal);
        }

        if ($request->has('tanggal_akhir')) {
            $data->whereDate('tanggal_kedatangan', '<=', $request->tanggal_akhir);
        }

        if ($request->has('jenis_gula')) {
            $data->where('jenis_gula', $request->jenis_gula);
        }

        $records = $data->get();

        $disposisiCount = [];

        foreach ($records as $item) {
            foreach ($item->analisaShortTerm as $a) {
                if ($a->disposisi) {
                    $label = $a->disposisi->disposisi;
                    $disposisiCount[$label] = ($disposisiCount[$label] ?? 0) + 1;
                }
            }

            foreach ($item->analisaGaramGula as $a) {
                if ($a->disposisi) {
                    $label = $a->disposisi->disposisi;
                    $disposisiCount[$label] = ($disposisiCount[$label] ?? 0) + 1;
                }
            }
        }

        return response()->json([
            'disposisi_summary' => $disposisiCount
        ]);
    }

    // Optional: data detail list
    public function getListIdentitas(Request $request)
    {
        $data = IdentitasRM::with([
            'samplingMobil',
            'samplingDokumen',
            'samplingFisikKemasan',
            'samplingFisikRaw',
            'analisaShortTerm.disposisi',
            'analisaGaramGula.disposisi',
            'analisaLongTerm'
        ]);

        if ($request->has('tanggal_awal')) {
            $data->whereDate('tanggal_kedatangan', '>=', $request->tanggal_awal);
        }

        if ($request->has('tanggal_akhir')) {
            $data->whereDate('tanggal_kedatangan', '<=', $request->tanggal_akhir);
        }

        if ($request->has('jenis_gula')) {
            $data->where('jenis_gula', $request->jenis_gula);
        }

        return response()->json($data->get());
    }
}
