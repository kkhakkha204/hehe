@extends('layouts.app-public')

@section('title', 'Cập nhật mật khẩu')

@section('content')
    <section class="min-h-[calc(100vh-180px)] bg-[#f3f3f3] py-8 md:py-12">
        <div style="max-width: 500px;" class="mx-auto px-4">
            <div class="rounded-2xl border border-[#d6dbe3] bg-white p-5 shadow-sm md:p-6">
                <h1 class="text-[22px] font-bold leading-tight text-[#082341]">Cập nhật mật khẩu</h1>
                <p class="mt-2 text-[13px] leading-6 text-[#576B84]">
                    Đặt mật khẩu để lần sau đăng nhập nhanh hơn bằng số điện thoại + mật khẩu.
                </p>

                <form method="POST" action="{{ route('otp.password.prompt.store') }}" class="mt-5 space-y-3">
                    @csrf

                    <div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Nhập mật khẩu mới"
                            class="h-[44px] w-full rounded-md border border-[#d6dbe3] bg-[#f8f9fb] px-3 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Nhập lại mật khẩu mới"
                            class="h-[44px] w-full rounded-md border border-[#d6dbe3] bg-[#f8f9fb] px-3 text-[14px] text-[#082341] placeholder-[#97A3B4] focus:border-[#082341] focus:outline-none"
                        >
                    </div>

                    <div class="grid gap-2 pt-2 sm:grid-cols-2">
                        <button
                            type="submit"
                            class="h-[80px] rounded-md bg-black py-2 px-4 text-[16px] font-bold text-white transition-opacity hover:opacity-90"
                        >
                            Cập nhật mật khẩu
                        </button>

                        <button
                            type="submit"
                            form="skip-otp-password-prompt"
                            class="h-[80px] rounded-md border border-[#c6ceda] bg-white py-2 px-4 text-[16px] font-semibold text-[#082341] transition-colors hover:bg-[#f3f6fb]"
                        >
                            Bỏ qua
                        </button>
                    </div>
                </form>
            </div>

            <form id="skip-otp-password-prompt" method="POST" action="{{ route('otp.password.prompt.skip') }}" class="hidden">
                @csrf
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.addEventListener('pageshow', function (event) {
            const navigationEntry = performance.getEntriesByType('navigation')[0];

            if (event.persisted || navigationEntry?.type === 'back_forward') {
                window.location.reload();
            }
        });
    </script>
@endpush
