<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // Tên bài học
            $table->string('thumbnail')->nullable(); // Ảnh thumbnail
            $table->text('embed_code')->nullable(); // Bunny.net embed code
            $table->longText('content')->nullable(); // Nội dung bài học (Rich text)
            $table->boolean('is_preview')->default(false); // Cho phép xem thử
            $table->integer('duration')->default(0); // Thời lượng video (phút)
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
