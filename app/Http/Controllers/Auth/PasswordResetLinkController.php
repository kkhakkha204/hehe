<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\OtpService;
use App\Support\VietnamPhone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use RuntimeException;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {
    }

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! VietnamPhone::isValid((string) $value)) {
                        $fail('Số điện thoại phải là số di động Việt Nam hợp lệ.');
                    }
                },
            ],
        ], [
            'phone.required' => 'Số điện thoại là bắt buộc.',
        ]);

        $phone = VietnamPhone::normalize($data['phone']);
        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return $this->otpErrorResponse($request, 'phone', 'Số điện thoại này chưa có tài khoản.');
        }

        $ip = (string) $request->ip();
        $verifyKey = 'reset-otp-verify:'.$ip;

        $pendingOtp = PhoneOtp::query()->firstOrNew([
            'phone' => $phone,
        ], [
            'sent_count' => 0,
        ]);

        if ($pendingOtp->last_sent_at && $pendingOtp->last_sent_at->gt(now()->subMinutes(15)) && (int) $pendingOtp->sent_count >= 5) {
            return $this->otpErrorResponse($request, 'phone', 'Bạn đã gửi OTP khoảng 5 lần. Vui lòng thử lại sau 15 phút.');
        }

        $shouldRefreshOtp = ! $pendingOtp->exists
            || ! $pendingOtp->expires_at
            || $pendingOtp->expires_at->isPast()
            || $pendingOtp->consumed_at
            || (int) $pendingOtp->attempts_count >= (int) $pendingOtp->max_attempts;

        $otp = $shouldRefreshOtp
            ? (string) random_int(100000, 999999)
            : (string) $pendingOtp->otp;

        $pendingOtp->fill([
            'user_id' => $user->id,
            'phone' => $phone,
            'purpose' => 'password_reset',
            'otp' => $otp,
            'ip_address' => $ip,
            'expires_at' => $shouldRefreshOtp ? now()->addMinutes(5) : $pendingOtp->expires_at,
            'attempts_count' => $shouldRefreshOtp ? 0 : $pendingOtp->attempts_count,
            'max_attempts' => 5,
            'sent_count' => $pendingOtp->last_sent_at && $pendingOtp->last_sent_at->lte(now()->subMinutes(15))
                ? 1
                : ((int) $pendingOtp->sent_count) + 1,
            'last_sent_at' => now(),
            'verified_at' => null,
            'consumed_at' => $shouldRefreshOtp ? null : $pendingOtp->consumed_at,
        ]);
        $pendingOtp->save();

        try {
            $this->otpService->sendLoginOtp($phone, $otp);
        } catch (RuntimeException $exception) {
            return $this->otpErrorResponse($request, 'phone', $exception->getMessage());
        }

        RateLimiter::clear($verifyKey);

        $message = $shouldRefreshOtp
            ? 'OTP đặt lại mật khẩu đã được gửi. Mã có hiệu lực trong 5 phút.'
            : 'OTP cũ vẫn còn hiệu lực trong 5 phút. Hệ thống đã gửi lại mã hiện tại cho bạn.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'phone' => $phone,
                'resend_in' => 60,
                'csrf_token' => csrf_token(),
            ]);
        }

        return back()
            ->withInput(['phone' => $phone])
            ->with('status', $message)
            ->with('otp_sent_phone', $phone);
    }

    protected function otpErrorResponse(Request $request, string $field, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => false,
                'field' => $field,
                'message' => $message,
                'csrf_token' => csrf_token(),
            ], 422);
        }

        return back()
            ->withInput(['phone' => $request->input('phone')])
            ->withErrors([$field => $message]);
    }
}
