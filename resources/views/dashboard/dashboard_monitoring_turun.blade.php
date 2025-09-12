@extends('layouts.app')

@section('content')
<!-- Enhanced Page Title with Gradient -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-3 p-4 mb-4 shadow-sm">
            <div>
                <h4 class="mb-1 text-white fw-bold">Monitoring Turun Blending</h4>
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
                        <p class="text-muted mb-0 small">
                            Filter data by date range and variant to analyze specific periods
                        </p>
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

<!-- Disposition Chart -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg h-100 position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-success rounded-top" style="height: 4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">
                            <i class="ri-bar-chart-2-line text-success me-2"></i>Disposition Analysis
                        </h5>
                        <p class="text-muted mb-0 small">Distribution of disposition types</p>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div id="chart-disposition-blending" class="apex-charts"></div>
            </div>
        </div>
    </div>
</div>

<!-- Parameter Charts -->
@php
$parameters = ['brix', 'nacl', 'bj', 'visco', 'aw', 'buih', 'organo', 'ph'];
@endphp

<div class="row g-4">
    @foreach ($parameters as $param)
    <div class="col-xl-6">
        <div class="card border-0 shadow-lg h-100 position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-primary rounded-top" style="height: 4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1 text-dark fw-bold">{{ strtoupper($param) }} Analysis</h5>
                        <p class="text-muted mb-0 small">Trend monitoring for {{ strtoupper($param) }}</p>
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

<!-- Custom CSS -->
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

    .apex-charts {
        min-height: 320px;
    }

    @media (max-width: 768px) {
        .page-title-box {
            text-align: center;
        }

        .page-title-box .page-title-right {
            margin-top: 1rem;
        }
    }
</style>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>
<script>
    $(document).ready(function() {
        var parameterList = ['brix', 'nacl', 'bj', 'visco', 'aw', 'buih', 'organo', 'ph'];
        let chartInstances = {};

        // Spinner
        function showLoading(selector) {
            $(selector).html(`
            <div class="d-flex justify-content-center align-items-center" style="height: 280px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        }

        // Init
        initializeDashboard();

        // Apply filter
        $('#filter-data').on('click', function() {
            const start = $('#start_date').val();
            const end = $('#end_date').val();
            const variant = $('#variant').val();

            initializeDashboard(start, end, variant);
        });

        // Reset filter
        $('#reset-filter').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#variant').val('');
            initializeDashboard();
        });

        function initializeDashboard(start = null, end = null, variant = null) {
            showLoading('#chart-disposition-blending');
            parameterList.forEach(p => showLoading(`#chart-${p}`));

            loadMonitoringCharts(start, end, variant);
            loadDispositionChart(start, end, variant);
        }

        // Load monitoring chart
        function loadMonitoringCharts(startDate = null, endDate = null, variant = null) {
            let url = "{{url('/api/monitoring/turun/analysis')}}";
            let params = [];

            if (startDate) params.push(`start_date=${startDate}`);
            if (endDate) params.push(`end_date=${endDate}`);
            if (variant) params.push(`variant=${variant}`);

            if (params.length) url += `?${params.join('&')}`;

            $.getJSON(url, function(response) {
                const monitoringData = response.monitoring_turun_blending || [];

                parameterList.forEach(parameter => {
                    const series = monitoringData
                        .filter(entry => entry[parameter] !== null)
                        .map(entry => ({
                            x: `Batch ${entry.batch_range} • Shift ${entry.shift}`,
                            y: parseFloat(entry[parameter]),
                            meta: {
                                po: entry.po_number,
                                variant: entry.variant
                            }
                        }));

                    renderLineChart(`#chart-${parameter}`, series, parameter.toUpperCase());
                });
            });
        }

        // Load disposition chart
        function loadDispositionChart(startDate = null, endDate = null, variant = null) {
            let url = "{{url('/api/monitoring/turun/disposition-analysis')}}";
            let params = [];

            if (startDate) params.push(`start_date=${startDate}`);
            if (endDate) params.push(`end_date=${endDate}`);
            if (variant) params.push(`variant=${variant}`);

            if (params.length) url += `?${params.join('&')}`;

            $.getJSON(url, function(response) {
                const disposition = response.disposition_summary || {};
                renderBarChart('#chart-disposition-blending', disposition, 'Disposition');
            });
        }

        // Render line chart
        function renderLineChart(selector, data, title) {
            if (chartInstances[selector]) {
                chartInstances[selector].destroy();
                delete chartInstances[selector];
            }

            if (!data || data.length === 0) {
                $(selector).html(`
                <div class="alert alert-info text-center" role="alert" style="height:300px;">
                    <i class="ri-information-line me-2"></i>No ${title} data available.
                </div>
            `);
                return;
            }

            const categories = data.map(p => p.x);
            const values = data.map(p => p.y);
            const metaData = data.map(p => p.meta);

            const options = {
                chart: {
                    type: 'line',
                    height: 320,
                    animations: {
                        enabled: true
                    }
                },
                series: [{
                    name: title,
                    data: values
                }],
                xaxis: {
                    categories: categories
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5
                },
                tooltip: {
                    custom: function({
                        series,
                        dataPointIndex
                    }) {
                        const meta = metaData[dataPointIndex];
                        return `
                        <div class="p-2">
                            <strong>${title}: ${series[0][dataPointIndex]}</strong><br/>
                            PO: ${meta.po}<br/>
                            Variant: ${meta.variant}
                        </div>
                    `;
                    }
                }
            };

            $(selector).html('');
            chartInstances[selector] = new ApexCharts(document.querySelector(selector), options);
            chartInstances[selector].render();
        }

        // Render bar chart
        function renderBarChart(selector, data, title) {
            if (chartInstances[selector]) {
                chartInstances[selector].destroy();
                delete chartInstances[selector];
            }

            if (!data || Object.keys(data).length === 0) {
                $(selector).html(`
                <div class="alert alert-info text-center" role="alert" style="height:300px;">
                    <i class="ri-information-line me-2"></i>No ${title} data available.
                </div>
            `);
                return;
            }

            const options = {
                chart: {
                    type: 'bar',
                    height: 320
                },
                series: [{
                    name: 'Jumlah',
                    data: Object.values(data),
                    color: '#11998e'
                }],
                xaxis: {
                    categories: Object.keys(data)
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
                    formatter: val => `${val} cases`
                }
            };

            $(selector).html('');
            chartInstances[selector] = new ApexCharts(document.querySelector(selector), options);
            chartInstances[selector].render();
        }
    });
</script>
@endsection