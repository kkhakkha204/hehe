@extends('layouts.app-public')

@section('content')
    <!-- Section 1: Hero Section - Nền đen -->
    <section class="bg-black text-white py-12 md:py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <!-- Cột A: Thông tin khóa học - Desktop: bên trái, Mobile: dưới -->
                <div class="space-y-4 md:space-y-12 order-2 lg:order-1">
                    <!-- Dòng 1: Tiêu đề -->
                    <h1 class="text-2xl md:text-4xl heading-font font-bold">{{ $course->title }}</h1>
                    <div>
                    <!-- Giá khóa học -->
                    <div class="flex items-center gap-3 mb-4">
                        @if($course->sale_price && $course->sale_price < $course->price)
                            <!-- Có giá sale -->
                            <div class="flex items-baseline gap-2">
                            <span class="text-2xl md:text-4xl font-bold heading-font text-white">
                                {{ number_format($course->sale_price) }}đ
                            </span>
                                <span class="text-md md:text-xl heading-font text-gray-300 line-through">
                                {{ number_format($course->price) }}đ
                            </span>
                                <span class="bg-[#f4a83d] text-white text-md md:text-xl heading-font px-2 py-1.5 rounded">
                                - {{ round((($course->price - $course->sale_price) / $course->price) * 100) }}%
                            </span>
                            </div>
                        @else
                            <!-- Không có giá sale -->
                            @if($course->isFree())
                                <span class="text-3xl font-bold text-green-400">Miễn phí</span>
                            @else
                                <span class="text-3xl font-bold text-green-400">
                                {{ number_format($course->price) }}đ
                            </span>
                            @endif
                        @endif
                    </div>

                    <!-- Dòng 2: Author và Học viên -->
                    <div class="flex items-center gap-4 text-gray-100">
                        <!-- Author -->
                        <div class="flex items-center gap-2">
                            @if($course->author->avatar_url)
                                <img src="{{ $course->author->avatar_url }}"
                                     alt="{{ $course->author->name }}"
                                     class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-sm font-medium">{{ substr($course->author->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="font-medium">{{ $course->author->name }}</span>
                        </div>

                        <!-- Vertical Divider -->
                        <div class="h-6 w-px bg-gray-100"></div>

                        <!-- Số học viên -->
                        <div class="flex items-center">
                            <span>{{ number_format($course->current_students) }} học viên đã đăng ký</span>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Cột B: Thumbnail - Desktop: bên phải, Mobile: trên -->
                <div class="relative order-1 lg:order-2">
                    <div class="aspect-video rounded-md overflow-hidden shadow-2xl">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}"
                                 alt="{{ $course->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Nội dung chính - Nền trắng -->
    <section class="bg-white py-8 md:py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <!-- Cột 70% - Tabs -->
                <div class="lg:col-span-7">
                    <!-- Nút CTA - Hiển thị trên mobile, ẩn trên desktop -->
                    <div class="lg:hidden mb-6 flex justify-center">
                        @guest
                            @if($course->isFree())
                                <a href="{{ route('login') }}"
                                   class="inline-block w-full bg-black text-white font-semibold px-8 py-3 rounded-md transition duration-200 text-center">
                                    Đăng nhập để học
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-block w-full bg-black text-white font-semibold px-8 py-3 rounded-md transition duration-200 text-center">
                                    Đăng nhập để mua
                                </a>
                            @endif
                        @else
                            @if($course->isFree())
                                <a href="{{ route('learning.show', $course->slug) }}"
                                   class="inline-block w-full bg-black text-white font-semibold px-8 py-3 rounded-md transition duration-200 text-center">
                                    Vào học ngay
                                </a>
                            @else
                                @if($isEnrolled)
                                    <a href="{{ route('learning.show', $course->slug) }}"
                                       class="inline-block w-full bg-black text-white font-semibold px-8 py-3 rounded-md transition duration-200 text-center">
                                        Vào học ngay
                                    </a>
                                @else
                                    <a href="{{ route('pre-checkout.show', $course->slug) }}"
                                       class="inline-block w-full bg-black text-white font-semibold px-8 py-3 rounded-md transition duration-200 text-center uppercase">
                                        Mua ngay
                                    </a>
                                @endif
                            @endif
                        @endguest
                    </div>

                    <!-- Tab Headers -->
                    <div class="bg-black rounded-md mb-6">
                        <nav class="flex space-x-2 p-2">
                            <button onclick="switchTab('curriculum', this)"
                                    id="tab-curriculum"
                                    class="tab-button flex-1 py-2 px-4 text-sm md:text-lg bg-white text-black rounded-sm transition-all duration-300">
                                Giáo trình
                            </button>
                            <button onclick="switchTab('overview', this)"
                                    id="tab-overview"
                                    class="tab-button flex-1 py-2 px-4 text-sm md:text-lg text-white/60 rounded-sm transition-all duration-300">
                                Tổng quan
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content: Giáo trình -->
                    <div id="content-curriculum" class="tab-content">
                        <div class="space-y-4">
                            @php
                                $lessonNumber = 0;
                            @endphp
                            @foreach($course->chapters as $chapter)
                                <div class="border border-gray-200 rounded-md overflow-hidden">
                                    <!-- Chapter Header -->
                                    <div class="bg-black/10 px-4 py-3 border-b border-gray-200">
                                        <h3 class="text-black heading-font text-base md:text-lg">{{ $chapter->title }}</h3>
                                    </div>

                                    <!-- Lessons List -->
                                    <div class="divide-y divide-gray-200">
                                        @foreach($chapter->lessons as $lesson)
                                            @php
                                                $lessonNumber++;
                                            @endphp
                                            <div class="flex items-center gap-3 md:gap-4 p-4 hover:bg-gray-50 transition">
                                                <!-- Số thứ tự -->
                                                <div class="flex-shrink-0 flex items-center justify-center text-xs md:text-sm font-medium text-black">
                                                    {{ $lessonNumber }}
                                                </div>

                                                <!-- Thumbnail -->
                                                <div class="flex-shrink-0">
                                                    <div class="w-20 h-14 rounded-md overflow-hidden bg-gray-100">
                                                        @if($lesson->thumbnail_url)
                                                            <img src="{{ $lesson->thumbnail_url }}"
                                                                 alt="{{ $lesson->title }}"
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Tên bài học -->
                                                <div class="flex-grow">
                                                    <h4 class="text-xs md:text-base text-black">{{ $lesson->title }}</h4>
                                                    @if($lesson->is_preview)
                                                        <span class="inline-block mt-1 text-xs bg-[#f4a83d] text-white px-2 py-1 rounded">Xem thử miễn phí</span>
                                                    @endif
                                                </div>

                                                <!-- Thời lượng -->
                                                <div class="flex-shrink-0 text-xs md:text-sm text-gray-700">
                                                    @if($lesson->duration)
                                                        {{ gmdate('i:s', $lesson->duration) }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @if($course->chapters->isEmpty())
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <p class="mt-4">Chưa có nội dung giáo trình</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab Content: Tổng quan -->
                    <div id="content-overview" class="tab-content hidden opacity-0">
                        <div class="prose max-w-none">
                            {!! $course->description !!}
                        </div>
                    </div>
                </div>

                <!-- Cột 30% - Sidebar thông tin -->
                <div class="lg:col-span-3">
                    <div class="sticky top-4 space-y-6">
                        <!-- Nút CTA - Hiển thị trên desktop, ẩn trên mobile -->
                        <div class="hidden lg:block">
                            @guest
                                @if($course->isFree())
                                    <a href="{{ route('login') }}"
                                       class="block w-full bg-black text-white text-md md:text-lg font-semibold px-6 py-4 rounded-md transition duration-200 text-center">
                                        Đăng nhập để học
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="block w-full bg-black text-white text-md md:text-lg font-semibold px-6 py-4 rounded-md transition duration-200 text-center">
                                        Đăng nhập để mua
                                    </a>
                                @endif
                            @else
                                @if($course->isFree())
                                    <a href="{{ route('learning.show', $course->slug) }}"
                                       class="block w-full bg-black text-white text-md md:text-lg font-semibold px-6 py-4 rounded-md transition duration-200 text-center">
                                        Vào học ngay
                                    </a>
                                @else
                                    @if($isEnrolled)
                                        <a href="{{ route('learning.show', $course->slug) }}"
                                           class="block w-full bg-black text-white text-md md:text-lg font-semibold px-6 py-4 rounded-md transition duration-200 text-center">
                                            Vào học ngay
                                        </a>
                                    @else
                                        <a href="{{ route('pre-checkout.show', $course->slug) }}"
                                           class="block w-full bg-black text-white text-md md:text-lg font-semibold px-6 py-4 rounded-md transition duration-200 text-center">
                                            Mua ngay
                                        </a>
                                    @endif
                                @endif
                            @endguest
                        </div>

                        <!-- Card: Thông tin khóa học -->
                        <div class="bg-white border border-gray-200 rounded-md p-4 md:p-6">
                            <h3 class="font-semibold heading-font text-lg mb-4">Thông tin khóa học</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Danh mục -->
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500">Danh mục</p>
                                    </div>
                                    <p class="font-medium text-sm">{{ $course->category->name }}</p>
                                </div>

                                <!-- Tác giả -->
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500">Tác giả</p>
                                    </div>
                                    <p class="font-medium text-sm">{{ $course->author->name }}</p>
                                </div>

                                <!-- Thời lượng -->
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500">Thời lượng</p>
                                    </div>
                                    <p class="font-medium text-sm">{{ $course->duration }}</p>
                                </div>

                                <!-- Lượt xem -->
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500">Lượt xem</p>
                                    </div>
                                    <p class="font-medium text-sm">{{ number_format($course->views) }}</p>
                                </div>

                                <!-- Số học viên -->
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500">Số học viên</p>
                                    </div>
                                    <p class="font-medium text-sm">{{ number_format($course->current_students) }}</p>
                                </div>

                                <!-- Số bài học -->
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500">Số bài học</p>
                                    </div>
                                    <p class="font-medium text-sm">{{ $course->total_lessons }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card: Khóa học liên quan -->
                        @if($relatedCourses->isNotEmpty())
                            <div class="bg-white border border-gray-200 rounded-md p-4 md:p-6">
                                <h3 class="font-semibold heading-font text-lg mb-4">Khóa học liên quan</h3>
                                <div class="space-y-4">
                                    @foreach($relatedCourses as $related)
                                        <a href="{{ route('courses.show', $related->slug) }}"
                                           class="block group">
                                            <div class="flex gap-3">
                                                <!-- Thumbnail - 40% -->
                                                <div class="w-[40%] flex-shrink-0">
                                                    <div class="aspect-video rounded overflow-hidden bg-gray-100">
                                                        @if($related->thumbnail_url)
                                                            <img src="{{ $related->thumbnail_url }}"
                                                                 alt="{{ $related->title }}"
                                                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Info - 60% -->
                                                <div class="w-[60%] flex flex-col justify-center">
                                                    <h4 class="font-medium text-sm text-gray-900 group-hover:text-blue-600 line-clamp-2 mb-1">
                                                        {{ $related->title }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500">{{ $related->author->name }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function switchTab(tabName, button) {
                const allContents = document.querySelectorAll('.tab-content');
                const allButtons = document.querySelectorAll('.tab-button');
                const targetContent = document.getElementById('content-' + tabName);

                // Fade out current content
                const currentContent = document.querySelector('.tab-content:not(.hidden)');

                gsap.to(currentContent, {
                    duration: 0.2,
                    opacity: 0,
                    y: -10,
                    ease: 'power2.in',
                    onComplete: function() {
                        // Hide all contents
                        allContents.forEach(content => {
                            content.classList.add('hidden');
                            gsap.set(content, { opacity: 0, y: -10 });
                        });

                        // Show target content
                        targetContent.classList.remove('hidden');

                        // Fade in new content
                        gsap.to(targetContent, {
                            duration: 0.3,
                            opacity: 1,
                            y: 0,
                            ease: 'power2.out'
                        });
                    }
                });

                // Update button states with animation
                allButtons.forEach(btn => {
                    gsap.to(btn, {
                        duration: 0.3,
                        backgroundColor: 'rgba(0, 0, 0, 0)', // thay vì rgba(255, 255, 255, 0)
                        ease: 'power2.out'
                    });
                    btn.classList.remove('font-semibold', 'bg-white/10', 'text-black'); // thêm text-black
                    btn.classList.add('font-medium', 'text-white/60');
                });



                // Animate active button
                gsap.to(button, {
                    duration: 0.3,
                    backgroundColor: 'rgba(255, 255, 255, 1)', // thay đổi từ 0.1 thành 1
                    ease: 'power2.out'
                });
                button.classList.remove('font-medium', 'text-white/60');
                button.classList.add('bg-white', 'text-black'); // thêm bg-white text-black
            }
        </script>
    @endpush
@endsection
