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
    <div class="col-lg-3">
        <a class="btn btn-danger" href="{{url('analis/rmpm/list/rm')}}">Back</a>
    </div>
    <br>
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
                    @if(is_null($identitas->samplingMobil))
                    <button type="button" class="list-group-item list-group-item-action sampling-option" data-sampling="kondisi_mobil" data-title="Sampling Kondisi Mobil" data-bs-dismiss="modal">
                        <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                    </button>
                    @endif

                    @if(is_null($identitas->samplingDokumen))
                    <button type="button" class="list-group-item list-group-item-action sampling-option" data-sampling="kondisi_dokumen" data-title="Sampling Dokumen" data-bs-dismiss="modal">
                        <i class="ri-file-text-line me-2"></i> Sampling Dokumen
                    </button>
                    @endif

                    @if(is_null($identitas->samplingFisikKemasan))
                    <button type="button" class="list-group-item list-group-item-action sampling-option" data-sampling="kondisi_kemasan" data-title="Sampling Kemasan" data-bs-dismiss="modal">
                        <i class="ri-inbox-line me-2"></i> Sampling Kemasan
                    </button>
                    @endif

                    @if($identitas->jenis_gula !== 'Garam' && is_null($identitas->samplingFisikRaw))
                    <button type="button" class="list-group-item list-group-item-action sampling-option" data-sampling="kondisi_raw" data-title="Sampling Raw" data-bs-dismiss="modal">
                        <i class="ri-flask-line me-2"></i> Sampling Raw
                    </button>
                    @endif
                    @if(
                    !is_null($identitas->sampling_mobil) &&
                    !is_null($identitas->sampling_dokumen) &&
                    !is_null($identitas->sampling_fisik_kemasan) &&
                    ($identitas->jenis_gula === 'Garam' || !is_null($identitas->sampling_fisik_raw))
                    )
                    <div class="alert alert-success text-center mt-3">
                        Semua kategori sampling telah diisi.
                    </div>
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
                <form class="form-sampling" id="form-kondisi-mobil">
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
                <form class="form-sampling" id="form-dokumen">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. CoA</label><br>
                        <label><input type="radio" name="coa" value="yes"> Iya</label>
                        <label><input type="radio" name="coa" value="no"> Tidak</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Surat Jalan Vendor</label><br>
                        <label><input type="radio" name="suratjalan_vendor" value="yes"> Iya</label>
                        <label><input type="radio" name="suratjalan_vendor" value="no"> Tidak</label>
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
                <form class="form-sampling" id="form-kemasan">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">
                    @if($identitas->jenis_gula == 'Garam')
                    <!-- Input untuk garam -->
                    <div class="mb-3">
                        <label class="form-label">a. Kotor</label><br>
                        <label><input type="radio" name="kotor" value="yes"> Iya</label>
                        <label><input type="radio" name="kotor" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>


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
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>


                    </div>

                    <div class="mb-3">
                        <label class="form-label">f. Sesuai STD</label><br>
                        <label><input type="radio" name="sesuai_std" value="yes"> Iya</label>
                        <label><input type="radio" name="sesuai_std" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    @else
                    <div class="mb-3">
                        <label class="form-label">a. Kotor</label><br>
                        <label><input type="radio" name="kotor" value="yes"> Iya</label>
                        <label><input type="radio" name="kotor" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>


                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Rusak Sobek</label><br>
                        <label><input type="radio" name="rusak" value="yes"> Iya</label>
                        <label><input type="radio" name="rusak" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Sesuai STD</label><br>
                        <label><input type="radio" name="sesuai_std" value="yes"> Iya</label>
                        <label><input type="radio" name="sesuai_std" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Lain-lain</label><br>
                        <label><input class="form-control" type="text" name="lain-lain"></label>
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
                <form class="form-sampling" id="form-raw">
                    @csrf
                    <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                    <div class="mb-3">
                        <label class="form-label">a. Leleh</label><br>
                        <label><input type="radio" name="leleh" value="yes"> Iya</label>
                        <label><input type="radio" name="leleh" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">b. Warna sesuai STD</label><br>
                        <label><input type="radio" name="warna_std" value="yes"> Iya</label>
                        <label><input type="radio" name="warna_std" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">c. Campuran</label><br>
                        <label><input type="radio" name="campuran" value="yes"> Iya</label>
                        <label><input type="radio" name="campuran" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">d. Aroma STD</label><br>
                        <label><input type="radio" name="aroma_std" value="yes"> Iya</label>
                        <label><input type="radio" name="aroma_std" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">e. Sesuai STD</label><br>
                        <label><input type="radio" name="sesuai_std" value="yes"> Iya</label>
                        <label><input type="radio" name="sesuai_std" value="no"> Tidak</label>
                        <div class="zak-input-wrapper mt-2" style="display:none;">
                            <label class="form-label">Berapa zak yang tidak standar?</label>
                            <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                        </div>
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
        // Tampilkan atau sembunyikan input tambahan
        $('form.form-sampling input[type=radio]').on('change', function() {
            const parent = $(this).closest('.mb-3');
            const wrapper = parent.find('.zak-input-wrapper');
            const value = $(this).val();
            const name = $(this).attr('name'); // ambil nama input (misalnya: sesuai_std, kotor, dll)

            // Default hide dulu
            wrapper.hide();
            wrapper.find('.zak-qty-input').val('');

            if (name === 'sesuai_std') {
                // KHUSUS untuk Sesuai STD
                if (value === 'no') {
                    wrapper.show();
                }
            } else {
                // Untuk field lain
                if (value === 'yes') {
                    wrapper.show();
                }
            }
        });

        $('.modal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Fungsi untuk memproses value radio sebelum submit
        function prepareRadioValues(form) {
            form.find('.mb-3').each(function() {
                const parent = $(this);
                const radio = parent.find('input[type=radio]:checked');
                const zakInput = parent.find('.zak-qty-input').val()?.trim();

                if (radio.val() === 'no' && zakInput) {
                    radio.val(`no, karena ${zakInput}`);
                }
            });
        }

        $('#form-kondisi-mobil').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            prepareRadioValues(form);
            let data = form.serialize();

            $.ajax({
                type: 'POST',
                url: '{{ route("sampling.kondisi_mobil.store") }}',
                data: data,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Data berhasil disimpan!',
                    }).then(() => {
                        $('#modalKondisiMobil').modal('hide');
                    });
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.responseJSON?.message || 'Terjadi kesalahan!',
                    });
                }
            });
        });

        $('#form-dokumen, #form-kemasan').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            prepareRadioValues(form);
            let data = form.serialize();

            const url = form.attr('id') === 'form-dokumen' ?
                '{{ route("sampling.dokumen.store") }}' :
                '{{ route("sampling.fisik_kemasan.store") }}';

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Data berhasil disimpan!',
                    }).then(() => {
                        if (form.attr('id') === 'form-dokumen') {
                            $('#modalDokumen').modal('hide');
                        } else {
                            $('#modalKemasan').modal('hide');
                        }
                    });
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.responseJSON?.message || 'Terjadi kesalahan!',
                    });
                }
            });
        });

        $('#form-raw').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            prepareRadioValues(form);
            let data = form.serialize();

            $.ajax({
                type: 'POST',
                url: '{{ route("sampling.fisik_raw.store") }}', // Pastikan route ini sesuai di Laravel Anda
                data: data,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Data berhasil disimpan!',
                    }).then(() => {
                        $('#modalFisikRaw').modal('hide');
                        location.reload(); // Reload halaman untuk update data
                    });
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.responseJSON?.message || 'Terjadi kesalahan!',
                    });
                }
            });
        });

    });
