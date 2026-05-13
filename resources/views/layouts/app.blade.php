<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="shortcut icon" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="apple-touch-icon" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[var(--color-blanco-liturgico)] dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

    {{-- Main Layout Container --}}
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar (Sibiling) --}}
        @livewire('layouts.sidebar-toggle')

        {{-- Content Area (Wrapper) --}}
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden bg-[var(--color-marfil-calido)] dark:bg-gray-900">
            {{-- Navbar (Inside Wrapper) --}}
            @include('layouts.navbar')

            {{-- Main Content --}}
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>



    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.js"></script>
    
    <script>
        // Sincronizar rotación de flecha con estado del sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const arrow = document.getElementById('sidebar-arrow');
            
            // Verificar estado inicial
            function updateArrow() {
                const isCollapsed = sidebar.getAttribute('data-collapsed') === 'true';
                if (isCollapsed) {
                    arrow.classList.remove('rotate-180');
                } else {
                    arrow.classList.add('rotate-180');
                }
            }
            
            // Actualizar al cargar
            updateArrow();
            
            // Observar cambios en el atributo data-collapsed
            const observer = new MutationObserver(updateArrow);
            observer.observe(sidebar, {
                attributes: true,
                attributeFilter: ['data-collapsed']
            });
        });
    </script>

</body>
</html>
