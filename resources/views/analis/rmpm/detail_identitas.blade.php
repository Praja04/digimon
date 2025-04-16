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
                            <button type="button" class="btn btn-info"
                                data-bs-toggle="modal" data-bs-target="#modalAnalisa"> <i class="ri-filter-3-line align-bottom me-1"></i> Analisa </button>
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

                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6 col-6 ">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0 small">
                                    <h5>Sampling Dokumen</h5>
                                    <tr>
                                        <th class="table-active">CoA</th>
                                        <th>{{ $data_dokumen->coa ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Surat Jalan</th>
                                        <th>{{ $data_dokumen->surat_jalan_vendor ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Packing List</th>
                                        <th>{{ $data_dokumen->packing_list ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Identitas Kemasan</th>
                                        <th>{{ $data_dokumen->identitas_kemasan ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Logo Halal</th>
                                        <th>{{ $data_dokumen->logo_halal ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Kesesuaian Matriks Bahan Baku</th>
                                        <th>{{ $data_dokumen->kesesuaian_matriks_bahan ?? 'belum input'}}</th>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6 col-6">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0 small">
                                    <h5>Sampling Kondisi Mobil</h5>
                                    <tr>
                                        <th class="table-active">Bersih</th>
                                        <th>{{ $data_mobil->bersih ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Kering</th>
                                        <th>{{ $data_mobil->kering ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Tidak Ada Benda Asing</th>
                                        <th>{{ $data_mobil->benda_asing ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Tidak Cacat</th>
                                        <th>{{ $data_mobil->cacat ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Segel</th>
                                        <th>{{ $data_mobil->segel ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Berbau</th>
                                        <th>{{ $data_mobil->berbau ?? 'belum input'}}</th>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-lg-6 col-6">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0 small">
                                    <h5>Sampling Fisik Kemasan</h5>
                                    <tr>
                                        <th class="table-active">Kotor</th>
                                        <th>{{ $data_kemasan->kotor ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Rusak/Sobek</th>
                                        <th>{{ $data_kemasan->rusak ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Sesuai STD</th>
                                        <th>{{ $data_kemasan->sesuai_std ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Lain-lain</th>
                                        <th>{{ $data_kemasan->{'lain-lain'} ?? 'belum input'}}</th>

                                    </tr>
                                    <tr>
                                        <th class="table-active">Berair</th>
                                        <th>{{ $data_kemasan->berair ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Basah</th>
                                        <th>{{ $data_kemasan->basah ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Campuran</th>
                                        <th>{{ $data_kemasan->campuran ?? 'belum input'}}</th>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-lg-6 col-6">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0 small">
                                    <h5>Sampling Fisik Kemasan</h5>
                                    <tr>
                                        <th class="table-active">Leleh</th>
                                        <th>{{ $data_raw->leleh ?? 'belum input' }}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Warna sesuai STD</th>
                                        <th>{{ $data_raw->warna_std ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Campuran</th>
                                        <th>{{ $data_raw->campuran ?? 'belum input'}}</th>
                                    </tr>
                                    <tr>
                                        <th class="table-active">Aroma sesuai STD</th>
                                        <th>{{ $data_raw->aroma_std ?? 'belum input'}}</th>

                                    </tr>
                                    <tr>
                                        <th class="table-active">Sesuai STD</th>
                                        <th>{{ $data_raw->sesuai_std ?? 'belum input'}}</th>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Sampling & Analisa -->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">

                            <a href="javascript:window.print()" class="btn btn-warning">
                                <i class="ri-printer-line align-bottom me-1"></i> Print
                            </a>
                            <a class="btn btn-secondary" id="downloadBtn">
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
                    @if($identitas->jenis_gula !== 'Garam')
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

<!-- Modal Sampling Kondisi Mobil -->
<div class="modal fade" id="modalKondisiMobil" tabindex="-1" aria-labelledby="modalKondisiMobilLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sampling Kondisi Mobil - {{ $identitas->no_mobil }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-kondisi-mobil">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. Bersih</label><br>
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
                    @if($identitas->jenis_gula == 'Garam')
                    <!-- Input untuk garam -->
                    <div class="mb-3">
                        <label class="form-label">a. Kotor</label><br>
                        <label><input type="radio" name="kotor" value="yes"> Iya</label>
                        <label><input type="radio" name="kotor" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Berair</label><br>
                        <label><input type="radio" name="berair" value="yes"> Iya</label>
                        <label><input type="radio" name="berair" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Basah</label><br>
                        <label><input type="radio" name="basah" value="yes"> Iya</label>
                        <label><input type="radio" name="basah" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Campuran</label><br>
                        <label><input type="radio" name="campuran" value="yes"> Iya</label>
                        <label><input type="radio" name="campuran" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">e. Rusak Sobek</label><br>
                        <label><input type="radio" name="rusak" value="yes"> Iya</label>
                        <label><input type="radio" name="rusak" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">f. Sesuai STD</label><br>
                        <label><input type="radio" name="sesuai_std" value="yes"> Iya</label>
                        <label><input type="radio" name="sesuai_std" value="no"> Tidak</label>
                    </div>

                    @else
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
                    @endif



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


<!-- modal analisa -->
<div class="modal fade" id="modalAnalisa" tabindex="-1" aria-labelledby="modalAnalisaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formAnalisa">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnalisaLabel">Form Analisa</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body"> {{-- Form dinamis berdasarkan jenis_gula --}}
                    <input type="hidden" id="id_identitas" name="id_identitas" value="{{ $identitas->id }}">
                    <input type="hidden" id="jenis_gula" name="jenis_gula" value="{{ $identitas->jenis_gula }}">
                    <div id="form-analisa-content">
                        <!-- Form fields will di-render oleh jQuery -->
                    </div>
                    <div id="analisa-type-select" style="display: none;">
                        <h6 class="mb-3">Pilih Jenis Analisa</h6>
                        <div>
                            <label><input type="radio" name="analisa_type" value="short-term"> Short-Term</label>
                        </div>
                        <div>
                            <label><input type="radio" name="analisa_type" value="long-term"> Long-Term</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display: none;">Sebelumnya</button>
                    <button type="button" id="nextBtn" class="btn btn-primary">Berikutnya</button>
                </div>
                <!-- <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> <button type="submit" class="btn btn-primary">Simpan Analisa</button> </div> -->
            </form>
        </div>
    </div>
</div>

<<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
    <script>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            var element = document.getElementById('demo');

            var opt = {
                margin: 0.5,
                filename: 'data-kedatangan.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 1
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        });

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
                        showConfirmButton: true
                    }).then(() => {
                        location.reload(); // reload setelah klik "OK"
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
                        showConfirmButton: true
                    }).then(() => {
                        location.reload(); // reload setelah klik "OK"
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

        // $('#form-kemasan').on('submit', function(e) {
        //     e.preventDefault();

        //     let form = $(this);
        //     let submitBtn = $('#submitBtnKemasan');
        //     submitBtn.prop('disabled', true).text('Menyimpan...');

        //     $.ajax({
        //         url: "{{ route('sampling.fisik_kemasan.store') }}",
        //         method: "POST",
        //         data: form.serialize(),
        //         success: function(response) {
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Berhasil',
        //                 text: response.message,
        //                 timer: 2000,
        //                 showConfirmButton: false
        //             });
        //             form.trigger('reset');
        //         },
        //         error: function(xhr) {
        //             const handlers = {
        //                 422: function() {
        //                     let errors = xhr.responseJSON.errors;
        //                     let list = '';
        //                     $.each(errors, function(key, value) {
        //                         list += `<li>${value[0]}</li>`;
        //                     });
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Validasi Gagal',
        //                         html: `<ul style="text-align:left;">${list}</ul>`
        //                     });
        //                 },
        //                 409: function() {
        //                     Swal.fire({
        //                         icon: 'warning',
        //                         title: 'Data Sudah Ada',
        //                         text: xhr.responseJSON.message
        //                     });
        //                 },
        //                 500: function() {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Server Error',
        //                         text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
        //                     });
        //                 },
        //                 default: function() {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Gagal',
        //                         text: 'Terjadi kesalahan saat menyimpan data.'
        //                     });
        //                 }
        //             };

        //             (handlers[xhr.status] || handlers.default)(); // panggil handler sesuai status
        //         },
        //         complete: function() {
        //             submitBtn.prop('disabled', false).text('Simpan Sampling');
        //         }
        //     });
        // });

        $('#form-kemasan').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let submitBtn = $('#submitBtnKemasan');
            submitBtn.prop('disabled', true).text('Menyimpan...');

            // Ambil data dari form
            let formData = form.serializeArray();
            let jenisGula = "{{ $identitas->jenis_gula }}";

            // Konversi ke objek
            let data = {};
            formData.forEach(item => {
                data[item.name] = item.value;
            });

            // Tambahkan field yang tidak dikirim di form (isi default '-')
            if (jenisGula === 'Garam') {
                data['lain-lain'] = '-';
                if (!data['rusak']) data['rusak'] = 'no';
                if (!data['sesuai_std']) data['sesuai_std'] = 'no';
            } else {
                data['berair'] = '-';
                data['basah'] = '-';
                data['campuran'] = '-';
                if (!data['lain-lain'] || data['lain-lain'].trim() === '') {
                    data['lain-lain'] = '-';
                }
            }

            // Kirim lewat AJAX
            $.ajax({
                url: "{{ route('sampling.fisik_kemasan.store') }}",
                method: "POST",
                data: {
                    ...data,
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload(); // reload setelah klik "OK"
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

                    (handlers[xhr.status] || handlers.default)();
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
                        showConfirmButton: true
                    }).then(() => {
                        location.reload(); // reload setelah klik "OK"
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


        const formContent = $('#form-analisa-content');
        const jenisGula = $('#jenis_gula').val();
        let currentStep = 0;
        let steps = [];

        // const renderGroupInput = (label, name) => {
        //     let html = `<div class="form-step" data-step="${name}" style="display:none;">
        //             <h6 class="mb-3">${label}</h6>`;
        //     for (let i = 1; i <= 30; i++) {
        //         html += `<input type="text" class="form-control mb-2" name="${name}[]" placeholder="${label} ke-${i}">`;
        //     }
        //     html += `</div>`;
        //     return html;
        // };
        const renderGroupInput = (label, name) => {
            let html = `<div class="form-step" data-step="${name}" style="display:none;">
        <h6 class="mb-3">${label}</h6>`;

            for (let i = 1; i <= 30; i++) {
                if (name === 'disposisi') {
                    html += `
                    <label for="">${i}</label>
                <select class="form-control mb-2" name="${name}[]">
                    <option value="">Pilih Disposisi ke-${i}</option>
                    <option value="Release">Release</option>
                    <option value="Reject">Reject</option>
                </select>`;
                } else {
                    html += `
                     <label for="">${i}</label>
                    <input type="text" class="form-control mb-2" name="${name}[]" placeholder="${label} ke-${i}">`;
                }
            }

            html += `</div>`;
            return html;
        };


        function showStep(index) {
            $('.form-step').hide();
            $('.form-step').eq(index).show();
            $('#prevBtn').toggle(index > 0);
            $('#nextBtn').text(index === steps.length - 1 ? 'Simpan Analisa' : 'Berikutnya');
        }

        $('#modalAnalisa').on('show.bs.modal', function() {
            formContent.html('');
            steps = [];

            if (jenisGula === 'Gula Kelapa' || jenisGula === 'Gula Tebu') {
                // Tampilkan pilihan analisa (Short-Term / Long-Term)
                $('#analisa-type-select').show();
            } else if (jenisGula === 'Gula' || jenisGula === 'Garam') {
                const fields = ['fisik', '%ka', 'kotoran', 'organo', 'warna', 'aroma', '%nacl', 'gross_weight', 'disposisi'];
                steps = fields;

                fields.forEach(field => {
                    formContent.append(renderGroupInput(field.toUpperCase(), field));
                });
                $('#analisa-type-select').hide(); // Sembunyikan pilihan analisa jika jenis gula bukan Gula Kelapa atau Gula Tebu
            } else {
                formContent.html(`<div class="alert alert-warning">Jenis gula tidak dikenali: ${jenisGula}</div>`);
                $('#prevBtn').hide();
                $('#nextBtn').hide();
                return;
            }

            currentStep = 0;
            showStep(currentStep);
            $('#prevBtn').show();
            $('#nextBtn').show();
        });

        $('#nextBtn').click(function() {
            if ($('#analisa-type-select').is(':visible')) {
                const analisaType = $('input[name="analisa_type"]:checked').val();
                if (!analisaType) {
                    alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
                    return;
                }

                // Sembunyikan pilihan analisa dan tampilkan form sesuai pilihan
                $('#analisa-type-select').hide();

                if (analisaType === 'short-term') {
                    // Untuk short-term, tampilkan semua form
                    url = '/analisa/short-term';
                    const fields = ['brix', 'ph', 'kotoran', 'ka', 'organo', 'warna', 'aroma', 'disposisi'];
                    steps = fields;
                    fields.forEach(field => {
                        formContent.append(renderGroupInput(field.toUpperCase(), field));
                    });
                } else if (analisaType === 'long-term') {
                    // Untuk long-term, hanya tampilkan Uji Kristal dan Disposisi
                    url = '/analisa/long-term';
                    const fields = ['uji_kristal', 'disposisi'];
                    steps = fields;
                    fields.forEach(field => {
                        formContent.append(renderGroupInput(field.toUpperCase(), field));
                    });
                }
                currentStep = 0;
                showStep(currentStep);
            } else {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    $('#formAnalisa').submit(); // terakhir submit
                }
            }
        });

        $('#prevBtn').click(function() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        $('#formAnalisa').off('submit').on('submit', function(e) {
            e.preventDefault();

            const jenis = $('#jenis_gula').val();
            let url = '';

            // Cek apakah jenis gula adalah Gula Kelapa atau Gula Tebu
            if (jenis === 'Gula Kelapa' || jenis === 'Gula Tebu') {
                // Cek jenis analisa yang dipilih
                const analisaType = $('input[name="analisa_type"]:checked').val(); // Dapatkan pilihan jenis analisa

                if (!analisaType) {
                    alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
                    return;
                }

                // Tentukan URL berdasarkan pilihan jenis analisa
                if (analisaType === 'short-term') {
                    url = '/analisa/short-term'; // URL untuk Short-Term
                } else if (analisaType === 'long-term') {
                    url = '/analisa/long-term'; // URL untuk Long-Term
                } else {
                    alert('Jenis analisa tidak dikenali!');
                    return;
                }
            } else if (jenis === 'Gula' || jenis === 'Garam') {
                url = '/analisa/garam-gula'; // URL untuk Gula atau Garam
            } else {
                alert('Jenis gula tidak dikenali!');
                return;
            }

            // Ambil token dari meta tag
            const token = $('meta[name="csrf-token"]').attr('content');

            // Ambil data form, lalu tambahkan token di awal
            let formData = $(this).serialize();
            formData = `_token=${encodeURIComponent(token)}&` + formData;

            // Kirim data ke server dengan metode POST
            $.post(url, formData)
                .done(function(response) {
                    alert('Data berhasil disimpan!');
                    $('#modalAnalisa').modal('hide');
                    $('#formAnalisa')[0].reset();
                })
                .fail(function(xhr) {
                    const errMsg = xhr.responseJSON?.message || 'Gagal menyimpan data!';
                    alert(errMsg);
                });
        });
    </script>



    @endsection