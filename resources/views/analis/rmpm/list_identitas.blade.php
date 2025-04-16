@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div
            class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Identitas RM - {{ $jenis }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Identitas</a>
                    </li>
                    <li class="breadcrumb-item active">
                    {{ $jenis }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="leadsList">
            <div class="card-header border-0">
                <div class="row g-4 align-items-center">
                    <div class="col-sm-3">
                        <div class="search-box">
                            <input
                                type="text"
                                class="form-control search"
                                placeholder="Search for..." />
                            <i
                                class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-sm-auto ms-auto">
                        <div class="hstack gap-2">
                            <button
                                class="btn btn-soft-danger"
                                id="remove-actions"
                                onClick="deleteMultiple()">
                                <i
                                    class="ri-delete-bin-2-line"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-info"
                                data-bs-toggle="offcanvas"
                                href="#offcanvasExample">
                                <i
                                    class="ri-filter-3-line align-bottom me-1"></i>
                                Fliters
                            </button>
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                                <i class="ri-add-line align-bottom me-1"></i> Input Identitas RM
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <div class="table-responsive table-card">
                        <table class="table align-middle text-center" id="customerTable">
                            <thead class="table-light">

                                <tr>
                                    <th>No</th>
                                    <th>No SPB</th>
                                    <th>Nama Bahan</th>
                                    <th>Suplier</th>
                                    <th class="sort" data-sort="tanggal_kedatangan">Tanggal Kedatangan</th>

                                    <th>Asal Bahan</th>
                                    <th>Jumlah Kedatangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody
                                class="list form-check-all">
                                @forelse ($identitasList as $index => $identitas)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="no_spb">{{ $identitas->no_spb }}</td>
                                    <td class="nama_bahan">{{ $identitas->nama_bahan }}</td>
                                    <td class="suplier">{{ $identitas->suplier_manufactur }}</td>
                                    <td class="tanggal_kedatangan">{{ $identitas->tanggal_kedatangan }}</td>
                                    <td class="asal_bahan">{{ $identitas->asal_bahan }}</td>
                                    <td class="jumlah_kedatangan">{{ $identitas->jumlah_kedatangan }}</td>

                                    <td>
                                        <a href="{{ route('rmpm.detailIdentitas', ['id' => $identitas->id]) }}" class="btn btn-sm btn-info">
                                            <i class="ri-eye-line"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Data tidak tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div
                            class="noresult"
                            style="display: none">
                            <div class="text-center">
                                <lord-icon
                                    src="https://cdn.lordicon.com/msoeawqm.json"
                                    trigger="loop"
                                    colors="primary:#121331,secondary:#08a88a"
                                    style="
                                                                width: 75px;
                                                                height: 75px;
                                                            "></lord-icon>
                                <h5 class="mt-2">
                                    Sorry! No Result
                                    Found
                                </h5>
                                <p
                                    class="text-muted mb-0">
                                    We've searched more
                                    than 150+ leads We
                                    did not find any
                                    leads for you
                                    search.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex justify-content-end mt-3">
                        <div
                            class="pagination-wrap hstack gap-2">
                            <a
                                class="page-item pagination-prev disabled"
                                href="#">
                                Previous
                            </a>
                            <ul
                                class="pagination listjs-pagination mb-0"></ul>
                            <a
                                class="page-item pagination-next"
                                href="#">
                                Next
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--end col-->
</div>

<!-- modal -->
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Input Identitas RM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('rmpm.simpanIdentitas') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_gula" value="{{ $jenis }}">

                    <div class="mb-3">
                        <label for="nama_bahan" class="form-label">Nama Bahan</label>
                        <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_kedatangan" class="form-label">Tanggal & Jam Kedatangan</label>
                        <input type="datetime-local" class="form-control" id="tanggal_kedatangan" name="tanggal_kedatangan" required>
                   
                    </div>

                    <div class="mb-3">
                        <label for="suplier_manufactur" class="form-label">Supplier / Manufactur</label>
                        <input type="text" class="form-control" id="suplier_manufactur" name="suplier_manufactur" required>
                    </div>

                    <div class="mb-3">
                        <label for="asal_bahan" class="form-label">Asal Bahan</label>
                        <input type="text" class="form-control" id="asal_bahan" name="asal_bahan" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_mobil" class="form-label">No Mobil</label>
                        <input type="text" class="form-control" id="no_mobil" name="no_mobil" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_spb" class="form-label">No SPB</label>
                        <input type="text" class="form-control" id="no_spb" name="no_spb" required>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_kedatangan" class="form-label">Jumlah Kedatangan (kg)</label>
                        <input type="number" class="form-control" id="jumlah_kedatangan" name="jumlah_kedatangan" placeholder="input dalam kilogram" required>
                    </div>

                    <div class="mb-3">
                        <label for="lot_batch" class="form-label">Lot / Batch</label>
                        <input type="text" class="form-control" id="lot_batch" name="lot_batch" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal end -->
@endsection