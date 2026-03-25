<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\OtpService;
use App\Support\VietnamPhone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class AuthenticatedSessionController extends Controller
{
    private const OTP_EXPIRES_MINUTES = 5;

    private const OTP_RESEND_COOLDOWN_SECONDS = 60;

    private const OTP_RESEND_LIMIT_PER_WINDOW = 3;

    private const OTP_RESEND_WINDOW_MINUTES = 15;

    public function __construct(
        protected OtpService $otpService,
    ) {
    }

    public function create(Request $request): View
    {
        $mode = $request->query('mode', 'otp');

        if (! in_array($mode, ['otp', 'password'], true)) {
            $mode = 'otp';
        }

        return view('auth.login', ['mode' => $mode]);
    }

    public function sendOtp(Request $request): RedirectResponse|JsonResponse
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

        $rawPhone = (string) $request->input('phone');
        $phone = VietnamPhone::normalize($data['phone']);
        $user = User::query()->where('phone', $phone)->first() ?? $this->createOtpOnlyUser($phone);

        if ($blockMessage = $this->getDailyOtpBlockMessage($phone)) {
            return $this->otpErrorResponse($request, 'otp', $blockMessage);
        }

        $pendingOtp = PhoneOtp::query()->firstOrNew([
            'phone' => $phone,
        ], [
            'sent_count' => 0,
        ]);

        if ($pendingOtp->exists && $pendingOtp->purpose !== 'login') {
            $pendingOtp->sent_count = 0;
            $pendingOtp->attempts_count = 0;
            $pendingOtp->last_sent_at = null;
            $pendingOtp->consumed_at = null;
            $pendingOtp->verified_at = null;
        }

        $now = now();

        if ($pendingOtp->last_sent_at && $pendingOtp->last_sent_at->lte($now->copy()->subMinutes(self::OTP_RESEND_WINDOW_MINUTES))) {
            $pendingOtp->sent_count = 0;
        }

        if ($pendingOtp->last_sent_at && $pendingOtp->last_sent_at->diffInSeconds($now) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            $remaining = self::OTP_RESEND_COOLDOWN_SECONDS - $pendingOtp->last_sent_at->diffInSeconds($now);

            return $this->otpErrorResponse(
                $request,
                'otp',
                'Vui lòng đợi '.$remaining.' giây trước khi gửi lại OTP.'
            );
        }

        $resendCountInWindow = max(0, ((int) $pendingOtp->sent_count) - 1);

        if (
            $pendingOtp->last_sent_at
            && $pendingOtp->last_sent_at->gt($now->copy()->subMinutes(self::OTP_RESEND_WINDOW_MINUTES))
            && $resendCountInWindow >= self::OTP_RESEND_LIMIT_PER_WINDOW
        ) {
            $windowRemainingSeconds = max(
                60,
                (self::OTP_RESEND_WINDOW_MINUTES * 60) - $pendingOtp->last_sent_at->diffInSeconds($now)
            );

            return $this->otpErrorResponse(
                $request,
                'otp',
                'Bạn đã gửi OTP quá '.self::OTP_RESEND_LIMIT_PER_WINDOW.' lần trong 15 phút. Vui lòng thử lại sau '.$this->formatWaitTime($windowRemainingSeconds).'.'
            );
        }

        $otp = (string) random_int(100000, 999999);

        $pendingOtp->fill([
            'user_id' => $user->id,
            'phone' => $phone,
            'purpose' => 'login',
            'otp' => $otp,
            'ip_address' => (string) $request->ip(),
            'expires_at' => $now->copy()->addMinutes(self::OTP_EXPIRES_MINUTES),
            'attempts_count' => 0,
            'max_attempts' => 5,
            'sent_count' => ((int) $pendingOtp->sent_count) + 1,
            'last_sent_at' => $now,
            'verified_at' => null,
            'consumed_at' => null,
        ]);
        $pendingOtp->save();

        try {
            $this->otpService->sendLoginOtp($phone, $otp);
        } catch (RuntimeException $exception) {
            return $this->otpErrorResponse($request, 'phone', $exception->getMessage());
        }

        $message = 'OTP đăng nhập đã được gửi. Mã có hiệu lực trong '.self::OTP_EXPIRES_MINUTES.' phút.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'phone' => $phone,
                'resend_in' => self::OTP_RESEND_COOLDOWN_SECONDS,
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect()->route('login', ['mode' => 'otp'])
            ->withInput(['phone' => $rawPhone])
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

    public function otpLogin(Request $request): RedirectResponse
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
            'otp' => ['required', 'digits:6'],
        ], [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'otp.required' => 'Vui lòng nhập OTP.',
            'otp.digits' => 'OTP phải gồm đúng 6 số.',
        ]);

        $rawPhone = (string) $request->input('phone');
        $phone = VietnamPhone::normalize($data['phone']);

        if ($blockMessage = $this->getDailyOtpBlockMessage($phone)) {
            return back()
                ->withInput(['phone' => $rawPhone])
                ->withErrors(['otp' => $blockMessage]);
        }

        $otpRecord = PhoneOtp::query()
            ->where('phone', $phone)
            ->where('purpose', 'login')
            ->first();

        if (! $otpRecord || $otpRecord->consumed_at || ! $otpRecord->expires_at || $otpRecord->expires_at->isPast()) {
            return back()
                ->withInput(['phone' => $rawPhone])
                ->withErrors([
                    'otp' => 'OTP không hợp lệ hoặc đã hết hạn. Vui lòng gửi lại mã mới.',
                ]);
        }

        if ($otpRecord->attempts_count >= $otpRecord->max_attempts) {
            return back()
                ->withInput(['phone' => $rawPhone])
                ->withErrors([
                    'otp' => 'Bạn đã nhập sai OTP quá 5 lần. Vui lòng gửi lại mã mới.',
                ]);
        }

        if ($otpRecord->otp !== $data['otp']) {
            $otpRecord->increment('attempts_count');
            $this->recordWrongOtpAttempt($phone);

            $blockedMessage = $this->getDailyOtpBlockMessage($phone);

            return back()
                ->withInput(['phone' => $rawPhone])
                ->withErrors([
                    'otp' => $blockedMessage ?: 'OTP không chính xác.',
                ]);
        }

        $user = User::query()->find($otpRecord->user_id);

        if (! $user) {
            $user = $this->createOtpOnlyUser($phone);

            $otpRecord->forceFill([
                'user_id' => $user->id,
            ])->save();
        }

        $otpRecord->forceFill([
            'verified_at' => now(),
            'consumed_at' => now(),
        ])->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        $fallbackUrl = $user->isAdmin() ? url('/admin') : route('dashboard');
        $intendedUrl = $request->session()->pull('url.intended', $fallbackUrl);

        if (! is_string($intendedUrl) || $intendedUrl === '') {
            $intendedUrl = $fallbackUrl;
        }

        $request->session()->put('otp_password_prompt_user_id', $user->id);
        $request->session()->put('otp_password_prompt_intended', $intendedUrl);

        return redirect()->route('otp.password.prompt');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        if (Auth::user()->isAdmin()) {
            return redirect()->intended(url('/admin'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    protected function createOtpOnlyUser(string $phone): User
    {
        $digits = preg_replace('/\D+/', '', $phone);

        return User::create([
            'username' => $this->generateUniqueUsername($digits),
            'name' => $this->generateDisplayName($digits),
            'email' => null,
            'phone' => $phone,
            'password' => Hash::make(Str::random(24)),
            'role' => 'student',
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
        $visiblePhone = strlen($digits) > 4 ? substr($digits, 0, 4).'...' : $digits;

        return 'Học viên '.$visiblePhone;
    }

    protected function recordWrongOtpAttempt(string $phone): void
    {
        $now = now();
        $dailyWrongKey = $this->dailyWrongAttemptsKey($phone, $now->format('Y-m-d'));
        $secondsUntilDayEnd = max(60, $now->diffInSeconds($now->copy()->endOfDay()));

        Cache::add($dailyWrongKey, 0, $secondsUntilDayEnd);
        $dailyWrongAttempts = (int) Cache::increment($dailyWrongKey);

        $blockDuration = $this->resolveBlockDurationByDailyWrongAttempts($dailyWrongAttempts);

        if ($blockDuration <= 0) {
            return;
        }

        $blockKey = $this->otpBlockUntilKey($phone);
        $currentBlockUntil = (int) Cache::get($blockKey, 0);
        $newBlockUntil = $now->copy()->addSeconds($blockDuration)->timestamp;

        if ($newBlockUntil > $currentBlockUntil) {
            Cache::put($blockKey, $newBlockUntil, $blockDuration);
        }
    }

    protected function getDailyOtpBlockMessage(string $phone): ?string
    {
        $blockUntil = (int) Cache::get($this->otpBlockUntilKey($phone), 0);
        $now = now()->timestamp;

        if ($blockUntil <= $now) {
            if ($blockUntil > 0) {
                Cache::forget($this->otpBlockUntilKey($phone));
            }

            return null;
        }

        $remainingSeconds = max(1, $blockUntil - $now);

        return 'Bạn đang bị tạm khóa do nhập sai OTP quá nhiều lần. Vui lòng thử lại sau '.$this->formatWaitTime($remainingSeconds).'.';
    }

    protected function resolveBlockDurationByDailyWrongAttempts(int $dailyWrongAttempts): int
    {
        return match (true) {
            $dailyWrongAttempts >= 20 => 12 * 60 * 60,
            $dailyWrongAttempts >= 15 => 3 * 60 * 60,
            $dailyWrongAttempts >= 10 => 60 * 60,
            $dailyWrongAttempts >= 5 => 15 * 60,
            default => 0,
        };
    }

    protected function dailyWrongAttemptsKey(string $phone, string $date): string
    {
        return 'login-otp-wrong-daily:'.sha1($phone.'|'.$date);
    }

    protected function otpBlockUntilKey(string $phone): string
    {
        return 'login-otp-block-until:'.sha1($phone);
    }

    protected function formatWaitTime(int $seconds): string
    {
        if ($seconds >= 3600) {
            return ceil($seconds / 3600).' giờ';
        }

        if ($seconds >= 60) {
            return ceil($seconds / 60).' phút';
        }

        return $seconds.' giây';
    }
}
