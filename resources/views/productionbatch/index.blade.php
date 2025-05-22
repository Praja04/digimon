@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Persiapan Masak</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Analis</a></li>
                    <li class="breadcrumb-item active">QC</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="orderList">
            <div class="card-header border-0">
                <div class="row align-items-center gy-3">
                    <div class="col-sm">
                        <h5 class="card-title mb-0">Data PO</h5>
                    </div>

                </div>
            </div>

            <div class="card-body pt-0">
                <div>
                    <form id="form_input_po" class="tablelist-form">
                        @csrf
                        <div class="mb-3">
                            <label for="po_number" class="form-label">PO</label>
                            <input type="text" name="po_number" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="variant" class="form-label">Variant</label>
                            <input type="text" name="variant" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="production_date" class="form-label">Tanggal Produksi</label>
                            <input type="date" name="production_date" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="batch_range" class="form-label">Rentang Batch Masak</label>
                            <input type="text" name="batch_range" class="form-control" required placeholder="misal 1-12" id="batch_range_input" />
                        </div>

                        <div class="mb-3" id="storage_fields">
                            <!-- Storage fields akan ditambahkan di sini -->
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <input type="text" name="description" class="form-control" required />
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success" id="submitBtn">Simpan Sampling</button>
                        </div>
                    </form>

                </div>


            </div>
        </div>

    </div>
    <!--end col-->
</div>
<!--end row-->
<script>
    function createStorageFields(count) {
        const container = $('#storage_fields');
        container.empty();
        for (let i = 0; i < count; i++) {
            container.append(`
                <div class="mb-2">
                    <label for="storage_${i}" class="form-label">Storage ${i + 1}</label>
                    <input type="text" name="storage[]" class="form-control" id="storage_${i}" required />
                </div>
            `);
        }
    }

    $('#batch_range_input').on('change keyup', function() {
        const value = $(this).val();
        const match = value.match(/(\d+)\s*-\s*(\d+)/);

        if (match) {
            const start = parseInt(match[1]);
            const end = parseInt(match[2]);
            const totalBatches = end - start + 1;

            if (totalBatches > 0) {
                const groupCount = Math.ceil(totalBatches / 10);
                createStorageFields(groupCount);
            }
        } else if (!isNaN(value)) {
            createStorageFields(1);
        }
    });

    // Trigger saat halaman load jika sudah diisi sebelumnya
    $(document).ready(function() {
        $('#batch_range_input').trigger('change');
    });


    $(document).ready(function() {
        $('#form_input_po').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let submitBtn = $('#submitBtn');
            submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{ route('productionbatch.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload(); // reload setelah klik "OK"
                    });
                    form.trigger('reset');
                },
                error: function(xhr) {
                    const handlers = {
                        422: function() {
                            let errors = xhr.responseJSON.errors;
                            let list = '';
                            $.each(errors, function(key, value) {
                                list += `<li>${value[0]}</li>`;
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: `<ul style="text-align:left;">${list}</ul>`
                            });
                        },
                        409: function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Sudah Ada',
                                text: xhr.responseJSON.message
                            });
                        },
                        500: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
                            });
                        },
                        default: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menyimpan data.'
                            });
                        }
                    };

                    (handlers[xhr.status] || handlers.default)(); // panggil handler sesuai status
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Simpan Sampling');
                }
            });
        });


    });
</script>
@endsection