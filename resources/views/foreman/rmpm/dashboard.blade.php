@extends('layouts.app') {{-- asumsi layout utama kamu namanya layouts.app --}}

@section('style')
{{-- Tambahkan style tambahan di sini jika perlu --}}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-4">Dashboard RMPM</h4>
    </div>
</div>

<div class="row" id="summary-boxes">
    <!-- Akan diisi dengan ringkasan via AJAX -->
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div id="grafik-kedatangan" style="height: 300px;"></div>
    </div>
    <div class="col-md-6">
        <div id="disposisi-pie" style="height: 300px;"></div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <h5>Progress Sampling</h5>
        <div id="progress-sampling" class="table-responsive">
            <!-- Tabel akan dimuat via JS -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadSummary();
        loadGrafikKedatangan();
        loadDisposisiPie();
        loadProgressSampling();

        function loadSummary() {
            $.get('/api/foreman/dashboard-rmpm/summary', function(res) {
                let html = '';
                for (const [key, value] of Object.entries(res)) {
                    html += `<div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-capitalize">${key.replace('_', ' ')}</h5>
                            <p class="card-text fs-4 fw-bold">${value}</p>
                        </div>
                    </div>
                </div>`;
                }
                $('#summary-boxes').html(html);
            });
        }

        function loadGrafikKedatangan() {
            $.get('/api/foreman/dashboard-rmpm/grafik-kedatangan', function(res) {
                Highcharts.chart('grafik-kedatangan', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Grafik Kedatangan Bahan'
                    },
                    xAxis: {
                        categories: res.labels
                    },
                    yAxis: {
                        title: {
                            text: 'Jumlah Kedatangan'
                        }
                    },
                    series: [{
                        name: 'Kedatangan',
                        data: res.data
                    }]
                });
            });
        }

        function loadDisposisiPie() {
            $.get('/api/foreman/dashboard-rmpm/disposisi-pie', function(res) {
                Highcharts.chart('disposisi-pie', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Disposisi Analisa'
                    },
                    series: [{
                        name: 'Jumlah',
                        colorByPoint: true,
                        data: res
                    }]
                });
            });
        }

        function loadProgressSampling() {
            $.get('/api/foreman/dashboard-rmpm/progress-sampling', function(res) {
                let html = `<table class="table table-bordered table-striped">
                <thead><tr>
                    <th>Nama Bahan</th>
                    <th>Mobil</th><th>Dokumen</th>
                    <th>Fisik Kemasan</th><th>Fisik Raw</th>
                </tr></thead><tbody>`;

                res.forEach(row => {
                    html += `<tr>
                    <td>${row.nama_bahan}</td>
                    <td>${row.sampling_mobil}</td>
                    <td>${row.sampling_dokumen}</td>
                    <td>${row.sampling_kemasan}</td>
                    <td>${row.sampling_raw}</td>
                </tr>`;
                });

                html += '</tbody></table>';
                $('#progress-sampling').html(html);
            });
        }
    });
</script>
@endsection