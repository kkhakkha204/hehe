<div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-total="{{ $courses->total() }}">
    @forelse($courses as $course)
        <a href="{{ route('courses.landing', $course->slug) }}" class="group block">
            <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                <!-- Thumbnail Card -->
                <div class="h-[220px] sm:h-[240px] md:h-[260px] lg:h-[280px] bg-gray-100 overflow-hidden relative">
                    @if($course->thumbnail_url)
                        <img
                            src="{{ $course->thumbnail_url }}"
                            alt="{{ $course->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Price Badge (Bottom Right) -->
                    <div class="absolute bottom-0 right-0">
                        @if($course->isFree())
                            <div class="bg-green-600 text-white text-[14px] md:text-[16px] heading-font rounded-tl-2xl px-6 py-2 ">
                                Miễn phí
                            </div>
                        @elseif($course->sale_price && $course->sale_price < $course->price)
                            <div class="bg-black text-white px-6 py-2 rounded-tl-2xl shadow-lg">
                                <div class="text-xs heading-font text-end line-through opacity-70">{{ number_format($course->price) }}₫</div>
                                <div class="text-[14px] md:text-[16px] heading-font">{{ number_format($course->sale_price) }}₫</div>
                            </div>
                        @else
                            <div class="bg-black text-white text-[14px] md:text-[16px] heading-font px-6 py-2 rounded-tl-2xl shadow-lg">
                                {{ number_format($course->price) }}₫
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Card -->
                <div class="p-3 md:p-5 bg-gray-50 min-h-[150px] flex flex-col justify-between">
                    <!-- Mục 1: Author & Title -->
                    <div>
                        <!-- Author -->
                        <div class="flex items-center space-x-2 mb-2">
                            @if($course->author->avatar_url)
                                <img src="{{ $course->author->avatar_url }}" alt="{{ $course->author->name }}" class="w-6 h-6 md:w-8 md:h-8 rounded-full">
                            @else
                                <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ strtoupper(substr($course->author->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-[12px] md:text-[14px] text-black">{{ $course->author->name }}</span>
                        </div>

                        <!-- Title -->
                        <h3 title="{{ $course->title }}" class="truncate whitespace-nowrap text-lg font-bold uppercase text-black group-hover:text-gray-600 transition">
                            {{ $course->title }}
                        </h3>
                    </div>

                    <!-- Mục 2: Stats -->
                    <div class="flex items-center justify-end text-sm heading-font text-black gap-4 mt-3">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            {{ $course->lessons_count ?? 0 }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ number_format($course->views ?? 0) }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500">Chưa có khóa học nào.</p>
        </div>
    @endforelse
</div>
