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

        <!-- Dots Navigation -->
        @if(count($banners) > 1)
            <div class="dots-container absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex gap-2">
                @foreach($banners as $index => $banner)
                    <button
                        class="dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/60 transition-all duration-300"
                        data-dot="{{ $index }}"
                        aria-label="Chuyển đến slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
        @endif
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.banner-carousel');
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.banner-slide');
            const dots = carousel.querySelectorAll('.dot');
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
            if (dots.length > 0) {
                dots[0].classList.add('active');
                gsap.set(dots[0], { scale: 1.2, backgroundColor: 'rgba(255, 255, 255, 1)' });
            }

            function goToSlide(index, direction = 'next') {
                if (isAnimating || index === currentIndex || index < 0 || index >= totalSlides) return;

                isAnimating = true;
                const currentSlide = slides[currentIndex];
                const nextSlide = slides[index];
                const oldIndex = currentIndex;

                // Update dots
                if (dots.length > 0) {
                    gsap.to(dots[oldIndex], {
                        scale: 1,
                        backgroundColor: 'rgba(255, 255, 255, 0.4)',
                        duration: 0.3
                    });
                    dots[oldIndex].classList.remove('active');

                    gsap.to(dots[index], {
                        scale: 1.2,
                        backgroundColor: 'rgba(255, 255, 255, 1)',
                        duration: 0.3
                    });
                    dots[index].classList.add('active');
                }

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

            // Dots click
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    goToSlide(index);
                    resetAutoplay();
                });
            });

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
