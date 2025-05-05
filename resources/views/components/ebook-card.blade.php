<div class="card ebook-card h-100">
    <div class="card-img-top-container">
        <img src="{{ $ebook->cover_url }}" 
             class="card-img-top" 
             alt="{{ $ebook->title }}">
        @if(!$ebook->is_free)
        <span class="badge bg-warning price-badge">
            Rp {{ number_format($ebook->price) }}
        </span>
        @endif
    </div>
    <div class="card-body">
        <h6 class="card-title">{{ Str::limit($ebook->title, 40) }}</h6>
        <div class="d-flex justify-content-between align-items-center">
            <div class="rating small">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bx bxs-star{{ $i <= $ebook->average_rating ? '' : '-empty' }}"></i>
                @endfor
                <span>({{ $ebook->reviews_count }})</span>
            </div>
            @if($ebook->is_free)
                <span class="badge bg-success">Gratis</span>
            @endif
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <a href="{{ route('guest.ebook.show', $ebook->slug) }}" 
           class="btn btn-sm btn-primary w-100">
            Detail
        </a>
    </div>
</div>

<style>
.ebook-card {
    transition: transform 0.3s;
    border-radius: 0.5rem;
    overflow: hidden;
}
.ebook-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.card-img-top-container {
    position: relative;
    height: 180px;
    overflow: hidden;
}
.card-img-top {
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.ebook-card:hover .card-img-top {
    transform: scale(1.05);
}
.price-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}
</style>