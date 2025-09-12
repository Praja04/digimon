@extends('layouts.app')
@section('title', 'Dashboard Sampling RMPM')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div data-aos="fade-right">
                    <h1 class="display-6 fw-bold text-dark mb-1" style="letter-spacing: -0.8px;">
                        <div class="d-inline-flex align-items-center">
                            <div class="icon-wrapper me-3 p-3 rounded-4 bg-primary bg-opacity-10">
                                <i class="ri-bar-chart-line fs-2 text-primary"></i>
                            </div>
                            <div>
                                <span class="text-primary">Dashboard Sampling</span>
                                <br>
                                <small class="text-muted fs-6 fw-normal">RMPM Analytics</small>
                            </div>
                        </div>
                    </h1>
                </div>
                <div class="text-end" data-aos="fade-left">
                    <div class="badge bg-light text-dark px-3 py-2 rounded-pill">
                        <i class="ri-calendar-line me-1"></i>
                        <span id="current-date"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="text-center">
                                <i class="ri-filter-3-line fs-2 "></i>
                            </div>
                        </div>
                        <div class="col-md-11">
                            <form id="filter-form" class="row gx-3 gy-3 align-items-end">
                                <div class="col-lg-3 col-md-4">
                                    <label for="filter-jenis-gula" class="form-label  fw-semibold mb-2">
                                        <i class="ri-drop-line me-1"></i>Jenis Gula
                                    </label>
                                    <select class="form-select form-select-lg border-0 shadow-sm" id="filter-jenis-gula" name="jenis_gula">
                                        <option value="">Semua Jenis</option>
                                        <option value="Gula">Gula</option>
                                        <option value="Garam">Garam</option>
                                        <option value="Gula Tebu">Gula Tebu</option>
                                        <option value="Gula Kelapa">Gula Kelapa</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <label for="filter-start-date" class="form-label  fw-semibold mb-2">
                                        <i class="ri-calendar-check-line me-1"></i>Tanggal Mulai
                                    </label>
                                    <input type="date" class="form-control form-control-lg border-0 shadow-sm" id="filter-start-date" name="start_date">
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <label for="filter-end-date" class="form-label  fw-semibold mb-2">
                                        <i class="ri-calendar-check-fill me-1"></i>Tanggal Akhir
                                    </label>
                                    <input type="date" class="form-control form-control-lg border-0 shadow-sm" id="filter-end-date" name="end_date">
                                </div>
                                <div class="col-lg-3 col-md-12">
                                    <button type="button" id="btn-filter" class="btn btn-light btn-lg w-100 shadow-sm fw-semibold">
                                        <i class="ri-search-line me-2"></i>Analisis Data
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Charts Grid -->
    <div class="row g-4 mb-5">
        <div class="col-xl-6 col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3 p-2 rounded-3 bg-success bg-opacity-10">
                            <i class="ri-car-line text-success"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Kondisi Mobil</h5>
                    </div>
                </div>
                <div class="card-body pt-3" id="chart-mobil"></div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3 p-2 rounded-3 bg-info bg-opacity-10">
                            <i class="ri-file-text-line text-info"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Status Dokumen</h5>
                    </div>
                </div>
                <div class="card-body pt-3" id="chart-dokumen"></div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3 p-2 rounded-3 bg-warning bg-opacity-10">
                            <i class="ri-box-3-line text-warning"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Fisik Kemasan</h5>
                    </div>
                </div>
                <div class="card-body pt-3" id="chart-kemasan"></div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6" data-aos="fade-up" data-aos-delay="400">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3 p-2 rounded-3 bg-danger bg-opacity-10">
                            <i class="ri-flask-line text-danger"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Fisik Bahan Mentah</h5>
                    </div>
                </div>
                <div class="card-body pt-3" id="chart-raw"></div>
            </div>
        </div>
    </div>

    <!-- Sampling Overview -->
    <div class="row mb-5" data-aos="fade-up" data-aos-delay="500">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3 p-3 rounded-4 bg-primary bg-opacity-10">
                                <i class="ri-pie-chart-2-line fs-4 text-primary"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold">Ringkasan Sampling</h4>
                                <small class="text-muted">Overview data sampling keseluruhan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="card-umum"></div>
            </div>
        </div>
    </div>

    <!-- Quality Analysis Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="icon-wrapper mx-auto mb-3 p-4 rounded-4 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: fit-content;">
                    <i class="ri-microscope-line fs-1 "></i>
                </div>
                <h2 class="display-6 fw-bold text-dark mb-2">Analisa Kualitas & Disposisi</h2>
                <p class="text-muted fs-5 mb-0">Monitoring parameter kualitas bahan dan status disposisi</p>
            </div>
        </div>
    </div>

    <!-- Quality Parameters Grid -->
    <div class="row g-4 mb-5">
        @foreach (['Gula', 'Garam', 'Gula Tebu', 'Gula Kelapa'] as $index => $jenis)
        <div class="col-xl-6 col-lg-6" data-aos="fade-up" data-aos-delay="{{($index + 1) * 100}}">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-header bg-transparent border-0 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3 p-2 rounded-3 
                            @if($jenis == 'Gula') bg-primary bg-opacity-10 
                            @elseif($jenis == 'Garam') bg-info bg-opacity-10
                            @elseif($jenis == 'Gula Tebu') bg-success bg-opacity-10
                            @else bg-warning bg-opacity-10
                            @endif">
                            <i class="ri-drop-line 
                                @if($jenis == 'Gula') text-primary 
                                @elseif($jenis == 'Garam') text-info
                                @elseif($jenis == 'Gula Tebu') text-success
                                @else text-warning
                                @endif"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold">{{ $jenis }}</h5>
                            <small class="text-muted">Parameter Kualitas</small>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2" id="chart-{{ Str::slug($jenis) }}"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Disposition Summary -->
    <div class="row g-4">
        <div class="col-xl-8" data-aos="fade-right" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3 p-3 rounded-3 bg-success bg-opacity-10">
                            <i class="ri-pie-chart-line fs-4 text-success"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Rekap Disposisi</h5>
                            <small class="text-muted">Distribusi status disposisi keseluruhan</small>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="chart-disposisi-total"></div>
            </div>
        </div>

        <div class="col-xl-4" data-aos="fade-left" data-aos-delay="200">
            <div class="card border-0 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #719dedff 0%, #242bb0ff 100%);">
                <div class="card-body d-flex flex-column justify-content-center text-center p-5">
                    <div class="icon-wrapper mx-auto mb-4 p-4 rounded-4 bg-white bg-opacity-90 shadow-sm">
                        <i class="ri-time-line fs-1 text-warning"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Total Pending</h5>
                    <h1 id="pending-count" class="display-3 fw-bold text-dark mb-2">...</h1>
                    <p class="mb-0 fw-medium">Disposisi Menunggu</p>
                </div>
                <div class="position-absolute top-0 end-0 p-3">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2">
                        <i class="ri-alert-line "></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .card {
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #f39c12;
        box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
    }

    .btn-warning:hover {
        background-color: #e67e22;
        border-color: #e67e22;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .display-6 {
            font-size: 1.5rem;
        }

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(function() {
        // Tampilkan tanggal hari ini
        $('#current-date').text(new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }));

        // API endpoints
        const endpoints = {
            mobil: {
                url: "{{url('/api/dashboard-rmpm/mobil')}}",
                key: 'mobil'
            },
            dokumen: {
                url: "{{url('/api/dashboard-rmpm/dokumen')}}",
                key: 'dokumen'
            },
            kemasan: {
                url: "{{url('/api/dashboard-rmpm/kemasan')}}",
                key: 'kemasan'
            },
            raw: {
                url: "{{url('/api/dashboard-rmpm/raw')}}",
                key: 'raw_material'
            },
            umum: "{{url('/api/dashboard-rmpm/umum')}}",
            parameter: "{{url('/api/dashboard-rmpm/parameter-kualitas-per-jenis')}}",
            disposisi: "{{url('/api/dashboard-rmpm/disposisi')}}"
        };

        // Store chart instances untuk cleanup
        const chartInstances = {};

        // Utility: slugify string
        function slugify(str) {
            return str.toLowerCase().replace(/\s+/g, '-');
        }

        // Cleanup existing chart
        function cleanupChart(containerId) {
            const chartId = `chart-${containerId}`;
            if (chartInstances[chartId]) {
                chartInstances[chartId].destroy();
                delete chartInstances[chartId];
            }
            $(`#${chartId}`).empty();
        }

        // Show no data message
        function showNoDataMessage(containerId, message = 'Tidak ada data untuk ditampilkan') {
            $(`#chart-${containerId}`).html(`
            <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 280px;">
                <div class="icon-wrapper mb-3 p-3 rounded-4 bg-light">
                    <i class="ri-database-2-line fs-2 text-muted"></i>
                </div>
                <h6 class="text-muted mb-2">${message}</h6>
                <p class="text-muted small mb-0">Coba ubah filter atau periksa data</p>
            </div>
        `);
        }

        // Render chart stacked bar dengan error handling
        function renderStackedBarChart(containerId, dataObj) {
            cleanupChart(containerId);

            // Check if data is empty or invalid
            if (!dataObj || typeof dataObj !== 'object' || Object.keys(dataObj).length === 0) {
                showNoDataMessage(containerId);
                return;
            }

            const categories = Object.keys(dataObj);
            const yesData = categories.map(k => dataObj[k]?.yes || 0);
            const noData = categories.map(k => dataObj[k]?.no || 0);

            // Check if all data is zero
            const totalData = yesData.reduce((a, b) => a + b, 0) + noData.reduce((a, b) => a + b, 0);
            if (totalData === 0) {
                showNoDataMessage(containerId);
                return;
            }

            const options = {
                chart: {
                    type: 'bar',
                    stacked: true,
                    height: 320,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'Inter, sans-serif',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 400
                        }
                    }
                },
                series: [{
                    name: 'Sesuai',
                    data: yesData
                }, {
                    name: 'Tidak Sesuai',
                    data: noData
                }],
                plotOptions: {
                    bar: {
                        columnWidth: '55%',
                        borderRadius: 8,
                        borderRadiusApplication: 'end'
                    }
                },
                xaxis: {
                    categories: categories.map(k => k.replace(/_/g, ' ').toUpperCase())
                },
                colors: ['#10b981', '#ef4444'],
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '12px',
                    fontWeight: 500
                },
                grid: {
                    strokeDashArray: 3
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            series,
                            seriesIndex,
                            dataPointIndex,
                            w
                        }) {
                            return value + ' data';
                        }
                    }
                }
            };

            try {
                const chart = new ApexCharts(document.querySelector(`#chart-${containerId}`), options);
                chartInstances[`chart-${containerId}`] = chart;
                chart.render();
            } catch (error) {
                console.error(`Error rendering chart ${containerId}:`, error);
                showNoDataMessage(containerId, 'Gagal memuat chart');
            }
        }

        // Render parameter quality chart
        function renderParameterChart(jenis, data, analisa) {
            const containerId = 'chart-' + slugify(jenis);
            const chartId = `chart-${slugify(jenis)}`;

            // Cleanup existing chart
            if (chartInstances[chartId]) {
                chartInstances[chartId].destroy();
                delete chartInstances[chartId];
            }

            const el = document.querySelector(`#${containerId}`);
            el.innerHTML = '';

            if (!data || data.length === 0) {
                el.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 280px;">
                    <div class="icon-wrapper mb-3 p-3 rounded-4 bg-light">
                        <i class="ri-flask-line fs-2 text-muted"></i>
                    </div>
                    <h6 class="text-muted mb-2">Tidak ada data parameter</h6>
                    <p class="text-muted small mb-0">Data untuk ${jenis} tidak tersedia</p>
                </div>
            `;
                return;
            }

            let categories = data.map(r => r.disposisi);
            let series = [];

            if (analisa === 'garam_gula') {
                series = [{
                    name: '%KA',
                    data: data.map(d => parseFloat(d.avg_ka) || 0)
                }, {
                    name: '%NaCl',
                    data: data.map(d => parseFloat(d.avg_nacl) || 0)
                }, {
                    name: 'Gross Weight',
                    data: data.map(d => parseFloat(d.avg_weight) || 0)
                }];
            } else {
                series = [{
                    name: 'Brix',
                    data: data.map(d => parseFloat(d.avg_brix) || 0)
                }, {
                    name: 'pH',
                    data: data.map(d => parseFloat(d.avg_ph) || 0)
                }];
            }

            const options = {
                chart: {
                    type: 'bar',
                    height: 300,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series,
                xaxis: {
                    categories
                },
                plotOptions: {
                    bar: {
                        columnWidth: '60%',
                        borderRadius: 6,
                        borderRadiusApplication: 'end'
                    }
                },
                colors: ['#3b82f6', '#f59e0b', '#10b981'],
                legend: {
                    position: 'top',
                    fontSize: '12px'
                },
                grid: {
                    strokeDashArray: 3
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            series,
                            seriesIndex,
                            dataPointIndex,
                            w
                        }) {
                            const seriesName = w.globals.seriesNames[seriesIndex];
                            if (seriesName === '%KA' || seriesName === '%NaCl') {
                                return value.toFixed(2) + '%';
                            } else if (seriesName === 'Gross Weight') {
                                return value.toFixed(2) + ' kg';
                            }
                            return value.toFixed(2);
                        }
                    }
                }
            };

            try {
                const chart = new ApexCharts(el, options);
                chartInstances[chartId] = chart;
                chart.render();
            } catch (error) {
                console.error(`Error rendering parameter chart for ${jenis}:`, error);
                el.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="ri-error-warning-line fs-2 mb-3"></i>
                    <div>Gagal memuat chart</div>
                </div>
            `;
            }
        }

        // Render disposisi chart
        function renderDisposisiChart(data, totalPending) {
            const chartId = 'chart-disposisi-total';

            // Cleanup existing chart
            if (chartInstances[chartId]) {
                chartInstances[chartId].destroy();
                delete chartInstances[chartId];
            }

            const el = document.querySelector(`#${chartId}`);
            el.innerHTML = '';

            if (!data || data.length === 0) {
                el.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 320px;">
                    <div class="icon-wrapper mb-3 p-3 rounded-4 bg-light">
                        <i class="ri-pie-chart-line fs-2 text-muted"></i>
                    </div>
                    <h6 class="text-muted mb-2">Tidak ada data disposisi</h6>
                    <p class="text-muted small mb-0">Data disposisi tidak tersedia</p>
                </div>
            `;
                $('#pending-count').text('0');
                return;
            }

            const series = data.map(r => parseInt(r.total) || 0);
            const labels = data.map(r => r.disposisi);

            // Check if all series data is zero
            const totalData = series.reduce((a, b) => a + b, 0);
            if (totalData === 0) {
                el.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 320px;">
                    <div class="icon-wrapper mb-3 p-3 rounded-4 bg-light">
                        <i class="ri-pie-chart-line fs-2 text-muted"></i>
                    </div>
                    <h6 class="text-muted mb-2">Tidak ada data disposisi</h6>
                    <p class="text-muted small mb-0">Semua data disposisi kosong</p>
                </div>
            `;
                $('#pending-count').text('0');
                return;
            }

            const options = {
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'Inter, sans-serif',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 700
                    }
                },
                labels,
                series,
                colors: ['#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#06b6d4'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: v => v + ' data'
                    }
                }
            };

            try {
                const chart = new ApexCharts(el, options);
                chartInstances[chartId] = chart;
                chart.render();
            } catch (error) {
                console.error('Error rendering disposisi chart:', error);
                el.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="ri-error-warning-line fs-2 mb-3"></i>
                    <div>Gagal memuat chart disposisi</div>
                </div>
            `;
            }

            // Update pending count with animation
            $('#pending-count').hide().text(totalPending || 0).fadeIn(300);
        }

        // Load dashboard data dengan promise untuk better error handling
        function loadDashboard(params = {}) {
            // Show loading state
            const loadingHtml = `
            <div class="d-flex align-items-center justify-content-center py-5">
                <div class="spinner-border text-primary me-3" role="status"></div>
                <span>Memuat data...</span>
            </div>
        `;

            // Chart mobil, dokumen, kemasan, raw
            $.each(endpoints, function(id, config) {
                if (id !== 'umum' && typeof config === 'object') {
                    $(`#chart-${id}`).html(loadingHtml);

                    $.get(config.url, params)
                        .done(function(res) {
                            renderStackedBarChart(id, res[config.key]);
                        })
                        .fail(function(xhr, status, error) {
                            console.error(`Error loading ${id}:`, error);
                            showNoDataMessage(id, 'Gagal memuat data');
                        });
                }
            });

            // Card Umum
            $('#card-umum').html(loadingHtml);
            $.get(endpoints.umum, params)
                .done(function(res) {
                    if (!res.total_identitas && !Object.keys(res.jenis_gula || {}).length && !Object.keys(res.top_supplier || {}).length) {
                        $('#card-umum').html(`
                        <div class="text-center py-5">
                            <div class="icon-wrapper mx-auto mb-3 p-3 rounded-4 bg-light">
                                <i class="ri-database-2-line fs-2 text-muted"></i>
                            </div>
                            <h6 class="text-muted mb-2">Tidak ada data ringkasan</h6>
                            <p class="text-muted small mb-0">Data tidak tersedia untuk periode yang dipilih</p>
                        </div>
                    `);
                        return;
                    }

                    $('#card-umum').hide().html(`
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 bg-gradient h-100" style="background: linear-gradient(135deg,#667eea,#764ba2);">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mx-auto mb-3 p-3 rounded-4 bg-white bg-opacity-20">
                                        <i class="ri-database-2-line fs-2"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Total Sample</h6>
                                    <h1 class="display-5 fw-bold mb-2">${res.total_identitas}</h1>
                                    <p class="small text-muted mb-0">Sample Masuk Keseluruhan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 h-100 shadow-sm">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-3">Jenis Gula</h6>
                                    ${Object.entries(res.jenis_gula).map(([k, v]) => `
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded bg-light">
                                            <span>${k}</span>
                                            <span class="badge bg-info rounded-pill px-3 py-2">${v}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card border-0 h-100 shadow-sm">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-3">Top Supplier</h6>
                                    ${Object.entries(res.top_supplier).map(([k,v],i) => `
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded ${i===0?'bg-success bg-opacity-10':'bg-light'}">
                                            <div><i class="${i===0?'ri-trophy-line text-success':'ri-user-line text-muted'} me-2"></i>${k}</div>
                                            <span class="badge ${i===0?'bg-success':'bg-secondary'} rounded-pill px-3 py-2">${v}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `).fadeIn(400); // smooth muncul
                });

            // Parameter kualitas per jenis
            ['Gula', 'Garam', 'Gula Tebu', 'Gula Kelapa'].forEach(jenis => {
                const containerId = 'chart-' + slugify(jenis);
                $(`#${containerId}`).html(loadingHtml);

                $.get(endpoints.parameter, {
                        ...params,
                        jenis_gula: jenis
                    })
                    .done(function(res) {
                        renderParameterChart(jenis, res.data, res.analisa);
                    })
                    .fail(function(xhr, status, error) {
                        console.error(`Error loading parameter for ${jenis}:`, error);
                        const el = document.querySelector(`#${containerId}`);
                        el.innerHTML = `
                        <div class="text-center py-5 text-muted">
                            <i class="ri-error-warning-line fs-2 mb-3"></i>
                            <div>Gagal memuat data ${jenis}</div>
                        </div>
                    `;
                    });
            });

            // Disposisi chart
            $('#chart-disposisi-total').html(loadingHtml);
            $.get(endpoints.disposisi, params)
                .done(function(res) {
                    renderDisposisiChart(res.rekap_disposisi, res.total_pending_disposisi);
                })
                .fail(function(xhr, status, error) {
                    console.error('Error loading disposisi:', error);
                    $('#chart-disposisi-total').html(`
                    <div class="text-center py-5 text-muted">
                        <i class="ri-error-warning-line fs-2 mb-3"></i>
                        <div>Gagal memuat data disposisi</div>
                    </div>
                `);
                    $('#pending-count').text('0');
                });
        }

        // Pertama kali load tanpa filter
        loadDashboard();

        // Event klik filter dengan debounce untuk mencegah multiple requests
        let filterTimeout;
        $('#btn-filter').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            // Show loading state
            $btn.prop('disabled', true).html('<i class="ri-loader-4-line me-2"></i>Memuat...');

            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                const params = {
                    jenis_gula: $('#filter-jenis-gula').val(),
                    start_date: $('#filter-start-date').val(),
                    end_date: $('#filter-end-date').val()
                };

                loadDashboard(params);

                // Reset button state
                setTimeout(() => {
                    $btn.prop('disabled', false).html(originalText);
                }, 1000);
            }, 300);
        });

        // Cleanup on page unload
        $(window).on('beforeunload', function() {
            Object.values(chartInstances).forEach(chart => {
                if (chart && typeof chart.destroy === 'function') {
                    chart.destroy();
                }
            });
        });
    });
</script>


@endsection