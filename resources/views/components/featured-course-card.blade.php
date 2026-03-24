@props(['course'])

<article class="course-card group">
    <a href="{{ route('courses.landing', $course->slug) }}" class="block">
        <!-- Thumbnail -->
        <div class="thumbnail relative overflow-hidden" style="aspect-ratio: 16/9;">
            <!-- Brand Logo top left -->
            <div class="absolute top-4 left-4 z-10 w-24">
                <img src="/images/logo.png" alt="Mewart" class="w-full h-auto brightness-0 invert">
            </div>

            <img
                src="{{ $course->thumbnail_url }}"
                alt="{{ $course->title }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                loading="lazy"
            >

            <!-- Status Badges -->
            @if($course->isFree())
                <!-- Free Badge top right -->
                <div class="absolute top-0 right-0 bg-[#d4af37] text-black text-[13px] font-bold px-4 py-1.5 uppercase">
                    Miễn phí
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="info bg-[#0f0f0f] border border-[#2a2a2a] border-t-0 p-5 mt-0 min-h-[170px] md:min-h-[180px] flex flex-col justify-between">
            <!-- Dòng 1: Title & Description -->
            <div class="mb-4">
                <h3 class="text-white font-bold text-[15px] md:text-[17px] uppercase mb-2 line-clamp-2 transition-colors">
                    {{ $course->title }}
                </h3>
                <p class="text-gray-400 text-[13px] line-clamp-2">{{ $course->excerpt ?? 'Những bạn đang phân vân về học trang điểm' }}</p>
            </div>

            <div class="space-y-4 md:space-y-5">
                <!-- Dòng 3: Author - Button Chi tiết -->
                <div class="flex items-center justify-between gap-4 mt-auto">
                    <!-- Author & Lessons -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 min-w-0">
                        @if($course->author)
                            <div class="flex items-center gap-2">
                                <img
                                    src="{{ $course->author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->author->name) . '&background=3b82f6&color=fff' }}"
                                    alt="{{ $course->author->name }}"
                                    class="w-6 h-6 rounded-full object-cover flex-shrink-0"
                                >
                                <span class="text-gray-300 text-[13px] truncate">{{ $course->author->name }}</span>
                            </div>
                        @endif

                        <div class="hidden sm:block w-[1px] h-3 bg-gray-600"></div>

                        <!-- Lessons -->
                        <div class="flex items-center gap-1.5 text-gray-300 text-[13px]">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>{{ $course->total_lessons ?? 7 }} bài</span>
                        </div>
                    </div>

                    <!-- Button Chi tiết -->
                    <button class="flex items-center gap-1.5 text-white transition-colors flex-shrink-0 group/btn">
                        <span class="text-[13px] font-bold uppercase tracking-wider">Chi tiết</span>
                        <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </a>
</article>
