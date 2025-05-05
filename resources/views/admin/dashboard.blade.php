@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">
                    <span class="text-muted fw-light">Admin /</span> Dashboard
                </h4>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-cog me-1"></i> Pengaturan Cepat
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.ebooks.create') }}">Tambah Ebook Baru</a></li>
                        <li><a class="dropdown-item" href="#">Lihat Pengguna</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Pengaturan Sistem</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <!-- Total Ebook -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-book-open fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">{{ $stats['total_ebooks'] }}</h3>
                            <small class="text-muted">Total Ebook</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent pt-0">
                    <small class="text-muted">
                        <i class="bx bx-info-circle"></i> {{ $stats['free_ebooks'] }} gratis
                    </small>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-user fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                            <small class="text-muted">Total Pengguna</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent pt-0">
                    <small class="text-muted">
                        <i class="bx bx-info-circle"></i> {{ $stats['new_users'] }} baru minggu ini
                    </small>
                </div>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-line-chart fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">{{ $stats['active_reads'] }}</h3>
                            <small class="text-muted">Sedang Membaca</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent pt-0">
                    <small class="text-success">
                        <i class="bx bx-up-arrow-alt"></i> {{ $stats['read_increase'] }}% dari kemarin
                    </small>
                </div>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-dollar fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Pendapatan Bulan Ini</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent pt-0">
                    <small class="{{ $stats['revenue_change'] >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="bx bx-{{ $stats['revenue_change'] >= 0 ? 'up' : 'down' }}-arrow-alt"></i> 
                        {{ abs($stats['revenue_change']) }}% dari bulan lalu
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Tabel -->
    <div class="row">
        <!-- Grafik Aktivitas -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Aktivitas Pengguna 30 Hari Terakhir</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Lihat Detail</a></li>
                            <li><a class="dropdown-item" href="#">Ekspor Data</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" class="chartjs" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Ebook Terpopuler -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Ebook Terpopuler</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($popularEbooks as $ebook)
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ $ebook->cover_url }}" alt="Cover" class="rounded" width="50">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $ebook->title }}</h6>
                                    <small class="text-muted">{{ $ebook->grade_level }}</small>
                                </div>
                                <div class="badge bg-label-primary rounded-pill">
                                    {{ $ebook->read_count }}x dibaca
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pengguna Terbaru -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pengguna Terdaftar Terbaru</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $user->is_paid ? 'bg-label-success' : 'bg-label-secondary' }}">
                                        {{ $user->is_paid ? 'Premium' : 'Reguler' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Grafik Aktivitas
    const activityCtx = document.getElementById('activityChart');
    const activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($activityChart['labels']) !!},
            datasets: [
                {
                    label: 'Pembaca',
                    data: {!! json_encode($activityChart['readers']) !!},
                    borderColor: '#7367f0',
                    backgroundColor: 'transparent',
                    tension: 0.1
                },
                {
                    label: 'Pendaftar',
                    data: {!! json_encode($activityChart['registrations']) !!},
                    borderColor: '#28c76f',
                    backgroundColor: 'transparent',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection