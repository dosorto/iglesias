@extends('layouts.app')

@section('title', 'Editar Tipo de Curso')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Tipo de Curso</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('tipocurso.update', $tipocurso) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre del Curso --}}
                <div class="md:col-span-2">
                    <label for="nombre_curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del Curso *</label>
                    <input type="text" name="nombre_curso" id="nombre_curso" value="{{ old('nombre_curso', $tipocurso->nombre_curso) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nombre_curso') border-red-500 @enderror">
                    @error('nombre_curso')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción del Curso --}}
                <div class="md:col-span-2">
                    <label for="descripcion_curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción del Curso</label>
                    <textarea name="descripcion_curso" id="descripcion_curso" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descripcion_curso') border-red-500 @enderror">{{ old('descripcion_curso', $tipocurso->descripcion_curso) }}</textarea>
                    @error('descripcion_curso')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado del Curso --}}
                <div>
                    <label for="estado_curso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado *</label>
                    <select name="estado_curso" id="estado_curso" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('estado_curso') border-red-500 @enderror">
                        <option value="">Seleccione...</option>
                        <option value="activo" {{ old('estado_curso', $tipocurso->estado_curso) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado_curso', $tipocurso->estado_curso) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado_curso')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('tipocurso.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
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
