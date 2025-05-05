<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi secara massal
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_paid',
        'avatar',
        'points',
        'email_verified_at'
    ];

    /**
     * Kolom yang harus disembunyikan saat serialisasi
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data atribut
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_paid' => 'boolean',
            'role' => 'string',
            'points' => 'integer'
        ];
    }

    /**
     * Relasi ke model ReadingHistory
     */
    public function readingHistory()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Relasi ke model Ebook melalui purchases (ebook yang dibeli)
     */
    public function purchasedEbooks()
    {
        return $this->belongsToMany(Ebook::class, 'purchases')
                   ->withPivot(['amount', 'payment_method', 'transaction_id', 'purchased_at'])
                   ->withTimestamps();
    }

    /**
     * Relasi ke model Payment
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relasi ke model Rating
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Cek apakah user sudah membeli ebook tertentu
     */
    public function hasPurchased(Ebook $ebook): bool
    {
        return $this->purchasedEbooks()->where('ebook_id', $ebook->id)->exists();
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user memiliki akses premium
     */
    public function hasPremiumAccess(): bool
    {
        return $this->is_paid || $this->isAdmin();
    }

    /**
     * Get URL avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar 
            ? Storage::url($this->avatar) 
            : asset('assets/img/avatars/default.png');
    }

    /**
     * Tambah poin ke user
     */
    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
    }

    /**
     * Scope untuk user premium
     */
    public function scopePremium($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope untuk user reguler
     */
    public function scopeRegular($query)
    {
        return $query->where('is_paid', false);
    }

    /**
     * Dapatkan progress membaca ebook tertentu
     */
    public function readingProgress(Ebook $ebook): ?int
    {
        $history = $this->readingHistory()
                      ->where('ebook_id', $ebook->id)
                      ->first();

        return $history ? $history->progress : null;
    }
}