@extends('layouts.app')

@section('content')
<!-- Enhanced Page Title with Gradient -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-gradient-primary rounded-3 p-4 mb-4 shadow-sm">
            <div>
                <h4 class="mb-1 text-white fw-bold">Monitoring Blending Mikro</h4>
                <p class="text-white-75 mb-0">Comprehensive blending after adjust mikro analysis</p>
            </div>
            <div class="page-title-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);" class="text-white-75 text-decoration-none">
                                <i class="ri-dashboard-line me-1"></i>Dashboards
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            Blending Mikro
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
                        <p class="text-muted mb-0 small">Filter data by date range and variant</p>
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
                    <div id="filter-status" class="badge bg-soft-info text-info px-3 py-2" style="display:none;">
                        <i class="ri-information-line me-1"></i>
                        <span id="filter-status-text">All data displayed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Parameter Charts -->
@php
$parameters = ['eb', 'tpc', 'ym', 'hasil'];
@endphp

<div class="row g-4">
    @foreach ($parameters as $param)
    <div class="col-xl-6">
        <div class="card border-0 shadow-lg h-100 position-relative">
            <div class="position-absolute top-0 start-0 w-100 bg-gradient-primary rounded-top" style="height:4px;"></div>
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="card-title mb-1 text-dark fw-bold">{{ strtoupper($param) }} Analysis</h5>
                <p class="text-muted mb-0 small">Trend monitoring for {{ strtoupper($param) }}</p>
            </div>
            <div class="card-body pt-3">
                <div id="chart-{{ $param }}" class="apex-charts"></div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<br><br>
<div></div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    .apex-charts {
        min-height: 320px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        const params = ['eb', 'tpc', 'ym', 'hasil'];

        fetchData();

        $('#filter-data').on('click', function() {
            const s = $('#start_date').val();
            const e = $('#end_date').val();
            const v = $('#variant').val();
            fetchData(s, e, v);
            updateFilterStatus(s, e, v);
        });

        $('#reset-filter').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#variant').val('');
            fetchData();
            updateFilterStatus('', '', '');
        });

        function updateFilterStatus(s, e, v) {
            let txt = 'All data displayed';
            if (s || e || v) {
                txt = 'Filter: ';
                if (s && e) txt += `from ${s} to ${e} `;
                if (v) txt += `variant ${v}`;
            }
            $('#filter-status-text').text(txt);
            $('#filter-status').show();
        }

        function parseNumeric(val) {
            if (val === null || val === undefined || val === '') return null;
            return parseFloat(String(val).replace(',', '.'));
        }

        function fetchData(s = null, e = null, v = null) {
            let url = "{{url('/api/blending/mikro/analysis')}}";
            const q = [];
            if (s && e) q.push(`start_date=${s}&end_date=${e}`);
            if (v) q.push(`variant=${v}`);
            if (q.length) url += '?' + q.join('&');

            $.getJSON(url, function(res) {
                const data = res.blending_after_adjust_mikro || [];
                params.forEach(p => {
                    const series = data.map(item => {
                        const y = parseNumeric(item[p]);
                        if (y === null) return null;
                        return {
                            x: `Batch ${item.batch_range} (No ${item.nomor_blending}, ${item.variant})`,
                            y: y,
                            meta: {
                                po: item.po_number,
                                variant: item.variant,
                                created: item.created_at
                            }
                        }
                    }).filter(Boolean);
                    renderChart(`#chart-${p}`, series, p.toUpperCase());
                });
            });
        }

        function renderChart(sel, series, title) {
            $(sel).html('');
            new ApexCharts(document.querySelector(sel), {
                chart: {
                    type: 'line',
                    height: 350,
                    zoom: {
                        enabled: true
                    }
                },
                series: [{
                    name: title,
                    data: series
                }],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 4
                },
                xaxis: {
                    type: 'category',
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    labels: {
                        formatter: val => val.toFixed(2)
                    }
                },
                tooltip: {
                    custom: ({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) => {
                        const it = w.config.series[seriesIndex].data[dataPointIndex];
                        return `<div><b>${title}:</b> ${it.y}<br>PO: ${it.meta.po}<br>Variant: ${it.meta.variant}</div>`;
                    }
                }
            }).render();
        }
    });
</script>
@endsection