@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Monitoring Turun Blending</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                    <li class="breadcrumb-item active">Analis</li>
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
                <h4 class="fs-16 mb-1">Selamat Datang, {{Session::get('username')}}!</h4>
                <p class="text-muted mb-0">Mari tingkatkan kualitas agar menjadi perusahan makanan kelas dunia.</p>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input id="date-picker" type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-auto">
                            <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none"><i class="ri-pulse-line"></i></button>
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
                                        <div><a href="#" class="text-primary d-block">{{Session::get('username')}}</a></div>
                                        <div class="vr"></div>

                                        <div class="text-muted">Tanggal Produksi : <span class="text-body fw-medium">{{ $productionBatch->production_date }}</span></div>


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
                    <h5 class="card-title mb-0 flex-grow-1">Detail Data</h5>
                </div>
            </div>
            <!--end card-body-->
            <div class="card-body">
                <div class="table-responsive table-card mb-4">
                    <table class="table align-middle table-nowrap mb-0 text-center" id="tasksTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>PO</th>
                                <th>Batch</th>
                                <th>Nomor Blending</th>
                                <th>Input Data</th>
                                <th>Input Disposisi</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            @foreach ($filteredMonitoring as $data)
                            <tr>
                                <td>{{ $data->po_number }}</td>
                                <td>
                                    @if($data->revisi != null)
                                    {{ $data->batch_range }} ❗

                                    @else
                                    {{ $data->batch_range }}
                                    @endif

                                    @if($data->additional_batch_info)

                                    @foreach($data->additional_batch_info as $relasi)
                                    <span class="badge bg-info">{{ $relasi->batch }}</span>
                                    @endforeach
                                    @else

                                    @endif
                                </td>
                                <td>{{ $data->nomor_blending }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-lihat" data-id="{{ $data->id }}">
                                        <i class="fas fa-eye"></i> Lihat Data
                                    </button>
                                    @if (($data->data_count) < 3) <button class="btn btn-sm btn-success btn-input" data-id="{{ $data->id }}">
                                        <i class="fas fa-edit"></i> Input Data
                                        </button>
                                        @else
                                        <span class="text-muted"></span>
                                        @endif
                                </td>
                                <td>
                                    @if (is_null($data->disposition))
                                    <button class="btn btn-sm btn-warning btn-input-disposisi" data-id="{{ $data->id }}">
                                        <i class="fas fa-edit"></i> Input Disposisi
                                    </button>
                                    @else
                                    <span class="text-muted">{{ $data->disposition }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!--end table-->
                    <div class="modal fade" id="modalMonitoring" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="modal-content-monitoring">
                                <!-- Konten dari AJAX dimuat di sini -->
                                <form id="form-monitoring-input" action="{{ url('/monitoring-blending/' . $data->id) }}" method="POST">
                                    @csrf
                                    @method('POST') {{-- Atau gunakan PUT jika kamu pakai update --}}
                                    <div class="modal-header">
                                        <h5 class="modal-title">Input Monitoring Blending</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body row g-3">
                                        <input type="hidden" id="monitoring_turun_blending_id" name="monitoring_turun_blending_id">

                                        <div class="col-md-4">
                                            <label for="brix" class="form-label">Brix</label>
                                            <input type="number" class="form-control" name="brix" id="brix" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nacl" class="form-label">NaCl</label>
                                            <input type="number" class="form-control" name="nacl" id="nacl" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="bj" class="form-label">BJ</label>
                                            <input type="number" class="form-control" name="bj" id="bj" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="visco" class="form-label">Visco</label>
                                            <input type="number" class="form-control" name="visco" id="visco">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="aw" class="form-label">AW</label>
                                            <input type="number" class="form-control" name="aw" id="aw">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="buih" class="form-label">Buih</label>
                                            <input type="number" class="form-control" name="buih" id="buih">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="organo" class="form-label">Organo</label>
                                            <input type="text" class="form-control" name="organo" id="organo" oninput="this.value = this.value.toUpperCase();">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="ph" class="form-label">pH</label>
                                            <input type="number" class="form-control" name="ph" id="ph">
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
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalDataMonitoring" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content" id="modal-data-monitoring">
                                <!-- Akan diisi dari AJAX -->
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalDisposisiMonitoring" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content" id="modal-disposisi-monitoring">
                                <form id="turunblendingForm">
                                    @csrf
                                    @method('POST') {{-- Atau gunakan PUT jika kamu pakai update --}}
                                    <div class="modal-header">
                                        <h5 class="modal-title">Input Disposisi Monitoring Blending</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body row g-3">
                                        <input type="hidden" id="monitoring_id" name="monitoring_id">
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
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </form>
                            </div>
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


        // Tombol Lihat
        $('.btn-lihat').on('click', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "{{url('analis/monitoring/blending/detail/data')}}" + '/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let html = `
                <div class="modal-header">
                    <h5 class="modal-title">Detail Monitoring Blending</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Batch Range:</strong> ${response.monitoring.batch_range}</p>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-sm">
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
                            <tbody>`;

                    if (response.dataAnalisa.length > 0) {
                        response.dataAnalisa.forEach(function(data) {
                            html += `
                        <tr>
                            <td>${data.shift ?? '-'}</td>
                            <td>${data.brix ?? '-'}</td>
                            <td>${data.nacl ?? '-'}</td>
                            <td>${data.bj ?? '-'}</td>
                            <td>${data.visco ?? '-'}</td>
                            <td>${data.aw ?? '-'}</td>
                            <td>${data.buih ?? '-'}</td>
                            <td>${data.organo ?? '-'}</td>
                            <td>${data.ph ?? '-'}</td>
                            <td>${data.endapan ?? '-'}</td>
                            <td>${data.warna ?? '-'}</td>
                        </tr>`;
                        });
                    } else {
                        html += `<tr><td colspan="11" class="text-center">Belum ada data analisa</td></tr>`;
                    }

                    html += `
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>`;

                    $('#modal-data-monitoring').html(html);
                    $('#modalDataMonitoring').modal('show');
                },
                error: function() {
                    alert('Gagal memuat data.');
                }
            });
        });



        // Tombol Input
        $('.btn-input').on('click', function() {
            var id = $(this).data('id');
            $('#monitoring_turun_blending_id').val(id);
            $('#modalMonitoring').modal('show');
        });

        $('#form-monitoring-input').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: "{{ url('analis/monitoring/blending/detail/data') }}", // Pastikan sesuai dengan route-mu
                type: "POST",
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    // Opsional: tampilkan loading spinner
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

                        $('#modalMonitoring').modal('hide');
                        form.trigger('reset');
                        // Optional: reload tabel atau data
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validasi gagal
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
                        // Error server 500 atau lainnya
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

        $('.btn-input-disposisi').on('click', function() {
            var id = $(this).data('id');
            $('#monitoring_id').val(id);
            $('#modalDisposisiMonitoring').modal('show');
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