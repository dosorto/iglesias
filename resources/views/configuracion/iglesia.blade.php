@extends('layouts.app')

@section('title', 'Configuración de la Iglesia')

@section('content')
<div class="content-container space-y-6 max-w-4xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración de la Iglesia</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Actualiza los datos generales y encabezado de documentos de la iglesia activa.</p>
        </div>

        <a href="{{ route('settings.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a Configuración
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('configuracion.iglesia.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre de la Iglesia <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       value="{{ old('nombre', $iglesia->nombre) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Dirección <span class="text-red-500">*</span>
                </label>
                <textarea name="direccion"
                          id="direccion"
                          rows="4"
                          required
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror">{{ old('direccion', $iglesia->direccion) }}</textarea>
                @error('direccion')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="header_diocesis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Diócesis (Encabezado)
                </label>
                <input type="text"
                       name="header_diocesis"
                       id="header_diocesis"
                       value="{{ old('header_diocesis', $iglesia->header_diocesis) }}"
                       placeholder="Ej. Diócesis de Choluteca"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('header_diocesis') border-red-500 @enderror">
                @error('header_diocesis')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    El lugar del encabezado se toma automáticamente de la Dirección.
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('settings.index') }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
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
</div>
@endsection