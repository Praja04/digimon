@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3 px-md-4 py-3">
        <!-- Page Header -->
        <div class="mb-4">
            <h4 class="mb-1 fw-semibold">Monitoring Pasteurisasi</h4>
            <p class="text-muted small mb-0">Real-time monitoring dan analisis data pasteurisasi</p>
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
                            <a href="{{ url('analis/monitoring/blending/data') }}"
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
                            <a href="{{ url('analis/monitoring/pasteurisasi/data') }}"
                                class="btn btn-sm btn-outline-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Storage Kimia -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-6">
                    <div class="card border h-100">
                        <div class="card-body p-3 text-center">
                            <div class="mb-2">
                                <span class="badge bg-success-subtle text-success small">Storage Kimia</span>
                            </div>
                            <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Storage Kimia" class="mb-2"
                                height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Storage</h6>
                            <p class="text-muted small mb-3">Analisis Storage Kimia</p>
                            <div id="chart-sm" data-colors='["--vz-primary"]' style="height: 50px;" class="mb-3">
                            </div>
                            <a href="{{ url('analis/monitoring/storage/data') }}"
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
                            <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Storage Mikro" class="mb-2"
                                height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Storage</h6>
                            <p class="text-muted small mb-3">Analisis Storage Mikro</p>
                            <div id="chart-sm-mikro" data-colors='["--vz-primary"]' style="height: 50px;" class="mb-3">
                            </div>
                            <a href="{{ url('analis/monitoring/storage/data/mikro') }}"
                                class="btn btn-sm btn-outline-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Monitoring Before Use -->
                <div class="col-12 col-sm-6 col-lg-4 col-xl-6">
                    <div class="card border h-100">
                        <div class="card-body p-3 text-center">
                            <div class="mb-2">
                                <span class="badge bg-warning-subtle text-warning small">Storage Before Use</span>
                            </div>
                            <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Monitoring Before Use"
                                class="mb-2" height="60">
                            <h6 class="mb-1 fw-semibold">Monitoring Storage Before Use</h6>
                            <p class="text-muted small mb-3">Analisis Before Use</p>
                            <div id="chart-beforeuse" data-colors='["--vz-primary"]' style="height: 50px;" class="mb-3">
                            </div>
                            <a href="{{ route('analis.monitoring_storage_before_use.index') }}"
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
