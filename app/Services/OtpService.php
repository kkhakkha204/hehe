<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class OtpService
{
    public function sendRegistrationOtp(string $phone, string $otp): void
    {
        $this->sendOtp($phone, $otp, 'registration');
    }

    public function sendLoginOtp(string $phone, string $otp): void
    {
        $this->sendOtp($phone, $otp, 'login');
    }

    protected function sendOtp(string $phone, string $otp, string $purpose): void
    {
        $webhookUrl = config('services.otp.webhook_url');
        $payload = [
            'phone' => $phone,
            'otp' => $otp,
        ];

        if (! $webhookUrl) {
            Log::warning('OTP webhook URL is not configured', [
                'phone' => $phone,
                'purpose' => $purpose,
                'otp' => $otp,
            ]);

            return;
        }

        $request = Http::timeout(15)
            ->acceptJson()
            ->contentType('application/json')
            ->withBody(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'application/json');

        if (! app()->environment('production')) {
            $request = $request->withoutVerifying();
        }

        $response = $request->post($webhookUrl);

        if (! $response->successful()) {
            Log::error('OTP webhook request failed', [
                'purpose' => $purpose,
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Không thể gửi OTP lúc này. Vui lòng thử lại sau.');
        }

        Log::info('OTP sent successfully', [
            'purpose' => $purpose,
            'phone' => $phone,
        ]);
    }
}
