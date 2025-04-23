<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use App\Models\GgaGgasProcess;
use Illuminate\Http\Request;

class ProductionBatchController extends Controller
{
    public function index()
    {
        // Mengambil semua data master yang ada
        $productionBatches = ProductionBatch::all();

        // Menampilkan view 'productionbatch.index' dengan data
        return view('productionbatch.index', compact('productionBatches'));
    }

    // Menampilkan form untuk input data master
    public function create()
    {
        return view('productionbatch.create');
    }

    // Menyimpan data master (ProductionBatch)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'po_number' => 'required|string|max:255',
            'variant' => 'required|string|max:255',
            'production_date' => 'required|date',
            'batch_range' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        ProductionBatch::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.'
        ], 201);
    }

    // Menampilkan form untuk input GGA/GGAS
    public function createGgaGgas($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);
        return view('ggaggas.create', compact('productionBatch'));
    }

    // Menyimpan data GGA/GGAS
    public function storeGgaGgas(Request $request, $id)
    {
        $validatedData = $request->validate([

            'storage' => 'required|string|max:255',
            'batch' => 'required|string|max:255',
            'sample_type' => 'required|in:GGA,GGAS',
            'dissolver_number' => 'required|string|max:255',
        ]);

        $productionBatch = ProductionBatch::findOrFail($id);

        $ggaGgasProcess = new GgaGgasProcess($validatedData);
        $ggaGgasProcess->production_batch_id = $productionBatch->id;

        // Generate barcode for this process
        $ggaGgasProcess->barcode = 'GGA-' . uniqid();

        $ggaGgasProcess->save();

        return redirect()->route('ggaggas.show', $ggaGgasProcess->id)->with('success', 'Data GGA/GGAS berhasil disimpan');
    }

    // Menampilkan form untuk input hasil analisis
    public function show($id)
    {
        $ggaGgasProcess = GgaGgasProcess::findOrFail($id);
        return view('ggaggas.show', compact('ggaGgasProcess'));
    }

    // Menyimpan hasil analisis
    public function storeAnalysis(Request $request, $id)
    {
        $validatedData = $request->validate([
            'analysis_result' => 'required|array', // Hasil analisis yang akan dimasukkan dalam array
            'disposition' => 'required|in:release,release_conditional,resampling,handling',
            'comments' => 'nullable|string|max:255',
        ]);

        $ggaGgasProcess = GgaGgasProcess::findOrFail($id);
        $ggaGgasProcess->analysis_result = $validatedData['analysis_result'];
        $ggaGgasProcess->disposition = $validatedData['disposition'];
        $ggaGgasProcess->comments = $validatedData['comments'];

        $ggaGgasProcess->save();

        // Logic based on disposition (for example, resampling and return to previous steps)
        if ($validatedData['disposition'] == 'resampling') {
            // Logic to resample and return to previous step (perhaps resetting status or flags)
        }

        return redirect()->route('ggaggas.show', $ggaGgasProcess->id)->with('success', 'Hasil analisis berhasil disimpan');
    }


    public function selectGgaGgas()
    {
        $activeBatches = ProductionBatch::whereDoesntHave('ggaGgasProcesses')->get();
        return view('ggaggas.select', compact('activeBatches'));
    }
}
