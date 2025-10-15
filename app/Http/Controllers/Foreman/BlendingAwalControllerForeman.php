<?php

namespace App\Http\Controllers\Foreman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\BlendingAwalModel;
use App\Models\ManageWarnaModel;
use App\Models\ProductionBatch;

class BlendingAwalControllerForeman extends Controller
{
    public function dashboard()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }


        return view('foreman.blending.dashboard_blending_awal');
    }
    //

    public function menu()
    {
        // Menampilkan view 'productionbatch.index' dengan data
        return view('foreman.blending.menu');
    }
    //


    public function Blending_data()
    {
        // Ambil semua PO yang memiliki data GGA
        $productionBatches = ProductionBatch::orderby('created_at', 'desc')->has('BlendingAwal')->with('BlendingAwal')->get();

        return view('foreman.blending.blending_awal', compact('productionBatches'));
    }


    public function Blending_detail($id)
    {
        // // Ambil PO dengan GGA yang belum lengkap
        // $productionBatch = ProductionBatch::with('BlendingAwal')->findOrFail($id);


        // return view('foreman.blending.blending_awal_detail', compact('productionBatch'));

        // Ambil data ProductionBatch beserta BlendingAwal dan BlendingBatchRelation
        $productionBatch = ProductionBatch::with([
            'BlendingAwal.additionalBatches' // nested eager loading
        ])->findOrFail($id);

        // Loop setiap blending_awal untuk cek apakah ada additional batch
        foreach ($productionBatch->BlendingAwal as $blending) {
            // Tambahkan properti custom 'additional_batch_info' ke setiap data
            $blending->additional_batch_info = $blending->additionalBatches->isNotEmpty()
                ? $blending->additionalBatches
                : null;

            $blending->po_number = $productionBatch->po_number;
        }
        // return json response untuk debugging
        //return response()->json($productionBatch->BlendingAwal);

        $manageWarna = ManageWarnaModel::orderBy('nama_warna', 'asc')->get();


        return view('foreman.blending.blending_awal_detail', compact('productionBatch', 'manageWarna'));
    }

    public function showInputFormBlendingAwal($id)
    {
        $blending = BlendingAwalModel::find($id);
        return view('foreman.blending.blending_awal_detail_id', compact('blending'));
    }
    public function updateAjaxBlending(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brix' => 'required|numeric|min:0|max:100',
            'nacl' => 'required|numeric|min:0|max:100',
            'bj' => 'required|string|max:20',
            'visco' => 'required|string|max:20',
            'aw' => 'required|string|max:20',
            'ph' => 'nullable',
            'buih' => 'nullable|string|max:20',
            'organo' => 'required|string|max:20',
            'endapan' => 'nullable|string|max:20',
            'warna' => 'required|string|max:20',
            'disposition' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
            'disposition_remarks' => 'nullable|string|max:255',
            'adjustment_qty_air' => 'nullable',
            'adjustment_qty_garam' => 'nullable',
            'adjustment_qty_gula' => 'nullable',
        ], [
            'brix.max' => 'Nilai brix melebihi batas input yaitu 100.',
            'nacl.max' => 'Nilai NaCl melebihi batas input yaitu 100.',
            'brix.min' => 'Nilai brix tidak boleh negatif.',
            'nacl.min' => 'Nilai NaCl tidak boleh negatif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = BlendingAwalModel::findOrFail($id);
        if ($blending->disposition) {
            return response()->json([
                'errors' => ['Data dengan ID ini sudah memiliki disposisi .']
            ], 422);
        }

        $disposition = $request->disposition;
        $remarks = $request->disposition_remarks ?? null;

        // Validasi remark wajib isi untuk kondisi tertentu
        if (in_array($disposition, ['Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling']) && empty($remarks)) {
            return response()->json([
                'errors' => ['Kolom keterangan (remarks) wajib diisi untuk disposition ini.']
            ], 422);
        }

        if ($disposition === 'Release') {
            $remarks = '-';
        }
        $username = session('username');
        $dataUpdate = [
            'brix' => $request->brix,
            'nacl' => $request->nacl,
            'bj' => $request->bj,
            'visco' => $request->visco,
            'aw' => $request->aw,
            'buih' => $request->buih,
            'organo' => $request->organo,
            'endapan' => $request->endapan,
            'ph' => $request->ph,
            'warna' => $request->warna,
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
            'created_by' => $username,
        ];

        // Jika adjustment
        if ($disposition === 'Adjustment') {
            $dataUpdate['adjustment_qty_air'] = $request->adjustment_qty_air;
            $dataUpdate['adjustment_qty_garam'] = $request->adjustment_qty_garam;
            $dataUpdate['adjustment_qty_gula'] = $request->adjustment_qty_gula;
            $dataUpdate['not_standar'] = true;
        }
        if ($disposition === 'Jalan Bareng') {

            $dataUpdate['not_standar'] = true;
        }
        if ($disposition === 'Leveling') {

            $dataUpdate['not_standar'] = true;
        }

        // Jika resampling
        if ($disposition === 'Resampling') {
            $dataUpdate['disposition_remarks'] = $disposition;
            $dataUpdate['not_standar'] = true;
        }

        $blending->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function editBlending(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brix_edit' => 'required|numeric|min:0|max:100',
            'nacl_edit' => 'required|numeric|min:0|max:100',
            'bj_edit' => 'required|string|max:20',
            'visco_edit' => 'required|string|max:20',
            'aw_edit' => 'required|string|max:20',
            'ph_edit' => 'required',
            'buih_edit' => 'required|string|max:20',
            'organo_edit' => 'required|string|max:20',
            'endapan_edit' => 'required|string|max:20',
            'warna_edit' => 'required|string|max:20',
            'disposition_edit' => 'required|in:Release,Release Bersyarat,Resampling,Reject,Repro,Adjustment,Jalan Bareng,Leveling',
            'disposition_remarks_edit' => 'nullable|string|max:255',
            'adjustment_qty_edit_air' => 'nullable',
            'adjustment_qty_edit_garam' => 'nullable',
            'adjustment_qty_edit_gula' => 'nullable',
        ], [
            'brix_edit.max' => 'Nilai brix melebihi batas input yaitu 100.',
            'nacl_edit.max' => 'Nilai NaCl melebihi batas input yaitu 100.',
            'brix_edit.min' => 'Nilai brix tidak boleh negatif.',
            'nacl_edit.min' => 'Nilai NaCl tidak boleh negatif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $blending = BlendingAwalModel::findOrFail($request->id_edit);
        $disposition = $request->disposition_edit;
        $remarks = $request->disposition_remarks_edit ?? null;

        // Validasi remark wajib isi untuk kondisi tertentu
        if (in_array($disposition, ['Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling']) && empty($remarks)) {
            return response()->json([
                'errors' => ['Kolom keterangan (remarks) wajib diisi untuk disposition ini.']
            ], 422);
        }

        if ($disposition === 'Release') {
            $remarks = '-';
        }
        $username = session('username');
        $dataUpdate = [
            'brix' => $request->brix_edit,
            'nacl' => $request->nacl_edit,
            'bj' => $request->bj_edit,
            'visco' => $request->visco_edit,
            'aw' => $request->aw_edit,
            'buih' => $request->buih_edit,
            'organo' => $request->organo_edit,
            'ph' => $request->ph_edit,
            'endapan' => $request->endapan_edit,
            'warna' => $request->warna_edit,
            'disposition' => $disposition,
            'disposition_remarks' => $remarks,
            'created_by' => $username,
        ];

        // Jika adjustment
        if ($disposition === 'Adjustment') {
            $dataUpdate['adjustment_qty_air'] = $request->adjustment_qty_edit_air;
            $dataUpdate['adjustment_qty_garam'] = $request->adjustment_qty_edit_garam;
            $dataUpdate['adjustment_qty_gula'] = $request->adjustment_qty_edit_gula;
            $dataUpdate['not_standar'] = true;
        }
        if ($disposition === 'Jalan Bareng') {

            $dataUpdate['not_standar'] = true;
        }
        if ($disposition === 'Leveling') {

            $dataUpdate['not_standar'] = true;
        }

        // Jika resampling
        if ($disposition === 'Resampling') {
            $dataUpdate['disposition_remarks'] = $disposition;
            $dataUpdate['not_standar'] = true;
        }

        $blending->update($dataUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }
}
