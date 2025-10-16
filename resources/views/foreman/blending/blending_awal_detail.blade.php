@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Blending Awal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                        <li class="breadcrumb-item active">Foreman</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Selamat Datang, {{ Session::get('username') }}!</h4>
                    <p class="text-muted mb-0">Mari tingkatkan kualitas agar menjadi perusahan makanan kelas dunia.</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <form action="javascript:void(0);">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input id="date-picker" type="text"
                                        class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr"
                                        data-range-date="true" data-date-format="d M, Y"
                                        data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                    <div class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-auto">
                                <button type="button"
                                    class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none"><i
                                        class="ri-pulse-line"></i></button>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div><!-- end card header -->
        </div>
        <!--end col-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row gx-lg-5">
                        <div class="col-xl-12">
                            <div class="mt-xl-0 mt-5">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h4>{{ $productionBatch->po_number }} (Nomor PO)</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><a href="#"
                                                    class="text-primary d-block">{{ Session::get('username') }}</a></div>
                                            <div class="vr"></div>

                                            <div class="text-muted">Tanggal Produksi : <span
                                                    class="text-body fw-medium">{{ $productionBatch->production_date }}</span>
                                            </div>


                                        </div>
                                    </div>

                                </div>



                                <div class="row mt-4">
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="p-2 border border-dashed rounded">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                        <i class="ri-drop-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="text-muted mb-1">Variant :</p>
                                                    <h5 class="mb-0">{{ $productionBatch->variant }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="p-2 border border-dashed rounded">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                        <i class="ri-arrow-left-right-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="text-muted mb-1">Batch Range :</p>
                                                    <h5 class="mb-0">{{ $productionBatch->batch_range }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="p-2 border border-dashed rounded">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                        <i class="ri-home-gear-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="text-muted mb-1">Storage :</p>
                                                    <h5 class="mb-0">{{ $productionBatch->storage }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->

                                    <!-- end col -->
                                </div>


                                <!-- end row -->

                                <div class="mt-4 text-muted">
                                    <h5 class="fs-14">Description :</h5>
                                    <p>{{ $productionBatch->description }}</p>
                                </div>


                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <div class="col-lg-12">
            <div class="card" id="tasksList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">All Tasks</h5>
                    </div>
                </div>

                <!--end card-body-->
                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>Nomor PO</th>
                                    <th>Batch Range</th>
                                    <th>No Blending</th>
                                    <th>Volume</th>
                                    <th>BRIX</th>
                                    <th>NACL</th>
                                    <th>Bj</th>
                                    <th>Visco</th>
                                    <th>Aw</th>
                                    <th>Buih</th>
                                    <th>Organo</th>
                                    <th>pH</th>
                                    <th>Endapan</th>
                                    <th>Warna</th>
                                    <th>Created By</th>
                                    <th>Disposisi</th>
                                    <th>Catatan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @forelse ($productionBatch->BlendingAwal as $blending)
                                    <tr>
                                        <td>
                                            {{ $blending->po_number }}
                                        </td>
                                        <td>
                                            @if ($blending->revisi != null)
                                                {{ $blending->batch_range }} ❗
                                            @else
                                                {{ $blending->batch_range }}
                                            @endif

                                            @if ($blending->additional_batch_info)
                                                @foreach ($blending->additional_batch_info as $relasi)
                                                    <span class="badge bg-info">{{ $relasi->batch }}</span>
                                                @endforeach
                                            @else
                                            @endif
                                        </td>
                                        <td>{{ $blending->nomor_blending }}</td>
                                        <td>{{ $blending->volume }}</td>
                                        <td>{{ $blending->brix ?? '-' }}</td>
                                        <td>{{ $blending->nacl ?? '-' }}</td>
                                        <td>{{ $blending->bj ?? '-' }}</td>
                                        <td>{{ $blending->visco ?? '-' }}</td>
                                        <td>{{ $blending->aw ?? '-' }}</td>
                                        <td>{{ $blending->buih ?? '-' }}</td>
                                        <td>{{ $blending->organo ?? '-' }}</td>
                                        <td>{{ $blending->ph ?? '-' }}</td>
                                        <td>{{ $blending->endapan ?? '-' }}</td>
                                        <td>{{ $blending->warna ?? '-' }}</td>
                                        <td>{{ $blending->created_by ?? '-' }}</td>
                                        <td>{{ $blending->disposition ?? '-' }}</td>
                                        <td>{{ $blending->disposition_remarks ?? '-' }}</td>
                                        <td>
                                            @if (
                                                $blending->disposition_remarks != null &&
                                                    $blending->disposition_remarks != '-' &&
                                                    $blending->disposition != 'Adjustment')
                                                {{ $blending->disposition_remarks }}
                                            @elseif($blending->disposition == 'Adjustment')
                                                Adjustment Air: {{ $blending->adjustment_qty_air }} Liter, Garam:
                                                {{ $blending->adjustment_qty_garam }} Kg, Gula:
                                                {{ $blending->adjustment_qty_gula }} Kg
                                            @elseif($blending->is_adjustment == true)
                                                After Adjustment
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if (is_null($blending->disposition))
                                                <button class="btn btn-sm btn-primary open-blending-modal"
                                                    data-id="{{ $blending->id }}">Input Analisa Blending Awal</button>
                                            @else
                                                <button class="btn btn-sm btn-warning open-blending-modal-edit"
                                                    data-id="{{ $blending->id }}" data-brix="{{ $blending->brix }}"
                                                    data-nacl="{{ $blending->nacl }}" data-bj="{{ $blending->bj }}"
                                                    data-visco="{{ $blending->visco }}" data-aw="{{ $blending->aw }}"
                                                    data-buih="{{ $blending->buih }}"
                                                    data-organo="{{ $blending->organo }}" data-ph="{{ $blending->ph }}"
                                                    data-endapan="{{ $blending->endapan }}"
                                                    data-warna="{{ $blending->warna }}"
                                                    data-disposition="{{ $blending->disposition }}"
                                                    data-disposition_remarks="{{ $blending->disposition_remarks }}">
                                                    Edit Data
                                                </button>
                                            @endif


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Semua data Blending Awal sudah
                                            lengkap.</td>
                                    </tr>
                                @endforelse

                                <!-- Modal input GGA tunggal -->
                                <div class="modal fade" id="inputBlendingModal" tabindex="-1"
                                    aria-labelledby="inputBlendingModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <form id="blendingForm" class="ajax-gga-form">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Input Data Blending Awal</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none error-alert"></div>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">BRIX</label>
                                                            <input type="number" step="0.01" max="100"
                                                                min="0" name="brix" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">NACL</label>
                                                            <input type="number" step="0.01" max="100"
                                                                min="0" name="nacl" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Bj</label>
                                                            <input type="number" step="0.01" name="bj"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Visco</label>
                                                            <input type="number" step="0.01" name="visco"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Aw</label>
                                                            <input type="number" step="0.01" name="aw"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Buih</label>
                                                            <input type="number" step="0.01" name="buih"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Organo</label>
                                                            <input type="text" name="organo" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">pH</label>
                                                            <input type="number" step="0.01" name="ph"
                                                                class="form-control">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Endapan</label>
                                                            <input type="text" name="endapan" class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Warna</label>
                                                            <select name="warna" id="warnaSelect" class="form-select"
                                                                required>
                                                                <option value="">-- Pilih Warna --</option>
                                                                @foreach ($manageWarna as $warna)
                                                                    <option value="{{ $warna->nama_warna }}">
                                                                        {{ $warna->nama_warna }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Disposition</label>
                                                            <select name="disposition"
                                                                class="form-select disposition-select" required>
                                                                <option value="">-- Pilih Disposition --</option>
                                                                <option value="Release">Release</option>
                                                                <option value="Release Bersyarat">Release Bersyarat
                                                                </option>
                                                                <option value="Resampling">Resampling</option>
                                                                <option value="Reject">Reject</option>
                                                                <option value="Repro">Repro</option>
                                                                <option value="Adjustment">Adjustment</option>
                                                                <option value="Jalan Bareng">Jalan Bareng</option>
                                                                <option value="Leveling">Leveling</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-mb-12 mb-3">
                                                            <label class="form-label">Remarks</label>
                                                            <textarea name="disposition_remarks" class="form-control" rows="2"
                                                                placeholder="Isi remarks jika diperlukan..."></textarea>
                                                        </div>
                                                        <div class="col-mb-12 d-none adjustment-qty-wrapper">
                                                            <h6 class="form-label fw-bold mb-3">Adjustment Qty</h6>
                                                            <div class="row g-3">
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Air (Liter)</label>
                                                                    <input type="number" step="0.01"
                                                                        name="adjustment_qty_air"
                                                                        class="form-control adjustment-qty"
                                                                        placeholder="0.00">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Gula (Kg)</label>
                                                                    <input type="number" step="0.01"
                                                                        name="adjustment_qty_gula"
                                                                        class="form-control adjustment-qty"
                                                                        placeholder="0.00">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Garam (Kg)</label>
                                                                    <input type="number" step="0.01"
                                                                        name="adjustment_qty_garam"
                                                                        class="form-control adjustment-qty"
                                                                        placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </tbody>

                        </table>
                        <!--end table-->
                        <div class="modal fade" id="EditBlendingModal" tabindex="-1"
                            aria-labelledby="EditBlendingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form id="blendingFormEdit">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Blending Awal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger d-none error-alert"></div>
                                            <div class="row g-3">
                                                <input type="hidden" name="id_edit" id="id_edit">
                                                <div class="col-md-4">
                                                    <label class="form-label">BRIX</label>
                                                    <input type="number" step="0.01" max="100" min="0"
                                                        name="brix_edit" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">NACL</label>
                                                    <input type="number" step="0.01" max="100" min="0"
                                                        name="nacl_edit" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Bj</label>
                                                    <input type="text" name="bj_edit" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Visco</label>
                                                    <input type="text" name="visco_edit" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">AW</label>
                                                    <input type="text" name="aw_edit" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Buih</label>
                                                    <input type="text" name="buih_edit" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Organo</label>
                                                    <input type="text" name="organo_edit" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">pH</label>
                                                    <input type="text" name="ph_edit" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Endapan</label>
                                                    <input type="text" name="endapan_edit" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Warna</label>
                                                    <select name="warna_edit" id="warna_edit" class="form-select"
                                                        required>
                                                        <option value="">-- Pilih Warna --</option>
                                                        @foreach ($manageWarna as $warna)
                                                            <option value="{{ $warna->nama_warna }}">
                                                                {{ $warna->nama_warna }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Disposition</label>
                                                    <select name="disposition_edit" class="form-select disposition-select"
                                                        required>
                                                        <option value="">-- Pilih Disposition --</option>
                                                        <option value="Release">Release</option>
                                                        <option value="Release Bersyarat">Release Bersyarat</option>
                                                        <option value="Resampling">Resampling</option>
                                                        <option value="Reject">Reject</option>
                                                        <option value="Repro">Repro</option>
                                                        <option value="Adjustment">Adjustment</option>
                                                        <option value="Jalan Bareng">Jalan Bareng</option>
                                                        <option value="Leveling">Leveling</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Remarks</label>
                                                    <textarea name="disposition_remarks_edit" class="form-control" rows="3"
                                                        placeholder="Isi remarks jika diperlukan..."></textarea>
                                                </div>
                                            </div>


                                            <div class="mb-3 d-none adjustment-qty-wrapper">
                                                <h6 class="form-label fw-bold">Adjustment Qty</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Air (Liter)</label>
                                                        <input type="number" step="0.01"
                                                            name="adjustment_qty_edit_air"
                                                            class="form-control adjustment-qty" placeholder="0.00">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Gula (Kg)</label>
                                                        <input type="number" step="0.01"
                                                            name="adjustment_qty_edit_gula"
                                                            class="form-control adjustment-qty" placeholder="0.00">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Garam (Kg)</label>
                                                        <input type="number" step="0.01"
                                                            name="adjustment_qty_edit_garam"
                                                            class="form-control adjustment-qty" placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <div class="pagination-wrap hstack gap-2">
                            <a class="page-item pagination-prev disabled" href="#">
                                Previous
                            </a>
                            <ul class="pagination listjs-pagination mb-0"></ul>
                            <a class="page-item pagination-next" href="#">
                                Next
                            </a>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <script>
        const warnaUrl = "{{ url('/data/warna') }}";

        function loadWarnaOptions() {
            $.ajax({
                url: warnaUrl,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.success && res.data.length > 0) {
                        const select = $('#warnaSelect');
                        select.empty().append('<option value="">-- Pilih Warna --</option>');
                        res.data.forEach(item => {
                            const option = $('<option></option>')
                                .val(item.code_warna)
                                .text(item.nama_warna + ' (' + item.code_warna + ')');
                            select.append(option);
                        });
                    }
                },
                error: function() {
                    console.warn('Gagal mengambil data warna');
                }
            });
        }

        // Load saat modal dibuka
        loadWarnaOptions();

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let selectedId = null;

            // Ketika tombol diklik, simpan ID dan buka modal
            $('.open-blending-modal').on('click', function() {
                selectedId = $(this).data('id');
                $('#inputBlendingModal').modal('show');
            });

            $('.open-blending-modal-edit').on('click', function() {
                selectedId = $(this).data('id');

                const id = $(this).data('id');
                const brix = $(this).data('brix');
                const nacl = $(this).data('nacl');
                const bj = $(this).data('bj');
                const visco = $(this).data('visco');
                const aw = $(this).data('aw');
                const buih = $(this).data('buih');
                const organo = $(this).data('organo');
                const ph = $(this).data('ph');
                const endapan = $(this).data('endapan');
                const warna = $(this).data('warna');
                const disposition = $(this).data('disposition');
                const disposition_remarks = $(this).data('disposition_remarks');

                // Set nilai ke dalam form modal
                $('input[name="id_edit"]').val(id);
                $('input[name="brix_edit"]').val(brix);
                $('input[name="nacl_edit"]').val(nacl);
                $('input[name="bj_edit"]').val(bj);
                $('input[name="visco_edit"]').val(visco);
                $('input[name="aw_edit"]').val(aw);
                $('input[name="buih_edit"]').val(buih);
                $('input[name="organo_edit"]').val(organo);
                $('input[name="ph_edit"]').val(ph);
                $('input[name="endapan_edit"]').val(endapan);
                $('select[name="warna_edit"]').val(warna);
                $('select[name="disposition_edit"]').val(disposition);
                $('input[name="disposition_remarks_edit"]').val(disposition_remarks);

                $('#EditBlendingModal').modal('show');
            });

            // Show/hide adjustment qty saat ganti disposition
            $('.disposition-select').on('change', function() {
                const selected = $(this).val();
                const qtyWrapper = $('.adjustment-qty-wrapper');
                const qtyWrapperlabel = $('.adjustment-qty-wrapper');
                const qtyInput = $('.adjustment-qty');
                const qtyInputedit = $('.adjustment-qty-edit');

                if (selected === 'Adjustment') {
                    qtyWrapper.removeClass('d-none');
                    qtyWrapperlabel.removeClass('d-none');
                    qtyInput.prop('required', true);
                    qtyInputedit.prop('required', true);
                } else {
                    qtyWrapper.addClass('d-none');
                    qtyWrapperlabel.addClass('d-none');
                    qtyInput.prop('required', false).val('');
                    qtyInputedit.prop('required', false).val('');
                }
            });

            // Reset form saat modal dibuka
            $('#inputBlendingModal').on('shown.bs.modal', function() {
                $('#blendingForm')[0].reset();
                $('.disposition-select').trigger('change');
                $('.error-alert').addClass('d-none').html('');
            });

            // Submit form
            $('#blendingForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const alertBox = form.find('.error-alert');
                const submitBtn = form.find('button[type="submit"]');

                alertBox.addClass('d-none').empty();
                submitBtn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ url('/foreman/blending/update') }}/" + selectedId,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#inputBlendingModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil disimpan.'
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || ['Terjadi kesalahan.'];
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyimpan!',
                            html: errors.join('<br>'),
                        });

                        submitBtn.prop('disabled', false).text('Simpan');
                    }
                });
            });

            $('#blendingFormEdit').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const alertBox = form.find('.error-alert');
                const submitBtn = form.find('button[type="submit"]');

                alertBox.addClass('d-none').empty();
                submitBtn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ url('/foreman/blending/edit') }}/" + selectedId,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#EditBlendingModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil disimpan.'
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || ['Terjadi kesalahan.'];
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyimpan!',
                            html: errors.join('<br>'),
                        });

                        submitBtn.prop('disabled', false).text('Simpan');
                    }
                });
            });
        });
    </script>
@endsection
