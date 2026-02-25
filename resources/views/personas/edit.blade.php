@extends('layouts.app')

@section('title', 'Editar Persona')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Persona</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('personas.update', $persona) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- DNI --}}
                <div>
                    <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">DNI *</label>
                    <input type="text" name="dni" id="dni" value="{{ old('dni', $persona->dni) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('dni') border-red-500 @enderror">
                    @error('dni')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Primer Nombre --}}
                <div>
                    <label for="primer_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primer Nombre *</label>
                    <input type="text" name="primer_nombre" id="primer_nombre" value="{{ old('primer_nombre', $persona->primer_nombre) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('primer_nombre') border-red-500 @enderror">
                    @error('primer_nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Segundo Nombre --}}
                <div>
                    <label for="segundo_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre" value="{{ old('segundo_nombre', $persona->segundo_nombre) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('segundo_nombre') border-red-500 @enderror">
                    @error('segundo_nombre')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Primer Apellido --}}
                <div>
                    <label for="primer_apellido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primer Apellido *</label>
                    <input type="text" name="primer_apellido" id="primer_apellido" value="{{ old('primer_apellido', $persona->primer_apellido) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('primer_apellido') border-red-500 @enderror">
                    @error('primer_apellido')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Segundo Apellido --}}
                <div>
                    <label for="segundo_apellido" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" value="{{ old('segundo_apellido', $persona->segundo_apellido) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('segundo_apellido') border-red-500 @enderror">
                    @error('segundo_apellido')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Nacimiento --}}
                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('fecha_nacimiento') border-red-500 @enderror">
                    @error('fecha_nacimiento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sexo --}}
                <div>
                    <label for="sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexo *</label>
                    <select name="sexo" id="sexo" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('sexo') border-red-500 @enderror">
                        <option value="">Seleccione...</option>
                        <option value="M" {{ old('sexo', $persona->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo', $persona->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('sexo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $persona->telefono) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $persona->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('personas.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
