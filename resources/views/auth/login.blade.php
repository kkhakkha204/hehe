@extends('layouts.app-public')

@section('title', 'Đăng nhập')

@section('content')
    <section class="bg-[#f3f3f3] min-h-[calc(100vh-180px)] py-8 md:py-12">
        <div class="max-w-[430px] mx-auto px-4">
            <h1 class="text-[#082341] text-[20px] font-bold mb-5">Đăng nhập</h1>

            <x-auth-session-status class="mb-4 text-sm text-green-700" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <input
                        id="login"
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        required
                        autofocus
                        placeholder="Nhập email hoặc tên đăng nhập"
                        class="w-full h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] px-4 text-[14px] text-[#97A3B4] placeholder-[#97A3B4] focus:outline-none focus:border-[#082341]"
                    >
                    @error('login')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Nhập mật khẩu"
                            class="w-full h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] px-4 pr-11 text-[14px] text-[#97A3B4] placeholder-[#97A3B4] focus:outline-none focus:border-[#082341]"
                        >
                        <button
                            type="button"
                            id="toggle-login-password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#97A3B4] hover:text-[#082341]"
                            aria-label="Hiện hoặc ẩn mật khẩu"
                        >
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
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="w-[15px] h-[15px] rounded border-[#c8ced8] text-black focus:ring-black"
                        >
                        Lưu tài khoản
                    </label>

                    <button type="submit" class="h-[42px] min-w-[132px] bg-black text-white text-[16px] font-bold rounded-md hover:opacity-90 transition-opacity">
                        Đăng Nhập
                    </button>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <div class="h-px bg-[#d6dbe3] flex-1"></div>
                    <span class="text-[#97A3B4] text-[13px] whitespace-nowrap">Đăng nhập với mạng xã hội</span>
                    <div class="h-px bg-[#d6dbe3] flex-1"></div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] text-[16px] text-[#082341] font-semibold">Google</button>
                    <button type="button" class="h-[42px] rounded-md bg-[#307be8] text-white text-[16px] font-semibold">Facebook</button>
                </div>

                <div class="border-t border-[#d6dbe3] pt-5 flex items-center justify-between text-[16px]">
                    <a href="{{ route('register') }}" class="text-black hover:underline">Đăng ký</a>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[#576B84] hover:underline">Quên mật khẩu?</a>
                    @endif
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-login-password');

            if (!passwordInput || !toggleButton) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            });
        });
    </script>
@endpush
