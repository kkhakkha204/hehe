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
            $imagePath = self::normalizeImagePath($banner->image);

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
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

        $imagePath = self::normalizeImagePath($this->image);

        if (!$imagePath) {
            return null;
        }

        return Storage::disk('public')->url($imagePath);
    }

    protected static function normalizeImagePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, strlen('storage/'));
        }

        return $normalizedPath;
    }

    // Scope: Chỉ lấy banner active
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order');
    }
}
