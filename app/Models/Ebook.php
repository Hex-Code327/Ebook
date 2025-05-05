<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'is_active'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'published_date' => 'date',
    ];

    /**
     * Get the chapters for the ebook.
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    /**
     * Get the reading history records for this ebook.
     */
    public function readingHistory(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Get the users who have purchased this ebook.
     */
    public function purchasedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'purchases')
                   ->withTimestamps();
    }

    /**
     * Get the ratings for this ebook.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the URL for the cover image.
     */
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image 
            ? Storage::url($this->cover_image) 
            : asset('assets/img/covers/default.png');
    }

    /**
     * Scope for free ebooks.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Scope for premium ebooks.
     */
    public function scopePremium($query)
    {
        return $query->where('is_free', false);
    }

    /**
     * Scope for active ebooks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the read count for this ebook.
     */
    public function getReadCountAttribute(): int
    {
        return $this->readingHistory()->count();
    }

    /**
     * Get the average rating for this ebook.
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Get the total page count for this ebook.
     */
    public function getTotalPagesAttribute(): int
    {
        return $this->chapters()->sum('page_count');
    }
}