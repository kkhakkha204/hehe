<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'thumbnail',
        'embed_code',
        'content',
        'is_preview',
        'duration',
        'sort_order',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if ($lesson->sort_order === 0 || $lesson->sort_order === null) {
                // Lấy sort_order lớn nhất trong cùng chapter
                $lesson->sort_order = static::where('chapter_id', $lesson->chapter_id)
                        ->max('sort_order') + 1;
            }
        });

        static::deleting(function ($lesson) {
            if ($lesson->thumbnail && Storage::disk('public')->exists($lesson->thumbnail)) {
                Storage::disk('public')->delete($lesson->thumbnail);
            }
        });
    }

    // Relationships
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // Accessor
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        return Storage::disk('public')->url($this->thumbnail);
    }
}
