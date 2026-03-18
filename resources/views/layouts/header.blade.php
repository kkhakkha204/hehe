<header class="bg-[#111111] border-b border-[#333] sticky top-0 z-50">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-24">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="Mewart Logo" class="h-10 w-auto object-contain">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden xl:flex items-center space-x-12">
                <nav class="flex items-center space-x-8 uppercase text-sm font-semibold tracking-wider">
                    <a href="{{ route('home') }}" class="text-white hover:text-[#d4af37] transition-colors">Trang Chủ</a>
                    <a href="{{ route('courses.index') }}" class="text-white hover:text-[#d4af37] transition-colors">Khóa Học</a>
                    <a href="#" class="text-white hover:text-[#d4af37] transition-colors">Giới Thiệu</a>
                </nav>
            </div>

            <!-- Auth -->
            <div class="hidden xl:flex items-center space-x-4 uppercase text-sm font-semibold tracking-wider">
                @auth
                    <a href="{{ route('dashboard') }}" class="border border-[#d4af37] text-[#d4af37] px-5 py-2 rounded-sm hover:bg-[#d4af37] hover:text-black transition-colors">Khóa học của tôi</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-[#d4af37] transition-colors">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-[#d4af37] transition-colors uppercase">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="bg-[#d4af37] text-black px-6 py-2.5 rounded-sm hover:bg-white hover:text-black transition-colors">Đăng ký</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="xl:hidden text-white p-2 focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </div>
</header>