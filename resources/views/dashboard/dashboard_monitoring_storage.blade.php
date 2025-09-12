@extends('layouts.app')

@section('content')
<!-- Enhanced Page Title with Gradient -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-3 p-4 mb-4 shadow-sm">
            <div>
                <h4 class="mb-1 text-white fw-bold">Monitoring Storage</h4>
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
                            <option value="SS3">SS3</option>
                            <option value="SS4">SS4</option>
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
                <!-- Filter Status Display -->
                <div class="mt-3">
                    <div id="filter-status" class="badge bg-soft-info text-info px-3 py-2" style="display: none;">
                        <i class="ri-information-line me-1"></i>
                        <span id="filter-status-text">All data displayed</span>
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
                <div id="chart-disposition-storage" class="apex-charts"></div>
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
<br><br>

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

<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>
<script>
    $(document).ready(function() {
        const params = ['brix', 'nacl', 'bj', 'visco', 'aw', 'buih', 'organo', 'ph'];

        // Initial load
        fetchData();
        fetchDispositionData();

        // Apply filter
        $('#filter-data').on('click', function() {
            const start = $('#start_date').val();
            const end = $('#end_date').val();
            const variant = $('#variant').val();

            console.log('Filter applied:', {
                start,
                end,
                variant
            });

            fetchData(start, end, variant);
            fetchDispositionData(start, end, variant);
            updateFilterStatus(start, end, variant);
        });

        // Reset filter
        $('#reset-filter').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#variant').val('');
            console.log('Filter reset');

            fetchData();
            fetchDispositionData();
            updateFilterStatus('', '', '');
        });

        // Update filter status display
        function updateFilterStatus(startDate, endDate, variant) {
            let statusText = 'Showing ';
            const filters = [];

            if (startDate && endDate) filters.push(`from ${startDate} to ${endDate}`);
            else if (startDate) filters.push(`from ${startDate}`);
            else if (endDate) filters.push(`until ${endDate}`);

            if (variant && variant !== '') filters.push(`variant: ${variant}`);

            if (filters.length > 0) {
                statusText += filters.join(', ');
                $('#filter-status-text').text(statusText);
                $('#filter-status').removeClass('bg-soft-info text-info').addClass('bg-soft-primary text-primary').show();
            } else {
                $('#filter-status-text').text('All data displayed');
                $('#filter-status').removeClass('bg-soft-primary text-primary').addClass('bg-soft-info text-info').show();
            }
        }

        // Helper: parsing numeric, skip if tidak valid
        function parseNumericValue(value) {
            if (value === null || value === undefined || value === '' || value === 'null') return null;
            const stringValue = String(value).trim();
            const numericMatch = stringValue.match(/^(\d+(?:[.,]\d+)?)/);
            if (numericMatch) return parseFloat(numericMatch[1].replace(',', '.'));
            return null;
        }

        // === Fetch & Render Monitoring Storage ===
        function fetchData(startDate = null, endDate = null, variant = null) {
            let url = "{{ url('/api/monitoring/storage/analysis') }}";
            const paramsUrl = [];

            if (startDate && endDate) paramsUrl.push(`start_date=${startDate}&end_date=${endDate}`);
            if (variant && variant !== '') paramsUrl.push(`variant=${variant}`);
            if (paramsUrl.length) url += '?' + paramsUrl.join('&');

            console.log('Fetching data from:', url);

            $.getJSON(url, function(response) {
                const data = response.monitoring_storage || [];

                params.forEach(param => {
                    const seriesData = data
                        .map(item => {
                            const numericValue = parseNumericValue(item[param]);
                            if (numericValue === null) return null; // skip null
                            return {
                                x: `Batch ${item.batch_range} (No ${item.nomor_blending}, ${item.variant})`,
                                y: numericValue,
                                meta: {
                                    po: item.po_number,
                                    variant: item.variant,
                                    originalValue: item[param],
                                    createdAt: item.created_at
                                }
                            };
                        })
                        .filter(item => item !== null);

                    renderLineChart(`#chart-${param}`, seriesData, param.toUpperCase());
                    console.log(`Data for ${param}:`, seriesData);
                });
            }).fail(function(xhr, status, error) {
                console.error('Error fetching data:', error);
            });
        }

        // === Fetch & Render Disposition Data ===
        function fetchDispositionData(startDate = null, endDate = null, variant = null) {
            let url = "{{ url('/api/monitoring/storage/disposition-analysis') }}";
            const paramsUrl = [];

            if (startDate && endDate) paramsUrl.push(`start_date=${startDate}&end_date=${endDate}`);
            if (variant && variant !== '') paramsUrl.push(`variant=${variant}`);
            if (paramsUrl.length) url += '?' + paramsUrl.join('&');

            $.getJSON(url, function(response) {
                const summary = response.disposition_summary || {};
                const labels = Object.keys(summary);
                const counts = Object.values(summary);

                const options = {
                    chart: {
                        type: 'bar',
                        height: 350,
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800
                        }
                    },
                    series: counts.length ? [{
                        name: 'Jumlah',
                        data: counts
                    }] : [],
                    noData: {
                        text: "No Data Found",
                        align: 'center',
                        verticalAlign: 'middle',
                        style: {
                            fontSize: '14px',
                            color: '#999'
                        }
                    },
                    xaxis: {
                        categories: labels,
                        title: {
                            text: 'Disposition Type'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Total Cases'
                        }
                    },
                    title: {
                        text: 'Monitoring Storage Disposition Summary',
                        align: 'left'
                    },
                    colors: ['#0AB39C'],
                    tooltip: {
                        y: {
                            formatter: val => `${val} kasus`
                        }
                    }
                };

                $('#chart-disposition-storage').html('');
                new ApexCharts(document.querySelector('#chart-disposition-storage'), options).render();
            }).fail(function(xhr, status, error) {
                console.error('Error fetching disposition data:', error);
            });
        }

        // === Render Line Chart ===
        function renderLineChart(selector, seriesData, title) {
            if (seriesData.length && seriesData[0].meta?.createdAt) {
                seriesData.sort((a, b) => new Date(a.meta.createdAt) - new Date(b.meta.createdAt));
            }

            const options = {
                chart: {
                    type: 'line',
                    height: 350,
                    zoom: {
                        enabled: true
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: seriesData.length ? [{
                    name: title,
                    data: seriesData
                }] : [],
                noData: {
                    text: "No Data Found",
                    align: 'center',
                    verticalAlign: 'middle',
                    style: {
                        fontSize: '14px',
                        color: '#999'
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 4,
                    hover: {
                        size: 6
                    }
                },
                title: {
                    text: `${title} Trend`,
                    align: 'left'
                },
                xaxis: {
                    type: 'category',
                    title: {
                        text: 'Batch'
                    },
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: title
                    },
                    labels: {
                        formatter: val => typeof val === 'number' ? val.toFixed(2) : val
                    }
                },
                tooltip: {
                    shared: false,
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const item = w.config.series[seriesIndex]?.data[dataPointIndex];
                        if (!item) return `<div class="apex-tooltip">Data tidak tersedia</div>`;
                        const formattedValue = typeof item.y === 'number' ? item.y.toFixed(2) : item.y;
                        return `
                        <div class="apex-tooltip" style="padding:8px; background:white; border:1px solid #ccc; border-radius:4px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
                            <strong>${title}: ${formattedValue}</strong><br/>
                            <small>PO: ${item.meta?.po || '-'}</small><br/>
                            <small>Variant: ${item.meta?.variant || '-'}</small><br/>
                            ${item.meta?.originalValue ? `<small>Original: ${item.meta.originalValue}</small>` : ''}
                        </div>
                    `;
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 4
                },
                colors: ['#667eea']
            };

            $(selector).html('');
            new ApexCharts(document.querySelector(selector), options).render();
        }

        // Initialize filter status
        updateFilterStatus('', '', '');
    });
</script>



@endsection