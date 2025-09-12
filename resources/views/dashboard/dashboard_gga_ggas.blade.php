@extends('layouts.app')

@section('content')
<!-- Enhanced page title with gradient background -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-3 p-4 mb-4 shadow-sm">
            <div>
                <h4 class="mb-1 text-white fw-bold">GGA & GGAS Analytics Dashboard</h4>
                <p class="text-white-75 mb-0">Comprehensive analysis and monitoring system</p>
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
                            Analytics
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main Analysis Charts Section -->
<div class="row g-4 mb-5">
    <!-- GGA Chart -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">GGA Analysis</h5>
                        <p class="text-muted mb-0 small">Brix & NaCl monitoring trends</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-line me-1"></i>Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-4 shadow-lg border-0" style="min-width: 280px;">
                            <h6 class="dropdown-header text-uppercase fw-bold text-primary mb-3">
                                <i class="ri-calendar-line me-1"></i>Date Range Filter
                            </h6>
                            <div class="mb-3">
                                <label for="start_date_gga" class="form-label small fw-semibold">Start Date</label>
                                <input type="date" id="start_date_gga" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="end_date_gga" class="form-label small fw-semibold">End Date</label>
                                <input type="date" id="end_date_gga" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="variant_gga" class="form-label small fw-semibold">Variant</label>
                                <select id="variant_gga" class="form-select form-select-sm">
                                    <option value="">-- All Variants --</option>
                                    <option value="SS1">SS1</option>
                                    <option value="SS2">SS2</option>
                                    <option value="BB">BB</option>
                                    <option value="MSD NR1">MSD NR1</option>
                                    <option value="MSD NR2">MSD NR2</option>
                                    <option value="JB">JB</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100 btn-sm" id="filter_gga">
                                <i class="ri-search-line me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="chart-gga" class="apex-charts"></div>
            </div>
        </div>
    </div>

    <!-- GGAS Chart -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">GGAS Analysis</h5>
                        <p class="text-muted mb-0 small">Brix & NaCl monitoring trends</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-line me-1"></i>Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-4 shadow-lg border-0" style="min-width: 280px;">
                            <h6 class="dropdown-header text-uppercase fw-bold text-success mb-3">
                                <i class="ri-calendar-line me-1"></i>Date Range Filter
                            </h6>
                            <div class="mb-3">
                                <label for="start_date_ggas" class="form-label small fw-semibold">Start Date</label>
                                <input type="date" id="start_date_ggas" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="end_date_ggas" class="form-label small fw-semibold">End Date</label>
                                <input type="date" id="end_date_ggas" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="variant_ggas" class="form-label small fw-semibold">Variant</label>
                                <select id="variant_ggas" class="form-select form-select-sm">
                                    <option value="">-- All Variants --</option>
                                    <option value="SS1">SS1</option>
                                    <option value="SS2">SS2</option>
                                    <option value="BB">BB</option>
                                    <option value="MSD NR1">MSD NR1</option>
                                    <option value="MSD NR2">MSD NR2</option>
                                    <option value="JB">JB</option>
                                </select>
                            </div>
                            <button class="btn btn-success w-100 btn-sm" id="filter_ggas">
                                <i class="ri-search-line me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="chart-ggas" class="apex-charts"></div>
            </div>
        </div>
    </div>
</div>

