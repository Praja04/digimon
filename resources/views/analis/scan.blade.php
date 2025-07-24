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
                <!-- Preview kamera (hanya muncul di mobile) -->
                <div id="cameraPreviewWrapper" class="d-none mt-3">
                    <div id="mobileCameraPreview"></div>
                    <p class="text-muted mt-2">Arahkan kamera ke QR Code untuk scan otomatis</p>
                </div>
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
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function isMobileDevice() {
        return /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    }

    const inputField = document.getElementById('scannedUrl');
    const spinner = document.getElementById('loadingSpinner');
    const cameraWrapper = document.getElementById('cameraPreviewWrapper');
    let html5QrCode;

    // Modal terbuka
    document.getElementById('manualScanModal').addEventListener('shown.bs.modal', function() {
        inputField.value = '';
        inputField.disabled = false;
        spinner.classList.add('d-none');
        inputField.classList.remove('d-none');
        cameraWrapper.classList.add('d-none');
        inputField.focus();

        // Kalau mobile, aktifkan kamera
        if (isMobileDevice()) {
            inputField.classList.add('d-none');
            cameraWrapper.classList.remove('d-none');

            html5QrCode = new Html5Qrcode("mobileCameraPreview");
            Html5Qrcode.getCameras().then(devices => {
                if (devices.length) {
                    // Coba cari kamera belakang
                    let backCamera = devices.find(device =>
                        /back|rear|environment/i.test(device.label)
                    );

                    // Kalau tidak ada label, fallback ke perangkat pertama
                    const selectedCameraId = backCamera ? backCamera.id : devices[0].id;

                    html5QrCode.start(
                        selectedCameraId, {
                            fps: 10,
                            qrbox: 250
                        },
                        scannedUrl => {
                            html5QrCode.stop();
                            spinner.classList.remove('d-none');
                            setTimeout(() => window.location.href = scannedUrl, 1500);
                        },
                        error => console.warn("Scan error:", error)
                    );
                } else {
                    console.warn("Tidak ada kamera yang tersedia.");
                }
            });
        }
    });

    // Modal ditutup
    document.getElementById('manualScanModal').addEventListener('hidden.bs.modal', function() {
        if (html5QrCode) {
            html5QrCode.stop().catch(err => console.warn("Gagal berhenti:", err));
        }
    });

    inputField.addEventListener('input', function() {
        const url = inputField.value.trim();
        if (url) {
            spinner.classList.remove('d-none'); // Tampilkan loading
            inputField.disabled = true;

            setTimeout(() => {
                window.location.href = url;
            }, 1500);
        }
    });
</script>
@endsection