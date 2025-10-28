@extends('layouts.app')

@section('content')
    <style>
        /* Gaya Sederhana untuk notifikasi yang belum dibaca */
        .notif-item {
            border-left: 3px solid transparent;
            transition: all 0.2s ease-in-out;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid #f1f1f1;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item.unread-notif {
            border-left: 3px solid #007bff;
            background-color: #f8faff;
            font-weight: 500;
        }

        .notif-item:hover {
            background-color: #f1f5f9;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card bg-primary-gradient p-4 mb-4 shadow rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1 fw-bold">🔔 Kotak Notifikasi</h4>
                        <p class="mb-0 opacity-75">Kelola semua pesan dan peringatan sistem Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center py-3 bg-white">
                    <h5 class="mb-0 fw-semibold text-dark">Daftar Pesan</h5>
                    <form id="markAllReadForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-primary p-0">
                            <i class="ri-check-double-line me-1"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                </div>

                <div class="list-group list-group-flush p-2">
                    @if ($notifications->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="ri-check-double-line fs-1 mb-3 d-block text-success"></i>
                            <h6 class="fw-bold">Tidak ada notifikasi.</h6>
                            <p class="mb-0 text-sm">Kotak masuk Anda bersih.</p>
                        </div>
                    @else
                        @foreach ($notifications as $notif)
                            @php
                                $isUnread = $notif->status === 'unread';
                                $disposisiBadge = match (strtolower($notif->disposisi)) {
                                    'reject' => 'danger',
                                    'release' => 'success',
                                    default => 'secondary',
                                };

                                $remarkText = $notif->remark ?? null;
                            @endphp
                            <a href="javascript:void(0);"
                                class="list-group-item d-flex justify-content-between align-items-center notif-item {{ $isUnread ? 'unread-notif' : 'text-muted' }}"
                                onclick="markReadAndRedirect('{{ $notif->id }}')">

                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="me-3">
                                        @if ($isUnread)
                                            <i class="ri-notification-badge-fill fs-5 text-primary"></i>
                                        @else
                                            <i class="ri-notification-line fs-5 opacity-50"></i>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="fw-bold {{ $isUnread ? 'text-dark' : 'text-secondary' }}">
                                            {{ $notif->title }} ({{ $notif->productionBatch->po_number }})
                                        </div>
                                        <small class="d-block mt-1">
                                            <span class="badge bg-{{ $disposisiBadge }} bg-opacity-75 rounded-pill me-2">
                                                {{ $notif->disposisi }}
                                            </span>
                                            <span class="text-muted">
                                                <i class="ri-time-line me-1"></i>
                                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                            </span>
                                        </small>

                                        @if (!empty($remarkText))
                                            <div class="mt-1 p-2 border-start border-3 border-warning bg-light rounded-sm">
                                                <small class="fw-semibold text-warning">Catatan:</small>
                                                <small class="d-block text-dark">{{ $remarkText }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Penanda BARU diletakkan di kanan --}}
                                @if ($isUnread)
                                    <span class="text-danger fw-bold ms-3" style="font-size: 0.75rem;">NEW</span>
                                @endif
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

    <script>
        async function markReadAndRedirect(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ?
                document.querySelector('meta[name="csrf-token"]').content : '';

            try {
                await fetch(`{{ url('notifications/mark-read') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                window.location.reload();
            } catch (err) {
                console.error('Gagal update status:', err);
            }
        }

        $(document).ready(function() {
            $('#markAllReadForm').on('submit', function(e) {
                e.preventDefault();

                const url = "{{ route('notifications.markAllAsRead') }}";
                const csrfToken = $('input[name="_token"]').val();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: csrfToken
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Semua notifikasi berhasil ditandai dibaca.',
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        alert('Gagal menandai semua notifikasi dibaca.');
                        console.error('AJAX Error:', xhr);
                    }
                });
            });
        });
    </script>
@endsection
