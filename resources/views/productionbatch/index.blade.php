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
                    <div class="col-sm-auto">
                        <div class="d-flex gap-1 flex-wrap">
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Create </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form>
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" placeholder="Search for order ID, customer, order status or something...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" id="demo-datepicker" placeholder="Select date">
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
            <div class="card-body pt-0">
                <div>
                    <hr>
                    <div class="table-responsive table-card mb-1">
                        <table class="table table-nowrap align-middle text-center" id="orderTable">
                            <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th>
                                        No
                                    </th>
                                    <th class="sort" data-sort="po">PO</th>
                                    <th class="sort" data-sort="varian">Varian</th>
                                    <th class="sort" data-sort="tanggal_produksi">Tanggal Produksi</th>
                                    <th class="sort" data-sort="batch">Rentang Batch</th>
                                    <th class="sort" data-sort="storage">Storage</th>
                                    <th class="sort" data-sort="description">Keterangan</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @foreach ($productionBatches as $index => $productionBatch)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $productionBatch->po_number }}</td>
                                    <td>{{ $productionBatch->variant }}</td>
                                    <td>{{ $productionBatch->production_date }}</td>
                                    <td>{{ $productionBatch->batch_range }}</td>
                                    <td>{{ $productionBatch->storage }}</td>
                                    <td>{{ $productionBatch->description }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-warning">
                                            <i class="ri-pencil"></i> edit
                                        </a>
                                        <a href="" class="btn btn-sm btn-danger">
                                            <i class="ri-trash"></i> delete
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted">We've searched more than 150+ Orders We did not find any orders for you search.</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
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

                <!-- modals -->
                <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">&nbsp;</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                            </div>
                            <form id="form_input_po" class="tablelist-form" >
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3" id="modal-id">
                                        <label for="po_number" class="form-label">PO</label>
                                        <input type="text" name="po_number" id="orderId" class="form-control" id="po_number" placeholder="Input nomor PO" required />
                                    </div>

                                    <div class="mb-3">
                                        <label for="variant" class="form-label">Variant</label>
                                        <input type="text" name="variant" id="variant" class="form-control" placeholder="Input variant" required />
                                    </div>

                                    <div class="mb-3">
                                        <label for="production_date" class="form-label">Tanggal Produksi</label>
                                        <input type="date" name="production_date" id="production_date" class="form-control" required />
                                    </div>

                                    <div class="mb-3">
                                        <label for="batch_range" class="form-label">Rentang Batch Masak</label>
                                        <input type="text" name="batch_range" id="batch_range" class="form-control" required placeholder="misal 1-2..." />
                                    </div>
                                    <div class="mb-3">
                                        <label for="storage" class="form-label">Storage</label>
                                        <input type="text" name="storage" id="storage" class="form-control" required placeholder="Input storage" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Keterangan</label>
                                        <input type="text" name="description" id="description" class="form-control" required placeholder="Input keterangan" />
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="submitBtn">Save</button>
                                        <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body p-5 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                                <div class="mt-4 text-center">
                                    <h4>You are about to delete a order ?</h4>
                                    <p class="text-muted fs-15 mb-4">Deleting your order will remove all of your information from our database.</p>
                                    <div class="hstack gap-2 justify-content-center remove">
                                        <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                                        <button class="btn btn-danger" id="delete-record">Yes, Delete It</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end modal -->
            </div>
        </div>

    </div>
    <!--end col-->
</div>
<!--end row-->

<script>
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