<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Học Online') - LMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    @stack('styles')

    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .heading-font {
            font-family: 'Oswald', sans-serif;
        }
    </style>
</head>
<body class="bg-white text-black antialiased">

@include('layouts.header')

<!-- Main Content -->
<main>
    @yield('content')
</main>

<!-- Footer -->
@include('layouts.footer')

<div class="fixed bottom-5 right-4 z-[60] flex flex-col gap-3 sm:bottom-6 sm:right-6">
    <a
        href="https://www.facebook.com/people/MEW-ART-MAKE-UP/61578945831825/"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Nhắn tin Messenger"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-[#0084ff] text-white shadow-[0_12px_30px_rgba(0,132,255,0.35)] transition-transform duration-200 hover:-translate-y-1"
    >
        <svg viewBox="0 0 24 24" class="h-7 w-7 fill-current" aria-hidden="true">
            <path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.913 1.452 5.512 3.723 7.21V22l3.221-1.768c.979.272 2.017.418 3.056.418 5.523 0 10-4.145 10-9.258S17.523 2 12 2zm1.032 12.445-2.548-2.718-4.968 2.718 5.466-5.804 2.579 2.718 4.937-2.718-5.466 5.804z"/>
        </svg>
    </a>

    <a
        href="tel:0899898345"
        aria-label="Gọi điện 0899898345"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-[#22c55e] text-white shadow-[0_12px_30px_rgba(34,197,94,0.35)] transition-transform duration-200 hover:-translate-y-1"
    >
        <svg viewBox="0 0 24 24" class="h-7 w-7 fill-current" aria-hidden="true">
            <path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.11.37 2.3.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.85 21 3 13.15 3 4a1 1 0 0 1 1-1h3.49a1 1 0 0 1 1 1c0 1.28.2 2.47.57 3.58a1 1 0 0 1-.25 1.01l-2.19 2.2z"/>
        </svg>
    </a>
</div>

@stack('scripts')
</body>
</html>
