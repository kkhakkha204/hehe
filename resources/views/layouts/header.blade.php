<header class="bg-[#111] sticky top-0 z-50 border-b border-[#333]">
    @php
        $isHomeActive = request()->routeIs('home');
        $isCoursesActive = request()->routeIs('courses.*') || request()->routeIs('learning.*') || request()->routeIs('checkout.*') || request()->routeIs('pre-checkout.*');
        $isAboutActive = request()->routeIs('about');
        $isFeedbackActive = request()->routeIs('feedback');
    @endphp

    <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="LMS Logo" class="h-14 w-auto object-contain">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden xl:flex items-center gap-10">
                <nav class="flex items-center gap-10 text-[16px] font-normal">
                    <a href="{{ route('home') }}" class="text-white border-b-2 {{ $isHomeActive ? 'border-white' : 'border-transparent hover:border-white/70' }} pb-1 transition-colors duration-200">
                        Trang chủ
                    </a>
                    <a href="{{ route('courses.index') }}" class="text-white border-b-2 {{ $isCoursesActive ? 'border-white' : 'border-transparent hover:border-white/70' }} pb-1 transition-colors duration-200">
                        Khóa học
                    </a>
                    <a href="{{ route('about') }}" class="text-white border-b-2 {{ $isAboutActive ? 'border-white' : 'border-transparent hover:border-white/70' }} pb-1 transition-colors duration-200">
                        Giới thiệu
                    </a>
                    <a href="{{ route('feedback') }}" class="text-white border-b-2 {{ $isFeedbackActive ? 'border-white' : 'border-transparent hover:border-white/70' }} pb-1 transition-colors duration-200">
                        Feedback
                    </a>
                    <a href="{{ route('knowledge') }}" class="{{ request()->routeIs('knowledge') ? 'text-[#ffb800]' : 'text-white hover:text-[#ffb800]' }} transition-colors duration-200">
                        Kiến thức Makeup
                    </a>
                </nav>

                <!-- Auth Links -->
                <div class="flex items-center gap-4 text-[16px] font-normal">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="border border-[#d4af37] text-[#d4af37] px-5 py-2 rounded-md hover:bg-[#d4af37] hover:text-black transition-colors duration-300">
                                Truy cập admin
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="border border-[#d4af37] text-[#d4af37] px-5 py-2 rounded-md hover:bg-[#d4af37] hover:text-black transition-colors duration-300">
                                Khóa học của tôi
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-[#d4af37] transition-colors duration-200">
                                Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-[#111] px-7 py-2.5 rounded-md hover:bg-[#f0f0f0] transition-colors duration-200">
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="xl:hidden text-white p-2 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed top-0 right-0 h-full w-[80%] md:w-80 bg-black z-50 transform translate-x-full">
        <div class="flex flex-col h-full p-8">
            <!-- Logo and Close Button on same line -->
            <div class="flex justify-between items-center mb-12">
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="LMS Logo" class="h-10 w-auto object-contain">
                <button id="mobile-menu-close" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col space-y-6 mb-12">
                <a href="{{ route('home') }}" class="text-white text-lg border-l-2 {{ $isHomeActive ? 'border-white' : 'border-transparent' }} pl-3 hover:text-gray-300 transition-colors duration-200 font-medium">
                    Trang chủ
                </a>
                <a href="{{ route('courses.index') }}" class="text-white text-lg border-l-2 {{ $isCoursesActive ? 'border-white' : 'border-transparent' }} pl-3 hover:text-gray-300 transition-colors duration-200 font-medium">
                    Khóa học
                </a>
                <a href="{{ route('about') }}" class="text-white text-lg border-l-2 {{ $isAboutActive ? 'border-white' : 'border-transparent' }} pl-3 hover:text-gray-300 transition-colors duration-200 font-medium">
                    Giới thiệu
                </a>
                <a href="{{ route('feedback') }}" class="text-white text-lg border-l-2 {{ $isFeedbackActive ? 'border-white' : 'border-transparent' }} pl-3 hover:text-gray-300 transition-colors duration-200 font-medium">
                    Feedback
                </a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-white text-lg hover:text-gray-300 transition-colors duration-200 font-medium">
                            Truy cập admin
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="text-white text-lg hover:text-gray-300 transition-colors duration-200 font-medium">
                            Khóa học của tôi
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- Auth Buttons -->
            <div class="flex flex-col space-y-4 mt-auto">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1 text-white text-lg border-b hover:text-gray-300 transition-colors duration-200 font-medium text-left">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-black text-lg px-6 py-3 text-center font-medium rounded-md hover:bg-gray-200 transition-colors duration-200">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}" class="text-white text-lg text-center hover:text-gray-300 transition-colors duration-200 font-medium">
                        Đăng ký
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');

        function openMenu() {
            overlay.classList.remove('hidden');
            gsap.to(overlay, {
                opacity: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
            gsap.to(mobileMenu, {
                x: 0,
                duration: 0.4,
                ease: 'power2.out'
            });
        }

        function closeMenu() {
            gsap.to(overlay, {
                opacity: 0,
                duration: 0.3,
                ease: 'power2.in',
                onComplete: () => overlay.classList.add('hidden')
            });
            gsap.to(mobileMenu, {
                x: '100%',
                duration: 0.4,
                ease: 'power2.in'
            });
        }

        menuBtn.addEventListener('click', openMenu);
        closeBtn.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);

        // Close menu when clicking on navigation links
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    });
</script>
