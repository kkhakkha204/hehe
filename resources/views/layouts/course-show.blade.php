<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Khóa học')</title>

    @include('layouts.partials.assets-no-vite')
    @stack('styles')

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
    </style>
</head>
<body class="bg-white text-[#1f2937] antialiased">
    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
