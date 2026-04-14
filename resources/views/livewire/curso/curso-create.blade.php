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
                <div class="relative flex flex-col-reverse items-center w-full">
                    <span class="text-xs mb-2 font-medium {{ $paso == $n ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $label }}
                    </span>

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
                </div>
            @endforeach
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-xl border border-emerald-200 dark:border-emerald-700/50
                    bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3">
            <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">
                {{ session('success') }}
            </p>
        </div>
    @endif

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
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('fecha_inicio') border-red-400 @enderror" />
                        @error('fecha_inicio')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('fecha_fin') border-red-400 @enderror" />
                        @error('fecha_fin')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Seleccionar tipo de curso <span class="text-red-500">*</span>
                    </label>

                    <select wire:model="tipo_curso_id"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60
                                   text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   @error('tipo_curso_id') border-red-400 @enderror">
                        <option value="">-- Selecciona un tipo de curso --</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre_curso }}</option>
                        @endforeach
                    </select>

                    @error('tipo_curso_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if(! $showCrearTipoCurso)
                    <button type="button"
                            wire:click="abrirCrearTipoCurso"
                            class="w-full py-2.5 text-sm font-semibold
                                   text-emerald-700 dark:text-emerald-300
                                   border border-emerald-300 dark:border-emerald-600 rounded-xl
                                   hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                        + Registrar nuevo tipo de curso
                    </button>
                @endif

                @if($showCrearTipoCurso)
                    <div class="rounded-xl border border-emerald-200 dark:border-emerald-700/50
                                bg-gradient-to-b from-emerald-50/80 to-transparent
                                dark:from-emerald-900/15 dark:to-transparent overflow-hidden">

                        <div class="flex items-center justify-between px-5 py-3
                                    border-b border-emerald-100 dark:border-emerald-800/40
                                    bg-emerald-50 dark:bg-emerald-900/20">
                            <h4 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                                Registrar nuevo tipo de curso
                            </h4>
                            <button type="button" wire:click="cancelarCrearTipoCurso"
                                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                           hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                    Nombre del tipo de curso <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model="nuevo_tipo_nombre"
                                       placeholder="Ej: Discipulado"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('nuevo_tipo_nombre') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                @error('nuevo_tipo_nombre')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                    Descripción
                                </label>
                                <textarea wire:model="nuevo_tipo_descripcion"
                                          rows="3"
                                          placeholder="Descripción opcional del tipo de curso..."
                                          class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                 border border-gray-300 dark:border-gray-600
                                                 bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none
                                                 @error('nuevo_tipo_descripcion') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror"></textarea>
                                @error('nuevo_tipo_descripcion')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end gap-3 pt-2 border-t border-emerald-100 dark:border-emerald-800/40">
                                <button type="button"
                                        wire:click="cancelarCrearTipoCurso"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                                               bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                               text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    Cancelar
                                </button>

                                <button type="button"
                                        wire:click="guardarNuevoTipoCurso"
                                        wire:loading.attr="disabled"
                                        wire:target="guardarNuevoTipoCurso"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                               shadow-md shadow-emerald-500/30 transition-all
                                               bg-gradient-to-r from-emerald-500 to-emerald-600
                                               hover:from-emerald-600 hover:to-emerald-700
                                               text-white disabled:opacity-60">
                                    <span wire:loading.remove wire:target="guardarNuevoTipoCurso">Guardar</span>
                                    <span wire:loading wire:target="guardarNuevoTipoCurso">Guardando...</span>
                                </button>
                            </div>
                        </div>
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
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Seleccionar instructor <span class="text-red-500">*</span>
                    </label>

                    <select wire:model="instructor_id"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60
                                   text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   @error('instructor_id') border-red-400 @enderror">
                        <option value="">-- Selecciona un instructor --</option>
                        @foreach($instructores as $inst)
                            <option value="{{ $inst->id }}">
                                {{ $inst->feligres?->persona?->nombre_completo ?? 'Instructor sin nombre' }}
                            </option>
                        @endforeach
                    </select>

                    @error('instructor_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if(! $showCrearInstructor)
                    <button type="button"
                            wire:click="abrirCrearInstructor"
                            class="w-full py-2.5 text-sm font-semibold
                                   text-emerald-700 dark:text-emerald-300
                                   border border-emerald-300 dark:border-emerald-600 rounded-xl
                                   hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                        + Registrar nuevo instructor
                    </button>
                @endif

                @if($showCrearInstructor)
                    <div class="rounded-xl border border-sky-200 dark:border-sky-700/50
                                bg-gradient-to-b from-sky-50/80 to-transparent
                                dark:from-sky-900/15 dark:to-transparent overflow-hidden">

                        <div class="flex items-center justify-between px-5 py-3
                                    border-b border-sky-100 dark:border-sky-800/40
                                    bg-sky-50 dark:bg-sky-900/20">
                            <h4 class="text-sm font-semibold text-sky-800 dark:text-sky-300">
                                Registrar nuevo instructor
                            </h4>
                            <button type="button" wire:click="cancelarCrearInstructor"
                                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                           hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="p-5 space-y-4">
                            @error('iglesia_id')
                                <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700">
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                </div>
                            @enderror

                            @if($instructor_estado === 'idle')
                                <div class="flex gap-3">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <input type="text"
                                               wire:model="instructor_busqueda"
                                               placeholder="Buscar por DNI o nombre..."
                                               autocomplete="off"
                                               wire:keydown.enter="buscarPersonaInstructor"
                                               class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-lg transition-colors
                                                      border border-gray-300 dark:border-gray-600
                                                      bg-gray-50 dark:bg-gray-700/60
                                                      text-gray-900 dark:text-white dark:placeholder-gray-400
                                                      focus:ring-2 focus:ring-sky-500 focus:border-transparent" />
                                    </div>

                                    <button type="button"
                                            wire:click="buscarPersonaInstructor"
                                            wire:loading.attr="disabled"
                                            wire:target="buscarPersonaInstructor"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                                                   bg-sky-600 hover:bg-sky-700 text-white shadow-sm transition-all disabled:opacity-60">
                                        <svg wire:loading.remove wire:target="buscarPersonaInstructor"
                                             class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <svg wire:loading wire:target="buscarPersonaInstructor"
                                             class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                        </svg>
                                        Buscar
                                    </button>
                                </div>

                                @error('instructor_busqueda')
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            @endif

                            @if($instructor_estado === 'multiples' && !empty($resultadosBusqueda))
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Se encontraron {{ count($resultadosBusqueda) }} personas. Selecciona una:
                                    </p>

                                    <div class="space-y-1.5 max-h-64 overflow-y-auto pr-1">
                                        @foreach ($resultadosBusqueda as $res)
                                            <button type="button"
                                                    wire:click="seleccionarPersonaInstructor({{ $res['id'] }})"
                                                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-left
                                                           border border-gray-200 dark:border-gray-600
                                                           bg-white dark:bg-gray-700/40
                                                           hover:bg-sky-50 dark:hover:bg-sky-900/20
                                                           hover:border-sky-300 dark:hover:border-sky-600
                                                           transition-all group">
                                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-600
                                                            flex items-center justify-center flex-shrink-0
                                                            group-hover:bg-sky-100 dark:group-hover:bg-sky-900/40">
                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                        {{ $res['nombre_completo'] }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        DNI: {{ $res['dni'] }}
                                                        @if (!empty($res['telefono']))
                                                            &middot; {{ $res['telefono'] }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 text-gray-300 group-hover:text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($instructor_estado === 'sin_feligres' && $personaInstructor)
                                <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20
                                            border border-amber-200 dark:border-amber-700/50 space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600
                                                    flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(substr($personaInstructor['nombre_completo'], 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate text-sm">
                                                {{ $personaInstructor['nombre_completo'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                DNI: {{ $personaInstructor['dni'] }}
                                            </p>
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                                         bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                                                Persona encontrada — no está registrada como feligrés
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-2 border-t border-amber-100 dark:border-amber-800/40">
                                        <button type="button"
                                                wire:click="cancelarCrearInstructor"
                                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                                                       bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                                       text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            Cancelar
                                        </button>

                                        <button type="button"
                                                wire:click="guardarInstructorDesdePersonaExistente"
                                                wire:loading.attr="disabled"
                                                wire:target="guardarInstructorDesdePersonaExistente"
                                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                                       shadow-md shadow-amber-500/30 transition-all
                                                       bg-gradient-to-r from-amber-500 to-orange-600
                                                       hover:from-amber-600 hover:to-orange-700
                                                       text-white disabled:opacity-60">
                                            <span wire:loading.remove wire:target="guardarInstructorDesdePersonaExistente">
                                                Registrar como instructor
                                            </span>
                                            <span wire:loading wire:target="guardarInstructorDesdePersonaExistente">
                                                Guardando...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if($instructor_estado === 'found' && $personaInstructor)
                                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20
                                            border border-emerald-200 dark:border-emerald-700/50 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600
                                                    flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate text-sm">
                                                {{ $personaInstructor['nombre_completo'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                DNI: {{ $personaInstructor['dni'] }}
                                            </p>
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                                         bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                                Feligrés encontrado — listo para registrarse como instructor
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-2 border-t border-emerald-100 dark:border-emerald-800/40">
                                        <button type="button"
                                                wire:click="cancelarCrearInstructor"
                                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                                                       bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                                       text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            Cancelar
                                        </button>

                                        <button type="button"
                                                wire:click="guardarInstructorDesdePersonaExistente"
                                                wire:loading.attr="disabled"
                                                wire:target="guardarInstructorDesdePersonaExistente"
                                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                                       shadow-md shadow-emerald-500/30 transition-all
                                                       bg-gradient-to-r from-emerald-500 to-emerald-600
                                                       hover:from-emerald-600 hover:to-emerald-700
                                                       text-white disabled:opacity-60">
                                            <span wire:loading.remove wire:target="guardarInstructorDesdePersonaExistente">
                                                Registrar instructor
                                            </span>
                                            <span wire:loading wire:target="guardarInstructorDesdePersonaExistente">
                                                Guardando...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if($instructor_estado === 'sin_persona')
                                <div class="rounded-xl border border-emerald-200 dark:border-emerald-700/50
                                            bg-gradient-to-b from-emerald-50/80 to-transparent
                                            dark:from-emerald-900/15 dark:to-transparent overflow-hidden">

                                    <div class="flex items-center justify-between px-5 py-3
                                                border-b border-emerald-100 dark:border-emerald-800/40
                                                bg-emerald-50 dark:bg-emerald-900/20">
                                        <h4 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                            </svg>
                                            Crear persona e instructor
                                        </h4>
                                        <button type="button" wire:click="cancelarCrearInstructor"
                                                class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                                       hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="p-5 space-y-5">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3
                                                       flex items-center gap-2
                                                       before:content-[''] before:flex-1 before:h-px before:bg-gray-200 dark:before:bg-gray-700
                                                       after:content-[''] after:flex-1 after:h-px after:bg-gray-200 dark:after:bg-gray-700">
                                                Datos Personales
                                            </p>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="sm:col-span-2">
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Número de Identidad <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text"
                                                           wire:model="i_dni"
                                                           inputmode="numeric"
                                                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                  @error('i_dni') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                                    @error('i_dni')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Primer Nombre <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text"
                                                           wire:model="i_primer_nombre"
                                                           oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'')"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                  @error('i_primer_nombre') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                                    @error('i_primer_nombre')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Segundo Nombre
                                                    </label>
                                                    <input type="text"
                                                           wire:model="i_segundo_nombre"
                                                           oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'')"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Primer Apellido <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text"
                                                           wire:model="i_primer_apellido"
                                                           oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'')"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                  @error('i_primer_apellido') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                                    @error('i_primer_apellido')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Segundo Apellido
                                                    </label>
                                                    <input type="text"
                                                           wire:model="i_segundo_apellido"
                                                           oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s']/g,'')"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Fecha de Nacimiento
                                                    </label>
                                                    <input type="date" wire:model="i_fecha_nacimiento"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                  @error('i_fecha_nacimiento') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                                    @error('i_fecha_nacimiento')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Sexo
                                                    </label>
                                                    <select wire:model="i_sexo"
                                                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                   border border-gray-300 dark:border-gray-600
                                                                   bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                   @error('i_sexo') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror">
                                                        <option value="">Seleccionar...</option>
                                                        <option value="M">Masculino</option>
                                                        <option value="F">Femenino</option>
                                                    </select>
                                                    @error('i_sexo')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Teléfono
                                                    </label>
                                                    <input type="text"
                                                           wire:model="i_telefono"
                                                           inputmode="numeric"
                                                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                  @error('i_telefono') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                                    @error('i_telefono')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 tracking-wide">
                                                        Correo Electrónico
                                                    </label>
                                                    <input type="email" wire:model="i_email"
                                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                                  border border-gray-300 dark:border-gray-600
                                                                  bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                                  @error('i_email') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                                    @error('i_email')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-3 pt-2 border-t border-emerald-100 dark:border-emerald-800/40">
                                            <button type="button"
                                                    wire:click="cancelarCrearInstructor"
                                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                                                           bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                                                           text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                Cancelar
                                            </button>

                                            <button type="button"
                                                    wire:click="guardarMiniInstructorPersona"
                                                    wire:loading.attr="disabled"
                                                    wire:target="guardarMiniInstructorPersona"
                                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                                           shadow-md shadow-emerald-500/30 transition-all
                                                           bg-gradient-to-r from-emerald-500 to-emerald-600
                                                           hover:from-emerald-600 hover:to-emerald-700
                                                           text-white disabled:opacity-60">
                                                <svg wire:loading wire:target="guardarMiniInstructorPersona"
                                                     class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                                </svg>
                                                <span wire:loading.remove wire:target="guardarMiniInstructorPersona">Guardar instructor</span>
                                                <span wire:loading wire:target="guardarMiniInstructorPersona">Guardando...</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between">
        @if($paso > 1)
            <button wire:click="anteriorPaso"
                    type="button"
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
                    type="button"
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
                    type="button"
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