<header class="sticky top-0 z-50 border-b border-[#333] bg-[#111]">
    @php
        $isHomeActive = request()->routeIs('home');
        $isCoursesActive = request()->routeIs('courses.*') || request()->routeIs('learning.*') || request()->routeIs('checkout.*') || request()->routeIs('pre-checkout.*');
        $isAboutActive = request()->routeIs('about');
        $isFeedbackActive = request()->routeIs('feedback');
        $isKnowledgeActive = request()->routeIs('knowledge');
    @endphp

    <div class="mx-auto max-w-[1340px] px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="LMS Logo" class="h-14 w-auto object-contain">
            </a>

            <div class="hidden xl:flex items-center gap-10">
                <nav class="flex items-center gap-10 text-[16px] font-normal">
                    <a href="{{ route('home') }}" class="border-b-2 pb-1 text-white transition-colors duration-200 {{ $isHomeActive ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                        Trang chủ
                    </a>
                    <a href="{{ route('courses.index') }}" class="border-b-2 pb-1 text-white transition-colors duration-200 {{ $isCoursesActive ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                        Khóa học
                    </a>
                    <a href="{{ route('about') }}" class="border-b-2 pb-1 text-white transition-colors duration-200 {{ $isAboutActive ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                        Giới thiệu
                    </a>
                    <a href="{{ route('feedback') }}" class="border-b-2 pb-1 text-white transition-colors duration-200 {{ $isFeedbackActive ? 'border-white' : 'border-transparent hover:border-white/70' }}">
                        Feedback
                    </a>
                    <a href="{{ route('knowledge') }}" class="transition-colors duration-200 {{ $isKnowledgeActive ? 'text-[#ffb800]' : 'text-white hover:text-[#ffb800]' }}">
                        Kiến thức Makeup
                    </a>
                </nav>

                <div class="flex items-center gap-4 text-[16px] font-normal">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="rounded-md border border-[#d4af37] px-5 py-2 text-[#d4af37] transition-colors duration-300 hover:bg-[#d4af37] hover:text-black">
                                Truy cập admin
                            </a>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button
                                    type="button"
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="inline-flex items-center gap-2 rounded-md bg-white px-5 py-2.5 text-[#111] transition-colors duration-200 hover:bg-[#f0f0f0]"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a3 3 0 116 0 3 3 0 01-6 0zm-2 7a5 5 0 1110 0H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition.opacity.duration.150ms
                                    class="absolute right-0 top-full z-50 mt-4 w-[420px] rounded-md bg-[#f7f3ed] p-6 text-[#0f172a] shadow-[0_20px_60px_rgba(0,0,0,0.22)]"
                                    style="display: none;"
                                >
                                    <p class="heading-font mb-6 text-[20px] text-[#111]">Lối tắt</p>

                                    <div class="flex items-start justify-between gap-6">
                                        <a href="{{ route('profile.edit', ['tab' => 'courses']) }}" class="text-[18px] transition-colors duration-200 hover:text-black">
                                            Các khóa học đã đăng ký
                                        </a>
                                        <a href="{{ route('profile.edit', ['tab' => 'settings']) }}" class="text-[18px] transition-colors duration-200 hover:text-black">
                                            Cài đặt
                                        </a>
                                    </div>

                                    <div class="my-6 h-px bg-[#d8d2c9]"></div>

                                    <form method="POST" action="{{ route('logout') }}" class="flex justify-end">
                                        @csrf
                                        <button type="submit" class="text-[18px] transition-colors duration-200 hover:text-black">
                                            Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="rounded-md bg-white px-7 py-2.5 text-[#111] transition-colors duration-200 hover:bg-[#f0f0f0]">
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>

            <button id="mobile-menu-btn" class="p-2 text-white focus:outline-none xl:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu-overlay" class="fixed inset-0 z-40 hidden bg-black/50"></div>

    <div id="mobile-menu" class="fixed right-0 top-0 z-50 h-full w-[80%] translate-x-full bg-black md:w-80">
        <div class="flex h-full flex-col p-8">
            <div class="mb-12 flex items-center justify-between">
                <img src="https://khoahoc.mewartmakeup.vn/wp-content/uploads/2025/12/logo.webp" alt="LMS Logo" class="h-10 w-auto object-contain">
                <button id="mobile-menu-close" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="mb-12 flex flex-col space-y-6">
                <a href="{{ route('home') }}" class="border-l-2 pl-3 text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300 {{ $isHomeActive ? 'border-white' : 'border-transparent' }}">
                    Trang chủ
                </a>
                <a href="{{ route('courses.index') }}" class="border-l-2 pl-3 text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300 {{ $isCoursesActive ? 'border-white' : 'border-transparent' }}">
                    Khóa học
                </a>
                <a href="{{ route('about') }}" class="border-l-2 pl-3 text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300 {{ $isAboutActive ? 'border-white' : 'border-transparent' }}">
                    Giới thiệu
                </a>
                <a href="{{ route('feedback') }}" class="border-l-2 pl-3 text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300 {{ $isFeedbackActive ? 'border-white' : 'border-transparent' }}">
                    Feedback
                </a>
                <a href="{{ route('knowledge') }}" class="border-l-2 pl-3 text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300 {{ $isKnowledgeActive ? 'border-white' : 'border-transparent' }}">
                    Kiến thức Makeup
                </a>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300">
                            Truy cập admin
                        </a>
                    @else
                        <div class="border-t border-white/10 pt-6">
                            <p class="mb-4 text-lg font-medium text-white">{{ auth()->user()->name }}</p>
                            <div class="flex flex-col space-y-4">
                                <a href="{{ route('profile.edit', ['tab' => 'courses']) }}" class="text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300">
                                    Các khóa học đã đăng ký
                                </a>
                                <a href="{{ route('profile.edit', ['tab' => 'settings']) }}" class="text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300">
                                    Cài đặt
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </nav>

            <div class="mt-auto flex flex-col space-y-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="border-b p-1 text-left text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-md bg-white px-6 py-3 text-center text-lg font-medium text-black transition-colors duration-200 hover:bg-gray-200">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}" class="text-center text-lg font-medium text-white transition-colors duration-200 hover:text-gray-300">
                        Đăng ký
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        menuBtn?.addEventListener('click', openMenu);
        closeBtn?.addEventListener('click', closeMenu);
        overlay?.addEventListener('click', closeMenu);

        const menuLinks = mobileMenu?.querySelectorAll('a');
        menuLinks?.forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    });
</script>
