@extends('layouts.app')

@section('content')
<div class="flex justify-center pt-8 px-4">
    <div class="w-full max-w-xs sm:max-w-sm md:max-w-md">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 text-center border border-gray-200 dark:border-gray-700">
            <div class="mb-4">
                <svg class="w-12 h-12 text-yellow-500 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                Acceso Denegado
            </h1>

            <p class="text-base text-gray-600 dark:text-gray-300 mb-4">
                No tienes los permisos necesarios para acceder a esta sección.
            </p>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mb-4">
                <div class="flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-left">
                        <p class="text-xs font-medium text-yellow-800 dark:text-yellow-200">
                            Permisos insuficientes
                        </p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300">
                            Contacta al administrador para solicitar acceso.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-8 8a1 1 0 011.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l2.293 2.293a1 1 0 001.414-1.414l-8-8z"></path>
                    </svg>
                    Ir al Dashboard
                </a>

                <button onclick="history.back()"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Volver Atrás
                </button>
            </div>
        </div>
    </div>
</div>
@endsection