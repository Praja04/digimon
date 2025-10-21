@extends('layouts.app')

@section('content')
    <!-- Welcome & Controls Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm ">
                <div class="card-body p-4">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div
                                    class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="ri-user-smile-line text-white fs-16"></i>
                                </div>
                                <div>
                                    <h4 class="fs-18 mb-1 text-dark">Selamat Datang, {{ Session::get('username') }}!</h4>
                                    <p class="text-muted mb-0 fs-14">Mari tingkatkan kualitas untuk menjadi perusahaan
                                        makanan kelas dunia</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Cards -->
    <div class="row g-4">
        <!-- Input PO Card -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <div
                                class="avatar-xl bg-light border rounded-4 mx-auto d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/images/masak.jpg') }}" alt="Input PO Masak" class="rounded-3"
                                    height="80" style="object-fit: cover;">
                            </div>
                            <h5 class="mb-2 fw-semibold">
                                <a href="{{ url('analis/productionbatch/po_masak') }}"
                                    class="link-primary text-decoration-none">
                                    Input PO Masak
                                </a>
                            </h5>
                            <p class="text-muted mb-3 fs-14">Kelola Purchase Order untuk Produksi Masak</p>
                        </div>

                        <a href="{{ url('analis/productionbatch/po_masak') }}" class="btn btn-primary w-100 rounded-pill">
                            <i class="ri-eye-line me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch GGA & GGAS Card -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <div
                                class="avatar-xl bg-light border rounded-4 mx-auto d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/images/nomor_po.jpg') }}" alt="Batch GGA & GGAS"
                                    class="rounded-3" height="80" style="object-fit: cover;">
                            </div>
                            <h5 class="mb-2 fw-semibold">
                                <a href="{{ url('analis/productionbatch/data_po') }}"
                                    class="link-success text-decoration-none">
                                    Batch GGA & GGAS
                                </a>
                            </h5>
                            <p class="text-muted mb-3 fs-14">Monitoring Batch Produksi GGA & GGAS</p>
                        </div>

                        <a href="{{ url('analis/productionbatch/data_po') }}" class="btn btn-success w-100 rounded-pill">
                            <i class="ri-eye-line me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch Blending Card -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <div
                                class="avatar-xl bg-light border rounded-4 mx-auto d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/images/blending_awal.png') }}" alt="Batch Blending"
                                    class="rounded-3" height="80" style="object-fit: cover;">
                            </div>
                            <h5 class="mb-2 fw-semibold">
                                <a href="{{ url('analis/productionbatch/data_po/blending/awal') }}"
                                    class="link-warning text-decoration-none">
                                    Batch Blending
                                </a>
                            </h5>
                            <p class="text-muted mb-3 fs-14">Proses Blending Awal Produksi</p>
                        </div>

                        <a href="{{ url('analis/productionbatch/data_po/blending/awal') }}"
                            class="btn btn-warning w-100 rounded-pill">
                            <i class="ri-eye-line me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring Turun Blending Card -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <div
                                class="avatar-xl bg-light border rounded-4 mx-auto d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Monitoring Turun Blending"
                                    class="rounded-3" height="80" style="object-fit: cover;">
                            </div>
                            <h5 class="mb-2 fw-semibold">
                                <a href="{{ url('analis/productionbatch/data_po/monitoring/blending') }}"
                                    class="link-primary text-decoration-none">
                                    Monitoring Turun Blending
                                </a>
                            </h5>
                            <p class="text-muted mb-3 fs-14">Real-time monitoring proses blending</p>
                        </div>

                        <a href="{{ url('analis/productionbatch/data_po/monitoring/blending') }}"
                            class="btn btn-primary w-100 rounded-pill">
                            <i class="ri-eye-line me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pasteurisasi Card -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <div
                                class="avatar-xl bg-light border rounded-4 mx-auto d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Pasteurisasi"
                                    class="rounded-3" height="80" style="object-fit: cover;">
                            </div>
                            <h5 class="mb-2 fw-semibold">
                                <a href="{{ route('productionbatch.data_po_monitoring_pasteurisasi') }}"
                                    class="link-info text-decoration-none">
                                    Pasteurisasi
                                </a>
                            </h5>
                            <p class="text-muted mb-3 fs-14">Real-time proses pasteurisasi</p>
                        </div>

                        <a href="{{ route('productionbatch.data_po_monitoring_pasteurisasi') }}"
                            class="btn btn-info w-100 rounded-pill">
                            <i class="ri-eye-line me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring Storage Card -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="position-relative overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="mb-4">
                            <div
                                class="avatar-xl bg-light border rounded-4 mx-auto d-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('assets/images/blending_adjust.png') }}" alt="Monitoring Storage"
                                    class="rounded-3" height="80" style="object-fit: cover;">
                            </div>
                            <h5 class="mb-2 fw-semibold">
                                <a href="{{ url('analis/productionbatch/data_po/monitoring/storage') }}"
                                    class="link-secondary text-decoration-none">
                                    Monitoring Storage
                                </a>
                            </h5>
                            <p class="text-muted mb-3 fs-14">Monitoring sistem penyimpanan</p>
                        </div>

                        <a href="{{ url('analis/productionbatch/data_po/monitoring/storage') }}"
                            class="btn btn-secondary w-100 rounded-pill">
                            <i class="ri-eye-line me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Stats Section -->
    <div class="row mt-5">

    </div>


    <style>
        .hover-shadow-lg:hover {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
            transform: translateY(-2px);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .bg-gradient-to-r {
            background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-info) 100%);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }

        .avatar-lg {
            width: 4rem;
            height: 4rem;
        }

        .avatar-xl {
            width: 5rem;
            height: 5rem;
        }

        .progress-sm {
            height: 0.5rem;
        }

        .ribbon-shape {
            border-radius: 0 0 0 1rem;
        }
    </style>
@endsection
