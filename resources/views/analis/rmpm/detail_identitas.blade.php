@extends('layouts.app')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #demo,
        #demo * {
            visibility: visible;
        }

        #demo {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@section('content')

</style>
<div class="row">
    <div class="col-12">
        <div
            class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Identitas RM - Detail</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="javascript: void(0);">Identitas</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $identitas->jenis_gula }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="leadsList">
            <div class="card-header border-0">
                <div class="row g-4 align-items-center">
                    <div class="col-sm-3">
                        <div class="search-box">
                            <input
                                type="text"
                                class="form-control "
                                placeholder="{{ $identitas->nama_bahan }}" disabled />
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
                                Analisa
                            </button>
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#samplingModal">
                                <i class="ri-add-line align-bottom me-1"></i> Sampling
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row justify-content-center">
    <div class="col-xxl-9">
        <div class="card" id="demo">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-header border-bottom-dashed p-4">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="80">
                                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" class="card-logo card-logo-light" alt="logo light" height="80">
                                <div class="mt-sm-5 mt-4">
                                    <h6 class="text-muted text-uppercase fw-semibold">Asal Bahan</h6>
                                    <p class="text-muted mb-1">{{ $identitas->asal_bahan }}</p>
                                    <p class="text-muted mb-0"><span>No SPB:</span> {{ $identitas->no_spb }}</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 mt-sm-0 mt-3">
                                <h6><span class="text-muted fw-normal">Supplier / Manufactur:</span> {{ $identitas->suplier_manufactur }}</h6>
                                <h6><span class="text-muted fw-normal">No Mobil:</span> {{ $identitas->no_mobil }}</h6>
                                <h6><span class="text-muted fw-normal">Tanggal Kedatangan:</span> {{ \Carbon\Carbon::parse($identitas->tanggal_kedatangan)->format('d M Y') }}</h6>
                                <h6><span class="text-muted fw-normal">Jam Kedatangan:</span> {{ \Carbon\Carbon::parse($identitas->tanggal_kedatangan)->format('H:i') }}</h6>

                                <h6 class="mb-0"><span class="text-muted fw-normal">Jenis Gula: </span> {{ $identitas->jenis_gula }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-4 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Nama Bahan</p>
                                <h5 class="fs-14 mb-0">{{ $identitas->nama_bahan }}</h5>
                            </div>
                            <div class="col-lg-4 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Jumlah Kedatangan</p>
                                <h5 class="fs-14 mb-0">{{ $identitas->jumlah_kedatangan }} kg</h5>
                            </div>
                            <div class="col-lg-4 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Lot / Batch</p>
                                <h5 class="fs-14 mb-0">{{ $identitas->lot_batch }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Sampling & Analisa -->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <!-- <a href="" class="btn btn-primary">
                                <i class="ri-flask-fill"></i> Sampling
                            </a>
                            <a href="" class="btn btn-success">
                                <i class="ri-bar-chart-fill"></i> Analisa
                            </a> -->
                            <a href="javascript:window.print()" class="btn btn-warning">
                                <i class="ri-printer-line align-bottom me-1"></i> Print
                            </a>
                            <a href="javascript:void(0);" class="btn btn-secondary">
                                <i class="ri-download-2-line align-bottom me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>

            </div> <!-- end row -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->



<!-- modal -->
<div class="modal fade" id="samplingsModal" tabindex="-1" aria-labelledby="samplingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="samplingModalLabel">Pilih Kategori Sampling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Silakan pilih kategori sampling yang ingin Anda isi.</p>
                <div class="list-group">
                    <!-- Sampling Kondisi Mobil -->
                    <!-- <a href="{{ route('sampling.kondisi_mobil', ['id' => $identitas->id]) }}"
                        class="list-group-item list-group-item-action {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}">
                        <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                    </a> -->
                    <button type="button"
                        class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}"
                        data-sampling="kondisi_mobil"
                        data-title="Sampling Kondisi Mobil"
                        {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}>
                        <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                    </button>

                    <!-- Sampling Dokumen -->
                    <a href="{{ route('sampling.dokumen', ['id' => $identitas->id]) }}"
                        class="list-group-item list-group-item-action {{ $identitas->sampling_dokumen ? 'disabled' : '' }}">
                        <i class="ri-file-text-line me-2"></i> Sampling Dokumen
                    </a>

                    <!-- Sampling Fisik Kemasan -->
                    <a href="{{ route('sampling.fisik_kemasan', ['id' => $identitas->id]) }}"
                        class="list-group-item list-group-item-action {{ $identitas->sampling_fisik_kemasan ? 'disabled' : '' }}">
                        <i class="ri-inbox-line me-2"></i> Sampling Fisik Kemasan
                    </a>

                    @if($identitas->jenis_gula !== 'garam')
                    <!-- Sampling Fisik Raw (Hanya untuk Gula Kelapa & Gula Tebu) -->
                    <a href="{{ route('sampling.fisik_raw', ['id' => $identitas->id]) }}"
                        class="list-group-item list-group-item-action {{ $identitas->sampling_fisik_raw ? 'disabled' : '' }}">
                        <i class="ri-flask-line me-2"></i> Sampling Fisik Raw
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilihan Sampling -->
<div class="modal fade" id="samplingModal" tabindex="-1" aria-labelledby="samplingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="samplingModalLabel">Pilih Kategori Sampling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Silakan pilih kategori sampling yang ingin Anda isi.</p>
                <div class="list-group">
                    <!-- sampling kondisi mobil -->
                    <button type="button"
                        class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}"
                        data-sampling="kondisi_mobil"
                        data-title="Sampling Kondisi Mobil"
                        {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}
                        data-bs-dismiss="modal">
                        <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                    </button>
                    <!-- sampling  dokumen -->
                    <button type="button"
                        class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_dokumen ? 'disabled' : '' }}"
                        data-sampling="kondisi_dokumen"
                        data-title="Sampling Dokumen"
                        {{ $identitas->sampling_dokumen ? 'disabled' : '' }}
                        data-bs-dismiss="modal">
                        <i class="ri-file-text-line me-2"></i> Sampling Dokumen
                    </button>
                    <!-- sampling  fisik kemasan -->
                    <button type="button"
                        class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_kemasan ? 'disabled' : '' }}"
                        data-sampling="kondisi_kemasan"
                        data-title="Sampling Kemasan"
                        {{ $identitas->sampling_kemasan ? 'disabled' : '' }}
                        data-bs-dismiss="modal">
                        <i class="ri-inbox-line me-2"></i> Sampling Kemasan
                    </button>
                    @if($identitas->jenis_gula !== 'garam')
                    <!-- sampling  fisik raw -->
                    <button type="button"
                        class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_raw ? 'disabled' : '' }}"
                        data-sampling="kondisi_raw"
                        data-title="Sampling Raw"
                        {{ $identitas->sampling_raw ? 'disabled' : '' }}
                        data-bs-dismiss="modal">
                        <i class="ri-flask-line me-2"></i> Sampling raw
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- endmodal -->
<!-- Modal Form Sampling -->
<!-- Modal Sampling Kondisi Mobil -->
<div class="modal fade" id="modalKondisiMobil" tabindex="-1" aria-labelledby="modalKondisiMobilLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sampling Kondisi Mobil - {{ $identitas->nama_bahan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-kondisi-mobil">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. leleh</label><br>
                        <label><input type="radio" name="bersih" value="yes"> Iya</label>
                        <label><input type="radio" name="bersih" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Kering</label><br>
                        <label><input type="radio" name="kering" value="yes"> Iya</label>
                        <label><input type="radio" name="kering" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Tidak Ada Benda Asing</label><br>
                        <label><input type="radio" name="benda_asing" value="yes"> Iya</label>
                        <label><input type="radio" name="benda_asing" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Tidak Cacat / Bolong</label><br>
                        <label><input type="radio" name="cacat" value="yes"> Iya</label>
                        <label><input type="radio" name="cacat" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">e. Segel</label><br>
                        <label><input type="radio" name="segel" value="yes"> Iya</label>
                        <label><input type="radio" name="segel" value="no"> Tidak</label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">f. Tidak Berbau</label><br>
                        <label><input type="radio" name="berbau" value="yes"> Iya</label>
                        <label><input type="radio" name="berbau" value="no"> Tidak</label>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Sampling</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal dokumen -->
