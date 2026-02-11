<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'sort_order',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chapter) {
            if ($chapter->sort_order === 0 || $chapter->sort_order === null) {
                // Lấy sort_order lớn nhất trong cùng course
                $chapter->sort_order = static::where('course_id', $chapter->course_id)
                        ->max('sort_order') + 1;
            }
        });
    }
}
