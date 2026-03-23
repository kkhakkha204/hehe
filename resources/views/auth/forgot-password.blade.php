@extends('layouts.app-public')

@section('title', 'Quên mật khẩu')

@section('content')
    <section class="min-h-[calc(100vh-180px)] bg-[#f3f3f3] py-8 md:py-12">
        <div class="mx-auto max-w-[430px] px-4">
            <h1 class="mb-5 text-[20px] font-bold text-[#082341]">Quên mật khẩu</h1>

            <x-auth-session-status class="mb-4 text-sm text-green-700" :status="session('status')" />

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4" id="forgot-password-form">
                @csrf

                <div>
                    <input id="forgot-phone" type="text" name="phone" value="{{ old('phone') }}" required autofocus autocomplete="tel" placeholder="Nhập số điện thoại" class="h-[42px] w-full rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                    <p id="forgot-phone-error" class="mt-2 text-sm text-red-600 {{ $errors->has('phone') ? '' : 'hidden' }}">{{ $errors->first('phone') }}</p>
                </div>

                <div>
                    <div class="flex gap-3">
                        <input id="forgot-otp" type="text" name="otp" value="{{ old('otp') }}" inputmode="numeric" maxlength="6" placeholder="Nhập OTP" class="h-[42px] min-w-0 flex-1 rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                        <button type="button" id="forgot-send-otp" class="inline-flex h-[42px] min-w-[132px] items-center justify-center gap-2 rounded-md border border-black bg-white px-4 text-[14px] font-bold text-black transition-opacity hover:opacity-90">
                            <span class="hidden h-4 w-4 animate-spin rounded-full border-2 border-black/25 border-t-black" data-spinner></span>
                            <span data-label>Gửi OTP</span>
                        </button>
                    </div>
                    <p id="forgot-otp-error" class="mt-2 text-sm text-red-600 {{ $errors->has('otp') ? '' : 'hidden' }}">{{ $errors->first('otp') }}</p>
                    <p id="forgot-otp-status" class="mt-2 hidden text-[12px] text-[#082341]"></p>
                </div>

                <div>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Nhập mật khẩu mới" class="h-[42px] w-full rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Nhập lại mật khẩu mới" class="h-[42px] w-full rounded-md border border-[#d6dbe3] bg-[#f3f3f3] px-4 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none">
                </div>

                <div class="pt-1">
                    <button type="submit" class="h-[42px] min-w-[160px] rounded-md bg-black px-5 text-[16px] font-bold text-white transition-opacity hover:opacity-90">Đặt lại mật khẩu</button>
                </div>

                <div class="border-t border-[#d6dbe3] pt-5 text-[16px]">
                    <a href="{{ route('login') }}" class="text-black hover:underline">Quay lại đăng nhập</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setupOtpAjax('forgot-password-form', 'forgot-send-otp', 'forgot-phone', 'forgot-phone-error', 'forgot-otp-error', 'forgot-otp-status', @json(route('password.email')));
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
