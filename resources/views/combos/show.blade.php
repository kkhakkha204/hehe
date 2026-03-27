@extends('layouts.app-public')

@section('title', $combo->title)

@section('content')
    <section class="bg-[#e5e5e5] py-10 md:py-14">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-8">
                <div class="lg:col-span-3 space-y-6">
                    <h1 class="heading-font text-[44px] md:text-[52px] leading-tight uppercase text-[#333333]">{{ $combo->title }}</h1>

                    <div class="bg-black rounded-md p-3 md:p-4">
                        <p class="text-white text-[20px] mb-4">Combo đã bao gồm các khóa học:</p>

                        @if($includedCourses->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($includedCourses as $course)
                                    <a href="{{ route('courses.show', $course->slug) }}" class="flex items-center gap-3 rounded-sm hover:bg-white/10 transition-colors p-1 min-w-0">
                                        <img
                                            src="{{ $course->thumbnail_url ?? asset('storage/nen.webp') }}"
                                            alt="{{ $course->title }}"
                                            class="w-[74px] h-[42px] object-cover rounded"
                                            loading="lazy"
                                        >
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-white text-[20px] leading-tight truncate">{{ $course->title }}</h3>
                                        </div>
                                        <span class="text-white text-[16px] heading-font leading-none whitespace-nowrap shrink-0">
                                            {{ number_format($course->display_price, 0, ',', '.') }}đ
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-white/70">Chưa có khóa học phù hợp trong combo này.</p>
                        @endif
                    </div>

                    <div class="rounded-md overflow-hidden">
                        <img
                            src="{{ $combo->thumbnail_url ?? asset('storage/nen.webp') }}"
                            alt="{{ $combo->title }}"
                            class="w-full h-auto object-cover"
                        >
                    </div>
                </div>

                <aside class="lg:col-span-1 space-y-8">
                    @php
                        $buyNowUrl = $combo->courses->isNotEmpty()
                            ? route('courses.show', $combo->courses->first()->slug)
                            : route('courses.index');
                    @endphp

                    <a href="{{ $buyNowUrl }}" class="grid grid-cols-[auto_1fr] bg-black rounded-md overflow-hidden group">
                        <span class="px-5 py-3 text-white text-[14px] md:text-[15px] font-semibold uppercase border-r border-white/10 group-hover:text-[#d4af37] transition-colors whitespace-nowrap">
                            Mua ngay
                        </span>
                        <span class="px-5 py-3 text-white text-[16px] font-semibold whitespace-nowrap text-right">
                            {{ number_format($combo->display_price, 0, ',', '.') }}đ
                        </span>
                    </a>

                    <div class="border-t border-[#d4d4d4] pt-6">
                        <h2 class="text-[#333333] font-semibold text-[20px] mb-3">Tìm kiếm</h2>
                        <form method="GET" action="{{ route('combos.show', $combo->slug) }}" class="flex items-stretch gap-2">
                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="flex-1 h-[40px] border border-[#bdbdbd] bg-[#efefef] px-3 text-[14px] focus:outline-none"
                                placeholder="Nhập tên khóa học"
                            >
                            <button type="submit" class="h-[40px] min-w-[88px] bg-[#39404a] text-white text-[14px] font-semibold px-4 whitespace-nowrap hover:bg-black transition-colors">
                                Tìm kiếm
                            </button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
