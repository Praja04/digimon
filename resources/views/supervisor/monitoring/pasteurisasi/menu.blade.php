@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3 px-md-4 py-3">
        <!-- Page Header -->
        <div class="mb-4">
            <h4 class="mb-1 fw-semibold">Monitoring Pasteurisasi</h4>
            <p class="text-muted small mb-0">Real-time monitoring dan analisis data pasteurisasi</p>
        </div>

        <!-- Quick Access Section -->
        <div class="mb-4">
            <h6 class="text-uppercase text-muted small fw-semibold mb-3">Quick Access</h6>
            <div class="row g-3">
                <!-- Blending -->
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border h-100 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Blending"
                                        class="rounded" height="48">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">Monitoring Turun Blending</h6>
                                    <p class="text-muted small mb-3">Real-time monitoring proses blending</p>
                                    <a href="{{ url('analis/productionbatch/data_po/monitoring/blending') }}"
                                        class="btn btn-sm btn-primary w-100">
                                        Buka Monitoring
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pasteurisasi -->
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border h-100 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Pasteurisasi"
                                        class="rounded" height="48">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">Pasteurisasi</h6>
                                    <p class="text-muted small mb-3">Real-time proses pasteurisasi</p>
                                    <a href="{{ route('productionbatch.data_po_monitoring_pasteurisasi') }}"
                                        class="btn btn-sm btn-info w-100">
                                        Buka Monitoring
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Storage -->
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border h-100 hover-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Storage"
                                        class="rounded" height="48">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">Monitoring Storage</h6>
                                    <p class="text-muted small mb-3">Monitoring sistem penyimpanan</p>
                                    <a href="{{ url('analis/productionbatch/data_po/monitoring/storage') }}"
                                        class="btn btn-sm btn-secondary w-100">
                                        Buka Storage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Analytics Section -->
        <div class="mb-4">
            <h6 class="text-uppercase text-muted small fw-semibold mb-3">Data Analytics</h6>
            <div class="row g-3">
                <!-- Turun Blending -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-6">
                    <div class="card border h-100">
                        <div class="card-body p-3 text-center">
                            <div class="mb-2">
                                <span class="badge bg-primary-subtle text-primary small">Turun Blending</span>
                            </div>
                            <img src="{{ asset('assets/images/blending_awal.png') }}" alt="Turun Blending" class="mb-2"
                                height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Turun Blending</h6>
                            <p class="text-muted small mb-3">Analisis Monitoring Turun</p>
                            <div id="chart-tb" data-colors='["--vz-primary"]' style="height: 50px;" class="mb-3"></div>
                            <a href="{{ url('supervisor/monitoring/blending/data') }}"
                                class="btn btn-sm btn-outline-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pasteurisasi -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-6">
                    <div class="card border h-100">
                        <div class="card-body p-3 text-center">
                            <div class="mb-2">
                                <span class="badge bg-info-subtle text-info small">Pasteurisasi</span>
                            </div>
                            <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Pasteurisasi" class="mb-2"
                                height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Pasteurisasi</h6>
                            <p class="text-muted small mb-3">Analisis Pasteurisasi</p>
                            <div id="chart-pasteur" data-colors='["--vz-primary"]' style="height: 50px;" class="mb-3">
                            </div>
                            <a href="{{ url('supervisor/monitoring/pasteurisasi/data') }}"
                                class="btn btn-sm btn-outline-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Storage Makro -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-6">
                    <div class="card border h-100">
                        <div class="card-body p-3 text-center">
                            <div class="mb-2">
                                <span class="badge bg-success-subtle text-success small">Storage Makro</span>
                            </div>
                            <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Storage Makro" class="mb-2"
                                height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Storage</h6>
                            <p class="text-muted small mb-3">Analisis Storage Makro</p>
                            <div id="chart-sm" data-colors='["--vz-primary"]' style="height: 50px;" class="mb-3">
                            </div>
                            <a href="{{ url('supervisor/monitoring/storage/data') }}"
                                class="btn btn-sm btn-outline-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Storage Mikro -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-6">
                    <div class="card border h-100">
                        <div class="card-body p-3 text-center">
                            <div class="mb-2">
                                <span class="badge bg-warning-subtle text-warning small">Storage Mikro</span>
                            </div>
                            <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Storage Mikro"
                                class="mb-2" height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Storage</h6>
                            <p class="text-muted small mb-3">Analisis Storage Mikro</p>
                            <div id="chart-sm-mikro" data-colors='["--vz-primary"]' style="height: 50px;"
                                class="mb-3"></div>
                            <a href="{{ url('supervisor/monitoring/storage/data/mikro') }}"
                                class="btn btn-sm btn-outline-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.075);
            transform: translateY(-2px);
        }

        .avatar-sm {
            width: 2.5rem;
            height: 2.5rem;
        }
    </style>
@endsection
