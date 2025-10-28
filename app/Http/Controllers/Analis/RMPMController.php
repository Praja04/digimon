<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RMPM\IdentitasRM;
use App\Models\RMPM\AnalisaGaramGula;
use App\Models\RMPM\AnalisaLongTermGKT;
use App\Models\RMPM\AnalisaShortTermGKT;
use App\Models\RMPM\KonfirmasiKedatangan;
use App\Models\RMPM\DisposisiRm;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
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
            ['nama' => 'Gula', 'image' => 'gula.png', 'ribbon' => 'dark'],
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

    public function dataRM()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('analis.rmpm.data_rm');
    }

    // 2. Menyimpan Identitas RM
    public function simpanIdentitas(Request $request)
    {
        $data = $request->all();

        // Set nama_bahan berdasarkan jenis_gula
        $data['nama_bahan'] = $request->jenis_gula;

        $identitas = IdentitasRM::create($data);
        return redirect()->back();
    }

    // 3. Menampilkan daftar Identitas RM berdasarkan jenis gula
    public function listIdentitas($jenis)
    {
        $identitasList = IdentitasRM::with('konfirmasi')
            ->where('jenis_gula', $jenis)
            ->get();
        return view('analis.rmpm.list_identitas', compact('identitasList', 'jenis'));
    }

    //4.detail identitas
    public function detailIdentitas($id)
    {
        $identitas = IdentitasRM::with([
            'samplingDokumen',
            'samplingMobil',
            'samplingFisikKemasan',
            'samplingFisikRaw',
            'analisaGaramGula',
            'analisaShortTerm',
            'analisaLongTerm',
            'konfirmasi'
        ])->findOrFail($id);

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
        return view('analis.rmpm.detail_identitas', compact('identitas', 'disposisi', 'data_dokumen', 'data_mobil', 'data_kemasan', 'data_raw', 'analisa_garam_gula', 'analisa_short_term', 'analisa_long_term'));
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
            'disposisi' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $brix = array_map(fn($val) => str_replace(',', '.', $val), $request->brix);
        $ph = array_map(fn($val) => str_replace(',', '.', $val), $request->ph);

        $ka = array_map(fn($val) => str_replace(',', '.', $val), $request->ka);


        $username = session('username');

        DB::beginTransaction();

        try {
            // 1. Insert ke tabel disposisi_rm
            $disposisi = DisposisiRm::create([
                'disposisi' => $request->disposisi,
                'keterangan' => $request->keterangan,
            ]);

            // 2. Dapatkan id_disposisi dari hasil insert
            $id_disposisi = $disposisi->id;

            // 3. Siapkan data analisa short term
            $jumlah = count($request->brix);
            $dataAnalisa = [];

            for ($i = 0; $i < $jumlah; $i++) {
                $dataAnalisa[] = [
                    'id_identitas'    => $request->id_identitas,
                    'brix'            => $brix[$i] ?? null,
                    'ph'              => $ph[$i] ?? null,
                    'kotoran'         => $request->kotoran[$i] ?? null,
                    'ka'              => $ka[$i] ?? null,
                    'organo'          => $request->organo[$i] ?? null,
                    'warna'           => $request->warna[$i] ?? null,
                    'aroma'           => $request->aroma[$i] ?? null,
                    'id_disposisi'    => $id_disposisi,
                    'created_by_user' => $username,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }

            // 4. Insert semua data analisa
            AnalisaShortTermGKT::insert($dataAnalisa);

            DB::commit();

            return response()->json(['message' => 'Berhasil menyimpan data analisa dan disposisi'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function storeGaramGula(Request $request)
    {
        $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'fisik' => 'nullable|array',
            '%ka' => 'nullable|array',
            'kotoran' => 'nullable|array',
            'organo' => 'nullable|array',
            'warna' => 'nullable|array',
            'aroma' => 'nullable|array',
            '%nacl' => 'nullable|array',
            'gross_weight' => 'nullable|array',
        ]);

        $username = session('username');

        DB::beginTransaction();

        try {
            // 1. Insert ke tabel disposisi_rm
            $disposisi = DisposisiRm::create([
                'disposisi' => $request->disposisi,
                'keterangan' => $request->keterangan,
            ]);

            // 2. Dapatkan id_disposisi dari hasil insert
            $id_disposisi = $disposisi->id;

            // 3. Siapkan data analisa short term
            $jumlah = count($request->fisik);
            $dataAnalisa = [];

            for ($i = 0; $i < $jumlah; $i++) {
                $dataAnalisa[] = [
                    'id_identitas'  => $request->id_identitas,
                    'fisik'         => $request->fisik[$i] ?? null,
                    '%ka'           => $request['%ka'][$i] ?? null,
                    'kotoran'       => $request->kotoran[$i] ?? null,
                    'organo'        => $request->organo[$i] ?? null,
                    'warna'         => $request->warna[$i] ?? null,
                    'aroma'         => $request->aroma[$i] ?? null,
                    '%nacl'         => $request['%nacl'][$i] ?? null,
                    'gross_weight'  => $request->gross_weight[$i] ?? null,
                    'created_at'    => now(),
                    'id_disposisi'    => $id_disposisi,
                    'updated_at'    => now(),
                    'created_by_user' => $username
                ];
            }

            // 4. Insert semua data analisa
            AnalisaGaramGula::insert($dataAnalisa);

            DB::commit();

            return response()->json(['message' => 'Berhasil menyimpan data analisa dan disposisi'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
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

    ///KONFIRMASI KEDATANGAN

    public function getDataKedatangan($id)
    {
        // $identitas = IdentitasRM::with('konfirmasi')->findOrFail($id);

        // $result = [
        //     'id' => $identitas->id,
        //     'nama_bahan' => $identitas->nama_bahan,
        //     'jam_kedatangan_exists' => $identitas->konfirmasi && $identitas->konfirmasi->jam_kedatangan ? true : false,
        //     'jam_analisa_exists' => $identitas->konfirmasi && $identitas->konfirmasi->jam_analisa ? true : false,
        // ];

        // return response()->json($result);

        $identitas = IdentitasRM::with([
            'samplingMobil',
            'samplingDokumen',
            'samplingFisikKemasan',
            'samplingFisikRaw',
        ])->findOrFail($id);

        $jamAnalisaExist = KonfirmasiKedatangan::where('id_identitas', $id)->exists();

        return response()->json([
            'jam_analisa_exists' => $jamAnalisaExist,
            'sampling_complete' => $identitas->isSamplingComplete()
        ]);
    }


    public function updateJam(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jam' => 'required'
        ]);

        // Pastikan data IdentitasRM ada
        IdentitasRM::findOrFail($id);

        // Cek apakah sudah ada data konfirmasi
        $konfirmasi = KonfirmasiKedatangan::where('id_identitas', $id)->first();
        $username = session('username');
        if ($konfirmasi) {
            // Update data yang sudah ada
            $konfirmasi->update([
                //'jam_kedatangan' => $request->jam_kedatangan,
                'jam_analisa' => $request->jam,
                'dianalisa_by_user' => $username,
            ]);
        } else {
            // Buat data baru
            KonfirmasiKedatangan::create([
                'id_identitas' => $id,
                'jam_kedatangan' => $request->jam,
                'diterima_by_user' => $username,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jam kedatangan dan analisa berhasil disimpan.'
        ]);
    }


    ///test 
    public function storeLongTerm(Request $request)
    {
        // Validasi awal yang selalu dicek
        $request->validate([
            'id_identitas' => 'required|exists:identitas_rm_master,id',
            'uji_kristal'  => 'required|in:positif,negatif',
        ]);

        $ujiKristal = $request->uji_kristal;
        $attachmentName = null;
        $disposisi = null;

        if ($ujiKristal === 'negatif') {
            // Jika negatif: tidak perlu attachment, disposisi otomatis release
            $attachmentName = '-';
            $disposisi = 'release';
        } else {
            // Jika positif: attachment wajib
            $request->validate([
                'attachment' => 'required|image|mimes:jpg,jpeg,png,gif|max:5000',
            ]);

            // Simpan attachment
            if ($request->hasFile('attachment')) {
                $filename = 'attachment_' . time() . '_' . uniqid() . '.' . $request->attachment->extension();
                $request->file('attachment')->storeAs('uploads/attachment_analisa', $filename, 'public');
                $attachmentName = basename($filename);
            }
        }

        // Simpan ke database
        AnalisaLongTermGKT::create([
            'id_identitas'    => $request->id_identitas,
            'uji_kristal'     => $ujiKristal,
            'disposisi'       => $disposisi,
            'attachment'      => $attachmentName,
            'created_by_user' => session('username'),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json(['message' => 'Berhasil menyimpan data long term'], 201);
    }


    public function getDataRM()
    {
        $dataRM = IdentitasRM::with([
            'analisaGaramGula.disposisi',
            'analisaLongTerm',
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        $dataRM = $dataRM->transform(function ($item) {
            $status = 'progress';

            if (in_array($item->jenis_gula, ['Garam', 'Gula'])) {
                foreach ($item->analisaGaramGula as $analisa) {
                    if ($analisa->disposisi) {
                        $status = 'done';
                        break;
                    }
                }
            } elseif (in_array($item->jenis_gula, ['Gula Tebu', 'Gula Kelapa'])) {
                foreach ($item->analisaLongTerm as $analisa) {
                    if (!empty($analisa->disposisi)) {
                        $status = 'done';
                        break;
                    }
                }
            }

            return [
                'id' => $item->id,
                'nama_bahan' => $item->nama_bahan,
                'tanggal_kedatangan' => $item->tanggal_kedatangan,
                'suplier_manufactur' => $item->suplier_manufactur,
                'asal_bahan' => $item->asal_bahan,
                'no_spb' => $item->no_spb,
                'jumlah_kedatangan' => $item->jumlah_kedatangan,
                'status' => $status,
                'jenis_gula' => $item->jenis_gula,
            ];
        });

        $dataRM = $dataRM->sortBy(function ($item) {
            return $item['status'] === 'done' ? 1 : 0;
        })->values();

        return response()->json(['data' => $dataRM]);
    }
}
