<div class="space-y-6">

    {{-- ══ HEADER ══════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600
                dark:from-emerald-700 dark:to-teal-700 shadow-md px-6 py-5">
        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white leading-tight">Editar Feligrés</h1>
                    <p class="text-emerald-100 text-sm mt-0.5">
                        Modifica los datos del feligrés registrado.
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

    {{-- ══ PASO 1: PERSONA ════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/60
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full
                         bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300
                         text-xs font-bold ring-2 ring-emerald-200 dark:ring-emerald-700/50">1</span>
            <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100 tracking-wide uppercase">
                Persona
            </h2>
        </div>

        <div class="p-6">

            {{-- Persona seleccionada --}}
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
                                {{ trim(($primer_nombre ?? '') . ' ' . ($segundo_nombre ?? '') . ' ' . ($primer_apellido ?? '') . ' ' . ($segundo_apellido ?? '')) ?: $personaSeleccionada['nombre_completo'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                DNI: {{ $dni ?: $personaSeleccionada['dni'] }}
                                @if ($telefono)
                                    <span class="mx-1 opacity-40">·</span>{{ $telefono }}
                                @endif
                                @if ($email)
                                    <span class="mx-1 opacity-40">·</span>{{ $email }}
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

                <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700/60">
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <h3 class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Editar datos de persona
                        </h3>
                        <span class="text-[11px] text-gray-400 dark:text-gray-500">Se guardarán junto al feligrés</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                DNI <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="dni"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('dni') border-red-400 @enderror" />
                            @error('dni')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Sexo <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="sexo"
                                    class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                           border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                           focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                           @error('sexo') border-red-400 @enderror">
                                <option value="">Seleccione...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            @error('sexo')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Primer nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="primer_nombre"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('primer_nombre') border-red-400 @enderror" />
                            @error('primer_nombre')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Segundo nombre
                            </label>
                            <input type="text"
                                   wire:model="segundo_nombre"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('segundo_nombre') border-red-400 @enderror" />
                            @error('segundo_nombre')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Primer apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="primer_apellido"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('primer_apellido') border-red-400 @enderror" />
                            @error('primer_apellido')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Segundo apellido
                            </label>
                            <input type="text"
                                   wire:model="segundo_apellido"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('segundo_apellido') border-red-400 @enderror" />
                            @error('segundo_apellido')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Fecha de nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   wire:model="fecha_nacimiento"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('fecha_nacimiento') border-red-400 @enderror" />
                            @error('fecha_nacimiento')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Teléfono
                            </label>
                            <input type="text"
                                   wire:model="telefono"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('telefono') border-red-400 @enderror" />
                            @error('telefono')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                                Correo electrónico
                            </label>
                            <input type="email"
                                   wire:model="email"
                                   class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                          border border-gray-300 dark:border-gray-600
                                          bg-white dark:bg-gray-700/60 text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                          @error('email') border-red-400 @enderror" />
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            {{-- Buscador --}}
            @else
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
                           placeholder="Buscar persona por nombre, apellido o DNI…"
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
                        </div>
                    @else
                        <div class="mt-3 flex items-center gap-3 p-4 rounded-xl
                                    bg-amber-50 dark:bg-amber-900/20
                                    border border-amber-200 dark:border-amber-700/50">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-amber-800 dark:text-amber-300">
                                No se encontró ninguna persona con <strong>"{{ $search }}"</strong>.
                            </p>
                        </div>
                    @endif
                @endif
            @endif

        </div>
    </div>

    {{-- ══ PASO 2: DATOS DEL FELIGRÉS ════════════════════════════════ --}}
    <div class="rounded-xl shadow-sm border overflow-hidden transition-all duration-300
                @if($persona_id)
                    bg-white dark:bg-gray-800/80 border-gray-200 dark:border-gray-700/60 ring-1 ring-black/5 dark:ring-white/5
                @else
                    bg-gray-50 dark:bg-gray-800/40 border-gray-200 dark:border-gray-700/40 opacity-60 pointer-events-none
                @endif">

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

                {{-- Parroquia --}}
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">
                        Parroquia <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="id_iglesia"
                            class="w-full px-3 py-2.5 text-sm rounded-lg transition-colors
                                   border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700/60
                                   text-gray-900 dark:text-white
                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                   @error('id_iglesia') border-red-400 @enderror">
                        <option value="">Seleccionar Parroquia</option>
                        @foreach ($iglesias as $ig)
                            <option value="{{ $ig->id }}">{{ $ig->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_iglesia')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
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
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
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
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Barra de acciones --}}
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
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="guardar">Actualizar Feligrés</span>
                    <span wire:loading wire:target="guardar">Guardando…</span>
                </button>
            </div>
        </div>
    </div>

</div>
