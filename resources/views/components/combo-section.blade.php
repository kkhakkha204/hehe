@props(['combos' => []])

<section class="combo-section bg-[#0f0f0f] py-12 md:py-16">
    <div class="container max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title -->
        <h2 class="heading-font text-[36px] font-normal text-white uppercase text-center md:text-start mb-8 md:mb-12 leading-none">
            Các gói combo ưu đãi
        </h2>

        <!-- Placeholder for Combo Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Mock Combo 1 -->
            <div class="combo-card bg-[#111111] border border-[#d4af37]/30 rounded-lg p-6 flex flex-col items-center text-center">
                <h3 class="text-[#d4af37] text-xl font-bold uppercase mb-2">Combo Cơ Bản</h3>
                <div class="text-3xl font-bold text-white mb-6">1.490.000đ</div>
                <ul class="text-gray-400 space-y-3 mb-8 w-full text-left flex-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-[#d4af37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Khóa học trang điểm cá nhân
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-[#d4af37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Chứng nhận hoàn thành
                    </li>
                </ul>
                <button class="w-full bg-[#d4af37] text-black font-bold uppercase py-3 rounded hover:bg-[#b5952f] transition-colors">Đăng ký ngay</button>
            </div>
            
            <!-- Mock Combo 2 (Best Seller) -->
            <div class="combo-card bg-[#111111] border-2 border-[#d4af37] rounded-lg p-6 flex flex-col items-center text-center relative transform lg:-translate-y-4">
                <div class="absolute -top-4 bg-[#d4af37] text-black font-bold uppercase px-4 py-1 text-sm rounded-full">Bán chạy nhất</div>
                <h3 class="text-[#d4af37] text-xl font-bold uppercase mb-2 mt-2">Combo Chuyên Nghiệp</h3>
                <div class="text-3xl font-bold text-white mb-6">3.990.000đ</div>
                <ul class="text-gray-400 space-y-3 mb-8 w-full text-left flex-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-[#d4af37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Khóa học trang điểm cá nhân
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-[#d4af37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Khóa học trang điểm cô dâu
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-[#d4af37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Chứng nhận chuyên nghiệp
                    </li>
                </ul>
                <button class="w-full bg-[#d4af37] text-black font-bold uppercase py-3 rounded hover:bg-[#b5952f] transition-colors">Đăng ký ngay</button>
            </div>
        </div>
    </div>
</section>