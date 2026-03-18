<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Mewart Makeup'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Mulish', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-[#1a1a1a] text-white">
    @include('layouts.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('layouts.footer')
</body>
</html>