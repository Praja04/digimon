@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Master Production Batch</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>PO</th>
                <th>Varian</th>
                <th>Tanggal Produksi</th>
                <th>Rentang Batch</th>
                <th>Storage</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productionBatches as $productionBatch)
            <tr>
                <td>{{ $productionBatch->po_number }}</td>
                <td>{{ $productionBatch->variant }}</td>
                <td>{{ $productionBatch->production_date }}</td>
                <td>{{ $productionBatch->batch_range }}</td>
                <td>{{ $productionBatch->storage }}</td>
                <td>{{ $productionBatch->description }}</td>
                <td>
                    <a href="{{ route('productionbatch.createGgaGgas', $productionBatch->id) }}" class="btn btn-info">Input GGA/GGAS</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('productionbatch.create') }}" class="btn btn-success">Tambah Data Master</a>
</div>
@endsection