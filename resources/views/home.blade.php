@extends('layouts.app-public')

@section('title', 'Trang chủ | Mewart Makeup')

@section('content')
    <!-- Banner -->
    <section class="relative bg-[#000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/banner.webp" class="w-full h-full object-cover opacity-60" alt="Banner">
        </div>
        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-32 md:py-48 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 uppercase tracking-wider drop-shadow-lg">
                TỰ TIN MAKEUP <span class="text-[#d4af37]">MỖI NGÀY</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto">
                Trang điểm giúp mỗi người trở thành phiên bản rạng rỡ và tự tin nhất của chính mình.
            </p>
            <a href="{{ route('courses.index') }}" class="inline-block bg-[#d4af37] text-black font-bold uppercase tracking-widest py-4 px-10 rounded-sm hover:bg-white hover:text-black transition-colors duration-300">
                Bắt Đầu Học Ngay
            </a>
        </div>
    </section>

    <!-- Stats -->
    <section class="border-y border-[#333] bg-[#0a0a0a]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-[#d4af37] mb-2">6</div>
                    <div class="text-sm text-gray-400 uppercase tracking-widest">Năm kinh nghiệm</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-[#d4af37] mb-2">5,600+</div>
                    <div class="text-sm text-gray-400 uppercase tracking-widest">Học viên</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-[#d4af37] mb-2">589K+</div>
                    <div class="text-sm text-gray-400 uppercase tracking-widest">Người theo dõi</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-[#d4af37] mb-2">4.8/5</div>
                    <div class="text-sm text-gray-400 uppercase tracking-widest">Sao đánh giá</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-20 bg-[#151515]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white uppercase tracking-wider mb-4">KHÓA HỌC NỔI BẬT</h2>
                <div class="w-24 h-1 bg-[#d4af37] mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($featuredCourses as $course)
                    <div class="bg-[#1a1a1a] border border-[#333] rounded-lg overflow-hidden group hover:border-[#d4af37] transition-all duration-300">
                        <a href="{{ route('courses.show', $course->slug) }}" class="block relative aspect-video overflow-hidden">
                            <img src="{{ $course->thumbnail ?? 'https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/banner2.webp' }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @if($course->is_featured)
                                <span class="absolute top-4 right-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded uppercase">Hot</span>
                            @endif
                        </a>
                        <div class="p-6 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-white mb-3 line-clamp-2">
                                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-[#d4af37] transition-colors">{{ $course->title }}</a>
                            </h3>
                            <div class="text-sm text-gray-400 mb-6">👤 {{ $course->author->name ?? 'Hiền Mew' }}</div>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-[#333]">
                                <div class="flex flex-col">
                                    @if($course->sale_price > 0 && $course->sale_price < $course->price)
                                        <span class="text-gray-500 line-through text-xs">{{ number_format($course->price, 0, ',', '.') }}đ</span>
                                        <span class="text-[#d4af37] font-bold text-lg">{{ number_format($course->sale_price, 0, ',', '.') }}đ</span>
                                    @else
                                        <span class="text-[#d4af37] font-bold text-lg">{{ $course->price == 0 ? 'Miễn phí' : number_format($course->price, 0, ',', '.') . 'đ' }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('courses.show', $course->slug) }}" class="bg-[#333] hover:bg-[#d4af37] hover:text-black text-white px-4 py-2 text-sm font-bold uppercase transition-colors rounded">Xem ngay</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3 text-center">Chưa có khóa học nào nổi bật.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Free Courses -->
    <section class="py-20 bg-[#111111]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white uppercase tracking-wider mb-4">KHÓA HỌC MIỄN PHÍ</h2>
                <div class="w-24 h-1 bg-[#d4af37] mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($freeCourses as $course)
                    <div class="bg-[#1a1a1a] border border-[#333] rounded-lg overflow-hidden group hover:border-[#d4af37] transition-all duration-300">
                        <a href="{{ route('courses.show', $course->slug) }}" class="block relative aspect-video overflow-hidden">
                            <img src="{{ $course->thumbnail ?? 'https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/banner2.webp' }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </a>
                        <div class="p-6 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-white mb-3 line-clamp-2">
                                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-[#d4af37] transition-colors">{{ $course->title }}</a>
                            </h3>
                            <div class="text-sm text-gray-400 mb-6">👤 {{ $course->author->name ?? 'Hiền Mew' }}</div>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-[#333]">
                                <span class="text-[#d3a637] font-bold text-lg">Miễn phí</span>
                                <a href="{{ route('courses.show', $course->slug) }}" class="bg-[#333] hover:bg-[#d3a637] hover:text-black text-white px-4 py-2 text-sm font-bold uppercase transition-colors rounded">Học ngay</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3 text-center">Chưa có khóa học miễn phí nào.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection