@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Blending Awal</a></li>
                        <li class="breadcrumb-item active">Product Details</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

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
                                            <div class="text-end">
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#inputModal">
                                                    Input Blending Awal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-6 col-sm-6">
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
                                    <div class="col-lg-6 col-sm-6">
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
                                    {{-- <div class="col-lg-4 col-sm-6">
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
                                    </div> --}}
                                    <!-- end col -->

                                    <!-- end col -->
                                </div>


                                <!-- end row -->

                                <div class="mt-4 text-muted">
                                    <h5 class="fs-14">Description :</h5>
                                    <p>{{ $productionBatch->description }}</p>
                                </div>

                                <div class="product-content mt-5">
                                    <h5 class="fs-14 mb-3">Generate Barcode :</h5>
                                    <nav>
                                        <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab"
                                                    href="#nav-speci" role="tab" aria-controls="nav-speci"
                                                    aria-selected="true">Blending Awal</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab"
                                                    href="#nav-detail" role="tab" aria-controls="nav-detail"
                                                    aria-selected="false">Blending Mikro - Adjustment</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-speci" role="tabpanel"
                                            aria-labelledby="nav-speci-tab">
                                            <div class="table-responsive">

                                                @if ($productionBatch->BlendingAwal->count() > 0)
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Batch Range</th>
                                                                <th>QR Code (URL)</th>
                                                                <th>Storage</th>
                                                                <th>Disposisi</th>
                                                                <th>Keterangan</th>
                                                                <th>Revisi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($productionBatch->BlendingAwal as $blending)
                                                                <tr>
                                                                    <td>{{ $blending->batch_range }}
                                                                        @if ($blending->has_relation == true)
                                                                            <span class="badge bg-info ms-2">
                                                                                -{{ $blending->related_batches }}
                                                                                {{ $blending->additional_batches }}

                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <!-- Tombol untuk buka modal -->
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#qrModal{{ $blending->id }}">
                                                                            QR Code {{ $blending->id }}
                                                                        </button>

                                                                        <!-- Modal Besar -->
                                                                        <div class="modal fade"
                                                                            id="qrModal{{ $blending->id }}" tabindex="-1"
                                                                            aria-labelledby="qrModalLabel{{ $blending->id }}"
                                                                            aria-hidden="true">
                                                                            <div
                                                                                class="modal-dialog modal-dialog-centered modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header py-2">
                                                                                        <h5 class="modal-title"
                                                                                            id="qrModalLabel{{ $blending->id }}">
                                                                                            QR Code - ID
                                                                                            {{ $blending->id }}</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close btn-sm"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body text-center"
                                                                                        id="qrPrintArea{{ $blending->id }}">
                                                                                        <div style="display: inline-block;">
                                                                                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('analis/blending/awal/detail/form/' . $blending->id), 'QRCODE') }}"
                                                                                                alt="QR Code">
                                                                                        </div>
                                                                                        <p>Blending/{{ $productionBatch->po_number }}/{{ $productionBatch->production_date }}/{{ $blending->batch_range }}-
                                                                                            @if ($blending->has_relation)
                                                                                                {{ $blending->related_batches }}
                                                                                            @endif
                                                                                        </p>
                                                                                    </div>
                                                                                    <div
                                                                                        class="modal-footer justify-content-center py-2">
                                                                                        <button
                                                                                            onclick="printQR('qrPrintArea{{ $blending->id }}')"
                                                                                            class="btn btn-sm btn-success">Print</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-secondary"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $blending->storage ?? '-' }}</td>
                                                                    <td>
                                                                        {{ $blending->disposition }}
                                                                        @if (in_array($blending->disposition, ['Adjustment', 'Resampling', 'Leveling', 'Jalan Bareng']) &&
                                                                                $blending->not_standar == true)
                                                                            <button
                                                                                class="btn btn-sm btn-warning generate-revisi-btn"
                                                                                data-id="{{ $blending->id }}"
                                                                                data-batch="{{ $blending->batch_range }}"
                                                                                data-po="{{ $blending->production_batch_id }}"
                                                                                data-disposition="{{ $blending->disposition }}">
                                                                                ❗
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (
                                                                            $blending->disposition_remarks != null &&
                                                                                $blending->disposition_remarks != '-' &&
                                                                                $blending->disposition != 'Adjustment')
                                                                            {{ $blending->disposition_remarks }}
                                                                        @elseif($blending->disposition == 'Adjustment')
                                                                            Adjustment Air:
                                                                            {{ $blending->adjustment_qty_air }} Liter,
                                                                            Garam: {{ $blending->adjustment_qty_garam }}
                                                                            Kg, Gula: {{ $blending->adjustment_qty_gula }}
                                                                            Kg
                                                                        @elseif($blending->is_adjustment == true)
                                                                            -
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                    <td>

                                                                        @if ($blending->revisi != null)
                                                                            Revisi Ke-{{ $blending->revisi }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p class="text-muted">Belum ada data Blending.</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="nav-detail" role="tabpanel"
                                            aria-labelledby="nav-detail-tab">
                                            <div class="table-responsive">
                                                @if ($productionBatch->blendingAfterAdjustMikro->count() > 0)
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Batch Range</th>
                                                                <th>QR Code (URL)</th>
                                                                <th>Disposisi</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($productionBatch->blendingAfterAdjustMikro as $blending)
                                                                <tr>
                                                                    <td>{{ $blending->batch_range }}</td>
                                                                    <td>
                                                                        <!-- Tombol untuk buka modal -->
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#qrModal{{ $blending->id }}">
                                                                            QR Code - {{ $blending->id }}
                                                                        </button>

                                                                        <!-- Modal Besar -->
                                                                        <div class="modal fade"
                                                                            id="qrModal{{ $blending->id }}"
                                                                            tabindex="-1"
                                                                            aria-labelledby="qrModalLabel{{ $blending->id }}"
                                                                            aria-hidden="true">
                                                                            <div
                                                                                class="modal-dialog modal-dialog-centered modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header py-2">
                                                                                        <h5 class="modal-title"
                                                                                            id="qrModalLabel{{ $blending->id }}">
                                                                                            QR Code - Mikro</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close btn-sm"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body text-center"
                                                                                        id="qrPrintArea{{ $blending->id }}">
                                                                                        <div
                                                                                            style="display: inline-block;">
                                                                                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('analis/blending/mikro/detail/form/' . $blending->id), 'QRCODE') }}"
                                                                                                alt="QR Code">
                                                                                        </div>
                                                                                        <p>BlendingAfterAdjust-Mikro
                                                                                            /{{ $productionBatch->po_number }}/{{ $productionBatch->variant }}/{{ $blending->batch_range }}
                                                                                        </p>
                                                                                    </div>
                                                                                    <div
                                                                                        class="modal-footer justify-content-center py-2">
                                                                                        <button
                                                                                            onclick="printQR('qrPrintArea{{ $blending->id }}')"
                                                                                            class="btn btn-sm btn-success">Print</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-secondary"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        {{ $blending->disposition }}
                                                                        @if (in_array($blending->disposition, ['Adjustment', 'Resampling', 'Leveling', 'Jalan Bareng']) &&
                                                                                $blending->revisi == null &&
                                                                                $blending->not_standar == true)
                                                                            <button
                                                                                class="btn btn-sm btn-warning generate-revisi-btn"
                                                                                data-id="{{ $blending->id }}"
                                                                                data-batch="{{ $blending->batch_range }}"
                                                                                data-po="{{ $blending->production_batch_id }}"
                                                                                data-disposition="{{ $blending->disposition }}">
                                                                                ❗
                                                                            </button>
                                                                        @else
                                                                        @endif
                                                                    </td>
                                                                    <td>

                                                                        @if ($blending->revisi != null)
                                                                            Revisi Ke-{{ $blending->revisi }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p class="text-muted">Belum ada data Blending.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
        <!-- end col -->
    </div>
    <!-- end row -->



    <!-- Modal -->
    <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Input Blending Awal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="production_batch_id" value="{{ $productionBatch->id }}">

                        <div class="mb-3">
                            <label for="batch_start" class="form-label">Batch Pertama</label>
                            <select name="batch_start" class="form-select" id="batch_start" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="batch_end" class="form-label">Batch Kedua</label>
                            <select name="batch_end" class="form-select" id="batch_end" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="no_blending" class="form-label">Nomor Blending</label>
                            <input type="number" name="no_blending" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="volume" class="form-label">Volume</label>
                            <input type="text" name="volume" class="form-control comma-input"
                                placeholder="Contoh: 0,00" required>
                        </div>
                        <div class="mb-3">
                            <label for="storage" class="form-label">Storage (Optional)</label>
                            <select name="storage" class="form-select">
                                <option value="">-- Pilih Storage --</option>
                                <optgroup label="A">
                                    <option value="A1">A1</option>
                                    <option value="A2">A2</option>
                                    <option value="A3">A3</option>
                                    <option value="A4">A4</option>
                                    <option value="A5">A5</option>
                                </optgroup>
                                <optgroup label="B">
                                    <option value="B1">B1</option>
                                    <option value="B2">B2</option>
                                    <option value="B3">B3</option>
                                    <option value="B4">B4</option>
                                    <option value="B5">B5</option>
                                </optgroup>
                                <optgroup label="C">
                                    <option value="C1">C1</option>
                                    <option value="C2">C2</option>
                                    <option value="C3">C3</option>
                                    <option value="C4">C4</option>
                                    <option value="C5">C5</option>
                                </optgroup>
                                <optgroup label="D">
                                    <option value="D1">D1</option>
                                    <option value="D2">D2</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="D5">D5</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Generate Ulang -->
    <div class="modal fade" id="generateRevisiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="generateRevisiForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generate Revisi Batch</h5>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" name="id_old_blending" id="modal_id_blending">
                        <input type="hidden" name="production_batch_id" id="modal_po_id">
                        <input type="hidden" name="production_batch_id_leveling[]" id="po_id_leveling_1">
                        <input type="hidden" name="production_batch_id_leveling[]" id="po_id_leveling_2">

                        <input type="hidden" name="disposition" id="modal_disposition">
                        <input type="hidden" id="modal_additional_batch_po_id" name="additional_batch_po_id">

                        <!-- <input type="hidden" name="revisi" id="modal_revisi" readonly> -->
                        <div class="col-lg-12">
                            <label>Batch</label>
                            <input type="text" class="form-control" id="modal_batch" name="batch_range" readonly>
                        </div>
                        <div class="col-lg-6">
                            <label>Revisi Ke-</label>
                            <input type="text" class="form-control" id="modal_revisi_display" name="revisi"
                                readonly>
                        </div>
                        <div class="col-lg-6">
                            <label>Nomor Blending</label>
                            <input type="text" class="form-control" name="no_blending">
                        </div>
                        <div class="col-lg-6">
                            <label>Volume After</label>
                            <input type="text" class="form-control comma-input" placeholder="Contoh: 0,00" required
                                name="volume">
                        </div>
                        <div class="col-lg-6">
                            <label for="storage" class="form-label">Storage (Optional)</label>
                            <select name="storage" class="form-select">
                                <option value="">-- Pilih Storage --</option>
                                <optgroup label="A">
                                    <option value="A1">A1</option>
                                    <option value="A2">A2</option>
                                    <option value="A3">A3</option>
                                    <option value="A4">A4</option>
                                    <option value="A5">A5</option>
                                </optgroup>
                                <optgroup label="B">
                                    <option value="B1">B1</option>
                                    <option value="B2">B2</option>
                                    <option value="B3">B3</option>
                                    <option value="B4">B4</option>
                                    <option value="B5">B5</option>
                                </optgroup>
                                <optgroup label="C">
                                    <option value="C1">C1</option>
                                    <option value="C2">C2</option>
                                    <option value="C3">C3</option>
                                    <option value="C4">C4</option>
                                    <option value="C5">C5</option>
                                </optgroup>
                                <optgroup label="D">
                                    <option value="D1">D1</option>
                                    <option value="D2">D2</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="D5">D5</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-lg-12 d-none" id="additional_batch_group">
                            <label>Pilih Batch Tambahan 1</label>
                            <select name="additional_batch[]" id="additional_batch" class="form-control">
                                <option value="">-- Pilih Batch --</option>
                            </select>
                            <input type="hidden" name="production_batch_id_leveling[]" id="po_id_leveling_1">
                        </div>

                        <div class="col-lg-6 d-none" id="additional_batch_group_2">
                            <label>Pilih Batch Tambahan 2</label>
                            <select name="additional_batch[]" id="additional_batch_2" class="form-control">
                                <option value="">-- Pilih Batch --</option>
                            </select>
                            <input type="hidden" name="production_batch_id_leveling[]" id="po_id_leveling_2">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="submit_generate" type="submit" class="btn btn-primary">Generate Ulang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function validateInput(input) {
                const value = input.value;

                // Jika ada titik, tampilkan peringatan
                if (value.includes('.')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format Salah!',
                        text: 'Gunakan tanda koma (,) untuk desimal, bukan titik (.)',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#3085d6'
                    });

                    // Ganti titik menjadi koma otomatis
                    input.value = value.replace(/\./g, ',');
                }
            }

            // Event listener untuk kedua input
            document.querySelectorAll('.comma-input').forEach(function(el) {
                el.addEventListener('input', function() {
                    validateInput(this);
                });
            });
        });

        const allBatches = JSON.parse('{!! addslashes(json_encode($batches)) !!}');
        const validGgasBatches = JSON.parse('{!! addslashes(json_encode($availableBatches)) !!}');

        // Isi select option hanya dengan batch yang valid
        function populateBatchOptions() {
            const $start = $('#batch_start');
            const $end = $('#batch_end');

            $start.empty();
            $end.empty();

            if (validGgasBatches.length === 0) {
                $start.append('<option disabled>Semua batch belum lolos GGAS</option>');
                $end.append('<option disabled>Semua batch belum lolos GGAS</option>');
                return;
            }

            validGgasBatches.forEach(batch => {
                $start.append(`<option value="${batch}">${batch}</option>`);
                $end.append(`<option value="${batch}">${batch}</option>`);
            });
        }

        populateBatchOptions();

        function printQR(id) {
            const content = document.getElementById(id).innerHTML;
            const win = window.open('', '', 'height=600,width=600');
            win.document.write('<html><head><title>Print QR</title>');
            win.document.write('<style>body{text-align:center; font-size:12px;}</style>');
            win.document.write('</head><body>');
            win.document.write(content);
            win.document.write('</body></html>');
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }

        $('form').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: "{{ route('blending.store') }}",
                method: "POST",
                data: formData,
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message
                    }).then(() => {
                        $('#inputModal').modal('hide');
                        // Optionally reload data or page
                        form[0].reset();
                        location.reload();
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const res = xhr.responseJSON;

                        let errors = res.errors;
                        let msg = res.message;

                        let errorMessages = msg;
                        if (errors) {
                            errorMessages = Object.values(errors).map(e => e.join(', ')).join('\n');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                }
            });
        });

        $(document).on('click', '.generate-revisi-btn', function() {
            let poId = $(this).data('po');
            let id_blending = $(this).data('id');
            let batch = $(this).data('batch');
            let disposition = $(this).data('disposition');

            //$('#modal_po_id').val(poId);
            $('#modal_id_blending').val(id_blending);
            $('#modal_batch').val(batch);
            $('#modal_additional_batch_po_id').val(''); // reset PO ID tambahan

            $.get('{{ url('/analis/productionbatch/processblending/get-last-revisi') }}', {
                production_batch_id: poId,
                batch_range: batch
            }, function(res) {
                //console.log(res);
                $('#modal_revisi_display').val(res.revisi);
                if (disposition === 'Leveling') {
                    $('#additional_batch_group, #additional_batch_group_2').removeClass('d-none');
                    $('#additional_batch').empty().append('<option value="">-- Pilih Batch --</option>');
                    $('#modal_po_id').val(poId);
                    $.get('{{ url('/analis/productionbatch/processblending/get-available-additional-batch') }}', {
                        production_batch_id: poId,
                        exclude_batch: batch
                    }, function(batchRes) {
                        console.log(batchRes);
                        batchRes.data.forEach(function(batchItem) {
                            let value = `${batchItem.batch_number}`;
                            $('#additional_batch,#additional_batch_2').append(
                                `<option data-id_additional_po="${batchItem.po_id}" value="${value}">Batch ${batchItem.batch_number} (PO ${batchItem.po_number})</option>`
                            );
                        });
                    });
                } else if (disposition === 'Jalan Bareng') {
                    $('#additional_batch_group').removeClass('d-none');
                    $('#additional_batch_group_2').addClass('d-none');
                    $('#po_id_leveling_2').val(''); // kosongkan batch 2
                    $('#additional_batch').empty().append('<option value="">-- Pilih Batch --</option>');
                    $('#modal_po_id').val(poId);
                    $.get('{{ url('/analis/productionbatch/processblending/get-jalan-bareng') }}', {
                        production_batch_id: poId
                    }, function(batchRes) {
                        console.log(batchRes);
                        let seen = new Set();

                        batchRes.data.forEach(function(batchItem) {
                            let value = `${batchItem.batch_range}-${batchItem.po_number}`;
                            if (!seen.has(value)) {
                                seen.add(value);
                                $('#additional_batch,#additional_batch_2').append(
                                    `<option data-id_additional_po="${batchItem.po_id}" value="${batchItem.batch_range}">Batch ${batchItem.batch_range} (PO ${batchItem.po_number})</option>`
                                );
                            }
                        });

                    });
                } else {
                    $('#additional_batch_group').addClass('d-none');
                    $('#additional_batch').empty();
                    $('#modal_po_id').val(poId);
                }

                $('#generateRevisiModal').modal('show');
            }).fail(function() {
                alert('Gagal mengambil data revisi.');
            });
        });

        // Saat user memilih additional_batch, simpan PO ID-nya di hidden input
        $('#additional_batch').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const selected = $(this).val(); // contoh: "4|3"
            const poId = selectedOption.data('id_additional_po');
            if (selected) {
                const [batch_number, po_id] = selected.split('|');
                $('#modal_additional_batch').val(batch_number);
                $('#modal_additional_batch_po_id').val(po_id);
                $('#id_leveling').val(poId);
                $('#po_id_leveling_1').val(poId || '');
            } else {
                $('#modal_additional_batch').val('');
                $('#modal_additional_batch_po_id').val('');
            }
        });

        $('#additional_batch_2').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const poId = selectedOption.data('id_additional_po');
            $('#po_id_leveling_2').val(poId || '');
        });
        $('#submit_generate').click(function(e) {
            e.preventDefault(); // cegah tombol submit reload page

            let form = $('#generateRevisiForm');
            let formData = form.serialize();

            $.post('{{ url('/analis/productionbatch/processblending/generate-revisi') }}', formData)
                .done(function(res) {
                    $('#generateRevisiModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "Revisi berhasil dibuat!"
                    }).then(function() {
                        location.reload();
                    });
                })
                .fail(function(err) {
                    const msg = err.responseJSON?.message || 'Unknown error';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + msg
                    });
                });
        });
    </script>

@endsection
