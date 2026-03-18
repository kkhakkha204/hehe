@props(['courses'])

<section class="featured-courses-section bg-[#000000] py-12 md:py-16">
    <div class="container max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title -->
        <h2 class="heading-font text-[36px] font-normal text-white uppercase text-center md:text-start mb-8 md:mb-12 leading-none">
            Khóa học nổi bật
        </h2>

        <!-- Carousel Container -->
        <div class="featured-carousel relative">
            <!-- Slides Wrapper -->
            <div class="overflow-hidden">
                <div class="featured-track flex gap-6">
                    @foreach($courses as $course)
                        <div class="featured-slide flex-shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                            <x-featured-course-card :course="$course" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Arrows -->
            @if(count($courses) > 3)
                <button
                    class="featured-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 lg:-translate-x-7 translate-y-0 z-10 w-8 h-8 md:w-10 md:h-10 bg-white text-black shadow-md rounded-md flex items-center justify-center hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    aria-label="Slide trước"
                >
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button
                    class="featured-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 lg:translate-x-7 translate-y-0 z-10 w-8 h-8 md:w-10 md:h-10 bg-white text-black shadow-md rounded-md flex items-center justify-center hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    aria-label="Slide tiếp theo"
                >
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.featured-carousel');
            if (!carousel) return;

            const track = carousel.querySelector('.featured-track');
            const slides = carousel.querySelectorAll('.featured-slide');
            const prevBtn = carousel.querySelector('.featured-prev');
            const nextBtn = carousel.querySelector('.featured-next');

            if (!track || slides.length === 0) return;

            let currentIndex = 0;
            let isAnimating = false;
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            let slidesPerView = getSlidesPerView();

            function getSlidesPerView() {
                const width = window.innerWidth;
                if (width >= 1024) return 3; // lg
                if (width >= 640) return 2;  // sm
                return 1;
            }

            function getMaxIndex() {
                return Math.max(0, slides.length - slidesPerView);
            }

            function updateButtonStates() {
                if (!prevBtn || !nextBtn) return;

                const maxIndex = getMaxIndex();
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
            }

            function goToSlide(index) {
                if (isAnimating) return;

                const maxIndex = getMaxIndex();
                index = Math.max(0, Math.min(index, maxIndex));

                if (index === currentIndex) return;

                isAnimating = true;

                const slideWidth = slides[0].offsetWidth;
                const gap = 24; // 6 * 4px (gap-6)
                const offset = -(index * (slideWidth + gap));

                gsap.to(track, {
                    x: offset,
                    duration: 0.6,
                    ease: 'power2.out',
                    onComplete: () => {
                        currentIndex = index;
                        isAnimating = false;
                        updateButtonStates();
                    }
                });
            }

            function nextSlide() {
                goToSlide(currentIndex + 1);
            }

            function prevSlide() {
                goToSlide(currentIndex - 1);
            }

            // Button clicks
            if (prevBtn) {
                prevBtn.addEventListener('click', prevSlide);
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', nextSlide);
            }

            // Touch/Mouse drag for swipe
            function handleStart(e) {
                if (isAnimating) return;
                isDragging = true;
                startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
                track.style.cursor = 'grabbing';
            }

            function handleMove(e) {
                if (!isDragging) return;
                e.preventDefault();
                currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
            }

            function handleEnd() {
                if (!isDragging) return;
                isDragging = false;
                track.style.cursor = 'grab';

                const diffX = currentX - startX;
                const threshold = 50;

                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        prevSlide();
                    } else {
                        nextSlide();
                    }
                }

                startX = 0;
                currentX = 0;
            }

            // Mouse events
            track.addEventListener('mousedown', handleStart);
            track.addEventListener('mousemove', handleMove);
            track.addEventListener('mouseup', handleEnd);
            track.addEventListener('mouseleave', handleEnd);

            // Touch events
            track.addEventListener('touchstart', handleStart, { passive: true });
            track.addEventListener('touchmove', handleMove, { passive: false });
            track.addEventListener('touchend', handleEnd);

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const newSlidesPerView = getSlidesPerView();
                    if (newSlidesPerView !== slidesPerView) {
                        slidesPerView = newSlidesPerView;
                        currentIndex = Math.min(currentIndex, getMaxIndex());
                        goToSlide(currentIndex);
                    }
                }, 250);
            });

            // Initialize
            updateButtonStates();
            track.style.cursor = 'grab';
        });
    </script>
@endpush
