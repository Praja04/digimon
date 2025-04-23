@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Input Sample GGA/GGAS</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('productionbatch.storeGgaGgas', ['id' => 0]) }}" method="POST" id="ggaGgasForm">
        @csrf

        <div class="mb-3">
            <label for="production_batch_id" class="form-label">Pilih Storage Aktif</label>
            <select name="production_batch_id" id="production_batch_id" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach($activeBatches as $batch)
                    <option value="{{ $batch->id }}">
                        Storage: {{ $batch->storage }} | Tgl Produksi: {{ $batch->production_date }} | Batch: {{ $batch->batch_range }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="batch" class="form-label">Batch</label>
            <input type="text" name="batch" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="sample_type" class="form-label">Jenis Sample</label>
            <select name="sample_type" class="form-select" required>
                <option value="GGA">GGA</option>
                <option value="GGAS">GGAS</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="dissolver_no" class="form-label">No Dissolver</label>
            <input type="text" name="dissolver_no" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit & Generate Barcode</button>
    </form>
</div>

<script>
    // Dynamic form submission with batch ID
    document.getElementById('ggaGgasForm').addEventListener('submit', function (e) {
        const batchId = document.getElementById('production_batch_id').value;
        this.action = this.action.replace('0', batchId);
    });
</script>
@endsection
