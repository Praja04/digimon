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
        <div class="col-12">
            <div class="card" id="leadsList">
                <div class="card-header border-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

                        <!-- Tombol Kembali -->
                        <div class="text-center text-md-start">
                            <a class="btn btn-danger w-100 w-md-auto" href="{{ url('foreman/rmpm/list/rm') }}">
                                Kembali
                            </a>
                        </div>

                        <!-- Grup Tombol Aksi -->
                        <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                data-bs-target="#samplingModal">
                                <i class="ri-add-line align-bottom me-1"></i> Sampling
                            </button>
                            <button type="button" class="btn btn-warning" id="btnBukaModalKonfirmasi">
                                Konfirmasi
                            </button>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#modalAnalisa">
                                <i class="ri-filter-3-line align-bottom me-1"></i> Analisa
                            </button>
                            <button class="btn btn-danger" id="remove-actions" onClick="deleteMultiple()">
                                <i class="ri-delete-bin-2-line"></i>
                            </button>
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
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-4">
                                    <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" alt="Logo"
                                        height="100" class="rounded-2">
                                    <div>
                                        <h4 class="mb-0 fw-bold text-dark">{{ $identitas->jenis_gula }}</h4>
                                        <small class="text-muted">{{ $identitas->asal_bahan }} ·
                                            {{ $identitas->no_spb }}</small>
                                    </div>
                                </div>
                                <div
                                    class="d-flex flex-column flex-sm-row justify-content-sm-end align-items-sm-center text-sm-end text-center mt-2">
                                    <div>
                                        <span class="badge bg-primary fs-6 fw-semibold mb-1 mb-sm-0">
                                            {{ \Carbon\Carbon::parse($identitas->tanggal_kedatangan)->format('d M Y') }}
                                        </span>
                                        <div class="text-muted fs-6 mt-1">
                                            {{ \Carbon\Carbon::parse($identitas->tanggal_kedatangan)->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row g-2 text-center">
                                <div class="col-6 col-lg">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">Supplier</div>
                                        <div class="fw-semibold">{{ $identitas->suplier_manufactur }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">Truck</div>
                                        <div class="fw-semibold">{{ $identitas->no_mobil }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">SPB</div>
                                        <div class="fw-semibold">{{ $identitas->no_spb }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg">
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-muted small">Jenis</div>
                                        <div class="fw-semibold">{{ $identitas->jenis_gula }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        function getBadgeClass($disposisi)
                        {
                            return match ($disposisi) {
                                'reject' => 'badge-soft-danger',
                                'release' => 'badge-soft-success',
                                default => 'badge-soft-warning',
                            };
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

                                    @if ($identitas->jenis_gula == 'Gula Tebu' || $identitas->jenis_gula == 'Gula Kelapa')
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
                                            <i class="mdi mdi-panorama-outline text-primary" style="font-size: 36px;"></i>
                                            <!-- icon gambar -->
                                            <p>Attachment Kristal</p>
                                        </a>

                                        <!-- Modal untuk menampilkan gambar -->
                                        <div class="modal fade" id="imageModal" tabindex="-1"
                                            aria-labelledby="imageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imageModalLabel">Lampiran Gambar</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/uploads/attachment_analisa/' . $attachment_analisa) }}"
                                                            alt="Lampiran Analisa" class="img-fluid rounded">
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

                    <div class="col-12 mb-3">
                        <div class="alert alert-light border-0 shadow-sm">
                            <div class="d-flex flex-wrap align-items-center gap-4">
                                <strong class="text-muted">Keterangan Status:</strong>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-checkbox-circle-fill text-success fs-4 fw-bold"></i>
                                    <span class="text-muted fw-semibold">Ya / Tersedia</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-close-circle-fill text-danger fs-4 fw-bold"></i>
                                    <span class="text-muted fw-semibold">Tidak / Tidak Tersedia</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-indeterminate-circle-fill text-warning fs-4 fw-bold"></i>
                                    <span class="text-muted fw-semibold">Belum Diisi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Dokumen -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-muted">Dokumen Pendukung</h5>
                                @if ($data_dokumen)
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModalDokumen">
                                        <i class="ri-edit-box-line me-1"></i>Update
                                    </button>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                @if ($data_dokumen)
                                    <div class="row g-2">
                                        @foreach ([
            'coa' => 'CoA',
            'suratjalan_vendor' => 'Surat Jalan Vendor',
            'packing_list' => 'Packing List',
            'identitas_kemasan' => 'Identitas Kemasan',
            'logo_halal' => 'Logo Halal',
            'kesesuaian_matriks_bahan' => 'Kesesuaian Matriks Bahan Baku',
        ] as $key => $label)
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    @if (optional($data_dokumen)->$key === 'yes')
                                                        <i class="ri-checkbox-circle-fill text-success fs-4 fw-bold"></i>
                                                    @elseif(optional($data_dokumen)->$key === 'no')
                                                        <i class="ri-close-circle-fill text-danger fs-4 fw-bold"></i>
                                                    @else
                                                        <i
                                                            class="ri-indeterminate-circle-fill text-warning fs-4 fw-bold"></i>
                                                    @endif
                                                    <span class="text-muted fw-semibold">{{ $label }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-muted mb-0">Belum ada data</p>
                                @endif
                            </div>
                        </div>

                        <!-- modal dokumen -->
                        <div class="modal fade" id="editModalDokumen" tabindex="-1"
                            aria-labelledby="editModalDokumenLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Dokumen Pendukung</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-sampling" id="editDokumenForm">
                                            @csrf
                                            <input type="hidden" name="id_dokumen"
                                                value="{{ $data_dokumen->id ?? '' }}">
                                            @foreach ([
            'coa' => 'a. CoA',
            'suratjalan_vendor' => 'b. Surat Jalan Vendor',
            'packing_list' => 'c. Packing List',
            'identitas_kemasan' => 'd. Identitas di Kemasan',
            'logo_halal' => 'e. Logo Halal di Kemasan',
            'kesesuaian_matriks_bahan' => 'f. Kesesuaian dengan Matriks Bahan Baku',
        ] as $field => $label)
                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0 fw-semibold">{{ $label }}</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_yes"
                                                                value="yes"
                                                                {{ optional($data_dokumen)->$field === 'yes' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_yes">Iya</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_no"
                                                                value="no"
                                                                {{ optional($data_dokumen)->$field === 'no' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_no">Tidak</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-2">
                                            @endforeach

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary"
                                                    id="submitBtnDokumen">Simpan
                                                    Sampling</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Kondisi Mobil -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-muted">Sample Kondisi Mobil</h5>
                                @if ($data_mobil)
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModalMobil">
                                        <i class="ri-edit-box-line me-1"></i>Update
                                    </button>
                                @endif
                            </div>

                            <div class="card-body p-3">
                                @if ($data_mobil)
                                    <div class="row g-2">
                                        @foreach ([
            'bersih' => 'Bersih',
            'kering' => 'Kering',
            'benda_asing' => 'Tidak Ada Benda Asing',
            'cacat' => 'Tidak Cacat',
            'segel' => 'Segel',
            'berbau' => 'Berbau',
        ] as $key => $label)
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    @if (optional($data_mobil)->$key === 'yes')
                                                        <i class="ri-checkbox-circle-fill text-success fs-4 fw-bold"></i>
                                                    @elseif(optional($data_mobil)->$key === 'no')
                                                        <i class="ri-close-circle-fill text-danger fs-4 fw-bold"></i>
                                                    @else
                                                        <i
                                                            class="ri-indeterminate-circle-fill text-warning fs-4 fw-bold"></i>
                                                    @endif
                                                    <span class="text-muted fw-semibold">{{ $label }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-muted mb-0">Belum ada data</p>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Edit Kondisi Mobil -->
                        <div class="modal fade" id="editModalMobil" tabindex="-1" aria-labelledby="editModalMobilLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Kondisi Mobil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editMobilForm">
                                            @csrf
                                            <input type="hidden" name="id_mobil" value="{{ $data_mobil->id ?? null }}">

                                            @foreach ([
            'bersih' => 'a. Bersih',
            'kering' => 'b. Kering',
            'benda_asing' => 'c. Tidak Ada Benda Asing',
            'cacat' => 'd. Tidak Cacat',
            'segel' => 'e. Segel',
            'berbau' => 'f. Berbau',
        ] as $field => $label)
                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0 fw-semibold">{{ $label }}</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_yes"
                                                                value="yes"
                                                                {{ optional($data_mobil)->$field === 'yes' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_yes">Iya</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_no"
                                                                value="no"
                                                                {{ optional($data_mobil)->$field === 'no' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_no">Tidak</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-2">
                                            @endforeach

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary" id="submitBtnMobil">
                                                    Simpan Kondisi Mobil
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Fisik Kemasan -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-muted">Sample Fisik Kemasan</h5>
                                @if ($data_kemasan)
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModalKemasan">
                                        <i class="ri-edit-box-line me-1"></i>Update
                                    </button>
                                @endif
                            </div>

                            <div class="card-body p-3">
                                @if ($data_kemasan)
                                    <div class="row g-2">
                                        @php
                                            $kemasanFields =
                                                $identitas->jenis_gula === 'Garam'
                                                    ? [
                                                        'kotor' => 'Kotor',
                                                        'berair' => 'Berair',
                                                        'basah' => 'Basah',
                                                        'campuran' => 'Campuran',
                                                        'rusak' => 'Rusak / Robek',
                                                        'sesuai_std' => 'Sesuai STD',
                                                        'lain-lain' => 'Lain-lain',
                                                    ]
                                                    : [
                                                        'kotor' => 'Kotor',
                                                        'rusak' => 'Rusak / Sobek',
                                                        'sesuai_std' => 'Sesuai STD',
                                                        'lain-lain' => 'Lain-lain',
                                                    ];
                                        @endphp
                                        @foreach ($kemasanFields as $key => $label)
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    @if (optional($data_kemasan)->$key === 'yes')
                                                        <i class="ri-checkbox-circle-fill text-success fs-4 fw-bold"></i>
                                                    @elseif(optional($data_kemasan)->$key === 'no')
                                                        <i class="ri-close-circle-fill text-danger fs-4 fw-bold"></i>
                                                    @else
                                                        <i
                                                            class="ri-indeterminate-circle-fill text-warning fs-4 fw-bold"></i>
                                                    @endif
                                                    <span class="text-muted fw-semibold">{{ $label }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-muted mb-0">Belum ada data</p>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Edit Kemasan -->
                        <div class="modal fade" id="editModalKemasan" tabindex="-1"
                            aria-labelledby="editModalKemasanLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalKemasanLabel">Update Sample Fisik Kemasan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="editKemasanForm">
                                            @csrf
                                            <input type="hidden" name="id_kemasan"
                                                value="{{ $data_kemasan->id ?? null }}">

                                            @php
                                                $modalFields =
                                                    $identitas->jenis_gula === 'Garam'
                                                        ? [
                                                            'kotor' => 'a. Kotor',
                                                            'berair' => 'b. Berair',
                                                            'basah' => 'c. Basah',
                                                            'campuran' => 'd. Campuran',
                                                            'rusak' => 'e. Rusak / Robek',
                                                            'sesuai_std' => 'f. Sesuai STD',
                                                        ]
                                                        : [
                                                            'kotor' => 'a. Kotor',
                                                            'rusak' => 'b. Rusak / Sobek',
                                                            'sesuai_std' => 'c. Sesuai STD',
                                                        ];
                                            @endphp
                                            @foreach ($modalFields as $field => $label)
                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0 fw-semibold">{{ $label }}</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_yes"
                                                                value="yes"
                                                                {{ optional($data_kemasan)->$field === 'yes' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_yes">Iya</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_no"
                                                                value="no"
                                                                {{ optional($data_kemasan)->$field === 'no' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_no">Tidak</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-2">
                                            @endforeach

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">
                                                    {{ $identitas->jenis_gula === 'Garam' ? 'g. Lain-lain' : 'd. Lain-lain' }}
                                                </label>
                                                <input type="text" class="form-control" name="lain-lain"
                                                    value="{{ $data_kemasan->{'lain-lain'} ?? '' }}"
                                                    placeholder="Tuliskan keterangan lain...">
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary" id="submitBtnKemasan">
                                                    Simpan Sample Fisik
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Fisik Raw -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-muted">Sample Fisik Raw</h5>
                                @if ($data_raw)
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModalRaw">
                                        <i class="ri-edit-box-line me-1"></i>Update
                                    </button>
                                @endif
                            </div>

                            <div class="card-body p-3">
                                @if ($data_raw)
                                    <div class="row g-2">
                                        @foreach ([
            'leleh' => 'Leleh',
            'warna_std' => 'Warna Sesuai Standar',
            'campuran' => 'Campuran',
            'aroma_std' => 'Aroma Tidak Sesuai Standar',
            'sesuai_std' => 'Sesuai Standar',
        ] as $key => $label)
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    @if (optional($data_raw)->$key === 'yes')
                                                        <i class="ri-checkbox-circle-fill text-success fs-4 fw-bold"></i>
                                                    @elseif(optional($data_raw)->$key === 'no')
                                                        <i class="ri-close-circle-fill text-danger fs-4 fw-bold"></i>
                                                    @else
                                                        <i
                                                            class="ri-indeterminate-circle-fill text-warning fs-4 fw-bold"></i>
                                                    @endif
                                                    <span class="text-muted fw-semibold">{{ $label }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-muted mb-0">Belum ada data</p>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Edit Raw -->
                        <div class="modal fade" id="editModalRaw" tabindex="-1" aria-labelledby="editModalRawLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Sample Fisik Raw</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="editRawForm">
                                            @csrf
                                            <input type="hidden" name="id_raw" value="{{ $data_raw->id ?? null }}">

                                            @foreach ([
            'leleh' => 'a. Leleh',
            'warna_std' => 'b. Warna Sesuai Standar',
            'aroma_std' => 'c. Aroma Tidak Sesuai Standar',
            'sesuai_std_raw' => 'd. Sesuai Standar',
            'campuran_raw' => 'e. Campuran',
        ] as $field => $label)
                                                @php
                                                    $fieldName = str_replace('_raw', '', $field);
                                                    $value = optional($data_raw)->$fieldName;
                                                @endphp

                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <label class="form-label mb-0 fw-semibold">{{ $label }}</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_yes"
                                                                value="yes" {{ $value === 'yes' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_yes">Iya</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="{{ $field }}" id="{{ $field }}_no"
                                                                value="no" {{ $value === 'no' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="{{ $field }}_no">Tidak</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-2">
                                            @endforeach


                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary" id="submitBtnRaw">
                                                    Simpan Sample Fisik Raw
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($identitas->jenis_gula == 'Gula Tebu' || $identitas->jenis_gula == 'Gula Kelapa')
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 fw-semibold">Short Term Analisa</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped align-middle mb-0">
                                            <thead class="table-light text-muted text-uppercase small">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Brix</th>
                                                    <th>pH</th>
                                                    <th>Kotoran</th>
                                                    <th>KA</th>
                                                    <th>Organo</th>
                                                    <th>Warna</th>
                                                    <th>Aroma</th>
                                                    <th>Disposisi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-detail-short">
                                                @forelse ($analisa_short_term as $index => $short)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $short->brix ?? '-' }}</td>
                                                        <td>{{ $short->ph ?? '-' }}</td>
                                                        <td>{{ $short->kotoran ?? '-' }}</td>
                                                        <td>{{ $short->ka ?? '-' }}</td>
                                                        <td>{{ $short->organo ?? '-' }}</td>
                                                        <td>{{ $short->warna ?? '-' }}</td>
                                                        <td>{{ $short->aroma ?? '-' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ getBadgeClass($short->disposisi->disposisi ?? null) }}">
                                                                {{ $short->disposisi->disposisi ?? '-' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center text-muted py-4">
                                                            Belum ada data short term
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 fw-semibold">Long Term Analisa</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped align-middle mb-0">
                                            <thead class="table-light text-muted text-uppercase small">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Uji Kristal</th>
                                                    <th>Disposisi</th>
                                                    <th width="15%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-detail-long">
                                                @forelse ($analisa_long_term as $index => $long)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $long->uji_kristal ?? '-' }}</td>
                                                        <td>
                                                            <span class="badge {{ getBadgeClass($long->disposisi) }}">
                                                                {{ $long->disposisi ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            @if (strtolower($long->uji_kristal ?? '') === 'positif' && $long->disposisi !== 'release')
                                                                <button type="button"
                                                                    class="btn btn-sm btn-warning btn-edit-disposisi"
                                                                    data-id="{{ $long->id }}"
                                                                    data-disposisi="{{ $long->disposisi }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updateDisposisiModal">
                                                                    <i class="ri-edit-line"></i> Update
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-4">
                                                            Belum ada data long term
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 fw-semibold">Hasil Analisa</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped align-middle mb-0">
                                            <thead class="table-light text-muted text-uppercase small">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Fisik</th>
                                                    <th>%KA</th>
                                                    <th>Kotoran</th>
                                                    <th>Organo</th>
                                                    <th>Warna</th>
                                                    <th>Aroma</th>
                                                    <th>%NaCl</th>
                                                    <th>Gross Weight</th>
                                                    <th>Disposisi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-detail-analisa">
                                                @forelse ($analisa_garam_gula as $index => $garamgula)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $garamgula->fisik ?? '-' }}</td>
                                                        <td>{{ $garamgula->{'%ka'} ?? '-' }}</td>
                                                        <td>{{ $garamgula->kotoran ?? '-' }}</td>
                                                        <td>{{ $garamgula->organo ?? '-' }}</td>
                                                        <td>{{ $garamgula->warna ?? '-' }}</td>
                                                        <td>{{ $garamgula->aroma ?? '-' }}</td>
                                                        <td>{{ $garamgula->{'%nacl'} ?? '-' }}</td>
                                                        <td>{{ $garamgula->gross_weight ?? '-' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ getBadgeClass($garamgula->disposisi->disposisi ?? null) }}">
                                                                {{ $garamgula->disposisi->disposisi ?? '-' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center text-muted py-4">
                                                            Belum ada data analisa
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
    <div class="modal fade" id="updateDisposisiModal" tabindex="-1" aria-labelledby="updateDisposisiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
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



    <!-- modal -->

    <!-- Modal Pilihan Sampling -->
    <div class="modal fade" id="samplingModal" tabindex="-1" aria-labelledby="samplingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="samplingModalLabel">Pilih Kategori Sampling</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $samplingComplete =
                            !is_null($identitas->samplingMobil) &&
                            !is_null($identitas->samplingDokumen) &&
                            !is_null($identitas->samplingFisikKemasan) &&
                            ($identitas->jenis_gula === 'Garam' || !is_null($identitas->samplingFisikRaw));
                    @endphp

                    @if ($samplingComplete)
                        <div class="alert alert-info text-center">
                            Anda sudah mengisi semua sampling.
                        </div>
                    @else
                        <p>Silakan pilih kategori sampling yang ingin Anda isi.</p>
                        <div class="list-group">
                            @if (is_null($identitas->samplingMobil))
                                <button type="button" class="list-group-item list-group-item-action sampling-option"
                                    data-sampling="kondisi_mobil" data-title="Sampling Kondisi Mobil"
                                    data-bs-dismiss="modal">
                                    <i class="ri-truck-line me-2"></i> Sampling Kondisi Mobil
                                </button>
                            @endif

                            @if (is_null($identitas->samplingDokumen))
                                <button type="button" class="list-group-item list-group-item-action sampling-option"
                                    data-sampling="kondisi_dokumen" data-title="Sampling Dokumen"
                                    data-bs-dismiss="modal">
                                    <i class="ri-file-text-line me-2"></i> Sampling Dokumen
                                </button>
                            @endif

                            @if (is_null($identitas->samplingFisikKemasan))
                                <button type="button" class="list-group-item list-group-item-action sampling-option"
                                    data-sampling="kondisi_kemasan" data-title="Sampling Kemasan"
                                    data-bs-dismiss="modal">
                                    <i class="ri-inbox-line me-2"></i> Sampling Kemasan
                                </button>
                            @endif

                            @if ($identitas->jenis_gula !== 'Garam' && is_null($identitas->samplingFisikRaw))
                                <button type="button" class="list-group-item list-group-item-action sampling-option"
                                    data-sampling="kondisi_raw" data-title="Sampling Raw" data-bs-dismiss="modal">
                                    <i class="ri-flask-line me-2"></i> Sampling Raw
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- endmodal -->

    <!-- Modal Sampling Kondisi Mobil -->
    <div class="modal fade" id="modalKondisiMobil" tabindex="-1" aria-labelledby="modalKondisiMobilLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sampling Kondisi Mobil - {{ $identitas->no_mobil }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-sampling" id="form-kondisi-mobil">
                        @csrf
                        <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0 fw-semibold">a. Bersih</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="bersih" id="bersih-yes"
                                        value="yes">
                                    <label class="form-check-label" for="bersih-yes">Iya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="bersih" id="bersih-no"
                                        value="no">
                                    <label class="form-check-label" for="bersih-no">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0 fw-semibold">b. Kering</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kering" id="kering-yes"
                                        value="yes">
                                    <label class="form-check-label" for="kering-yes">Iya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kering" id="kering-no"
                                        value="no">
                                    <label class="form-check-label" for="kering-no">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0 fw-semibold">c. Tidak Ada Benda Asing</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="benda_asing"
                                        id="benda_asing-yes" value="yes">
                                    <label class="form-check-label" for="benda_asing-yes">Iya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="benda_asing"
                                        id="benda_asing-no" value="no">
                                    <label class="form-check-label" for="benda_asing-no">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0 fw-semibold">d. Tidak Cacat / Bolong</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="cacat" id="cacat-yes"
                                        value="yes">
                                    <label class="form-check-label" for="cacat-yes">Iya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="cacat" id="cacat-no"
                                        value="no">
                                    <label class="form-check-label" for="cacat-no">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0 fw-semibold">e. Segel</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="segel" id="segel-yes"
                                        value="yes">
                                    <label class="form-check-label" for="segel-yes">Iya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="segel" id="segel-no"
                                        value="no">
                                    <label class="form-check-label" for="segel-no">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0 fw-semibold">f. Tidak Berbau</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="berbau" id="berbau-yes"
                                        value="yes">
                                    <label class="form-check-label" for="berbau-yes">Iya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="berbau" id="berbau-no"
                                        value="no">
                                    <label class="form-check-label" for="berbau-no">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Sampling</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal dokumen -->
    <div class="modal fade" id="modalDokumen" tabindex="-1" aria-labelledby="modalDokumenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sampling Dokumen - {{ $identitas->nama_bahan }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-sampling" id="form-dokumen">
                        @csrf
                        <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

                        @foreach ([
            'coa' => 'a. CoA',
            'suratjalan_vendor' => 'b. Surat Jalan Vendor',
            'packing_list' => 'c. Packing List',
            'identitas_kemasan' => 'd. Identitas di Kemasan',
            'logo_halal' => 'e. Logo Halal di Kemasan',
            'kesesuaian_matriks_bahan' => 'f. Kesesuaian dengan Matriks Bahan Baku',
        ] as $field => $label)
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0 fw-semibold">{{ $label }}</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="{{ $field }}"
                                            id="{{ $field }}_yes" value="yes">
                                        <label class="form-check-label" for="{{ $field }}_yes">Iya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="{{ $field }}"
                                            id="{{ $field }}_no" value="no">
                                        <label class="form-check-label" for="{{ $field }}_no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">
                        @endforeach

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtnDokumen">Simpan
                                Sampling</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- kemasan -->
    <div class="modal fade" id="modalKemasan" tabindex="-1" aria-labelledby="modalKemasanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                        @if ($identitas->jenis_gula == 'Garam')
                            <!-- Input untuk garam -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">a. Kotor</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kotor" id="kotor-yes"
                                                value="yes">
                                            <label class="form-check-label" for="kotor-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kotor" id="kotor-no"
                                                value="no">
                                            <label class="form-check-label" for="kotor-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="zak-input-wrapper mt-3" style="display:none;">
                                    <label class="form-label small text-muted">Jumlah zak kotor?</label>
                                    <input type="number" class="form-control form-control-sm zak-qty-input"
                                        placeholder="Contoh: 5" min="0">
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">b. Berair</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="berair"
                                                id="berair-yes" value="yes">
                                            <label class="form-check-label" for="berair-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="berair" id="berair-no"
                                                value="no">
                                            <label class="form-check-label" for="berair-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">c. Basah</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="basah" id="basah-yes"
                                                value="yes">
                                            <label class="form-check-label" for="basah-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="basah" id="basah-no"
                                                value="no">
                                            <label class="form-check-label" for="basah-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">d. Campuran</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="campuran"
                                                id="campuran-yes" value="yes">
                                            <label class="form-check-label" for="campuran-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="campuran"
                                                id="campuran-no" value="no">
                                            <label class="form-check-label" for="campuran-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">e. Rusak / Sobek</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rusak" id="rusak-yes"
                                                value="yes">
                                            <label class="form-check-label" for="rusak-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rusak" id="rusak-no"
                                                value="no">
                                            <label class="form-check-label" for="rusak-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="zak-input-wrapper mt-2" style="display:none;">
                                    <label class="form-label">Berapa zak yang rusak / sobek?</label>
                                    <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">f. Sesuai STD</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sesuai_std"
                                                id="sesuai_std-yes" value="yes">
                                            <label class="form-check-label" for="sesuai_std-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sesuai_std"
                                                id="sesuai_std-no" value="no">
                                            <label class="form-check-label" for="sesuai_std-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="zak-input-wrapper mt-2" style="display:none;">
                                    <label class="form-label">Berapa zak yang tidak standar?</label>
                                    <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">a. Kotor</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kotor" id="kotor-yes"
                                                value="yes">
                                            <label class="form-check-label" for="kotor-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="kotor" id="kotor-no"
                                                value="no">
                                            <label class="form-check-label" for="kotor-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="zak-input-wrapper mt-3" style="display:none;">
                                    <label class="form-label small text-muted">Jumlah zak kotor?</label>
                                    <input type="number" class="form-control form-control-sm zak-qty-input"
                                        placeholder="Contoh: 5" min="0">
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">b. Rusak / Sobek</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rusak" id="rusak-yes"
                                                value="yes">
                                            <label class="form-check-label" for="rusak-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rusak" id="rusak-no"
                                                value="no">
                                            <label class="form-check-label" for="rusak-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="zak-input-wrapper mt-3" style="display:none;">
                                    <label class="form-label small text-muted">Jumlah zak rusak / sobek?</label>
                                    <input type="number" class="form-control form-control-sm zak-qty-input"
                                        placeholder="Contoh: 5" min="0">
                                </div>
                            </div>
                            <hr class="my-2">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">c. Sesuai STD</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sesuai_std"
                                                id="sesuai_std-yes" value="yes">
                                            <label class="form-check-label" for="sesuai_std-yes">Iya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sesuai_std"
                                                id="sesuai_std-no" value="no">
                                            <label class="form-check-label" for="sesuai_std-no">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="zak-input-wrapper mt-3" style="display:none;">
                                    <label class="form-label">Berapa zak yang tidak standar?</label>
                                    <input type="text" class="form-control zak-qty-input" placeholder="Contoh: 5 zak">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0 fw-semibold">d. Lain-lain</label>
                                    <div>
                                        <input class="form-control" type="text" name="lain-lain">
                                    </div>
                                </div>
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
        <div class="modal-dialog modal-lg">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0 fw-semibold">a. Leleh</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="leleh" id="leleh-yes"
                                            value="yes">
                                        <label class="form-check-label" for="leleh-yes">Iya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="leleh" id="leleh-no"
                                            value="no">
                                        <label class="form-check-label" for="leleh-no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="zak-input-wrapper mt-3" style="display:none;">
                                <label class="form-label small text-muted">Jumlah zak leleh?</label>
                                <input type="number" class="form-control form-control-sm zak-qty-input"
                                    placeholder="Contoh: 5" min="0">
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0 fw-semibold">b. Warna tidak sesuai STD</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="warna_std"
                                            id="warna_std-yes" value="yes">
                                        <label class="form-check-label" for="warna_std-yes">Iya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="warna_std"
                                            id="warna_std-no" value="no">
                                        <label class="form-check-label" for="warna_std-no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="zak-input-wrapper mt-3" style="display:none;">
                                <label class="form-label small text-muted">Jumlah zak tidak standar?</label>
                                <input type="number" class="form-control form-control-sm zak-qty-input"
                                    placeholder="Contoh: 5" min="0">
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0 fw-semibold">c. Campuran</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="campuran"
                                            id="campuran-yes" value="yes">
                                        <label class="form-check-label" for="campuran-yes">Iya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="campuran"
                                            id="campuran-no" value="no">
                                        <label class="form-check-label" for="campuran-no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="zak-input-wrapper mt-3" style="display:none;">
                                <label class="form-label small text-muted">Jumlah zak campuran?</label>
                                <input type="number" class="form-control form-control-sm zak-qty-input"
                                    placeholder="Contoh: 5" min="0">
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0 fw-semibold">d. Aroma tidak STD</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="aroma_std"
                                            id="aroma_std-yes" value="yes">
                                        <label class="form-check-label" for="aroma_std-yes">Iya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="aroma_std"
                                            id="aroma_std-no" value="no">
                                        <label class="form-check-label" for="aroma_std-no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="zak-input-wrapper mt-3" style="display:none;">
                                <label class="form-label small text-muted">Jumlah zak tidak standar</label>
                                <input type="number" class="form-control form-control-sm zak-qty-input"
                                    placeholder="Contoh: 5" min="0">
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0 fw-semibold">e. Sesuai STD</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sesuai_std"
                                            id="sesuai_std-yes" value="yes">
                                        <label class="form-check-label" for="sesuai_std-yes">Iya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sesuai_std"
                                            id="sesuai_std-no" value="no">
                                        <label class="form-check-label" for="sesuai_std-no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="zak-input-wrapper mt-3" style="display:none;">
                                <label class="form-label small text-muted">Jumlah zak tidak standar?</label>
                                <input type="number" class="form-control form-control-sm zak-qty-input"
                                    placeholder="Contoh: 5" min="0">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtnRaw">Simpan Sampling</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Analisa -->
    <div class="modal fade" id="modalAnalisa" tabindex="-1" aria-labelledby="modalAnalisaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-sm rounded-4">
                <form id="formAnalisa">
                    <div class="modal-header border-0 pb-2">
                        <h5 class="modal-title fw-semibold" id="modalAnalisaLabel">Form Analisa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <!-- Hidden inputs -->
                        <input type="hidden" id="id_identitas" name="id_identitas" value="{{ $identitas->id }}">
                        <input type="hidden" id="jenis_gula" name="jenis_gula"
                            value="{{ $identitas->jenis_gula }}">

                        <!-- Dynamic form fields -->
                        <div id="form-analisa-content" class="mb-3"></div>

                        <!-- Type selection -->
                        <div id="analisa-type-select" class="mb-3" style="display: none;">
                            <label class="form-label fw-medium">Jenis Analisa</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="analisa_type" id="short"
                                    value="short-term">
                                <label class="btn btn-outline-primary" for="short">Short-Term</label>

                                <input type="radio" class="btn-check" name="analisa_type" id="long"
                                    value="long-term">
                                <label class="btn btn-outline-primary" for="long">Long-Term</label>
                            </div>
                        </div>

                        <!-- Jumlah data -->
                        <div id="analisa-jumlah" style="display: none;">
                            <label for="jumlah_data" class="form-label fw-medium">Jumlah Data</label>
                            <input class="form-control" type="number" name="jumlah_data" id="jumlah_data"
                                min="1" placeholder="Masukkan jumlah data">
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="modal-footer border-0 px-4 pb-3">
                        <button type="button" id="prevBtn" class="btn btn-secondary"
                            style="display: none;">Sebelumnya</button>
                        <button type="button" id="nextBtn" class="btn btn-primary">Berikutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Jam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="formKonfirmasi">
                        <div class="mb-3">
                            <label for="jamInput" class="form-label" id="labelJam">Jam Kedatangan</label>
                            <input type="datetime-local" class="form-control" id="jamInput"
                                value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSimpanJam" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>

    <script>
        // =====================================
        // AJAX SETUP & GLOBAL CSRF TOKEN
        // =====================================
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // =====================================
        // PDF DOWNLOAD HANDLER
        // =====================================
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

        // =====================================
        // SAMPLING MODAL HANDLER
        // =====================================
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
                            const modal = new bootstrap.Modal(document.getElementById(
                                modalId));
                            modal.show();
                        }
                    }, 500);
                });
            });
        });

        // =====================================
        // UPDATE DISPOSISI LONG TERM
        // =====================================
        const updateDisposisiUrlTemplate = "{{ route('rmpm_foreman.updateDisposisiLong', ['id' => '__ID__']) }}";

        $(document).on('click', '.btn-edit-disposisi', function() {
            const id = $(this).data('id');
            const currentDisposisi = $(this).data('disposisi');

            $('#disposisi').val(currentDisposisi);
            $('#disposisi_id').val(id);
            $('#updateDisposisiModal').modal('show');
        });

        $('#formUpdateDisposisi').on('submit', function(e) {
            e.preventDefault();

            const id = $('#disposisi_id').val();
            const url = updateDisposisiUrlTemplate.replace('__ID__', id);
            const data = $(this).serialize();

            $.post(url, data, function(res) {
                $('#updateDisposisiModal').modal('hide');
                Swal.fire('Sukses', 'Disposisi berhasil diperbarui', 'success').then(() => {
                    location.reload();
                });
            }).fail(function(xhr) {
                Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim data.', 'error');
            });
        });

        // =====================================
        // EDIT DOKUMEN FORM
        // =====================================
        $('#editDokumenForm').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'POST',
                id: $('input[name="id_dokumen"]').val(),
                coa: $('input[name="coa"]:checked').val(),
                suratjalan_vendor: $('input[name="suratjalan_vendor"]:checked').val(),
                packing_list: $('input[name="packing_list"]:checked').val(),
                identitas_kemasan: $('input[name="identitas_kemasan"]:checked').val(),
                logo_halal: $('input[name="logo_halal"]:checked').val(),
                kesesuaian_matriks_bahan: $('input[name="kesesuaian_matriks_bahan"]:checked').val()
            };

            $.ajax({
                url: "{{ url('/foreman/sampling/edit/dokumen') }}/" + formData.id,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editModalDokumen').modal('hide');
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

        // =====================================
        // EDIT KEMASAN FORM
        // =====================================
        $('#editKemasanForm').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'POST',
                id: $('input[name="id_kemasan"]').val(),
                kotor: $('input[name="kotor"]:checked').val(),
                rusak: $('input[name="rusak"]:checked').val(),
                sesuai_std: $('input[name="sesuai_std"]:checked').val(),
                lain_lain: $('input[name="lain-lain"]').val(),
                berair: $('input[name="berair"]:checked').val(),
                basah: $('input[name="basah"]:checked').val(),
                campuran: $('input[name="campuran"]:checked').val(),
            };

            $.ajax({
                url: "{{ url('/foreman/sampling/edit/fisik-kemasan') }}/" + formData.id,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editModalKemasan').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data kemasan berhasil diperbarui',
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

        // =====================================
        // EDIT MOBIL FORM
        // =====================================
        $('#editMobilForm').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'POST',
                id: $('input[name="id_mobil"]').val(),
                bersih: $('input[name="bersih"]:checked').val(),
                kering: $('input[name="kering"]:checked').val(),
                benda_asing: $('input[name="benda_asing"]:checked').val(),
                cacat: $('input[name="cacat"]:checked').val(),
                segel: $('input[name="segel"]:checked').val(),
                berbau: $('input[name="berbau"]:checked').val()
            };

            $.ajax({
                url: "{{ url('/foreman/sampling/edit/kondisi-mobil') }}/" + formData.id,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editModalMobil').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data mobil berhasil diperbarui',
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

        // =====================================
        // EDIT RAW FORM
        // =====================================
        $('#editRawForm').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'POST',
                id: $('input[name="id_raw"]').val(),
                leleh: $('input[name="leleh"]:checked').val(),
                warna_std: $('input[name="warna_std"]:checked').val(),
                campuran: $('input[name="campuran_raw"]:checked').val(),
                aroma_std: $('input[name="aroma_std"]:checked').val(),
                sesuai_std: $('input[name="sesuai_std_raw"]:checked').val(),
            };

            $.ajax({
                url: "{{ url('/foreman/sampling/edit/fisik-raw') }}/" + formData.id,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editModalRaw').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data raw berhasil diperbarui',
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

        // =====================================
        // SAMPLING FORM HANDLERS
        // =====================================
        $(document).ready(function() {
            // Show/hide input tambahan based on radio selection
            $('form.form-sampling input[type=radio]').on('change', function() {
                const parent = $(this).closest('.mb-3');
                const wrapper = parent.find('.zak-input-wrapper');
                const value = $(this).val();
                const name = $(this).attr('name');

                wrapper.hide();
                wrapper.find('.zak-qty-input').val('');

                if (name === 'sesuai_std') {
                    if (value === 'no') {
                        wrapper.show();
                    }
                } else {
                    if (value === 'yes') {
                        wrapper.show();
                    }
                }
            });

            // Close modal handler
            $('.modal').on('hidden.bs.modal', function() {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });

            // Prepare radio values before submit
            function prepareRadioValues(form) {
                form.find('.mb-3').each(function() {
                    const parent = $(this);
                    const radio = parent.find('input[type=radio]:checked');
                    const zakInput = parent.find('.zak-qty-input').val()?.trim();

                    if (radio.val() === 'yes' && zakInput) {
                        radio.val(`no, karena ${zakInput}`);
                    }
                });
            }

            // Submit Kondisi Mobil
            $('#form-kondisi-mobil').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                prepareRadioValues(form);
                let data = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('sampling.kondisi_mobil.store') }}',
                    data: data,
                    success: function(response) {
                        $('#modalKondisiMobil').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            location.reload();
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

            // Submit Dokumen & Kemasan
            $('#form-dokumen, #form-kemasan').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                prepareRadioValues(form);
                let data = form.serialize();

                const url = form.attr('id') === 'form-dokumen' ?
                    '{{ route('sampling.dokumen.store') }}' :
                    '{{ route('sampling.fisik_kemasan.store') }}';

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(response) {
                        if (form.attr('id') === 'form-dokumen') {
                            $('#modalDokumen').modal('hide');
                        } else {
                            $('#modalKemasan').modal('hide');
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            location.reload();
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

            // Submit Raw
            $('#form-raw').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                prepareRadioValues(form);
                let data = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('sampling.fisik_raw.store') }}',
                    data: data,
                    success: function(response) {
                        $('#modalRaw').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            location.reload();
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

        // =====================================
        // KONFIRMASI JAM HANDLER
        // =====================================
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
                            $('#labelJam').hide();
                            $('#jamInput').hide();
                            $('#btnSimpanJam').hide();
                            $('#pesanRequiredSampling').show();
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
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Response simpan:', response);
                            $('#statusMessage').show().text(response.message);

                            if (tipeInput === 'kedatangan') {
                                tipeInput = 'analisa';
                                $('#labelJam').text('Jam Analisa');
                                $('#jamInput').val('');
                            } else {
                                $('#modalKonfirmasi').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message || 'Data berhasil disimpan!',
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                });
            }

            Konfirmasi();
        });

        // =====================================
        // ANALISA FORM - MAIN LOGIC
        // =====================================
        const formContent = $('#form-analisa-content');
        let jenisGula = $('#jenis_gula').val();

        $(document).ready(function() {
            setupEventHandlers();
        });

        function setupEventHandlers() {
            $(document).on('change', 'input[name="attachment"]', handleImageCompression);
            $(document).on('input', '.decimal-only', formatDecimalInput);
            $(document).on('input', '.kapital-case', formatCapitalCase);
            $(document).on('change', '#select-uji-kristal', handleCrystalTestChange);
            $(document).on('keydown', 'input', handleEnterKey);

            $('#modalAnalisa')
                .on('show.bs.modal', initializeModal)
                .on('shown.bs.modal', function() {
                    setTimeout(loadDraft, 100);
                });

            $('#formAnalisa').on('submit', handleFormSubmit);
            $('#nextBtn').click(handleNextButton);

            $(document).on('input change',
                '#form-analisa-content input, #form-analisa-content select, #form-analisa-content textarea',
                function() {
                    saveDraft();
                });
        }

        // Image compression handler
        async function handleImageCompression(event) {
            const file = event.target.files[0];
            if (!file || file.size <= 2 * 1024 * 1024) return;

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

        // Format decimal input
        function formatDecimalInput() {
            let val = $(this).val();
            val = val.replace(/[^0-9,]/g, '');
            val = val.replace(/(,.*),/, '$1');
            $(this).val(val);
        }

        // Format capital case
        function formatCapitalCase() {
            let value = $(this).val().toUpperCase().replace(/[^A-Z\s]/g, '');
            $(this).val(value);
        }

        // Handle enter key navigation
        function handleEnterKey(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const inputs = $('input:visible');
                const currentIndex = inputs.index(this);
                const nextInput = inputs.get(currentIndex + 1);
                if (nextInput) nextInput.focus();
            }
        }

        // Crystal test change handler
        function handleCrystalTestChange() {
            const value = $(this).val();
            console.log('Crystal test changed to:', value);

            $('.attachment-wrapper, .disposisi-wrapper, .disposisi-wrapper-negatif, .disposisi').hide();

            if (value === 'negatif') {
                console.log('Showing disposisi-wrapper-negatif');
                $('.disposisi-wrapper-negatif').show();
                $('.disposisi-wrapper-negatif select[name="disposisi"]').val('Release');
                saveDraft();
            } else if (value === 'positif') {
                console.log('Showing attachment-wrapper');
                $('.attachment-wrapper').show();
                $('select[name="disposisi"]').val('');
                saveDraft();
            } else {
                console.log('Showing default disposisi');
                $('.disposisi').show();
                $('.disposisi select[name="disposisi"]').val('');
                saveDraft();
            }
        }

        // Initialize modal
        function initializeModal() {
            formContent.html('');
            $('#analisa-type-select, #analisa-jumlah').hide();
            $('#nextBtn').show().text('Berikutnya');

            const draft = localStorage.getItem('analisaDraft');
            console.log('Draft analisa:', draft);

            if (draft) {
                const draftData = JSON.parse(draft);

                const hasOtherData = Object.keys(draftData).some(key => {
                    const cleanKey = key.replace(/\[\]$/, '');
                    if (['analisaType', 'jumlahData'].includes(cleanKey)) {
                        return false;
                    }

                    const value = draftData[key];
                    if (Array.isArray(value)) {
                        return value.some(item => item && item.toString().trim() !== '');
                    } else {
                        return value && value.toString().trim() !== '';
                    }
                });

                console.log('Has other data:', hasOtherData);

                if (hasOtherData && draftData.jumlahData && draftData.analisaType) {
                    console.log('Loading existing draft data...');

                    if (draftData.jenisGula) {
                        jenisGula = draftData.jenisGula;
                    }

                    const jumlahData = parseInt(draftData.jumlahData);
                    const analisaType = draftData.analisaType;

                    renderAnalysisFields(jumlahData, analisaType);
                    $('#prevBtn, #nextBtn').hide();
                    return;
                }
            }

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

        // Handle next button
        function handleNextButton() {
            if ($('#analisa-type-select').is(':visible')) {
                const analisaType = $('input[name="analisa_type"]:checked').val();
                if (!analisaType) {
                    alert('Silakan pilih jenis analisa (Short-Term / Long-Term)');
                    return;
                }

                if (analisaType === 'long-term') {
                    $('#analisa-type-select').hide();
                    $('#prevBtn, #nextBtn').hide();
                    renderAnalysisFields(1, analisaType);
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

        // Render analysis fields - FIXED FOR GARAM
        function renderAnalysisFields(jumlahData, analisaType = null) {
            let fields = [];

            if (jenisGula === 'Gula' || jenisGula === 'Garam') {
                // FIX: Use safe field names for IDs
                fields = ['fisik', 'persen_ka', 'kotoran', 'organo', 'warna', 'aroma', 'persen_nacl', 'gross_weight',
                    'disposisi'
                ];
            } else {
                if (!analisaType) {
                    analisaType = $('input[name="analisa_type"]:checked').val();
                }

                if (analisaType === 'short-term') {
                    fields = ['brix', 'ph', 'kotoran', 'ka', 'organo', 'warna', 'aroma', 'disposisi'];
                } else if (analisaType === 'long-term') {
                    fields = ['uji_kristal', 'attachment', 'disposisi'];
                }
            }

            let navHtml = `<ul class="nav nav-tabs nav-tabs-custom" id="analisaTab" role="tablist">`;
            let tabContentHtml = `<div class="tab-content mt-3">`;

            fields.forEach((field, idx) => {
                const activeClass = idx === 0 ? 'active' : '';
                const showClass = idx === 0 ? 'show active' : '';

                const labelMap = {
                    fisik: 'Fisik',
                    persen_ka: '%KA',
                    kotoran: 'Kotoran',
                    organo: 'Organo',
                    warna: 'Warna',
                    aroma: 'Aroma',
                    persen_nacl: '%NaCl',
                    gross_weight: 'Gross Weight',
                    disposisi: 'Disposisi',
                    brix: 'Brix',
                    ph: 'pH',
                    ka: 'KA',
                    uji_kristal: 'Uji Kristal',
                    attachment: 'Lampiran'
                };

                navHtml += `
            <li class="nav-item" role="presentation">
                <button class="nav-link ${activeClass}" id="${field}-tab" data-bs-toggle="tab" 
                        data-bs-target="#tab-${field}" type="button" role="tab" 
                        aria-controls="tab-${field}" aria-selected="${idx === 0 ? 'true' : 'false'}">
                    ${labelMap[field] || field.toUpperCase()}
                </button>
            </li>`;

                tabContentHtml += `
            <div class="tab-pane fade ${showClass}" id="tab-${field}" role="tabpanel" aria-labelledby="${field}-tab">
                ${renderFieldInput(field, jumlahData)}
            </div>`;
            });

            navHtml += `</ul>`;
            tabContentHtml += `</div>`;

            formContent.html(navHtml + tabContentHtml);
            console.log('Analysis fields rendered');

            setupTabValidation();
        }

        // Render field input - FIXED MAPPING
        function renderFieldInput(fieldName, count) {
            // Map clean field names to actual form names
            const nameMap = {
                'persen_ka': '%ka',
                'persen_nacl': '%nacl'
            };

            const actualFieldName = nameMap[fieldName] || fieldName;

            switch (fieldName) {
                case 'disposisi':
                    return `
                <div class="disposisi-wrapper" style="display:none;">
                    <label class="form-label fw-medium">Disposisi</label>
                    <select class="form-select mb-3" name="disposisi">
                        <option value="">Pilih Disposisi</option>
                        <option value="Release">Release</option>
                        <option value="Reject">Reject</option>
                    </select>
                </div>
                <div class="disposisi-wrapper-negatif" style="display:none;">
                    <label class="form-label fw-medium">Disposisi</label>
                    <select class="form-select mb-3" name="disposisi">
                        <option value="">Pilih Disposisi</option>
                        <option value="Release">Release</option>
                    </select>
                </div>
                <div class="disposisi">
                    <label class="form-label fw-medium">Disposisi</label>
                    <select class="form-select mb-3" name="disposisi">
                        <option value="">Pilih Disposisi</option>
                        <option value="Release">Release</option>
                        <option value="Reject">Reject</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Simpan</button>`;

                case 'uji_kristal':
                    return `
                <div class="mb-3">
                    <label class="form-label fw-medium">Hasil Uji Kristal</label>
                    <select class="form-select" name="uji_kristal" id="select-uji-kristal">
                        <option value="">Pilih Hasil Uji</option>
                        <option value="negatif">Negatif</option>
                        <option value="positif">Positif</option>
                    </select>
                </div>`;

                case 'attachment':
                    return `
                <div class="attachment-wrapper">
                    <label class="form-label fw-medium">Lampirkan Gambar</label>
                    <input type="file" class="form-control" name="attachment" accept="image/*">
                    <div class="form-text">Format: JPG, PNG, max 2 MB</div>
                </div>`;

                case 'organo':
                case 'warna':
                case 'aroma':
                    let html = '<div class="row g-2">';
                    for (let i = 1; i <= count; i++) {
                        html += `
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label fw-medium">Sampel ${i}</label>
                        <input type="text" class="form-control kapital-case" name="${actualFieldName}[]" placeholder="Sampel ${i}">
                    </div>`;
                    }
                    html += '</div>';
                    return html;

                default:
                    let defaultHtml = '<div class="row g-2">';
                    for (let i = 1; i <= count; i++) {
                        defaultHtml += `
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label fw-medium">Sampel ${i}</label>
                        <input type="text" class="form-control decimal-only" name="${actualFieldName}[]" placeholder="Sampel ${i}">
                    </div>`;
                    }
                    defaultHtml += '</div>';
                    return defaultHtml;
            }
        }

        // Save draft
        function saveDraft() {
            let draftData = {};

            const existingDraft = localStorage.getItem('analisaDraft');
            if (existingDraft) {
                try {
                    const existing = JSON.parse(existingDraft);
                    if (existing.analisaType) draftData.analisaType = existing.analisaType;
                    if (existing.jumlahData) draftData.jumlahData = existing.jumlahData;
                    if (existing.jenisGula) draftData.jenisGula = existing.jenisGula;
                } catch (e) {
                    console.log('Error parsing existing draft:', e);
                }
            }

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

            const analisaTypeFromForm = $('input[name="analisa_type"]:checked').val();
            const jumlahDataFromForm = $('#jumlah_data').val();

            if (analisaTypeFromForm) draftData.analisaType = analisaTypeFromForm;
            if (jumlahDataFromForm) draftData.jumlahData = jumlahDataFromForm;

            draftData.jenisGula = jenisGula;

            localStorage.setItem('analisaDraft', JSON.stringify(draftData));
            console.log('Draft saved:', draftData);
        }

        // Load draft
        function loadDraft(draftData = null) {
            if (!draftData) {
                const draft = localStorage.getItem('analisaDraft');
                if (!draft) return;
                draftData = JSON.parse(draft);
            }

            console.log('Loading draft:', draftData);

            for (const name in draftData) {
                if (['analisaType', 'jumlahData', 'jenisGula'].includes(name)) {
                    continue;
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

            if (draftData.uji_kristal) {
                setTimeout(() => {
                    $('#select-uji-kristal').trigger('change');
                }, 100);
            }

            setTimeout(() => {
                const firstTab = $('.nav-tabs a').first();
                if (firstTab.length) {
                    firstTab.tab('show');
                }
            }, 200);

            console.log('Draft loaded successfully');
        }

        // Handle form submit
        function handleFormSubmit(e) {
            e.preventDefault();

            $('.decimal-only').each(function() {
                const val = $(this).val().replace(',', '.');
                $(this).val(val);
            });

            const jenis = $('#jenis_gula').val();
            let analisaType = null;

            analisaType = $('input[name="analisa_type"]:checked').val();

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

            const token = $('meta[name="csrf-token"]').attr('content');
            const formData = new FormData(this);
            formData.append('_token', token);

            if (analisaType) {
                formData.append('analisa_type', analisaType);
            }

            if (jenis === 'Gula Kelapa' || jenis === 'Gula Tebu') {
                if (analisaType === 'long-term') {
                    url = '/analis/analisa/long-term';

                    const kristalVal = $('select[name="uji_kristal"]').val();
                    console.log('kristalVal:', kristalVal);

                    if (!kristalVal) {
                        alert('Silakan pilih hasil uji kristal.');
                        return;
                    }

                    if (kristalVal === 'negatif') {
                        console.log('Kristal negatif - setting disposisi to Release');

                        $('.disposisi-wrapper-negatif select[name="disposisi"]').val('Release');
                        formData.set('disposisi', 'Release');

                        formData.delete('attachment');
                    }

                    if (kristalVal === 'positif') {
                        console.log('Kristal positif - checking attachment');

                        const attachment = $('input[name="attachment"]')[0];
                        if (!attachment || !attachment.files || !attachment.files[0]) {
                            alert('Silakan lampirkan gambar karena hasil uji kristal positif.');
                            return;
                        }

                        formData.delete('disposisi');
                        console.log('Attachment found, disposisi removed from formData');
                    }

                } else if (analisaType === 'short-term') {
                    url = '/analis/analisa/short-term';

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

            console.log('FormData contents:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            const baseUrl = window.location.origin;
            const fullUrl = baseUrl + url;
            console.log('Submitting to URL:', fullUrl);

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
                    $('#modalAnalisa').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil disimpan!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#formAnalisa')[0].reset();
                        location.reload();
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

        // Setup tab validation
        function setupTabValidation() {
            $(document).on('input change', '#form-analisa-content input, #form-analisa-content select', function() {
                saveDraft();
            });

            $(document).on('show.bs.tab', '#analisaTab button[data-bs-toggle="tab"]', function(e) {
                const clickedTab = $(e.target);
                const clickedIndex = $('#analisaTab .nav-link').index(clickedTab);
                const currentActiveTab = $('#analisaTab .nav-link.active').not(clickedTab);
                const currentActiveIndex = $('#analisaTab .nav-link').index(currentActiveTab);

                if (clickedIndex <= currentActiveIndex) {
                    return;
                }

                let incompleteTabIndex = -1;
                let incompleteTabName = '';

                for (let i = 0; i < clickedIndex; i++) {
                    const tab = $('#analisaTab .nav-link').eq(i);
                    const tabId = tab.attr('data-bs-target');
                    const tabPane = $(tabId);
                    const tabName = tab.text().trim();
                    const inputs = tabPane.find('input[type="text"], select').not('[type="file"]');

                    let allFilled = true;
                    inputs.each(function() {
                        const value = $(this).val();
                        if (!value || value.trim() === '') {
                            allFilled = false;
                            return false;
                        }
                    });

                    if (!allFilled) {
                        incompleteTabIndex = i;
                        incompleteTabName = tabName;
                        break;
                    }
                }

                if (incompleteTabIndex !== -1) {
                    e.preventDefault();
                    e.stopPropagation();

                    const incompleteTab = $('#analisaTab .nav-link').eq(incompleteTabIndex);
                    const incompleteTabId = incompleteTab.attr('data-bs-target');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: `Harap lengkapi isian pada tab "${incompleteTabName}" terlebih dahulu!`,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        const tabTrigger = new bootstrap.Tab(incompleteTab[0]);
                        tabTrigger.show();

                        const firstEmptyInput = $(incompleteTabId).find('input[type="text"], select')
                            .filter(function() {
                                const val = $(this).val();
                                return !val || val.trim() === '';
                            }).first();

                        if (firstEmptyInput.length > 0) {
                            setTimeout(() => {
                                firstEmptyInput.focus();
                            }, 300);
                        }
                    });

                    return false;
                }
            });
        }

        // Validation styles
        const validationStyles = `
    <style id="tab-validation-styles">
    .tab-pane input:focus,
    .tab-pane select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .tab-pane input.is-invalid,
    .tab-pane select.is-invalid {
        border-color: #dc3545;
    }

    .tab-pane input.is-valid,
    .tab-pane select.is-valid {
        border-color: #198754;
    }
    </style>
    `;

        // Inject styles if not exists
        if (!$('#tab-validation-styles').length) {
            $('head').append(validationStyles);
        }
    </script>


@endsection
