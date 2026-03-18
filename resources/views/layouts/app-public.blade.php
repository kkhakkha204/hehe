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

@stack('scripts')
</body>
</html>
