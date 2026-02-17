@extends('layouts.app')

@section('title', 'Detalle de Religión')

@section('content')
<div class="container-fluid max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                Detalle de Religión
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Información detallada de la religión.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('religion.index') }}" 
               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg 
                      hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                Volver
            </a>
            @can('religiones.edit')
                <a href="{{ route('religion.edit', $religion) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 
                          transition-colors duration-200 text-sm font-medium">
                    Editar
                </a>
            @endcan
        </div>
    </div>

    {{-- Detalle de Religión --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                Información General
            </h2>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-tighter block">
                    Religión
                </label>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $religion->religion }}</p>
            </div>
        </div>

        {{-- Timestamps --}}
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
            <div class="text-[10px] text-gray-400 flex flex-col gap-1">
                <span>Creado: {{ $religion->created_at->format('d/m/Y H:i') }} por {{ $religion->creator->name ?? 'Sistema' }}</span>
                <span>Actualizado: {{ $religion->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
