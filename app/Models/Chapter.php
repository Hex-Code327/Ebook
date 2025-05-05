<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterImage extends Model
{
    protected $fillable = [
        'chapter_id',  // ID chapter yang terkait dengan gambar
        'image_path'   // Lokasi penyimpanan gambar
    ];

    /**
     * Relasi: Setiap ChapterImage dimiliki oleh satu Chapter.
     * 
     * @return BelongsTo
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
