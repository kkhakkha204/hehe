<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('landing_enabled')->default(false)->after('is_featured');
            $table->string('landing_title')->nullable()->after('landing_enabled');
            $table->longText('landing_html')->nullable()->after('landing_title');
            $table->longText('landing_css')->nullable()->after('landing_html');
            $table->longText('landing_js')->nullable()->after('landing_css');
            $table->longText('landing_project_data')->nullable()->after('landing_js');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'landing_enabled',
                'landing_title',
                'landing_html',
                'landing_css',
                'landing_js',
                'landing_project_data',
            ]);
        });
    }
};
