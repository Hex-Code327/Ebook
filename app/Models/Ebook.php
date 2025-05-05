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
        'is_free',           // Apakah ebook gratis
        'author',
        'published_date',
        'page_count',
        'language',
        'is_active',         // Apakah ebook aktif
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'published_date' => 'date',
    ];

    /**
     * Relasi: Ebook memiliki banyak chapter yang diurutkan berdasarkan nomor urutan
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
     * 
     * Jika ada cover image, gunakan URL dari penyimpanan, jika tidak ada, gunakan gambar default.
     */
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image 
            ? Storage::url($this->cover_image) 
            : asset('assets/img/covers/default.png');
    }

    /**
     * Scope: Hanya ebook gratis (is_free = true)
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope: Hanya ebook premium (is_free = false)
     */
    public function scopePremium($query)
    {
        return $query->where('is_free', false);
    }

    /**
     * Scope: Hanya ebook yang aktif (is_active = true)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Akses custom: Menghitung rata-rata rating ebook
     * 
     * Mengambil rata-rata rating dari semua rating yang ada
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    /**
     * Akses custom: Menghitung total halaman dari semua chapter
     * 
     * Menjumlahkan halaman dari semua chapter yang terkait dengan ebook ini.
     */
    public function getTotalPagesAttribute(): int
    {
        return $this->chapters()->sum('page_count');
    }

    /**
     * Cek apakah ebook ini dapat diakses oleh user tertentu
     * 
     * Ebook gratis dapat diakses oleh siapa saja, sementara ebook berbayar hanya bisa diakses oleh premium user.
     */
    public function canBeAccessedBy($user): bool
    {
        // Ebook gratis dapat diakses oleh semua user
        if ($this->is_free) {
            return true;
        }

        // Ebook premium hanya bisa diakses oleh user yang memiliki akses premium
        return $user->hasPremiumAccess();
    }
}
