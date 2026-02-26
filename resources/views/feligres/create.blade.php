@extends('layouts.app')

@section('title', 'Nuevo Feligrés')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Registrarr Nuevo Feligrés</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('feligres.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Persona --}}
                <div class="md:col-span-2">
                    <label for="id_persona" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Persona *</label>
                    <select name="id_persona" id="id_persona" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('id_persona') border-red-500 @enderror">
                        <option value="">Seleccione una persona...</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->id }}" {{ old('id_persona') == $persona->id ? 'selected' : '' }}>
                                {{ $persona->nombre_completo }} — {{ $persona->dni }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_persona')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Iglesia --}}
                <div class="md:col-span-2">
                    <label for="id_iglesia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Iglesia *</label>
                    <select name="id_iglesia" id="id_iglesia" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('id_iglesia') border-red-500 @enderror">
                        <option value="">Seleccione una iglesia...</option>
                        @foreach($iglesias as $iglesia)
                            <option value="{{ $iglesia->id }}" {{ old('id_iglesia') == $iglesia->id ? 'selected' : '' }}>
                                {{ $iglesia->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_iglesia')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Ingreso --}}
                <div>
                    <label for="fecha_ingreso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de Ingreso</label>
                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" value="{{ old('fecha_ingreso') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_ingreso') border-red-500 @enderror">
                    @error('fecha_ingreso')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                    <select name="estado" id="estado" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado') border-red-500 @enderror">
                        <option value="Activo" {{ old('estado', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('feligres.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
