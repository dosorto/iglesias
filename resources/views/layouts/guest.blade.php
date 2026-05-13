<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Parroquial') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="shortcut icon" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="apple-touch-icon" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    use App\Models\TenantIglesia;

    $isTenantActive = config('database.default') === config('tenancy.tenant_connection', 'tenant');
    $iglesiaConfig  = $isTenantActive
        ? TenantIglesia::current()
        : TenantIglesia::currentFromCentral();

    $logoUrl          = $iglesiaConfig?->logo_url             ?? asset('image/Logo_guest.png');
    $logoDerUrl       = $iglesiaConfig?->logo_derecha_url     ?? null;
    $bgUrl            = $iglesiaConfig?->login_background_url ?? null;
    $iglesiaNombre    = $iglesiaConfig?->nombre               ?? '';
    $iglesiaSubtitulo = $iglesiaConfig?->header_diocesis      ?? '';
    $iglesiaDir       = $iglesiaConfig?->direccion            ?? '';
    $hasTenant        = (bool) $iglesiaConfig;
@endphp
<body class="font-sans antialiased min-h-screen flex">

    {{-- ══════════════════════════════════════════════════════
         PANEL IZQUIERDO  — branding de la parroquia (solo desktop)
    ══════════════════════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-[58%] flex-col bg-[#0F6E46] relative overflow-hidden"
         @if($bgUrl) style="background-image:url('{{ $bgUrl }}'); background-size:cover; background-position:center;" @endif>

        {{-- Overlay cuando hay imagen de fondo --}}
        @if($bgUrl)
            <div class="absolute inset-0 bg-[#0F6E46]/70 pointer-events-none"></div>
        @endif

        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full bg-white/5 -translate-x-1/2 translate-y-1/2"></div>
            <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-white/5 translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute top-1/2 left-1/2 w-[600px] h-[600px] rounded-full bg-white/[0.03] -translate-x-1/2 -translate-y-1/2"></div>
        </div>

        {{-- Contenido centrado verticalmente --}}
        <div class="relative z-10 flex flex-col items-center justify-center flex-1 px-12 text-center gap-6">

            {{-- Logo(s) --}}
            <div class="flex items-center justify-center gap-8">
                <img src="{{ $logoUrl }}"
                     alt="Logo"
                     class="w-32 h-32 object-contain drop-shadow-2xl">
                @if ($logoDerUrl && $logoDerUrl !== $logoUrl)
                    <img src="{{ $logoDerUrl }}"
                         alt="Logo"
                         class="w-32 h-32 object-contain drop-shadow-2xl">
                @endif
            </div>

            {{-- Nombre y datos de la parroquia --}}
            @if ($iglesiaNombre)
                <div>
                    <h1 class="text-white text-3xl font-bold uppercase tracking-wide leading-tight">
                        {{ $iglesiaNombre }}
                    </h1>
                    @if ($iglesiaSubtitulo)
                        <p class="text-emerald-200 text-base tracking-widest uppercase mt-2">{{ $iglesiaSubtitulo }}</p>
                    @endif
                    @if ($iglesiaDir)
                        <p class="text-emerald-300 text-sm mt-2">{{ $iglesiaDir }}</p>
                    @endif
                </div>
            @else
                <div>
                    <h1 class="text-white text-3xl font-bold tracking-wide leading-tight">
                        Sistema de Gestión Parroquial
                    </h1>
                    <p class="text-emerald-200 text-base mt-2">
                        Administración de sacramentos y feligreses
                    </p>
                </div>
            @endif

            {{-- Divisor decorativo --}}
            <div class="w-16 h-0.5 bg-emerald-400/50 rounded-full"></div>

            {{-- Descripción --}}
            <p class="text-emerald-100/70 text-sm max-w-xs leading-relaxed">
                Gestione bautismos, confirmaciones, matrimonios y primera comunión desde un solo lugar.
            </p>
        </div>

        {{-- Pie del panel --}}
        <div class="relative z-10 px-12 pb-8 text-center">
            <p class="text-emerald-500 text-xs">Sistema de Gestión Parroquial</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         PANEL DERECHO  — formulario
    ══════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-10 bg-white">

        {{-- Logo y nombre en móvil --}}
        <div class="lg:hidden flex flex-col items-center mb-8 gap-3">
            <img src="{{ $logoUrl }}" alt="Logo" class="w-20 h-20 object-contain">
            @if ($iglesiaNombre)
                <p class="text-[#0F6E46] font-bold text-center uppercase tracking-wide text-base leading-tight">
                    {{ $iglesiaNombre }}
                </p>
            @endif
        </div>

        {{-- Formulario --}}
        <div class="w-full max-w-sm">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
