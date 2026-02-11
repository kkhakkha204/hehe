<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên giảng viên
            $table->string('avatar')->nullable(); // Đường dẫn ảnh đại diện
            $table->text('bio')->nullable(); // Tiểu sử/Mô tả ngắn
            $table->string('email')->nullable(); // Email liên hệ
            $table->string('facebook')->nullable(); // Link Facebook
            $table->string('linkedin')->nullable(); // Link LinkedIn
            $table->string('website')->nullable(); // Website cá nhân
            $table->boolean('is_active')->default(true); // Hiển thị/Ẩn
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
