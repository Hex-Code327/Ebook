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

<!-- Kategori -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Kategori Populer</h2>
            <a href="{{ route('guest.categories') }}" class="btn btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="row">
            @foreach($categories->take(6) as $category)
            <div class="col-md-4 col-lg-2 mb-3">
                <a href="{{ route('guest.category', $category->slug) }}" 
                   class="card category-card h-100 text-center">
                    <div class="card-body">
                        <div class="avatar avatar-lg mb-2">
                            <span class="avatar-initial rounded bg-label-{{ ['primary','success','warning','danger','info'][$loop->index % 5] }}">
                                <i class="bx bx-book"></i>
                            </span>
                        </div>
                        <h6 class="mb-0">{{ $category->name }}</h6>
                        <small class="text-muted">{{ $category->ebooks_count }} ebook</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Ebook Gratis -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ebook Gratis</h2>
            <a href="{{ route('guest.free-ebooks') }}" class="btn btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="row">
            @foreach($freeEbooks as $ebook)
            <div class="col-md-4 col-lg-2 mb-4">
                @include('components.ebook-card', ['ebook' => $ebook])
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Ebook Premium -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ebook Premium</h2>
            <a href="{{ route('guest.premium-ebooks') }}" class="btn btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="row">
            @foreach($premiumEbooks as $ebook)
            <div class="col-md-4 col-lg-2 mb-4">
                @include('components.ebook-card', ['ebook' => $ebook])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection