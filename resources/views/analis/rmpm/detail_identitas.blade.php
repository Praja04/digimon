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
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
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
                            <input type="text" class="form-control " placeholder="{{ $identitas->nama_bahan }}" disabled />
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-sm-auto ms-auto">
                        <div class="hstack gap-2">
                            <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()">
                                <i class="ri-delete-bin-2-line"></i>
                            </button>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalAnalisa"> <i class="ri-filter-3-line align-bottom me-1"></i> Analisa </button>
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#samplingModal">
                                <i class="ri-add-line align-bottom me-1"></i> Sampling
                            </button>
                            <button type="button" class="btn btn-warning" id="btnBukaModalKonfirmasi">
                                Konfirmasi
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<div class="row justify-content-center">
    <div class="col-xxl-12">
        <div class="card" id="demo">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-header border-bottom-dashed p-4">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="150">
                                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" class="card-logo card-logo-light" alt="logo light" height="150">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Asal Bahan</h6>
                                <h3 class="fs-14 mb-0">{{ $identitas->asal_bahan }}</h3>
                            </div>
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Nomor SPB</h6>
                                <h3 class="fs-14 mb-0">{{ $identitas->no_spb }}</h3>
                            </div>
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Suplier/Manufacture</h6>
                                <h3 class="fs-14 mb-0"> {{ $identitas->suplier_manufactur }}</h3>
                            </div>
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Nomor Mobil</h6>
                                <h3 class="fs-14 mb-0"> {{ $identitas->no_mobil }}</h3>
                            </div>
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Tanggal Kedatangan</h6>
                                <h3 class="fs-14 mb-0">{{ \Carbon\Carbon::parse($identitas->tanggal_kedatangan)->format('d M Y') }}</h3>
                            </div>
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Jam Kedatangan</h6>
                                <h3 class="fs-14 mb-0">{{ \Carbon\Carbon::parse($identitas->tanggal_kedatangan)->format('H:i') }}</h3>
                            </div>
                            <div class="col-lg-3 col-6">
                                <h6 class="text-muted text-uppercase fw-semibold">Jenis Gula</h6>
                                <h3 class="fs-14 mb-0">{{ $identitas->jenis_gula }}</h3>
                            </div>

                        </div>
                    </div>
                </div>

                @php
                function getBadgeClass($disposisi) {
                if ($disposisi === 'reject') {
                return 'badge-soft-danger';
                } elseif ($disposisi === 'release') {
                return 'badge-soft-success';
                } else {
                return 'badge-soft-warning';
                }
                }
                @endphp

                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Nama Bahan</p>
                                <h5 class="fs-14 mb-0">{{ $identitas->nama_bahan }}</h5>
                            </div>
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Jumlah Kedatangan</p>
                                <h5 class="fs-14 mb-0">{{ $identitas->jumlah_kedatangan }} kg</h5>
                            </div>
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Lot / Batch</p>
                                <h5 class="fs-14 mb-0">{{ $identitas->lot_batch }}</h5>
                            </div>
                            <div class="col-lg-3 col-6">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Disposisi</p>

                                @if ($identitas->jenis_gula == 'Gula Tebu' || $identitas->jenis_gula == 'Gula Kelapa' )
                                @php
                                $disposisi_short_term = $disposisi->disposisi ?? null;
                                $disposisi_long_term = $analisa_long_term->first()->disposisi ?? null;
                                $attachment_analisa = $analisa_long_term->first()->attachment ?? null;
                                @endphp

                                <h6><span class="badge {{ getBadgeClass($disposisi_short_term) }} badge-border">
                                        Short Term : {{ $disposisi_short_term ?? 'belum input' }}
                                    </span></h6> <br>

                                <h6><span class="badge {{ getBadgeClass($disposisi_long_term) }} badge-border">
                                        Long Term : {{ $disposisi_long_term ?? 'belum input' }}
                                    </span></h6>


                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                                    <i class="mdi mdi-panorama-outline text-primary" style="font-size: 36px;"></i> <!-- icon gambar -->
                                    <p>Attachment Kristal</p>
                                </a>

                                <!-- Modal untuk menampilkan gambar -->
                                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel">Lampiran Gambar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/uploads/attachment_analisa/' . $attachment_analisa) }}" alt="Lampiran Analisa" class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                @php
                                $disposisi_gula_garam = $disposisi->disposisi ?? null;
                                @endphp

                                <h6><span class="badge {{ getBadgeClass($disposisi_gula_garam) }} badge-border">
                                        {{ $disposisi_gula_garam ?? 'belum input' }}
                                    </span></h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="card-body" id="tasksList">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Sample Dokumen</h5>

                            </div>
                        </div>

                        <!--end card-body-->
                        <div class="card-body">
                            <div class="table-responsive table-card mb-4">
                                <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                                    <thead class="table-light text-muted">
                                        <tr>

                                            <th>COA</th>
                                            <th>Surat Jalan</th>
                                            <th>Packing List</th>
                                            <th>Identitas Kemasan</th>
                                            <th>Logo Halal</th>
                                            <th>Kesuaian Matriks Bahan Baku</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @if ($data_dokumen)
                                        <tr>
                                            <td>{{ $data_dokumen->coa ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->surat_jalan_vendor ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->packing_list ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->identitas_kemasan ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->logo_halal ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->kesesuaian_matriks_bahan ?? 'belum input'}}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!--end table-->
                            </div>

                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <hr>
                <div class="col-lg-12">
                    <div class="card-body" id="tasksList">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Sample Kondisi Mobil</h5>

                            </div>
                        </div>
                        <!--end card-body-->
                        <div class="card-body">
                            <div class="table-responsive table-card mb-4">
                                <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th>Bersih</th>
                                            <th>Kering</th>
                                            <th>Tidak ada Benda Asing</th>
                                            <th>Tidak Cacat</th>
                                            <th>Segel</th>
                                            <th>Berbau</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @if ($data_mobil)
                                        <tr>
                                            <td>{{ $data_mobil->bersih ?? 'belum input'}}</td>
                                            <td>{{ $data_mobil->kering ?? 'belum input'}}</td>
                                            <td>{{ $data_mobil->benda_asing ?? 'belum input'}}</td>
                                            <td>{{ $data_mobil->cacat ?? 'belum input'}}</td>
                                            <td>{{ $data_mobil->segel ?? 'belum input'}}</td>
                                            <td>{{ $data_mobil->berbau ?? 'belum input'}}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!--end table-->
                            </div>

                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <hr>
                <div class="col-lg-12">
                    <div class="card-body" id="tasksList">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Sample Fisik Kemasan</h5>

                            </div>
                        </div>
                        <!--end card-body-->
                        <div class="card-body">
                            <div class="table-responsive table-card mb-4">
                                <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                                    <thead class="table-light text-muted">
                                        <tr>

                                            <th>Kotor</th>
                                            <th>Rusak/Sobek</th>
                                            <th>Sesuai STD</th>
                                            <th>Lain-lain</th>
                                            <th>berair</th>
                                            <th>Basah</th>
                                            <th>Campuran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @if ($data_kemasan)
                                        <tr>
                                            <td>{{ $data_kemasan->kotor ?? 'belum input'}}</td>
                                            <td>{{ $data_kemasan->rusak ?? 'belum input'}}</td>
                                            <td>{{ $data_kemasan->sesuai_std ?? 'belum input'}}</td>
                                            <td>{{ $data_kemasan->{'lain-lain'} ?? 'belum input'}}</td>
                                            <td>{{ $data_kemasan->berair ?? 'belum input'}}</td>
                                            <td>{{ $data_kemasan->basah ?? 'belum input'}}</td>
                                            <td>{{ $data_kemasan->campuran ?? 'belum input'}}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!--end table-->
                            </div>

                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <hr>

                <div class="col-lg-12">
                    <div class="card-body" id="tasksList">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Sample Fisik Raw</h5>

                            </div>
                        </div>
                        <!--end card-body-->
                        <div class="card-body">
                            <div class="table-responsive table-card mb-4">
                                <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th>Leleh</th>
                                            <th>Warna Sesuai Std</th>
                                            <th>Campuran</th>
                                            <th>Aroma Sesuai Std</th>
                                            <th>Sesuai Std</th>

                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @if ($data_raw)
                                        <tr>
                                            <td>{{ $data_raw->leleh ?? 'belum input' }}</td>
                                            <td>{{ $data_raw->warna_std ?? 'belum input' }}</td>
                                            <td>{{ $data_raw->campuran ?? 'belum input' }}</td>
                                            <td>{{ $data_raw->aroma_std ?? 'belum input' }}</td>
                                            <td>{{ $data_raw->sesuai_std ?? 'belum input' }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!--end table-->
                            </div>

                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <hr>
                @if ($identitas->jenis_gula == 'Gula Tebu' || $identitas->jenis_gula == 'Gula Kelapa' )
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Short Term Analisa</h5>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead>
                                    <tr class="table-light text-muted">
                                        <th scope="col">No</th>
                                        <th scope="col">Brix</th>
                                        <th scope="col">pH</th>
                                        <th scope="col">Kotoran</th>
                                        <th scope="col">ka</th>
                                        <th scope="col">organo</th>
                                        <th scope="col">warna</th>
                                        <th scope="col">aroma</th>
                                        <!-- <th scope="col">disposisi</th> -->
                                    </tr>
                                </thead>
                                <tbody id="list-detail-short">
                                    @forelse ($analisa_short_term as $index => $short)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $short->brix ?? 'belum input'}}</td>
                                        <td>{{ $short->ph ?? 'belum input'}}</td>
                                        <td>{{ $short->kotoran ?? 'belum input'}}</td>
                                        <td>{{ $short->ka ?? 'belum input'}}</td>
                                        <td>{{ $short->organo ?? 'belum input'}}</td>
                                        <td>{{ $short->warna ?? 'belum input'}}</td>
                                        <td>{{ $short->aroma ?? 'belum input'}}</td>
                                        <!-- <td>{{ $short->disposisi ?? 'belum input'}}</td> -->
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data short term</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table><!--end table-->
                        </div>
                    </div>
                    <!--end card-body-->
                </div>

                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Long Term Analisa</h5>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead>
                                    <tr class="table-light text-muted">
                                        <th scope="col">No</th>
                                        <th scope="col">Uji Kristal</th>
                                        <th scope="col">disposisi</th>
                                    </tr>
                                </thead>
                                <tbody id="list-detail-long">
                                    @forelse ($analisa_long_term as $index => $long)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $long->uji_kristal ?? 'belum input'}}</td>
                                        <td>
                                            <span class="badge 
                                                    @if($long->disposisi === 'reject')
                                                        badge-soft-danger
                                                    @elseif($long->disposisi === 'release')
                                                        badge-soft-success
                                                    @else
                                                        badge-soft-warning
                                                    @endif
                                                ">
                                                {{ $long->disposisi ?? 'belum input' }}
                                            </span>
                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Belum ada data long term</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table><!--end table-->
                        </div>
                    </div>
                    <!--end card-body-->
                </div>
                @else
                <!-- kode jika identitas.jenis_gula false -->
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5>Hasil Analisa</h5>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead>
                                    <tr class="table-light text-muted">
                                        <th scope="col">No</th>
                                        <th scope="col">Fisik</th>
                                        <th scope="col">%ka</th>
                                        <th scope="col">Kotoran</th>
                                        <th scope="col">organo</th>
                                        <th scope="col">warna</th>
                                        <th scope="col">aroma</th>
                                        <th scope="col">%nacl</th>
                                        <th scope="col">grosweight</th>
                                        <th scope="col">disposisi</th>
                                    </tr>
                                </thead>
                                <tbody id="list-detail-analisa">
                                    @forelse ($analisa_garam_gula as $index => $garamgula)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $garamgula->fisik ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->{'%ka'} ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->kotoran ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->organo ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->warna ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->aroma ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->{'%nacl'} ?? 'belum input'}}</td>
                                        <td>{{ $garamgula->gross_weight?? 'belum input'}}</td>
                                        <td>{{ $garamgula->disposisi->disposisi ?? 'belum input'}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Belum ada data analisa</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table><!--end table-->
                        </div>
                    </div>
                    <!--end card-body-->
                </div>
                @endif



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
                    <button type="button" class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}" data-sampling="kondisi_mobil" data-title="Sampling Kondisi Mobil" {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}>
                        <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                    </button>

                    <!-- Sampling Dokumen -->
                    <a href="{{ route('sampling.dokumen', ['id' => $identitas->id]) }}" class="list-group-item list-group-item-action {{ $identitas->sampling_dokumen ? 'disabled' : '' }}">
                        <i class="ri-file-text-line me-2"></i> Sampling Dokumen
                    </a>

                    <!-- Sampling Fisik Kemasan -->
                    <a href="{{ route('sampling.fisik_kemasan', ['id' => $identitas->id]) }}" class="list-group-item list-group-item-action {{ $identitas->sampling_fisik_kemasan ? 'disabled' : '' }}">
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
                    <button type="button" class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }}" data-sampling="kondisi_mobil" data-title="Sampling Kondisi Mobil" {{ $identitas->sampling_kondisi_mobil ? 'disabled' : '' }} data-bs-dismiss="modal">
                        <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                    </button>
                    <!-- sampling  dokumen -->
                    <button type="button" class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_dokumen ? 'disabled' : '' }}" data-sampling="kondisi_dokumen" data-title="Sampling Dokumen" {{ $identitas->sampling_dokumen ? 'disabled' : '' }} data-bs-dismiss="modal">
                        <i class="ri-file-text-line me-2"></i> Sampling Dokumen
                    </button>
                    <!-- sampling  fisik kemasan -->
                    <button type="button" class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_kemasan ? 'disabled' : '' }}" data-sampling="kondisi_kemasan" data-title="Sampling Kemasan" {{ $identitas->sampling_kemasan ? 'disabled' : '' }} data-bs-dismiss="modal">
                        <i class="ri-inbox-line me-2"></i> Sampling Kemasan
                    </button>
                    @if($identitas->jenis_gula !== 'Garam')
                    <!-- sampling  fisik raw -->
                    <button type="button" class="list-group-item list-group-item-action sampling-option {{ $identitas->sampling_raw ? 'disabled' : '' }}" data-sampling="kondisi_raw" data-title="Sampling Raw" {{ $identitas->sampling_raw ? 'disabled' : '' }} data-bs-dismiss="modal">
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
                        <label><input type="radio" name="aroma_std" value="yes"> Iya</label>
                        <label><input type="radio" name="aroma_std" value="no"> Tidak</label>
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
                    <div id="analisa-jumlah" style="display: none;">
                        <h6 class="mb-3">Input Jumlah Data</h6>
                        <div>
                            <label>Jumlah Data</label>
                            <input class="form-control" type="number" name="jumlah_data" id="jumlah_data">
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

