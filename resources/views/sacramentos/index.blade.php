@extends('layouts.app')

@section('content')
<div class="content-container space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sacramentos</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Gestiona bautismos, matrimonios, confirmaciones y primera comunión</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @can('bautismo.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-sky-100 dark:bg-sky-900/30 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bautismos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Administra los registros de bautismo</p>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ \App\Models\Bautismo::count() }} registros</span>
                <a href="{{ route('bautismo.index') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    Gestionar
                </a>
            </div>
        </div>
        @endcan

        @can('matrimonio.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Matrimonios</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Administra los registros de matrimonio</p>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ \App\Models\Matrimonio::count() }} registros</span>
                <a href="{{ route('matrimonio.index') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    Gestionar
                </a>
            </div>
        </div>
        @endcan

        @can('confirmacion.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirmación</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Administra los registros de confirmación</p>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ \App\Models\Confirmacion::count() }} registros</span>
                <a href="{{ route('confirmacion.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    Gestionar
                </a>
            </div>
        </div>
        @endcan

        @can('primera-comunion.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Primera Comunión</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Administra los registros de primera comunión</p>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ \App\Models\PrimeraComunion::count() }} registros</span>
                <a href="{{ route('primera-comunion.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    Gestionar
                </a>
            </div>
        </div>
        @endcan

    </div>
</div>
@endsection
