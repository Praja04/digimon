<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Digimon QC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/icon-utility/kecap.png') }}">

    <!-- Sweet Alert css-->
    <link href="{{ asset('material/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('material/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('material/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('material/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('material/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('material/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- jQuery should be included before DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('style')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.component.topbar')

        <!-- /.modal -->
        <!-- ========== App Menu ========== -->

        @include('layouts.component.sidebar')
        <!-- Left Sidebar End -->


        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <!-- content -->
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <!-- end content -->

            <!-- footer -->
            @include('layouts.component.footer')
            <!-- end footer -->
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- JAVASCRIPT -->
    <script src="{{ asset('material/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('material/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('material/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('material/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('material/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('material/assets/js/plugins.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ asset('material/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('material/assets/js/pages/sweetalerts.init.js') }}"></script>

    <script src="{{ asset('material/assets/js/highcharts.js') }}"></script>
    <script src="{{ asset('material/assets/js/app.js') }}"></script>

    <!-- aos js -->
    <script src="{{ asset('material/assets/libs/aos/aos.js') }}"></script>
    <!-- prismjs plugin -->
    <script src="{{ asset('material/assets/libs/prismjs/prism.js') }}"></script>
    <!-- animation init -->
    <script src="{{ asset('material/assets/js/pages/animation-aos.init.js') }}"></script>

    @if (Session::get('role') === 'foreman' || Session::get('role') === 'supervisor')
        <script>
            async function loadNotifications() {
                const list = document.getElementById('notification-list');
                const badge = document.getElementById('notif-badge');

                try {
                    const res = await fetch('/notifications/unread');
                    const data = await res.json();

                    list.innerHTML = ''; // Kosongkan dulu

                    if (data.length === 0) {
                        list.innerHTML = `<div class="text-center text-muted py-3">Tidak ada notifikasi baru</div>`;
                        badge.classList.add('d-none');
                        return;
                    }

                    // Tampilkan jumlah notifikasi
                    badge.textContent = data.length;
                    badge.classList.remove('d-none');

                    // Render setiap notifikasi
                    data.forEach(n => {
                        const item = document.createElement('div');
                        item.className =
                            'text-reset notification-item d-block dropdown-item position-relative border-bottom';
                        item.style.cursor = 'pointer';
                        item.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-1">
                                    <h6 class="mt-0 mb-2 lh-base">
                                        <b>${n.title}</b> - Disposisi: <span class="text-danger">${n.disposisi}</span>
                                    </h6>
                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                        <i class="mdi mdi-clock-outline"></i> ${new Date(n.created_at).toLocaleString()}
                                    </p>
                                </div>
                            </div>
                        `;
                        // Ketika notifikasi diklik
                        item.addEventListener('click', async () => {
                            try {
                                await fetch(`/notifications/mark-read/${n.id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    }
                                });

                                window.location.href = '/notifications'; // arahkan ke index notifikasi
                            } catch (err) {
                                console.error('Gagal menandai sebagai dibaca:', err);
                            }
                        });

                        list.appendChild(item);
                    });
                } catch (error) {
                    console.error('Gagal memuat notifikasi:', error);
                    list.innerHTML = `<div class="text-center text-danger py-3">Gagal memuat notifikasi</div>`;
                }
            }


            // Mark all as read
            async function markAllRead() {
                await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                loadNotifications(); // reload list
            }
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadNotifications();
            document.getElementById('mark-all-read').addEventListener('click', markAllRead);

            // Polling ringan setiap 5 detik
            setInterval(loadNotifications, 5000);
        });

        $(document).ready(function() {
            // Logout button handler
            $('#logoutButton').click(function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will be logged out from your session!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, logout!',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan SweetAlert loading
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait while we process your request.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading(); // Menampilkan animasi loading
                            }
                        });

                        $.ajax({
                            url: "{{ route('logout') }}",
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Tutup loading dan tampilkan pesan sukses
                                Swal.fire({
                                    title: 'Logged Out!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href =
                                        "{{ url('/') }}"; // Redirect ke halaman utama atau login
                                });
                            },
                            error: function(xhr) {
                                // Tutup loading dan tampilkan pesan error
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an issue logging out.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>
</body>

</html>
