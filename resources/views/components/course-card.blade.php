@props(['course'])

<article class="course-card group">
    <a href="{{ route('courses.show', $course->slug) }}" class="block">
        <!-- Thumbnail -->
        <div class="thumbnail relative overflow-hidden rounded-t-md" style="aspect-ratio: 16/9;">
            <img
                src="{{ $course->thumbnail_url }}"
                alt="{{ $course->title }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                loading="lazy"
            >

            <!-- Free Badge -->
            @if($course->isFree())
                <div class="absolute bottom-0 right-0 bg-[#f4a83d] text-white text-[14px] md:text-[16px] heading-font px-6 py-2 rounded-tl-2xl uppercase">
                    Miễn phí
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="info bg-[#111111] p-4 md:p-6 rounded-b-md min-h-[170px] md:min-h-[200px] flex flex-col justify-between">
            <!-- Dòng 1: Title -->
            <h3 class="text-white font-semibold text-[16px] md:text-lg uppercase mb-4 line-clamp-2 transition-colors">
                {{ $course->title }}
            </h3>

            <div class="space-y-4 md:space-y-6">
                <!-- Dòng 2: Lessons - Views -->
                <div class="flex items-center gap-4 text-white text-sm">
                    <!-- Lessons -->
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="whitespace-nowrap text-sm">{{ $course->total_lessons }}</span>
                    </div>

                    <!-- Views -->
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="whitespace-nowrap text-sm">{{ number_format($course->views ?? 0) }}</span>
                    </div>
                </div>

                <!-- Dòng 3: Author - Button Chi tiết -->
                <div class="flex items-center justify-between gap-4">
                    <!-- Author -->
                    @if($course->author)
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <img
                                src="{{ $course->author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->author->name) . '&background=3b82f6&color=fff' }}"
                                alt="{{ $course->author->name }}"
                                class="w-7 h-7 md:w-9 md:h-9 rounded-full object-cover flex-shrink-0"
                            >
                            <span class="text-white text-[14px] lg:text-[16px] truncate">{{ $course->author->name }}</span>
                        </div>
                    @else
                        <div class="flex-1"></div>
                    @endif

                    <!-- Button Chi tiết (Icon only) -->
                    <button
                        class="flex items-center gap-1.5 text-white transition-colors flex-shrink-0 group/btn"
                        aria-label="Xem chi tiết"
                    >
                        <span class="text-[14px] lg:text-[16px] font-semibold">Chi tiết</span>
                        <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </a>
</article>
