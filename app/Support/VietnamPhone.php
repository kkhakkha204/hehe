<?php

namespace App\Support;

class VietnamPhone
{
    public static function normalize(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return '84'.substr($digits, 1);
        }

        return $digits;
    }

    public static function isValid(?string $phone): bool
    {
        return (bool) preg_match('/^84\d{9}$/', self::normalize($phone));
    }
}


