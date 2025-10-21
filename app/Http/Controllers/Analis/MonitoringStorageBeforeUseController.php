<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonitoringStorageBeforeUseController extends Controller
{
    public function index()
    {
        $productionBatches = ProductionBatch::orderby('created_at', 'desc')->with('MonitoringStorageBeforeUse')->has('MonitoringStorageBeforeUse')->get();
        return view('analis.monitoring.monitoring_before_use.index', compact('productionBatches'));
    }

    public function show($id)
    {
        $productionBatch = ProductionBatch::with('MonitoringStorageBeforeUse')->findOrFail($id);
        return view('analis.monitoring.monitoring_before_use.show', compact('productionBatch'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brix' => 'required|numeric|min:0|max:100',
            'visco' => 'required|string|max:20',
            'aw' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }
    }
}
