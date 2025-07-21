@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">RMPM</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                    <li class="breadcrumb-item active">Supervisor</li>
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
                <p class="text-muted mb-0">Mari tingkatkan kualitas agar menjadi perusahaan makanan kelas dunia.</p>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input id="date-picker" type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y" data-default-date="01 Jan 2022 to 31 Jan 2022">
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

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="dataRMCard">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-sm">
                        <h5 class="card-title mb-0">Data RM QC</h5>
                    </div>
                </div>
            </div>

            <div class="card-body border-top">
                <form>
                    <div class="row g-3 align-items-center">
                        <div class="col-xxl-3 col-sm-3">
                            <div class="search-box">
                                <input type="text" class="form-control" id="searchDataRM" placeholder="Cari nama bahan, SPB, atau suplier...">
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
                                <option value="done">Selesai Analisa</option>
                                <option value="progress">On Progress</option>
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-3">
                            <button type="button" class="btn btn-outline-warning w-100" onclick="resetFilterRM()">
                                <i class="ri-refresh-line me-1 align-bottom"></i> Reset Filter
                            </button>
                        </div>
                        <div class="col-xxl-2 col-sm-3">
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                                <i class="ri-add-line align-bottom me-1"></i> Input Identitas RM
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body pt-0">
                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-2" data-bs-toggle="tab" href="#tabAll" role="tab">🧾 Semua Data RM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#gulatebu" role="tab">Gula Tebu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#gulakelapa" role="tab">Gula Kelapa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#gula" role="tab">Gula</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#garam" role="tab">Garam</a>
                    </li>
                </ul>

                <div class="tab-content">
                    @foreach (['tabAll' => 'Semua Data RM', 'gulatebu' => 'Gula Tebu', 'gula' => 'Gula', 'garam' => 'Garam', 'gulakelapa' => 'RM On Progress'] as $tabId => $label)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel">
                        <div class="table-responsive table-card">
                            <table class="table table-nowrap align-middle" id="{{ $tabId }}Table">
                                <thead class="table-light text-center text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>No SPB</th>
                                        <th>Bahan</th>
                                        <th>Nama Bahan</th>
                                        <th>Suplier</th>
                                        <th>Tanggal Kedatangan</th>
                                        <th>Asal Bahan</th>
                                        <th>Jumlah Kedatangan</th>
                                        <th>Selesai Analisa</th>
                                        <th>Aksi</th>
                                        <th>Barcode</th>
                                    </tr>
                                </thead>
                                <tbody class="list text-center">
                                    <!-- Data akan ditampilkan secara dinamis -->
                                </tbody>
                            </table>
                            <nav class="mt-2 text-center">
                                <ul class="pagination justify-content-center" id="pagination{{ $tabId }}"></ul>
                            </nav>
                            <div class="noresult text-center py-4" style="display:none">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Data Tidak Ditemukan</h5>
                                <p class="text-muted">Silakan periksa kembali pencarian atau filter status.</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Modal untuk input identitas RM -->
        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Input Identitas RM</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('rmpm.simpanIdentitas') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="jenis_gula" class="form-label">Nama Bahan</label>
                                <select class="form-select" name="jenis_gula" id="jenis_gula">
                                    <option value="Gula Tebu">Gula Tebu</option>
                                    <option value="Gula Kelapa">Gula Kelapa</option>
                                    <option value="Gula">Gula</option>
                                    <option value="Garam">Garam</option>
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="tanggal_kedatangan" class="form-label">Tanggal & Jam Kedatangan</label>
                                <input type="datetime-local" class="form-control" id="tanggal_kedatangan" name="tanggal_kedatangan" required>

                            </div>

                            <div class="mb-3">
                                <label for="suplier_manufactur" class="form-label">Supplier / Manufactur</label>
                                <input type="text" class="form-control" id="suplier_manufactur" name="suplier_manufactur" required>
                            </div>

                            <div class="mb-3">
                                <label for="asal_bahan" class="form-label">Asal Bahan</label>
                                <input type="text" class="form-control" id="asal_bahan" name="asal_bahan" required>
                            </div>

                            <div class="mb-3">
                                <label for="no_mobil" class="form-label">No Mobil</label>
                                <input type="text" class="form-control" id="no_mobil" name="no_mobil" required>
                            </div>

                            <div class="mb-3">
                                <label for="no_spb" class="form-label">No SPB</label>
                                <input type="number" class="form-control" id="no_spb" name="no_spb" required>
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_kedatangan" class="form-label">Jumlah Kedatangan (kg)</label>
                                <input type="number" class="form-control" id="jumlah_kedatangan" name="jumlah_kedatangan" placeholder="input dalam kilogram" required>
                            </div>

                            <div class="mb-3">
                                <label for="lot_batch" class="form-label">Lot / Batch</label>
                                <input type="text" class="form-control" id="lot_batch" name="lot_batch" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="qrModalDynamic" tabindex="-1" aria-labelledby="qrModalDynamicLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="qrModalDynamicLabel">QR Code - ID</h5>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" id="qrPrintArea">
                        <div style="display: inline-block;" id="qrImageArea">
                            <!-- QR Image will be injected here -->
                        </div>
                        <p class="mt-2 small" id="qrLabelText"></p>
                    </div>
                    <div class="modal-footer justify-content-center py-2">
                        <button onclick="printQR('qrPrintArea')" class="btn btn-sm btn-success">Print</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const itemsPerPageRM = 20;
    let dataRMStore = {
        tabAll: [],
        gulatebu: [],
        gulakelapa: [],
        gula: [],
        garam: [],
    };
    let currentPagesRM = {
        tabAll: 1,
        gulatebu: 1,
        gulakelapa: 1,
        gula: 1,
        garam: 1,
    };

    function paginateRM(tabId) {
        const data = dataRMStore[tabId];
        const currentPage = currentPagesRM[tabId];
        const start = (currentPage - 1) * itemsPerPageRM;
        const end = start + itemsPerPageRM;
        const slicedRows = data.slice(start, end);
        const $tbody = $(`#${tabId}Table tbody`);
        $tbody.empty().append(slicedRows);

        const $pagination = $(`#pagination${tabId}`);
        $pagination.empty();

        const totalPages = Math.ceil(data.length / itemsPerPageRM);
        if (totalPages <= 1) return;

        // ← tombol sebelumnya
        $pagination.append(`
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link rm-page-btn" href="#" data-tab="${tabId}" data-page="${currentPage - 1}">←</a>
        </li>
    `);

        for (let i = 1; i <= totalPages; i++) {
            $pagination.append(`
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link rm-page-btn" href="#" data-tab="${tabId}" data-page="${i}">${i}</a>
            </li>
        `);
        }

        // → tombol berikutnya
        $pagination.append(`
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link rm-page-btn" href="#" data-tab="${tabId}" data-page="${currentPage + 1}">→</a>
        </li>
    `);

        // Event klik pagination
        $('.rm-page-btn').click(function(e) {
            e.preventDefault();
            const tab = $(this).data('tab');
            const page = Number($(this).data('page'));
            currentPagesRM[tab] = page;
            paginateRM(tab);
        });
    }

    function filterDataRM() {
        const keyword = $('#searchDataRM').val().toLowerCase();
        const statusFilter = $('#filterStatus').val();
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();


        $.ajax({
            url: "{{url('analis/rmpm/data/rm')}}",
            method: 'GET',
            success: function(response) {
                const data = response.data;

                const tabRows = {
                    tabAll: [],
                    gulatebu: [],
                    gulakelapa: [],
                    gula: [],
                    garam: [],
                };

                data.forEach((item, index) => {
                    const searchable = `${item.nama_bahan} ${item.no_spb} ${item.suplier_manufactur}`.toLowerCase();
                    if (keyword && !searchable.includes(keyword)) return;
                    if (statusFilter && statusFilter !== 'all' && item.status !== statusFilter) return;
                    // Filter tanggal
                    function adjustDate(date, offsetDays) {
                        const d = new Date(date);
                        d.setDate(d.getDate() + offsetDays);
                        return d;
                    }

                    const itemDate = new Date(item.tanggal_kedatangan);
                    const startDateObj = startDate ? adjustDate(startDate, -1) : null;
                    const endDateObj = endDate ? adjustDate(endDate, +1) : null;


                    // Filter tanggal secara inklusif
                    if (startDateObj && itemDate < startDateObj) return;
                    if (endDateObj && itemDate > endDateObj) return;


                    const row = `
<tr>
    <td>${index + 1}</td>
    <td>${item.no_spb}</td>
    <td>${item.jenis_gula}</td>
    <td>${item.nama_bahan}</td>
    <td>${item.suplier_manufactur}</td>
    <td>${item.tanggal_kedatangan}</td>
    <td>${item.asal_bahan}</td>
    <td>${item.jumlah_kedatangan}</td>
    <td>${item.status === 'done' ? '✅ Selesai' : '⌛ Proses'}</td>
    <td>
        <a href="{{url('/supervisor/rmpm/detail/data/${item.id}')}}" class="btn btn-sm btn-info">
            <i class="ri-eye-line"></i> View
        </a>
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-primary" onclick="showQrModal(${item.id}, '${item.id}_${item.no_spb}_${item.tanggal_kedatangan}_${item.nama_bahan}')">
            QR Code
        </button>
    </td>
</tr>`;

                    tabRows.tabAll.push(row);
                    if (item.jenis_gula === 'Gula') tabRows.gula.push(row);
                    if (item.jenis_gula === 'Garam') tabRows.garam.push(row);
                    if (item.jenis_gula === 'Gula Tebu') tabRows.gulatebu.push(row);
                    if (item.jenis_gula === 'Gula Kelapa') tabRows.gulakelapa.push(row);
                });

                dataRMStore = tabRows;
                currentPagesRM = {
                    tabAll: 1,
                    gulatebu: 1,
                    gulakelapa: 1,
                    gula: 1,
                    garam: 1,
                };

                Object.keys(dataRMStore).forEach(tabId => {
                    paginateRM(tabId);
                });

                $('.noresult').toggle(tabRows.tabAll.length === 0);
            },
            error: function() {
                alert('Gagal mengambil data RM');
            }
        });
    }

    function showQrModal(id, label) {
        console.log(`Showing QR for ID: ${id}, Label: ${label}`);

        const qrArea = document.getElementById('qrImageArea');
        qrArea.innerHTML = ''; // Clear previous QR
        const urlToEncode = `${window.location.origin}/supervisor/rmpm/detail/data/${id}`;

        new QRCode(qrArea, {
            text: urlToEncode,
            width: 150,
            height: 150
        });

        $('#qrLabelText').text(label);
        const modal = new bootstrap.Modal(document.getElementById('qrModalDynamic'));
        modal.show();
    }

    // Optionally load on page load
    $(document).ready(function() {
        filterDataRM();
        document.getElementById('filter-date-btn').addEventListener('click', function() {
            filterDataRM();
        });
        // Allow search on enter
        $('#searchDataRM').on('keypress', function(e) {
            if (e.which === 13) filterDataRM();
        });

        // Allow filter on change
        $('#filterStatus').on('change', function() {
            filterDataRM();
        });


    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#showModal form');
        form.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => {
            if (input.name !== 'jenis_gula') {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });
    });

    function resetFilterRM() {
        // Bersihkan input pencarian
        $('#searchDataRM').val('');

        // Reset dropdown status
        $('#filterStatus').val('all');

        // Reset tanggal
        $('#start_date').val('');
        $('#end_date').val('');

        // Panggil kembali filter tanpa syarat
        filterDataRM();
    }
</script>

<script>
    function printQR(id) {
        const content = document.getElementById(id).innerHTML;
        const win = window.open('', '', 'height=600,width=600');
        win.document.write('<html><head><title>Print QR</title>');
        win.document.write('<style>body{text-align:center; font-size:12px;}</style>');
        win.document.write('</head><body>');
        win.document.write(content);
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        win.print();
        win.close();
    }
</script>
@endsection