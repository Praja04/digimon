<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\RMPM\SamplingKondisiMobil;
use App\Models\RMPM\SamplingDokumen;
use App\Models\RMPM\SamplingFisikKemasan;
use App\Models\RMPM\SamplingFisikRaw;
use App\Models\RMPM\IdentitasRM;
use Illuminate\Contracts\Session\Session;

class SamplingController extends Controller
{
    // 🟢 FORM & STORE SAMPLING KONDISI MOBIL
    public function showKondisiMobil($id)
    {
        $samplingExists = SamplingKondisiMobil::where('id_identitas', $id)->exists();

        if ($samplingExists) {
            return view('analis.sampling.kondisi_mobil', [
                'identitas' => IdentitasRM::findOrFail($id),
                'samplingExists' => true
            ]);
        }

        $identitas = IdentitasRM::findOrFail($id);

        return view('analis.sampling.kondisi_mobil', compact('identitas', 'samplingExists'));
    }

    public function storeKondisiMobil(Request $request)
    {
        $username = session('username');

        $validated = $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'bersih' => 'required',
            'kering' => 'required',
            'benda_asing' => 'required',
            'cacat' => 'required',
            'segel' => 'required',
            'berbau' => 'required',
        ]);
        // Cek apakah data dengan id_identitas sudah ada
        $existing = SamplingKondisiMobil::where('id_identitas', $validated['id_identitas'])->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sampling Kondisi Mobil sudah pernah disimpan untuk ID ini.'
            ], 409); // 409 = Conflict
        }
        // Tambahkan user ke data yang akan disimpan
        $validated['created_by_user'] = $username;

        SamplingKondisiMobil::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Sampling Kondisi Mobil berhasil disimpan.'
        ], 201);
    }


    // 🟢 FORM & STORE SAMPLING DOKUMEN
    public function showDokumen($id)
    {
        if (SamplingDokumen::where('id_identitas', $id)->exists()) {
            return redirect()->route('rmpm.detailIdentitas', $id)->with('error', 'Sampling Dokumen sudah diisi.');
        }

        return view('analis.sampling.dokumen', compact('id'));
    }

    public function storeDokumen(Request $request)
    {
        $username = session('username');
        $validated = $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'coa' => 'required',
            'suratjalan_vendor' => 'required',
            'packing_list' => 'required',
            'identitas_kemasan' => 'required',
            'logo_halal' => 'required',
            'kesesuaian_matriks_bahan' => 'required',

        ]);
        // Tambahkan user ke data yang akan disimpan
        $validated['created_by_user'] = $username;
        // Cek apakah data dengan id_identitas sudah ada
        $existing = SamplingDokumen::where('id_identitas', $validated['id_identitas'])->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sampling Dokumen sudah pernah disimpan untuk ID ini.'
            ], 409); // 409 = Conflict
        }
        SamplingDokumen::create($validated);

        return redirect()->route('rmpm.detailIdentitas', $request->id_identitas)->with('success', 'Sampling Dokumen berhasil disimpan.');
    }

    // 🟢 FORM & STORE SAMPLING FISIK KEMASAN
    public function showFisikKemasan($id)
    {
        if (SamplingFisikKemasan::where('id_identitas', $id)->exists()) {
            return redirect()->route('rmpm.detailIdentitas', $id)->with('error', 'Sampling Fisik Kemasan sudah diisi.');
        }

        return view('analis.sampling.fisik_kemasan', compact('id'));
    }

    public function storeFisikKemasan(Request $request)
    {
        $username = session('username');
        $validated = $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'kotor' => 'required',
            'rusak' => 'required',
            'sesuai_std' => 'required',
            'lain-lain' => 'nullable',
            'berair' => 'nullable',
            'basah' => 'nullable',
            'campuran' => 'nullable',
        ]);
        // Tambahkan user ke data yang akan disimpan
        $validated['created_by_user'] = $username;
        // Cek apakah data dengan id_identitas sudah ada
        $existing = SamplingFisikKemasan::where('id_identitas', $validated['id_identitas'])->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sampling Kemasan sudah pernah disimpan untuk ID ini.'
            ], 409); // 409 = Conflict
        }

        SamplingFisikKemasan::create($validated);

        return redirect()->route('rmpm.detailIdentitas', $request->id_identitas)->with('success', 'Sampling Fisik Kemasan berhasil disimpan.');
    }

    // 🟢 FORM & STORE SAMPLING FISIK RAW (Hanya untuk Gula, Tidak untuk Garam)
    public function showFisikRaw($id)
    {
        // Cek apakah jenis gula adalah garam (tidak bisa sampling fisik raw)
        $identitas = \App\Models\RMPM\IdentitasRM::findOrFail($id);
        if ($identitas->jenis_gula == 'garam') {
            return redirect()->route('rmpm.detailIdentitas', $id)->with('error', 'Garam tidak memiliki Sampling Fisik Raw.');
        }

        if (SamplingFisikRaw::where('id_identitas', $id)->exists()) {
            return redirect()->route('rmpm.detailIdentitas', $id)->with('error', 'Sampling Fisik Raw sudah diisi.');
        }

        return view('analis.sampling.fisik_raw', compact('id'));
    }

    public function storeFisikRaw(Request $request)
    {
        $username = session('username');
        $validated = $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'leleh' => 'required',
            'warna_std' => 'required',
            'campuran' => 'required',
            'aroma_std' => 'required',
            'sesuai_std' => 'required',
            
        ]);
         // Tambahkan user ke data yang akan disimpan
         $validated['created_by_user'] = $username;
         // Cek apakah data dengan id_identitas sudah ada
         $existing = SamplingFisikRaw::where('id_identitas', $validated['id_identitas'])->first();
 
         if ($existing) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Sampling Raw sudah pernah disimpan untuk ID ini.'
             ], 409); // 409 = Conflict
         }

        SamplingFisikRaw::create($validated);
        

        return redirect()->route('rmpm.detailIdentitas', $request->id_identitas)->with('success', 'Sampling Fisik Raw berhasil disimpan.');
    }
}
