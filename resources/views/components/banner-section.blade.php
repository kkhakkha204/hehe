@props(['banners'])

<section class="banner-section relative w-full overflow-hidden bg-gray-900">
    <div class="banner-carousel relative">
        <!-- Slides Container -->
        <div class="slides-wrapper relative w-full" style="aspect-ratio: 18/9;">
            @foreach($banners as $index => $banner)
                <div class="banner-slide absolute inset-0 opacity-0" data-slide="{{ $index }}">
                    <img
                        src="{{ $banner->image_url }}"
                        alt="{{ $banner->title }}"
                        class="w-full h-full object-cover"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    >
                </div>
            @endforeach
        </div>

        <!-- Arrow Navigation -->
        @if(count($banners) > 1)
            <button class="banner-arrow banner-arrow-prev absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white text-2xl transition-all duration-300 cursor-pointer" aria-label="Slide trước">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="banner-arrow banner-arrow-next absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-black/30 hover:bg-black/50 text-white text-2xl transition-all duration-300 cursor-pointer" aria-label="Slide tiếp">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"></polyline></svg>
            </button>
        @endif
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.banner-carousel');
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.banner-slide');
            const prevBtn = carousel.querySelector('.banner-arrow-prev');
            const nextBtn = carousel.querySelector('.banner-arrow-next');
            const totalSlides = slides.length;

            if (totalSlides === 0) return;

            let currentIndex = 0;
            let isAnimating = false;
            let autoplayInterval;
            let startX = 0;
            let currentX = 0;
            let isDragging = false;

            // Initialize first slide
            gsap.set(slides[0], { opacity: 1 });
            // No dots to initialize

            function goToSlide(index, direction = 'next') {
                if (isAnimating || index === currentIndex || index < 0 || index >= totalSlides) return;

                isAnimating = true;
                const currentSlide = slides[currentIndex];
                const nextSlide = slides[index];
                const oldIndex = currentIndex;

                // No dots to update

                // Animate slides
                const timeline = gsap.timeline({
                    onComplete: () => {
                        isAnimating = false;
                        currentIndex = index;
                    }
                });

                timeline
                    .set(nextSlide, { opacity: 0, scale: 1.05 })
                    .to(currentSlide, {
                        opacity: 0,
                        duration: 0.6,
                        ease: 'power2.inOut'
                    }, 0)
                    .to(nextSlide, {
                        opacity: 1,
                        scale: 1,
                        duration: 0.6,
                        ease: 'power2.inOut'
                    }, 0);
            }

            function nextSlide() {
                const next = (currentIndex + 1) % totalSlides;
                goToSlide(next, 'next');
            }

            function prevSlide() {
                const prev = (currentIndex - 1 + totalSlides) % totalSlides;
                goToSlide(prev, 'prev');
            }

            // Arrow click
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    resetAutoplay();
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    resetAutoplay();
                });
            }

            // Touch/Mouse events for swipe
            const slidesWrapper = carousel.querySelector('.slides-wrapper');

            function handleStart(e) {
                if (isAnimating) return;
                isDragging = true;
                startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
                slidesWrapper.style.cursor = 'grabbing';
            }

            function handleMove(e) {
                if (!isDragging) return;
                currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
            }

            function handleEnd() {
                if (!isDragging) return;
                isDragging = false;
                slidesWrapper.style.cursor = 'grab';

                const diffX = currentX - startX;
                const threshold = 50;

                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        prevSlide();
                    } else {
                        nextSlide();
                    }
                    resetAutoplay();
                }

                startX = 0;
                currentX = 0;
            }

            // Mouse events
            slidesWrapper.addEventListener('mousedown', handleStart);
            slidesWrapper.addEventListener('mousemove', handleMove);
            slidesWrapper.addEventListener('mouseup', handleEnd);
            slidesWrapper.addEventListener('mouseleave', handleEnd);

            // Touch events
            slidesWrapper.addEventListener('touchstart', handleStart, { passive: true });
            slidesWrapper.addEventListener('touchmove', handleMove, { passive: true });
            slidesWrapper.addEventListener('touchend', handleEnd);

            // Autoplay
            function startAutoplay() {
                if (totalSlides <= 1) return;
                autoplayInterval = setInterval(nextSlide, 5000);
            }

            function stopAutoplay() {
                if (autoplayInterval) {
                    clearInterval(autoplayInterval);
                }
            }

            function resetAutoplay() {
                stopAutoplay();
                startAutoplay();
            }

            // Start autoplay
            startAutoplay();

            // Pause on hover
            carousel.addEventListener('mouseenter', stopAutoplay);
            carousel.addEventListener('mouseleave', startAutoplay);

            // Pause autoplay when tab is not visible
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopAutoplay();
                } else {
                    startAutoplay();
                }
            });
        });
    </script>
@endpush
