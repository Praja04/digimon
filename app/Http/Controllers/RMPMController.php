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

        return view('rmpm.pilih_jenis_gula', compact('jenis_gula'));
    }

    // 2. Menampilkan form input identitas RM berdasarkan jenis gula
    public function formIdentitas($jenis)
    {
        return view('rmpm.input_identitas', compact('jenis'));
    }

    // 3. Menyimpan Identitas RM
    public function simpanIdentitas(Request $request)
    {
        $identitas = IdentitasRM::create($request->all());
        return redirect()->route('rmpm.listIdentitas', ['jenis' => $identitas->jenis_gula]);
    }

    // 4. Menampilkan daftar Identitas RM berdasarkan jenis gula
    public function listIdentitas($jenis)
    {
        $identitasList = IdentitasRM::where('jenis_gula', $jenis)->get();
        return view('rmpm.list_identitas', compact('identitasList', 'jenis'));
    }

    // 5. Menampilkan halaman input sampling dan analisa berdasarkan ID Identitas
    public function formSamplingAnalisa($id)
    {
        $identitas = IdentitasRM::findOrFail($id);
        return view('rmpm.sampling_analisa', compact('identitas'));
    }

    // 6. Menyimpan Sampling & Analisa
    public function simpanSamplingAnalisa(Request $request)
    {
        $id_identitas = $request->id_identitas;

        SamplingKondisiMobil::create($request->only(['id_identitas', 'bersih', 'kering', 'benda_asing', 'cacat', 'segel', 'berbau', 'created_by_user']));
        SamplingDokumen::create($request->only(['id_identitas', 'coa', 'suratjalan_vendor', 'packing_list', 'identitas_kemasan', 'logo_halal', 'kesesuaian_matriks_bahan', 'created_by_user']));
        SamplingFisikKemasan::create($request->only(['id_identitas', 'kotor', 'rusak', 'sesuai_std', 'lain-lain', 'berair', 'basah', 'campuran', 'created_by_user']));
        SamplingFisikRaw::create($request->only(['id_identitas', 'leleh', 'warna_std', 'campuran', 'aroma_std', 'sesuai_std', 'created_by_user']));

        if (in_array(IdentitasRM::find($id_identitas)->jenis_gula, ['Gula Kelapa', 'Gula Tebu'])) {
            AnalisaShortTermGKT::create($request->only(['id_identitas', 'brix', 'ph', 'kotoran', 'ka', 'organo', 'warna', 'aroma', 'created_by_user']));
            AnalisaLongTermGKT::create($request->only(['id_identitas', 'uji_kristal', 'created_by_user']));
        } else {
            AnalisaShortTermGKT::create($request->only(['id_identitas', 'brix', 'ph', 'kotoran', 'ka', 'organo', 'warna', 'aroma', 'created_by_user']));
        }

        return redirect()->route('rmpm.listIdentitas', ['jenis' => IdentitasRM::find($id_identitas)->jenis_gula])
            ->with('success', 'Data berhasil disimpan!');
    }

    //7.detail identitas
    public function detailIdentitas($id)
    {
        $identitas = IdentitasRM::findOrFail($id);
        return view('rmpm.detail_identitas', compact('identitas'));
    }
}
