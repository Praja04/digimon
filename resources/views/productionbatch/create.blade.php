@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Input Data Master Production Batch</h2>

        <form action="{{ route('productionbatch.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="po">PO:</label>
                <input type="text" name="po_number" class="form-control" id="po_number" required>
            </div>

            <div class="form-group">
                <label for="variant">Varian:</label>
                <input type="text" name="variant" class="form-control" id="variant" required>
            </div>

            <div class="form-group">
                <label for="production_date">Tanggal Produksi:</label>
                <input type="date" name="production_date" class="form-control" id="production_date" required>
            </div>

            <div class="form-group">
                <label for="batch_range">Rentang Batch Masak:</label>
                <input type="text" name="batch_range" class="form-control" id="batch_range" required>
            </div>

            <div class="form-group">
                <label for="storage">Storage:</label>
                <input type="text" name="storage" class="form-control" id="storage" required>
            </div>

            <div class="form-group">
                <label for="description">Keterangan:</label>
                <input type="text" name="description" class="form-control" id="description">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Data Master</button>
        </form>
    </div>
@endsection
