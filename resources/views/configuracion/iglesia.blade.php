@extends('layouts.app')

@section('title', 'Configuración de la Parroquia')

@section('content')
@php
    $logoUrl    = $iglesia->logo_url    ?? asset('image/Logo_guest.png');
    $logoDerUrl = $iglesia->logo_derecha_url ?? null;
@endphp
<div class="content-container space-y-6 max-w-5xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración de la Parroquia</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Estos datos aparecen en el encabezado de certificados y en la pantalla de inicio de sesión.</p>
        </div>

        <a href="{{ route('settings.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a Configuración
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        {{-- ── Formulario ── --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-5">Datos de la parroquia</h2>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('configuracion.iglesia.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre de la Parroquia <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           value="{{ old('nombre', $iglesia->nombre) }}"
                           required
                           placeholder="Ej: Espíritu Santo"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Diócesis --}}
                <div>
                    <label for="header_diocesis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Diócesis / Subtítulo
                    </label>
                    <input type="text"
                           name="header_diocesis"
                           id="header_diocesis"
                           value="{{ old('header_diocesis', $iglesia->header_diocesis) }}"
                           placeholder="Ej: Diócesis de Choluteca"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm @error('header_diocesis') border-red-500 @enderror">
                    @error('header_diocesis')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dirección <span class="text-red-500">*</span>
                    </label>
                    <textarea name="direccion"
                              id="direccion"
                              rows="2"
                              required
                              placeholder="Ej: Monjarás, Marcovia, Choluteca"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm @error('direccion') border-red-500 @enderror">{{ old('direccion', $iglesia->direccion) }}</textarea>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Logos --}}
                <div class="pt-1 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logos</p>
                    <div class="flex items-center gap-3 flex-wrap">
                        <img src="{{ $logoUrl }}" alt="Logo" class="w-12 h-12 object-contain rounded border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 p-1">
                        @if ($logoDerUrl && $logoDerUrl !== $logoUrl)
                            <img src="{{ $logoDerUrl }}" alt="Logo derecha" class="w-12 h-12 object-contain rounded border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 p-1">
                        @endif
                        <a href="{{ route('iglesia.logo') }}"
                           class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            Cambiar logos →
                        </a>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('settings.index') }}"
                       class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 text-sm transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Vista previa del login ── --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                Vista previa de la pantalla de inicio de sesión
            </h2>

            <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 flex" style="min-height:260px; font-family: ui-sans-serif, system-ui, sans-serif;">

                {{-- Panel verde --}}
                <div class="flex flex-col items-center justify-center gap-3 px-5 py-6 text-center"
                     style="width:58%; background-color:#0F6E46; position:relative; overflow:hidden;">

                    {{-- Logos --}}
                    <div class="flex items-center justify-center gap-4">
                        <img src="{{ $logoUrl }}" alt="Logo"
                             style="width:52px; height:52px; object-fit:contain; filter:drop-shadow(0 2px 6px rgba(0,0,0,.3));">
                        @if ($logoDerUrl && $logoDerUrl !== $logoUrl)
                            <img src="{{ $logoDerUrl }}" alt="Logo"
                                 style="width:52px; height:52px; object-fit:contain; filter:drop-shadow(0 2px 6px rgba(0,0,0,.3));">
                        @endif
                    </div>

                    @if ($iglesia->nombre)
                        <div>
                            <p style="color:#fff; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; line-height:1.3;">
                                {{ $iglesia->nombre }}
                            </p>
                            @if ($iglesia->header_diocesis)
                                <p style="color:#a7f3d0; font-size:9px; text-transform:uppercase; letter-spacing:.8px; margin-top:3px;">
                                    {{ $iglesia->header_diocesis }}
                                </p>
                            @endif
                            @if ($iglesia->direccion)
                                <p style="color:#6ee7b7; font-size:8px; margin-top:2px;">
                                    {{ $iglesia->direccion }}
                                </p>
                            @endif
                        </div>
                    @else
                        <p style="color:#fff; font-size:11px; font-weight:700;">Sistema de Gestión Parroquial</p>
                    @endif

                    <div style="width:32px; height:2px; background:#34d399; border-radius:9px; opacity:.6;"></div>

                    <p style="color:rgba(209,250,229,.65); font-size:7.5px; max-width:140px; line-height:1.5;">
                        Gestione bautismos, confirmaciones, matrimonios y primera comunión.
                    </p>
                </div>

                {{-- Panel blanco (formulario simulado) --}}
                <div class="flex flex-col items-center justify-center gap-2 px-4" style="flex:1; background:#fff;">
                    <div style="width:100%; max-width:120px;">
                        <div style="height:8px; background:#e5e7eb; border-radius:4px; margin-bottom:10px;"></div>
                        <div style="height:26px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:6px; margin-bottom:6px;"></div>
                        <div style="height:26px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:6px; margin-bottom:10px;"></div>
                        <div style="height:26px; background:#0F6E46; border-radius:6px;"></div>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3 text-center">
                Los cambios se reflejan al guardar. Los logos se gestionan en la sección de logos.
            </p>
        </div>

    </div>

    {{-- ── Imagen de fondo del login ── --}}
    @if($isTenantActive ?? false)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Imagen de fondo del login</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
            Se muestra detrás del panel verde en la pantalla de inicio de sesión. Recomendado: imagen de la parroquia o fondo decorativo.
        </p>
        @livewire('iglesia.login-background-update')
    </div>
    @endif

</div>
@endsection