<div class="modal fade" id="modalDokumen" tabindex="-1" aria-labelledby="modalDokumenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sampling Dokumen - {{ $identitas->nama_bahan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Dokumen -->
                <form id="form-dokumen">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. CoA</label><br>
                        <label><input type="radio" name="coa" value="yes"> Iya</label>
                        <label><input type="radio" name="coa" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Surat Jalan Vendor</label><br>
                        <label><input type="radio" name="surat_jalan_vendor" value="yes"> Iya</label>
                        <label><input type="radio" name="surat_jalan_vendor" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Packing List</label><br>
                        <label><input type="radio" name="packing_list" value="yes"> Iya</label>
                        <label><input type="radio" name="packing_list" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Identitas di Kemasan</label><br>
                        <label><input type="radio" name="identitas_kemasan" value="yes"> Iya</label>
                        <label><input type="radio" name="identitas_kemasan" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">e. Logo Halal di Kemasan</label><br>
                        <label><input type="radio" name="logo_halal" value="yes"> Iya</label>
                        <label><input type="radio" name="logo_halal" value="no"> Tidak</label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">f. Kesuaian dengan Matriks Bahan Baku</label><br>
                        <label><input type="radio" name="kesesuaian_matriks_bahan" value="yes"> Iya</label>
                        <label><input type="radio" name="kesesuaian_matriks_bahan" value="no"> Tidak</label>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtnDokumen">Simpan Sampling</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- kemasan -->
