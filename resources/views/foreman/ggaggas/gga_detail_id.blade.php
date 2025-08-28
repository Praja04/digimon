@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Input GGA</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">QC</a></li>
                    <li class="breadcrumb-item active">GGA</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($gga)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row gx-lg-5">
                    <div class="col-xl-12">
                        <div class="mt-xl-0 mt-5">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h4>{{ $ggas->productionBatch->po_number }} (Nomor PO)</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block">{{Session::get('username')}}</a></div>
                                        <div class="vr"></div>

                                        <div class="text-muted">Tanggal Produksi : <span class="text-body fw-medium">{{ $ggas->productionBatch->production_date }}</span></div>


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
                                                <h5 class="mb-0">{{ $ggas->productionBatch->variant }}</h5>
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
                                                <h5 class="mb-0">{{ $ggas->productionBatch->batch_range }}</h5>
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
                                                <h5 class="mb-0">{{ $ggas->productionBatch->storage }}</h5>
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
                                <p>{{ $ggas->productionBatch->description }}</p>
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
                <h5 class="card-title mb-0">Form Input GGA - Batch {{ $gga->batch_range }}</h5>
            </div>
            <div class="card-body">
                <form class="ajax-gga-form" data-id="{{ $gga->id }}">
                    @csrf
                    <input type="hidden" name="url" id="url" value="{{ $gga->production_batch_id }}">
                    <div class="alert alert-danger d-none error-alert"></div>

                    <div class="mb-3">
                        <label for="brix" class="form-label">BRIX</label>
                        <input type="number" step="0.01" max="100" min="0" name="brix" id="brix" class="form-control" required value="{{ old('brix', $gga->brix) }}">
                    </div>

                    <div class="mb-3">
                        <label for="nacl" class="form-label">NACL</label>
                        <input type="number" step="0.01" max="100" min="0" name="nacl" id="nacl" class="form-control" required value="{{ old('nacl', $gga->nacl) }}">
                    </div>

                    <div class="mb-3">
                        <label for="warna" class="form-label">Warna</label>
                        <input type="text" name="warna" id="warna" class="form-control" required value="{{ old('warna', $gga->warna) }}">
                       
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
    Data GGA tidak ditemukan.
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
        $('.open-ggas-modal').on('click', function() {
            selectedId = $(this).data('id');
            $('#inputGgasModal').modal('show');
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

        // Reset form saat modal dibuka
        $('#inputGgasModal').on('shown.bs.modal', function() {
            $('#ggasForm')[0].reset();
            $('.disposition-select').trigger('change');
            $('.error-alert').addClass('d-none').html('');
        });

        $('.ajax-gga-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = $('#url').val();
            var id = form.data('id');
            var alertBox = form.find('.error-alert');
            var submitBtn = form.find('button[type="submit"]');

            alertBox.addClass('d-none').empty();
            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{ url('foreman/ggaggas/gga/update-ajax') }}/" + id,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ url('foreman/ggaggas/gga') }}/" + url;
                    });
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