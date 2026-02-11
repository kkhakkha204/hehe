<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 15, 0)->default(0)->after('amount');
            $table->decimal('final_amount', 15, 0)->after('discount_amount');
            $table->foreignId('coupon_id')->nullable()->after('final_amount')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['discount_amount', 'final_amount', 'coupon_id']);
        });
    }
};
