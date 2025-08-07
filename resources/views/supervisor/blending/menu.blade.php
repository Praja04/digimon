@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Blending Awal</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                    <li class="breadcrumb-item active">Supervisor</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-16 mb-1">Selamat Datang, {{Session::get('username')}}!</h4>
                <p class="text-muted mb-0">Mari tingkatkan kualitas agar menjadi perusahan makanan kelas dunia.</p>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input id="date-picker" type="text" class="form-control border-0 dash-filter-picker shadow" data-provider="flatpickr" data-range-date="true" data-date-format="d M, Y" data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                <div class="input-group-text bg-primary border-primary text-white">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-auto">
                            <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none"><i class="ri-pulse-line"></i></button>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
        </div><!-- end card header -->
    </div>
    <!--end col-->
</div>

<div class="row mt-4">

    <div class="col-xl-4 col-lg-4">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Makro</span>
                </div>
                <img src="{{ asset('assets/images/blending_awal.png' ) }}" alt="gambar" height="100">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Data Blending </a></h5>
                <p class="text-muted mb-4">Analisis Blending - Makro</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-gga" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('supervisor/blending/awal')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-xl-4 col-lg-4">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Makro</span>
                </div>
                <img src="{{ asset('assets/images/blending_adjust.png' ) }}" alt="gambar" height="100">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Data Blending After Adjustment</a></h5>
                <p class="text-muted mb-4">Analisis Blending After Adjust - Makro</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-ggas" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="mt-4">
                        <a href="{{url('supervisor/blending/adjust/data')}}" class="btn btn-light w-100">Lihat Detail</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
-->
    <div class="col-xl-4 col-lg-4">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-warning ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Mikro</span>
                </div>
                <img src="{{ asset('assets/images/blending_adjust.png' ) }}" alt="gambar" height="100">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Data Blending After Adjustment</a></h5>
                <p class="text-muted mb-4">Analisis Blending After Adjust - Mikro</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-ggas" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="mt-4">
                        <a href="{{url('supervisor/blending/mikro/data')}}" class="btn btn-light w-100">Lihat Detail</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
<div class="modal fade" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="comingSoonModalLabel">Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('assets/images/comingsoon.png') }}" alt="Coming Soon" height="80" class="mb-3">
                <h5>Fitur Blending After Adjustment</h5>
                <p>Masih dalam tahap pengembangan. Tunggu update berikutnya ya!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection