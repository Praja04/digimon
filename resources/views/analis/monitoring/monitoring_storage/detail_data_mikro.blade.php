@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Monitoring Storage</h4>

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
                        <table class="table align-middle table-nowrap mb-0 text-center" id="tasksTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>Nomor PO</th>
                                    <th>Batch Range</th>
                                    <th>No Blending</th>
                                    <th>Volume</th>
                                    <th>EB</th>
                                    <th>TPC</th>
                                    <th>YM</th>

                                    <th>Action</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @if ($productionBatch && $productionBatch->MonitoringStorageMikro)
                                    @foreach ($productionBatch->MonitoringStorageMikro as $data)
                                        <tr>
                                            <td>{{ $productionBatch->po_number }}</td>
                                            <td>{{ $data->batch_range }}</td>
                                            <td>{{ $data->nomor_blending }}</td>
                                            <td>{{ $data->volume_blending }}</td>
                                            <td>{{ $data->eb ?? '-' }}</td>
                                            <td>{{ $data->tpc ?? '-' }}</td>
                                            <td>{{ $data->ym ?? '-' }}</td>
                                            <td>
                                                @if (is_null($data->ym))
                                                    <button class="btn btn-sm btn-primary open-modal"
                                                        data-id="{{ $data->id }}">Input Analisa Monitoring
                                                        Storage</button>
                                                @else
                                                    <span class="text-muted">✓ Lengkap</span>
                                                @endif
                                            </td>
                                            <td>{{ $data->hasil ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10">Tidak ada data blending untuk batch ini.</td>
                                    </tr>
                                @endif
                                <!-- Modal input GGA tunggal -->
                                <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="monitoring_form" class="ajax-gga-form">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Input Data Monitoring Storage</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none error-alert"></div>
                                                    <input type="hidden" name="id" id="id">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pilih Parameter</label>
                                                        <select id="parameterSelector" class="form-select">
                                                            <option value="">-- Pilih --</option>
                                                            <option value="eb">EB</option>
                                                            <option value="tpc">TPC</option>
                                                            <option value="ym">YM</option>
                                                        </select>
                                                    </div>

                                                    <div id="ebContainer" class="mb-3 d-none">
                                                        <label class="form-label">EB</label>
                                                        <input type="number" step="0.01" max="100"
                                                            min="0" name="eb" id="ebInput"
                                                            class="form-control">
                                                    </div>
                                                    <div id="tpcContainer" class="mb-3 d-none">
                                                        <label class="form-label">TPC</label>
                                                        <input type="number" step="0.01" max="100"
                                                            min="0" name="tpc" id="tpcInput"
                                                            class="form-control">
                                                    </div>
                                                    <div id="ymContainer" class="mb-3 d-none">
                                                        <label class="form-label">YM</label>
                                                        <input type="number" step="0.01" name="ym"
                                                            id="ymInput" class="form-control">
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
        $('#parameterSelector').on('change', function() {
            const selected = $(this).val();

            // Sembunyikan semua
            $('#ebContainer, #tpcContainer, #ymContainer').addClass('d-none');

            // Tampilkan sesuai pilihan
            if (selected === 'eb') $('#ebContainer').removeClass('d-none');
            if (selected === 'tpc') $('#tpcContainer').removeClass('d-none');
            if (selected === 'ym') $('#ymContainer').removeClass('d-none');
        });
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let selectedId = null;

            // Ketika tombol diklik, simpan ID dan buka modal
            $('.open-modal').on('click', function() {
                selectedId = $(this).data('id');
                $('#id').val(selectedId);
                $('#inputModal').modal('show');
            });

            // Show/hide adjustment qty saat ganti disposition


            // Reset form saat modal dibuka
            $('#inputModal').on('shown.bs.modal', function() {
                $('#monitoring_form')[0].reset();
                $('.disposition-select').trigger('change');
                $('.error-alert').addClass('d-none').html('');
            });

            // Submit form
            $('#monitoring_form').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const alertBox = form.find('.error-alert');
                const submitBtn = form.find('button[type="submit"]');

                alertBox.addClass('d-none').empty();
                submitBtn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ url('/analis/monitoring/storage/update/data/mikro') }}/" + selectedId,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#inputModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil disimpan.'
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        let errors = [];

                        if (xhr.responseJSON) {
                            // Jika ada array error dari validasi
                            if (xhr.responseJSON.errors) {
                                errors = xhr.responseJSON.errors;
                            }
                            // Jika ada satu pesan error khusus
                            else if (xhr.responseJSON.error) {
                                errors = [xhr.responseJSON.error];
                            } else {
                                errors = ['Terjadi kesalahan yang tidak diketahui.'];
                            }
                        } else {
                            errors = ['Gagal terhubung ke server.'];
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyimpan!',
                            html: errors.join('<br>')
                        });

                        submitBtn.prop('disabled', false).text('Simpan');
                    }
                });
            });
        });
    </script>



@endsection
