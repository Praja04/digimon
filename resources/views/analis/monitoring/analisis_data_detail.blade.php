@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Input Monitoring Blending</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">QC</a></li>
                    <li class="breadcrumb-item active">Monitoring</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($monitoring)
<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row gx-lg-5">
                        <div class="col-xl-12">
                            <div class="mt-xl-0 mt-5">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h4>{{ $monitoring->ProductionBatch->po_number }} (Nomor PO)</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><a href="#" class="text-primary d-block">{{Session::get('username')}}</a></div>
                                            <div class="vr"></div>

                                            <div class="text-muted">Tanggal Produksi : <span class="text-body fw-medium">{{ $monitoring->ProductionBatch->production_date }}</span></div>
                                            <div class="text-end">
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModalDataAnalisa">
                                                    Input Data Analisa
                                                </button>
                                            </div>
                                            <div class="text-end">
                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#inputModalDataDisposisi">
                                                    Input Data Disposisi
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
                                                    <h5 class="mb-0">{{ $monitoring->ProductionBatch->variant }}</h5>
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
                                                    <h5 class="mb-0">{{ $monitoring->ProductionBatch->batch_range }}</h5>
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
                                                    <h5 class="mb-0">{{ $monitoring->ProductionBatch->storage }}</h5>
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
                                    <p>{{ $monitoring->ProductionBatch->description }}</p>
                                </div>

                                <div class="product-content mt-5">
                                    <h5 class="fs-14 mb-3">Data :</h5>
                                    <nav>
                                        <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab" href="#nav-speci" role="tab" aria-controls="nav-speci" aria-selected="true">Monitoring Blending</a>
                                            </li>

                                        </ul>
                                    </nav>
                                    <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-speci" role="tabpanel" aria-labelledby="nav-speci-tab">
                                            <div class="table-responsive">


                                                @if($monitoring->monitoringData->count() > 0)
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Shift</th>
                                                            <th>Brix</th>
                                                            <th>NaCl</th>
                                                            <th>BJ</th>
                                                            <th>Visco</th>
                                                            <th>AW</th>
                                                            <th>Buih</th>
                                                            <th>Organo</th>
                                                            <th>pH</th>
                                                            <th>Endapan</th>
                                                            <th>Warna</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($monitoring->monitoringData as $data)
                                                        <tr>
                                                            <td>{{ $data->shift }}</td>
                                                            <td>{{ $data->brix }}</td>
                                                            <td>{{ $data->nacl }}</td>
                                                            <td>{{ $data->bj }}</td>
                                                            <td>{{ $data->visco }}</td>
                                                            <td>{{ $data->aw }}</td>
                                                            <td>{{ $data->buih }}</td>
                                                            <td>{{ $data->organo }}</td>
                                                            <td>{{ $data->ph }}</td>
                                                            <td>{{ $data->endapan }}</td>
                                                            <td>{{ $data->warna }}</td>
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
</div>

