@props([
    'featuredCourses' => collect(),
    'freeCourses' => collect(),
    'latestCourses' => collect(),
])

@php
    $tabs = [
        'featured' => [
            'label' => 'Nổi bật',
            'heading' => 'Khóa học nổi bật',
            'description' => 'Những khóa học được học viên quan tâm nhiều và có lộ trình rõ ràng để bắt đầu nhanh hơn.',
            'courses' => $featuredCourses,
        ],
        'free' => [
            'label' => 'Miễn phí',
            'heading' => 'Khóa học miễn phí',
            'description' => 'Bắt đầu với các khóa học nền tảng để làm quen trước khi đi sâu hơn vào chuyên đề nâng cao.',
            'courses' => $freeCourses,
        ],
        'latest' => [
            'label' => 'Mới nhất',
            'heading' => 'Khóa học mới cập nhật',
            'description' => 'Danh sách khóa học vừa được đưa lên hệ thống để anh dễ theo dõi nội dung mới nhất.',
            'courses' => $latestCourses,
        ],
    ];

    $defaultKey = collect($tabs)
        ->filter(fn ($tab) => $tab['courses']->isNotEmpty())
        ->keys()
        ->first() ?? 'featured';
@endphp

<section class="f8-home-courses bg-white py-14 md:py-20" x-data="{ tab: '{{ $defaultKey }}' }">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="max-w-[720px]">
                <p class="text-[13px] font-semibold uppercase tracking-[0.24em] text-[#f05123]">Khóa học</p>
                <h2 class="mt-3 text-[30px] font-bold leading-[1.12] tracking-[-0.02em] text-[#111827] md:text-[42px]">
                    Học theo lộ trình gọn gàng, giao diện kiểu F8
                </h2>
                <p class="mt-4 max-w-[620px] text-[14px] leading-7 text-[#667085] md:text-[15px]">
                    Phần khóa học trang chủ được gom theo tab để người dùng xem nhanh hơn, thao tác giống các nền tảng học online hiện đại.
                </p>
            </div>

            <a
                href="{{ route('courses.index') }}"
                class="inline-flex h-11 items-center justify-center rounded-full border border-[#e5e7eb] px-5 text-[14px] font-semibold text-[#111827] transition-colors duration-200 hover:border-[#f05123] hover:text-[#f05123]"
            >
                Xem tất cả khóa học
            </a>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            @foreach ($tabs as $key => $tab)
                <button
                    type="button"
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'border-[#111827] bg-[#111827] text-white shadow-[0_12px_24px_rgba(17,24,39,0.16)]'
                        : 'border-[#e5e7eb] bg-white text-[#4b5563] hover:border-[#f05123] hover:text-[#f05123]'"
                    class="inline-flex h-11 items-center justify-center rounded-full border px-5 text-[14px] font-semibold transition-all duration-200"
                >
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        @foreach ($tabs as $key => $tab)
            <div x-show="tab === '{{ $key }}'" x-transition.opacity.duration.250ms class="mt-8">
                <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h3 class="text-[26px] font-bold leading-[1.15] tracking-[-0.02em] text-[#111827] md:text-[32px]">
                            {{ $tab['heading'] }}
                        </h3>
                        <p class="mt-2 text-[14px] leading-7 text-[#667085]">
                            {{ $tab['description'] }}
                        </p>
                    </div>

                    <div class="text-[13px] font-medium text-[#98a2b3]">
                        {{ $tab['courses']->count() }} khóa học
                    </div>
                </div>

                @if ($tab['courses']->isEmpty())
                    <div class="rounded-[24px] border border-[#eaecf0] bg-[#f8fafc] px-6 py-12 text-center">
                        <p class="text-[15px] font-medium text-[#475467]">Hiện chưa có khóa học trong mục này.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($tab['courses'] as $course)
                            <x-f8-course-card :course="$course" :href="route('courses.landing', $course->slug)" compact />
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</section>
