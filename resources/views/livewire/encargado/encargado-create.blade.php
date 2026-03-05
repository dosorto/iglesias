<div class="space-y-6">

    {{-- HEADER --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600
                dark:from-indigo-700 dark:to-violet-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Registrar Nuevo Encargado</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">Busca una persona por nombre o DNI, o créala si no existe</p>
                </div>
            </div>
            <a href="{{ route('encargado.index') }}"
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

    {{-- SECCIÓN 1: BUSCAR / SELECCIONAR PERSONA --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300
                         text-xs font-bold ring-2 ring-indigo-200 dark:ring-indigo-700/50">1</span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">Persona</h2>
        </div>

        <div class="p-6">

            {{-- Persona ya seleccionada --}}
            @if ($personaSeleccionada)
                <div class="flex items-center justify-between p-4 rounded-xl
                            bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600
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

            @else
                {{-- Buscador --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg wire:loading.remove wire:target="search"
                             class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg wire:loading wire:target="search"
                             class="h-4 w-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </div>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar por nombre, apellido o DNI…"
                           autocomplete="off"
                           class="block w-full pl-9 pr-3 py-2.5 text-sm rounded-lg transition-colors
                                  border border-gray-300 dark:border-gray-600
                                  bg-gray-50 dark:bg-gray-700/60
                                  text-gray-900 dark:text-white dark:placeholder-gray-400
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                </div>

                @error('persona_id')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror

                @if (strlen(trim($search)) >= 2)
                    @if ($this->resultados->count() > 0)
                        <div class="mt-3 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($this->resultados as $p)
                                    <li>
                                        <button type="button"
                                                wire:click="seleccionarPersona({{ $p->id }})"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left
                                                       hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600
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
                                                    @if ($p->telefono) &nbsp;·&nbsp; {{ $p->telefono }} @endif
                                                    @if ($p->email) &nbsp;·&nbsp; {{ $p->email }} @endif
                                                </p>
                                            </div>
                                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold flex-shrink-0">
                                                Seleccionar
                                            </span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-900/40
                                        border-t border-gray-100 dark:border-gray-700">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs text-gray-500 dark:text-gray-400">¿No aparece?</span>
                                <button type="button"
                                        wire:click="toggleCrearPersona"
                                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                                    Crear nueva persona
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="mt-3 flex items-start gap-3 p-4 rounded-xl
                                    bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-amber-800 dark:text-amber-300">
                                No se encontró ninguna persona con <strong>"{{ $search }}"</strong>.
                                Completa el formulario de abajo para crearla.
                            </p>
                        </div>
                    @endif
                @endif

                {{-- Form crear persona --}}
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
                                    wire:click="toggleCrearPersona"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Número de Identidad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model.defer="p_dni" placeholder="Ej: 0801199912345"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('p_dni') border-red-400 @enderror" />
                                @error('p_dni') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Primer Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model.defer="p_primer_nombre"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('p_primer_nombre') border-red-400 @enderror" />
                                @error('p_primer_nombre') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Segundo Nombre
                                </label>
                                <input type="text" wire:model.defer="p_segundo_nombre"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Primer Apellido <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model.defer="p_primer_apellido"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                              @error('p_primer_apellido') border-red-400 @enderror" />
                                @error('p_primer_apellido') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Segundo Apellido
                                </label>
                                <input type="text" wire:model.defer="p_segundo_apellido"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Teléfono
                                </label>
                                <input type="text" wire:model.defer="p_telefono"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                @error('p_telefono') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Email
                                </label>
                                <input type="email" wire:model.defer="p_email"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                @error('p_email') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" wire:model.defer="p_fecha_nacimiento"
                                       class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                              border border-gray-300 dark:border-gray-600
                                              bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                              focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
                                @error('p_fecha_nacimiento') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                    Sexo
                                </label>
                                <select wire:model.defer="p_sexo"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                               border border-gray-300 dark:border-gray-600
                                               bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white
                                               focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="">— Seleccionar —</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                @error('p_sexo') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
            @endif
        </div>
    </div>

    {{-- SECCIÓN 2: DATOS DEL ENCARGADO --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border ring-1 ring-black/5 dark:ring-white/5
                {{ $persona_id ? 'border-gray-200 dark:border-gray-700/60' : 'border-gray-200 dark:border-gray-700/60 opacity-60 pointer-events-none' }}">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         {{ $persona_id ? 'bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 ring-2 ring-indigo-200 dark:ring-indigo-700/50' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500' }}
                         text-xs font-bold">2</span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Datos del Encargado
            </h2>
            @if (!$persona_id)
                <span class="text-xs font-normal text-gray-400 dark:text-gray-500">— selecciona una persona primero</span>
            @endif
        </div>

        <div class="p-6">

            {{-- Firma --}}
            <div class="max-w-sm">
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                    Firma Principal
                </label>
                <input type="file"
                       wire:model="firma"
                       accept="image/*"
                       class="block w-full text-sm text-gray-700 dark:text-gray-300
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              dark:file:bg-indigo-900/30 dark:file:text-indigo-300
                              hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50
                              transition-colors" />
                @error('firma')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                @if ($firma)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Vista previa:</p>
                        <img src="{{ $firma->temporaryUrl() }}" alt="Vista previa firma"
                             class="h-16 object-contain rounded border border-indigo-200 dark:border-indigo-700 bg-white p-1">
                    </div>
                @endif
            </div>

            {{-- Barra de acciones --}}
            <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <a href="{{ route('encargado.index') }}"
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
                    <span wire:loading.remove wire:target="guardar">Guardar Encargado</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>
