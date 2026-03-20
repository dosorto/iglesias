@extends('layouts.app')

@section('content')
<!-- Encabezado de Bienvenida -->
<div class="mb-10">
    <h1 class="text-4xl font-serif text-gray-900 dark:text-white">Bienvenido al Archivo Sagrado</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-2 font-body max-w-2xl italic">"Custodiando la fe y la historia de nuestra comunidad parroquial con reverencia y orden."</p>
</div>

<!-- Cuadrícula Bento: Métricas Principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    <!-- Tarjeta de Feligreses Activos -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-b-4 border-purple-200 dark:border-purple-800">
        <div class="flex justify-between items-start mb-4">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
        </div>
        <h3 class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ \App\Models\Feligres::count() }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-body">Feligreses Activos</p>
    </div>

    <!-- Tarjeta de Bautismos -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-b-4 border-sky-200 dark:border-sky-800">
        <div class="flex justify-between items-start mb-4">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </span>
        </div>
        <h3 class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ \App\Models\Bautismo::count() }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-body">Bautismos (Año)</p>
    </div>

    <!-- Tarjeta de Matrimonios -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-b-4 border-rose-200 dark:border-rose-800">
        <div class="flex justify-between items-start mb-4">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </span>
        </div>
        <h3 class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ \App\Models\Matrimonio::count() }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-body">Matrimonios</p>
    </div>

    <!-- Tarjeta de Inscritos en Cursos -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-b-4 border-blue-200 dark:border-blue-800">
        <div class="flex justify-between items-start mb-4">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-5.747-8.12l11.494 4.373M6.253 14.373l11.494-4.373"/>
                </svg>
            </span>
        </div>
        <h3 class="text-3xl font-serif font-bold text-gray-900 dark:text-white">{{ \App\Models\InscripcionCurso::count() }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-body">Inscritos en Cursos</p>
    </div>
</div>

<!-- Diseño del Dashboard: Actividad y Próximos -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Área del Gráfico de Actividad Principal -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Sección de Actividad Sacramental -->
        <section class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-serif text-gray-900 dark:text-white">Actividad Sacramental</h2>
                <div class="flex gap-2">
                    <button class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-bold text-purple-600 dark:text-purple-400">Mensual</button>
                    <button class="px-3 py-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-400">Anual</button>
                </div>
            </div>
            <div class="h-64 flex items-end gap-4 px-4 overflow-hidden relative">
                <div class="flex-1 bg-purple-200 dark:bg-purple-900/30 rounded-t-lg h-32 relative group hover:bg-purple-300 dark:hover:bg-purple-900/50 transition-all">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100">12</div>
                </div>
                <div class="flex-1 bg-purple-300 dark:bg-purple-800/50 rounded-t-lg h-48 relative group hover:bg-purple-400 dark:hover:bg-purple-800 transition-all">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100">18</div>
                </div>
                <div class="flex-1 bg-purple-400 dark:bg-purple-800 rounded-t-lg h-36 relative group hover:bg-purple-500 dark:hover:bg-purple-700 transition-all">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100">14</div>
                </div>
                <div class="flex-1 bg-purple-600 dark:bg-purple-700 rounded-t-lg h-56 relative group hover:bg-purple-700 dark:hover:bg-purple-600 transition-all shadow-lg shadow-purple-600/20">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100">24</div>
                </div>
                <div class="flex-1 bg-purple-400 dark:bg-purple-800 rounded-t-lg h-40 relative group hover:bg-purple-500 dark:hover:bg-purple-700 transition-all">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100">16</div>
                </div>
                <div class="flex-1 bg-purple-200 dark:bg-purple-900/30 rounded-t-lg h-24 relative group hover:bg-purple-300 dark:hover:bg-purple-900/50 transition-all">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100">8</div>
                </div>
            </div>
            <div class="flex justify-between mt-4 px-4 text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">
                <span>Ene</span>
                <span>Feb</span>
                <span>Mar</span>
                <span>Abr</span>
                <span>May</span>
                <span>Jun</span>
            </div>
        </section>

        <!-- Sección de Acciones Rápidas -->
        <section>
            <h2 class="text-xl font-serif text-gray-900 dark:text-white mb-6 px-2">Acciones Rápidas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('personas.index') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl hover:bg-purple-600 hover:text-white dark:hover:bg-purple-700 transition-all group border border-gray-200 dark:border-gray-700 hover:border-purple-600 dark:hover:border-purple-500">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 group-hover:bg-white/20 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-gray-900 dark:text-white group-hover:text-white">Nuevo Registro</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-white/70">Añadir feligrés al censo</p>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl hover:bg-blue-600 hover:text-white dark:hover:bg-blue-700 transition-all group border border-gray-200 dark:border-gray-700 hover:border-blue-600 dark:hover:border-blue-500">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 group-hover:bg-white/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-gray-900 dark:text-white group-hover:text-white">Certificado Digital</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-white/70">Emitir fe de bautismo</p>
                    </div>
                </a>
            </div>
        </section>
    </div>

    <!-- Barra Lateral Derecha: Nuevos Feligreses y Actividad Reciente -->
    <div class="space-y-8">
        <!-- Sección de Nuevos Feligreses -->
        <section class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-serif text-gray-900 dark:text-white">Nuevos Feligreses</h2>
                <a href="{{ route('feligres.create') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-600 dark:bg-purple-700 text-white hover:bg-purple-700 dark:hover:bg-purple-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
            <div class="space-y-6">
                <!-- Elementos de Feligreses -->
                @forelse(\App\Models\Feligres::with('persona')->latest()->take(3)->get() as $feligres)
                <a href="{{ route('feligres.show', $feligres->id) }}" class="flex items-center gap-4 group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 p-2 rounded-lg transition-colors">
                    <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white dark:group-hover:bg-purple-600 transition-all flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 dark:text-white truncate">{{ $feligres->persona->primer_nombre ?? 'Sin nombre' }} {{ $feligres->persona->primer_apellido ?? '' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $feligres->created_at?->format('d M') ?? 'Fecha no disponible' }}</p>
                    </div>
                </a>
                @empty
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay feligreses nuevos</p>
                </div>
                @endforelse
            </div>
            <a href="{{ route('feligres.index') }}" class="w-full mt-8 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-purple-600 dark:hover:text-purple-400 transition-all block text-center">
                Ver Todos los Registros
            </a>
        </section>

        <!-- Sección de Actividad Reciente -->
        <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm overflow-hidden">
            <h2 class="text-lg font-serif text-gray-900 dark:text-white mb-4">Ingresos Recientes</h2>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H2a1 1 0 00-1 1v17a1 1 0 001 1h5a1 1 0 000-2H4V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">Nueva Acta de Matrimonio</p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Hace 2 horas • Folio #452</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">Registro de Feligrés: Elena Solís</p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500">Hace 4 horas • ID: 1209</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
