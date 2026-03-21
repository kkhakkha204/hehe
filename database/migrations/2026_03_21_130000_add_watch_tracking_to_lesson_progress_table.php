<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->unsignedInteger('watched_seconds')->default(0)->after('lesson_id');
            $table->timestamp('completed_at')->nullable()->after('last_viewed_at');
            $table->index(['user_id', 'course_id', 'completed_at'], 'lesson_progress_user_course_completed_index');
        });
    }

    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('lesson_progress_user_course_completed_index');
            $table->dropColumn(['watched_seconds', 'completed_at']);
        });
    }
};
