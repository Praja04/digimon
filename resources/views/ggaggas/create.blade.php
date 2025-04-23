@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Input Data GGA/GGAS</h2>

        <form action="{{ route('productionbatch.storeGgaGgas', $productionBatch->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="storage">Storage:</label>
                <input type="text" name="storage" class="form-control" id="storage" required>
            </div>

            <div class="form-group">
                <label for="batch">Batch:</label>
                <input type="text" name="batch" class="form-control" id="batch" required>
            </div>

            <div class="form-group">
                <label for="sample_type">Jenis Sample:</label>
                <select name="sample_type" class="form-control" id="sample_type" required>
                    <option value="GGA">GGA</option>
                    <option value="GGAS">GGAS</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dissolver_number">Nomor Dissolver:</label>
                <input type="text" name="dissolver_number" class="form-control" id="dissolver_number" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan GGA/GGAS</button>
        </form>
    </div>
@endsection
