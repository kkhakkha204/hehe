@extends('layouts.app-public')

@section('title', 'Tài khoản')

@section('content')
    <section class="bg-white py-8 md:py-10">
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
            <div class="border-t-4 border-black">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <a
                        href="{{ route('profile.edit', ['tab' => 'courses']) }}"
                        class="flex items-center justify-center gap-3 px-6 py-5 text-center heading-font text-[20px] uppercase transition-colors duration-200 {{ $activeTab === 'courses' ? 'bg-black text-white' : 'bg-white text-black hover:bg-black/5' }}"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm1 3h10v2H5V6zm0 4h10v4H5v-4z"></path>
                        </svg>
                        Các khóa học đã đăng ký
                    </a>
                    <a
                        href="{{ route('profile.edit', ['tab' => 'settings']) }}"
                        class="flex items-center justify-center gap-3 px-6 py-5 text-center heading-font text-[20px] uppercase transition-colors duration-200 {{ $activeTab === 'settings' ? 'bg-black text-white' : 'bg-white text-black hover:bg-black/5' }}"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17a1 1 0 00-1.98 0l-.2 1.38a1 1 0 01-.96.84l-1.4.06a1 1 0 00-.61 1.77l1.08.88a1 1 0 01.32 1.04l-.35 1.36a1 1 0 001.5 1.1l1.18-.7a1 1 0 011.04 0l1.18.7a1 1 0 001.5-1.1l-.35-1.36a1 1 0 01.32-1.04l1.08-.88a1 1 0 00-.61-1.77l-1.4-.06a1 1 0 01-.96-.84l-.2-1.38zM10.5 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" clip-rule="evenodd"></path>
                        </svg>
                        Cài đặt
                    </a>
                </div>
            </div>

            @if($activeTab === 'courses')
                <div class="pt-8">
                    <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="heading-font text-[46px] leading-none text-[#0b2545] md:text-[56px]">Các khóa học đã đăng ký</h1>
                        </div>
                        <button type="button" class="inline-flex items-center gap-2 self-start text-[16px] text-[#60758a] md:self-auto">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19h16M7 16V8m5 8V4m5 12v-6"></path>
                            </svg>
                            Ẩn số liệu
                        </button>
                    </div>

                    <div class="mb-8 h-px bg-[#d8dde4]"></div>

                    <div class="mb-9">
                        <div class="max-w-[300px] border border-[#d8dde4] bg-white px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#efefef]">
                                    <svg class="h-7 w-7 text-black" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 6l7 4 7-4-7-4zm-6 6.5L10 12l6-3.5V14l-6 3-6-3V8.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[16px] text-[#5c6b7a]">Các gói</p>
                                    <p class="text-[20px] font-semibold text-[#0b2545]">{{ $courseStats['packages'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10 grid grid-cols-1 border border-[#d8dde4] md:grid-cols-4">
                        <div class="border-b-2 border-r border-black bg-[#f6f6f6] px-6 py-4 md:border-b-0">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#ececec]">
                                    <svg class="h-7 w-7 text-black" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4h4v4H4V4zm0 8h4v4H4v-4zm8-8h4v4h-4V4zm0 8h4v4h-4v-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[16px] text-[#5c6b7a]">Tất cả</p>
                                    <p class="text-[20px] font-semibold text-[#0b2545]">{{ $courseStats['all'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-r border-[#d8dde4] px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#e7f8d8] text-[#69c129]">
                                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[16px] text-[#5c6b7a]">Đã hoàn thành</p>
                                    <p class="text-[20px] font-semibold text-[#0b2545]">{{ $courseStats['completed'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-r border-[#d8dde4] px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#fff3d6] text-[#f3a400]">
                                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3H7a2 2 0 00-2 2v10a2 2 0 002 2h6.586A2 2 0 0015 16.414l2.414-2.414A2 2 0 0018 12.586V7a4 4 0 00-4-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[16px] text-[#5c6b7a]">Đang tiến hành</p>
                                    <p class="text-[20px] font-semibold text-[#0b2545]">{{ $courseStats['in_progress'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#ffe1e1] text-[#ff4d4d]">
                                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm2.707-10.293a1 1 0 00-1.414-1.414L10 7.586 8.707 6.293a1 1 0 00-1.414 1.414L8.586 9l-1.293 1.293a1 1 0 101.414 1.414L10 10.414l1.293 1.293a1 1 0 001.414-1.414L11.414 9l1.293-1.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[16px] text-[#5c6b7a]">Thất bại</p>
                                    <p class="text-[20px] font-semibold text-[#0b2545]">{{ $courseStats['failed'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($courses->isEmpty())
                        <div class="rounded-2xl border border-[#d8dde4] bg-[#f8f9fb] px-8 py-12 text-center">
                            <h2 class="heading-font text-[34px] text-[#111]">Chưa có khóa học nào</h2>
                            <p class="mt-3 text-[18px] text-[#64748b]">Khi đăng ký khóa học, chúng sẽ xuất hiện tại đây.</p>
                            <a href="{{ route('courses.index') }}" class="mt-6 inline-flex bg-black px-7 py-3.5 heading-font text-[18px] uppercase text-white">
                                Xem khóa học
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                            @foreach($courses as $item)
                                <article class="overflow-hidden border border-[#d8dde4] bg-white">
                                    <div class="aspect-[4/3] overflow-hidden bg-[#f0f2f5]">
                                        @if($item['course']->thumbnail_url)
                                            <img src="{{ $item['course']->thumbnail_url }}" alt="{{ $item['course']->title }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>

                                    <div class="px-5 py-4">
                                        <p class="mb-3 text-[15px] text-[#9aa3ad]">{{ $item['course']->category?->name ?? 'Khóa học online' }}</p>
                                        <h3 class="min-h-[84px] text-[18px] leading-[1.35] text-[#ef4c7d]">
                                            {{ $item['course']->title }}
                                        </h3>

                                        <div class="mt-6">
                                            <p class="text-[16px] text-[#0b2545]">{{ $item['progress_percent'] }}% Hoàn thành</p>
                                            <div class="mt-2 h-2 bg-[#e7e7e7]">
                                                <div class="h-full bg-black" style="width: {{ $item['progress_percent'] }}%"></div>
                                            </div>
                                        </div>

                                        <a href="{{ $item['action_url'] }}" class="mt-6 flex w-full items-center justify-center bg-black px-4 py-3.5 heading-font text-[18px] uppercase text-white">
                                            Xem khóa học
                                        </a>

                                        <p class="mt-4 text-center text-[15px] text-[#9aa3ad]">
                                            Bắt đầu từ {{ optional($item['enrollment']->enrolled_at)->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 gap-8 pt-8 lg:grid-cols-[320px_minmax(0,1fr)]">
                    <aside>
                        <div class="rounded-[32px] border border-[#e4e8ef] bg-white px-7 py-7 text-center">
                            <div class="mx-auto flex h-48 w-48 items-center justify-center rounded-full bg-[#d9d9d9] text-[#f5f5f5]">
                                <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a3 3 0 116 0 3 3 0 01-6 0zm-2 7a5 5 0 1110 0H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>

                            <h2 class="mt-6 text-[26px] font-semibold text-[#111]">{{ $user->name }}</h2>
                            <p class="mt-1 text-[18px] text-[#111]">Học viên</p>
                        </div>

                        <div class="mt-6 rounded-[24px] border border-[#e4e8ef] bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.05)]">
                            <h3 class="heading-font text-[26px] leading-[1.1] text-[#111]">Bạn có thắc mắc gì không?</h3>
                            <p class="mt-2 text-[17px] leading-7 text-[#334155]">
                                Nếu cần hỗ trợ thêm, hãy nhắn ngay cho đội ngũ để được tư vấn nhanh hơn.
                            </p>
                            <a href="https://www.facebook.com/people/MEW-ART-MAKE-UP/61578945831825/" target="_blank" rel="noopener noreferrer" class="mt-5 inline-flex rounded-xl bg-[#21c999] px-6 py-3.5 heading-font text-[18px] uppercase text-white">
                                Gửi yêu cầu
                            </a>
                        </div>
                    </aside>

                    <div>
                        <div class="mb-6 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                            <h1 class="heading-font text-[54px] leading-none text-[#111]">Hồ sơ của tôi</h1>
                        </div>

                        @if (session('status') === 'profile-updated')
                            <div class="mb-5 rounded-2xl border border-[#bae6d3] bg-[#f0fdf4] px-5 py-3.5 text-[16px] text-[#166534]">
                                Thông tin tài khoản đã được cập nhật.
                            </div>
                        @endif

                        @if (session('status') === 'password-updated')
                            <div class="mb-5 rounded-2xl border border-[#c7d2fe] bg-[#eef2ff] px-5 py-3.5 text-[16px] text-[#3730a3]">
                                Mật khẩu đã được cập nhật.
                            </div>
                        @endif

                        <form id="profile-settings-form" method="POST" action="{{ route('profile.update') }}?tab=settings" class="rounded-[28px] bg-[#eef3fb] px-6 py-7 md:px-10">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="name" class="mb-3 block text-[18px] font-semibold text-[#111]">Tên</label>
                                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="h-14 w-full border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111] outline-none focus:border-black">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="mb-3 block text-[18px] font-semibold text-[#111]">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="h-14 w-full border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111] outline-none focus:border-black">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 max-w-[520px]">
                                <label for="phone" class="mb-3 block text-[18px] font-semibold text-[#111]">Số điện thoại</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại của bạn" class="h-14 w-full border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111] outline-none focus:border-black">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6 max-w-[520px]">
                                <label class="mb-3 block text-[18px] font-semibold text-[#111]">Tên hiển thị</label>
                                <div class="flex h-14 items-center justify-between border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111]">
                                    <span>{{ $user->name }}</span>
                                    <svg class="h-5 w-5 text-[#94a3b8]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="mt-2 max-w-[520px] text-[16px] leading-7 text-[#64748b]">
                                    Tên hiển thị được dùng trên tài khoản và các khu vực học tập của bạn.
                                </p>
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="inline-flex rounded-full bg-black px-9 py-3.5 heading-font text-[18px] uppercase text-white">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>

                        <div class="mt-8">
                            <h2 class="heading-font text-[50px] leading-none text-[#111]">Đổi mật khẩu</h2>
                        </div>

                        <form id="password-settings-form" method="POST" action="{{ route('password.update') }}?tab=settings" class="mt-5 rounded-[28px] bg-[#eef3fb] px-6 py-7 md:px-10">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label for="current_password" class="mb-3 block text-[18px] font-semibold text-[#111]">Mật khẩu hiện tại</label>
                                    <input id="current_password" name="current_password" type="password" class="h-14 w-full max-w-[520px] border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111] outline-none focus:border-black">
                                    @if($errors->updatePassword->has('current_password'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="password" class="mb-3 block text-[18px] font-semibold text-[#111]">Mật khẩu mới</label>
                                    <input id="password" name="password" type="password" class="h-14 w-full border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111] outline-none focus:border-black">
                                    @if($errors->updatePassword->has('password'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="password_confirmation" class="mb-3 block text-[18px] font-semibold text-[#111]">Nhập lại mật khẩu mới</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="h-14 w-full border border-[#d7dee8] bg-white px-5 text-[18px] text-[#111] outline-none focus:border-black">
                                    @if($errors->updatePassword->has('password_confirmation'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <button form="password-settings-form" type="submit" class="order-1 inline-flex rounded-full bg-black px-9 py-3.5 heading-font text-[18px] uppercase text-white sm:order-1">
                                Lưu mật khẩu
                            </button>
                            <button form="logout-form" type="submit" class="order-2 inline-flex items-center gap-2 self-start rounded-full bg-[#eef3fb] px-8 py-3.5 text-[18px] text-[#334155] sm:order-3">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V7m-6 14h6a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Đăng xuất
                            </button>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
