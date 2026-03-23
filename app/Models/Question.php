<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'title',
        'type',
        'answers',
        'sort_order',
    ];

    protected $casts = [
        'answers' => 'json',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
