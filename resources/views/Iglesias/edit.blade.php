@extends('layouts.app')

@section('title', 'Editar Iglesia')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Iglesia: {{ $iglesia->nombre }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        {{-- Asegúrate de que la variable se llame $iglesia y la ruta sea iglesias.update --}}
        <form action="{{ route('iglesias.update', $iglesia) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre de la Iglesia --}}
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre de la Iglesia *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $iglesia->nombre) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre del Párroco --}}
                <div>
                    <label for="parroco_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del Párroco *</label>
                    <input type="text" name="parroco_nombre" id="parroco_nombre" value="{{ old('parroco_nombre', $iglesia->parroco_nombre) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('parroco_nombre') border-red-500 @enderror">
                    @error('parroco_nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                    <select name="estado" id="estado" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="Activo" {{ old('estado', $iglesia->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado', $iglesia->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $iglesia->telefono) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $iglesia->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección *</label>
                    <textarea name="direccion" id="direccion" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('direccion') border-red-500 @enderror">{{ old('direccion', $iglesia->direccion) }}</textarea>
                    @error('direccion')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('iglesias.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualizar Iglesia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection