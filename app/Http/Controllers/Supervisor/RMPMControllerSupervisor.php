<?php

namespace App\Http\Controllers\Supervisor;

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

class RMPMControllerSupervisor extends Controller
{
    //
    public function dashboard()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }


        return view('supervisor.rmpm.dashboard');
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

        return view('supervisor.rmpm.menu', compact('jenis_gula'));
    }

  
    public function list_data($jenis)
    {
        $identitasList = IdentitasRM::where('jenis_gula', $jenis)->orderby('tanggal_kedatangan', 'DESC')->get();
        $identitasIds = $identitasList->pluck('id');
        $dataSummary = [];

        if ($jenis === 'Gula Tebu') {
            $analisaLT = AnalisaLongTermGKT::whereIn('id_identitas', $identitasIds)->whereNotNull('disposisi')->get();
            $analisaST = AnalisaShortTermGKT::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $summaryST = $analisaST->groupBy(fn ($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn ($group) => $group->count());

            $summaryLT = $analisaLT->groupBy('disposisi')
                ->map(fn ($group) => $group->count());

            $dataSummary = $summaryST->mergeRecursive($summaryLT)->map(function ($item) {
                return is_array($item) ? array_sum($item) : $item;
            });

            return view('supervisor.rmpm.list_data', [
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

            $summaryST = $analisaST->groupBy(fn ($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn ($group) => $group->count());

            $summaryLT = $analisaLT->groupBy('disposisi')
                ->map(fn ($group) => $group->count());

            $dataSummary = $summaryST->mergeRecursive($summaryLT)->map(function ($item) {
                return is_array($item) ? array_sum($item) : $item;
            });

            return view('supervisor.rmpm.list_data', [
                'identitasList' => $identitasList,
                'jenis' => $jenis,
                'data_detail2' => $analisaLT->keyBy('id_identitas'),
                'data_detail3' => $analisaST->keyBy('id_identitas'),
                'dataSummary' => $dataSummary,
            ]);
        }

        if ($jenis === 'Gula') {
            $analisaGG = AnalisaGaramGula::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $dataSummary = $analisaGG->groupBy(fn ($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn ($group) => $group->count());

            return view('supervisor.rmpm.list_data', [
                'identitasList' => $identitasList,
                'jenis' => $jenis,
                'data_detail' => $analisaGG->keyBy('id_identitas'),
                'dataSummary' => $dataSummary,
            ]);
        }

        if ($jenis === 'Garam') {
            $analisaGG = AnalisaGaramGula::with('disposisi')->whereIn('id_identitas', $identitasIds)->get();

            $dataSummary = $analisaGG->groupBy(fn ($item) => optional($item->disposisi)->disposisi ?? 'Undefined')
                ->map(fn ($group) => $group->count());

            return view('supervisor.rmpm.list_data', [
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
        return view('supervisor.rmpm.detail_data', compact('identitas', 'disposisi', 'data_dokumen', 'data_mobil', 'data_kemasan', 'data_raw', 'analisa_garam_gula', 'analisa_short_term', 'analisa_long_term'));
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

    

   
}