<div class="modal fade" id="modalKemasan" tabindex="-1" aria-labelledby="modalKemasanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sampling Kemasan - {{ $identitas->nama_bahan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Kemasan -->
                <form id="form-kemasan">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. Kotor</label><br>
                        <label><input type="radio" name="kotor" value="yes"> Iya</label>
                        <label><input type="radio" name="kotor" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Rusak Sobek</label><br>
                        <label><input type="radio" name="rusak" value="yes"> Iya</label>
                        <label><input type="radio" name="rusak" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Sesuai STD</label><br>
                        <label><input type="radio" name="sesuai_std" value="yes"> Iya</label>
                        <label><input type="radio" name="sesuai_std" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Lain-lain</label><br>
                        <label><input type="text" name="lain-lain"></label>
                    </div>


                    <button type="submit" class="btn btn-primary" id="submitBtnKemasan">Simpan Sampling</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- raw -->
<div class="modal fade" id="modalRaw" tabindex="-1" aria-labelledby="modalRawLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sampling Raw - {{ $identitas->nama_bahan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Raw -->
                <form id="form-raw">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. Leleh</label><br>
                        <label><input type="radio" name="leleh" value="yes"> Iya</label>
                        <label><input type="radio" name="leleh" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Warna tidak sesuai STD</label><br>
                        <label><input type="radio" name="warna_std" value="yes"> Iya</label>
                        <label><input type="radio" name="warna_std" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Campuran</label><br>
                        <label><input type="radio" name="campuran" value="yes"> Iya</label>
                        <label><input type="radio" name="campuran" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Aroma tidak STD</label><br>
                        <label><input type="radio" name="aroma" value="yes"> Iya</label>
                        <label><input type="radio" name="aroma" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">e. Sesuai STD</label><br>
                        <label><input type="radio" name="sesuai_std" value="yes"> Iya</label>
                        <label><input type="radio" name="sesuai_std" value="no"> Tidak</label>
                    </div>



                    <button type="submit" class="btn btn-primary" id="submitBtnRaw">Simpan Sampling</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const samplingButtons = document.querySelectorAll('.sampling-option');

        samplingButtons.forEach(button => {
            button.addEventListener('click', function() {
                const samplingType = this.getAttribute('data-sampling');

                setTimeout(() => {
                    let modalId = '';

                    switch (samplingType) {
                        case 'kondisi_mobil':
                            modalId = 'modalKondisiMobil';
                            break;
                        case 'kondisi_dokumen':
                            modalId = 'modalDokumen';
                            break;
                        case 'kondisi_kemasan':
                            modalId = 'modalKemasan';
                            break;
                        case 'kondisi_raw':
                            modalId = 'modalRaw';
                            break;
                    }

                    if (modalId) {
                        const modal = new bootstrap.Modal(document.getElementById(modalId));
                        modal.show();
                    }
                }, 500);
            });
        });
    });

    $('#form-kondisi-mobil').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "{{ route('sampling.kondisi_mobil.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                form.trigger('reset');
            },
            error: function(xhr) {
                const handlers = {
                    422: function() {
                        let errors = xhr.responseJSON.errors;
                        let list = '';
                        $.each(errors, function(key, value) {
                            list += `<li>${value[0]}</li>`;
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: `<ul style="text-align:left;">${list}</ul>`
                        });
                    },
                    409: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Sudah Ada',
                            text: xhr.responseJSON.message
                        });
                    },
                    500: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
                        });
                    },
                    default: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                };

                (handlers[xhr.status] || handlers.default)(); // panggil handler sesuai status
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Simpan Sampling');
            }
        });
    });


    $('#form-dokumen').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = $('#submitBtnDokumen');
        submitBtn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "{{ route('sampling.dokumen.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                form.trigger('reset');
            },
            error: function(xhr) {
                const handlers = {
                    422: function() {
                        let errors = xhr.responseJSON.errors;
                        let list = '';
                        $.each(errors, function(key, value) {
                            list += `<li>${value[0]}</li>`;
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: `<ul style="text-align:left;">${list}</ul>`
                        });
                    },
                    409: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Sudah Ada',
                            text: xhr.responseJSON.message
                        });
                    },
                    500: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
                        });
                    },
                    default: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                };

                (handlers[xhr.status] || handlers.default)(); // panggil handler sesuai status
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Simpan Sampling');
            }
        });
    });

    $('#form-kemasan').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = $('#submitBtnKemasan');
        submitBtn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "{{ route('sampling.fisik_kemasan.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                form.trigger('reset');
            },
            error: function(xhr) {
                const handlers = {
                    422: function() {
                        let errors = xhr.responseJSON.errors;
                        let list = '';
                        $.each(errors, function(key, value) {
                            list += `<li>${value[0]}</li>`;
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: `<ul style="text-align:left;">${list}</ul>`
                        });
                    },
                    409: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Sudah Ada',
                            text: xhr.responseJSON.message
                        });
                    },
                    500: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
                        });
                    },
                    default: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                };

                (handlers[xhr.status] || handlers.default)(); // panggil handler sesuai status
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Simpan Sampling');
            }
        });
    });

    $('#form-raw').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = $('#submitBtnRaw');
        submitBtn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "{{ route('sampling.fisik_raw.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                form.trigger('reset');
            },
            error: function(xhr) {
                const handlers = {
                    422: function() {
                        let errors = xhr.responseJSON.errors;
                        let list = '';
                        $.each(errors, function(key, value) {
                            list += `<li>${value[0]}</li>`;
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: `<ul style="text-align:left;">${list}</ul>`
                        });
                    },
                    409: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Sudah Ada',
                            text: xhr.responseJSON.message
                        });
                    },
                    500: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
                        });
                    },
                    default: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                };

                (handlers[xhr.status] || handlers.default)(); // panggil handler sesuai status
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Simpan Sampling');
            }
        });
    });
</script>


@endsection