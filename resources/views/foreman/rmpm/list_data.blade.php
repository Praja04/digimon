@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Identitas RM - {{ $jenis }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Identitas</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $jenis }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="leadsList">
                <div class="card-header border-0">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm-3">
                            <div class="search-box">
                                <input type="text" class="form-control search" id="searchInput"
                                    placeholder="Search for..." />
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-sm-auto ms-auto">
                            <div class="hstack gap-2">
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#manualScanModal">
                                    <i class="ri-qr-scan-line"></i> Scan via Alat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card">
                            <table class="table align-middle text-center" id="customerTable">
                                <thead class="table-light">

                                    <tr>
                                        <th>No</th>
                                        <th>No SPB</th>
                                        <th>Nama Bahan</th>
                                        <th>Suplier</th>
                                        <th class="sort" data-sort="tanggal_kedatangan">Tanggal Kedatangan</th>
                                        <th>Asal Bahan</th>
                                        <th>Jumlah Kedatangan</th>
                                        <th>Kedatangan di Lab</th>
                                        <th>Selesai Analisa</th>
                                        @if ($jenis == 'Gula Kelapa' || $jenis == 'Gula Tebu')
                                            <th>Disposisi Short Term</th>
                                            <th>Disposisi Long Term</th>
                                        @else
                                            <th>Disposisi</th>
                                        @endif
                                        <th>Aksi</th>
                                        <th>barcode</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    @forelse ($identitasList as $index => $identitas)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="no_spb">{{ $identitas->no_spb }}</td>
                                            <td class="nama_bahan">{{ $identitas->nama_bahan }}</td>
                                            <td class="suplier">{{ $identitas->suplier_manufactur }}</td>
                                            <td class="tanggal_kedatangan">{{ $identitas->tanggal_kedatangan }}</td>
                                            <td class="asal_bahan">{{ $identitas->asal_bahan }}</td>
                                            <td class="jumlah_kedatangan">{{ $identitas->jumlah_kedatangan }}</td>
                                            <td class="kedatangan_lab">{{ $identitas->konfirmasi->jam_kedatangan ?? '-' }}
                                            </td>
                                            <td class="selesai_analisa">{{ $identitas->konfirmasi->jam_analisa ?? '-' }}
                                            </td>
                                            @if (in_array($identitas->jenis_gula, ['Gula Kelapa', 'Gula Tebu']))
                                                <td class="disposisi_short_term">
                                                    @php
                                                        $short =
                                                            $data_detail3[$identitas->id]->disposisi->disposisi ?? null;
                                                    @endphp
                                                    @if ($short === 'Reject')
                                                        <span class="badge bg-danger">
                                                            <i class="ri-error-warning-line me-1"></i> {{ $short }}
                                                        </span>
                                                    @elseif ($short === 'Release')
                                                        <span class="badge bg-success">{{ $short }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="disposisi_long_term">
                                                    @php
                                                        $long = $data_detail2[$identitas->id]->disposisi ?? null;
                                                        $kristal = $data_detail2[$identitas->id]->uji_kristal ?? null;
                                                    @endphp
                                                    @if ($kristal === 'positif' && $long === 'reject')
                                                        <span class="badge bg-danger">
                                                            <i class="ri-error-warning-line me-1"></i> Kristal
                                                            {{ $kristal }}
                                                        </span>
                                                    @elseif ($long === 'release')
                                                        <span class="badge bg-success">{{ $long }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @else
                                                <td class="disposisi">
                                                    @php
                                                        $dispo = $data_detail[$identitas->id]->disposisi ?? null;
                                                    @endphp
                                                    @if ($dispo === 'Reject')
                                                        <span class="badge bg-danger">
                                                            <i class="ri-error-warning-line me-1"></i> {{ $dispo }}
                                                        </span>
                                                    @elseif ($dispo === 'Release')
                                                        <span class="badge bg-success">{{ $dispo }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{ route('rmpm_foreman.detail_data', ['id' => $identitas->id]) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="ri-eye-line"></i> View
                                                </a>
                                            </td>
                                            <td>
                                                <!-- Tombol untuk buka modal -->
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#qrModal{{ $identitas->id }}">
                                                    QR Code
                                                </button>

                                                <!-- Modal Besar -->
                                                <div class="modal fade" id="qrModal{{ $identitas->id }}" tabindex="-1"
                                                    aria-labelledby="qrModalLabel{{ $identitas->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-2">
                                                                <h5 class="modal-title"
                                                                    id="qrModalLabel{{ $identitas->id }}">QR Code - ID
                                                                    {{ $identitas->id }}</h5>
                                                                <button type="button" class="btn-close btn-sm"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center"
                                                                id="qrPrintArea{{ $identitas->id }}">
                                                                <div style="display: inline-block;">
                                                                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(route('rmpm.detailIdentitas', ['id' => $identitas->id]), 'QRCODE') }}"
                                                                        alt="QR Code">
                                                                </div>
                                                                <p class="mt-2 small">
                                                                    {{ $identitas->no_spb }}_{{ $identitas->nama_bahan }}
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer justify-content-center py-2">
                                                                <button
                                                                    onclick="printQR('qrPrintArea{{ $identitas->id }}')"
                                                                    class="btn btn-sm btn-success">Print</button>
                                                                <button type="button" class="btn btn-sm btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Data tidak tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="pagination" class="mt-3 d-flex justify-content-center"></div>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a"
                                        style="
                                                                width: 75px;
                                                                height: 75px;
                                                            "></lord-icon>
                                    <h5 class="mt-2">
                                        Sorry! No Result
                                        Found
                                    </h5>
                                    <p class="text-muted mb-0">
                                        We've searched more
                                        than 150+ leads We
                                        did not find any
                                        leads for you
                                        search.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!-- Modal Update Disposisi -->


    <!-- modal -->
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
                        <input type="hidden" name="jenis_gula" value="{{ $jenis }}">

                        <div class="mb-3">
                            <label for="nama_bahan" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kedatangan" class="form-label">Tanggal & Jam Kedatangan</label>
                            <input type="datetime-local" class="form-control" id="tanggal_kedatangan"
                                name="tanggal_kedatangan" required>

                        </div>

                        <div class="mb-3">
                            <label for="suplier_manufactur" class="form-label">Supplier / Manufactur</label>
                            <input type="text" class="form-control" id="suplier_manufactur" name="suplier_manufactur"
                                required>
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
                            <input type="text" class="form-control" id="no_spb" name="no_spb" required>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_kedatangan" class="form-label">Jumlah Kedatangan (kg)</label>
                            <input type="number" class="form-control" id="jumlah_kedatangan" name="jumlah_kedatangan"
                                placeholder="input dalam kilogram" required>
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

    <div class="modal fade" id="manualScanModal" tabindex="-1" aria-labelledby="manualScanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="text" id="scannedUrl" class="form-control text-center"
                        placeholder="Scan QR Code di sini..." autofocus>
                    <!-- Spinner Loading -->
                    <div id="loadingSpinner" class="mt-3 d-none">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Mengarahkan ke halaman...</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal end -->
    <!-- Tambahkan ini di <head> atau sebelum </body> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
        $(document).ready(function() {
            let rowsPerPage = 10;
            let $rows = $('#customerTable tbody tr');
            let $pagination = $('#pagination');
            let $searchInput = $('#searchInput');
            let $noResult = $('.noresult');
            let currentPage = 1;

            // Fungsi tampilkan halaman
            function displayPage(page, filteredRows) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;
                $rows.hide();

                filteredRows.slice(start, end).show();
                currentPage = page;
                highlightPageButton(page);
            }

            // Fungsi buat tombol pagination
            function renderPagination(filteredRows) {
                $pagination.empty();
                const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

                if (pageCount <= 1) return;

                for (let i = 1; i <= pageCount; i++) {
                    const activeClass = i === currentPage ? 'active' : '';
                    $pagination.append(`
                <button class="btn btn-sm btn-outline-primary mx-1 page-link ${activeClass}" data-page="${i}">
                    ${i}
                </button>
                `);
                }
            }


            // Highlight halaman aktif
            function highlightPageButton(page) {
                $pagination.find('.page-link').removeClass('active');
                $pagination.find(`.page-link[data-page="${page}"]`).addClass('active');
            }

            // Fungsi filter data dan refresh pagination
            function applyFilter() {
                let query = $searchInput.val().toLowerCase();
                let matchedRows = $rows.filter(function() {
                    return $(this).text().toLowerCase().includes(query);
                });

                if (matchedRows.length === 0) {
                    $noResult.show();
                    $pagination.empty();
                    $rows.hide();
                } else {
                    $noResult.hide();
                    renderPagination(matchedRows);
                    displayPage(1, matchedRows);
                }
            }

            // Event pencarian
            $searchInput.on('keyup', function() {
                applyFilter();
            });

            // Event klik pagination
            $pagination.on('click', '.page-link', function() {
                let page = $(this).data('page');
                let query = $searchInput.val().toLowerCase();
                let matchedRows = $rows.filter(function() {
                    return $(this).text().toLowerCase().includes(query);
                });

                if (page === 'prev' && currentPage > 1) {
                    displayPage(currentPage - 1, matchedRows);
                } else if (page === 'next' && currentPage < Math.ceil(matchedRows.length / rowsPerPage)) {
                    displayPage(currentPage + 1, matchedRows);
                } else if (!isNaN(page)) {
                    displayPage(page, matchedRows);
                }
            });

            // Inisialisasi tampilan awal
            applyFilter();



            // Saat tombol edit diklik
            $('.btn-edit-disposisi').on('click', function() {
                const id = $(this).data('id');
                const current = $(this).data('current');

                $('#disposisi_id').val(id);
                $('#disposisi').val(current);

                $('#updateDisposisiModal').modal('show');
            });

            // Submit form via AJAX
            $('#updateDisposisiForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#disposisi_id').val();
                const formData = $(this).serialize();

                $.ajax({
                    url: `/rmpm/update-disposisi-long/${id}`, // sesuaikan route kamu
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        $('#updateDisposisiModal').modal('hide');
                        Swal.fire('Berhasil', 'Disposisi berhasil diperbarui.', 'success');
                        setTimeout(() => location.reload(),
                        1000); // reload halaman setelah 1 detik
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
                    }
                });
            });
        });

        const inputField = document.getElementById('scannedUrl');
        const spinner = document.getElementById('loadingSpinner');

        // Fokus otomatis saat modal terbuka
        document.getElementById('manualScanModal').addEventListener('shown.bs.modal', function() {
            inputField.value = '';
            spinner.classList.add('d-none');
            inputField.focus();
        });

        // Jika input berubah (scanner isi URL)
        inputField.addEventListener('input', function() {
            const url = inputField.value.trim();
            if (url.length > 5 && url.startsWith("http")) {
                // Tampilkan spinner
                spinner.classList.remove('d-none');

                // Nonaktifkan input sementara
                inputField.disabled = true;

                // Tunggu 1.5 detik, lalu redirect
                setTimeout(() => {
                    window.location.href = url;
                }, 1500);
            }
        });
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
