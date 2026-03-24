<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'level',
        'category_id',
        'author_id',
        'thumbnail',
        'price',
        'sale_price',
        'duration',
        'description',
        'current_students',
        'views',
        'is_published',
        'is_featured',
        'landing_enabled',
        'landing_title',
        'landing_html',
        'landing_css',
        'landing_js',
        'landing_project_data',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'landing_enabled' => 'boolean',
        'level' => 'integer',
        'price' => 'decimal:0',
        'sale_price' => 'decimal:0',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }

            // Auto-generate sort_order
            if ($course->sort_order === 0 || $course->sort_order === null) {
                $course->sort_order = static::max('sort_order') + 1;
            }
        });

        static::deleting(function ($course) {
            if ($course->thumbnail && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('sort_order');
    }

    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_course')
            ->withTimestamps();
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

    // Helper: Tính tổng số bài học
    public function getTotalLessonsAttribute(): int
    {
        return $this->chapters()->withCount('lessons')->get()->sum('lessons_count');
    }

    // Helper: Giá hiển thị (ưu tiên sale_price)
    public function getDisplayPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function isFree(): bool
    {
        return $this->display_price == 0;
    }
}
