@extends('layouts.app')

@section('title', 'Nuevo Instructor')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Registrar Nuevo Instructor</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('instructor.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                {{-- Feligrés --}}
                <div>
                    <label for="feligres_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Feligrés *</label>
                    <select name="feligres_id" id="feligres_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('feligres_id') border-red-500 @enderror">
                        <option value="">Seleccione un feligrés...</option>
                        @foreach($feligres as $item)
                            <option value="{{ $item->id }}" {{ old('feligres_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->persona->nombre_completo }} — {{ $item->persona->dni }} ({{ $item->iglesia->nombre ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('feligres_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @if($feligres->isEmpty())
                        <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">No hay feligreses disponibles sin instructor asignado.</p>
                    @endif
                </div>

                {{-- Firma --}}
                <div>
                    <label for="path_firma" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ruta de Firma *</label>
                    <input type="text" name="path_firma" id="path_firma"
                           value="{{ old('path_firma') }}"
                           placeholder="ej. firmas/instructor.png"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('path_firma') border-red-500 @enderror">
                    @error('path_firma')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('instructor.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200">
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