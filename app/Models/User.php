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
        'last_active_at'
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
            'last_active_at' => 'datetime'
        ];
    }

    /**
     * Get the user's ratings.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
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
     * Now determined manually by admin through is_paid
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
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('last_active_at', '>=', now()->subDay());
    }

    /**
     * Mark the user as active.
     */
    public function markAsActive()
    {
        $this->update(['last_active_at' => now()]);
    }
}