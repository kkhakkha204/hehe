<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_otps', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('purpose');
            $table->index(['purpose', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::table('phone_otps', function (Blueprint $table) {
            $table->dropIndex(['purpose', 'ip_address']);
            $table->dropColumn('ip_address');
        });
    }
};
