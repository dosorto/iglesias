@if (session()->has('error'))
    <div class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 px-3 py-2 rounded-lg text-xs mb-4">
        {{ session('error') }}
    </div>
@endif

@if (session()->has('success_estado'))
    <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 px-3 py-2 rounded-lg text-xs mb-4">
        {{ session('success_estado') }}
    </div>
@endif

<div class="flex flex-col xl:flex-row gap-5 items-start">

    {{-- SIDEBAR --}}
    <aside class="w-full xl:w-64 shrink-0 space-y-4">

        {{-- ACCIONES --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">
                Acciones
            </p>

            <div class="space-y-2">
                @can('inscripcion-curso.edit')
                    <a href="{{ route('inscripcion-curso.edit', $inscripcion) }}"
                       class="flex items-center w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar inscripción
                    </a>
                @endcan

                @can('inscripcion-curso.edit')
                    @if($inscripcion->aprobado)
                        <button type="button"
                                wire:click="imprimirCertificado"
                                class="flex items-center w-full bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2h-2m-10 8H5a2 2 0 01-2-2v-4a2 2 0 012-2h2m10-4V3H7v4m10 8H7v6h10v-6z"/>
                            </svg>
                            Imprimir certificado
                        </button>
                    @else
                        <button type="button"
                                disabled
                                class="flex items-center w-full bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-3 py-2 rounded-lg text-sm font-semibold cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2h-2m-10 8H5a2 2 0 01-2-2v-4a2 2 0 012-2h2m10-4V3H7v4m10 8H7v6h10v-6z"/>
                            </svg>
                            Imprimir certificado
                        </button>
                    @endif
                @endcan

                @can('inscripcion-curso.delete')
                    <button type="button"
                            wire:click="confirmarQuitar"
                            class="flex items-center w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                        </svg>
                        Quitar matriculado
                    </button>
                @endcan
            </div>
        </div>

        {{-- ESTADO --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">
                Estado del Matriculado
            </p>

            <div class="space-y-4">

                <div>
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <p class="text-xs text-gray-400 dark:text-gray-500">Aprobado</p>

                        @if($inscripcion->aprobado)
                            <button type="button"
                                    wire:click="quitarAprobacion"
                                    class="text-[11px] px-2.5 py-1 rounded-md bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition">
                                Quitar
                            </button>
                        @else
                            <button type="button"
                                    wire:click="aprobar"
                                    class="text-[11px] px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50 transition">
                                Aprobar
                            </button>
                        @endif
                    </div>

                    @if($inscripcion->aprobado)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                            Sí
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            No
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Certificado</p>
                    @if($inscripcion->certificado_emitido)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            Emitido
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            No emitido
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Fecha certificado</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        {{ optional($inscripcion->fecha_certificado)->format('d/m/Y') ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- HISTORIAL --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-3">
                Historial
            </p>

            @if($inscripcion->auditLogs?->count())
                <div class="space-y-3">
                    @foreach($inscripcion->auditLogs->take(5) as $log)
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $log->event === 'created' ? 'Creado' : ($log->event === 'updated' ? 'Editado' : 'Eliminado') }}
                                </p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ optional($log->created_at)->format('d/m/Y H:i') ?? 'N/A' }}
                                </p>
                            </div>

                            <span class="text-[10px] uppercase font-medium px-1.5 py-0.5 rounded
                                {{ $log->event === 'created'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                    : ($log->event === 'updated'
                                        ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                {{ $log->event }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                    Sin historial disponible.
                </p>
            @endif
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="flex-1 min-w-0 space-y-4">

        {{-- RESUMEN --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Detalle del Matriculado
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $inscripcion->feligres?->persona?->nombre_completo ?? 'N/A' }}
                    </p>
                </div>

                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300">
                    #{{ $inscripcion->id }}
                </span>
            </div>
        </div>

        {{-- DATOS DEL FELIGRÉS --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">
                Datos del Feligrés
            </p>

            <div class="flex items-center gap-3 p-3 rounded-lg bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-700/40">
                <div class="w-10 h-10 rounded-full bg-sky-600 flex items-center justify-center shrink-0">
                    <span class="text-white text-sm font-bold">
                        {{ strtoupper(substr($inscripcion->feligres?->persona?->primer_nombre ?? '?', 0, 1)) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ $inscripcion->feligres?->persona?->nombre_completo ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        DNI: {{ $inscripcion->feligres?->persona?->dni ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- DATOS DEL CURSO --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-4">
                Datos del Curso
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-3 rounded-lg border bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-700/40">
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">
                        Curso
                    </p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                        {{ $inscripcion->curso?->nombre ?? 'N/A' }}
                    </p>
                </div>

                <div class="p-3 rounded-lg border bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-700/40">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                        Fecha inscripción
                    </p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                        {{ optional($inscripcion->fecha_inscripcion)->format('d/m/Y') ?? 'N/A' }}
                    </p>
                </div>

                <div class="p-3 rounded-lg border bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-700/40">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                        Instructor
                    </p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                        {{ $inscripcion->curso?->instructor?->feligres?->persona?->nombre_completo ?? 'N/A' }}
                    </p>
                </div>

                <div class="p-3 rounded-lg border bg-violet-50 dark:bg-violet-900/20 border-violet-200 dark:border-violet-700/40">
                    <p class="text-xs font-semibold uppercase tracking-wide text-violet-600 dark:text-violet-400">
                        Encargado
                    </p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                        {{ $inscripcion->curso?->encargado?->feligres?->persona?->nombre_completo ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- VISTA PREVIA CERTIFICADO --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                    Vista del Certificado
                </p>

                @if($inscripcion->aprobado)
                    <a href="{{ route('inscripcion-curso.certificado.pdf', $inscripcion) }}"
                       target="_blank"
                       class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors">
                        Abrir certificado
                    </a>
                @endif
            </div>

            @if($inscripcion->aprobado)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-50 dark:bg-gray-900">
                    <iframe
                        src="{{ route('inscripcion-curso.certificado.pdf', $inscripcion) }}"
                        class="w-full h-[850px]"
                        title="Vista previa certificado de curso">
                    </iframe>
                </div>
            @else
                <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 p-8 text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        El certificado estará disponible cuando la inscripción esté aprobada.
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Confirmar eliminación
                    </h3>
                </div>

                <div class="px-6 py-5">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        ¿Seguro que deseas quitar este matriculado del curso?
                    </p>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button type="button"
                            wire:click="cancelarQuitar"
                            class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium">
                        Cancelar
                    </button>

                    <button type="button"
                            wire:click="quitar"
                            class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium">
                        Sí, quitar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>