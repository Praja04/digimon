@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Persiapan Masak</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                    <li class="breadcrumb-item active">Analis</li>
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

    <div class="col-xl-3 col-lg-6">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Input PO</span>
                </div>
                <img src="{{ asset('assets/images/masak.jpg' ) }}" alt="gambar" height="100" style="border-radius: 20px;">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Input PO Masak</a></h5>
                <p class="text-muted mb-4">Produksi Masak</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-input-po" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('analis/productionbatch/po_masak')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Pilih Batch</span>
                </div>
                <img src="{{ asset('assets/images/nomor_po.jpg' ) }}" alt="gambar" height="100" style="border-radius: 20px;">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Batch GGA & GGAS</a></h5>
                <p class="text-muted mb-4">Produksi Masak</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-input-po" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('analis/productionbatch/data_po')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Pilih Batch</span>
                </div>
                <img src="{{ asset('assets/images/blending_awal.png' ) }}" alt="gambar" height="100" style="border-radius: 20px;">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Batch Blending Awal</a></h5>
                <p class="text-muted mb-4">Produksi Masak</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-input-po" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('analis/productionbatch/data_po/blending/awal')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-xl-3 col-lg-6">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Pilih Batch</span>
                </div>
                <img src="{{ asset('assets/images/blending_adjust.png' ) }}" alt="gambar" height="100" style="border-radius: 20px;">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Batch Blending After Adjust</a></h5>
                <p class="text-muted mb-4">Produksi Masak</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-input-po" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('analis/productionbatch/data_po/blending/adjust')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-xl-3 col-lg-6">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Pilih Batch</span>
                </div>
                <img src="{{ asset('assets/images/blending_adjust.png' ) }}" alt="gambar" height="100" style="border-radius: 20px;">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Batch Monitoring Blending</a></h5>
                <p class="text-muted mb-4">Produksi Masak</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-input-po" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('analis/productionbatch/data_po/monitoring/blending')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card ribbon-box right overflow-hidden">
            <div class="card-body text-center p-4">
                <div class="ribbon ribbon-success ribbon-shape trending-ribbon">
                    <i class="ri-hand-heart-fill text-white align-bottom"></i>
                    <span class="trending-ribbon-text">Pilih Batch</span>
                </div>
                <img src="{{ asset('assets/images/blending_adjust.png' ) }}" alt="gambar" height="100" style="border-radius: 20px;">
                <h5 class="mb-1 mt-4"><a href="" class="link-primary">Batch Monitoring Storage</a></h5>
                <p class="text-muted mb-4">Produksi Masak</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div id="chart-input-po" data-colors='["--vz-danger"]'></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{url('analis/productionbatch/data_po/monitoring/storage')}}" class="btn btn-light w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- <div class="modal fade" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonModalLabel" aria-hidden="true">
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
</div> -->
@endsection