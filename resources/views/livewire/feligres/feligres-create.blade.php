<div class="space-y-6">

    {{-- ══ HEADER ══════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Registrar Nuevo Feligrés</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Busca una persona por nombre o DNI, o créala si no existe</p>
        </div>

        <a href="{{ route('feligres.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                  text-gray-700 dark:text-gray-200 font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    {{-- Flash: persona nueva --}}
    @if (session()->has('persona_nueva'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('persona_nueva') }}</p>
        </div>
    @endif

    {{-- ══ PASO 1: BUSCAR / SELECCIONAR PERSONA ═════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                             bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-bold">1</span>
                Persona
            </h2>
        </div>

        <div class="p-6">

            {{-- ── Persona ya seleccionada ────────────────────────────── --}}
            @if ($personaSeleccionada)
                <div class="flex items-center justify-between p-4 rounded-lg
                            bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                    <div class="flex items-start gap-3">
                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">
                                {{ strtoupper(substr($personaSeleccionada['nombre_completo'], 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $personaSeleccionada['nombre_completo'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                DNI: {{ $personaSeleccionada['dni'] }}
                                @if ($personaSeleccionada['telefono'])
                                    &nbsp;·&nbsp; {{ $personaSeleccionada['telefono'] }}
                                @endif
                                @if ($personaSeleccionada['email'])
                                    &nbsp;·&nbsp; {{ $personaSeleccionada['email'] }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="limpiarPersona"
                            class="ml-4 text-sm text-red-500 hover:text-red-700 dark:hover:text-red-400
                                   font-medium transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cambiar
                    </button>
                </div>

            {{-- ── Buscador + resultados (inline, sin dropdown absoluto) ── --}}
            @else
                {{-- Input de búsqueda --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg wire:loading.remove wire:target="search"
                             class="h-4 w-4 text-gray-400 dark:text-gray-500"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg wire:loading wire:target="search"
                             class="h-4 w-4 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </div>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar por nombre, apellido o DNI…"
                           autocomplete="off"
                           class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" />
                </div>

                @error('persona_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                {{-- Resultados inline --}}
                @if (strlen(trim($search)) >= 2)
                    @if ($this->resultados->count() > 0)
                        {{-- Lista de coincidencias --}}
                        <div class="mt-3 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($this->resultados as $p)
                                    <li>
                                        <button type="button"
                                                wire:click="seleccionarPersona({{ $p->id }})"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left
                                                       hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">
                                            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
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
                                                    @if ($p->telefono) &nbsp;·&nbsp; {{ $p->telefono }} @endif
                                                    @if ($p->email) &nbsp;·&nbsp; {{ $p->email }} @endif
                                                </p>
                                            </div>
                                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium flex-shrink-0">
                                                Seleccionar
                                            </span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            {{-- Opción crear al pie --}}
                            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-900/40
                                        border-t border-gray-100 dark:border-gray-700">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    ¿No aparece?
                                </span>
                                <button type="button"
                                        wire:click="toggleCrearPersona"
                                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                                    Crear nueva persona
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- Sin resultados: aviso + form automático --}}
                        <div class="mt-3 flex items-start gap-3 p-4 rounded-lg
                                    bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-amber-800 dark:text-amber-300">
                                No se encontró ninguna persona con
                                <strong>"{{ $search }}"</strong>.
                                Completa el formulario de abajo para crearla.
                            </p>
                        </div>
                    @endif
                @endif

                {{-- ── Form crear persona (se muestra automáticamente al no haber resultados) ── --}}
                @if ($showCrearPersona)
                    <div class="mt-4 p-5 rounded-lg border border-emerald-200 dark:border-emerald-800
                                bg-emerald-50/50 dark:bg-emerald-900/10 space-y-4">

                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Nueva Persona
                            </h3>
                            <button type="button"
                                    wire:click="toggleCrearPersona"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- DNI --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Número de Identidad <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model.defer="p_dni"
                                       placeholder="Ej: 0801199912345"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white
                                              @error('p_dni') border-red-500 @enderror" />
                                @error('p_dni')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Primer Nombre --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Primer Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model.defer="p_primer_nombre"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white
                                              @error('p_primer_nombre') border-red-500 @enderror" />
                                @error('p_primer_nombre')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Segundo Nombre --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Segundo Nombre
                                </label>
                                <input type="text"
                                       wire:model.defer="p_segundo_nombre"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white" />
                            </div>

                            {{-- Primer Apellido --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Primer Apellido <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model.defer="p_primer_apellido"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white
                                              @error('p_primer_apellido') border-red-500 @enderror" />
                                @error('p_primer_apellido')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Segundo Apellido --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Segundo Apellido
                                </label>
                                <input type="text"
                                       wire:model.defer="p_segundo_apellido"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white" />
                            </div>

                            {{-- Teléfono --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                                <input type="text"
                                       wire:model.defer="p_telefono"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white" />
                                @error('p_telefono')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email"
                                       wire:model.defer="p_email"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              dark:bg-gray-700 dark:text-white" />
                                @error('p_email')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button type="button"
                                    wire:click="crearPersona"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60
                                           text-white text-sm font-medium transition-colors flex items-center gap-2">
                                <svg wire:loading wire:target="crearPersona" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                                <svg wire:loading.remove wire:target="crearPersona" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span wire:loading.remove wire:target="crearPersona">Guardar Persona</span>
                                <span wire:loading wire:target="crearPersona">Guardando…</span>
                            </button>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>

    {{-- ══ PASO 2: DATOS DEL FELIGRÉS ════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden
                @if(!$persona_id) opacity-60 pointer-events-none @endif">

        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full
                             bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-bold">2</span>
                Datos del Feligrés
                @if (!$persona_id)
                    <span class="text-xs font-normal text-gray-400 dark:text-gray-500">— selecciona una persona primero</span>
                @endif
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Iglesia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Iglesia <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="id_iglesia"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:text-white
                                   @error('id_iglesia') border-red-500 @enderror">
                        <option value="">Seleccione una iglesia…</option>
                        @foreach ($iglesias as $ig)
                            <option value="{{ $ig->id }}">{{ $ig->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_iglesia')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="estado"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:text-white
                                   @error('estado') border-red-500 @enderror">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de Ingreso --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha de Ingreso
                    </label>
                    <input type="date"
                           wire:model="fecha_ingreso"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white
                                  @error('fecha_ingreso') border-red-500 @enderror" />
                    @error('fecha_ingreso')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('feligres.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                          text-gray-700 dark:text-gray-200 font-medium transition-colors">
                    Cancelar
                </a>

                <button type="button"
                        wire:click="guardar"
                        wire:loading.attr="disabled"
                        @disabled(!$persona_id)
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700
                               disabled:opacity-50 disabled:cursor-not-allowed
                               text-white font-medium transition-colors flex items-center gap-2">
                    <svg wire:loading wire:target="guardar" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg wire:loading.remove wire:target="guardar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span wire:loading.remove wire:target="guardar">Guardar Feligrés</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>