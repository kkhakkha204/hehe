<footer class="bg-[#111] text-white">
    <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-16 mb-12">
            <!-- Logo và Thông tin liên hệ -->
            <div>
                <a href="{{ route('home') }}" class="inline-block mb-8">
                    <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="Mewart" class="h-16 w-auto object-contain">
                </a>

                <div class="space-y-6 text-[18px] leading-relaxed text-white">
                    <div>
                        <p class="font-semibold text-white inline">Địa chỉ:</p>
                        <p>Số 05 Lô 04 Liền Kề Báo Nhân Dân, Đường Trịnh Văn Bô, Phường Xuân Phương, Quận Nam Từ Liêm, Thành Phố Hà Nội</p>
                    </div>

                    <div>
                        <p><span class="font-semibold">Hotline:</span> 089.9898.345</p>
                    </div>

                    <div>
                        <p><span class="font-semibold">Sales:</span> sales@mewartmakeup.vn</p>
                    </div>

                    <div>
                        <p><span class="font-semibold">CSKH:</span> info@mewartmakeup.vn</p>
                    </div>
                </div>
            </div>

            <!-- Về Mewart -->
            <div>
                <h3 class="text-[18px] font-semibold mb-6">Về Mewart</h3>
                <ul class="space-y-4 text-[18px] text-white">
                    <li><a href="{{ route('home') }}" class="hover:text-white/70 transition-colors">Trang chủ</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white/70 transition-colors">Login</a></li>
                    <li><a href="#" class="hover:text-white/70 transition-colors">Feedback</a></li>
                    <li><a href="#" class="hover:text-white/70 transition-colors">Giới thiệu</a></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-white/70 transition-colors">Khóa học</a></li>
                    <li><a href="#" class="hover:text-white/70 transition-colors">Kiến thức Makeup</a></li>
                    <li><a href="#" class="hover:text-white/70 transition-colors">Chính sách và bảo mật</a></li>
                    <li><a href="#" class="hover:text-white/70 transition-colors">Điều khoản</a></li>
                </ul>
            </div>

            <!-- Theo dõi chúng tôi -->
            <div>
                <h3 class="text-[18px] font-semibold mb-6">Theo dõi chúng tôi</h3>

                <div class="space-y-4">
                    <!-- TikTok -->
                    <div class="flex bg-white items-center space-x-5 px-4 py-3 rounded-lg">
                        <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                            <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/tiktok.png" alt="TikTok" class="w-14 h-14">
                        </div>
                        <div>
                            <p class="text-[18px] text-black font-bold leading-none">240K+</p>
                            <p class="text-[18px] text-black font-bold tracking-wide">FOLLOWER</p>
                        </div>
                    </div>

                    <!-- Facebook -->
                    <div class="flex bg-white items-center space-x-5 px-4 py-3 rounded-lg">
                        <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                            <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/facebook.png" alt="Facebook" class="w-14 h-14">
                        </div>
                        <div>
                            <p class="text-[18px] text-black font-bold leading-none">186K+</p>
                            <p class="text-[18px] text-black font-bold tracking-wide">FOLLOWER</p>
                        </div>
                    </div>

                    <!-- Instagram -->
                    <div class="flex bg-white items-center space-x-5 px-4 py-3 rounded-lg">
                        <div class="w-14 h-14 flex items-center justify-center flex-shrink-0">
                            <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/instagram.png" alt="Instagram" class="w-14 h-14">
                        </div>
                        <div>
                            <p class="text-[18px] text-black font-bold leading-none">8.3K+</p>
                            <p class="text-[18px] text-black font-bold tracking-wide">FOLLOWER</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-[#333] pt-8 text-[14px] text-center text-white/70">
            <p>&copy; Bản quyền thuộc về & Cung cấp bởi <span class="text-white">Nextgency.vn</span></p>
        </div>
    </div>
</footer>
