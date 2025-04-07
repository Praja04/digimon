<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RMPM\IdentitasRM;
use App\Models\RMPM\SamplingKondisiMobil;
use App\Models\RMPM\SamplingDokumen;
use App\Models\RMPM\SamplingFisikKemasan;
use App\Models\RMPM\SamplingFisikRaw;
use App\Models\RMPM\AnalisaShortTermGKT;
use App\Models\RMPM\AnalisaLongTermGKT;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class RMPMController extends Controller
{
    // 1. Menampilkan halaman pemilihan jenis gula
    public function pilihJenisGula()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        $jenis_gula = [
            ['nama' => 'Gula Tebu', 'image' => 'gula-tebu.png', 'ribbon' => 'success'],
            ['nama' => 'Gula Kelapa', 'image' => 'gula_kelapa.png', 'ribbon' => 'warning'],
            ['nama' => ' Gula aja', 'image' => 'gula.png', 'ribbon' => 'dark'],
            ['nama' => 'Garam', 'image' => 'garam.png', 'ribbon' => 'info'],
        ];

        return view('analis.rmpm.pilih_jenis_gula', compact('jenis_gula'));
    }

    // 2. Menampilkan form input identitas RM berdasarkan jenis gula
    public function formIdentitas($jenis)
    {
        // return view('rmpm.input_identitas', compact('jenis'));
        $identitasList = IdentitasRM::where('jenis_gula', $jenis)->get();
        return view('analis.rmpm.list_identitas', compact('identitasList', 'jenis'));
    }


    // 2. Menyimpan Identitas RM
    public function simpanIdentitas(Request $request)
    {
        $identitas = IdentitasRM::create($request->all());
        return redirect()->route('rmpm.listIdentitas', ['jenis' => $identitas->jenis_gula]);
    }

    // 3. Menampilkan daftar Identitas RM berdasarkan jenis gula
    public function listIdentitas($jenis)
    {
        $identitasList = IdentitasRM::where('jenis_gula', $jenis)->get();
        return view('analis.rmpm.list_identitas', compact('identitasList', 'jenis'));
    }

    //4.detail identitas
    public function detailIdentitas($id)
    {
        $identitas = IdentitasRM::findOrFail($id);
        return view('analis.rmpm.detail_identitas', compact('identitas'));
    }
}
