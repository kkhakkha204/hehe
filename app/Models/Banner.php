<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'button_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-increment sort_order
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($banner) {
            if ($banner->sort_order === 0 || $banner->sort_order === null) {
                $banner->sort_order = static::max('sort_order') + 1;
            }
        });

        static::deleting(function ($banner) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
        });
    }

    // Accessor: URL ảnh
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }

    // Scope: Chỉ lấy banner active
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order');
    }
}
