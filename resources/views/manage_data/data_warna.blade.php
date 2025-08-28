@extends('layouts.app')

@section('content')
<!-- ✅ Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Manage Warna</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">QC</a></li>
                    <li class="breadcrumb-item active">Foreman</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- 👋 Welcome Message -->
<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-16 mb-1">Selamat Datang, {{ Session::get('username') }}!</h4>
                <p class="text-muted mb-0">Mari tingkatkan kualitas agar menjadi perusahaan makanan kelas dunia.</p>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input id="date-picker" type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none">
                                <i class="ri-pulse-line"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 🎨 Warna Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="warnaList">
            <div class="card-header border-0">
                <div class="row g-4 align-items-center">
                    <div class="col-sm-3">
                        <div class="search-box">
                            <input type="text" class="form-control search" placeholder="Search for..." />
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-select filter-status">
                            <option value="">-- Filter Status --</option>
                            <option value="">Semua</option>
                            <option value="complete">✅ Complete</option>
                            <option value="progress">⌛ Progress</option>
                        </select>
                    </div>
                    <div class="col-sm-6 text-end">
                        <button class="btn btn-primary" id="btnAddWarna">+ Tambah Warna</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table align-middle text-center" id="customerTable">
                        <!-- Data akan diisi via AJAX -->
                    </table>

                    <div class="noresult" style="display: none">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width: 75px; height: 75px;"></lord-icon>
                            <h5 class="mt-2">Maaf! Tidak ada hasil ditemukan</h5>
                            <p class="text-muted mb-0">Kami tidak menemukan data warna sesuai pencarian Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <div class="pagination-wrap hstack gap-2">
                        <a class="page-item pagination-prev disabled" href="#">Previous</a>
                        <ul class="pagination listjs-pagination mb-0"></ul>
                        <a class="page-item pagination-next" href="#">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🧩 Modal Tambah/Edit Warna -->
<div class="modal fade" id="warnaModal" tabindex="-1" aria-labelledby="warnaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="warnaForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warnaModalLabel">Tambah Warna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="warnaId">
                    <div class="mb-3">
                        <label for="nama_warna" class="form-label">Nama Warna</label>
                        <input type="text" class="form-control" id="nama_warna" required>
                    </div>
                    <div class="mb-3">
                        <label for="code_warna" class="form-label">Kode Warna</label>
                        <input type="text" class="form-control" id="code_warna" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        const baseUrl = "{{ url('/data/warna') }}";

        function loadWarnaData() {
            $.ajax({
                url: baseUrl,
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.success && res.data.length > 0) {
                        renderTable(res.data);
                        $('.noresult').hide();
                    } else {
                        $('#customerTable').html('<tr><td colspan="4">Tidak ada data warna.</td></tr>');
                        $('.noresult').show();
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Tidak bisa mengambil data warna.', 'error');
                }
            });
        }

        function renderTable(data) {
            let html = `
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Warna</th>
                <th>Code Warna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
    `;

            data.forEach(item => {
                html += `
            <tr>
                <td>${item.id}</td>
                <td>${item.nama_warna}</td>
                <td>
                    <span class="badge" >
                        ${item.code_warna}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${item.id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">Delete</button>
                </td>
            </tr>
        `;
            });

            html += '</tbody>';
            $('#customerTable').html(html);
        }

        $('.search').on('keyup', function() {
            const keyword = $(this).val().toLowerCase();
            $('#customerTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);
            });
        });

        $('#btnAddWarna').on('click', function() {
            $('#warnaForm')[0].reset();
            $('#warnaId').val('');
            $('#warnaModalLabel').text('Tambah Warna');
            $('#warnaModal').modal('show');
        });

        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `${baseUrl}/${id}`,
                method: 'GET',
                success: function(res) {
                    if (res.success) {
                        $('#warnaId').val(res.data.id);
                        $('#nama_warna').val(res.data.nama_warna);
                        $('#code_warna').val(res.data.code_warna);
                        $('#warnaModalLabel').text('Edit Warna');
                        $('#warnaModal').modal('show');
                    } else {
                        Swal.fire('Oops!', 'Data tidak ditemukan.', 'warning');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Tidak bisa mengambil data warna.', 'error');
                }
            });
        });

        $('#warnaForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#warnaId').val();
            const method = id ? 'PUT' : 'POST';
            const url = id ? `${baseUrl}/${id}` : baseUrl;

            $.ajax({
                url: url,
                method: method,
                data: {
                    nama_warna: $('#nama_warna').val(),
                    code_warna: $('#code_warna').val()
                },
                success: function(res) {
                    if (res.success) {
                        $('#warnaModal').modal('hide');
                        loadWarnaData();
                        Swal.fire('Berhasil!', res.message, 'success');
                    } else {
                        Swal.fire('Gagal!', 'Data tidak bisa disimpan.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan.', 'error');
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data warna akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${baseUrl}/${id}`,
                        method: 'DELETE',
                        success: function(res) {
                            if (res.success) {
                                loadWarnaData();
                                Swal.fire('Terhapus!', res.message, 'success');
                            } else {
                                Swal.fire('Gagal!', 'Data tidak bisa dihapus.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
                        }
                    });
                }
            });
        });

        loadWarnaData();
    });
</script>
@endsection