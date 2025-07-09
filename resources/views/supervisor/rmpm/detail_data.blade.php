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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @if ($data_dokumen)
                                        <tr>
                                            <td>{{ $data_dokumen->coa ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->suratjalan_vendor ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->packing_list ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->identitas_kemasan ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->logo_halal ?? 'belum input'}}</td>
                                            <td>{{ $data_dokumen->kesesuaian_matriks_bahan ?? 'belum input'}}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                                                    Edit
                                                </button>
                                            </td>
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
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="editDokumenForm">
                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{ $data_dokumen->id }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Dokumen</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                @foreach ([
                                                'coa' => 'a. CoA',
                                                'suratjalan_vendor' => 'b. Surat Jalan Vendor',
                                                'packing_list' => 'c. Packing List',
                                                'identitas_kemasan' => 'd. Identitas di Kemasan',
                                                'logo_halal' => 'e. Logo Halal di Kemasan',
                                                'kesesuaian_matriks_bahan' => 'f. Kesesuaian dengan Matriks Bahan Baku'
                                                ] as $field => $label)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $label }}</label><br>
                                                    <label><input type="radio" name="{{ $field }}" value="yes" {{ $data_dokumen->$field == 'yes' ? 'checked' : '' }}> Iya</label>
                                                    <label><input type="radio" name="{{ $field }}" value="no" {{ $data_dokumen->$field == 'no' ? 'checked' : '' }}> Tidak</label>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Simpan</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
                                            <th>Aksi</th>
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
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModalMobil">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!--end table-->

                                <div class="modal fade" id="editModalMobil" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="editMobilForm">
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <input type="hidden" name="id" value="{{ $data_mobil->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Dokumen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @foreach ([
                                                    'bersih' => 'a. Bersih',
                                                    'kering' => 'b. Kering',
                                                    'benda_asing' => 'c. Benda Asing',
                                                    'cacat' => 'd. Cacat',
                                                    'segel' => 'e. Segel',
                                                    'berbau' => 'f. Berbau'
                                                    ] as $field => $label)
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ $label }}</label><br>
                                                        <label><input type="radio" name="{{ $field }}" value="yes" {{ $data_mobil->$field == 'yes' ? 'checked' : '' }}> Iya</label>
                                                        <label><input type="radio" name="{{ $field }}" value="no" {{ $data_mobil->$field == 'no' ? 'checked' : '' }}> Tidak</label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                                            <th>Aksi</th>
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
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModalKemasan">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="modal fade" id="editModalKemasan" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="editKemasanForm">
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <input type="hidden" name="id" value="{{ $data_kemasan->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Dokumen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>

                                                <div class="modal-body">
                                                    @foreach ([
                                                    'kotor' => 'a. Kotor',
                                                    'rusak' => 'b. Rusak',
                                                    'sesuai_std' => 'c. Sesuai Standar',

                                                    'berair' => 'd. Berair',
                                                    'basah' => 'e. Basah',
                                                    'campuran' => 'f. Campuran'
                                                    ] as $field => $label)
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ $label }}</label><br>
                                                        <label><input type="radio" name="{{ $field }}" value="yes" {{ $data_kemasan->$field == 'yes' ? 'checked' : '' }}> Iya</label>
                                                        <label><input type="radio" name="{{ $field }}" value="no" {{ $data_kemasan->$field == 'no' ? 'checked' : '' }}> Tidak</label>
                                                    </div>

                                                    @endforeach
                                                    <div class="mb-3">
                                                        <label class="form-label">d. Lain-lain</label><br>
                                                        <label><input type="text" class="form-control" name="lain-lain"></label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                                            <th>Aksi</th>

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
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModalRaw">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="12" class="text-center">Belum ada data</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <div class="modal fade" id="editModalRaw" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form id="editRawForm">
                                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                                <input type="hidden" name="id" value="{{ $data_raw->id }}">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Raw</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        @foreach ([
                                                        'leleh' => 'a. Leleh',
                                                        'warna_std' => 'b. Warna Sesuai Standar',
                                                        'aroma_std' => 'c. Aroma Sesuai Standar',
                                                        'sesuai_std_raw' => 'd. Sesuai Standar',

                                                        ] as $field => $label)
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ $label }}</label><br>
                                                            <label><input type="radio" name="{{ $field }}" value="yes" {{ $data_raw->$field == 'yes' ? 'checked' : '' }}> Iya</label>
                                                            <label><input type="radio" name="{{ $field }}" value="no" {{ $data_raw->$field == 'no' ? 'checked' : '' }}> Tidak</label>
                                                        </div>
                                                        @endforeach
                                                        <div class="mb-3">
                                                            <label class="form-label">e. Campuran</label><br>
                                                            <label><input type="radio" name="campuran_raw" value="yes"> Iya</label>
                                                            <label><input type="radio" name="campuran_raw" value="no"> Tidak</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Simpan</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                        <th scope="col">disposisi</th>
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
                                        <td>{{ $short->disposisi->disposisi ?? 'belum input'}}</td>
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
                                        <td>{{ $long->uji_kristal ?? 'belum input'}}

                                        </td>
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

                                            @if(strtolower($long->uji_kristal) === 'positif' && $long->disposisi !== 'release')
                                            <br>
                                            <button type="button" class="btn btn-sm btn-warning btn-edit-disposisi" data-id="{{ $long->id}}" data-disposisi="{{ $long->disposisi }}" data-bs-toggle="modal" data-bs-target="#updateDisposisiModal">
                                                <i class="ri-edit-line"></i> Update
                                            </button>
                                            @endif
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

