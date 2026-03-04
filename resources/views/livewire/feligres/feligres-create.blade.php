<div class="space-y-6">

    {{-- ══ HEADER ══════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600
                dark:from-emerald-700 dark:to-teal-700 shadow-md px-6 py-5">
        {{-- Decorative circles --}}
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Registrar Nuevo Feligrés</h1>
                    <p class="text-emerald-100 text-sm mt-0.5">
                        Busca una persona por nombre o DNI, o créala si no existe en la base de datos.
                    </p>
                </div>
            </div>

            <a href="{{ route('feligres.index') }}"
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

    {{-- Flash: persona nueva --}}
    @if (session()->has('persona_nueva'))
        <div class="flex items-center gap-3 p-4 rounded-xl
                    bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-700/60
                    shadow-sm">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-emerald-800 dark:text-emerald-200 font-medium text-sm">{{ session('persona_nueva') }}</p>
        </div>
    @endif

    {{-- ══ PASO 1: BUSCAR / SELECCIONAR PERSONA ═════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        {{-- Card header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300
                         text-xs font-bold ring-2 ring-emerald-200 dark:ring-emerald-700/50">1</span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Persona
            </h2>
        </div>

        <div class="p-6">

            {{-- ── Persona ya seleccionada ────────────────────────────── --}}
            @if ($personaSeleccionada)
                <div class="flex items-center justify-between p-4 rounded-xl
                            bg-emerald-50 dark:bg-emerald-900/20
                            border border-emerald-200 dark:border-emerald-700/50">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600
                                    flex items-center justify-center flex-shrink-0 shadow-sm">
                            <span class="text-white font-bold text-sm">
                                {{ strtoupper(substr($personaSeleccionada['nombre_completo'], 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                {{ $personaSeleccionada['nombre_completo'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                DNI: {{ $personaSeleccionada['dni'] }}
                                @if ($personaSeleccionada['telefono'])
                                    <span class="mx-1 opacity-40">·</span>{{ $personaSeleccionada['telefono'] }}
                                @endif
                                @if ($personaSeleccionada['email'])
                                    <span class="mx-1 opacity-40">·</span>{{ $personaSeleccionada['email'] }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="limpiarPersona"
                            class="ml-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                                   text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20
                                   hover:bg-red-100 dark:hover:bg-red-900/40
                                   border border-red-200 dark:border-red-800/50 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cambiar
                    </button>
                </div>

            {{-- ── Buscador + resultados ────────────────────────────────── --}}
            @else
                {{-- Input de búsqueda --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg wire:loading.remove wire:target="search"
                             class="h-4 w-4 text-gray-400 dark:text-gray-500"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg wire:loading wire:target="search"
                             class="h-4 w-4 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </div>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar por nombre, apellido o DNI…"
                           autocomplete="off"
                           class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-lg
                                  border border-gray-300 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/60
                                  text-gray-900 dark:text-white dark:placeholder-gray-400
                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  transition-colors" />
                </div>

                @error('persona_id')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror

                {{-- Resultados inline --}}
                @if (strlen(trim($search)) >= 2)
                    @if ($this->resultados->count() > 0)
                        <div class="mt-3 rounded-xl border border-gray-200 dark:border-gray-600/60 overflow-hidden shadow-sm">
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                @foreach ($this->resultados as $p)
                                    <li>
                                        <button type="button"
                                                wire:click="seleccionarPersona({{ $p->id }})"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left
                                                       hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors group">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600
                                                        flex items-center justify-center flex-shrink-0 shadow-sm">
                                                <span class="text-white font-bold text-sm">
                                                    {{ strtoupper(substr($p->primer_nombre, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $p->nombre_completo }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    DNI: {{ $p->dni }}
                                                    @if ($p->telefono) <span class="mx-1 opacity-40">·</span>{{ $p->telefono }} @endif
                                                    @if ($p->email) <span class="mx-1 opacity-40">·</span>{{ $p->email }} @endif
                                                </p>
                                            </div>
                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex-shrink-0
                                                         opacity-0 group-hover:opacity-100 transition-opacity">
                                                Seleccionar →
                                            </span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            {{-- Opción crear al pie --}}
                            <div class="flex items-center gap-2 px-4 py-2.5
                                        bg-gray-50 dark:bg-gray-900/40
                                        border-t border-gray-100 dark:border-gray-700/50">
                                <span class="text-xs text-gray-400 dark:text-gray-500">¿No aparece?</span>
                                <button type="button"
                                        wire:click="toggleCrearPersona"
                                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                                    Crear nueva persona
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- Sin resultados --}}
                        <div class="mt-3 flex items-start justify-between gap-3 p-4 rounded-xl
                                    bg-amber-50 dark:bg-amber-900/20
                                    border border-amber-200 dark:border-amber-700/50">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                        No se encontró ninguna persona
                                    </p>
                                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">
                                        No se encontró ning una persona con <strong>"{{ $search }}"</strong>.
                                        ¿Deseas crear una nueva?
                                    </p>
                                </div>
                            </div>
                            <button type="button"
                                    wire:click="toggleCrearPersona"
                                    class="flex-shrink-0 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700
                                           text-white text-xs font-semibold transition-colors shadow-sm">
                                Crear Nueva Persona
                            </button>
                        </div>
                    @endif
                @endif

                {{-- ── Form crear persona ──────────────────────────────── --}}
                @if ($showCrearPersona)
                    <div class="mt-5 rounded-xl border border-emerald-200 dark:border-emerald-700/50
                                bg-gradient-to-b from-emerald-50/80 to-transparent
                                dark:from-emerald-900/15 dark:to-transparent
                                overflow-hidden">

                        {{-- Sub-header del form --}}
                        <div class="flex items-center justify-between px-5 py-3
                                    border-b border-emerald-100 dark:border-emerald-800/40
                                    bg-emerald-50 dark:bg-emerald-900/20">
                            <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Nueva Persona
                            </h3>
                            <button type="button"
                                    wire:click="toggleCrearPersona"
                                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200
                                           hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                {{-- DNI --}}
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Número de Identidad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.defer="p_dni"
                                           placeholder="Ej: 0801199912345"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white placeholder-gray-400
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                  @error('p_dni') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                    @error('p_dni')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Primer Nombre --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Primer Nombre <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.defer="p_primer_nombre"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                  @error('p_primer_nombre') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                    @error('p_primer_nombre')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Segundo Nombre --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Segundo Nombre
                                    </label>
                                    <input type="text"
                                           wire:model.defer="p_segundo_nombre"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- Primer Apellido --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Primer Apellido <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.defer="p_primer_apellido"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                                  @error('p_primer_apellido') border-red-400 bg-red-50 dark:bg-red-900/10 @enderror" />
                                    @error('p_primer_apellido')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Segundo Apellido --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Segundo Apellido
                                    </label>
                                    <input type="text"
                                           wire:model.defer="p_segundo_apellido"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                </div>

                                {{-- Teléfono --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Teléfono
                                    </label>
                                    <input type="text"
                                           wire:model.defer="p_telefono"
                                           placeholder="+504 0000-0000"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white placeholder-gray-400
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                    @error('p_telefono')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Email
                                    </label>
                                    <input type="email"
                                           wire:model.defer="p_email"
                                           placeholder="ejemplo@correo.com"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white placeholder-gray-400
                                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                    @error('p_email')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-2 border-t border-emerald-100 dark:border-emerald-800/40">
                                <button type="button"
                                        wire:click="crearPersona"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                                               bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800
                                               disabled:opacity-60 disabled:cursor-not-allowed
                                               text-white text-sm font-semibold shadow-sm transition-all">
                                    <svg wire:loading wire:target="crearPersona" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                    </svg>
                                    <svg wire:loading.remove wire:target="crearPersona" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    <span wire:loading.remove wire:target="crearPersona">Guardar Persona</span>
                                    <span wire:loading wire:target="crearPersona">Guardando…</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>

    {{-- ══ PASO 2: DATOS DEL FELIGRÉS ════════════════════════════════════ --}}
    <div class="rounded-xl shadow-sm border overflow-hidden transition-all duration-300
                @if($persona_id)
                    bg-white dark:bg-gray-800/80 border-gray-200 dark:border-gray-700/60 ring-1 ring-black/5 dark:ring-white/5
                @else
                    bg-gray-50 dark:bg-gray-800/40 border-gray-200 dark:border-gray-700/40 opacity-60 pointer-events-none
                @endif">

        {{-- Card header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60
                    @if($persona_id) bg-white dark:bg-gray-800/80 @else bg-gray-50 dark:bg-gray-800/40 @endif">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                         ring-2 transition-all
                         @if($persona_id)
                             bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 ring-emerald-200 dark:ring-emerald-700/50
                         @else
                             bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 ring-gray-200 dark:ring-gray-600
                         @endif">2</span>
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-semibold tracking-wide uppercase
                           @if($persona_id) text-gray-800 dark:text-gray-100 @else text-gray-400 dark:text-gray-500 @endif">
                    Datos del Feligrés
                </h2>
                @if (!$persona_id)
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-normal normal-case tracking-normal">
                        — selecciona una persona primero
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Iglesia --}}
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Iglesia <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="id_iglesia"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60
                                   text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                   @error('id_iglesia') border-red-400 @enderror">
                        <option value="">Seleccionar Iglesia</option>
                        @foreach ($iglesias as $ig)
                            <option value="{{ $ig->id }}">{{ $ig->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_iglesia')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="estado"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60
                                   text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                   @error('estado') border-red-400 @enderror">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Ingreso --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Fecha de Ingreso
                    </label>
                    <input type="date"
                           wire:model="fecha_ingreso"
                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700/60
                                  text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  @error('fecha_ingreso') border-red-400 @enderror" />
                    @error('fecha_ingreso')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── Barra de acciones ─────────────────────────────────── --}}
            <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('feligres.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                          bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                          text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>

                <button type="button"
                        wire:click="guardar"
                        wire:loading.attr="disabled"
                        @disabled(!$persona_id)
                        class="inline-flex items-center gap-2.5 px-7 py-2.5 rounded-lg text-sm font-bold
                               shadow-md shadow-emerald-500/30 transition-all duration-150
                               bg-gradient-to-r from-emerald-500 to-emerald-600
                               hover:from-emerald-600 hover:to-emerald-700
                               active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none
                               text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    <svg wire:loading wire:target="guardar" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="guardar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span wire:loading.remove wire:target="guardar">Guardar Feligrés</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>