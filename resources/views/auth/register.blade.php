@extends('layouts.app-public')

@section('title', 'Đăng ký')

@section('content')
    <section class="bg-[#f3f3f3] min-h-[calc(100vh-180px)] py-8 md:py-12">
        <div class="max-w-[430px] mx-auto px-4">
            <h1 class="text-[#082341] text-[20px] font-bold mb-5">Đăng ký</h1>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="Nhập Email của bạn"
                        class="w-full h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] px-4 text-[14px] text-[#97A3B4] placeholder-[#97A3B4] focus:outline-none focus:border-[#082341]"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Tên đăng nhập"
                        class="w-full h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] px-4 text-[14px] text-[#97A3B4] placeholder-[#97A3B4] focus:outline-none focus:border-[#082341]"
                    >
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="name" value="{{ old('name', old('username')) }}">

                <div>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Nhập mật khẩu"
                            class="w-full h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] px-4 pr-11 text-[14px] text-[#97A3B4] placeholder-[#97A3B4] focus:outline-none focus:border-[#082341]"
                        >
                        <button
                            type="button"
                            id="toggle-register-password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#97A3B4] hover:text-[#082341]"
                            aria-label="Hiện hoặc ẩn mật khẩu"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-[12px] text-[#7b8796]">Tối thiểu 8 ký tự gồm chữ cái và số, bắt buộc có 1 ký tự in hoa</p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Nhập lại mật khẩu"
                            class="w-full h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] px-4 pr-11 text-[14px] text-[#97A3B4] placeholder-[#97A3B4] focus:outline-none focus:border-[#082341]"
                        >
                        <button
                            type="button"
                            id="toggle-register-password-confirmation"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#97A3B4] hover:text-[#082341]"
                            aria-label="Hiện hoặc ẩn xác nhận mật khẩu"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-1">
                    <button type="submit" class="h-[42px] min-w-[120px] px-5 bg-black text-white text-[16px] font-bold rounded-md hover:opacity-90 transition-opacity">
                        Đăng Ký
                    </button>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <div class="h-px bg-[#d6dbe3] flex-1"></div>
                    <span class="text-[#97A3B4] text-[13px] whitespace-nowrap">Hoặc đăng ký bằng cách sau</span>
                    <div class="h-px bg-[#d6dbe3] flex-1"></div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="h-[42px] border border-[#d6dbe3] rounded-md bg-[#f3f3f3] text-[16px] text-[#082341] font-semibold">Google</button>
                    <button type="button" class="h-[42px] rounded-md bg-[#307be8] text-white text-[16px] font-semibold">Facebook</button>
                </div>

                <div class="border-t border-[#d6dbe3] pt-5 text-[16px]">
                    <a href="{{ route('login') }}" class="text-black hover:underline">Đăng nhập</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleTargets = [
                { buttonId: 'toggle-register-password', inputId: 'password' },
                { buttonId: 'toggle-register-password-confirmation', inputId: 'password_confirmation' },
            ];

            toggleTargets.forEach(({ buttonId, inputId }) => {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);

                if (!input || !button) {
                    return;
                }

                button.addEventListener('click', function () {
                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
        });
    </script>
@endpush
