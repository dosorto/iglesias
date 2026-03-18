<div class="space-y-6">

    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-sky-600 to-blue-600
                dark:from-sky-700 dark:to-blue-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                 C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                 C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                 C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Registrar Nuevo Curso</h1>
                    <p class="text-sky-100 text-sm mt-0.5">Completa los pasos para registrar un curso en el sistema.</p>
                </div>
            </div>

            <a href="{{ route('curso.index') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-white/15 hover:bg-white/25 border border-white/20
                      text-white text-sm font-medium transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between relative">
            <div class="absolute top-4 left-0 w-full h-[2px] bg-gray-200 dark:bg-gray-600"></div>

            @foreach ([1 => 'Curso', 2 => 'Tipo Curso', 3 => 'Instructor'] as $n => $label)
                <div class="relative flex flex-col items-center w-full">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold z-10
                        {{ $paso == $n ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900' : ($paso > $n ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400') }}">
                        @if($paso > $n)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $n }}
                        @endif
                    </div>
                    <span class="text-xs mt-2 font-medium {{ $paso == $n ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $label }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    @if($paso === 1)
        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                    ring-1 ring-black/5 dark:ring-white/5">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                             bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300
                             text-xs font-bold ring-2 ring-blue-200 dark:ring-blue-700/50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                    Datos del Curso
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                            Nombre del Curso <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="nombre"
                               placeholder="Ej: Discipulado 2026…"
                               autocomplete="off"
                               class="block w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                      border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60
                                      text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('nombre') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                        @error('nombre')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                            Fecha Inicio
                        </label>
                        <input type="date"
                               wire:model="fecha_inicio"
                               class="block w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                      border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60
                                      text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                            Fecha Fin
                        </label>
                        <input type="date"
                               wire:model="fecha_fin"
                               class="block w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                      border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60
                                      text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                </div>
            </div>
        </div>
    @endif

    @if($paso === 2)
        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                    ring-1 ring-black/5 dark:ring-white/5">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                             bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300
                             text-xs font-bold ring-2 ring-blue-200 dark:ring-blue-700/50">2</span>
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                    Tipo de Curso
                </h2>
            </div>

            <div class="p-6 space-y-4">
                @if($tipo_curso_id)
                    <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-green-800 dark:text-green-200">{{ $buscar_tipo_curso }}</p>
                        </div>
                        <button type="button" wire:click="resetTipoCurso"
                                class="text-xs font-medium text-green-700 dark:text-green-300 hover:text-green-900 dark:hover:text-green-100 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cambiar
                        </button>
                    </div>
                @else
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                            Buscar tipo de curso
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg wire:loading.remove wire:target="buscar_tipo_curso"
                                     class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <svg wire:loading wire:target="buscar_tipo_curso"
                                     class="h-4 w-4 text-blue-500 dark:text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   wire:model.live.debounce.300ms="buscar_tipo_curso"
                                   placeholder="Escribe al menos 2 caracteres…"
                                   autocomplete="off"
                                   class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60
                                          text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500
                                          focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>

                        @error('buscar_tipo_curso')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        @if(strlen(trim($buscar_tipo_curso)) >= 2)
                            @if(count($tipoCursoResultados) > 0)
                                <div class="mt-3 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach($tipoCursoResultados as $tipo)
                                            <li>
                                                <button type="button"
                                                        wire:click="seleccionarTipoCurso({{ $tipo->id }})"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-left
                                                               hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white font-bold text-xs">
                                                            {{ strtoupper(substr($tipo->nombre_curso, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $tipo->nombre_curso }}
                                                        </p>
                                                        @if($tipo->descripcion_curso)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                                {{ $tipo->descripcion_curso }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="mt-3 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-amber-800 dark:text-amber-300">
                                        No se encontró ningún tipo de curso con <strong>"{{ $buscar_tipo_curso }}"</strong>.
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($paso === 3)
        <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                    ring-1 ring-black/5 dark:ring-white/5">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                             bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300
                             text-xs font-bold ring-2 ring-blue-200 dark:ring-blue-700/50">3</span>
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                    Instructor
                </h2>
            </div>

            <div class="p-6 space-y-4">
                @if($instructor_id)
                    <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-green-800 dark:text-green-200">{{ $buscar_instructor }}</p>
                        </div>
                        <button type="button" wire:click="resetInstructor"
                                class="text-xs font-medium text-green-700 dark:text-green-300 hover:text-green-900 dark:hover:text-green-100 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cambiar
                        </button>
                    </div>
                @else
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                            Buscar instructor
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg wire:loading.remove wire:target="buscar_instructor"
                                     class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <svg wire:loading wire:target="buscar_instructor"
                                     class="h-4 w-4 text-blue-500 dark:text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   wire:model.live.debounce.300ms="buscar_instructor"
                                   placeholder="Escribe al menos 2 caracteres…"
                                   autocomplete="off"
                                   class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60
                                          text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500
                                          focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>

                        @error('instructor_id')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        @if(strlen(trim($buscar_instructor)) >= 2)
                            @if(count($instructorResultados) > 0)
                                <div class="mt-3 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach($instructorResultados as $inst)
                                            <li>
                                                <button type="button"
                                                        wire:click="seleccionarInstructor({{ $inst->id }})"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-left
                                                               hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white font-bold text-xs">
                                                            {{ strtoupper(substr($inst->feligres?->persona?->primer_nombre ?? '?', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $inst->feligres?->persona?->nombre_completo }}
                                                        </p>
                                                    </div>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="mt-3 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-amber-800 dark:text-amber-300">
                                        No se encontró ningún instructor con <strong>"{{ $buscar_instructor }}"</strong>.
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between">
        @if($paso > 1)
            <button wire:click="anteriorPaso"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                           text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Anterior
            </button>
        @else
            <div></div>
        @endif

        @if($paso < 3)
            <button wire:click="siguientePaso"
                    class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                           shadow-md shadow-blue-500/30 transition-all duration-150
                           bg-gradient-to-r from-sky-500 to-blue-600
                           hover:from-sky-600 hover:to-blue-700
                           active:scale-[0.98]
                           text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                           dark:focus:ring-offset-gray-800">
                Siguiente
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        @else
            <button wire:click="guardar"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                           shadow-md shadow-emerald-500/30 transition-all duration-150
                           bg-gradient-to-r from-emerald-500 to-emerald-600
                           hover:from-emerald-600 hover:to-emerald-700
                           active:scale-[0.98]
                           disabled:opacity-50 disabled:cursor-not-allowed
                           text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2
                           dark:focus:ring-offset-gray-800">
                <svg wire:loading wire:target="guardar" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                <svg wire:loading.remove wire:target="guardar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                <span wire:loading.remove wire:target="guardar">Guardar Curso</span>
                <span wire:loading wire:target="guardar">Guardando…</span>
            </button>
        @endif
    </div>

</div>