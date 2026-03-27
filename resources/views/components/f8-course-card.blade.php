@props([
    'course',
    'href' => null,
    'showCategory' => true,
    'showAuthor' => true,
    'showLevel' => true,
    'showViews' => true,
    'showLessons' => true,
    'compact' => false,
])

@php
    $cardHref = $href ?: route('courses.show', $course->slug);
    $lessonsCount = $course->relationLoaded('chapters')
        ? $course->chapters->sum(fn ($chapter) => $chapter->relationLoaded('lessons') ? $chapter->lessons->count() : $chapter->lessons()->count())
        : $course->total_lessons;

    $titleClasses = $compact
        ? 'mt-3 line-clamp-2 min-h-[46px] text-[16px] leading-[1.45] md:min-h-[50px]'
        : 'mt-3 line-clamp-2 min-h-[50px] text-[17px] leading-[1.5] md:min-h-[54px]';

    $descriptionClasses = $compact
        ? 'mt-2 line-clamp-2 text-[12px] leading-5'
        : 'mt-2 line-clamp-2 text-[13px] leading-6';

    $bodyPadding = $compact ? 'p-4' : 'p-5';
@endphp

<a
    href="{{ $cardHref }}"
    class="group flex h-full flex-col overflow-hidden rounded-[20px] border border-[#eaecf0] bg-white shadow-[0_8px_24px_rgba(16,24,40,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-[#f05123]/35 hover:shadow-[0_18px_38px_rgba(240,81,35,0.12)]"
>
    <div class="relative aspect-[16/9] overflow-hidden bg-[#f3f4f6]">
        @if ($course->thumbnail_url)
            <img
                src="{{ $course->thumbnail_url }}"
                alt="{{ $course->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]"
                loading="lazy"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-[#98a2b3]">
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif

        <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-4">
            @if ($showCategory)
                <span class="max-w-[68%] truncate rounded-full bg-white/95 px-3 py-1.5 text-[11px] font-semibold text-[#111827] shadow-sm">
                    {{ $course->category?->name ?? 'Khóa học online' }}
                </span>
            @else
                <span></span>
            @endif

            @if ($course->isFree())
                <span class="rounded-full bg-[#e7f8ee] px-3 py-1.5 text-[11px] font-bold text-[#067647]">
                    Miễn phí
                </span>
            @elseif ($course->sale_price && $course->sale_price < $course->price)
                <span class="rounded-full bg-[#fff1eb] px-3 py-1.5 text-[11px] font-bold text-[#f05123]">
                    {{ number_format((float) $course->sale_price, 0, ',', '.') }}đ
                </span>
            @else
                <span class="rounded-full bg-[#fff1eb] px-3 py-1.5 text-[11px] font-bold text-[#f05123]">
                    {{ number_format((float) $course->price, 0, ',', '.') }}đ
                </span>
            @endif
        </div>
    </div>

    <div class="flex flex-1 flex-col {{ $bodyPadding }}">
        @if ($showAuthor)
            <div class="flex items-center gap-2 text-[12px] text-[#667085]">
                @if ($course->author)
                    @if ($course->author->avatar_url)
                        <img src="{{ $course->author->avatar_url }}" alt="{{ $course->author->name }}" class="h-6 w-6 rounded-full object-cover">
                    @else
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#111827] text-[10px] font-bold text-white">
                            {{ strtoupper(mb_substr($course->author->name, 0, 1)) }}
                        </span>
                    @endif
                    <span class="truncate font-medium">{{ $course->author->name }}</span>
                @else
                    <span class="font-medium">Mew Art Academy</span>
                @endif
            </div>
        @endif

        <h3
            title="{{ $course->title }}"
            class="{{ $titleClasses }} font-bold tracking-[-0.01em] text-[#111827] transition-colors duration-200 group-hover:text-[#f05123]"
        >
            {{ $course->title }}
        </h3>

        <p class="{{ $descriptionClasses }} text-[#667085]">
            {{ \Illuminate\Support\Str::limit(strip_tags((string) $course->description), $compact ? 92 : 110) ?: 'Khóa học được thiết kế theo lộ trình dễ theo dõi, bám sát thực hành và tối ưu trải nghiệm học online.' }}
        </p>

        <div class="mt-auto">
            <div class="mt-4 flex flex-wrap items-center gap-4 text-[12px] font-medium text-[#667085]">
                @if ($showLessons)
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        {{ $lessonsCount }} bài
                    </span>
                @endif

                @if ($showViews)
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 12s3.5-6 10.5-6 10.5 6 10.5 6-3.5 6-10.5 6S1.5 12 1.5 12Z" />
                            <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                        </svg>
                        {{ number_format($course->views ?? 0) }}
                    </span>
                @endif
            </div>

            <div class="mt-5 flex min-h-[32px] items-center justify-between border-t border-[#f2f4f7] pt-4">
                <div class="text-[13px] font-semibold text-[#111827]">
                    @if ($showLevel)
                        {{ $course->level ? 'Level '.$course->level : 'Mọi cấp độ' }}
                    @endif
                </div>

                <span class="inline-flex items-center gap-2 text-[13px] font-bold text-[#f05123]">
                    Xem chi tiết
                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                </span>
            </div>
        </div>
    </div>
</a>
