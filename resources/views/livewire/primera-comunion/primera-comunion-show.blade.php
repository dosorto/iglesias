<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalle de Primera Comunión</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa del registro</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('primera-comunion.edit')
                <a href="{{ route('primera-comunion.edit', $primeraComunion) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            @endcan
            <a href="{{ route('primera-comunion.index') }}"
               class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Datos del evento --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                Datos de la Primera Comunión
            </h2>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Iglesia</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $primeraComunion->iglesia?->nombre ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Fecha</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $primeraComunion->fecha_primera_comunion?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Libro</dt>
                    <dd class="text-sm font-mono text-gray-900 dark:text-white">{{ $primeraComunion->libro_comunion ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Folio</dt>
                    <dd class="text-sm font-mono text-gray-900 dark:text-white">{{ $primeraComunion->folio ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Partida N°</dt>
                    <dd class="text-sm font-mono text-gray-900 dark:text-white">{{ $primeraComunion->partida_numero ?? '—' }}</dd>
                </div>
                @if($primeraComunion->observaciones)
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Observaciones</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $primeraComunion->observaciones }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Personas --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                Personas Involucradas
            </h2>
            <dl class="space-y-3">
                @foreach ([
                    'Comulgante' => $primeraComunion->feligres,
                    'Catequista' => $primeraComunion->catequista,
                    'Ministro'   => $primeraComunion->ministro,
                    'Párroco'    => $primeraComunion->parroco,
                ] as $rol => $feligres)
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $rol }}</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white text-right">
                            @if($feligres?->persona)
                                <span>{{ $feligres->persona->nombre_completo }}</span>
                                <span class="block text-xs font-mono text-gray-400">{{ $feligres->persona->dni }}</span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>

</div>