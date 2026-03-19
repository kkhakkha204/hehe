@extends('layouts.app-public')

@section('title', $course->title)

@section('content')
    <section class="bg-black text-white py-10 md:py-16">
        <div class="max-w-[1120px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="order-2 lg:order-1">
                    <h1 class="heading-font text-[32px] md:text-[40px] leading-[1.05] mb-10 max-w-[520px]">{{ $course->title }}</h1>

                    <div class="flex items-center gap-3">
                        @if($course->author->avatar_url)
                            <img src="{{ $course->author->avatar_url }}" alt="{{ $course->author->name }}" class="w-11 h-11 rounded-full object-cover">
                        @else
                            <div class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($course->author->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="text-white leading-tight">
                            <p class="text-[15px]">Hướng dẫn</p>
                            <p class="text-[22px] heading-font">{{ $course->author->name }}</p>
                        </div>

                        @if(($course->current_students ?? 0) > 0)
                            <div class="h-14 w-px bg-white/45 ml-2"></div>
                            <div class="text-white leading-tight">
                                <p class="text-[22px] heading-font">{{ number_format($course->current_students) }}</p>
                                <p class="text-[15px]">Học viên đã đăng ký</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="order-1 lg:order-2 lg:justify-self-end w-full">
                    <div class="aspect-[16/9] w-full max-w-[560px] overflow-hidden">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-white/10 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="max-w-[1120px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <div class="lg:col-span-7">
                    @php
                        $lessonNumber = 0;
                    @endphp

                    <div class="border-b border-[#c7ccd2] mb-5 flex items-center gap-5">
                        <button id="tab-overview" class="tab-button py-3 text-[16px] heading-font text-[#64748b]" onclick="switchTab('overview', this)">
                            Tổng Quan Khóa Học
                        </button>
                        <button id="tab-curriculum" class="tab-button py-3 text-[16px] heading-font text-black border-b-2 border-black" onclick="switchTab('curriculum', this)">
                            Giáo Trình
                        </button>
                    </div>

                    <div id="content-curriculum" class="tab-content space-y-4">
                        @forelse($course->chapters as $chapter)
                            <div>
                                <div class="flex items-center gap-2 text-[26px] heading-font text-[#121827] mb-2">
                                    <h3>{{ $chapter->title }}</h3>
                                    <span class="w-6 h-6 rounded-full bg-[#cfd4da] inline-flex items-center justify-center text-[12px]">▲</span>
                                </div>

                                <div class="bg-[#f3f4f6] border border-[#dadde1] rounded-lg px-4 py-3 space-y-3">
                                    @foreach($chapter->lessons as $lesson)
                                        @php
                                            $lessonNumber++;
                                        @endphp
                                        <div class="flex items-center gap-3 rounded-md bg-white border border-[#e3e5e8] px-3 py-2">
                                            <span class="text-[#6b7280] text-sm w-4 text-center">{{ $lessonNumber }}</span>

                                            <div class="w-[70px] h-[40px] bg-[#e5e7eb] rounded"></div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-[17px] heading-font text-[#0f172a] truncate">{{ $lesson->title }}</p>
                                            </div>

                                            <div class="flex items-center gap-3 shrink-0">
                                                @if($lesson->is_preview)
                                                    <span class="inline-flex items-center h-6 px-2 rounded bg-black text-white text-[11px] font-semibold uppercase">Xem trước</span>
                                                @endif
                                                <span class="text-[14px] text-[#475569] heading-font">Video bài học</span>
                                                <svg class="w-4 h-4 text-[#475569]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-[#dadde1] rounded-lg px-5 py-6 text-[#64748b]">
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
                            $actionUrl = auth()->check() ? route('learning.show', $course->slug) : route('login');
                            $actionText = auth()->check() ? 'Vào học ngay' : 'Đăng nhập để học';
                        } else {
                            if (!auth()->check()) {
                                $actionUrl = route('login');
                                $actionText = 'Đăng nhập để mua';
                            } elseif($isEnrolled) {
                                $actionUrl = route('learning.show', $course->slug);
                                $actionText = 'Vào học ngay';
                            } else {
                                $actionUrl = route('pre-checkout.show', $course->slug);
                                $actionText = 'Mua ngay';
                            }
                        }
                    @endphp

                    <div class="sticky top-24 space-y-7">
                        <a href="{{ $actionUrl }}" class="grid grid-cols-[1fr_auto] bg-black text-white rounded-sm overflow-hidden">
                            <span class="px-4 py-3 heading-font text-[18px] uppercase tracking-wide border-r border-white/10">{{ $actionText }}</span>
                            <span class="px-4 py-3 text-right min-w-[130px]">
                                @if($course->isFree())
                                    <span class="heading-font text-[20px] leading-none">Miễn phí</span>
                                @elseif($course->sale_price && $course->sale_price < $course->price)
                                    <span class="block heading-font text-[20px] leading-none">{{ number_format($course->sale_price) }}đ</span>
                                    <span class="block heading-font text-[15px] text-white/70 line-through leading-none mt-1">{{ number_format($course->price) }}đ</span>
                                @else
                                    <span class="heading-font text-[20px] leading-none">{{ number_format($course->price) }}đ</span>
                                @endif
                            </span>
                        </a>

                        <div>
                            <h3 class="text-[30px] heading-font text-[#121827] mb-3">Thông tin khóa học</h3>
                            <div class="divide-y divide-[#c7ccd2] border-y border-[#c7ccd2]">
                                <div class="flex items-center justify-between py-3 gap-3">
                                    <span class="inline-flex items-center gap-2 text-[16px] heading-font text-[#334155]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        Bài giảng
                                    </span>
                                    <span class="text-[18px] heading-font text-[#0f172a]">{{ $course->total_lessons }}</span>
                                </div>

                                <div class="flex items-center justify-between py-3 gap-3">
                                    <span class="inline-flex items-center gap-2 text-[16px] heading-font text-[#334155]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Phân loại
                                    </span>
                                    <span class="text-[18px] heading-font text-[#0f172a]">Level {{ $course->level ?? 1 }}</span>
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
