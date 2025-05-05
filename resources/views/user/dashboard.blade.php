@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">
                    <span class="text-muted fw-light">Dashboard /</span> Overview
                </h4>
                <div class="btn-group">
                    <a href="{{ route('user.ebooks.index') }}" class="btn btn-primary">
                        <i class="bx bx-book me-1"></i> Lihat Semua Ebook
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <!-- Total Baca -->
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
                            <h3 class="mb-0">{{ auth()->user()->readingHistory()->count() }}</h3>
                            <small class="text-muted">Total Dibaca</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ebook Premium -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-star fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">{{ auth()->user()->purchasedEbooks()->count() }}</h3>
                            <small class="text-muted">Ebook Premium</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Terbaru -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-time fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">{{ auth()->user()->readingHistory()->whereDate('updated_at', today())->count() }}</h3>
                            <small class="text-muted">Dibaca Hari Ini</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poin -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-medal fs-4"></i>
                            </span>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0">{{ auth()->user()->points }}</h3>
                            <small class="text-muted">Poin Anda</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sedang Membaca -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sedang Membaca</h5>
                    <a href="{{ route('user.reading.history') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($readingHistory->count() > 0)
                    <div class="row">
                        @foreach($readingHistory as $history)
                        <div class="col-md-4 mb-3">
                            <div class="card ebook-card h-100">
                                <img src="{{ $history->ebook->cover_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $history->ebook->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $history->ebook->title }}</h5>
                                    <div class="progress mb-2">
                                        <div class="progress-bar" 
                                             style="width: {{ ($history->last_page / $history->ebook->total_pages) * 100 }}%">
                                        </div>
                                    </div>
                                    <small>Halaman {{ $history->last_page }} dari {{ $history->ebook->total_pages }}</small>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('user.ebooks.read', $history->ebook->id) }}" 
                                       class="btn btn-sm btn-primary w-100">
                                        Lanjut Baca
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <img src="{{ asset('assets/img/illustrations/reading.svg') }}" 
                             alt="No Reading History" 
                             style="height: 150px" 
                             class="mb-3">
                        <h5 class="mb-2">Belum ada riwayat membaca</h5>
                        <p class="text-muted">Mulailah membaca ebook pilihan Anda</p>
                        <a href="{{ route('guest.ebooks.index') }}" class="btn btn-primary">
                            <i class="bx bx-book me-1"></i> Jelajahi Ebook
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rekomendasi Untuk Anda</h5>
                </div>
                <div class="card-body">
                    @if($recommendations->count() > 0)
                    <div class="row">
                        @foreach($recommendations as $ebook)
                        <div class="col-md-3 col-6 mb-3">
                            @include('components.ebook-card', ['ebook' => $ebook])
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3">
                        <p class="text-muted">Belum ada rekomendasi. Mulailah membaca beberapa ebook.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Koleksi Ebook Premium -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Koleksi Ebook Premium Anda</h5>
                    <a href="{{ route('user.my-ebooks') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($myPremiumEbooks->count() > 0)
                    <div class="row">
                        @foreach($myPremiumEbooks as $ebook)
                        <div class="col-md-3 col-6 mb-3">
                            @include('components.ebook-card', ['ebook' => $ebook])
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3">
                        <img src="{{ asset('assets/img/illustrations/premium.svg') }}" 
                             alt="No Premium Ebooks" 
                             style="height: 100px" 
                             class="mb-2">
                        <p class="text-muted">Anda belum memiliki ebook premium</p>
                        <a href="{{ route('guest.ebooks.index') }}" class="btn btn-primary">
                            <i class="bx bx-store me-1"></i> Beli Ebook
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection