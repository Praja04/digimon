@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Dashboard RMPM</h4>

    {{-- Filter tanggal --}}
    <div class="row mb-3">
        <!-- <div class="col-md-3">
            <input type="date" id="tanggal_awal" class="form-control" value="{{ date('Y-m-01') }}">
        </div>
        <div class="col-md-3">
            <input type="date" id="tanggal_akhir" class="form-control" value="{{ date('Y-m-d') }}">
        </div> -->
        <div class="col-md-3">
            <input type="date" id="tanggal_awal" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" id="tanggal_akhir" class="form-control">
        </div>
        <div class="col-md-3">
            <select id="jenis_gula" class="form-control">
                <option value="">Semua Jenis Gula</option>
                <option value="Gula Kelapa">Gula Kelapa</option>
                <option value="Gula Tebu">Gula Tebu</option>
                <option value="Gula">Gula</option>
                <option value="Garam">Garam</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" id="btn-filter">Terapkan Filter</button>
        </div>
    </div>

    {{-- Cards --}}
    <div class="row" id="dashboard-cards">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Total Kedatangan</h6>
                    <h3 id="total-kedatangan">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Sampling Lengkap</h6>
                    <h3 id="sampling-lengkap">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Sudah Analisa</h6>
                    <h3 id="sudah-analisa">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Disposisi</h6>
                    <div id="disposisi-count">-</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Data Detail --}}
    <div class="mt-4">
        <h5>Data Kedatangan</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="table-identitas">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>No Polisi</th>
                        <th>Jenis Gula</th>
                        <th>Sampling</th>
                        <th>Analisa</th>
                    </tr>
                </thead>
                <tbody id="tbody-identitas">
                    <tr>
                        <td colspan="7" class="text-center">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modalAnalisa" tabindex="-1" aria-labelledby="modalAnalisaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Analisa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="modal-analisa-body">
                    <div class="text-center">Memuat data...</div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        function getFilterParams() {
            return {
                tanggal_awal: $('#tanggal_awal').val(),
                tanggal_akhir: $('#tanggal_akhir').val(),
                jenis_gula: $('#jenis_gula').val()
            };
        }

        function loadDashboard(useParams = false) {
            const params = useParams ? getFilterParams() : {}; // hanya kirim param jika filter diklik

            $.getJSON('/api/foreman/dashboard-rmpm/total-kedatangan', params, function(res) {
                $('#total-kedatangan').text(res.total_kedatangan);
            });

            $.getJSON('/api/foreman/dashboard-rmpm/sampling-lengkap', params, function(res) {
                $('#sampling-lengkap').text(res.sampling_lengkap);
            });

            $.getJSON('/api/foreman/dashboard-rmpm/sudah-analisa', params, function(res) {
                $('#sudah-analisa').text(res.sudah_analisa);
            });

            $.getJSON('/api/foreman/dashboard-rmpm/disposisi-summary', params, function(res) {
                let html = '';
                $.each(res.disposisi_summary, function(key, val) {
                    html += `<div>${key}: <strong>${val}</strong></div>`;
                });
                $('#disposisi-count').html(html || 'Tidak ada data');
            });

            $.getJSON('/api/foreman/dashboard-rmpm/list-identitas', params, function(res) {
                let rows = '';
                if (res.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>';
                } else {
                    $.each(res, function(i, item) {
                        let samplingStatus = (item.sampling_mobil && item.sampling_dokumen && item.sampling_fisik_kemasan && item.sampling_fisik_raw) ? '✅ Lengkap' : '❌ Tidak Lengkap';
                        let analisaStatus = (item.analisa_short_term.length || item.analisa_long_term.length || item.analisa_garam_gula.length) ?
                            `<button class="btn btn-sm btn-info btn-analisa" 
                data-id="${item.id}" 
                data-gula="${item.jenis_gula}" 
                data-analisa='${JSON.stringify({
                    short: item.analisa_short_term,
                    long: item.analisa_long_term,
                    garam: item.analisa_garam_gula
                })}'>✅ Ada</button>` :
                            '❌ Belum';

                        rows += `<tr>
                        <td>${i + 1}</td>
                        <td>${item.tanggal_kedatangan}</td>
                        <td>${item.suplier_manufactur ?? '-'}</td>
                        <td>${item.no_mobil ?? '-'}</td>
                        <td>${item.jenis_gula}</td>
                        <td>${samplingStatus}</td>
                        <td>${analisaStatus}</td>
                    </tr>`;
                    });
                }
                $('#tbody-identitas').html(rows);
            });
        }

        $('#btn-filter').click(function() {
            loadDashboard(true); // pakai param kalau klik tombol
        });

        loadDashboard(); // awal: tanpa param

        $(document).on('click', '.btn-analisa', function() {
            const data = $(this).data('analisa');
            const jenisGula = $(this).data('gula');

            let html = `<h5>Jenis Gula: ${jenisGula}</h5><hr/>`;

            const generateTable = (title, dataArray) => {
                if (!dataArray || dataArray.length === 0) return '';

                let headers = new Set();
                dataArray.forEach(item => {
                    Object.keys(item).forEach(k => {
                        if (k !== 'created_at' && k !== 'updated_at') {
                            headers.add(k);
                        }
                    });
                });

                headers = Array.from(headers);

                // Urutan prioritas kolom
                const orderedHeaders = [
                        'id', 'brix', 'ph', 'kotoran', 'ka', 'organo',
                        'warna', 'aroma', 'uji_kristal', 'disposisi', 'attachment'
                    ]
                    .filter(h => headers.includes(h))
                    .concat(headers.filter(h => ![
                        'id', 'brix', 'ph', 'kotoran', 'ka', 'organo',
                        'warna', 'aroma', 'uji_kristal', 'disposisi', 'attachment'
                    ].includes(h)));

                let table = `<h6 class="mt-3">${title}</h6>`;
                table += `<div class="table-responsive"><table class="table table-bordered table-sm"><thead><tr>`;
                orderedHeaders.forEach(h => {
                    table += `<th>${h}</th>`;
                });
                table += `</tr></thead><tbody>`;

                dataArray.slice(0, 3).forEach(row => {
                    table += `<tr>`;
                    orderedHeaders.forEach(k => {
                        let v = row[k];

                        if (k === 'disposisi') {
                            if (typeof v === 'object' && v !== null) {
                                v = v.disposisi ?? '-';
                            }
                        }

                        if (k === 'attachment') {
                            if (v) {
                                v = `<button class="btn btn-sm btn-primary btn-view-attachment" data-src="/storage/attachments/${v}">Lihat</button>`;
                            } else {
                                v = '-';
                            }
                        }

                        table += `<td>${v ?? '-'}</td>`;
                    });
                    table += `</tr>`;
                });

                table += `</tbody></table></div>`;
                return table;
            };

            html += generateTable('Analisa Short Term', data.short);
            html += generateTable('Analisa Long Term', data.long);
            html += generateTable('Analisa Garam & Gula', data.garam);

            $('#modal-analisa-body').html(html);
            $('#modalAnalisa').modal('show');
        });


    });
</script>
@endsection