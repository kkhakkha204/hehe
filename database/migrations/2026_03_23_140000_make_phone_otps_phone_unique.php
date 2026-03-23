<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('phone_otps')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        $kept = [];

        foreach ($rows as $row) {
            $phone = $this->normalizeVietnamPhone((string) $row->phone);

            if ($phone === '') {
                DB::table('phone_otps')->where('id', $row->id)->delete();
                continue;
            }

            if (isset($kept[$phone])) {
                DB::table('phone_otps')->where('id', $row->id)->delete();
                continue;
            }

            DB::table('phone_otps')
                ->where('id', $row->id)
                ->update(['phone' => $phone]);

            $kept[$phone] = true;
        }

        Schema::table('phone_otps', function (Blueprint $table) {
            $table->unique('phone');
        });
    }

    public function down(): void
    {
        Schema::table('phone_otps', function (Blueprint $table) {
            $table->dropUnique(['phone']);
        });
    }

    private function normalizeVietnamPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return '84'.substr($digits, 1);
        }

        return $digits;
    }
};
