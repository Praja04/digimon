@extends('layouts.app')

@section('content')
<!-- Enhanced page title with gradient background -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-blending rounded-3 p-4 mb-4 shadow-sm">
            <div>
                <h4 class="mb-1 text-white fw-bold">Blending After Adjust Analytics Dashboard</h4>
                <p class="text-white-75 mb-0">Comprehensive blending after adjustment parameter analysis and monitoring</p>
            </div>
            <div class="page-title-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);" class="text-white-75 text-decoration-none">
                                <i class="ri-dashboard-line me-1"></i>Dashboards
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            Blending After Adjust Analytics
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="mb-1 text-dark fw-bold">
                            <i class="ri-filter-3-line text-primary me-2"></i>Advanced Filters
                        </h5>
                        <p class="text-muted mb-0 small">Filter data by date range and variant to analyze specific periods</p>
                    </div>
                    <div class="badge bg-soft-primary text-primary px-3 py-2">
                        <i class="ri-calendar-check-line me-1"></i>Advanced Filter
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold">
                            <i class="ri-calendar-line me-1"></i>Start Date
                        </label>
                        <input type="date" id="start_date" class="form-control form-control-lg">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold">
                            <i class="ri-calendar-line me-1"></i>End Date
                        </label>
                        <input type="date" id="end_date" class="form-control form-control-lg">
                    </div>
                    <div class="col-md-3">
                        <label for="variant" class="form-label fw-semibold">
                            <i class="ri-flask-line me-1"></i>Variant
                        </label>
                        <select id="variant" class="form-select form-select-lg">
                            <option value="">-- All Variants --</option>
                            <option value="SS1">SS1</option>
                            <option value="SS2">SS2</option>
                            <option value="BB">BB</option>
                            <option value="MSD NR1">MSD NR1</option>
                            <option value="MSD NR2">MSD NR2</option>
                            <option value="JB">JB</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-lg flex-fill" id="reset-filter">
                                <i class="ri-refresh-line me-1"></i>Reset
                            </button>
                            <button class="btn btn-primary btn-lg flex-fill" id="filter-data">
                                <i class="ri-search-line me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disposition Analysis Section -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-warning rounded-top" style="height: 4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1 text-dark fw-bold">
                            <i class="ri-bar-chart-box-line text-warning me-2"></i>Disposition Analysis After Adjustment
                        </h5>
                        <p class="text-muted mb-0 small">Distribution breakdown by disposition types after adjustment</p>
                    </div>
                    <div class="badge bg-soft-warning text-warning px-3 py-2">
                        <i class="ri-pie-chart-line me-1"></i>Summary View
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="chart-disposition-blending" class="apex-charts"></div>
            </div>
        </div>
    </div>
</div>

