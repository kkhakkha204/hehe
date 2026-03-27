@extends('layouts.app-public')

@section('title', $course->landing_title ?: $course->title)

@section('content')
    @php
        $firstLesson = $course->chapters->pluck('lessons')->flatten()->first();
        $totalLessons = $course->chapters->pluck('lessons')->flatten()->count();
        $hasCustomLanding = filled($course->landing_html);
        $landingHeadStyles = $landingPayload['head_styles'] ?? [];
        $landingHeadScripts = $landingPayload['head_scripts'] ?? [];
        $landingBodyHtml = $landingPayload['body_html'] ?? (string) ($course->landing_html ?? '');

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

    @if ($hasCustomLanding)
        @if (count($landingHeadStyles))
            @push('styles')
                {!! implode("\n", $landingHeadStyles) !!}
            @endpush
        @endif

        @if (count($landingHeadScripts))
            @push('scripts')
                {!! implode("\n", $landingHeadScripts) !!}
            @endpush
        @endif

        @if (filled($course->landing_css))
            <style>{!! $course->landing_css !!}</style>
        @endif

        <div class="custom-landing-wrapper">
            {!! $landingBodyHtml !!}
        </div>

        @if (filled($course->landing_js))
            <script>{!! $course->landing_js !!}</script>
        @endif

        <div class="fixed bottom-4 right-4 z-40 flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('courses.info', $course->slug) }}" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-black shadow-lg">
                Xem thông tin khóa học
            </a>
            <a href="{{ $actionUrl }}" class="rounded-md bg-black px-4 py-2 text-sm font-semibold text-white shadow-lg">
                {{ $actionText }}
            </a>
        </div>
    @else
        <section class="bg-[#0b0b0d] py-12 text-white md:py-16">
            <div class="mx-auto grid max-w-[1180px] grid-cols-1 items-center gap-8 px-4 md:grid-cols-2 md:px-8">
                <div>
                    <p class="mb-2 text-sm uppercase tracking-[0.18em] text-white/55">Landing giới thiệu khóa học</p>
                    <h1 class="heading-font mb-4 text-[34px] uppercase leading-[1.05] md:text-[52px]">{{ $course->title }}</h1>
                    <p class="max-w-[560px] text-[16px] leading-7 text-white/80">
                        {!! strip_tags((string) $course->description) ?: 'Khóa học được thiết kế để học viên biết rõ trước khi mua: học gì, kết quả thế nào, và phù hợp với ai.' !!}
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ $actionUrl }}" class="rounded-md bg-white px-5 py-3 text-sm font-semibold uppercase tracking-wide text-black">
                            {{ $actionText }}
                        </a>
                        <a href="{{ route('courses.info', $course->slug) }}" class="rounded-md border border-white/30 px-5 py-3 text-sm font-semibold uppercase tracking-wide text-white">
                            Thông tin khóa học
                        </a>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-white/10 shadow-[0_25px_70px_rgba(0,0,0,0.38)]">
                    @if ($course->thumbnail_url)
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex aspect-video items-center justify-center bg-white/5 text-white/50">
                            Chưa có ảnh mô tả
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="bg-[#f4f4f4] py-12 md:py-14">
            <div class="mx-auto max-w-[1180px] px-4 md:px-8">
                <h2 class="heading-font mb-6 text-[30px] uppercase text-[#101010] md:text-[36px]">Khóa học này dành cho ai?</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <article class="rounded-xl border border-black/10 bg-white p-5">
                        <h3 class="mb-2 text-[18px] font-semibold text-[#101010]">Người mới bắt đầu</h3>
                        <p class="text-[15px] leading-7 text-[#475569]">Học từ nền tảng, có lộ trình rõ theo từng chương và bài học.</p>
                    </article>
                    <article class="rounded-xl border border-black/10 bg-white p-5">
                        <h3 class="mb-2 text-[18px] font-semibold text-[#101010]">Người tự học cần hệ thống</h3>
                        <p class="text-[15px] leading-7 text-[#475569]">Biết rõ học gì trước, học gì sau, tránh lan man không ra kết quả.</p>
                    </article>
                    <article class="rounded-xl border border-black/10 bg-white p-5">
                        <h3 class="mb-2 text-[18px] font-semibold text-[#101010]">Người cần cải thiện nhanh</h3>
                        <p class="text-[15px] leading-7 text-[#475569]">Đi thẳng vào kỹ thuật áp dụng được ngay, có case thực hành cụ thể.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-white py-12 md:py-14">
            <div class="mx-auto max-w-[1180px] px-4 md:px-8">
                <h2 class="heading-font mb-6 text-[30px] uppercase text-[#101010] md:text-[36px]">Nội dung chính</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach ($course->chapters as $chapter)
                        <article class="rounded-xl border border-black/10 p-5">
                            <h3 class="text-[22px] font-semibold text-[#101010]">{{ $chapter->title }}</h3>
                            <p class="mt-2 text-[15px] text-[#64748b]">{{ $chapter->lessons->count() }} bài học</p>
                            <ul class="mt-3 space-y-2 text-[15px] text-[#334155]">
                                @foreach ($chapter->lessons->take(4) as $lesson)
                                    <li>• {{ $lesson->title }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-[#0f0f12] py-12 text-white md:py-14">
            <div class="mx-auto max-w-[1180px] px-4 md:px-8">
                <h2 class="heading-font mb-6 text-[30px] uppercase md:text-[36px]">Feedback học viên</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <article class="rounded-xl border border-white/15 bg-white/[0.03] p-5">
                        <p class="text-[15px] leading-7 text-white/85">“Xem xong là áp dụng được ngay. Nội dung rõ ràng, không lan man.”</p>
                        <p class="mt-4 text-sm text-white/60">- Học viên Lan Anh</p>
                    </article>
                    <article class="rounded-xl border border-white/15 bg-white/[0.03] p-5">
                        <p class="text-[15px] leading-7 text-white/85">“Mình hiểu rõ điểm mạnh yếu của bản thân và sửa nhanh hơn trước nhiều.”</p>
                        <p class="mt-4 text-sm text-white/60">- Học viên Trúc Vy</p>
                    </article>
                    <article class="rounded-xl border border-white/15 bg-white/[0.03] p-5">
                        <p class="text-[15px] leading-7 text-white/85">“Bố cục bài học logic, xem đến đâu làm được đến đó.”</p>
                        <p class="mt-4 text-sm text-white/60">- Học viên Mai Hương</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-[#e9e9e9] py-12 md:py-14">
            <div class="mx-auto max-w-[1180px] px-4 md:px-8">
                <div class="rounded-2xl bg-black px-6 py-8 text-white md:px-10">
                    <h2 class="heading-font text-[30px] uppercase md:text-[40px]">Sẵn sàng học ngay?</h2>
                    <p class="mt-2 text-[16px] text-white/75">
                        Tổng {{ $totalLessons }} bài học, lộ trình rõ ràng, kết quả nhìn thấy được sau khi học.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ $actionUrl }}" class="rounded-md bg-white px-5 py-3 text-sm font-semibold uppercase tracking-wide text-black">
                            {{ $actionText }}
                        </a>
                        <a href="{{ route('courses.info', $course->slug) }}" class="rounded-md border border-white/35 px-5 py-3 text-sm font-semibold uppercase tracking-wide text-white">
                            Xem chi tiết chương trình
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
