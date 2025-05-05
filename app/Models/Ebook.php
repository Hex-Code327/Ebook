<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ebook extends Model
{
    protected $fillable = [
        'title',
        'synopsis',
        'grade_level',
        'goal',
        'cover_image',
        'is_free'
    ];

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}