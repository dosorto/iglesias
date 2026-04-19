<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-[#efedf8] to-[#f4f1f7] min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-between px-4 py-8">
        <div class="w-full max-w-[420px]">
            <div class="text-center mb-5">
                @if (request()->routeIs('register-perfil') && session('pending_encargado_registration'))
                    <span class="inline-block font-serif text-5xl font-bold text-[#171b3d] leading-none cursor-default">
                        Holy Manager
                    </span>
                @else
                    <a href="/" wire:navigate class="inline-block font-serif text-5xl font-bold text-[#171b3d] leading-none">
                        Holy Manager
                    </a>
                @endif
            </div>

            <div class="w-full rounded-2xl border border-[#d8d7e9] bg-[#f8f8fc] p-6 shadow-[0_14px_32px_rgba(28,32,72,0.12)]">
                {{ $slot }}
            </div>
        </div>

        <footer class="w-full max-w-[860px] mt-10 text-center text-sm text-[#64687c]">
            <span class="font-serif italic text-[#1d2142]">Holy Manager</span>
            <span class="mx-2">© 2026 Holy Manager.</span>
            <span>Disenado para la gestion eclesial.</span>
        </footer>
    </div>
</body>
</html>