</script>

<script>
    $(document).ready(function() {
        var tipeInput = 'kedatangan';

        $('#btnBukaModalKonfirmasi').click(function() {
            $('#modalKonfirmasi').modal('show');
        });

        function Konfirmasi() {

            $.ajax({
                url: "{{ url('analis/rmpm/konfirmasi/' . $identitas->id) }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.sampling_complete && !response.jam_analisa_exists) {
                        tipeInput = 'analisa';
                        $('#modalKonfirmasi').modal('show');
                        $('#labelJam').text('Jam Analisa');
                    } else {
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
                    url: "{{ url('analis/rmpm/simpan/konfirmasi/' . $identitas->id) }}",
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
</script>

<script>
    const formContent = $('#form-analisa-content');
    let jenisGula = $('#jenis_gula').val();

    // Initialize when document is ready
    $(document).ready(function() {
        // Event handlers setup
        setupEventHandlers();
    });

    function setupEventHandlers() {
        // Image compression handler
        $(document).on('change', 'input[name="attachment"]', handleImageCompression);

        // Input formatting handlers
        $(document).on('input', '.decimal-only', formatDecimalInput);
        $(document).on('input', '.kapital-case', formatCapitalCase);

        // Dynamic crystal test handling
        $(document).on('change', '#select-uji-kristal', handleCrystalTestChange);

        // Enter key navigation
        $(document).on('keydown', 'input', handleEnterKey);

        // Modal handlers
        $('#modalAnalisa')
            .on('show.bs.modal', initializeModal)
            .on('shown.bs.modal', function() {
                // Load draft setelah modal sepenuhnya terbuka
                setTimeout(loadDraft, 100);
            });

        // Form submission
        $('#formAnalisa').on('submit', handleFormSubmit);

        // Next button
        $('#nextBtn').click(handleNextButton);

        // Auto-save changes
        $(document).on('input change', '#form-analisa-content input, #form-analisa-content select, #form-analisa-content textarea', function() {
            saveDraft();
        });
    }

    async function handleImageCompression(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size <= 2 * 1024 * 1024) return;

        const options = {
            maxSizeMB: 2,
            maxWidthOrHeight: 1920,
            useWebWorker: true
        };

        try {
            const compressedFile = await imageCompression(file, options);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            event.target.files = dataTransfer.files;
            console.log('Gambar berhasil dikompres:', compressedFile.size / 1024, 'KB');
        } catch (error) {
            console.error('Error saat kompres gambar:', error);
        }
    }

    function formatDecimalInput() {
        let val = $(this).val();
        val = val.replace(/[^0-9,]/g, '');
        val = val.replace(/(,.*),/, '$1');
        $(this).val(val);
    }

    function formatCapitalCase() {
        let value = $(this).val().toUpperCase().replace(/[^A-Z\s]/g, '');
        $(this).val(value);
    }

    function handleCrystalTestChange() {
        const value = $(this).val();
        console.log('Crystal test changed to:', value);

        // Hide all sections first
        $('.attachment-wrapper, .disposisi-wrapper, .disposisi-wrapper-negatif, .disposisi').hide();

        if (value === 'negatif') {
            console.log('Showing disposisi-wrapper-negatif');
            $('.disposisi-wrapper-negatif').show();

            // Auto-set disposisi to Release for negatif
            $('.disposisi-wrapper-negatif select[name="disposisi"]').val('Release');

            // Trigger save draft
            saveDraft();

        } else if (value === 'positif') {
            console.log('Showing attachment-wrapper');
            $('.attachment-wrapper').show();

            // Clear disposisi for positif (backend will handle)
            $('select[name="disposisi"]').val('');

            // Trigger save draft
            saveDraft();

        } else {
            console.log('Showing default disposisi');
            $('.disposisi').show();

            // Clear disposisi when no selection
            $('.disposisi select[name="disposisi"]').val('');

            // Trigger save draft
            saveDraft();
        }
    }

    function handleEnterKey(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const inputs = $('input:visible');
            const currentIndex = inputs.index(this);
            const nextInput = inputs.get(currentIndex + 1);
            if (nextInput) nextInput.focus();
        }
    }

    function initializeModal() {
        formContent.html('');
        $('#analisa-type-select, #analisa-jumlah').hide();
        $(' #nextBtn').show().text('Berikutnya');

        const draft = localStorage.getItem('analisaDraft');
        console.log('Draft analisa:', draft);

        if (draft) {
            const draftData = JSON.parse(draft);

            // Check if there's actual analysis data (not just analisaType and jumlahData)
            const hasOtherData = Object.keys(draftData).some(key => {
                const cleanKey = key.replace(/\[\]$/, '');
                // Skip analisaType and jumlahData
                if (['analisaType', 'jumlahData'].includes(cleanKey)) {
                    return false;
                }

                // Check if this field has meaningful data
                const value = draftData[key];
                if (Array.isArray(value)) {
                    // For arrays, check if at least one element is not empty
                    return value.some(item => item && item.toString().trim() !== '');
                } else {
                    // For non-arrays, check if not empty
                    return value && value.toString().trim() !== '';
                }
            });

            console.log('Has other data:', hasOtherData);
            console.log('Draft data keys:', Object.keys(draftData));

            if (hasOtherData && draftData.jumlahData && draftData.analisaType) {
                console.log('Loading existing draft data...');

                // Update jenisGula if available in draft
                if (draftData.jenisGula) {
                    jenisGula = draftData.jenisGula;
                }

                const jumlahData = parseInt(draftData.jumlahData);
                const analisaType = draftData.analisaType;

                // Render analysis fields immediately
                renderAnalysisFields(jumlahData, analisaType);

                // Hide the navigation buttons since we're going directly to the analysis form
                $('#prevBtn, #nextBtn').hide();

                return; // Exit early, loadDraft will be called in shown.bs.modal
            }
        }

        // Fallback - show initial selection screens
        if (jenisGula === 'Gula Kelapa' || jenisGula === 'Gula Tebu') {
            $('#analisa-type-select').show();
        } else if (jenisGula === 'Gula' || jenisGula === 'Garam') {
            $('#analisa-jumlah').show();
        } else {
            formContent.html(
                `<div class="alert alert-warning">Jenis gula tidak dikenali: ${jenisGula}</div>`
            );
            $('#prevBtn, #nextBtn').hide();
        }
    }

    function handleNextButton() {
        if ($('#analisa-type-select').is(':visible')) {
            const analisaType = $('input[name="analisa_type"]:checked').val();
            if (!analisaType) {
                alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
                return;
            }
            $('#analisa-type-select').hide();
            $('#analisa-jumlah').show();
            return;
        }

        if ($('#analisa-jumlah').is(':visible')) {
            const jumlahData = parseInt($('#jumlah_data').val());
            if (isNaN(jumlahData) || jumlahData <= 0) {
                alert('Masukkan jumlah data yang valid!');
                return;
            }

            $('#analisa-jumlah').hide();
            $('#prevBtn, #nextBtn').hide();

            const analisaType = $('input[name="analisa_type"]:checked').val();
            renderAnalysisFields(jumlahData, analisaType);
        }
    }

    function renderAnalysisFields(jumlahData, analisaType = null) {
        let fields = [];

        if (jenisGula === 'Gula' || jenisGula === 'Garam') {
            fields = ['fisik', '%ka', 'kotoran', 'organo', 'warna', 'aroma', '%nacl', 'gross_weight', 'disposisi'];
        } else {
            // If analisaType is not provided, try to get it from the form or draft
            if (!analisaType) {
                analisaType = $('input[name="analisa_type"]:checked').val();
            }

            if (analisaType === 'short-term') {
                fields = ['brix', 'ph', 'kotoran', 'ka', 'organo', 'warna', 'aroma', 'disposisi'];
            } else if (analisaType === 'long-term') {
                fields = ['uji_kristal', 'attachment', 'disposisi'];
            }
        }

        let navHtml = `<ul class="nav nav-tabs" id="analisaTab" role="tablist">`;
        let tabContentHtml = `<div class="tab-content mt-3">`;

        fields.forEach((field, idx) => {
            const activeClass = idx === 0 ? 'active' : '';
            const showClass = idx === 0 ? 'show active' : '';

            navHtml += `
        <li class="nav-item" role="presentation">
            <button class="nav-link ${activeClass}" id="${field}-tab" data-bs-toggle="tab" data-bs-target="#tab-${field}" type="button" role="tab">
                ${field.toUpperCase()}
            </button>
        </li>`;

            tabContentHtml += `
        <div class="tab-pane fade ${showClass}" id="tab-${field}" role="tabpanel">
            ${renderFieldInput(field, jumlahData)}
        </div>`;
        });

        navHtml += `</ul>`;
        tabContentHtml += `</div>`;

        formContent.html(navHtml + tabContentHtml);
        console.log('Analysis fields rendered');
    }

    function renderFieldInput(fieldName, count) {
        switch (fieldName) {
            case 'disposisi':
                return `
            <div class="disposisi-wrapper" style="display: none;">
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
             <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            `;

            case 'uji_kristal':
                return `
            <select class="form-control mb-2" name="uji_kristal" id="select-uji-kristal">
                <option value="">Pilih Hasil Uji</option>
                <option value="negatif">Negatif</option>
                <option value="positif">Positif</option>
            </select>`;

            case 'attachment':
                return `<div class="attachment-wrapper">
            <label>Lampirkan Gambar</label>
            <input type="file" class="form-control mb-2" name="attachment" accept="image/*">
        </div>`;

            case 'organo':
            case 'warna':
            case 'aroma':
                let html = '';
                for (let i = 1; i <= count; i++) {
                    html += `<label>${i}</label>
                <input type="text" class="form-control kapital-case" name="${fieldName}[]" placeholder="${fieldName} ke-${i}">`;
                }
                return html;

            default:
                let defaultHtml = '';
                for (let i = 1; i <= count; i++) {
                    defaultHtml += `<label>${i}</label>
                <input type="text" class="form-control decimal-only" name="${fieldName}[]" placeholder="${fieldName} ke-${i}">`;
                }
                return defaultHtml;
        }
    }

    function saveDraft() {
        let draftData = {};

        // Get existing draft first to preserve analisaType and jumlahData
        const existingDraft = localStorage.getItem('analisaDraft');
        if (existingDraft) {
            try {
                const existing = JSON.parse(existingDraft);
                // Preserve important meta fields
                if (existing.analisaType) draftData.analisaType = existing.analisaType;
                if (existing.jumlahData) draftData.jumlahData = existing.jumlahData;
                if (existing.jenisGula) draftData.jenisGula = existing.jenisGula;
            } catch (e) {
                console.log('Error parsing existing draft:', e);
            }
        }

        // Collect current form data
        $('#form-analisa-content').find('input, select, textarea').each(function() {
            let name = $(this).attr('name');
            let value = $(this).val();

            if (name && value !== undefined) {
                if (name.endsWith('[]')) {
                    if (!draftData[name]) draftData[name] = [];
                    draftData[name].push(value);
                } else {
                    draftData[name] = value;
                }
            }
        });

        // Try to get analysis type and jumlah data from current form state
        const analisaTypeFromForm = $('input[name="analisa_type"]:checked').val();
        const jumlahDataFromForm = $('#jumlah_data').val();

        // Update with current form values if available
        if (analisaTypeFromForm) draftData.analisaType = analisaTypeFromForm;
        if (jumlahDataFromForm) draftData.jumlahData = jumlahDataFromForm;

        // Always save jenisGula for context
        draftData.jenisGula = jenisGula;

        localStorage.setItem('analisaDraft', JSON.stringify(draftData));
        console.log('Draft saved:', draftData);
    }

    function loadDraft(draftData = null) {
        if (!draftData) {
            const draft = localStorage.getItem('analisaDraft');
            if (!draft) return;
            draftData = JSON.parse(draft);
        }

        console.log('Loading draft:', draftData);

        // Load form values
        for (const name in draftData) {
            if (['analisaType', 'jumlahData', 'jenisGula'].includes(name)) {
                continue; // Skip meta fields
            }

            const elements = $(`[name="${name}"]`);
            if (elements.length > 0) {
                if (Array.isArray(draftData[name])) {
                    elements.each(function(index) {
                        if (draftData[name][index] !== undefined) {
                            $(this).val(draftData[name][index]);
                        }
                    });
                } else {
                    elements.val(draftData[name]);
                }
            }
        }

        // Handle crystal test change if uji_kristal exists
        if (draftData.uji_kristal) {
            setTimeout(() => {
                $('#select-uji-kristal').trigger('change');
            }, 100);
        }

        // Show the first tab (usually BRIX for short-term)
        setTimeout(() => {
            const firstTab = $('.nav-tabs a').first();
            if (firstTab.length) {
                firstTab.tab('show');
            }
        }, 200);

        console.log('Draft loaded successfully');
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        // Convert comma decimals to dots
        $('.decimal-only').each(function() {
            const val = $(this).val().replace(',', '.');
            $(this).val(val);
        });

        const jenis = $('#jenis_gula').val();

        // Get analisaType from multiple sources - prioritas dari draft
        let analisaType = null;

        // 1. Try to get from checked radio button
        analisaType = $('input[name="analisa_type"]:checked').val();

        // 2. If not found, try to get from localStorage draft
        if (!analisaType) {
            const draft = localStorage.getItem('analisaDraft');
            if (draft) {
                const draftData = JSON.parse(draft);
                analisaType = draftData.analisaType;
            }
        }

        console.log('jenis:', jenis);
        console.log('analisaType:', analisaType);

        let url = '';

        // Create FormData FIRST
        const token = $('meta[name="csrf-token"]').attr('content');
        const formData = new FormData(this);
        formData.append('_token', token);

        // Add analisaType to formData if available
        if (analisaType) {
            formData.append('analisa_type', analisaType);
        }

        if (jenis === 'Gula Kelapa' || jenis === 'Gula Tebu') {
            if (analisaType === 'long-term') {
                url = '/analis/analisa/long-term';

                const kristalVal = $('select[name="uji_kristal"]').val();
                console.log('kristalVal:', kristalVal);

                // Validasi wajib uji_kristal untuk long-term
                if (!kristalVal) {
                    alert('Silakan pilih hasil uji kristal.');
                    return;
                }

                // Jika uji kristal negatif, disposisi wajib Release dan attachment dihapus
                if (kristalVal === 'negatif') {
                    console.log('Kristal negatif - setting disposisi to Release');

                    // Set disposisi ke Release di form dan formData
                    $('.disposisi-wrapper-negatif select[name="disposisi"]').val('Release');
                    formData.set('disposisi', 'Release');

                    // Hapus attachment dari formData jika ada
                    formData.delete('attachment');
                }

                // Jika positif, wajib lampirkan gambar dan hapus disposisi (biar backend handle)
                if (kristalVal === 'positif') {
                    console.log('Kristal positif - checking attachment');

                    const attachment = $('input[name="attachment"]')[0];
                    if (!attachment || !attachment.files || !attachment.files[0]) {
                        alert('Silakan lampirkan gambar karena hasil uji kristal positif.');
                        return;
                    }

                    // Hapus disposisi dari formData (biar backend yang handle)
                    formData.delete('disposisi');
                    console.log('Attachment found, disposisi removed from formData');
                }

            } else if (analisaType === 'short-term') {
                // short-term
                url = '/analis/analisa/short-term';

                // Ambil disposisi dari form - cari yang visible/active untuk short-term
                const disposisiVal = $('.disposisi select[name="disposisi"]:visible').val();
                console.log('Short-term disposisiVal:', disposisiVal);

                if (!disposisiVal || disposisiVal.trim() === '') {
                    alert('Silakan pilih disposisi.');
                    return;
                }

            } else {
                alert('Jenis analisa tidak diketahui. Silakan refresh halaman dan pilih ulang.');
                return;
            }
        } else if (jenis === 'Gula' || jenis === 'Garam') {
            url = '/analis/analisa/garam-gula';

            // Untuk Gula/Garam, cek disposisi - cari yang visible/active
            const disposisiVal = $('.disposisi select[name="disposisi"]:visible').val();
            console.log('Gula/Garam disposisiVal:', disposisiVal);

            if (!disposisiVal || disposisiVal.trim() === '') {
                alert('Silakan pilih disposisi.');
                return;
            }
        } else {
            alert('Jenis gula tidak dikenali: ' + jenis);
            return;
        }

        // Log formData contents for debugging
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Build proper URL
        const baseUrl = "{{ url('/') }}";
        const fullUrl = baseUrl + url;
        console.log('Submitting to URL:', fullUrl);

        // Final validation
        if (!url) {
            alert('URL tidak dapat dibentuk. Silakan refresh halaman dan coba lagi.');
            return;
        }

        $.ajax({
            url: fullUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success response:', response);
                localStorage.removeItem('analisaDraft');
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
                console.log('Error response:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response Text:', xhr.responseText);

                let errMsg = 'Gagal menyimpan data!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errMsg = 'Endpoint tidak ditemukan. Periksa routing Laravel.';
                } else if (xhr.status === 405) {
                    errMsg = 'Method tidak diizinkan. Periksa routing Laravel.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errMsg,
                    confirmButtonText: 'Tutup'
                });
            }
        });
    }
</script>



@endsection