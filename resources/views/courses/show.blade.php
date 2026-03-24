@extends('layouts.app-public')

@section('title', $course->title)

@section('content')
    <section class="bg-black py-10 text-white md:py-16">
        <div class="mx-auto max-w-[1120px] px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
                <div class="order-2 lg:order-1">
                    <h1 class="heading-font mb-10 max-w-[520px] text-[32px] leading-[1.05] md:text-[40px]">{{ $course->title }}</h1>
                    <a href="{{ route('courses.landing', $course->slug) }}" class="mb-8 inline-flex items-center rounded-md border border-white/30 px-4 py-2 text-[13px] font-semibold uppercase tracking-wide text-white hover:bg-white/10">
                        Xem landing page
                    </a>

                    <div class="flex items-center gap-3">
                        @if($course->author->avatar_url)
                            <img src="{{ $course->author->avatar_url }}" alt="{{ $course->author->name }}" class="h-11 w-11 rounded-full object-cover">
                        @else
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white">
                                {{ strtoupper(substr($course->author->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="leading-tight text-white">
                            <p class="text-[15px]">Hướng dẫn</p>
                            <p class="heading-font text-[22px]">{{ $course->author->name }}</p>
                        </div>

                        @if(($course->current_students ?? 0) > 0)
                            <div class="ml-2 h-14 w-px bg-white/45"></div>
                            <div class="leading-tight text-white">
                                <p class="heading-font text-[22px]">{{ number_format($course->current_students) }}</p>
                                <p class="text-[15px]">Học viên đã đăng ký</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="order-1 w-full lg:order-2 lg:justify-self-end">
                    <div class="aspect-[16/9] w-full max-w-[560px] overflow-hidden">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-white/10">
                                <svg class="h-16 w-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#e7e7e7] py-10 md:py-14">
        <div class="mx-auto max-w-[1120px] px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-10">
                <div class="lg:col-span-7">
                    @php
                        $lessonNumber = 0;
                        $firstLesson = $course->chapters->pluck('lessons')->flatten()->first();
                    @endphp

                    <div class="mb-5 flex items-center gap-5 border-b border-[#c7ccd2]">
                        <button id="tab-overview" class="tab-button py-3 text-[16px] text-[#64748b] heading-font" onclick="switchTab('overview', this)">
                            Tổng Quan Khóa Học
                        </button>
                        <button id="tab-curriculum" class="tab-button border-b-2 border-black py-3 text-[16px] text-black heading-font" onclick="switchTab('curriculum', this)">
                            Giáo Trình
                        </button>
                    </div>

                    <div id="content-curriculum" class="tab-content space-y-4">
                        @forelse($course->chapters as $chapter)
                            <div
                                x-data="{ open: true }"
                                x-init="$nextTick(() => { $refs.panel.style.maxHeight = $refs.panel.scrollHeight + 'px'; $refs.panel.style.opacity = '1'; })"
                            >
                                <button
                                    type="button"
                                    @click="
                                        open = !open;
                                        if (open) {
                                            $refs.panel.style.maxHeight = $refs.panel.scrollHeight + 'px';
                                            $refs.panel.style.opacity = '1';
                                        } else {
                                            $refs.panel.style.maxHeight = '0px';
                                            $refs.panel.style.opacity = '0';
                                        }
                                    "
                                    class="mb-2 flex items-center gap-2 text-left text-[26px] text-[#121827] heading-font"
                                >
                                    <h3>{{ $chapter->title }}</h3>
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#cfd4da] text-[12px] transition-transform duration-300" :class="open ? 'rotate-0' : 'rotate-180'">▲</span>
                                </button>

                                <div
                                    x-ref="panel"
                                    class="overflow-hidden rounded-lg border border-[#dadde1] bg-[#f3f4f6] transition-all duration-300 ease-out"
                                    style="max-height: 0; opacity: 0;"
                                >
                                    <div class="space-y-3 px-4 py-3">
                                        @foreach($chapter->lessons as $lesson)
                                            @php
                                                $lessonNumber++;
                                                if ($isEnrolled) {
                                                    $lessonUrl = route('learning.lesson', ['course' => $course->slug, 'lesson' => $lesson->id]);
                                                } elseif (auth()->check()) {
                                                    $lessonUrl = route('pre-checkout.show', $course->slug);
                                                } else {
                                                    $lessonUrl = route('login');
                                                }
                                            @endphp
                                            <a href="{{ $lessonUrl }}" class="flex items-center gap-3 rounded-md border border-[#e3e5e8] bg-white px-3 py-2 transition hover:border-black/20 hover:shadow-sm">
                                                <span class="w-4 text-center text-sm text-[#6b7280]">{{ $lessonNumber }}</span>

                                                <div class="h-[40px] w-[70px] shrink-0 overflow-hidden rounded bg-[#e5e7eb]">
                                                    @if($lesson->thumbnail_url)
                                                        <img src="{{ $lesson->thumbnail_url }}" alt="{{ $lesson->title }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center bg-[#d7dbe0]">
                                                            <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-[17px] text-[#0f172a] heading-font">{{ $lesson->title }}</p>
                                                </div>

                                                <div class="flex shrink-0 items-center gap-3">
                                                    @if($lesson->is_preview)
                                                        <span class="inline-flex h-6 items-center rounded bg-black px-2 text-[11px] font-semibold uppercase text-white">Xem trước</span>
                                                    @endif
                                                    <span class="text-[14px] text-[#475569] heading-font">Video bài học</span>
                                                    
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-[#dadde1] bg-white px-5 py-6 text-[#64748b]">
                                Chưa có nội dung giáo trình.
                            </div>
                        @endforelse
                    </div>

                    <div id="content-overview" class="tab-content hidden opacity-0">
                        <div class="prose max-w-none prose-headings:text-black prose-p:text-[#374151]">
                            {!! $course->description !!}
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    @php
                        if ($course->isFree()) {
                            if (! auth()->check()) {
                                $actionUrl = route('login');
                                $actionText = 'Đăng nhập để học';
                            } elseif ($isEnrolled && $resumeLesson) {
                                $actionUrl = route('learning.lesson', ['course' => $course->slug, 'lesson' => $resumeLesson->id]);
                                $actionText = 'Tiếp tục học';
                            } elseif ($isEnrolled && $firstLesson) {
                                $actionUrl = route('learning.lesson', ['course' => $course->slug, 'lesson' => $firstLesson->id]);
                                $actionText = 'Vào học ngay';
                            } else {
                                $actionUrl = route('pre-checkout.show', $course->slug);
                                $actionText = 'Vào học ngay';
                            }
                        } else {
                            if (! auth()->check()) {
                                $actionUrl = route('login');
                                $actionText = 'Đăng nhập để mua';
                            } elseif ($isEnrolled && $resumeLesson) {
                                $actionUrl = route('learning.lesson', ['course' => $course->slug, 'lesson' => $resumeLesson->id]);
                                $actionText = 'Tiếp tục học';
                            } elseif ($isEnrolled && $firstLesson) {
                                $actionUrl = route('learning.lesson', ['course' => $course->slug, 'lesson' => $firstLesson->id]);
                                $actionText = 'Vào học ngay';
                            } else {
                                $actionUrl = route('pre-checkout.show', $course->slug);
                                $actionText = 'Mua ngay';
                            }
                        }
                    @endphp

                    <div class="sticky top-24 space-y-7">
                        @if($isEnrolled)
                            <div class="rounded-[2rem] bg-[#d9d9d9] px-8 py-6">
                                <div class="mb-3 text-right text-[16px] text-[#334155]">
                                    Tiến độ <span class="font-semibold text-[#0f172a]">{{ $progressPercent }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-white/80">
                                    <div class="h-full rounded-full bg-black transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                                </div>
                            </div>
                        @endif

                        <a href="{{ $actionUrl }}" class="grid grid-cols-[1fr_auto] overflow-hidden rounded-sm bg-black text-white">
                            <span class="heading-font border-r border-white/10 px-4 py-3 text-[18px] uppercase tracking-wide">{{ $actionText }}</span>
                            <span class="min-w-[130px] px-4 py-3 text-right">
                                @if($course->isFree())
                                    <span class="heading-font text-[20px] leading-none">Miễn phí</span>
                                @elseif($course->sale_price && $course->sale_price < $course->price)
                                    <span class="block heading-font text-[20px] leading-none">{{ number_format($course->sale_price) }}đ</span>
                                    <span class="mt-1 block heading-font text-[15px] leading-none text-white/70 line-through">{{ number_format($course->price) }}đ</span>
                                @else
                                    <span class="heading-font text-[20px] leading-none">{{ number_format($course->price) }}đ</span>
                                @endif
                            </span>
                        </a>

                        <div>
                            <h3 class="mb-3 text-[30px] text-[#121827] heading-font">Thông tin khóa học</h3>
                            <div class="divide-y divide-[#c7ccd2] border-y border-[#c7ccd2]">
                                <div class="flex items-center justify-between gap-3 py-3">
                                    <span class="inline-flex items-center gap-2 text-[16px] text-[#334155] heading-font">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        Bài giảng
                                    </span>
                                    <span class="heading-font text-[18px] text-[#0f172a]">{{ $course->total_lessons }}</span>
                                </div>

                                <div class="flex items-center justify-between gap-3 py-3">
                                    <span class="inline-flex items-center gap-2 text-[16px] text-[#334155] heading-font">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Phân loại
                                    </span>
                                    <span class="heading-font text-[18px] text-[#0f172a]">Level {{ $course->level ?? 1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function switchTab(tabName, button) {
            const allContents = document.querySelectorAll('.tab-content');
            const allButtons = document.querySelectorAll('.tab-button');
            const targetContent = document.getElementById('content-' + tabName);
            const currentContent = document.querySelector('.tab-content:not(.hidden)');

            if (currentContent === targetContent) {
                return;
            }

            gsap.to(currentContent, {
                duration: 0.15,
                opacity: 0,
                y: -6,
                ease: 'power1.in',
                onComplete: function () {
                    allContents.forEach(content => {
                        content.classList.add('hidden');
                        gsap.set(content, { opacity: 0, y: -6 });
                    });

                    targetContent.classList.remove('hidden');

                    gsap.to(targetContent, {
                        duration: 0.2,
                        opacity: 1,
                        y: 0,
                        ease: 'power1.out'
                    });
                }
            });

            allButtons.forEach(btn => {
                btn.classList.remove('text-black', 'border-b-2', 'border-black');
                btn.classList.add('text-[#64748b]');
            });

            button.classList.remove('text-[#64748b]');
            button.classList.add('text-black', 'border-b-2', 'border-black');
        }
    </script>
@endpush
