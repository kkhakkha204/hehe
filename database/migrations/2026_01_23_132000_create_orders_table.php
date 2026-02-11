<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique(); // Mã đơn hàng: SE123456
            $table->decimal('amount', 15, 0); // Số tiền cần thanh toán
            $table->enum('status', ['pending', 'paid', 'cancelled', 'expired'])->default('pending');
            $table->string('bank_transaction_id')->nullable(); // ID giao dịch từ SePay
            $table->timestamp('paid_at')->nullable(); // Thời điểm thanh toán thành công
            $table->timestamp('expires_at')->nullable(); // Hết hạn sau 15 phút
            $table->json('payment_data')->nullable(); // Lưu webhook data từ SePay
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('order_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
