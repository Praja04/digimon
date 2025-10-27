@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Monitoring Storage Before Use</h4>

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
                                        <h4>{{ $monitoringStorageBeforeUse->productionBatch->po_number }} (Nomor PO)</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><a href="#"
                                                    class="text-primary d-block">{{ Session::get('username') }}</a></div>
                                            <div class="vr"></div>

                                            <div class="text-muted">Tanggal Produksi : <span
                                                    class="text-body fw-medium">{{ $monitoringStorageBeforeUse->productionBatch->production_date }}</span>
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
                                                    <h5 class="mb-0">{{ $monitoringStorageBeforeUse->variant }}</h5>
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
                                                    <h5 class="mb-0">{{ $monitoringStorageBeforeUse->batch_range }}</h5>
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
                                                    <h5 class="mb-0">{{ $monitoringStorageBeforeUse->storage }}</h5>
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
                                    <p>{{ $monitoringStorageBeforeUse->description }}</p>
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
                <div class="card-body">
                    <form id="monitoring_form" class="ajax-gga-form">
                        @csrf

                        <div class="row g-3">
                            <!-- Alert Error -->
                            <div class="alert alert-danger d-none error-alert"></div>

                            <!-- Hidden ID -->
                            <input type="hidden" name="id" id="id"
                                value="{{ $monitoringStorageBeforeUse->id }}">

                            <!-- Input Visco -->
                            <div class="col-lg-12">
                                <label for="visco" class="form-label fw-semibold">Visco</label>
                                <input type="text" name="visco" id="visco" step="0.01"
                                    class="form-control comma-input" placeholder="Contoh: 2,75" required>
                            </div>

                            <!-- Input Brix -->
                            <div class="col-lg-12">
                                <label for="brix" class="form-label fw-semibold">Brix</label>
                                <input type="text" name="brix" id="brix" step="0.01"
                                    class="form-control comma-input" placeholder="Contoh: 2,75" required>
                            </div>

                            <!-- Input Aw -->
                            <div class="col-lg-12">
                                <label for="aw" class="form-label fw-semibold">Aw</label>
                                <input type="text" name="aw" id="aw" step="0.01"
                                    class="form-control comma-input" placeholder="Contoh: 2,75" required>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-secondary px-4">Batal</button>
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    <!--end row-->
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

                const id = {{ $monitoringStorageBeforeUse->productionBatch->id ?? 'null' }};

                $.ajax({
                    url: "{{ url('/analis/monitoring/storage/update/data/before-use') }}/" +
                        selectedId,
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil disimpan.'
                        }).then(() => {
                            if (id) {
                                // Arahkan ke route analis.monitoring_storage_before_use.show dengan ID
                                window.location.href =
                                    "{{ route('analis.monitoring_storage_before_use.show', ':id') }}"
                                    .replace(':id', id);
                            } else {
                                // Jika ID tidak tersedia, fallback reload
                                location.reload();
                            }
                        });
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