<!-- Disposition Analysis Section -->
<div class="row g-4">
    <div class="col-12 mb-3">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="mb-1 text-dark fw-bold">
                    <i class="ri-pie-chart-line text-warning me-2"></i>Disposition Analysis
                </h5>
                <p class="text-muted mb-0">Distribution analysis by disposition types</p>
            </div>
            <div class="badge bg-soft-info text-info px-3 py-2">
                <i class="ri-information-line me-1"></i>Real-time Data
            </div>
        </div>
        <hr class="my-3">
    </div>

    <!-- GGA Disposition -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-lg h-100 position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-primary rounded-top" style="height: 4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">
                            <i class="ri-bar-chart-box-line text-primary me-2"></i>GGA Disposition
                        </h5>
                        <p class="text-muted mb-0 small">Distribution breakdown analysis</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-line me-1"></i>Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-4 shadow-lg border-0" style="min-width: 280px;">
                            <h6 class="dropdown-header text-uppercase fw-bold text-primary mb-3">
                                <i class="ri-calendar-line me-1"></i>Date Range Filter
                            </h6>
                            <div class="mb-3">
                                <label for="start_date_gga_disposisi" class="form-label small fw-semibold">Start Date</label>
                                <input type="date" id="start_date_gga_disposisi" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="end_date_gga_disposisi" class="form-label small fw-semibold">End Date</label>
                                <input type="date" id="end_date_gga_disposisi" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="variant_gga_disposisi" class="form-label small fw-semibold">Variant</label>
                                <select id="variant_gga_disposisi" class="form-select form-select-sm">
                                    <option value="">-- All Variants --</option>
                                    <option value="SS1">SS1</option>
                                    <option value="SS2">SS2</option>
                                    <option value="BB">BB</option>
                                    <option value="MSD NR1">MSD NR1</option>
                                    <option value="MSD NR2">MSD NR2</option>
                                    <option value="JB">JB</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100 btn-sm" id="filter_gga_disposisi">
                                <i class="ri-search-line me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="disposition-gga" class="apex-charts"></div>
            </div>
        </div>
    </div>

    <!-- GGAS Disposition -->
    <div class="col-xl-6">
        <div class="card border-0 shadow-lg h-100 position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-success rounded-top" style="height: 4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">
                            <i class="ri-bar-chart-box-line text-success me-2"></i>GGAS Disposition
                        </h5>
                        <p class="text-muted mb-0 small">Distribution breakdown analysis</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-filter-line me-1"></i>Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-4 shadow-lg border-0" style="min-width: 280px;">
                            <h6 class="dropdown-header text-uppercase fw-bold text-success mb-3">
                                <i class="ri-calendar-line me-1"></i>Date Range Filter
                            </h6>
                            <div class="mb-3">
                                <label for="start_date_ggas_disposisi" class="form-label small fw-semibold">Start Date</label>
                                <input type="date" id="start_date_ggas_disposisi" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="end_date_ggas_disposisi" class="form-label small fw-semibold">End Date</label>
                                <input type="date" id="end_date_ggas_disposisi" class="form-control form-control-sm">
                            </div>
                            <div class="mb-3">
                                <label for="variant_ggas_disposisi" class="form-label small fw-semibold">Variant</label>
                                <select id="variant_ggas_disposisi" class="form-select form-select-sm">
                                    <option value="">-- All Variants --</option>
                                    <option value="SS1">SS1</option>
                                    <option value="SS2">SS2</option>
                                    <option value="BB">BB</option>
                                    <option value="MSD NR1">MSD NR1</option>
                                    <option value="MSD NR2">MSD NR2</option>
                                    <option value="JB">JB</option>
                                </select>
                            </div>
                            <button class="btn btn-success w-100 btn-sm" id="filter_ggas_disposisi">
                                <i class="ri-search-line me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="disposition-ggas" class="apex-charts"></div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .dropdown-menu {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .bg-soft-info {
        background-color: rgba(13, 202, 240, 0.1);
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
        // Global variables to store chart instances
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
            showLoading('#chart-gga');
            showLoading('#chart-ggas');
            showLoading('#disposition-gga');
            showLoading('#disposition-ggas');

            fetchChartData('gga');
            fetchChartData('ggas');
            fetchDispositionData('gga');
            fetchDispositionData('ggas');
        }

        // Event handlers for filters
        $('#filter_gga').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();
            showLoading('#chart-gga');

            const start = $('#start_date_gga').val();
            const end = $('#end_date_gga').val();
            const variant = $('#variant_gga').val();

            fetchChartData('gga', start, end, variant).finally(() => {
                $btn.html(originalText).prop('disabled', false);
            });
        });

        $('#filter_ggas').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            showLoading('#chart-ggas');

            const start = $('#start_date_ggas').val();
            const end = $('#end_date_ggas').val();
            const variant = $('#variant_ggas').val();

            fetchChartData('ggas', start, end, variant).finally(() => {
                $btn.html(originalText).prop('disabled', false);
            });
        });

        $('#filter_gga_disposisi').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            showLoading('#disposition-gga');

            const start = $('#start_date_gga_disposisi').val();
            const end = $('#end_date_gga_disposisi').val();
            const variant = $('#variant_gga_disposisi').val();

            fetchDispositionData('gga', start, end, variant).finally(() => {
                $btn.html(originalText).prop('disabled', false);
            });
        });

        $('#filter_ggas_disposisi').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            showLoading('#disposition-ggas');

            const start = $('#start_date_ggas_disposisi').val();
            const end = $('#end_date_ggas_disposisi').val();
            const variant = $('#variant_ggas_disposisi').val();

            fetchDispositionData('ggas', start, end, variant).finally(() => {
                $btn.html(originalText).prop('disabled', false);
            });
        });

        // Fetch chart data function
        function fetchChartData(type, startDate = null, endDate = null, variant = null) {
            let url = "{{url('/api/ggas/gga/analysis')}}";
            let params = [];

            if (startDate && endDate) {
                params.push(`start_date=${startDate}&end_date=${endDate}`);
            }
            if (variant) {
                params.push(`variant=${encodeURIComponent(variant)}`);
            }

            if (params.length > 0) {
                url += `?${params.join('&')}`;
            }

            return $.getJSON(url)
                .done(function(response) {
                    const data = type === 'gga' ? response.gga : response.ggas;
                    renderChart(`#chart-${type}`, data, type.toUpperCase());
                })
                .fail(function(xhr) {
                    console.error(`Error fetching ${type} chart data:`, xhr);
                    $(`#chart-${type}`).html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            <div>Error loading ${type.toUpperCase()} chart data. Please try again.</div>
                        </div>
                    `);
                });
        }

        // Fetch disposition data function
        function fetchDispositionData(type, startDate = null, endDate = null, variant = null) {
            let url = "{{url('/api/ggas/gga/disposition-analysis')}}";
            let params = [];

            if (startDate && endDate) {
                params.push(`start_date=${startDate}&end_date=${endDate}`);
            }
            if (variant) {
                params.push(`variant=${encodeURIComponent(variant)}`);
            }

            if (params.length > 0) {
                url += `?${params.join('&')}`;
            }

            return $.getJSON(url)
                .done(function(response) {
                    const data = type === 'gga' ? response.gga : response.ggas;
                    renderBarChartDisposisi(`#disposition-${type}`, data, type.toUpperCase());
                })
                .fail(function(xhr) {
                    console.error(`Error fetching ${type} disposition data:`, xhr);
                    $(`#disposition-${type}`).html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            <div>Error loading ${type.toUpperCase()} disposition data. Please try again.</div>
                        </div>
                    `);
                });
        }

        // Render line chart function
        function renderChart(selector, data, title) {
            // Destroy existing chart instance
            if (chartInstances[selector]) {
                chartInstances[selector].destroy();
                delete chartInstances[selector];
            }

            // Handle empty data
            if (!data || !Array.isArray(data) || data.length === 0) {
                $(selector).html(`
                    <div class="alert alert-info d-flex align-items-center justify-content-center" role="alert" style="height:300px;">
                        <i class="ri-information-line me-2"></i>
                        <div>No ${title} data available for the selected filters.</div>
                    </div>
                `);
                return;
            }

            const categories = data.map(item => `Batch ${item.batch_number} - ${item.variant} (PO: ${item.po_number})`);
            const brixSeries = data.map(item => parseFloat(item.brix) || 0);
            const naclSeries = data.map(item => parseFloat(item.nacl) || 0);
            const metaData = data.map(item => ({
                po: item.po_number,
                variant: item.variant,
                batch: item.batch_number,
                label: `Batch ${item.batch_number} - ${item.variant} (PO: ${item.po_number})`
            }));

            const options = {
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
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
                    name: 'Brix',
                    data: brixSeries,
                    color: '#667eea'
                }, {
                    name: 'NaCl',
                    data: naclSeries,
                    color: '#11998e'
                }],
                xaxis: {
                    categories: categories,
                    type: 'category',
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Concentration (°Bx)',
                        style: {
                            fontWeight: 600
                        }
                    }
                },
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
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 3
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontWeight: 600
                },
                title: {
                    text: `${title} Brix & NaCl Analysis`,
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 700,
                        color: '#2d3748'
                    }
                },
                tooltip: {
                    shared: true,
                    custom: function({
                        series,
                        dataPointIndex
                    }) {
                        if (dataPointIndex < 0 || !metaData[dataPointIndex]) return '';

                        const brix = series[0][dataPointIndex];
                        const nacl = series[1][dataPointIndex];
                        const meta = metaData[dataPointIndex];

                        return `
                            <div class="apex-tooltip p-3 bg-white shadow-lg border-0 rounded">
                                <div class="fw-bold text-dark mb-2">${meta.label}</div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="bg-primary rounded me-2" style="width: 12px; height: 12px;"></div>
                                    <span class="small">Brix: <strong>${brix.toFixed(2)} °Bx</strong></span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="bg-success rounded me-2" style="width: 12px; height: 12px;"></div>
                                    <span class="small">NaCl: <strong>${nacl.toFixed(2)} °Bx</strong></span>
                                </div>
                                <div class="small text-muted mt-2">
                                    <div>PO: ${meta.po}</div>
                                    <div>Variant: ${meta.variant}</div>
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

        // Render bar chart function
        function renderBarChartDisposisi(selector, data, title) {
            // Destroy existing chart instance
            if (chartInstances[selector]) {
                chartInstances[selector].destroy();
                delete chartInstances[selector];
            }

            // Handle empty data
            if (!data || typeof data !== 'object' || Object.keys(data).length === 0) {
                $(selector).html(`
                    <div class="alert alert-info d-flex align-items-center justify-content-center" role="alert" style="height:300px;">
                        <i class="ri-information-line me-2"></i>
                        <div>No ${title} disposition data available for the selected filters.</div>
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
                    color: title === 'GGA' ? '#667eea' : '#11998e'
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
                    text: `${title} Disposition Summary`,
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
                    },
                    style: {
                        fontSize: '12px'
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