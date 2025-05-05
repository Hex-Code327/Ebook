<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Ebook extends Model
{
    protected $fillable = [
        'title',
        'synopsis',
        'grade_level',
        'goal',
        'cover_image',
        'is_free',
        'author',
        'published_date',
        'page_count',
        'language',
        'is_active',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'published_date' => 'date',
    ];

    /**
     * Relasi: Ebook memiliki banyak chapter
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order_number');
    }

    /**
     * Relasi: Ebook memiliki banyak rating
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Akses custom: URL gambar cover ebook
     */
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image 
            ? Storage::url($this->cover_image) 
            : asset('assets/img/covers/default.png');
    }

    /**
     * Scope: Hanya ebook gratis
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope: Hanya ebook premium
     */
    public function scopePremium($query)
    {
        return $query->where('is_free', false);
    }

    /**
     * Scope: Hanya ebook yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Akses custom: Rata-rata rating
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    /**
     * Akses custom: Total halaman dari semua chapter
     */
    public function getTotalPagesAttribute(): int
    {
        return $this->chapters()->sum('page_count');
    }
}
