@extends('layouts.app')

@section('title', 'Nueva Religión')

@section('content')
<div class="container-fluid max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Nueva Religión
        </h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('religion.store') }}" method="POST">
            @csrf

            {{-- Religión --}}
            <div>
                <label for="religion"
                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Religión *
                </label>

                <input
                    type="text"
                    name="religion"
                    id="religion"
                    value="{{ old('religion') }}"
                    required
                    autofocus
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                           focus:ring-blue-500 focus:border-blue-500
                           dark:bg-gray-700 dark:text-white
                           @error('religion') border-red-500 @enderror"
                >

                @error('religion')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('religion.index') }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600
                          text-gray-700 dark:text-gray-200
                          rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500
                          transition-colors duration-200">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white
                               rounded-lg hover:bg-blue-700
                               transition-colors duration-200">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
