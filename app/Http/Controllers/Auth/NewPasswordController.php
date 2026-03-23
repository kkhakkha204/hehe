<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Support\VietnamPhone;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
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
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()],
        ], [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'otp.required' => 'Vui lòng nhập OTP.',
            'otp.digits' => 'OTP phải gồm đúng 6 số.',
            'password.required' => 'Mật khẩu mới là bắt buộc.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $phone = VietnamPhone::normalize($data['phone']);
        $ip = (string) $request->ip();
        $verifyKey = 'reset-otp-verify:'.$ip;

        if (RateLimiter::tooManyAttempts($verifyKey, 10)) {
            return back()
                ->withInput(['phone' => $phone])
                ->withErrors([
                    'otp' => 'Bạn đã thử xác thực quá nhiều lần từ IP này. Vui lòng thử lại sau 15 phút.',
                ]);
        }

        $otpRecord = PhoneOtp::query()
            ->where('phone', $phone)
            ->where('purpose', 'password_reset')
            ->first();

        if (! $otpRecord || $otpRecord->consumed_at || ! $otpRecord->expires_at || $otpRecord->expires_at->isPast()) {
            RateLimiter::hit($verifyKey, 900);

            return back()
                ->withInput(['phone' => $phone])
                ->withErrors([
                    'otp' => 'OTP không hợp lệ hoặc đã hết hạn. Vui lòng gửi lại mã mới.',
                ]);
        }

        if ($otpRecord->attempts_count >= $otpRecord->max_attempts) {
            RateLimiter::hit($verifyKey, 900);

            return back()
                ->withInput(['phone' => $phone])
                ->withErrors([
                    'otp' => 'Bạn đã nhập sai OTP quá 5 lần. Vui lòng gửi lại mã mới.',
                ]);
        }

        if ($otpRecord->otp !== $data['otp']) {
            $otpRecord->increment('attempts_count');
            RateLimiter::hit($verifyKey, 900);

            return back()
                ->withInput(['phone' => $phone])
                ->withErrors([
                    'otp' => 'OTP không chính xác.',
                ]);
        }

        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return back()
                ->withInput(['phone' => $phone])
                ->withErrors([
                    'phone' => 'Tài khoản không tồn tại.',
                ]);
        }

        RateLimiter::clear($verifyKey);

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(60),
        ])->save();

        $otpRecord->forceFill([
            'user_id' => $user->id,
            'verified_at' => now(),
            'consumed_at' => now(),
        ])->save();

        event(new PasswordReset($user));

        return redirect()->route('login', ['mode' => 'password'])
            ->with('status', 'Mật khẩu đã được đặt lại thành công. Hãy đăng nhập lại.');
    }
}
