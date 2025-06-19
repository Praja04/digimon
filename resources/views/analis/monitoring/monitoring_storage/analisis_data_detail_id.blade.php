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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Input Monitoring Storage - Batch {{ $data->batch_range }}</h5>
            </div>
            <div class="card-body">
                <form class="form-data" data-id="{{ $data->id }}">
                    @csrf
                    <input type="hidden" name="url" id="url" value="{{ $data->production_batch_id }}">
                    <div class="alert alert-danger d-none error-alert"></div>

                    <div class="mb-3">
                        <label class="form-label">BRIX</label>
                        <input type="number" step="0.01" max="100" min="0" name="brix" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NACL</label>
                        <input type="number" step="0.01" max="100" min="0" name="nacl" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bj</label>
                        <input type="text" name="bj" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visco</label>
                        <input type="text" name="visco" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Aw</label>
                        <input type="text" name="aw" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Buih</label>
                        <input type="text" name="buih" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PH</label>
                        <input type="text" name="ph" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Organo</label>
                        <input type="text" name="organo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Endapan</label>
                        <input type="text" name="endapan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control" required>
                    </div>
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
                        <label class="form-label">Adjustment Qty</label>
                        <input type="number" name="adjustment_qty" class="form-control adjustment-qty">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-danger">
    Data Monitoring Storage tidak ditemukan.
</div>
@endif

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // Show/hide adjustment qty saat ganti disposition
        $('.disposition-select').on('change', function() {
            const selected = $(this).val();
            const qtyWrapper = $('.adjustment-qty-wrapper');
            const qtyInput = $('.adjustment-qty');

            if (selected === 'Adjustment') {
                qtyWrapper.removeClass('d-none');
                qtyInput.prop('required', true);
            } else {
                qtyWrapper.addClass('d-none');
                qtyInput.prop('required', false).val('');
            }
        });



        $('.form-data').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = $('#url').val();
            var id = form.data('id');
            var alertBox = form.find('.error-alert');
            var submitBtn = form.find('button[type="submit"]');

            alertBox.addClass('d-none').empty();
            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{url('analis/monitoring/storage/update/data')}}/" + id,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ url('/analis/monitoring/storage/detail') }}/" + url;
                    });
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors || ['Terjadi kesalahan.'];
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan!',
                        html: errors.join('<br>'),
                    });

                    // Tunggu 3 detik sebelum redirect
                    setTimeout(() => {
                        window.location.href = "{{ url('/analis/monitoring/storage/detail') }}/" + url;
                    }, 3000);

                    submitBtn.prop('disabled', false).text('Simpan');
                }


            });
        });
    });
</script>
@endsection