<!-- Modal -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Jam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="formKonfirmasi">
                    <div class="mb-3">
                        <label for="jamInput" class="form-label" id="labelJam">Jam Kedatangan</label>
                        <input type="datetime-local" class="form-control" id="jamInput">
                    </div>
                </div>
                <div id="statusMessage" class="text-success" style="display:none;">
                    Data berhasil disimpan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSimpanJam" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>

<script>
    $(document).ready(function() {
        var tipeInput = 'kedatangan';

        $('#btnBukaModalKonfirmasi').click(function() {
            $('#modalKonfirmasi').modal('show');
        });

        function Konfirmasi() {

            $.ajax({
                url: "{{ url('rmpm/konfirmasi/' . $identitas->id) }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Data:', response);
                    if (!response.jam_kedatangan_exists) {
                        tipeInput = 'kedatangan';
                        $('#modalKonfirmasi').modal('show');
                        $('#labelJam').text('Jam Kedatangan');
                    } else if (!response.jam_analisa_exists) {
                        tipeInput = 'analisa';
                        $('#modalKonfirmasi').modal('show');
                        $('#labelJam').text('Jam Analisa');
                    } else if (response.jam_analisa_exists) {
                        $('#labelJam').text('sudah dikonfirmasi semua');
                        $('#jamInput').hide();
                        $('#btnSimpanJam').hide();
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });


            $('#btnSimpanJam').click(function() {
                var jam = $('#jamInput').val();

                $.ajax({
                    url: "{{ url('rmpm/simpan/konfirmasi/' . $identitas->id) }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        tipe: tipeInput,
                        jam: jam,
                        _token: '{{ csrf_token() }}' // jangan lupa CSRF token
                    },
                    success: function(response) {
                        console.log('Response simpan:', response);
                        $('#statusMessage').show().text(response.message);

                        // Kalau baru input kedatangan, sekarang lanjut analisa
                        if (tipeInput === 'kedatangan') {
                            tipeInput = 'analisa';
                            $('#labelJam').text('Jam Analisa');
                            $('#jamInput').val(''); // kosongkan input
                        } else {
                            // Kalau sudah analisa, close modal
                            $('#modalKonfirmasi').modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        }

        // Update setiap detik
        Konfirmasi();
    });

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
</script>

<script>
    const formContent = $('#form-analisa-content');
    const jenisGula = $('#jenis_gula').val();
    let currentStep = 0;
    let steps = [];

    // Handle compression sebelum submit
    $(document).on('change', 'input[name="attachment"]', async function(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Cek ukuran file, kalau sudah kecil, tidak perlu compress
        if (file.size <= 2 * 1024 * 1024) return;

        const options = {
            maxSizeMB: 2,
            maxWidthOrHeight: 1920,
            useWebWorker: true
        };

        try {
            const compressedFile = await imageCompression(file, options);

            // Ganti file input dengan file hasil compress
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            event.target.files = dataTransfer.files;

            console.log('Gambar berhasil dikompres:', compressedFile.size / 1024, 'KB');
        } catch (error) {
            console.error('Error saat kompres gambar:', error);
        }
    });

    // const renderGroupInput = (label, name, jumlahData) => {
    //     let html = `<div class="form-step" data-step="${name}" style="display:none;">
    //  <h6 class="mb-3">${label}</h6>`;

    //     if (name === 'disposisi') {
    //         html += ` <label for="">Disposisi</label>
    //         <select class="form-control mb-2" name="${name}">
    //             <option value="">Pilih Disposisi</option>
    //             <option value="Release">Release</option>
    //             <option value="Reject">Reject</option>
    //         </select>`;
    //     } else if (name === 'uji_kristal') {
    //         html += ` <label for="">Uji Kristal</label>
    //         <select class="form-control mb-2" name="${name}">
    //             <option value="">Pilih Hasil Uji</option>
    //             <option value="Negatif">Negatif</option>
    //             <option value="Positif">Positif</option>
    //         </select>`;
    //     } else if (name === 'attachment') {
    //         html += ` <label for="">Lampirkan Gambar (opsional)</label>
    //           <input type="file" class="form-control mb-2" name="attachment" accept="image/*" capture="environment">`;
    //     } else {
    //         for (let i = 1; i <= jumlahData; i++) {
    //             html += `
    //             <label for="">${i}</label>
    //             <input type="text" class="form-control mb-2" name="${name}[]" placeholder="${label} ke-${i}">`;
    //         }
    //     }

    //     html += `</div>`;
    //     return html;
    // };

    const renderGroupInput = (label, name, jumlahData) => {
        let html = `<div class="form-step" data-step="${name}" style="display:none;">
        <h6 class="mb-3">${label}</h6>`;

        if (name === 'disposisi') {
            html += `<div class="disposisi-wrapper" style="display: none;">
            <label>Disposisi</label>
            <select class="form-control mb-2" name="disposisi">
                <option value="">Pilih Disposisi</option>
                <option value="Release">Release</option>
                <option value="Reject">Reject</option>
            </select>
        </div>
        <div class="disposisi-wrapper-negatif" style="display: none;">
            <label>Disposisi</label>
            <select class="form-control mb-2" name="disposisi">
                <option value="">Pilih Disposisi</option>
                <option value="Release">Release</option>
               
            </select>
        </div>
        <div class="disposisi">
            <label>Disposisi</label>
            <select class="form-control mb-2" name="disposisi">
                <option value="">Pilih Disposisi</option>
                <option value="Release">Release</option>
                <option value="Reject">Reject</option>
            </select>
        </div>
        `;
        } else if (name === 'uji_kristal') {
            html += `<label for="">Uji Kristal</label>
            <select class="form-control mb-2" name="uji_kristal" id="select-uji-kristal">
                <option value="">Pilih Hasil Uji</option>
                <option value="negatif">Negatif</option>
                <option value="positif">Positif</option>
            </select>`;
        } else if (name === 'attachment') {
            html += `<div class="attachment-wrapper">
            <label>Lampirkan Gambar</label>
            <input type="file" class="form-control mb-2" name="attachment" accept="image/*">
        </div>`;
        } else {
            for (let i = 1; i <= jumlahData; i++) {
                html += `<label for="">${i}</label>
                <input type="text" class="form-control mb-2 " name="${name}[]" placeholder="${label} ke-${i}">`;
            }
        }

        html += `</div>`;
        return html;
    };

    // Event listener dinamis: tampilkan / sembunyikan attachment & disposisi
    $(document).on('change', '#select-uji-kristal', function() {
        const value = $(this).val();
        if (value === 'negatif') {
            $('.attachment-wrapper').hide();
            $('.disposisi-wrapper-negatif').show();
            $('.disposisi').hide();
            $('select[name="disposisi"]').val('release');
        } else if (value === 'positif') {
            $('.disposisi').hide();
            $('.attachment-wrapper').show();
            $('.disposisi-wrapper').hide();
            $('.disposisi-wrapper-negatif').hide();
            $('select[name="disposisi"]').val('');
        } else {
            $('.attachment-wrapper').hide();
            $('.disposisi').show();
        }
    });

    // Ubah titik ke koma saat blur pada input angka
    $(document).on('blur', '.numeric-input', function() {
        let val = $(this).val();
        if (val.includes('.')) {
            val = val.replace('.', ',');
            $(this).val(val);
        }
    });

    $(document).on('input', '.numeric-input', function() {
        // Hanya angka, koma, dan satu koma saja
        let val = $(this).val().replace(/[^0-9,]/g, '');

        // Batasi hanya satu koma
        const parts = val.split(',');
        if (parts.length > 2) {
            val = parts[0] + ',' + parts[1]; // buang kelebihan koma
        }

        $(this).val(val);
    });



    function showStep(index) {
        $('.form-step').hide();
        $('.form-step').eq(index).show();
        $('#prevBtn').toggle(index > 0);
        $('#nextBtn').text(index === steps.length - 1 ? 'Simpan Analisa' : 'Berikutnya');
    }

    $('#modalAnalisa').on('show.bs.modal', function() {
        formContent.html('');
        steps = [];
        currentStep = 0;

        $('#analisa-type-select').hide();
        $('#analisa-jumlah').hide();
        $('#prevBtn').hide();
        $('#nextBtn').text('Berikutnya');

        if (jenisGula === 'Gula Kelapa' || jenisGula === 'Gula Tebu') {
            $('#analisa-type-select').show();
        } else if (jenisGula === 'Gula' || jenisGula === 'Garam') {
            steps = ['analisa-jumlah'];
            $('#analisa-jumlah').show(); // Sudah ada di HTML
        } else {
            formContent.html(`<div class="alert alert-warning">Jenis gula tidak dikenali: ${jenisGula}</div>`);
            $('#prevBtn').hide();
            $('#nextBtn').hide();
        }
    });

    $('#nextBtn').click(function() {
        if ($('#analisa-type-select').is(':visible')) {
            const analisaType = $('input[name="analisa_type"]:checked').val();
            if (!analisaType) {
                alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
                return;
            }

            $('#analisa-type-select').hide();
            $('#analisa-jumlah').show(); // Tampilkan input jumlah data
            return;
        }

        if ($('#analisa-jumlah').is(':visible')) {
            const jumlahData = parseInt($('#jumlah_data').val());
            if (isNaN(jumlahData) || jumlahData <= 0) {
                alert('Masukkan jumlah data yang valid!');
                return;
            }

            $('#analisa-jumlah').hide();

            const jenisGula = $('#jenis_gula').val(); // Pastikan ini tersedia

            // Jika jenis Gula/Garam, baru render field berdasarkan jumlah
            if (jenisGula === 'Gula' || jenisGula === 'Garam') {
                const fields = ['fisik', '%ka', 'kotoran', 'organo', 'warna', 'aroma', '%nacl', 'gross_weight', 'disposisi'];

                // Isi steps baru
                steps = fields;

                // Kosongkan form dan isi ulang
                formContent.html('');
                fields.forEach(field => {
                    formContent.append(renderGroupInput(field.toUpperCase(), field, jumlahData));
                });

                currentStep = 0;
                showStep(currentStep);
                return;
            }

            // Untuk jenis lain seperti short-term/long-term
            const analisaType = $('input[name="analisa_type"]:checked').val();

            let fields = [];
            if (analisaType === 'short-term') {
                fields = ['brix', 'ph', 'kotoran', 'ka', 'organo', 'warna', 'aroma'];
            } else if (analisaType === 'long-term') {
                fields = ['uji_kristal', 'attachment'];
            }

            fields.push('disposisi');

            steps = fields;
            formContent.html('');
            fields.forEach(field => {
                formContent.append(renderGroupInput(field.toUpperCase(), field, jumlahData));
            });

            currentStep = 0;
            showStep(currentStep);
            return;
        }


        // Step input biasa
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        } else {
            $('#formAnalisa').submit();
        }
    });


    $('#prevBtn').click(function() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // $('#formAnalisa').off('submit').on('submit', function(e) {
    //     e.preventDefault();

    //     const jenis = $('#jenis_gula').val();
    //     let url = '';

    //     // Cek apakah jenis gula adalah Gula Kelapa atau Gula Tebu
    //     if (jenis === 'Gula Kelapa' || jenis === 'Gula Tebu') {
    //         const analisaType = $('input[name="analisa_type"]:checked').val(); // Dapatkan pilihan jenis analisa

    //         if (!analisaType) {
    //             alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
    //             return;
    //         }

    //         if (analisaType === 'short-term') {
    //             url = '/analisa/short-term';
    //         } else if (analisaType === 'long-term') {
    //             url = '/analisa/long-term';
    //         } else {
    //             alert('Jenis analisa tidak dikenali!');
    //             return;
    //         }
    //     } else if (jenis === 'Gula' || jenis === 'Garam') {
    //         url = '/analisa/garam-gula';
    //     } else {
    //         alert('Jenis gula tidak dikenali!');
    //         return;
    //     }

    //     const token = $('meta[name="csrf-token"]').attr('content');

    //     let formData = new FormData(this);
    //     formData.append('_token', token);

    //     $.ajax({
    //         url: url,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function(response) {
    //             alert('Data berhasil disimpan!');
    //             $('#modalAnalisa').modal('hide');
    //             $('#formAnalisa')[0].reset();
    //         },
    //         error: function(xhr) {
    //             const errMsg = xhr.responseJSON?.message || 'Gagal menyimpan data!';
    //             alert(errMsg);
    //         }
    //     });
    // });

    $('#formAnalisa').off('submit').on('submit', function(e) {
        e.preventDefault();

        const jenis = $('#jenis_gula').val();
        let url = '';

        // Tentukan URL berdasarkan jenis dan tipe analisa
        if (jenis === 'Gula Kelapa' || jenis === 'Gula Tebu') {
            const analisaType = $('input[name="analisa_type"]:checked').val();

            if (!analisaType) {
                alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
                return;
            }

            if (analisaType === 'short-term') {
                url = '/analisa/short-term';
            } else if (analisaType === 'long-term') {
                url = '/analisa/long-term';
            } else {
                alert('Jenis analisa tidak dikenali!');
                return;
            }
        } else if (jenis === 'Gula' || jenis === 'Garam') {
            url = '/analisa/garam-gula';
        } else {
            alert('Jenis gula tidak dikenali!');
            return;
        }

        const token = $('meta[name="csrf-token"]').attr('content');
        let formData = new FormData(this);
        formData.append('_token', token);

        // === Penanganan khusus Long-Term ===
        const analisaType = $('input[name="analisa_type"]:checked').val();
        const kristalVal = $('select[name="uji_kristal"]').val();

        if (analisaType === 'long-term') {
            if (!kristalVal) {
                alert('Silakan pilih hasil uji kristal.');
                return;
            }

            if (kristalVal === 'negatif') {
                formData.set('disposisi', 'release');
                formData.delete('attachment'); // Tidak perlu file
            }

            if (kristalVal === 'positif') {
                const attachment = $('input[name="attachment"]')[0].files[0];
                if (!attachment) {
                    alert('Silakan lampirkan gambar karena hasil uji kristal positif.');
                    return;
                }
                // Disposisi tidak diisi user di tahap ini
                formData.delete('disposisi');
            }
        }

        // === AJAX Submit ===
        $.ajax({
            url: "{{url('/')}}" + url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#modalAnalisa').modal('hide');
                    $('#formAnalisa')[0].reset();
                });
            },
            error: function(xhr) {
                const errMsg = xhr.responseJSON?.message || 'Gagal menyimpan data!';
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errMsg,
                    confirmButtonText: 'Tutup'
                });
            }
        });
    });
</script>


@endsection