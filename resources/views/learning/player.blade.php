<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lesson->title }} - {{ $course->title }}</title>
    @include('layouts.partials.assets-no-vite')
    <style>
        body {
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .bunny-video-wrapper,
        .bunny-video-wrapper > div,
        .bunny-video-wrapper iframe {
            width: 100% !important;
            height: 100% !important;
        }

        .bunny-video-wrapper {
            position: relative;
        }

        .bunny-video-wrapper > div,
        .bunny-video-wrapper iframe {
            position: absolute !important;
            inset: 0 !important;
            padding-top: 0 !important;
        }
    </style>
</head>
<body class="overflow-hidden bg-[#111317] text-white">
@php
    $allLessons = $course->chapters->pluck('lessons')->flatten()->values();
    $currentIndex = $allLessons->search(fn ($item) => $item->id === $lesson->id);
    $currentIndex = $currentIndex === false ? 0 : $currentIndex;
    $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
    $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
    $completedLessonIds = collect($completedLessonIds ?? [])->map(fn ($id) => (int) $id)->all();
    $seedComments = [
        ['name' => 'Lan Anh', 'time' => '2 giờ trước', 'content' => 'Bài giảng rất dễ hiểu, xem xong là mình áp dụng được ngay trên mặt mình luôn.'],
        ['name' => 'Trúc Vy', 'time' => 'Hôm nay', 'content' => 'Phần giải thích sản phẩm và cách chọn tone nền rất có tâm, đáng tiền thật sự.'],
        ['name' => 'Mai Hương', 'time' => 'Hôm qua', 'content' => 'Học online mà vẫn có cảm giác được cầm tay chỉ việc, nội dung gọn mà hiệu quả.'],
        ['name' => 'Bảo Ngọc', 'time' => '3 ngày trước', 'content' => 'Mình thích nhất là cách chị phân tích lỗi makeup thường gặp, dễ sửa hơn hẳn.'],
    ];
@endphp

<div
    x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        isMobile: window.innerWidth < 1024,
        commentsOpen: false,
        theme: localStorage.getItem('learning-player-theme') ?? 'dark',
        init() {
            window.addEventListener('resize', () => {
                const nextIsMobile = window.innerWidth < 1024;
                if (nextIsMobile === this.isMobile) return;
                this.isMobile = nextIsMobile;
                this.sidebarOpen = !nextIsMobile;
            });
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeSidebarIfMobile() {
            if (this.isMobile) this.sidebarOpen = false;
        },
        setTheme(nextTheme) {
            this.theme = nextTheme;
            localStorage.setItem('learning-player-theme', nextTheme);
        },
        toggleTheme() {
            this.setTheme(this.theme === 'dark' ? 'light' : 'dark');
        }
    }"
    :class="theme === 'dark' ? 'bg-[#111317] text-white' : 'bg-[#f3f4f6] text-[#101828]'"
    class="relative flex h-screen flex-col transition-colors duration-300"
>
    <header
        :class="theme === 'dark' ? 'border-white/10 bg-[#181c23]' : 'border-black/10 bg-white'"
        class="flex h-14 items-center justify-between border-b px-3 md:px-5"
    >
        <div class="flex min-w-0 items-center gap-2 md:gap-3">
            <a
                href="{{ route('courses.show', $course->slug) }}"
                :class="theme === 'dark' ? 'text-white/70 hover:bg-white/10 hover:text-white' : 'text-black/65 hover:bg-black/5 hover:text-black'"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full transition"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            <button
                type="button"
                @click="toggleSidebar()"
                :class="theme === 'dark' ? 'bg-white/8 text-white hover:bg-white/12' : 'bg-black/5 text-black hover:bg-black/10'"
                class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-[13px] font-semibold transition"
            >
                <span x-text="sidebarOpen ? '−' : '+'" class="text-base leading-none"></span>
                Giáo trình
            </button>

            <div class="min-w-0">
                <p :class="theme === 'dark' ? 'text-white/35' : 'text-black/40'" class="text-[10px] uppercase tracking-[0.18em]">Khóa học</p>
                <p :class="theme === 'dark' ? 'text-white/95' : 'text-black'" class="truncate text-sm font-semibold">{{ $course->title }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="toggleTheme()"
                :class="theme === 'dark' ? 'text-white/75 hover:bg-white/10 hover:text-white' : 'text-black/70 hover:bg-black/5 hover:text-black'"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full transition"
                title="Đổi giao diện"
            >
                <svg x-show="theme === 'dark'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-10h-1M4.34 12h-1m14.95 6.95-.71-.71M6.1 6.1l-.71-.71m12.02 0-.71.71M6.1 17.9l-.71.71M9 12a3 3 0 106 0 3 3 0 00-6 0z"></path>
                </svg>
                <svg x-show="theme === 'light'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3c0 4.97 4.03 9 9 9z"></path>
                </svg>
            </button>

            <button
                type="button"
                @click="commentsOpen = true; if (isMobile) sidebarOpen = false;"
                :class="theme === 'dark' ? 'text-white/75 hover:bg-white/10 hover:text-white' : 'text-black/70 hover:bg-black/5 hover:text-black'"
                class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium transition"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z"></path>
                </svg>
                <span class="hidden md:inline">Thảo luận</span>
            </button>
        </div>
    </header>

    <div class="flex min-h-0 flex-1">
        <div
            x-show="isMobile && sidebarOpen"
            x-transition.opacity.duration.200ms
            @click="sidebarOpen = false"
            class="absolute inset-0 z-30 bg-black/45 lg:hidden"
            style="display: none;"
        ></div>

        <aside
            :class="theme === 'dark' ? 'border-white/10 bg-[#171a20]' : 'border-black/10 bg-white'"
            class="fixed inset-y-14 left-0 z-40 h-auto w-[320px] max-w-[88vw] overflow-hidden border-r transition-all duration-300 lg:relative lg:inset-auto lg:z-auto lg:h-full lg:max-w-none"
            :style="isMobile
                ? (sidebarOpen
                    ? 'transform: translateX(0); opacity: 1;'
                    : 'transform: translateX(-100%); opacity: 0; pointer-events: none;')
                : (sidebarOpen
                    ? 'width: 320px; opacity: 1;'
                    : 'width: 0; opacity: 0;')"
        >
            <template x-if="sidebarOpen">
                <div class="flex h-full flex-col">
                    <div :class="theme === 'dark' ? 'border-white/10' : 'border-black/10'" class="border-b px-4 py-5">
                        <h1 :class="theme === 'dark' ? 'text-white' : 'text-black'" class="line-clamp-2 text-[24px] font-bold leading-[1.1] tracking-[-0.02em]">{{ $course->title }}</h1>
                        <div class="mt-5">
                            <div :class="theme === 'dark' ? 'bg-white/10' : 'bg-black/10'" class="h-2 overflow-hidden rounded-full">
                                <div class="h-full rounded-full bg-[#f05123] transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            <p :class="theme === 'dark' ? 'text-white/60' : 'text-black/55'" class="mt-2 text-xs">
                                Tiến độ khóa học: {{ $progressPercent }}% ({{ $completedLessons }}/{{ $totalLessons }} bài)
                            </p>
                        </div>
                    </div>

                    <div class="min-h-0 flex-1 overflow-y-auto" x-data="{ openChapter: {{ $chapter->id }} }">
                        @foreach ($course->chapters as $chapterItem)
                            <section :class="theme === 'dark' ? 'border-white/10' : 'border-black/10'" class="border-b">
                                <button
                                    type="button"
                                    @click="openChapter = openChapter === {{ $chapterItem->id }} ? null : {{ $chapterItem->id }}"
                                    :class="theme === 'dark' ? 'hover:bg-white/5' : 'hover:bg-black/5'"
                                    class="flex w-full items-center justify-between px-4 py-4 text-left transition"
                                >
                                    <div class="min-w-0">
                                        <h3 :class="theme === 'dark' ? 'text-white' : 'text-black'" class="truncate text-sm font-semibold">{{ $chapterItem->title }}</h3>
                                        <p :class="theme === 'dark' ? 'text-white/45' : 'text-black/45'" class="mt-1 text-xs">{{ $chapterItem->lessons->count() }} bài học</p>
                                    </div>
                                    <svg :class="openChapter === {{ $chapterItem->id }} ? 'rotate-180' : ''" class="h-4 w-4 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="openChapter === {{ $chapterItem->id }}" x-transition.opacity.duration.200ms class="space-y-1 px-2 pb-2">
                                    @foreach ($chapterItem->lessons as $lessonItem)
                                        <a
                                            href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $lessonItem->id]) }}"
                                            @click="closeSidebarIfMobile()"
                                            :class="theme === 'dark'
                                                ? '{{ $lessonItem->id === $lesson->id ? 'bg-white/10 border-white/10' : 'border-transparent hover:bg-white/5' }}'
                                                : '{{ $lessonItem->id === $lesson->id ? 'bg-black/5 border-black/10' : 'border-transparent hover:bg-black/5' }}'"
                                            class="flex items-center gap-3 rounded-2xl border px-3 py-3 transition"
                                        >
                                            <div class="relative h-11 w-11 shrink-0 overflow-hidden rounded-xl bg-black/10">
                                                @if ($lessonItem->thumbnail_url)
                                                    <img src="{{ $lessonItem->thumbnail_url }}" alt="{{ $lessonItem->title }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center">
                                                        <svg class="h-4 w-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p :class="theme === 'dark' ? 'text-white' : 'text-black'" class="line-clamp-2 text-[14px] font-medium leading-snug">{{ $lessonItem->title }}</p>
                                                <p :class="theme === 'dark' ? 'text-white/45' : 'text-black/45'" class="mt-1 text-xs">
                                                    {{ $lessonItem->is_preview ? 'Bài học xem trước' : 'Video bài học' }}
                                                </p>
                                            </div>

                                            <div class="shrink-0">
                                                @if (in_array($lessonItem->id, $completedLessonIds, true))
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#f05123] text-white">
                                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </span>
                                                @elseif ($lessonItem->id === $lesson->id)
                                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-[#f05123]/40">
                                                        <span class="block h-2.5 w-2.5 rounded-full bg-[#f05123]"></span>
                                                    </span>
                                                @else
                                                    <span :class="theme === 'dark' ? 'border-white/12' : 'border-black/12'" class="block h-4 w-4 rounded-full border"></span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </template>
        </aside>

        <main :class="theme === 'dark' ? 'bg-[#111317]' : 'bg-[#f3f4f6]'" class="min-w-0 flex-1 overflow-y-auto transition-colors duration-300">
            <div class="mx-auto w-full max-w-[1180px] px-4 py-7 md:px-8">
                <div class="mb-5">
                    <p :class="theme === 'dark' ? 'text-white/45' : 'text-black/45'" class="text-[12px] uppercase tracking-[0.18em]">Video bài học</p>
                    <h2 :class="theme === 'dark' ? 'text-white' : 'text-black'" class="mt-2 max-w-[920px] text-[28px] font-bold leading-[1.08] tracking-[-0.03em] md:text-[42px]">
                        {{ $lesson->title }}
                    </h2>
                </div>

                <div :class="theme === 'dark' ? 'bg-[#0b0d12]' : 'bg-white'" class="overflow-hidden rounded-[22px] border border-black/5 shadow-[0_20px_55px_rgba(0,0,0,0.18)]">
                    <div class="aspect-video w-full bg-black">
                        @if ($lesson->embed_code)
                            <div class="bunny-video-wrapper h-full w-full">
                                {!! $lesson->embed_code !!}
                            </div>
                        @elseif ($lesson->thumbnail_url)
                            <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <div class="text-center text-white/60">
                                    <svg class="mx-auto mb-4 h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <p>Video chưa được thêm</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div :class="theme === 'dark' ? 'border-white/10' : 'border-black/10'" class="mt-6 flex items-center justify-between gap-4 border-t pt-5 text-sm">
                    <div>
                        @if ($prevLesson)
                            <a
                                href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $prevLesson->id]) }}"
                                :class="theme === 'dark' ? 'border-white/10 text-white/80 hover:bg-white/5' : 'border-black/10 text-black/75 hover:bg-black/5'"
                                class="inline-flex h-10 items-center gap-2 rounded-full border px-4 font-medium transition"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Bài trước
                            </a>
                        @endif
                    </div>

                    <div class="hidden items-center gap-2 md:inline-flex">
                        @if (in_array($lesson->id, $completedLessonIds, true))
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#f05123] text-white">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span :class="theme === 'dark' ? 'text-white/70' : 'text-black/70'">Đã hoàn thành</span>
                        @else
                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-[#f05123]/40">
                                <span class="block h-2.5 w-2.5 rounded-full bg-[#f05123]"></span>
                            </span>
                            <span :class="theme === 'dark' ? 'text-white/70' : 'text-black/70'">Đang học</span>
                        @endif
                    </div>

                    <div>
                        @if ($nextLesson)
                            <a
                                href="{{ route('learning.lesson', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                                id="next-lesson-link"
                                class="inline-flex h-10 items-center gap-2 rounded-full bg-[#f05123] px-4 font-semibold text-white transition hover:bg-[#d94217]"
                            >
                                Bài tiếp
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                @if ($lesson->content)
                    <div
                        :class="theme === 'dark'
                            ? 'border-white/10 bg-white/[0.03] prose-invert prose-headings:text-white prose-p:text-white/75 prose-strong:text-white'
                            : 'border-black/10 bg-white prose-headings:text-black prose-p:text-black/75 prose-strong:text-black'"
                        class="prose mt-8 max-w-none rounded-[22px] border p-6 shadow-[0_12px_28px_rgba(15,23,42,0.06)]"
                    >
                        {!! $lesson->content !!}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <div
        x-show="commentsOpen"
        x-transition.opacity.duration.250ms
        @click="commentsOpen = false"
        class="absolute inset-0 z-40 bg-black/35"
        style="display: none;"
    ></div>

    <aside
        x-show="commentsOpen"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-250"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        :class="theme === 'dark' ? 'border-white/10 bg-[#171a20] text-white' : 'border-black/10 bg-white text-black'"
        class="absolute right-0 top-0 z-50 flex h-full w-full max-w-[420px] flex-col border-l shadow-[-20px_0_60px_rgba(0,0,0,0.18)]"
        style="display: none;"
    >
        <div :class="theme === 'dark' ? 'border-white/10' : 'border-black/10'" class="flex items-center justify-between border-b px-5 py-4">
            <div>
                <p :class="theme === 'dark' ? 'text-white/45' : 'text-black/45'" class="text-[11px] uppercase tracking-[0.16em]">Thảo luận</p>
                <h3 class="mt-1 text-[24px] font-bold tracking-[-0.02em]">Cảm nhận học viên</h3>
            </div>
            <button
                type="button"
                @click="commentsOpen = false"
                :class="theme === 'dark' ? 'bg-white/10 hover:bg-white/15' : 'bg-black/5 hover:bg-black/10'"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full transition"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-5">
            <div class="space-y-4">
                @foreach ($seedComments as $comment)
                    <article :class="theme === 'dark' ? 'border-white/10 bg-white/[0.035]' : 'border-black/10 bg-[#f8fafc]'" class="rounded-2xl border p-4">
                        <div class="flex items-start gap-3">
                            <div class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#f05123] text-sm font-semibold text-white">
                                {{ strtoupper(mb_substr($comment['name'], 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold">{{ $comment['name'] }}</h4>
                                    <span :class="theme === 'dark' ? 'text-white/40' : 'text-black/40'" class="text-xs">{{ $comment['time'] }}</span>
                                </div>
                                <p :class="theme === 'dark' ? 'text-white/72' : 'text-black/72'" class="mt-2 text-sm leading-6">
                                    {{ $comment['content'] }}
                                </p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </aside>
</div>

<script>
    (() => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const progressUrl = @json(route('learning.progress', ['course' => $course->slug, 'lesson' => $lesson->id]));
        const nextLink = document.getElementById('next-lesson-link');
        const initialWatchedSeconds = {{ (int) ($currentLessonWatchedSeconds ?? 0) }};
        const lessonAlreadyCompleted = {{ in_array($lesson->id, $completedLessonIds, true) ? 'true' : 'false' }};

        if (!csrfToken || !progressUrl) {
            return;
        }

        let startedAt = Date.now();
        let accumulatedSeconds = initialWatchedSeconds;
        let lastSavedSeconds = initialWatchedSeconds;
        let isSaving = false;
        let timerRunning = true;

        const currentWatchedSeconds = () => {
            const elapsedSeconds = timerRunning ? Math.max(0, Math.floor((Date.now() - startedAt) / 1000)) : 0;
            return accumulatedSeconds + elapsedSeconds;
        };

        const resetTimer = () => {
            accumulatedSeconds = currentWatchedSeconds();
            startedAt = Date.now();
            timerRunning = true;
        };

        const pauseTimer = () => {
            accumulatedSeconds = currentWatchedSeconds();
            timerRunning = false;
        };

        const persistProgress = async ({ markCompleted = false, force = false } = {}) => {
            const watchedSeconds = currentWatchedSeconds();

            if (!force && !markCompleted && watchedSeconds <= lastSavedSeconds) {
                return;
            }

            if (isSaving) {
                return;
            }

            isSaving = true;

            try {
                const response = await fetch(progressUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        watched_seconds: watchedSeconds,
                        mark_completed: markCompleted,
                    }),
                    keepalive: true,
                });

                if (response.ok) {
                    lastSavedSeconds = watchedSeconds;
                    resetTimer();
                }
            } finally {
                isSaving = false;
            }
        };

        const persistOnUnload = (markCompleted = false) => {
            const watchedSeconds = currentWatchedSeconds();

            fetch(progressUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    watched_seconds: watchedSeconds,
                    mark_completed: markCompleted,
                }),
                keepalive: true,
            });
        };

        const autosaveInterval = window.setInterval(() => {
            persistProgress();
        }, 15000);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                pauseTimer();
                persistProgress({ force: true });
            } else {
                resetTimer();
            }
        });

        window.addEventListener('beforeunload', () => {
            pauseTimer();
            persistOnUnload(false);
            window.clearInterval(autosaveInterval);
        });

        if (nextLink) {
            nextLink.addEventListener('click', async (event) => {
                event.preventDefault();
                nextLink.classList.add('pointer-events-none', 'opacity-60');

                try {
                    await persistProgress({
                        markCompleted: !lessonAlreadyCompleted,
                        force: true,
                    });
                } finally {
                    window.location.href = nextLink.href;
                }
            });
        }
    })();
</script>
</body>
</html>
