<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RMPM\IdentitasRM;
use App\Models\RMPM\AnalisaGaramGula;
use App\Models\RMPM\AnalisaLongTermGKT;
use App\Models\RMPM\AnalisaShortTermGKT;
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
        $data_dokumen = $identitas->samplingDokumen;
        $data_mobil = $identitas->samplingMobil;
        $data_kemasan = $identitas->samplingFisikKemasan;
        $data_raw = $identitas->samplingFisikRaw;
        return view('analis.rmpm.detail_identitas', compact('identitas', 'data_dokumen', 'data_mobil', 'data_kemasan', 'data_raw'));
    }


    ///////////////// CRUD ANALISA ////////////////////

    public function showGaramGula($id_identitas)
    {
        $analisaGaramGula = AnalisaGaramGula::where('id_identitas', $id_identitas)->first();
        if ($analisaGaramGula) {
            return response()->json($analisaGaramGula);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function showLongTerm($id_identitas)
    {
        $analisaLongTerm = AnalisaLongTermGKT::where('id_identitas', $id_identitas)->first();
        if ($analisaLongTerm) {
            return response()->json($analisaLongTerm);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function showShortTerm($id_identitas)
    {
        $analisaShortTerm = AnalisaShortTermGKT::where('id_identitas', $id_identitas)->first();
        if ($analisaShortTerm) {
            return response()->json($analisaShortTerm);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }


    public function storeGaramGula(Request $request)
    {
        $username = session('username');
        $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'fisik' => 'required|array',
            '%ka' => 'required|array',
            'kotoran' => 'required|array',
            'organo' => 'required|array',
            'warna' => 'required|array',
            'aroma' => 'required|array',
            '%nacl' => 'required|array',
            'gross_weight' => 'required|array',
            'disposisi' => 'required|array',
        ]);

        $jumlah = count($request->fisik);
        $data = [];

        for ($i = 0; $i < $jumlah; $i++) {
            $data[] = [
                'id_identitas'  => $request->id_identitas,
                'fisik'         => $request->fisik[$i] ?? null,
                '%ka'           => $request['%ka'][$i] ?? null,
                'kotoran'       => $request->kotoran[$i] ?? null,
                'organo'        => $request->organo[$i] ?? null,
                'warna'         => $request->warna[$i] ?? null,
                'aroma'         => $request->aroma[$i] ?? null,
                '%nacl'         => $request['%nacl'][$i] ?? null,
                'gross_weight'  => $request->gross_weight[$i] ?? null,
                'disposisi'     => $request->disposisi[$i] ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
                'created_by_user' => $username
            ];
        }

        AnalisaGaramGula::insert($data);

        return response()->json(['message' => 'Berhasil menyimpan semua data garam gula'], 201);
    }

    public function storeLongTerm(Request $request)
    {
        $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'uji_kristal' => 'required|array',
            
        ]);

        $username = session('username');
        $jumlah = count($request->uji_kristal);
        $data = [];

        for ($i = 0; $i < $jumlah; $i++) {
            $data[] = [
                'id_identitas' => $request->id_identitas,
                'uji_kristal' => $request->uji_kristal[$i] ?? null,
               
                'created_at' => now(),
                'updated_at' => now(),
                'created_by_user' => $username
            ];
        }

        AnalisaLongTermGKT::insert($data);

        return response()->json(['message' => 'Berhasil menyimpan semua data long term'], 201);
    }

    public function storeShortTerm(Request $request)
    {
        $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'brix' => 'required|array',
            'ph' => 'required|array',
            'kotoran' => 'required|array',
            'ka' => 'required|array',
            'organo' => 'required|array',
            'warna' => 'required|array',
            'aroma' => 'required|array',
            'disposisi' => 'required|array',
            
        ]);
        $username = session('username');
        $jumlah = count($request->brix); // diasumsikan semua array jumlahnya sama

        $data = [];

        for ($i = 0; $i < $jumlah; $i++) {
            $data[] = [
                'id_identitas'    => $request->id_identitas,
                'brix'            => $request->brix[$i] ?? null,
                'ph'              => $request->ph[$i] ?? null,
                'kotoran'         => $request->kotoran[$i] ?? null,
                'ka'              => $request->ka[$i] ?? null,
                'organo'          => $request->organo[$i] ?? null,
                'warna'           => $request->warna[$i] ?? null,
                'aroma'           => $request->aroma[$i] ?? null,
                'disposisi'       => $request->disposisi[$i] ?? null,
                
                'updated_at'      => now(),
                'created_by_user' => $username
            ];
        }

        AnalisaShortTermGKT::insert($data);

        return response()->json(['message' => 'Berhasil menyimpan semua data short term'], 201);
    }

    public function updateGaramGula(Request $request, $id)
    {
        $analisaGaramGula = AnalisaGaramGula::where('id', $id)->first();
        if ($analisaGaramGula) {
            $analisaGaramGula->update($request->all());
            return response()->json($analisaGaramGula);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function updateLongTerm(Request $request, $id)
    {
        $analisaLongTerm = AnalisaLongTermGKT::where('id', $id)->first();
        if ($analisaLongTerm) {
            $analisaLongTerm->update($request->all());
            return response()->json($analisaLongTerm);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function updateShortTerm(Request $request, $id)
    {
        $analisaShortTerm = AnalisaShortTermGKT::where('id', $id)->first();
        if ($analisaShortTerm) {
            $analisaShortTerm->update($request->all());
            return response()->json($analisaShortTerm);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function destroyGaramGula($id)
    {
        $analisaGaramGula = AnalisaGaramGula::where('id', $id)->first();
        if ($analisaGaramGula) {
            $analisaGaramGula->delete();
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function destroyLongTerm($id)
    {
        $analisaLongTerm = AnalisaLongTermGKT::where('id', $id)->first();
        if ($analisaLongTerm) {
            $analisaLongTerm->delete();
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }

    public function destroyShortTerm($id)
    {
        $analisaShortTerm = AnalisaShortTermGKT::where('id', $id)->first();
        if ($analisaShortTerm) {
            $analisaShortTerm->delete();
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => 'Data not found'], 404);
    }
}
