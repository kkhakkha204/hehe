@extends('layouts.app-public')

@section('title', 'Tài khoản')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .student-page {
            --sp-primary: #c8a87c;
            --sp-primary-dark: #b08d5e;
            --sp-accent: #d4956a;
            --sp-bg: #fbf8f4;
            --sp-bg-soft: #f4ede3;
            --sp-border: #eadfce;
            --sp-text: #4a4a4a;
            --sp-muted: #7b7b7b;
            --sp-dark: #171717;
            --sp-shadow: 0 16px 40px rgba(23, 23, 23, 0.08);
            background:
                radial-gradient(circle at top left, rgba(200, 168, 124, 0.16), transparent 26%),
                linear-gradient(180deg, #fffdf9 0%, var(--sp-bg) 100%);
            font-family: 'Inter', sans-serif;
        }

        .student-serif { font-family: 'Playfair Display', serif; }
        .student-panel {
            border: 1px solid var(--sp-border);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: var(--sp-shadow);
        }

        .student-shell {
            max-width: 1180px;
            gap: .7rem;
        }

        .student-sidebar-stack,
        .student-main-stack {
            gap: .75rem;
        }

        .student-profile-panel,
        .student-header-panel,
        .student-course-section,
        .student-settings-panel,
        .student-help-panel {
            padding: .85rem !important;
            border-radius: 20px !important;
        }

        .student-nav-panel {
            padding: .42rem !important;
            border-radius: 20px !important;
        }

        .student-avatar {
            background: linear-gradient(135deg, var(--sp-primary), var(--sp-accent));
            box-shadow: 0 18px 34px rgba(200, 168, 124, 0.28);
        }

        .student-profile-panel .student-avatar {
            width: 4.2rem;
            height: 4.2rem;
            margin-bottom: .6rem;
            font-size: 1.5rem;
        }

        .student-profile-panel h1 {
            font-size: 1.28rem !important;
        }

        .student-profile-panel > p {
            margin-top: .35rem !important;
            font-size: .6rem !important;
            letter-spacing: .12em !important;
        }

        .student-profile-panel .rounded-2xl {
            padding: .5rem .68rem !important;
            font-size: .68rem !important;
        }

        .student-nav-link {
            transition: all .2s ease;
        }

        .student-nav-panel .student-nav-link {
            padding: .56rem .68rem !important;
            border-radius: 12px !important;
            font-size: .72rem !important;
        }

        .student-nav-link.active {
            background: linear-gradient(135deg, #171717 0%, #2a2a2a 100%);
            color: #fff;
            box-shadow: 0 14px 28px rgba(23, 23, 23, 0.15);
        }

        .student-nav-link:not(.active):hover {
            background: var(--sp-bg);
            color: var(--sp-dark);
        }

        .student-stat-icon,
        .student-chip {
            background: var(--sp-bg);
            color: var(--sp-primary-dark);
        }

        .student-progress-track {
            background: var(--sp-bg-soft);
        }

        .student-progress-fill {
            background: linear-gradient(90deg, var(--sp-primary), var(--sp-accent));
        }

        .student-search,
        .student-input {
            border: 1px solid var(--sp-border);
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .student-search:focus,
        .student-input:focus {
            outline: none;
            border-color: rgba(200, 168, 124, 0.95);
            box-shadow: 0 0 0 4px rgba(200, 168, 124, 0.14);
        }

        .student-btn-primary {
            background: linear-gradient(135deg, var(--sp-primary), var(--sp-accent));
            color: #fff;
            box-shadow: 0 14px 28px rgba(200, 168, 124, 0.22);
        }

        .student-btn-dark {
            background: #171717;
            color: #fff;
        }

        .student-help-panel h2 {
            font-size: 1.15rem !important;
        }

        .student-help-panel p {
            font-size: .67rem !important;
            line-height: 1.5 !important;
        }

        .student-help-panel a,
        .student-help-panel button,
        .student-course-section .student-btn-dark,
        .student-course-section .student-btn-primary,
        .student-settings-panel .student-btn-dark,
        .student-settings-panel .student-btn-primary {
            height: 2rem !important;
            padding-inline: .78rem !important;
            font-size: .66rem !important;
        }

        .student-header-panel h2 {
            font-size: clamp(1.18rem, 1.6vw, 1.5rem) !important;
        }

        .student-header-panel p {
            font-size: .66rem !important;
            line-height: 1.45 !important;
        }

        .student-header-panel input {
            height: 2.05rem !important;
            font-size: .66rem !important;
        }

        .student-course-grid {
            display: grid;
            gap: .55rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .student-course-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .student-course-thumb {
            height: 122px;
        }

        .student-course-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: .4rem;
            padding: .55rem;
        }

        .student-course-card .student-chip {
            font-size: .56rem !important;
            padding: .24rem .46rem !important;
            line-height: 1.2;
        }

        .student-course-card .student-title-clamp {
            -webkit-line-clamp: 1;
            font-family: 'Inter', sans-serif;
            font-size: .82rem !important;
            font-weight: 700;
            line-height: 1.24 !important;
            letter-spacing: -.01em;
        }

        .student-course-card .student-desc-clamp {
            -webkit-line-clamp: 2;
            font-size: .66rem !important;
            line-height: 1.4 !important;
            color: #666;
        }

        .student-course-note,
        .student-course-card .student-course-body > .flex.items-center.justify-between.gap-3.pt-1 > p {
            font-size: .6rem;
            color: var(--sp-muted);
        }

        .student-course-cta,
        .student-course-card .student-btn-primary {
            height: 1.82rem;
            padding-inline: .62rem;
            font-size: .62rem !important;
            letter-spacing: .02em;
        }

        .student-course-progress {
            font-size: .62rem;
        }

        .student-course-card .absolute.left-4.top-4 {
            top: .55rem;
            left: .55rem;
            padding: .22rem .42rem;
            font-size: .54rem;
        }

        .student-course-card .student-price-badge {
            position: absolute;
            top: .55rem;
            right: .55rem;
            z-index: 10;
            border-radius: 999px;
            padding: .34rem .74rem;
            font-size: .66rem;
            font-weight: 800;
            line-height: 1.2;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.14);
        }

        .student-course-card .student-price-badge--free {
            background: rgba(231, 248, 238, 0.94);
            color: #067647;
        }

        .student-course-card .student-price-badge--paid {
            background: rgba(255, 241, 235, 0.94);
            color: #f05123;
        }

        .student-course-card img {
            transition: transform .35s ease;
        }

        .student-course-card:hover img {
            transform: scale(1.04);
        }

        .student-title-clamp,
        .student-desc-clamp {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .student-title-clamp { -webkit-line-clamp: 2; }
        .student-desc-clamp { -webkit-line-clamp: 3; }

        .student-course-section h3,
        .student-settings-panel h3 {
            font-size: 1.08rem !important;
        }

        .student-course-section > .mb-6 p,
        .student-settings-panel > p,
        .student-settings-panel .mt-5 .text-\[14px\] {
            font-size: .66rem !important;
            line-height: 1.42 !important;
        }

        .student-settings-panel label {
            font-size: .66rem !important;
        }

        .student-settings-panel input {
            height: 2.15rem !important;
            font-size: .68rem !important;
        }

        .student-page .rounded-\[22px\] {
            padding: .62rem .76rem !important;
            font-size: .68rem !important;
        }

        @media (min-width: 768px) {
            .student-course-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1536px) {
            .student-course-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1024px) {
            .student-sticky {
                position: static;
            }

            .student-shell {
                gap: .875rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $avatarLetter = mb_strtoupper(mb_substr(trim($user->name ?: 'H'), 0, 1));
    @endphp

    <section class="student-page py-5 md:py-6">
        <div class="student-shell mx-auto grid gap-4 px-4 lg:grid-cols-[228px_minmax(0,1fr)]">
            <aside class="student-sidebar-stack student-sticky top-[92px] self-start space-y-4">
                <div class="student-panel student-profile-panel rounded-[28px] p-6 text-center">
                    <div class="student-avatar mx-auto mb-3 flex h-28 w-28 items-center justify-center rounded-full text-[42px] font-bold text-white student-serif">
                        {{ $avatarLetter }}
                    </div>
                    <h1 class="student-serif text-[32px] leading-none text-[#171717]">{{ $user->name }}</h1>
                    <p class="mt-2 text-[13px] font-semibold uppercase tracking-[0.18em] text-[#b08d5e]">Học viên Mew Art</p>

                    <div class="mt-4 space-y-2 text-left">
                        <div class="rounded-2xl bg-[#fbf8f4] px-4 py-3 text-[14px] text-[#4a4a4a]">
                            {{ $user->email ?: 'Chưa cập nhật email' }}
                        </div>
                        <div class="rounded-2xl bg-[#fbf8f4] px-4 py-3 text-[14px] text-[#4a4a4a]">
                            {{ $user->phone ?: 'Chưa cập nhật số điện thoại' }}
                        </div>
                    </div>
                </div>

                <div class="student-panel student-nav-panel rounded-[28px] p-3">
                    <a href="{{ route('profile.edit', ['tab' => 'courses']) }}" class="student-nav-link {{ $activeTab === 'courses' ? 'active' : '' }} flex items-center justify-between rounded-[18px] px-4 py-3 text-[15px] font-semibold">
                        <span>Khóa học của tôi</span>
                        <span class="text-[12px] opacity-70">{{ $courseStats['all'] }}</span>
                    </a>
                    <a href="{{ route('profile.edit', ['tab' => 'settings']) }}" class="student-nav-link {{ $activeTab === 'settings' ? 'active' : '' }} mt-2 flex items-center justify-between rounded-[18px] px-4 py-3 text-[15px] font-semibold">
                        <span>Hồ sơ cá nhân</span>
                        <span class="text-[12px] opacity-70">Account</span>
                    </a>
                </div>

                <div class="student-panel student-help-panel rounded-[28px] p-6">
                    <h2 class="student-serif text-[28px] leading-none text-[#171717]">Cần hỗ trợ?</h2>
                    <p class="mt-3 text-[14px] leading-7 text-[#5f5f5f]">Nhắn trực tiếp cho đội ngũ để được hỗ trợ lộ trình học, tài khoản hoặc tư vấn khóa học phù hợp.</p>
                    <div class="mt-4 flex flex-wrap gap-2.5">
                        <a href="https://www.facebook.com/people/MEW-ART-MAKE-UP/61578945831825/" target="_blank" rel="noopener noreferrer" class="student-btn-primary inline-flex h-11 items-center justify-center rounded-full px-5 text-[14px] font-bold">Nhắn tư vấn</a>
                        <button form="logout-form" type="submit" class="inline-flex h-11 items-center justify-center rounded-full border border-[#eadfce] px-5 text-[14px] font-semibold text-[#171717]">Đăng xuất</button>
                    </div>
                </div>
            </aside>

            <div class="student-main-stack space-y-4">
                <div class="student-panel student-header-panel rounded-[28px] p-6 md:flex md:items-center md:justify-between md:gap-6">
                    <div>
                        <h2 class="student-serif text-[28px] leading-none text-[#171717] md:text-[34px]">
                            {{ $activeTab === 'courses' ? 'Lộ trình học tập' : 'Hồ sơ người dùng' }}
                        </h2>
                        <p class="mt-3 text-[13px] text-[#7b7b7b]">
                            {{ $activeTab === 'courses'
                                ? 'Giao diện mới theo phong cách dashboard học tập, giúp theo dõi tiến độ và tiếp tục học nhanh hơn.'
                                : 'Cập nhật thông tin cá nhân và mật khẩu trên một giao diện gọn gàng, dễ thao tác hơn.' }}
                        </p>
                    </div>

                    @if($activeTab === 'courses')
                        <div class="mt-3 md:mt-0 md:min-w-[280px]">
                            <input id="profile-course-search" type="text" placeholder="Tìm khóa học đã đăng ký..." class="student-search h-10 w-full rounded-full px-4 text-[12px] text-[#171717]">
                        </div>
                    @endif
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="rounded-[22px] border border-[#cde7d3] bg-[#f2fbf4] px-5 py-4 text-[14px] font-semibold text-[#21633b]">Thông tin tài khoản đã được cập nhật.</div>
                @endif

                @if (session('status') === 'password-updated')
                    <div class="rounded-[22px] border border-[#d8def8] bg-[#f3f5ff] px-5 py-4 text-[14px] font-semibold text-[#3342a5]">Mật khẩu đã được cập nhật thành công.</div>
                @endif

                @if($activeTab === 'courses')
                    @if(false)
                    <div class="hidden grid gap-4 md:grid-cols-2 xl:grid-cols-4" aria-hidden="true">
                        @foreach ([
                            ['label' => 'Đã đăng ký', 'value' => $courseStats['all']],
                            ['label' => 'Đã hoàn thành', 'value' => $courseStats['completed']],
                            ['label' => 'Đang học', 'value' => $courseStats['in_progress']],
                            ['label' => 'Gói combo', 'value' => $courseStats['packages']],
                        ] as $stat)
                            <div class="student-panel rounded-[24px] p-5">
                                <div class="flex items-center gap-4">
                                    <div class="student-stat-icon flex h-12 w-12 items-center justify-center rounded-2xl">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M5 7.5A2.5 2.5 0 017.5 5h9A2.5 2.5 0 0119 7.5v9A2.5 2.5 0 0116.5 19h-9A2.5 2.5 0 015 16.5v-9z" stroke="currentColor" stroke-width="1.8"/>
                                            <path d="M9 10h6M9 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[30px] font-bold leading-none text-[#171717]">{{ $stat['value'] }}</p>
                                        <p class="mt-2 text-[13px] text-[#7b7b7b]">{{ $stat['label'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @endif
                    <div class="student-panel student-course-section rounded-[28px] p-6">
                        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                            <div>
                                <h3 class="student-serif text-[24px] leading-none text-[#171717] md:text-[26px]">Khóa học đang sở hữu</h3>
                                <p class="mt-2 text-[13px] text-[#7b7b7b]">Tiếp tục học ngay từ nơi bạn đang dừng, với bố cục mới gọn hơn và đồng đều hơn.</p>
                            </div>
                            <a href="{{ route('courses.index') }}" class="student-btn-dark inline-flex h-10 items-center justify-center rounded-full px-4 text-[13px] font-bold">Khám phá thêm khóa học</a>
                        </div>

                        @if($courses->isEmpty())
                            <div class="rounded-[24px] bg-[#fbf8f4] px-6 py-10 text-center">
                                <h4 class="student-serif text-[32px] leading-none text-[#171717]">Chưa có khóa học nào</h4>
                                <p class="mx-auto mt-3 max-w-[520px] text-[14px] leading-7 text-[#7b7b7b]">Khi bạn đăng ký khóa học, toàn bộ tiến độ học sẽ xuất hiện tại đây.</p>
                                <a href="{{ route('courses.index') }}" class="student-btn-primary mt-6 inline-flex h-11 items-center justify-center rounded-full px-6 text-[14px] font-bold">Xem khóa học</a>
                            </div>
                        @else
                            <div id="profile-course-grid" class="student-course-grid">
                                @foreach($courses as $item)
                                    @php
                                        $course = $item['course'];
                                        $statusLabel = match ($item['status']) {
                                            'completed' => 'Đã hoàn thành',
                                            'in_progress' => 'Đang học',
                                            default => 'Chưa bắt đầu',
                                        };
                                        $actionLabel = $item['progress_percent'] > 0 ? 'Tiếp tục học' : 'Bắt đầu học';
                                    @endphp

                                    <article class="student-course-card student-panel overflow-hidden rounded-[26px]" data-course-card data-search="{{ \Illuminate\Support\Str::lower($course->title.' '.($course->category?->name ?? '').' '.($course->author?->name ?? '')) }}">
                                        <div class="student-course-thumb relative overflow-hidden bg-[#f4ede3]">
                                            @if($course->thumbnail_url)
                                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                            @endif

                                            <div class="absolute left-4 top-4 rounded-full bg-black/70 px-3 py-2 text-[12px] font-bold text-white backdrop-blur">
                                                {{ $statusLabel }}
                                            </div>

                                            <div class="student-price-badge {{ $course->isFree() ? 'student-price-badge--free' : 'student-price-badge--paid' }}">
                                                @if($course->isFree())
                                                    Miễn phí
                                                @elseif($course->sale_price && $course->sale_price < $course->price)
                                                    {{ number_format((float) $course->sale_price, 0, ',', '.') }}đ
                                                @else
                                                    {{ number_format((float) $course->price, 0, ',', '.') }}đ
                                                @endif
                                            </div>
                                        </div>

                                        <div class="student-course-body">
                                            <div class="flex flex-wrap gap-2">
                                                <span class="student-chip rounded-full px-3 py-2 text-[12px] font-semibold">{{ $item['total_lessons'] }} bài học</span>
                                                @if($course->category?->name)
                                                    <span class="student-chip rounded-full px-3 py-2 text-[12px] font-semibold">{{ $course->category->name }}</span>
                                                @endif
                                            </div>

                                            <h3 class="student-title-clamp text-[#171717]" title="{{ $course->title }}">{{ $course->title }}</h3>
                                            <p class="student-desc-clamp text-[14px] leading-7 text-[#5f5f5f]">{{ \Illuminate\Support\Str::limit(strip_tags((string) $course->description), 125) ?: 'Khóa học này đang chờ bạn bắt đầu để mở khóa toàn bộ lộ trình học tập.' }}</p>

                                            <div class="mt-auto">
                                                <div class="student-course-progress mb-2 flex items-center justify-between gap-3 font-semibold text-[#171717]">
                                                    <span>Tiến độ học tập</span>
                                                    <span>{{ $item['progress_percent'] }}%</span>
                                                </div>
                                                <div class="student-progress-track h-2 overflow-hidden rounded-full">
                                                    <div class="student-progress-fill h-full rounded-full" style="width: {{ $item['progress_percent'] }}%"></div>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between gap-3 pt-1">
                                                <p class="text-[12px] text-[#7b7b7b]">{{ $item['completed_lessons'] }}/{{ $item['total_lessons'] }} bài đã hoàn thành</p>
                                                <a href="{{ $item['action_url'] }}" class="student-btn-primary student-course-cta inline-flex items-center justify-center rounded-full font-bold">{{ $actionLabel }}</a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="student-panel student-course-section rounded-[28px] p-6">
                        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                            <div>
                                <h3 class="student-serif text-[24px] leading-none text-[#171717] md:text-[26px]">Khóa học chưa đăng ký</h3>
                                <p class="mt-2 text-[13px] text-[#7b7b7b]">Phần dưới hiển thị thêm các khóa học anh chưa mua, đúng kiểu chia 2 tầng danh sách như file HTML sếp gửi.</p>
                            </div>
                            <a href="{{ route('courses.index') }}" class="inline-flex h-10 items-center justify-center rounded-full border border-[#eadfce] px-4 text-[13px] font-semibold text-[#171717]">Xem toàn bộ</a>
                        </div>

                        @if(($availableCourses ?? collect())->isEmpty())
                            <div class="rounded-[24px] bg-[#fbf8f4] px-6 py-10 text-center">
                                <h4 class="student-serif text-[30px] leading-none text-[#171717]">Hiện chưa còn khóa gợi ý</h4>
                                <p class="mx-auto mt-3 max-w-[520px] text-[14px] leading-7 text-[#7b7b7b]">Tạm thời anh đã đăng ký gần hết các khóa đang public hoặc hệ thống chưa có khóa mới để đề xuất thêm.</p>
                            </div>
                        @else
                            <div class="student-course-grid">
                                @foreach($availableCourses as $course)
                                    <article class="student-course-card student-panel overflow-hidden rounded-[26px]" data-course-card data-search="{{ \Illuminate\Support\Str::lower($course->title.' '.($course->category?->name ?? '').' '.($course->author?->name ?? '')) }}">
                                        <div class="student-course-thumb relative overflow-hidden bg-[#f4ede3]">
                                            @if($course->thumbnail_url)
                                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                            @endif

                                            <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-2 text-[12px] font-bold text-[#171717] backdrop-blur">
                                                Chưa đăng ký
                                            </div>
                                            <div class="student-price-badge {{ $course->isFree() ? 'student-price-badge--free' : 'student-price-badge--paid' }}">
                                                @if($course->isFree())
                                                    Miễn phí
                                                @elseif($course->sale_price && $course->sale_price < $course->price)
                                                    {{ number_format((float) $course->sale_price, 0, ',', '.') }}đ
                                                @else
                                                    {{ number_format((float) $course->price, 0, ',', '.') }}đ
                                                @endif
                                            </div>
                                        </div>

                                        <div class="student-course-body">
                                            <div class="flex flex-wrap gap-2">
                                                @if($course->category?->name)
                                                    <span class="student-chip rounded-full px-3 py-2 text-[12px] font-semibold">{{ $course->category->name }}</span>
                                                @endif
                                                @if($course->author?->name)
                                                    <span class="student-chip rounded-full px-3 py-2 text-[12px] font-semibold">{{ $course->author->name }}</span>
                                                @endif
                                            </div>

                                            <h3 class="student-title-clamp text-[#171717]" title="{{ $course->title }}">{{ $course->title }}</h3>
                                            <p class="student-desc-clamp text-[14px] leading-7 text-[#5f5f5f]">{{ \Illuminate\Support\Str::limit(strip_tags((string) $course->description), 125) ?: 'Khóa học này có thể là bước tiếp theo phù hợp nếu anh muốn mở rộng thêm kỹ năng hoặc lộ trình học hiện tại.' }}</p>

                                            <div class="flex items-center justify-end gap-3 pt-1">
                                                <a href="{{ route('courses.show', $course->slug) }}" class="student-btn-primary inline-flex h-11 items-center justify-center rounded-full px-5 text-[14px] font-bold">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
                        <div class="student-panel student-settings-panel rounded-[28px] p-6">
                            <h3 class="student-serif text-[30px] leading-none text-[#171717]">Thông tin cá nhân</h3>
                            <p class="mt-3 text-[14px] leading-7 text-[#7b7b7b]">Cập nhật hồ sơ để hiển thị đúng trên trang tài khoản và giúp đội ngũ hỗ trợ bạn nhanh hơn khi cần.</p>

                            <form method="POST" action="{{ route('profile.update') }}?tab=settings" class="mt-6 grid gap-4 md:grid-cols-2">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="name" class="mb-2 block text-[13px] font-bold text-[#171717]">Tên hiển thị</label>
                                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="student-input h-12 w-full rounded-2xl px-4 text-[14px] text-[#171717]">
                                    @error('name')<p class="mt-2 text-[13px] text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="email" class="mb-2 block text-[13px] font-bold text-[#171717]">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="student-input h-12 w-full rounded-2xl px-4 text-[14px] text-[#171717]">
                                    @error('email')<p class="mt-2 text-[13px] text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="phone" class="mb-2 block text-[13px] font-bold text-[#171717]">Số điện thoại</label>
                                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="student-input h-12 w-full rounded-2xl px-4 text-[14px] text-[#171717]">
                                    @error('phone')<p class="mt-2 text-[13px] text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2 pt-2">
                                    <button type="submit" class="student-btn-primary inline-flex h-11 items-center justify-center rounded-full px-6 text-[14px] font-bold">Lưu thông tin</button>
                                </div>
                            </form>
                        </div>

                        <div class="space-y-5">
                            <div class="student-panel student-settings-panel rounded-[28px] p-6">
                                <h3 class="student-serif text-[30px] leading-none text-[#171717]">Tổng quan tài khoản</h3>
                                <div class="mt-5 space-y-3">
                                    <div class="flex items-center justify-between rounded-2xl bg-[#fbf8f4] px-4 py-4 text-[14px]">
                                        <span class="text-[#5f5f5f]">Vai trò</span>
                                        <strong class="text-[#171717]">{{ $user->isAdmin() ? 'Admin' : 'Học viên' }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-[#fbf8f4] px-4 py-4 text-[14px]">
                                        <span class="text-[#5f5f5f]">Khóa học đã đăng ký</span>
                                        <strong class="text-[#171717]">{{ $courseStats['all'] }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-[#fbf8f4] px-4 py-4 text-[14px]">
                                        <span class="text-[#5f5f5f]">Khóa học đang học</span>
                                        <strong class="text-[#171717]">{{ $courseStats['in_progress'] }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="student-panel student-settings-panel rounded-[28px] p-6">
                                <h3 class="student-serif text-[30px] leading-none text-[#171717]">Đổi mật khẩu</h3>
                                <p class="mt-3 text-[14px] leading-7 text-[#7b7b7b]">Đặt lại mật khẩu để đăng nhập nhanh hơn ở những lần sau.</p>

                                <form id="password-settings-form" method="POST" action="{{ route('password.update') }}?tab=settings" class="mt-6 space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label for="password" class="mb-2 block text-[13px] font-bold text-[#171717]">Mật khẩu mới</label>
                                        <input id="password" name="password" type="password" class="student-input h-12 w-full rounded-2xl px-4 text-[14px] text-[#171717]">
                                        @if($errors->updatePassword->has('password'))
                                            <p class="mt-2 text-[13px] text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="mb-2 block text-[13px] font-bold text-[#171717]">Nhập lại mật khẩu mới</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" class="student-input h-12 w-full rounded-2xl px-4 text-[14px] text-[#171717]">
                                        @if($errors->updatePassword->has('password_confirmation'))
                                            <p class="mt-2 text-[13px] text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                        @endif
                                    </div>
                                </form>

                                <div class="mt-6 flex flex-wrap gap-3">
                                    <button form="password-settings-form" type="submit" class="student-btn-dark inline-flex h-11 items-center justify-center rounded-full px-6 text-[14px] font-bold">Cập nhật mật khẩu</button>
                                    <button form="logout-form" type="submit" class="inline-flex h-11 items-center justify-center rounded-full border border-[#eadfce] px-6 text-[14px] font-semibold text-[#171717]">Đăng xuất</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('profile-course-search');
            const courseCards = Array.from(document.querySelectorAll('[data-course-card]'));

            if (!searchInput || courseCards.length === 0) {
                return;
            }

            searchInput.addEventListener('input', () => {
                const keyword = searchInput.value.trim().toLowerCase();

                courseCards.forEach((card) => {
                    const haystack = (card.getAttribute('data-search') || '').toLowerCase();
                    card.style.display = keyword === '' || haystack.includes(keyword) ? '' : 'none';
                });
            });
        });
    </script>
@endpush