<!-- Modal Update Disposisi (static modal, dipakai semua baris) -->
<div class="modal fade" id="updateDisposisiModal" tabindex="-1" aria-labelledby="updateDisposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formUpdateDisposisi" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDisposisiModalLabel">Update Disposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_disposisi" id="disposisi_id">
                    <div class="mb-3">
                        <label for="disposisi" class="form-label">Disposisi</label>
                        <select name="disposisi" id="disposisi" class="form-select" required>
                            <option value="">-- Pilih Disposisi --</option>
                            <option value="release">Release</option>
                            <option value="reject">Reject</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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

    // Buat template URL dengan placeholder
    const updateDisposisiUrlTemplate = "{{ route('rmpm_supervisor.updateDisposisiLong', ['id' => '__ID__']) }}";

    // Ketika tombol Edit diklik
    $(document).on('click', '.btn-edit-disposisi', function() {
        const id = $(this).data('id');
        const currentDisposisi = $(this).data('disposisi');

        $('#disposisi').val(currentDisposisi);
        $('#disposisi_id').val(id); // hidden input
        $('#updateDisposisiModal').modal('show');
    });

    // Submit form update disposisi
    $('#formUpdateDisposisi').on('submit', function(e) {
        e.preventDefault();

        const id = $('#disposisi_id').val();
        const url = updateDisposisiUrlTemplate.replace('__ID__', id);
        const data = $(this).serialize();

        $.post(url, data, function(res) {
            $('#updateDisposisiModal').modal('hide');
            Swal.fire('Sukses', 'Disposisi berhasil diperbarui', 'success').then(() => {
                location.reload(); // atau update baris tanpa reload
            });
        }).fail(function(xhr) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data.', 'error');
        });
    });

    $('#editDokumenForm').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'POST',
            id: $('input[name="id"]').val(),
            coa: $('input[name="coa"]:checked').val(),
            suratjalan_vendor: $('input[name="suratjalan_vendor"]:checked').val(),
            packing_list: $('input[name="packing_list"]:checked').val(),
            identitas_kemasan: $('input[name="identitas_kemasan"]:checked').val(),
            logo_halal: $('input[name="logo_halal"]:checked').val(),
            kesesuaian_matriks_bahan: $('input[name="kesesuaian_matriks_bahan"]:checked').val()
        };

        $.ajax({
            url: "{{url('/supervisor/sampling/edit/dokumen')}}/" + formData.id,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data dokumen berhasil diperbarui',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg
                });
            }
        });
    });

    $('#editKemasanForm').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'POST',
            id: $('input[name="id"]').val(),
            coa: $('input[name="coa"]:checked').val(),
            kotor: $('input[name="kotor"]:checked').val(),
            rusak: $('input[name="rusak"]:checked').val(),
            sesuai_std: $('input[name="sesuai_std"]:checked').val(),
            lain_lain: $('input[name="lain-lain"]').val(),
            berair: $('input[name="berair"]:checked').val(),
            basah: $('input[name="basah"]:checked').val(),
            campuran: $('input[name="campuran"]:checked').val(),
        };

        $.ajax({
            url: "{{url('/supervisor/sampling/edit/fisik-kemasan')}}/" + formData.id,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editModalKemasan').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data dokumen berhasil diperbarui',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg
                });
            }
        });
    });

    $('#editMobilForm').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'POST',
            id: $('input[name="id"]').val(),
            bersih: $('input[name="bersih"]:checked').val(),
            kering: $('input[name="kering"]:checked').val(),
            benda_asing: $('input[name="benda_asing"]:checked').val(),
            cacat: $('input[name="cacat"]:checked').val(),
            segel: $('input[name="segel"]:checked').val(),
            berbau: $('input[name="berbau"]:checked').val()
        };

        $.ajax({
            url: "{{url('/supervisor/sampling/edit/kondisi-mobil')}}/" + formData.id,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editModalMobil').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data dokumen berhasil diperbarui',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg
                });
            }
        });
    });

    $('#editRawForm').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'POST',
            id: $('input[name="id"]').val(),
            leleh: $('input[name="leleh"]:checked').val(),
            warna_std: $('input[name="warna_std"]:checked').val(),
            campuran: $('input[name="campuran_raw"]:checked').val(),
            aroma_std: $('input[name="aroma_std"]:checked').val(),
            sesuai_std: $('input[name="sesuai_std_raw"]:checked').val(),

        };

        $.ajax({
            url: "{{url('/supervisor/sampling/edit/fisik-raw')}}/" + formData.id,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editModalRaw').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data dokumen berhasil diperbarui',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg
                });
            }
        });
    });
</script>


@endsection