<!-- Parameter Analysis Section -->
<div class="row g-4">
    <div class="col-12 mb-3">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="mb-1 text-dark fw-bold">
                    <i class="ri-line-chart-line text-success me-2"></i>Parameter Trend Analysis After Adjustment
                </h5>
                <p class="text-muted mb-0">Detailed analysis of blending parameters after adjustment over time</p>
            </div>
            <div class="badge bg-soft-success text-success px-3 py-2">
                <i class="ri-trending-up-line me-1"></i>Live Monitoring
            </div>
        </div>
        <hr class="my-3">
    </div>

    @php
    $parameters = [
    'brix' => ['name' => 'Brix', 'icon' => 'ri-drop-line', 'color' => 'primary'],
    'nacl' => ['name' => 'NaCl', 'icon' => 'ri-contrast-drop-line', 'color' => 'info'],
    'bj' => ['name' => 'BJ', 'icon' => 'ri-test-tube-line', 'color' => 'success'],
    'visco' => ['name' => 'Viscosity', 'icon' => 'ri-water-percent-line', 'color' => 'warning'],
    'aw' => ['name' => 'AW', 'icon' => 'ri-reactjs-line', 'color' => 'danger'],
    'buih' => ['name' => 'Buih', 'icon' => 'ri-bubble-chart-line', 'color' => 'secondary'],
    'organo' => ['name' => 'Organo', 'icon' => 'ri-flask-line', 'color' => 'dark'],
    'ph' => ['name' => 'pH', 'icon' => 'ri-equalizer-line', 'color' => 'purple']
    ];
    @endphp

    @foreach ($parameters as $param => $config)
    <div class="col-xl-6 mb-4">
        <div class="card border-0 shadow-lg h-100 position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-{{ $config['color'] }} rounded-top" style="height: 4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-title mb-1 text-dark fw-bold">
                            <i class="{{ $config['icon'] }} text-{{ $config['color'] }} me-2"></i>{{ $config['name'] }} Analysis (After Adjust)
                        </h6>
                        <p class="text-muted mb-0 small">Monitoring {{ strtolower($config['name']) }} parameter trends after adjustment</p>
                    </div>
                    <div class="badge bg-soft-{{ $config['color'] }} text-{{ $config['color'] }} px-2 py-1 small">
                        {{ strtoupper($param) }}
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="chart-{{ $param }}" class="apex-charts"></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Custom CSS for enhanced styling -->
<style>
    .bg-gradient-blending {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    }

    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    .bg-gradient-dark {
        background: linear-gradient(135deg, #343a40 0%, #212529 100%);
    }

    .bg-gradient-purple {
        background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    .text-purple {
        color: #6f42c1;
    }

    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .bg-soft-primary {
        background-color: rgba(102, 126, 234, 0.1);
    }

    .bg-soft-info {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .bg-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-soft-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .bg-soft-secondary {
        background-color: rgba(108, 117, 125, 0.1);
    }

    .bg-soft-purple {
        background-color: rgba(111, 66, 193, 0.1);
    }

    .apex-charts {
        min-height: 320px;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }

    @media (max-width: 768px) {
        .page-title-box {
            text-align: center;
        }

        .page-title-box .page-title-right {
            margin-top: 1rem;
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .spin {
        animation: spin 1s linear infinite;
    }
</style>

<!-- Enhanced JavaScript with loading states -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>
<script>
    $(document).ready(function() {
        const params = ['brix', 'nacl', 'bj', 'visco', 'aw', 'buih', 'organo', 'ph'];
        let chartInstances = {};

        // Loading spinner function
        function showLoading(selector) {
            $(selector).html(`
                <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
        }

        // Initialize dashboard
        function initializeDashboard() {
            // Show loading for all charts
            showLoading('#chart-disposition-blending');
            params.forEach(param => showLoading(`#chart-${param}`));

            // Load default data
            fetchBlendingData();
            fetchDispositionData();
        }

        // Reset filter handler
        $('#reset-filter').on('click', function() {
            $('#start_date, #end_date, #variant').val('');
            showLoading('#chart-disposition-blending');
            params.forEach(param => showLoading(`#chart-${param}`));
            fetchBlendingData();
            fetchDispositionData();
        });

        // Filter event handler
        $('#filter-data').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Loading...');

            const start = $('#start_date').val();
            const end = $('#end_date').val();
            const variant = $('#variant').val();

            // Show loading for all charts
            showLoading('#chart-disposition-blending');
            params.forEach(param => showLoading(`#chart-${param}`));

            Promise.all([
                fetchBlendingData(start, end, variant),
                fetchDispositionData(start, end, variant)
            ]).finally(() => {
                $btn.html(originalText).prop('disabled', false);
            });
        });

        // Fetch blending data function (Updated to use after adjust endpoint)
        function fetchBlendingData(startDate = null, endDate = null, variant = null) {
            let url = "{{url('/api/blending/after/analysis')}}";
            let params_url = [];

            if (startDate && endDate) {
                params_url.push(`start_date=${startDate}&end_date=${endDate}`);
            }
            if (variant) {
                params_url.push(`variant=${encodeURIComponent(variant)}`);
            }

            if (params_url.length > 0) {
                url += `?${params_url.join('&')}`;
            }

            return $.getJSON(url)
                .done(function(response) {
                    const data = response.blending_after_adjust || [];

                    params.forEach(param => {
                        const seriesData = data
                            .filter(d => d[param] !== null && d[param] !== undefined)
                            .map(item => ({
                                x: `Batch ${item.batch_range} (No ${item.nomor_blending})`,
                                y: parseFloat(item[param]) || 0,
                                meta: {
                                    po: item.po_number,
                                    variant: item.variant,
                                    batch: item.batch_range,
                                    nomor: item.nomor_blending,
                                    revisi: item.revisi
                                }
                            }));

                        renderLineChart(`#chart-${param}`, seriesData, param.toUpperCase());
                    });
                })
                .fail(function(xhr) {
                    console.error('Error fetching blending data:', xhr);
                    params.forEach(param => {
                        $(`#chart-${param}`).html(`
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="ri-error-warning-line me-2"></i>
                                <div>Error loading ${param.toUpperCase()} data. Please try again.</div>
                            </div>
                        `);
                    });
                });
        }

        // Fetch disposition data function (Updated to use after adjust endpoint)
        function fetchDispositionData(startDate = null, endDate = null, variant = null) {
            let url = "{{url('/api/blending/after/disposition-analysis')}}";
            let params_url = [];

            if (startDate && endDate) {
                params_url.push(`start_date=${startDate}&end_date=${endDate}`);
            }
            if (variant) {
                params_url.push(`variant=${encodeURIComponent(variant)}`);
            }

            if (params_url.length > 0) {
                url += `?${params_url.join('&')}`;
            }

            return $.getJSON(url)
                .done(function(response) {
                    const summary = response.disposition_summary || {};
                    renderDispositionChart(summary);
                })
                .fail(function(xhr) {
                    console.error('Error fetching disposition data:', xhr);
                    $('#chart-disposition-blending').html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            <div>Error loading disposition data. Please try again.</div>
                        </div>
                    `);
                });
        }

        // Render disposition chart function
        function renderDispositionChart(data) {
            // Destroy existing chart
            if (chartInstances['#chart-disposition-blending']) {
                chartInstances['#chart-disposition-blending'].destroy();
                delete chartInstances['#chart-disposition-blending'];
            }

            if (!data || Object.keys(data).length === 0) {
                $('#chart-disposition-blending').html(`
                    <div class="alert alert-info d-flex align-items-center justify-content-center" role="alert" style="height:300px;">
                        <i class="ri-information-line me-2"></i>
                        <div>No disposition data available for the selected period.</div>
                    </div>
                `);
                return;
            }

            const labels = Object.keys(data);
            const counts = Object.values(data);

            const options = {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: [{
                    name: 'Total Cases',
                    data: counts,
                    color: '#ffc107'
                }],
                xaxis: {
                    categories: labels,
                    title: {
                        text: 'Disposition Type',
                        style: {
                            fontWeight: 600
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Total Cases',
                        style: {
                            fontWeight: 600
                        }
                    }
                },
                title: {
                    text: 'Blending After Adjust Disposition Summary',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 700,
                        color: '#2d3748'
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val + ' cases';
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        fontWeight: 600,
                        colors: ['#304758']
                    }
                },
                tooltip: {
                    y: {
                        formatter: val => `${val} kasus`
                    }
                }
            };

            $('#chart-disposition-blending').html('');
            chartInstances['#chart-disposition-blending'] = new ApexCharts(document.querySelector('#chart-disposition-blending'), options);
            chartInstances['#chart-disposition-blending'].render();
        }

        // Render line chart function
        function renderLineChart(selector, seriesData, title) {
            // Destroy existing chart
            if (chartInstances[selector]) {
                chartInstances[selector].destroy();
                delete chartInstances[selector];
            }

            if (!seriesData || seriesData.length === 0) {
                $(selector).html(`
                    <div class="alert alert-info d-flex align-items-center justify-content-center" role="alert" style="height:300px;">
                        <i class="ri-information-line me-2"></i>
                        <div>No ${title} data available for the selected period.</div>
                    </div>
                `);
                return;
            }

            // Color mapping for different parameters
            const colorMap = {
                'BRIX': '#667eea',
                'NACL': '#17a2b8',
                'BJ': '#28a745',
                'VISCO': '#ffc107',
                'AW': '#dc3545',
                'BUIH': '#6c757d',
                'ORGANO': '#343a40',
                'PH': '#6f42c1'
            };

            const options = {
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: false,
                            reset: true
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: [{
                    name: title + ' (After Adjust)',
                    data: seriesData,
                    color: colorMap[title] || '#667eea'
                }],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 6,
                    hover: {
                        size: 8
                    }
                },
                title: {
                    text: `${title} Trend Analysis (After Adjustment)`,
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 700,
                        color: '#2d3748'
                    }
                },
                xaxis: {
                    type: 'category',
                    title: {
                        text: 'Blending Number / Range',
                        style: {
                            fontWeight: 600
                        }
                    },
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: title,
                        style: {
                            fontWeight: 600
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3
                },
                tooltip: {
                    shared: false,
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const item = w.config.series[seriesIndex].data[dataPointIndex];
                        if (!item || typeof item.y === 'undefined') {
                            return `<div class="apex-tooltip p-2">Data tidak tersedia</div>`;
                        }

                        return `
                            <div class="apex-tooltip p-3 bg-white shadow-lg border-0 rounded">
                                <div class="fw-bold text-dark mb-2">${title}: ${item.y.toFixed(2)}</div>
                                <div class="small text-muted">
                                    <div>Batch: ${item.meta?.batch || '-'}</div>
                                    <div>Nomor: ${item.meta?.nomor || '-'}</div>
                                    <div>PO: ${item.meta?.po || '-'}</div>
                                    <div>Variant: ${item.meta?.variant || '-'}</div>
                                    <div>Revisi: ${item.meta?.revisi || '0'}</div>
                                </div>
                            </div>
                        `;
                    }
                }
            };

            $(selector).html('');
            chartInstances[selector] = new ApexCharts(document.querySelector(selector), options);
            chartInstances[selector].render();
        }

        // Initialize the dashboard
        initializeDashboard();
    });
</script>

@endsection