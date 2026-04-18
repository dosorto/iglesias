
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-amber-600 to-orange-600
                dark:from-amber-700 dark:to-orange-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Registrar Nuevo Instructor</h1>
                    <p class="text-amber-100 text-sm mt-0.5">Busca una persona por nombre o DNI, o créala si no existe</p>
                </div>
            </div>
            <a href="{{ route('instructor.index') }}"
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
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('persona_nueva') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>

        <p class="text-red-800 dark:text-red-200 font-medium">
            {{ session('error') }}
        </p>
    </div>
    @endif

    {{-- SECCIÓN 1: BUSCAR / SELECCIONAR PERSONA --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300
                         text-xs font-bold ring-2 ring-amber-200 dark:ring-amber-700/50">1</span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">Persona</h2>
        </div>

        <div class="p-6">

            {{-- Estado: ENCONTRADO --}}
            @if ($persona_estado === 'found' && $personaSeleccionada)
                <div class="flex items-center justify-between p-4 rounded-xl
                            bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50">
                    <div class="flex items-center gap-3">
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
                                @if ($personaSeleccionada['telefono']) &nbsp;·&nbsp; {{ $personaSeleccionada['telefono'] }} @endif
                                @if ($personaSeleccionada['email']) &nbsp;·&nbsp; {{ $personaSeleccionada['email'] }} @endif
                            </p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                         bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                                &#x2713; Seleccionada
                            </span>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="limpiarPersona"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                   text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30
                                   border border-red-200 dark:border-red-800/40 transition-all">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cambiar
                    </button>
                </div>

            {{-- Estado: IDLE --}}
            @elseif ($persona_estado === 'idle')
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                            </svg>
                        </div>
                        <input type="text"
                               wire:model="persona_dni"
                               wire:keydown.enter="buscarPersona"
                               placeholder="Ingresa el DNI o nombre de la persona..."
                               autocomplete="off"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-lg transition-colors
                                      border border-gray-300 dark:border-gray-600
                                      bg-gray-50 dark:bg-gray-700/60
                                      text-gray-900 dark:text-white dark:placeholder-gray-400
                                      focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                    </div>
                    <button type="button"
                            wire:click="buscarPersona"
                            wire:loading.attr="disabled"
                            wire:target="buscarPersona"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                                   bg-amber-600 hover:bg-amber-700 text-white shadow-sm transition-all disabled:opacity-60">
                        <svg wire:loading.remove wire:target="buscarPersona"
                             class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg wire:loading wire:target="buscarPersona"
                             class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        Buscar
                    </button>
                </div>
                @error('persona_dni')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                @error('persona_id')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

            {{-- Estado: NO ENCONTRADO --}}
            @elseif ($persona_estado === 'sin_persona')
                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-4 rounded-xl
                                bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-red-800 dark:text-red-300">
                            No se encontró ninguna persona con el criterio <strong>{{ $persona_dni }}</strong>.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <input type="text"
                               wire:model="persona_dni"
                               wire:keydown.enter="buscarPersona"
                               autocomplete="off"
                               placeholder="Ingresa el DNI o nombre de la persona..."
                               class="flex-1 px-3 py-2 text-sm rounded-lg transition-colors
                                      border border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                      focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                        <button type="button"
                                wire:click="buscarPersona"
                                class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            Buscar
                        </button>
                    </div>

                    @if (! $showCrearPersona)
                        <button type="button"
                                wire:click="abrirCrearPersona"
                                class="w-full py-2.5 text-sm font-semibold
                                       text-emerald-700 dark:text-emerald-300
                                       border border-emerald-300 dark:border-emerald-600 rounded-xl
                                       hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">
                            + Crear Nueva Persona
                        </button>
                    @endif
                </div>
            @endif

            {{-- Form crear persona (visible desde sin_persona) --}}
            @if ($showCrearPersona)
                <div class="mt-4 p-5 rounded-xl border border-emerald-200 dark:border-emerald-800
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
                                wire:click="cancelarCrearPersona"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

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
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('p_dni') border-red-400 @enderror" />
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
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('p_primer_nombre') border-red-400 @enderror" />
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
                                              bg-gray-50 dark:bg-gray-700/60
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
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('p_primer_apellido') border-red-400 @enderror" />
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
                                              bg-gray-50 dark:bg-gray-700/60
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
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white
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
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                @error('p_email')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha de Nacimiento --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date"
                                       wire:model.defer="p_fecha_nacimiento"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60
                                              text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                @error('p_fecha_nacimiento')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Sexo --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Sexo
                                </label>
                                <select wire:model.defer="p_sexo"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                               border border-gray-300 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700/60
                                               text-gray-900 dark:text-white
                                               focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="">— Seleccionar —</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                @error('p_sexo')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button type="button"
                                    wire:click="crearPersona"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold
                                           text-white shadow-md shadow-emerald-500/30 transition-all
                                           bg-gradient-to-r from-emerald-500 to-emerald-600
                                           hover:from-emerald-600 hover:to-emerald-700
                                           disabled:opacity-60 disabled:cursor-not-allowed">
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
        </div>
    </div>

    {{-- SECCIÓN 2: DATOS DEL INSTRUCTOR --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border ring-1 ring-black/5 dark:ring-white/5
                {{ $persona_id ? 'border-gray-200 dark:border-gray-700/60' : 'border-gray-200 dark:border-gray-700/60 opacity-60 pointer-events-none' }}">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         {{ $persona_id ? 'bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 ring-2 ring-amber-200 dark:ring-amber-700/50' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500' }}
                         text-xs font-bold">2</span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Datos del Instructor
            </h2>
            @if (!$persona_id)
                <span class="text-xs font-normal text-gray-400 dark:text-gray-500">— selecciona una persona primero</span>
            @endif
        </div>

        <div class="p-6">
            @if($persona_id && empty($personaSeleccionada['email']))
                <div class="mb-5 rounded-xl border border-amber-200 dark:border-amber-700/50 bg-amber-50 dark:bg-amber-900/20 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="w-full">
                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">
                                Esta persona no tiene correo electronico.
                            </p>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                Antes de guardar el instructor, elige si deseas configurarle un correo manual o generarlo con la nomenclatura del sistema.
                            </p>

                            <div class="mt-3 space-y-2">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="radio" wire:model.live="emailProvisionMode" value="manual" class="text-amber-600 focus:ring-amber-500" />
                                    Configurar correo manualmente
                                </label>

                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="radio" wire:model.live="emailProvisionMode" value="generate" class="text-amber-600 focus:ring-amber-500" />
                                    Generar correo automaticamente
                                </label>
                            </div>

                            @if($emailProvisionMode === 'manual')
                                <div class="mt-3">
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                        Correo del Instructor
                                    </label>
                                    <input type="email"
                                           wire:model.defer="emailManual"
                                           placeholder="instructor@correo.com"
                                           class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                                  border border-gray-300 dark:border-gray-600
                                                  bg-white dark:bg-gray-700/60
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                                  @error('emailManual') border-red-400 @enderror" />
                                    @error('emailManual')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @if($emailProvisionMode === 'generate')
                                <p class="mt-3 text-xs text-emerald-700 dark:text-emerald-300">
                                    Se generara: <strong>{{ $this->emailSugerido }}</strong>
                                </p>
                            @endif

                            @error('emailProvisionMode')
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Firma Principal --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                            Firma Principal
                        </label>

                        <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 px-6 py-10">
                            <div class="text-center">

                                {{-- Icono --}}
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7l-1.41-1.41a2 2 0 00-2.83 0L4 13.34V16h2.66l7.76-7.76a2 2 0 000-2.83z"/>
                                </svg>

                                {{-- Texto --}}
                                <div class="mt-3 flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label class="relative cursor-pointer rounded-md font-semibold text-amber-600 hover:text-amber-700">
                                        <span>Haz clic para subir la firma</span>
                                        <input type="file" class="sr-only" wire:model="firma" accept="image/*">
                                    </label>
                                </div>

                                <p class="text-xs text-gray-500 mt-1">
                                    PNG &nbsp; JPG &nbsp; JPEG &nbsp; Máx. 2MB
                                </p>

                                {{-- Vista previa --}}
                                @if ($firma)
                                    <div class="mt-4 flex justify-center">
                                        <img src="{{ $firma->temporaryUrl() }}"
                                            class="h-20 object-contain rounded border border-gray-200 bg-white p-2">
                                    </div>
                                @endif

                            </div>
                        </div>

                        @error('firma')
                            <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

            {{-- Barra de acciones --}}
            <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('instructor.index') }}"
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
                               shadow-md shadow-emerald-500/30 transition-all duration-150 active:scale-[0.98]
                               bg-gradient-to-r from-emerald-500 to-emerald-600
                               hover:from-emerald-600 hover:to-emerald-700
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none
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
                    <span wire:loading.remove wire:target="guardar">Guardar Instructor</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>