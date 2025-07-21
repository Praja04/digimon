@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Monitoring Turun Blending</h4>

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
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form>
                    <div class="row g-3 align-items-center">
                        <div class="col-xxl-3 col-sm-3">
                            <div class="search-box">
                                <input type="text" class="form-control" id="SearchData" placeholder="Cari nama Variant, nomor Po, atau lainnya...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="dropdown col-xxl-2 col-sm-3">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dateFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                📅 Filter Tanggal
                            </button>
                            <div class="dropdown-menu p-3" aria-labelledby="dateFilterDropdown" style="min-width: 300px;">
                                <div class="mb-2">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" id="start_date" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" id="end_date" class="form-control">
                                </div>
                                <button type="button" class="btn btn-success w-100" id="filter-date-btn">Apply Filter</button>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-3">
                            <select class="form-select" id="filterStatus">
                                <option value="">Filter Status</option>
                                <option value="all" selected>Semua</option>
                                <option value="complete">Selesai Analisa</option>
                                <option value="progress">On Progress</option>
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-3">
                            <button type="button" class="btn btn-outline-warning w-100" onclick="resetFilterRM()">
                                <i class="ri-refresh-line me-1 align-bottom"></i> Reset Filter
                            </button>
                        </div>
                    </div>
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
                                    <th class="sort" data-sort="storage">Storage</th>
                                    <th class="sort" data-sort="description">Keterangan</th>
                                    <th>Status</th>
                                    <th>Detail</th>
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
                                    <td>{{ $productionBatch->storage }}</td>
                                    <td>{{ $productionBatch->description }}</td>
                                    <td>{{ $productionBatch->isMonitoringBlendingComplete() ? '✅ Complete' : '⌛ Progress' }}</td>
                                    <td>
                                        <a href="{{ route('productionbatch.show_monitoring_blending', $productionBatch->id) }}" class="btn btn-sm btn-info"><i class="ri-eye-line"></i></a>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $productionBatch->id }}">
                                            <i class="ri-pencil-line"></i> edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $productionBatch->id }}">
                                            <i class="ri-delete-bin-line"></i> delete
                                        </button>
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
                            <ul id="pagination" class="pagination listjs-pagination mb-0"></ul>
                        </div>
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
                        <form id="form_input_po" class="tablelist-form">
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
        </div>

    </div>
    <!--end col-->

</div>
<!--end row-->
<script>
    $(document).ready(function() {

        let rowsPerPage = 10;
        let $rows = $('#orderTable tbody tr');
        let $pagination = $('#pagination');
        let $searchInput = $('#SearchData');
        let $noResult = $('.noresult');
        let currentPage = 1;

        function displayPage(page, filteredRows) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            $rows.hide();
            filteredRows.slice(start, end).show();
            currentPage = page;
            highlightActivePage(page);
        }

        function renderPagination(filteredRows) {
            $pagination.empty();
            const pageCount = Math.ceil(filteredRows.length / rowsPerPage);
            if (pageCount <= 1) return;

            for (let i = 1; i <= pageCount; i++) {
                const isActive = i === currentPage ? 'active' : '';
                $pagination.append(`
          <li class="page-item">
            <a class="page-link ${isActive}" href="#" data-page="${i}">${i}</a>
          </li>
        `);
            }
        }

        function highlightActivePage(page) {
            $pagination.find('.page-link').removeClass('active');
            $pagination.find(`.page-link[data-page="${page}"]`).addClass('active');
        }

        function applyFilter() {
            const query = $('#SearchData').val().toLowerCase();
            const status = $('#filterStatus').val();
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            const startObj = startDate ? new Date(startDate) : null;
            const endObj = endDate ? new Date(endDate) : null;

            const matchedRows = $('#orderTable tbody tr').filter(function() {
                const rowText = $(this).text().toLowerCase();
                const includeKeyword = rowText.includes(query);

                // Ambil tanggal produksi dari kolom ke-4 (index 3)
                const tanggalProduksi = $(this).find('td').eq(3).text().trim();
                const rowDate = new Date(tanggalProduksi);
                const includeDate =
                    (!startObj || rowDate >= startObj) &&
                    (!endObj || rowDate <= endObj);

                // Ambil status GGA & GGAS dari kolom 7 & 8 (index 6 & 7)
                const statuBlending = $(this).find('td').eq(6).text().trim().toLowerCase();
                const includeStatus =
                    status === 'all' || status === '' ||
                    statuBlending.includes(status);

                return includeKeyword && includeDate && includeStatus;
            });

            const $rows = $('#orderTable tbody tr');
            const $noResult = $('.noresult');
            const $pagination = $('#pagination');

            if (matchedRows.length === 0) {
                $rows.hide();
                $noResult.show();
                $pagination.empty();
            } else {
                $noResult.hide();
                $rows.hide();
                renderPagination(matchedRows);
                displayPage(1, matchedRows);
            }
        }
        // Event pagination click
        $pagination.on('click', '.page-link', function(e) {
            e.preventDefault();
            const selectedPage = parseInt($(this).data('page'));
            const query = $searchInput.val().toLowerCase();
            const matchedRows = $rows.filter(function() {
                return $(this).text().toLowerCase().includes(query);
            });
            displayPage(selectedPage, matchedRows);
        });

        // Event pencarian
        $searchInput.on('keyup', function() {
            applyFilter();
        });
        $('#filterStatus').on('change', function() {
            applyFilter();
        });
        $('#filter-date-btn').on('click', function() {
            applyFilter(); // filter hanya saat tombol diklik
        });


        // Inisialisasi awal
        applyFilter();


        let editId = null;

        // Edit button clicked
        $('.edit-btn').on('click', function() {
            editId = $(this).data('id');
            $.get(`/productionbatch/po_masak/${editId}`, function(data) {
                $('#orderId').val(data.po_number);
                $('#variant').val(data.variant);
                $('#production_date').val(data.production_date);
                $('#batch_range').val(data.batch_range);
                $('#storage').val(data.storage);
                $('#description').val(data.description);

                $('#submitBtn').text('Update');
                $('#showModal').modal('show');
            });
        });

        // Detect if it's update or create
        $('#form_input_po').off().on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let method = editId ? 'PUT' : 'POST';
            let url = editId ?
                "{{url('analis/productionbatch/po_masak')}}/" + editId :
                `{{ route('productionbatch.store') }}`;

            $('#submitBtn').prop('disabled', true).text(editId ? 'Updating...' : 'Saving...');

            $.ajax({
                url: url,
                method: method,
                data: form.serialize() + (editId ? `&_method=PUT` : ''),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => location.reload());

                    form.trigger('reset');
                    editId = null;
                    $('#submitBtn').text('Save');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors || {};
                    let list = '';
                    $.each(errors, function(key, val) {
                        list += `<li>${val[0]}</li>`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: `<ul style="text-align:left;">${list}</ul>`
                    });
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });

        // Delete
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{url('analis/productionbatch/po_masak')}}/" + id,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', response.message, 'success').then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data', 'error');
                        }
                    });
                }
            });
        });
    });

    function resetFilterRM() {
        location.reload();
    }
</script>
@endsection