<div class="modal fade" id="inputModalDataAnalisa" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-monitoring-input"> {{-- Perbaiki ID form --}}
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Analisa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> {{-- tombol silang --}}
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" id="monitoring_turun_blending_id" name="monitoring_turun_blending_id" value="{{ $monitoring->id }}">

                    <div class="col-md-4">
                        <label for="brix" class="form-label">Brix</label>
                        <input type="number" step="0.01" class="form-control" name="brix" id="brix" required>
                    </div>

                    <div class="col-md-4">
                        <label for="nacl" class="form-label">NaCl</label>
                        <input type="number" step="0.01" class="form-control" name="nacl" id="nacl" required>
                    </div>

                    <div class="col-md-4">
                        <label for="bj" class="form-label">BJ</label>
                        <input type="number" step="0.01" class="form-control" name="bj" id="bj" required>
                    </div>

                    <div class="col-md-4">
                        <label for="visco" class="form-label">Visco</label>
                        <input type="number" step="0.01" class="form-control" name="visco" id="visco">
                    </div>

                    <div class="col-md-4">
                        <label for="aw" class="form-label">AW</label>
                        <input type="number" step="0.01" class="form-control" name="aw" id="aw">
                    </div>

                    <div class="col-md-4">
                        <label for="buih" class="form-label">Buih</label>
                        <input type="number" step="0.01" class="form-control" name="buih" id="buih">
                    </div>

                    <div class="col-md-4">
                        <label for="organo" class="form-label">Organo</label>
                        <input type="text" class="form-control" name="organo" id="organo" oninput="this.value = this.value.toUpperCase();">
                    </div>

                    <div class="col-md-4">
                        <label for="ph" class="form-label">pH</label>
                        <input type="number" step="0.01" class="form-control" name="ph" id="ph">
                    </div>

                    <div class="col-md-4">
                        <label for="endapan" class="form-label">Endapan</label>
                        <input type="text" class="form-control" name="endapan" id="endapan" oninput="this.value = this.value.toUpperCase();">
                    </div>

                    <div class="col-md-4">
                        <label for="warna" class="form-label">Warna</label>
                        <!-- <input type="text" class="form-control" name="warna" id="warna" oninput="this.value = this.value.toUpperCase();"> -->
                        <select name="warna" id="warnaSelect" class="form-select" required>
                            <option value="">-- Pilih Warna --</option>
                        </select>

                    </div>

                    <div class="col-md-4">
                        <label for="shift" class="form-label">Shift</label>
                        <select class="form-select" name="shift" id="shift" required>
                            <option value="">-- Pilih Shift --</option>
                            <option value="1">Shift 1</option>
                            <option value="2">Shift 2</option>
                            <option value="3">Shift 3</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="inputModalDataDisposisi" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="turunblendingForm"> {{-- Perbaiki ID form --}}
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Disposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> {{-- tombol silang --}}
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" id="monitoring_id" name="monitoring_id" value="{{ $monitoring->id }}">
                    <div class="mb-3">
                        <label class="form-label">Disposition</label>
                        <select name="disposition" class="form-select disposition-select" required>
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
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="disposition_remarks" class="form-control" rows="2" placeholder="Isi remarks jika diperlukan..."></textarea>
                    </div>

                    <div class="mb-3 d-none adjustment-qty-wrapper">
                        <h6 class="form-label fw-bold">Adjustment Qty</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Air (Liter)</label>
                                <input type="number" step="0.01" name="adjustment_qty_air" class="form-control adjustment-qty" placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gula (Kg)</label>
                                <input type="number" step="0.01" name="adjustment_qty_gula" class="form-control adjustment-qty" placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Garam (Kg)</label>
                                <input type="number" step="0.01" name="adjustment_qty_garam" class="form-control adjustment-qty" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>
@else
<div class="alert alert-danger">
    Data Blending tidak ditemukan.
</div>
@endif
<script>
    $(document).ready(function() {
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


        $('#form-monitoring-input').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: "{{ url('analis/monitoring/blending/detail/data') }}", // Ganti sesuai route
                type: "POST",
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    // Optional loading
                },
                success: function(response) {
                    if (response.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#inputModalDataAnalisa').modal('hide'); // FIX: modal yang benar
                        form.trigger('reset');

                        // TODO: reload tabel data jika diperlukan
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        $.each(errors, function(key, value) {
                            errorMessages += `- ${value[0]}<br>`;
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            html: errorMessages,
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: xhr.responseJSON?.message || 'Unknown error',
                        });
                        console.error(xhr.responseJSON);
                    }
                }
            });
        });

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

        $('#turunblendingForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const alertBox = form.find('.error-alert');
            const submitBtn = form.find('button[type="submit"]');

            alertBox.addClass('d-none').empty();
            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{url('/analis/monitoring/blending/update/data')}}",
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#modalDisposisiMonitoring').modal('hide');
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