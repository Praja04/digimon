@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Monitoring Storage Before Use</a></li>
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
                                                    Input Monitoring Storage Before Use
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
                                                    aria-selected="true">Monitoring Storage Before Use</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-speci" role="tabpanel"
                                            aria-labelledby="nav-speci-tab">
                                            <div class="table-responsive">

                                                @if ($productionBatch->MonitoringStorageBeforeUse->count() > 0)
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Batch Range</th>
                                                                <th>QR Code (URL)</th>
                                                                <th>Storage</th>
                                                                <th>Jenis Sample</th>
                                                                <th>Hasil</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($productionBatch->MonitoringStorageBeforeUse as $row)
                                                                <tr>
                                                                    <td>{{ $row->batch_range }}
                                                                        @if ($row->has_relation == true)
                                                                            <span class="badge bg-info ms-2">
                                                                                -{{ $row->related_batches }}
                                                                                {{ $row->additional_batches }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <!-- Tombol untuk buka modal -->
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#qrModal{{ $row->id }}">
                                                                            QR Code {{ $row->id }}
                                                                        </button>

                                                                        <!-- Modal Besar -->
                                                                        <div class="modal fade"
                                                                            id="qrModal{{ $row->id }}" tabindex="-1"
                                                                            aria-labelledby="qrModalLabel{{ $row->id }}"
                                                                            aria-hidden="true">
                                                                            <div
                                                                                class="modal-dialog modal-dialog-centered modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header py-2">
                                                                                        <h5 class="modal-title"
                                                                                            id="qrModalLabel{{ $row->id }}">
                                                                                            QR Code - Monitoring Storage
                                                                                            Before Use
                                                                                            {{ $row->id }}</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close btn-sm"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body text-center"
                                                                                        id="qrPrintArea{{ $row->id }}">
                                                                                        <div style="display: inline-block;">
                                                                                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(route('analis.monitoring_storage_before_use.show_batch', $row->id), 'QRCODE') }}"
                                                                                                alt="QR Code">
                                                                                        </div>
                                                                                        <p>
                                                                                            Monitoring Storage Before
                                                                                            Use/{{ $productionBatch->po_number }}/{{ $productionBatch->production_date }}/{{ $row->batch_range }}
                                                                                        </p>
                                                                                    </div>
                                                                                    <div
                                                                                        class="modal-footer justify-content-center py-2">
                                                                                        <button
                                                                                            onclick="printQR('qrPrintArea{{ $row->id }}')"
                                                                                            class="btn btn-sm btn-success">Print</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-secondary"
                                                                                            data-bs-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $row->storage ?? '-' }}</td>
                                                                    <td>{{ $row->jenis_sample ?? '-' }}</td>
                                                                    <td>
                                                                        @if ($row->hasil === 'OK')
                                                                            <span class="badge bg-success">OK</span>
                                                                        @elseif ($row->hasil === 'NOT OK')
                                                                            <span class="badge bg-danger">NOT OK</span>
                                                                        @elseif ($row->hasil === 'PENDING')
                                                                            <span
                                                                                class="badge bg-warning text-dark">PENDING</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">-</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p class="text-muted">Belum ada data Monitoring.</p>
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
        <div class="modal-dialog modal-lg">
            <form id="inputMonitoringForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Input Monitoring Pasteurisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" name="production_batch_id" value="{{ $productionBatch->id }}">

                        <div class="col-lg-12">
                            <label for="batch" class="form-label">Batch</label>
                            <select name="batch" class="form-select" id="batch_start" required></select>
                        </div>

                        <div class="col-lg-6">
                            <label for="no_blending" class="form-label">Nomor Blending</label>
                            <input type="number" name="no_blending" class="form-control">
                        </div>

                        <div class="col-lg-6">
                            <label for="volume" class="form-label">Volume</label>
                            <input type="string" name="volume" class="form-control comma-input"
                                placeholder="Contoh: 2,75">
                        </div>

                        <div class="col-lg-12">
                            <label for="storage" class="form-label">Storage</label>
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

                        <div class="col-lg-12">
                            <label for="jenis_sample" class="form-label">Jenis
                                Sample</label>
                            <select id="jenis_sample" name="jenis_sample" class="form-select" required>
                                <option value="">-- Pilih Jenis Sample --</option>
                                <option value="Before Tiban">Before Tiban</option>
                                <option value="Flushing">Flushing</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Waktu Sample</label>
                            <input type="datetime-local" name="waktu_sample" id="waktu_sample" class="form-control"
                                required value="{{ old('waktu_sample', now()->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Waktu Selesai Pemakaian</label>
                            <input type="datetime-local" name="waktu_selesai_pemakaian" id="waktu_selesai_pemakaian"
                                class="form-control" required>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label">Estimasi Kadaluarsa</label>
                            <input type="datetime-local" name="estimasi_kadaluarsa" class="form-control" required
                                value="{{ old('estimasi_kadaluarsa', now()->format('Y-m-d\TH:i')) }}">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
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

        const allBatches = @json($filteredBatchGroups);
        const validGgasBatches = @json($filteredBatchGroups);

        // Isi select option hanya dengan batch yang valid
        function populateBatchOptions() {
            const $start = $('#batch_start');
            $start.empty();

            if (!validGgasBatches || validGgasBatches.length === 0) {
                $start.append('<option disabled>Belum ada Batch yang lolos blending(Release)</option>');
                return;
            }

            validGgasBatches.forEach(batch => {
                $start.append(`<option value="${batch}">${batch}</option>`);
            });

            console.log('Options added:', $start.find('option').length);
        }

        // Panggil saat document ready
        $(document).ready(function() {
            populateBatchOptions();
        });

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

        $('#inputMonitoringForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: "{{ route('analis.monitoring_storage_before_use.store') }}",
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
                    }
                }
            });
        });

        $(document).on('click', '.generate-revisi-btn', function() {
            let poId = $(this).data('po');
            let id_blending = $(this).data('id');
            let batch = $(this).data('batch');
            let disposition = $(this).data('disposition');

            $('#modal_po_id').val(poId);
            $('#modal_id_blending').val(id_blending);
            $('#modal_batch').val(batch);
            $('#modal_additional_batch_po_id').val(''); // reset PO ID tambahan

            $.get('{{ url('/analis/productionbatch/processmonitoringpasteurisasi/get-last-revisi') }}', {
                production_batch_id: poId,
                batch_range: batch
            }, function(res) {
                $('#modal_revisi_display').val(res.data?.revisi ? parseInt(res.data.revisi) + 1 : 1);
                if (disposition === 'Leveling') {
                    $('#additional_batch_group').removeClass('d-none');
                    $('#additional_batch').empty().append('<option value="">-- Pilih Batch --</option>');
                    $.get('{{ url('/analis/productionbatch/processmonitoringpasteurisasi/get-available-additional-batch') }}', {
                        production_batch_id: poId,
                        batch_range: batch
                    }, function(batchRes) {
                        console.log(batchRes);
                        batchRes.data.forEach(function(batchItem) {
                            let value = `${batchItem.batch_range}`;
                            $('#additional_batch').append(
                                `<option value="${value}">Batch ${batchItem.batch_range} (PO ${batchItem.po_number})</option>`
                            );
                        });
                    });

                } else if (disposition === 'Jalan Bareng') {
                    $('#additional_batch_group').removeClass('d-none');
                    $('#additional_batch').empty().append('<option value="">-- Pilih Batch --</option>');

                    $.get('{{ url('/analis/productionbatch/processmonitoringpasteurisasi/get-jalan-bareng') }}', {
                        production_batch_id: poId,
                        exclude_batch: batch
                    }, function(batchRes) {
                        console.log(batchRes);
                        batchRes.data.forEach(function(batchItem) {
                            let value = `${batchItem.batch_range}`;
                            $('#additional_batch').append(
                                `<option value="${value}">Batch ${batchItem.batch_range} (PO ${batchItem.po_number})</option>`
                            );
                        });
                    });
                } else {
                    $('#additional_batch_group').addClass('d-none');
                    $('#additional_batch').empty();
                }

                $('#generateRevisiModal').modal('show');
            }).fail(function() {
                alert('Gagal mengambil data revisi.');
            });
        });

        // Saat user memilih additional_batch, simpan PO ID-nya di hidden input
        $('#additional_batch').on('change', function() {
            const selected = $(this).val(); // contoh: "4|3"
            if (selected) {
                const [batch_number, po_id] = selected.split('|');
                $('#modal_additional_batch').val(batch_number);
                $('#modal_additional_batch_po_id').val(po_id);
            } else {
                $('#modal_additional_batch').val('');
                $('#modal_additional_batch_po_id').val('');
            }
        });


        $('#submit_generate').click(function(e) {
            e.preventDefault(); // cegah tombol submit reload page

            let form = $('#generateRevisiForm');
            let formData = form.serialize();

            $.post('{{ url('/analis/productionbatch/processmonitoringpasteurisasi/generate-revisi') }}', formData)
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
