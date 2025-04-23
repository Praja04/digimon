@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Data GGA/GGAS: {{ $ggaGgasProcess->barcode }}</h2>

        <div class="form-group">
            <label for="barcode">Barcode:</label>
            <input type="text" class="form-control" id="barcode" value="{{ $ggaGgasProcess->barcode }}" disabled>
        </div>

        <form action="{{ route('ggaggas.storeAnalysis', $ggaGgasProcess->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="analysis_result">Hasil Analisis:</label>
                <input type="text" name="analysis_result" class="form-control" id="analysis_result" required>
            </div>

            <div class="form-group">
                <label for="disposition">Disposisi:</label>
                <select name="disposition" class="form-control" id="disposition" required>
                    <option value="release">Release</option>
                    <option value="release_conditional">Release Conditional</option>
                    <option value="resampling">Resampling</option>
                    <option value="handling">Handling</option>
                </select>
            </div>

            <div class="form-group">
                <label for="comments">Komentar:</label>
                <textarea name="comments" class="form-control" id="comments"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Hasil Analisis</button>
        </form>
    </div>
@endsection
