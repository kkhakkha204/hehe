@extends('layouts.course-show')

@section('title', $course->title)

@push('styles')
    <style>
        .course-show-page {
            background: #fff;
            color: #292929;
        }

        .course-show-shell {
            max-width: 1336px;
        }

        .course-main-title {
            font-size: clamp(2rem, 4vw, 3.35rem);
            line-height: 1.14;
            letter-spacing: -0.04em;
        }

        .course-main-desc {
            max-width: 760px;
            color: #4f5665;
            font-size: 0.95rem;
            line-height: 1.75;
        }

        .course-section-title {
            color: #000;
            font-size: clamp(1.45rem, 2.6vw, 1.8rem);
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .course-topic-list li {
            position: relative;
            padding-left: 1.55rem;
            color: #292929;
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .course-topic-list li::before {
            content: "✓";
            position: absolute;
            left: 0;
            top: 0.05rem;
            color: #f05123;
            font-weight: 700;
        }

        .course-purchase-card {
            position: sticky;
            top: 108px;
        }

        .course-preview {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(135deg, #1d9bf0 0%, #1736d1 100%);
            box-shadow: 0 18px 42px rgba(17, 24, 39, 0.12);
        }

        .course-preview::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.08) 0%, rgba(0, 0, 0, 0.38) 100%);
        }

        .course-preview-play,
        .course-preview-caption {
            position: absolute;
            z-index: 2;
        }

        .course-preview-play {
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .course-preview-play span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.94);
            color: #f05123;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.24);
        }

        .course-preview-caption {
            left: 1rem;
            right: 1rem;
            bottom: 1rem;
            text-align: center;
            color: #fff;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .course-price {
            color: #f05123;
            font-size: clamp(2rem, 4vw, 2.4rem);
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .course-register-btn {
            height: 40px;
            border-radius: 999px;
            background: linear-gradient(180deg, #3299ff 0%, #1677ff 100%);
            color: #fff;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            box-shadow: 0 10px 24px rgba(22, 119, 255, 0.22);
        }

        .course-curriculum-row {
            overflow: hidden;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            background: #f5f5f5;
        }

        .course-lesson-row {
            border-top: 1px solid #ececec;
            background: #fff;
        }

        .course-curriculum-meta {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .course-chapter-title {
            font-size: 1rem;
            line-height: 1.45;
        }

        .course-lesson-title {
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .course-lesson-subtitle {
            font-size: 0.75rem;
            line-height: 1.45;
        }

        .course-richtext {
            max-width: 860px;
            color: #4f5665;
            font-size: 0.95rem;
            line-height: 1.85;
        }

        .course-richtext h2,
        .course-richtext h3,
        .course-richtext h4 {
            margin-top: 1.25rem;
            margin-bottom: 0.65rem;
            color: #111827;
            font-weight: 700;
        }

        .course-richtext ul,
        .course-richtext ol {
            margin: 0.9rem 0;
            padding-left: 1.2rem;
        }

        .course-richtext ul {
            list-style: disc;
        }

        .course-richtext ol {
            list-style: decimal;
        }

        .course-richtext p + p {
            margin-top: 0.85rem;
        }

        [x-cloak] {
            display: none !important;
        }

        @media (max-width: 1024px) {
            .course-purchase-card {
                position: static;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $allLessons = $course->chapters->pluck('lessons')->flatten();
        $firstLesson = $allLessons->first();
        $previewLesson = $allLessons->firstWhere('is_preview', true);
        $previewImage = $course->thumbnail_url ?: asset('storage/nen.webp');

        $formatDuration = function (?int $minutes): string {
            $minutes = max((int) $minutes, 0);

            if ($minutes === 0) {
                return 'Đang cập nhật';
            }

            $hours = intdiv($minutes, 60);
            $remainingMinutes = $minutes % 60;

            if ($hours > 0 && $remainingMinutes > 0) {
                return $hours . ' giờ ' . $remainingMinutes . ' phút';
            }

            if ($hours > 0) {
                return $hours . ' giờ';
            }

            return $remainingMinutes . ' phút';
        };

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
                $actionText = 'Đăng ký học';
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
                $actionText = 'Đăng ký học';
            }
        }

        $previewUrl = $previewLesson
            ? ($isEnrolled
                ? route('learning.lesson', ['course' => $course->slug, 'lesson' => $previewLesson->id])
                : (auth()->check() ? route('pre-checkout.show', $course->slug) : route('login')))
            : $actionUrl;

        $learningOutcomes = [
            'Biết cách đi theo lộ trình học rõ ràng qua ' . $course->chapters->count() . ' chương và ' . $course->total_lessons . ' bài học.',
            'Nắm được phần kiến thức trọng tâm để áp dụng nhanh hơn vào thực hành và công việc.',
            'Có thể học lại không giới hạn trên điện thoại hoặc máy tính vào bất kỳ thời điểm nào.',
            'Dễ dàng quay lại bài học đang xem dở nhờ tính năng lưu tiến độ và học tiếp.',
        ];

        if ($course->level) {
            $learningOutcomes[] = 'Nội dung được sắp xếp theo Level ' . $course->level . ' để học viên dễ bắt nhịp hơn.';
        }

        $courseBenefits = [
            $course->level ? 'Trình độ Level ' . $course->level : 'Trình độ cơ bản',
            'Tổng số ' . $course->total_lessons . ' bài học',
            'Thời lượng ' . $formatDuration($course->duration),
            'Học mọi lúc, mọi nơi',
        ];

        $courseSubtitle = trim(strip_tags((string) $course->description));
        $courseSubtitle = $courseSubtitle !== ''
            ? \Illuminate\Support\Str::limit($courseSubtitle, 180)
            : 'Trong khóa học này bạn sẽ được hướng dẫn theo một lộ trình rõ ràng, bám sát thực hành và tối ưu trải nghiệm học online.';

    @endphp

    <div class="course-show-page min-h-screen">
        <section class="course-show-shell mx-auto px-4 pb-16 pt-8 sm:px-6 lg:px-8 lg:pt-10">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_360px]">
                <div class="min-w-0 space-y-12">
                    <section>
                        <h1 class="course-main-title font-bold text-[#111827]">{{ $course->title }}</h1>
                        <p class="course-main-desc mt-5">{{ $courseSubtitle }}</p>
                    </section>

                    <section>
                        <h2 class="course-section-title">Bạn sẽ học được gì?</h2>
                        <ul class="course-topic-list mt-5 grid gap-x-10 gap-y-4 md:grid-cols-2">
                            @foreach ($learningOutcomes as $outcome)
                                <li>{{ $outcome }}</li>
                            @endforeach
                        </ul>
                    </section>

                    <section x-data="{ expandAll: false }">
                        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                            <div>
                                <h2 class="course-section-title">Nội dung khóa học</h2>
                                <p class="course-curriculum-meta mt-1.5">
                                    {{ $course->chapters->count() }} chương
                                    <span class="mx-1 text-[#9ca3af]">•</span>
                                    {{ $course->total_lessons }} bài học
                                    <span class="mx-1 text-[#9ca3af]">•</span>
                                    Thời lượng {{ $formatDuration($course->duration) }}
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="expandAll = !expandAll"
                                class="text-left text-[13px] font-medium text-[#f05123]"
                                x-text="expandAll ? 'Thu gọn tất cả' : 'Mở rộng tất cả'"
                            ></button>
                        </div>

                        <div class="mt-4 space-y-2">
                            @php $lessonNumber = 0; @endphp

                            @forelse ($course->chapters as $chapter)
                                @php
                                    $chapterDuration = $chapter->lessons->sum('duration');
                                @endphp
                                <section x-data="{ open: false }" x-effect="open = expandAll" class="course-curriculum-row">
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        class="flex min-h-[42px] w-full items-center justify-between gap-4 px-3.5 py-2.5 text-left"
                                    >
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span class="w-[16px] shrink-0 text-[1.1rem] font-normal leading-none text-[#f05123]" x-text="open ? '−' : '+'"></span>
                                            <div class="min-w-0">
                                                <h3 class="course-chapter-title font-semibold text-[#111827]">{{ $chapter->title }}</h3>
                                                @if ($chapterDuration > 0)
                                                    <p class="mt-0.5 text-[11px] text-[#6b7280]">{{ $formatDuration($chapterDuration) }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <span class="shrink-0 text-[12px] text-[#292929]">{{ $chapter->lessons->count() }} bài học</span>
                                    </button>

                                    <div x-show="open" x-transition.opacity.duration.200ms class="bg-white" x-cloak>
                                        @foreach ($chapter->lessons as $lesson)
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

                                            <a href="{{ $lessonUrl }}" class="course-lesson-row flex items-center gap-3 px-3.5 py-2.5 text-[14px] transition hover:bg-[#fff8f5]">
                                                <span class="w-5 shrink-0 text-center text-[12px] font-semibold text-[#9ca3af]">{{ $lessonNumber }}</span>

                                                <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#fff1eb] text-[#f05123]">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </span>

                                                <div class="min-w-0 flex-1">
                                                    <p class="course-lesson-title truncate font-medium text-[#111827]">{{ $lesson->title }}</p>
                                                    <p class="course-lesson-subtitle mt-0.5 text-[#6b7280]">
                                                        {{ $lesson->is_preview ? 'Bài học xem trước' : 'Video bài học' }}
                                                        @if ($lesson->duration)
                                                            <span class="mx-1">•</span>
                                                            {{ $formatDuration($lesson->duration) }}
                                                        @endif
                                                    </p>
                                                </div>

                                                @if ($lesson->is_preview)
                                                    <span class="rounded-full bg-[#111827] px-2.5 py-1 text-[10px] font-bold text-white">Preview</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </section>
                            @empty
                                <div class="rounded-[12px] bg-[#f5f5f5] px-5 py-8 text-[14px] text-[#6b7280]">Chưa có nội dung giáo trình.</div>
                            @endforelse
                        </div>
                    </section>

                    <section>
                        <h2 class="course-section-title">Mô tả chi tiết</h2>
                        <div class="course-richtext mt-5">
                            {!! $course->description !!}
                        </div>
                    </section>

                    @if ($relatedCourses->isNotEmpty())
                        <section class="border-t border-[#edf0f2] pt-10">
                            <div class="mb-5 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[#f05123]">Gợi ý thêm</p>
                                    <h2 class="mt-2 text-[28px] font-bold tracking-[-0.03em] text-[#111827]">Khóa học liên quan</h2>
                                </div>
                                <a href="{{ route('courses.index') }}" class="text-[14px] font-semibold text-[#f05123]">Xem tất cả</a>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($relatedCourses as $relatedCourse)
                                    <x-f8-course-card :course="$relatedCourse" compact />
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <aside>
                    <div class="course-purchase-card space-y-5">
                        <div class="rounded-[22px] border border-[#e8ebed] bg-white p-4 shadow-[0_10px_30px_rgba(15,23,42,0.05)] md:p-5">
                            <a href="{{ $previewUrl }}" class="course-preview block">
                                <div class="aspect-[16/9] w-full">
                                    <img src="{{ $previewImage }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                </div>

                                <div class="course-preview-play">
                                    <span>
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5.14v13.72a1 1 0 001.5.86l10.75-6.86a1 1 0 000-1.72L9.5 4.28A1 1 0 008 5.14z"/>
                                        </svg>
                                    </span>
                                </div>

                                <div class="course-preview-caption">Xem giới thiệu khóa học</div>
                            </a>

                            <div class="pt-4 text-center">
                                @if ($isEnrolled)
                                    <p class="mb-2 inline-flex rounded-full bg-[#eef7ff] px-3 py-1 text-[12px] font-semibold text-[#1677ff]">
                                        Đã đăng ký • {{ $progressPercent }}% hoàn thành
                                    </p>
                                @endif

                                @if ($course->isFree())
                                    <p class="course-price font-semibold">Miễn phí</p>
                                @elseif ($course->sale_price && $course->sale_price < $course->price)
                                    <p class="course-price font-semibold">{{ number_format((float) $course->sale_price, 0, ',', '.') }}đ</p>
                                    <p class="mt-2 text-[14px] font-medium text-[#9ca3af] line-through">{{ number_format((float) $course->price, 0, ',', '.') }}đ</p>
                                @else
                                    <p class="course-price font-semibold">{{ number_format((float) $course->price, 0, ',', '.') }}đ</p>
                                @endif

                                <a href="{{ $actionUrl }}" class="course-register-btn mt-4 inline-flex w-full items-center justify-center px-5">
                                    {{ $actionText }}
                                </a>
                            </div>

                            <ul class="mt-6 space-y-3">
                                @foreach ($courseBenefits as $benefit)
                                    <li class="relative pl-7 text-[0.93rem] leading-[1.65] text-[#4f5665]">
                                        <svg class="absolute left-0 top-[0.28rem] h-4 w-4 text-[#4f5665]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                        </svg>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="rounded-[22px] border border-[#e8ebed] bg-white p-5 shadow-[0_10px_30px_rgba(15,23,42,0.04)]">
                            <h3 class="text-[20px] font-bold text-[#111827]">Thông tin khóa học</h3>

                            <div class="mt-4 space-y-3 text-[14px]">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[#6b7280]">Bài giảng</span>
                                    <strong class="text-[#111827]">{{ $course->total_lessons }}</strong>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[#6b7280]">Chương học</span>
                                    <strong class="text-[#111827]">{{ $course->chapters->count() }}</strong>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[#6b7280]">Trình độ</span>
                                    <strong class="text-[#111827]">{{ $course->level ? 'Level '.$course->level : 'Mọi cấp độ' }}</strong>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[#6b7280]">Thời lượng</span>
                                    <strong class="text-[#111827]">{{ $formatDuration($course->duration) }}</strong>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[#6b7280]">Học viên</span>
                                    <strong class="text-[#111827]">{{ number_format($course->current_students ?? 0) }}</strong>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[#6b7280]">Lượt xem</span>
                                    <strong class="text-[#111827]">{{ number_format($course->views ?? 0) }}</strong>
                                </div>
                            </div>

                            @if ($previewLesson)
                                <div class="mt-5 border-t border-[#edf0f2] pt-5">
                                    <a href="{{ $previewUrl }}" class="inline-flex items-center gap-2 text-[14px] font-semibold text-[#f05123]">
                                        Xem bài học xem trước
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </div>
@endsection
