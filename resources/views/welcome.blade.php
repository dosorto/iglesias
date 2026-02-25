<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans flex items-center justify-center min-h-screen bg-gradient-to-br from-[#6C5DD3] via-[#8A7BE9] to-[#B2A4F2]">

    <!-- Contenedor central -->
    <div class="relative w-full max-w-md p-8 bg-white dark:bg-zinc-800 rounded-xl shadow-xl flex flex-col gap-6 z-10">

        <!-- 🔵 LOGO -->
        <div class="flex justify-center">
            <img src="{{ asset('image/Logo_guest.png') }}"
                 alt="Logo Iglesia"
                 class="w-24 h-24 object-contain" />
        </div>

        <h1 class="text-3xl font-bold text-black dark:text-white text-center">
            Sistema de Iglesias UNAH
        </h1>

        <p class="text-sm text-black/70 dark:text-white/70 text-center">
            Registra tu iglesia o inicia sesión para comenzar.
        </p>

        @guest
        <div class="flex flex-col gap-3">
            <a href="{{ route('register.organization') }}"
               class="inline-flex items-center justify-center rounded-md bg-[#4B3FBD] px-4 py-2 text-sm font-semibold text-white hover:bg-[#3A2EA0] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4B3FBD]">
                Crear cuenta de Iglesia
            </a>

            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center rounded-md border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 focus:outline-none dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                Iniciar sesión
            </a>
        </div>
        @endguest

    </div>

</body>
</html>