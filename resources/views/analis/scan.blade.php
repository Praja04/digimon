@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Scan data sample</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                    <li class="breadcrumb-item active">Scan data sample</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <h4 class="mt-4 fw-semibold">Scan Barcode QR</h4>
                            <p class="text-muted mt-3">Arahkan pada QR code sample QC anda!</p>
                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manualScanModal">
                                    Click here for start scanning
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-5 mb-2">
                        <div class="col-sm-7 col-8">
                            <img src="{{ asset('assets/images/verification-img.png') }}" alt="" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>

<div class="modal fade" id="manualScanModal" tabindex="-1" aria-labelledby="manualScanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center">
                <input type="text" id="scannedUrl" class="form-control text-center" placeholder="Scan QR Code di sini..." autofocus>
                <!-- Spinner Loading -->
                <div id="loadingSpinner" class="mt-3 d-none">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Mengarahkan ke halaman...</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    const inputField = document.getElementById('scannedUrl');
    const spinner = document.getElementById('loadingSpinner');

    // Fokus otomatis saat modal terbuka
    document.getElementById('manualScanModal').addEventListener('shown.bs.modal', function() {
        inputField.value = '';
        spinner.classList.add('d-none');
        inputField.focus();
    });

    // Jika input berubah (scanner isi URL)
    inputField.addEventListener('input', function() {
        const url = inputField.value.trim();
        if (url.length > 5 && url.startsWith("http")) {
            // Tampilkan spinner
            spinner.classList.remove('d-none');

            // Nonaktifkan input sementara
            inputField.disabled = true;

            // Tunggu 1.5 detik, lalu redirect
            setTimeout(() => {
                window.location.href = url;
            }, 1500);
        }
    });
</script>
@endsection