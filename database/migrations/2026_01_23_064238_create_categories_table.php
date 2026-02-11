<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên danh mục
            $table->string('slug')->unique(); // URL thân thiện (VD: lap-trinh-web)
            $table->string('seo_title')->nullable(); // Tên SEO (Meta Title)
            $table->text('seo_description')->nullable(); // Mô tả SEO (Meta Description)
            $table->boolean('is_active')->default(true); // Hiển thị/Ẩn
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
