@extends('layouts.app-public')

@section('title', 'Đăng nhập')

@section('content')
    @php
        $mode = $mode ?? request('mode', 'otp');
        $isPasswordMode = $mode === 'password';
    @endphp

    <section class="min-h-[calc(100vh-180px)] bg-[#f3f3f3] py-8 md:py-12">
        <div class="mx-auto max-w-[430px] px-4">
            <h1 class="mb-5 text-[20px] font-bold text-[#082341]">Đăng nhập</h1>

            <x-auth-session-status class="mb-4 text-sm text-green-700" :status="session('status')" />

            @if ($isPasswordMode)
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus placeholder="Nhập số điện thoại" class="h-[42px] w-full rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Nhập mật khẩu" class="h-[42px] w-full rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 pr-11 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                            <button type="button" id="toggle-login-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#97A3B4] hover:text-[#082341]" aria-label="Hiện hoặc ẩn mật khẩu">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2 text-[14px] text-[#082341]">
                            <input id="remember_me" type="checkbox" name="remember" class="h-[15px] w-[15px] rounded border-[#c8ced8] text-black focus:ring-black">
                            Lưu tài khoản
                        </label>
                        <button type="submit" class="h-[42px] min-w-[132px] rounded-md bg-black text-[16px] font-bold text-white transition-opacity hover:opacity-90">Đăng nhập</button>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('login.otp') }}" class="space-y-4" id="login-otp-form">
                    @csrf

                    <div>
                        <input id="login-phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus autocomplete="tel" placeholder="Nhập số điện thoại" class="h-[42px] w-full rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                        <p id="login-phone-error" class="mt-2 text-sm text-red-600 {{ $errors->has('phone') ? '' : 'hidden' }}">{{ $errors->first('phone') }}</p>
                    </div>

                    <div>
                        <div class="flex gap-3">
                            <input id="login-otp" type="text" name="otp" value="{{ old('otp') }}" inputmode="numeric" maxlength="6" placeholder="Nhập OTP" class="h-[42px] min-w-0 flex-1 rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                            <button type="button" id="login-send-otp" class="inline-flex h-[42px] min-w-[132px] items-center justify-center gap-2 rounded-md border border-black bg-white px-4 text-[14px] font-bold text-black transition-opacity hover:opacity-90">
                                <span class="hidden h-4 w-4 animate-spin rounded-full border-2 border-black/25 border-t-black" data-spinner></span>
                                <span data-label>Gửi OTP</span>
                            </button>
                        </div>
                        <p id="login-otp-error" class="mt-2 text-sm text-red-600 {{ $errors->has('otp') ? '' : 'hidden' }}">{{ $errors->first('otp') }}</p>
                        <p id="login-otp-status" class="mt-2 hidden text-[12px] text-[#082341]"></p>
                    </div>

                    <div class="pt-1">
                        <button type="submit" class="h-[42px] min-w-[132px] rounded-md bg-black px-5 text-[16px] font-bold text-white transition-opacity hover:opacity-90">Đăng nhập</button>
                    </div>
                </form>
            @endif

            <div class="mt-5 flex items-center justify-between border-t border-[#d6dbe3] pt-5 text-[16px]">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[#576B84] hover:underline">Quên mật khẩu?</a>
                @else
                    <span></span>
                @endif

                @if ($isPasswordMode)
                    <a href="{{ route('login', ['mode' => 'otp']) }}" class="text-black hover:underline">Đăng nhập bằng OTP</a>
                @else
                    <a href="{{ route('login', ['mode' => 'password']) }}" class="text-black hover:underline">Đăng nhập bằng mật khẩu</a>
                @endif
            </div>

            <div class="mt-4 text-[16px]">
                <a href="{{ route('register') }}" class="text-black hover:underline">Đăng ký</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-login-password');
            if (passwordInput && toggleButton) {
                toggleButton.addEventListener('click', () => {
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                });
            }

            setupOtpAjax('login-otp-form', 'login-send-otp', 'login-phone', 'login-phone-error', 'login-otp-error', 'login-otp-status', @json(route('login.send-otp')));
        });

        function setupOtpAjax(formId, buttonId, phoneInputId, phoneErrorId, otpErrorId, statusId, url) {
            const form = document.getElementById(formId);
            const button = document.getElementById(buttonId);
            const phoneInput = document.getElementById(phoneInputId);
            const phoneError = document.getElementById(phoneErrorId);
            const otpError = document.getElementById(otpErrorId);
            const status = document.getElementById(statusId);

            if (!form || !button || !phoneInput) {
                return;
            }

            const csrfInput = form.querySelector('input[name="_token"]');
            const spinner = button.querySelector('[data-spinner]');
            const label = button.querySelector('[data-label]');
            const countdownStorageKey = `${formId}-otp-countdown-until`;
            let countdownInterval = null;

            const syncCsrfToken = (token) => {
                if (token && csrfInput) {
                    csrfInput.value = token;
                }
            };

            const setMessage = (element, message = '', hidden = false) => {
                if (!element) return;
                element.textContent = message;
                element.classList.toggle('hidden', hidden);
            };

            const setPending = (pending) => {
                button.disabled = pending;
                button.classList.toggle('cursor-not-allowed', pending);
                button.classList.toggle('opacity-60', pending);
                spinner?.classList.toggle('hidden', !pending);
                if (label && pending) {
                    label.textContent = 'Đang gửi';
                }
            };

            const getCountdownRemaining = () => {
                const countdownUntil = Number(window.localStorage.getItem(countdownStorageKey) || 0);
                if (!countdownUntil) return 0;
                return Math.max(0, Math.ceil((countdownUntil - Date.now()) / 1000));
            };

            const startCountdown = (seconds) => {
                let remaining = seconds;

                button.disabled = true;
                button.classList.add('cursor-not-allowed', 'opacity-60');
                spinner?.classList.add('hidden');

                if (label) {
                    label.textContent = `${remaining}s`;
                }

                countdownInterval = window.setInterval(() => {
                    remaining -= 1;

                    if (remaining <= 0) {
                        window.clearInterval(countdownInterval);
                        window.localStorage.removeItem(countdownStorageKey);
                        button.disabled = false;
                        button.classList.remove('cursor-not-allowed', 'opacity-60');
                        if (label) {
                            label.textContent = 'Gửi OTP';
                        }
                        return;
                    }

                    if (label) {
                        label.textContent = `${remaining}s`;
                    }
                }, 1000);
            };

            button.addEventListener('click', async () => {
                if (button.disabled) {
                    return;
                }

                setMessage(phoneError, '', true);
                setMessage(otpError, '', true);
                setMessage(status, '', true);

                if (!phoneInput.value.trim()) {
                    setMessage(phoneError, 'Vui lòng nhập số điện thoại.', false);
                    return;
                }

                setPending(true);

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfInput?.value || '',
                        },
                        body: new FormData(form),
                    });

                    const data = await response.json();
                    syncCsrfToken(data.csrf_token);

                    if (!response.ok || !data.ok) {
                        setPending(false);
                        const target = data.field === 'otp' ? otpError : phoneError;
                        setMessage(target, data.message || 'Không thể gửi OTP.', false);
                        if (label) {
                            label.textContent = 'Gửi OTP';
                        }
                        return;
                    }

                    setMessage(status, data.message || 'OTP đã được gửi.', false);

                    if (countdownInterval) {
                        window.clearInterval(countdownInterval);
                    }

                    window.localStorage.setItem(countdownStorageKey, String(Date.now() + ((data.resend_in || 60) * 1000)));
                    startCountdown(data.resend_in || 60);
                } catch (error) {
                    setPending(false);
                    if (label) {
                        label.textContent = 'Gửi OTP';
                    }
                    setMessage(phoneError, 'Không thể gửi OTP lúc này. Vui lòng thử lại.', false);
                }
            });

            const initialCountdownRemaining = getCountdownRemaining();
            if (initialCountdownRemaining > 0) {
                startCountdown(initialCountdownRemaining);
            }
        }
    </script>
@endpush
