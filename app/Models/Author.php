<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'bio',
        'email',
        'facebook',
        'linkedin',
        'website',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Accessor để lấy URL đầy đủ của avatar
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // Nếu avatar là URL đầy đủ (http/https)
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        // Nếu avatar lưu trong storage
        return Storage::disk('public')->url($this->avatar);
    }

    // Quan hệ với Course (sẽ tạo sau)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Xóa file avatar khi xóa author
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($author) {
            if ($author->avatar && Storage::disk('public')->exists($author->avatar)) {
                Storage::disk('public')->delete($author->avatar);
            }
        });

        // THÊM ĐOẠN NÀY
        static::creating(function ($author) {
            if ($author->sort_order === 0 || $author->sort_order === null) {
                $author->sort_order = static::max('sort_order') + 1;
            }
        });
    }
}
