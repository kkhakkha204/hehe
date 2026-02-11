<header class="bg-black sticky top-0 z-50">
    <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="LMS Logo" class="h-7 w-auto object-contain">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden xl:flex items-center space-x-16">
                <nav class="flex items-center space-x-8">
                    <a href="{{ route('courses.index') }}" class="text-white hover:text-gray-300 transition-colors duration-200 font-medium">
                        Khóa học
                    </a>
                    <a href="#" class="text-white hover:text-gray-300 transition-colors duration-200 font-medium">
                        Về chúng tôi
                    </a>
                </nav>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white text-black px-6 py-2.5 font-medium rounded-md hover:bg-gray-200 transition-colors duration-200">
                            Khóa học của tôi
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-gray-300 transition-colors duration-200 font-medium">
                                Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-black px-6 py-2.5 font-medium rounded-md hover:bg-gray-200 transition-colors duration-200">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="text-white hover:text-gray-300 transition-colors duration-200 font-medium ">
                            Đăng ký
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
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="LMS Logo" class="h-7 w-auto object-contain">
                <button id="mobile-menu-close" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col space-y-6 mb-12">
                <a href="{{ route('courses.index') }}" class="text-white text-lg hover:text-gray-300 transition-colors duration-200 font-medium">
                    Khóa học
                </a>
                <a href="#" class="text-white text-lg hover:text-gray-300 transition-colors duration-200 font-medium">
                    Về chúng tôi
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white text-lg hover:text-gray-300 transition-colors duration-200 font-medium">
                        Khóa học của tôi
                    </a>
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
