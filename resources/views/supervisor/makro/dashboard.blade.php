@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Rata-Rata Parameter per Variant</h5>
            </div>
            <div class="card-body">
                <div id="chart-summary" class="apex-charts" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Perbandingan GGA vs GGAS</h5>
            </div>
            <div class="card-body">
                <div id="chart-comparison" class="apex-charts" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Disposition Remarks (QC Issues)</h5>
            </div>
            <div class="card-body">
                <div id="chart-issues" class="apex-charts" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        // 1. Summary Chart
        $.getJSON('/api/ggas/gga/summary', function(data) {
            const variants = data.map(d => d.variant);
            const ggaBrix = data.map(d => d.gga.brix_avg);
            const ggasBrix = data.map(d => d.ggas.brix_avg);

            new ApexCharts(document.querySelector("#chart-summary"), {
                chart: {
                    type: 'bar',
                    height: 300
                },
                series: [{
                        name: 'GGA Brix',
                        data: ggaBrix
                    },
                    {
                        name: 'GGAS Brix',
                        data: ggasBrix
                    }
                ],
                xaxis: {
                    categories: variants
                },
                colors: ['#34c38f', '#556ee6'],
                plotOptions: {
                    bar: {
                        columnWidth: '50%'
                    }
                }
            }).render();
        });


        // 2. Comparison Chart
        $.getJSON('/api/ggas/gga/comparison', function(res) {
            new ApexCharts(document.querySelector("#chart-comparison"), {
                chart: {
                    type: 'bar',
                    height: 300
                },
                series: [{
                        name: 'Avg Brix',
                        data: [res.gga.avg_brix, res.ggas.avg_brix]
                    },
                    {
                        name: 'Avg NaCl',
                        data: [res.gga.avg_nacl, res.ggas.avg_nacl]
                    },
                    {
                        name: 'Not Standar',
                        data: [res.gga.not_standar_count, res.ggas.not_standar_count]
                    }
                ],
                xaxis: {
                    categories: ['GGA', 'GGAS']
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%'
                    }
                },
                colors: ['#34c38f', '#556ee6', '#f46a6a']
            }).render();
        });

        // 3. QC Issues
        $.getJSON('/api/ggas/gga/issues', function(res) {
            const ggaReasons = Object.keys(res.gga.top_reasons);
            const ggaCounts = Object.values(res.gga.top_reasons);
            const ggasCounts = Object.values(res.ggas.top_reasons);

            new ApexCharts(document.querySelector("#chart-issues"), {
                chart: {
                    type: 'bar',
                    height: 320
                },
                series: [{
                        name: 'GGA',
                        data: ggaCounts
                    },
                    {
                        name: 'GGAS',
                        data: ggasCounts
                    }
                ],
                xaxis: {
                    categories: ggaReasons
                },
                colors: ['#f1b44c', '#556ee6'],
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                }
            }).render();
        });
    });
</script>
@endsection