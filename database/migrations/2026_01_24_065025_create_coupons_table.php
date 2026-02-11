<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá (VD: SALE50)
            $table->text('description')->nullable(); // Mô tả

            // Loại giảm giá
            $table->enum('type', ['percentage', 'fixed']); // percentage: %, fixed: VNĐ
            $table->decimal('value', 15, 2); // Giá trị (50 = 50% hoặc 50.000đ)
            $table->decimal('max_discount', 15, 0)->nullable(); // Giảm tối đa (với %)
            $table->decimal('min_order', 15, 0)->default(0); // Đơn tối thiểu

            // Phạm vi áp dụng
            $table->enum('scope', ['all', 'specific'])->default('all'); // all: tất cả, specific: khóa học cụ thể

            // Giới hạn
            $table->integer('usage_limit')->nullable(); // Số lần dùng tối đa (null = không giới hạn)
            $table->integer('usage_count')->default(0); // Đã dùng bao nhiêu lần
            $table->integer('per_user_limit')->default(1); // Mỗi user dùng tối đa

            // Thời gian
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Bảng pivot: coupon áp dụng cho khóa học nào
        Schema::create('coupon_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Bảng lưu lịch sử dùng coupon
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('discount_amount', 15, 0); // Số tiền đã giảm
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupon_course');
        Schema::dropIfExists('coupons');
    }
};
