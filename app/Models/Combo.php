<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'description',
        'price',
        'sale_price',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'sale_price' => 'decimal:0',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $combo) {
            if (blank($combo->slug) && filled($combo->title)) {
                $combo->slug = Str::slug($combo->title);
            }

            if ($combo->sort_order === 0 || $combo->sort_order === null) {
                $combo->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });

        static::deleting(function (self $combo) {
            if ($combo->thumbnail && Storage::disk('public')->exists($combo->thumbnail)) {
                Storage::disk('public')->delete($combo->thumbnail);
            }
        });
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'combo_course')
            ->withTimestamps()
            ->orderBy('courses.title');
    }

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

    public function getDisplayPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->latest('id');
    }
}
