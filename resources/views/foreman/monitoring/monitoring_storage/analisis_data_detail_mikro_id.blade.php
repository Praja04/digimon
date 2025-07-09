@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Input Monitoring Storage</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">QC</a></li>
                    <li class="breadcrumb-item active">Monitoring</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($data)
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        {{ $data->production_batch_id }}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Input Blending - Batch {{ $data->batch_range }}</h5>
            </div>
            <div class="card-body">
                <form id="monitoringForm" class="ajax-form" data-id="{{ $data->id }}">
                    @csrf
                    <input type="hidden" name="url" id="url" value="{{ $data->production_batch_id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Input Data Monitoring Storage</h5>

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




        // Submit form
        $('#monitoringForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const alertBox = form.find('.error-alert');
            const submitBtn = form.find('button[type="submit"]');
            var id = form.data('id');
            var url = $('#url').val();
            alertBox.addClass('d-none').empty();
            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{url('/foreman/monitoring/storage/update/data/mikro')}}/" + id,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan.'
                    }).then(() => window.location.href = "{{ url('/foreman/monitoring/storage/detail/mikro/') }}/" + url);
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