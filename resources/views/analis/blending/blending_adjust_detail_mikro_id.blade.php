@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Input Blending After Adjustment</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">QC</a></li>
                    <li class="breadcrumb-item active">Blending</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($blending)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row gx-lg-5">
                    <div class="col-xl-12">
                        <div class="mt-xl-0 mt-5">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h4>{{ $blending->productionBatch->po_number }} (Nomor PO)</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block">{{Session::get('username')}}</a></div>
                                        <div class="vr"></div>

                                        <div class="text-muted">Tanggal Produksi : <span class="text-body fw-medium">{{ $blending->productionBatch->production_date }}</span></div>


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
                                                <h5 class="mb-0">{{ $blending->productionBatch->variant }}</h5>
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
                                                <h5 class="mb-0">{{ $blending->productionBatch->batch_range }}</h5>
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
                                                <h5 class="mb-0">{{ $blending->productionBatch->storage }}</h5>
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
                                <p>{{ $blending->productionBatch->description }}</p>
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
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Input Blending - Batch {{ $blending->batch_range }}</h5>
            </div>
            <div class="card-body">
                <form id="blendingForm" class="ajax-gga-form" data-id="{{ $blending->id }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Input Data Blending After Adjustment</h5>

                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger d-none error-alert"></div>
                            <input type="hidden" name="id" id="id">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama_analis" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shift</label>
                                <select name="shift" id="shift" class="form-select">
                                    <option value="shift 1"> Shift 1</option>
                                    <option value="shift 2"> Shift 2</option>
                                    <option value="shift 3"> Shift 3</option>
                                </select>
                            </div>
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
                                <input type="number" step="0.01" max="100" min="0" name="eb" id="ebInput" class="form-control">
                            </div>
                            <div id="tpcContainer" class="mb-3 d-none">
                                <label class="form-label">TPC</label>
                                <input type="number" step="0.01" max="100" min="0" name="tpc" id="tpcInput" class="form-control">
                            </div>
                            <div id="ymContainer" class="mb-3 d-none">
                                <label class="form-label">YM</label>
                                <input type="text" name="ym" id="ymInput" class="form-control">
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-danger">
    Data Blending tidak ditemukan.
</div>
@endif

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
        $('.open-blending-modal').on('click', function() {

            $('#id').val(selectedId);
            $('#inputBlendingModal').modal('show');
        });


        // Reset form saat modal dibuka
        $('#inputBlendingModal').on('shown.bs.modal', function() {
            $('#blendingForm')[0].reset();

            $('.error-alert').addClass('d-none').html('');
        });

        // Submit form
        $('#blendingForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const alertBox = form.find('.error-alert');
            const submitBtn = form.find('button[type="submit"]');
            var id = form.data('id');
            alertBox.addClass('d-none').empty();
            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{url('/analis/blending/mikro/update')}}/" + id,
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
                    const errorData = xhr.responseJSON || {};
                    const errors = [];

                    if (Array.isArray(errorData.errors)) {
                        errors.push(...errorData.errors);
                    }

                    if (typeof errorData.error === 'string') {
                        errors.push(errorData.error);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan!',
                        html: errors.length ? errors.join('<br>') : 'Terjadi kesalahan.'
                    });

                    submitBtn.prop('disabled', false).text('Simpan');
                }
            });
        });

    });
</script>
@endsection