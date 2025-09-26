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
    document.addEventListener("DOMContentLoaded", function() {
        const $start = document.getElementById("start_date");
        const $end = document.getElementById("end_date");
        const $variant = document.getElementById("variant");
        const $btnFilter = document.getElementById("filter-data");
        const $btnReset = document.getElementById("reset-filter");
        const $filterStatus = document.getElementById("filter-status");
        const $filterStatusText = document.getElementById("filter-status-text");

        let paramCharts = {};
        const API_BASE = "/api/blending/mikro";

        function getParams() {
            const params = {};
            if ($start.value) params.start_date = $start.value;
            if ($end.value) params.end_date = $end.value;
            if ($variant.value) params.variant = $variant.value;
            return new URLSearchParams(params).toString();
        }

        async function loadParameters() {
            try {
                const res = await fetch(`${API_BASE}/analysis?${getParams()}`);
                const data = await res.json();
                const mikroData = data.blending_after_adjust_mikro || [];

                // Update filter status
                if (data.filter_applied) {
                    const {
                        start_date,
                        end_date,
                        variant,
                        total_records
                    } = data.filter_applied;
                    $filterStatus.style.display = "inline-block";
                    $filterStatusText.textContent = `Menampilkan ${total_records} data untuk ${variant !== "all" ? variant : "semua variant"} dari ${start_date} hingga ${end_date}`;
                }

                mikroData.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                const params = ["eb", "tpc", "ym", "hasil"];

                params.forEach(param => {
                    const el = document.querySelector(`#chart-${param}`);
                    if (!el) return;

                    const validData = mikroData.filter(item => {
                        const val = item[param];
                        return val !== null && val !== undefined && val !== "";
                    });

                    const seriesData = validData.map(item => [
                        new Date(item.created_at).getTime(),
                        parseFloat(item[param])
                    ]);

                    if (seriesData.length === 0) {
                        if (paramCharts[param]) {
                            paramCharts[param].destroy();
                            paramCharts[param] = null;
                        }
                        el.innerHTML = `<div class="text-center text-muted py-5">Tidak ada data untuk parameter ${param.toUpperCase()}</div>`;
                        return;
                    }

                    const options = {
                        series: [{
                            name: param.toUpperCase(),
                            data: seriesData
                        }],
                        chart: {
                            type: "line",
                            height: 280,
                            zoom: {
                                enabled: true,
                                type: "x"
                            },
                            toolbar: {
                                show: true,
                                tools: {
                                    download: true,
                                    selection: true,
                                    zoom: true,
                                    zoomin: true,
                                    zoomout: true,
                                    pan: true,
                                    reset: true
                                }
                            },
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
                                    speed: 350
                                }
                            }
                        },
                        stroke: {
                            curve: "smooth",
                            width: 3
                        },
                        xaxis: {
                            type: "datetime"
                        },
                        yaxis: {
                            labels: {
                                formatter: val => val != null ? val.toFixed(2) : "-"
                            }
                        },
                        markers: {
                            size: 4
                        },
                        tooltip: {
                            x: {
                                format: "dd MMM yyyy HH:mm"
                            },
                            y: {
                                formatter: val => val != null ? val.toFixed(2) : "-"
                            },
                            custom: function({
                                series,
                                seriesIndex,
                                dataPointIndex
                            }) {
                                const item = validData[dataPointIndex];
                                return item ? `
                                <div class="p-2">
                                    <strong>${param.toUpperCase()}: ${series[seriesIndex][dataPointIndex]}</strong><br/>
                                    Variant: ${item.variant || "-"}<br/>
                                    PO: ${item.po_number || "-"}<br/>
                                    Volume: ${item.volume_blending || "-"}<br/>
                                    Date: ${new Date(item.created_at).toLocaleString("id-ID")}
                                </div>
                            ` : "";
                            }
                        }
                    };

                    el.innerHTML = "";
                    paramCharts[param] = new ApexCharts(el, options);
                    paramCharts[param].render();
                });
            } catch (err) {
                console.error("Error loading mikro parameters:", err);
            }
        }

        function loadAll() {
            loadParameters();
        }

        $btnFilter.addEventListener("click", loadAll);
        $btnReset.addEventListener("click", () => {
            $start.value = "";
            $end.value = "";
            $variant.value = "";
            loadAll();
        });

        loadAll();
    });
</script>
@endsection