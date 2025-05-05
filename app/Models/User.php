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
     * The attributes that are mass assignable.
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
        'email_verified_at',
        'last_active_at' // Added for tracking active users
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
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
            'points' => 'integer',
            'last_active_at' => 'datetime' // Added for tracking active users
        ];
    }

    /**
     * Get the user's reading history.
     */
    public function readingHistory()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Get the ebooks purchased by the user.
     */
    public function purchasedEbooks()
    {
        return $this->belongsToMany(Ebook::class, 'purchases')
                   ->withPivot(['purchased_at'])
                   ->withTimestamps();
    }

    /**
     * Get the user's ratings.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Check if the user has purchased a specific ebook.
     */
    public function hasPurchased(Ebook $ebook): bool
    {
        return $this->purchasedEbooks()->where('ebook_id', $ebook->id)->exists();
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user has premium access.
     */
    public function hasPremiumAccess(): bool
    {
        return $this->is_paid || $this->isAdmin();
    }

    /**
     * Get the URL for the user's avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar 
            ? Storage::url($this->avatar) 
            : asset('assets/img/avatars/default.png');
    }

    /**
     * Add points to the user.
     */
    public function addPoints(int $points): void
    {
        $this->increment('points', $points);
    }

    /**
     * Scope for premium users.
     */
    public function scopePremium($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope for regular users.
     */
    public function scopeRegular($query)
    {
        return $query->where('is_paid', false);
    }

    /**
     * Scope for active users (used in dashboard stats).
     */
    public function scopeActive($query)
    {
        return $query->where('last_active_at', '>=', now()->subDay());
    }

    /**
     * Get reading progress for a specific ebook.
     */
    public function readingProgress(Ebook $ebook): ?int
    {
        $history = $this->readingHistory()
                      ->where('ebook_id', $ebook->id)
                      ->first();

        return $history ? $history->progress : null;
    }

    /**
     * Get the most read ebooks by this user.
     */
    public function mostReadEbooks($limit = 5)
    {
        return $this->readingHistory()
                  ->select('ebook_id', DB::raw('count(*) as read_count'))
                  ->groupBy('ebook_id')
                  ->orderBy('read_count', 'desc')
                  ->limit($limit)
                  ->with('ebook')
                  ->get();
    }

    /**
     * Mark the user as active.
     */
    public function markAsActive()
    {
        $this->update(['last_active_at' => now()]);
    }
}