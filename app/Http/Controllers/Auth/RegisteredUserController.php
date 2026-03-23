<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\OtpService;
use App\Support\VietnamPhone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use RuntimeException;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function sendOtp(Request $request): RedirectResponse|JsonResponse
    {
        $data = $this->validateRegistrationData($request);
        $phone = VietnamPhone::normalize($data['phone']);
        $ip = (string) $request->ip();
        $verifyKey = 'register-otp-verify:'.$ip;

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
            'user_id' => null,
            'phone' => $phone,
            'purpose' => 'registration',
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
            $this->otpService->sendRegistrationOtp($phone, $otp);
        } catch (RuntimeException $exception) {
            return $this->otpErrorResponse($request, 'phone', $exception->getMessage());
        }

        RateLimiter::clear($verifyKey);

        $message = $shouldRefreshOtp
            ? 'OTP đã được gửi tới số điện thoại của bạn. Mã có hiệu lực trong 5 phút.'
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
            ->withInput($request->except('password', 'password_confirmation', 'otp'))
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
            ->withInput($request->except('password', 'password_confirmation', 'otp'))
            ->withErrors([$field => $message]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRegistrationData($request, true);
        $phone = VietnamPhone::normalize($data['phone']);
        $ip = (string) $request->ip();
        $verifyKey = 'register-otp-verify:'.$ip;

        if (RateLimiter::tooManyAttempts($verifyKey, 10)) {
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'otp'))
                ->withErrors([
                    'otp' => 'Bạn đã thử xác thực quá nhiều lần từ IP này. Vui lòng thử lại sau 15 phút.',
                ]);
        }

        $otpRecord = PhoneOtp::query()
            ->where('phone', $phone)
            ->where('purpose', 'registration')
            ->first();

        if (! $otpRecord || $otpRecord->consumed_at || ! $otpRecord->expires_at || $otpRecord->expires_at->isPast()) {
            RateLimiter::hit($verifyKey, 900);

            return back()
                ->withInput($request->except('password', 'password_confirmation', 'otp'))
                ->withErrors([
                    'otp' => 'OTP không hợp lệ hoặc đã hết hạn. Vui lòng gửi lại mã mới.',
                ]);
        }

        if ($otpRecord->attempts_count >= $otpRecord->max_attempts) {
            RateLimiter::hit($verifyKey, 900);

            return back()
                ->withInput($request->except('password', 'password_confirmation', 'otp'))
                ->withErrors([
                    'otp' => 'Bạn đã nhập sai OTP quá 5 lần. Vui lòng gửi lại mã mới.',
                ]);
        }

        if ($otpRecord->otp !== $data['otp']) {
            $otpRecord->increment('attempts_count');
            RateLimiter::hit($verifyKey, 900);

            return back()
                ->withInput($request->except('password', 'password_confirmation', 'otp'))
                ->withErrors([
                    'otp' => 'OTP không chính xác.',
                ]);
        }

        RateLimiter::clear($verifyKey);

        $digits = preg_replace('/\D+/', '', $phone);
        $user = User::create([
            'username' => $this->generateUniqueUsername($digits),
            'name' => $this->generateDisplayName($digits),
            'email' => null,
            'phone' => $phone,
            'password' => Hash::make($data['password']),
            'role' => 'student',
        ]);

        $otpRecord->forceFill([
            'user_id' => $user->id,
            'verified_at' => now(),
            'consumed_at' => now(),
        ])->save();

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    protected function validateRegistrationData(Request $request, bool $requiresOtp = false): array
    {
        return $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! VietnamPhone::isValid((string) $value)) {
                        $fail('Số điện thoại phải là số di động Việt Nam hợp lệ.');
                    }
                },
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (User::query()->where('phone', VietnamPhone::normalize((string) $value))->exists()) {
                        $fail('Số điện thoại này đã được sử dụng.');
                    }
                },
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()->mixedCase(),
            ],
            'otp' => $requiresOtp ? ['required', 'digits:6'] : ['nullable'],
        ], [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'otp.required' => 'Vui lòng nhập OTP.',
            'otp.digits' => 'OTP phải gồm đúng 6 số.',
        ]);
    }

    protected function generateUniqueUsername(string $digits): string
    {
        $base = 'u'.$digits;
        $username = $base;
        $suffix = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base.$suffix;
            $suffix++;
        }

        return $username;
    }

    protected function generateDisplayName(string $digits): string
    {
        $visiblePhone = strlen($digits) > 4
            ? substr($digits, 0, 4).'...'
            : $digits;

        return 'Học viên '.$visiblePhone;
    }
}
