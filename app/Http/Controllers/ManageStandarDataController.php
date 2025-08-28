<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManageWarnaModel;

class ManageStandarDataController extends Controller
{
    //
    public function tampilan()
    {
        return view('manage_data.data_warna');
    }
    // ✅ Get all warna
    public function index()
    {
        $data = ManageWarnaModel::all();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ✅ Create new warna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_warna' => 'required|string|max:255',
            'code_warna' => 'required|string|max:10'
        ]);

        $warna = ManageWarnaModel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Warna berhasil ditambahkan',
            'data' => $warna
        ], 201);
    }

    // ✅ Show specific warna
    public function show($id)
    {
        $warna = ManageWarnaModel::find($id);

        if (!$warna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $warna
        ]);
    }

    // ✅ Update warna
    public function update(Request $request, $id)
    {
        $warna = ManageWarnaModel::find($id);

        if (!$warna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_warna' => 'sometimes|required|string|max:255',
            'code_warna' => 'sometimes|required|string|max:10'
        ]);

        $warna->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Warna berhasil diupdate',
            'data' => $warna
        ]);
    }

    // ✅ Delete warna
    public function destroy($id)
    {
        $warna = ManageWarnaModel::find($id);

        if (!$warna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $warna->delete();

        return response()->json([
            'success' => true,
            'message' => 'Warna berhasil dihapus'
        ]);
    }

}
