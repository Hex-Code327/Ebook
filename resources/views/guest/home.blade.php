@extends('layouts.app')

@section('title', 'Beranda - Ebook Platform')

@section('content')

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-3">Temukan Ebook Terbaik</h1>
                <p class="lead mb-4">Baca ribuan ebook berkualitas dari berbagai kategori</p>
                <form action="{{ route('guest.search') }}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="keyword" class="form-control form-control-lg" 
                               placeholder="Cari ebook..." required>
                        <button class="btn btn-dark" type="submit">
                            <i class="bx bx-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('assets/img/illustrations/reading.svg') }}" 
                     alt="Hero Image" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Ebook Gratis -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ebook Gratis</h2>
            <a href="{{ route('guest.ebooks.byType', 'free') }}" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row">
            @forelse($freeEbooks as $ebook)
                <div class="col-md-4 col-lg-2 mb-4">
                    @include('components.ebook-card', ['ebook' => $ebook])
                </div>
            @empty
                <p class="text-muted">Belum ada ebook gratis tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Ebook Premium -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ebook Premium</h2>
            <a href="{{ route('guest.ebooks.byType', 'premium') }}" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row">
            @forelse($premiumEbooks as $ebook)
                <div class="col-md-4 col-lg-2 mb-4">
                    @include('components.ebook-card', [
                        'ebook' => $ebook,
                        'premiumLock' => true
                    ])
                </div>
            @empty
                <p class="text-muted">Belum ada ebook premium tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Redirect non-login user ketika klik ebook premium
    document.querySelectorAll('.premium-lock').forEach(element => {
        element.addEventListener('click', function(e) {
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            if (!isLoggedIn) {
                e.preventDefault();
                window.location.href = "{{ route('login') }}";
            }
        });
    });
</script>
@endpush

@endsection
