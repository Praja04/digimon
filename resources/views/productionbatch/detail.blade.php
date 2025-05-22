@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Product Details</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
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
                                        <div><a href="#" class="text-primary d-block">{{Session::get('username')}}</a></div>
                                        <div class="vr"></div>

                                        <div class="text-muted">Tanggal Produksi : <span class="text-body fw-medium">{{ $productionBatch->production_date }}</span></div>
                                        <div class="text-end">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal">
                                                Input GGA / GGAS
                                            </button>
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

                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">Generate Barcode :</h5>
                                <nav>
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab" href="#nav-speci" role="tab" aria-controls="nav-speci" aria-selected="true">GGA</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab" href="#nav-detail" role="tab" aria-controls="nav-detail" aria-selected="false">GGAS</a>
                                        </li>
                                    </ul>
                                </nav>
                                <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-speci" role="tabpanel" aria-labelledby="nav-speci-tab">
                                        <div class="table-responsive">

                                            @if($productionBatch->GgaProcesses->count() > 0)
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Batch Number</th>
                                                        <th>Dissolver</th>

                                                        <th>QR Code (URL)</th>
                                                        <th>Disposisi</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($productionBatch->GgaProcesses as $gga)
                                                    <tr>
                                                        <td>{{ $gga->batch_number }}</td>
                                                        <td>{{ $gga->dissolver_number }}</td>

                                                        <td>
                                                            <!-- Tombol untuk buka modal -->
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#qrModal{{ $gga->id }}">
                                                                QR Code {{ $gga->id }}
                                                            </button>

                                                            <!-- Modal Besar -->
                                                            <div class="modal fade" id="qrModal{{ $gga->id }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $gga->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header py-2">
                                                                            <h5 class="modal-title" id="qrModalLabel{{ $gga->id }}">QR Code - ID {{ $gga->id }}</h5>
                                                                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body text-center" id="qrPrintArea{{ $gga->id }}">
                                                                            <div style="display: inline-block;">
                                                                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('/analis/ggaggas/gga/id/' . $gga->id), 'QRCODE') }}" alt="QR Code">
                                                                            </div>
                                                                            <p>GGA/{{ $productionBatch->po_number }}/{{ $productionBatch->production_date }}/{{ $gga->batch_number }}</p>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-center py-2">
                                                                            <button onclick="printQR('qrPrintArea{{ $gga->id }}')" class="btn btn-sm btn-success">Print</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $gga->disposition }}
                                                            @if(in_array($gga->disposition, ['Adjustment', 'Resampling']) && $gga->revisi == null && $gga->not_standar == true )
                                                            <button class="btn btn-sm btn-warning generate-revisi-btn" data-id="{{ $gga->id }}" data-batch="{{ $gga->batch_number }}" data-po="{{ $gga->production_batch_id }}" data-dissolver="{{ $gga->dissolver_number }}">
                                                                ❗
                                                            </button>

                                                            @endif
                                                        </td>
                                                        <td>

                                                            @if($gga->revisi != null)
                                                             Revisi Ke-{{ $gga->revisi }}
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @else
                                            <p class="text-muted">Belum ada data GGA.</p>
                                            @endif



                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
                                        <div class="table-responsive">
                                            @if($productionBatch->GgasProcesses->count() > 0)
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Batch Number</th>
                                                        <th>Dissolver</th>

                                                        <th>QR Code (URL)</th>
                                                        <th>Disposisi</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($productionBatch->GgasProcesses as $ggas)
                                                    <tr>
                                                        <td>{{ $ggas->batch_number }}</td>
                                                        <td>{{ $ggas->dissolver_number }}</td>

                                                        <td>
                                                            <!-- Tombol untuk buka modal -->
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#qrModalGGAS{{ $ggas->id }}">
                                                                QR Code {{ $ggas->id }}
                                                            </button>

                                                            <!-- Modal Besar -->
                                                            <div class="modal fade" id="qrModalGGAS{{ $ggas->id }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $ggas->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header py-2">
                                                                            <h5 class="modal-title" id="qrModalLabel{{ $ggas->id }}">QR Code - ID {{ $ggas->id }}</h5>
                                                                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body text-center" id="qrPrintArea{{ $ggas->id }}">
                                                                            <div style="display: inline-block;">
                                                                                <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('/analis/ggaggas/gga/id/' . $ggas->id), 'QRCODE') }}" alt="QR Code">
                                                                            </div>
                                                                            <p>GGAS/{{ $productionBatch->po_number }}/{{ $productionBatch->production_date }}/{{ $ggas->batch_number }}</p>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-center py-2">
                                                                            <button onclick="printQR('qrPrintArea{{ $ggas->id }}')" class="btn btn-sm btn-success">Print</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $ggas->disposition }}
                                                            @if(in_array($ggas->disposition, ['Adjustment', 'Resampling']) && $ggas->revisi == null )
                                                            <button class="btn btn-sm btn-warning generate-revisi-btn-ggas" data-id="{{ $ggas->id }}" data-batch="{{ $ggas->batch_number }}" data-po="{{ $ggas->production_batch_id }}" data-dissolver="{{ $ggas->dissolver_number }}">
                                                                ❗
                                                            </button>

                                                            @endif
                                                        </td>
                                                        <td>

                                                            @if($ggas->revisi != null)
                                                             Revisi Ke-{{ $ggas->revisi }}
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @else
                                            <p class="text-muted">Belum ada data GGAS.</p>
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
                    <h5 class="modal-title">Input GGA / GGAS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="production_batch_id" value="{{ $productionBatch->id }}">
                    
                    <div class="mb-3">
                        <label for="batch_number" class="form-label">Batch Number</label>
                        <select name="batch_number" class="form-select" required>
                            @foreach($batches as $batch)
                            <option value="{{ $batch }}">{{ $batch }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mb-3">
                        <label for="dissolver_number" class="form-label">Dissolver Number</label>
                        <input type="text" name="dissolver_number" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Sample</label>
                        <select name="type" class="form-select" required>
                            <option value="GGA">GGA</option>
                            @if ($allCovered)
                            <option value="GGAS">GGAS</option>
                            @endif
                        </select>
                        @if (!$allCovered)
                        <small class="text-danger">* GGAS hanya bisa diinput setelah semua batch sudah masuk GGA</small>
                        @endif
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
    <div class="modal-dialog">
        <form id="generateRevisiForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Revisi Batch</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_old_gga" id="id_old_gga">
                    <input type="hidden" name="production_batch_id" id="modal_po_id">
                    <input type="hidden" name="dissolver_number" id="modal_dissolver_number">
                    <!-- <input type="hidden" name="revisi" id="modal_revisi" readonly> -->
                    <div class="mb-3">
                        <label>Batch</label>
                        <input type="text" class="form-control" id="modal_batch" name="batch_number" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Revisi Ke-</label>
                        <input type="text" class="form-control" id="modal_revisi_display" name="revisi_gga" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="submit_generate" type="submit" class="btn btn-primary">Generate Ulang</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="generateRevisiModalGGAS" tabindex="-1">
    <div class="modal-dialog">
        <form id="generateRevisiFormGGAS">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Revisi Batch</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_old_ggas" id="id_old_ggas">
                    <input type="hidden" name="production_batch_id_ggas" id="modal_po_id_ggas">
                    <input type="hidden" name="dissolver_number_ggas" id="modal_dissolver_number_ggas">
                    <!-- <input type="hidden" name="revisi_ggas" id="modal_revisi_ggas" readonly> -->
                    <div class="mb-3">
                        <label>Batch</label>
                        <input type="text" class="form-control" id="modal_batch_ggas" name="batch_number_ggas" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Revisi Ke-</label>
                        <input type="text" class="form-control" id="modal_revisi_display_ggas" name="revisi_ggas" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="submit_generate_ggas" type="submit" class="btn btn-primary">Generate Ulang</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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
            url: "{{ route('process.store') }}",
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
        let Id = $(this).data('id');
        let poId = $(this).data('po');
        let batch = $(this).data('batch');
        let dissolver = $(this).data('dissolver');

        $('#modal_po_id').val(poId);
        $('#id_old_gga').val(Id);
        $('#modal_batch').val(batch);
        $('#modal_dissolver_number').val(dissolver);

        // Ambil revisi terakhir via AJAX
        $.get('{{ url("/analis/productionbatch/processgga/get-last-revisi") }}', {
            production_batch_id: poId,
            batch_number: batch
        }, function(res) {
            $('#modal_revisi').val(res.revisi);
            $('#modal_revisi_display').val(res.revisi);
            $('#generateRevisiModal').modal('show');
        }).fail(function() {
            alert('Gagal mengambil data revisi.');
        });
    });

    $('#submit_generate').click(function(e) {
        e.preventDefault(); // cegah tombol submit reload page

        let form = $('#generateRevisiForm');
        let formData = form.serialize();

        $.post('{{ url("/analis/productionbatch/processgga/generate-revisi") }}', formData, function(res) {
            alert('Revisi berhasil dibuat!');
            location.reload();
        }).fail(function(err) {
            alert('Terjadi kesalahan: ' + (err.responseJSON?.message || 'Unknown error'));
        });
    });

    $(document).on('click', '.generate-revisi-btn-ggas', function() {
        let Id = $(this).data('id');
        let poId = $(this).data('po');
        let batch = $(this).data('batch');
        let dissolver = $(this).data('dissolver');

        $('#id_old_ggas').val(Id);
        $('#modal_po_id_ggas').val(poId);
        $('#modal_batch_ggas').val(batch);
        $('#modal_dissolver_number_ggas').val(dissolver);

        // Ambil revisi terakhir via AJAX
        $.get('{{ url("/analis/productionbatch/processggas/get-last-revisi") }}', {
            production_batch_id: poId,
            batch_number: batch
        }, function(res) {
            $('#modal_revisi_ggas').val(res.revisi);
            $('#modal_revisi_display_ggas').val(res.revisi);
            $('#generateRevisiModalGGAS').modal('show');
        }).fail(function() {
            alert('Gagal mengambil data revisi.');
        });
    });

    $('#submit_generate_ggas').click(function(e) {
        e.preventDefault(); // cegah tombol submit reload page

        let form = $('#generateRevisiFormGGAS');
        let formData = form.serialize();

        $.post('{{ url("/analis/productionbatch/processggas/generate-revisi") }}', formData, function(res) {
            alert('Revisi berhasil dibuat!');
            location.reload();
        }).fail(function(err) {
            alert('Terjadi kesalahan: ' + (err.responseJSON?.message || 'Unknown error'));
        });
    });
</script>

@endsection