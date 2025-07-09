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
                                <label class="form-label">EB</label>
                                <input type="number" step="0.01" max="100" min="0" name="eb" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">TPC</label>
                                <input type="number" step="0.01" max="100" min="0" name="tpc" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">YM</label>
                                <input type="text" name="ym" class="form-control" required>
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
                url: "{{url('/foreman/blending/mikro/update')}}/" + id,
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

    });
</script>
@endsection